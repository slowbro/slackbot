<?php namespace Slack\Event\Group_joined;

class InitEventAction extends \Slack\Event\EventAction {

    public function run(){
        $group = $this->event->channel;
        $this->state->addGroup($group);
    }

}
