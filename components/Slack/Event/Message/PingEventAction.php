<?php namespace Slack\Event\Message;

class PingEventAction extends DefaultEventAction {

    protected $trigger = true;
    protected $regex = '#^ping$#';

    public function run(){
        $this->message->reply("pong! (".round((microtime(true)-$this->event->init_time)*1000, 2)."ms)");
        throw new \Slack\Exception\StopProcessingException;
    }

}
