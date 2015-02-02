<?php namespace Slack\Event\Team_join;

class InitEventAction extends \Slack\Event\EventAction {

    public function run(){
        $new_user = $this->event->user;
        $logger = $this->slack->getLogger();
        $this->state->addUser((array)$new_user);
        $logger->info("{$new_user->name} has joined the team!");
    }

}
