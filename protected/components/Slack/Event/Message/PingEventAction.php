<?php namespace Slack\Event\Message;

class PingEventAction extends DefaultEventAction {

    protected $trigger = true;
    protected $regex = '#^ping$#';

    public function run(){
        $this->message->reply("pong!");
        throw new \Slack\Exception\StopProcessingException;
    }

}
