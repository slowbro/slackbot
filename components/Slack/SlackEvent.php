<?php namespace Slack;

class SlackEvent {

    public $slack;
    public $init_time;

    private $data;
    private $_json;

    public function __construct(){
        $this->init_time = microtime(true);
        $this->slack  = Slack::factory();
    }

    public function __set($name, $value){
        $this->data->$name = $value;
    }

    public function __get($name){
        if (property_exists($this->data, $name)) {
            return $this->data->$name;
        }
        return null;
    }

    public function __isset($name){
        return isset($this->data->$name);
    }

    public function __unset($name){
        unset($this->data->$name);
    }

    public function parse($messageJson){
        $this->_json = $messageJson;
        $logger = $this->slack->getLogger();
        $this->data = json_decode($messageJson);
        if(!$this->data){
            $logger->error("Received invalid message: $messageJson");
            return false;
        }

        if(!isset($this->data->type)){
            if($this->data->ok == false)
                var_dump($this->data);
            return true;
        }

        $baseDir = \Yii::getAlias("@slackbot/components/Slack/Event/".ucfirst($this->data->type).(isset($this->data->subtype)?'/'.ucfirst($this->data->subtype):''));
        if(!file_exists($baseDir)){
            $logger->debug("No actions for ".$this->data->type.(isset($this->data->subtype)?':'.$this->data->subtype:''));
            $logger->debug($messageJson);
            return false;
        }

        # do init first
        if(file_exists($baseDir.'/InitEventAction.php')){
            $class = "Slack\Event\\".ucfirst($this->data->type).(isset($this->data->subtype)?'\\'.ucfirst($this->data->subtype):'')."\InitEventAction";
            try {
                $action = new $class($this);
                $action->run();
            } catch (Exception $e){
                $logger->info("Unhandled Exception: ".get_class($e)."; ".$e->getMessage());
            } finally {
                $action = null;
            }
        }

        # now do the rest
        # load the classes first so we can sort them...
        $classes = glob($baseDir."/*EventAction.php");
        $class_array = [];
        foreach($classes as $name){
            $name = str_replace($baseDir.'/', '', str_replace('.php','',$name));
            if(in_array($name,["InitEventAction", "DefaultEventAction"]))
                continue;
            $class = "Slack\Event\\".ucfirst($this->data->type)."\\$name";
            $class_array[] = ['class'=>$class,'sort'=>$class::$sort];
        }
        usort($class_array, function($a,$b){return $a['sort']-$b['sort'];});

        # run the sorted clases
        foreach($class_array as $ca){
            $class = $ca['class'];
            try {
                $action = new $class($this);
                $action->run();
            } catch (\Exception $e){
                if($e instanceof \Slack\Exception\StopProcessingException)
                    break;
                if($e instanceof \Slack\Exception\SkipActionException)
                    continue;
                $logger->info("$name Unhandled Exception: ".$e->getMessage());
            } finally {
                $action = null;
            }
        }
        return true;
    }

    private function channel_rename($event){
        $state = SlackState::getState();
        $channel = $state->findChannelById($event->channel->id);
        $old_name = $channel->name;
        $channel->update((array)$event->channel);
    }

    public function asObject(){
        return $this->data;
    }

    public function asJson(){
        return $this->_json;
    }
}
