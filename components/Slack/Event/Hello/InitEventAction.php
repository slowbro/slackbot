<?php namespace Slack\Event\Hello;

class InitEventAction extends \Slack\Event\EventAction {

    public function run(){
        $logger = $this->slack->getLogger();
        $logger->info("Logged in to Slack!");
        $logger->info("Welcome to ".$this->state->team->name."!");
    }

}
