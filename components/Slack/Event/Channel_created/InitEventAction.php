<?php namespace Slack\Event\Channel_created;

class InitEventAction extends EventAction {

    protected $trigger = false;

    public function run(){
        $channel = $this->state->addChannel($event->asJson());
        $this->slack->logger->info("New channel created: {$channel->name}");
    }

}
