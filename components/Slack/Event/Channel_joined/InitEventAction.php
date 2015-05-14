<?php namespace Slack\Event\Channel_joined;

class InitEventAction extends \Slack\Event\EventAction {

    public function run(){
        $channel = $this->event->channel;
        $this->state->addChannel($channel);
    }

}
