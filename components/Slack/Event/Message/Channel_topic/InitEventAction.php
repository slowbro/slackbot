<?php namespace Slack\Event\Message\Channel_topic;

class InitEventAction extends \Slack\Event\EventAction {

    protected $trigger = false;

    public function run(){
        echo $this->event->topic;
    }

}
