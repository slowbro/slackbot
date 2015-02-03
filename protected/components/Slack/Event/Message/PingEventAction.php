<?php namespace Slack\Event\Message;

class PingEventAction extends DefaultEventAction {

    protected $trigger = true;
    protected $regex = '#^ping$#';

    public function run(){
        $this->message->reply("pong! (".(microtime(true)-$this->event->init_time)."s)");
        throw new \Slack\Exception\StopProcessingException;
    }

}
