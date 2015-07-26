<?php namespace Event\Message;

class PingAction extends \Slowbro\Slack\Event\Message\BaseAction {

    protected $trigger = true;
    protected $regex = '#^ping$#';

    public function run(){
        $this->message->reply("pong! (".round((microtime(true)-$this->event->init_time)*1000, 2)."ms)");
        throw new \Slowbro\Slack\Exception\StopProcessingException;
    }

}
