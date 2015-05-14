<?php namespace Slack\Event\Message;

class RollEventAction extends \Slack\Event\Message\DefaultEventAction {

    protected $trigger = true;
    protected $regex   = '#^roll\s(.*)#i';

    public function run(){
        $resp = "";
        $in = explode(' ', $this->matches[1]);
        $dice = array_map('trim', $in);
        if(count($dice) > 5){
            $this->message->reply("You can only do 5 rolls at a time.");
            throw new \Slack\Exception\StopProcessingException;
        }
        foreach($dice as $d){
            if(preg_match('#^[0-9]+d[0-9]+$#', $d) === 0){
                $this->message->reply("Invalid roll: $d");
                throw new \Slack\Exception\StopProcessingException;
            }
            $e = explode('d',$d);
            if($e[0] > 10){
                $this->message->reply("You can only roll 10 of each die at a time.");
                throw new \Slack\Exception\StopProcessingException;
            }
            if($e[1] < 1){
                $this->message->reply("quit trying to break the universe");
                throw new \Slack\Exception\StopProcessingException;
            }
            $resp .= "*$d*: ";
            for ($i=0;$i<$e[0];$i++){
                $resp .= rand(1, $e[1]).", ";
            }
            $resp = rtrim($resp, ', ').'; ';
        }
            $resp = rtrim($resp, '; ');
        $this->message->reply($resp);
       
    }

}
