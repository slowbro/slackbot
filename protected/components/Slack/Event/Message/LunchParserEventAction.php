<?php namespace Slack\Event\Message;

class LunchParserEventAction extends DefaultEventAction {

    protected $regex = '#(what((\')?s| is)? for lunch|^lunch)([\sA-z]+)?(\?)?$#i';

    public function run(){
        $channel = $this->message->getChannel();
        $time = time();
        if(!empty($this->matches[4]))
            $time = strtotime(preg_replace('#^\s?(on)\s?#','',$this->matches[4]));
        if(!$time){
            $channel->message("What do you mean, '".trim($this->matches[4])."'..?");
            throw new \Slack\Exception\StopProcessingException;
        }
        $lunch = \Lunch::model()->find('day=:day', array('day'=>date('mdY', $time)));
        if(!$lunch){
            $channel->message("I don't know :(");
            throw new \Slack\Exception\StopProcessingException;
        }
        $channel->message((@$this->matches[4]?date('l, M d', $time).' lunch':'Lunch today')." is.. ".$lunch->food);
        throw new \Slack\Exception\StopProcessingException;
    }

}
