<?php namespace Slack\Event\Message\Channel_join;

class InitEventAction extends \Slack\Event\EventAction {

    public function run(){
        $channel = $this->state->findChannelById($this->event->channel);
        $channel->addMember($this->event->user);
        $logger = $this->slack->getLogger();
        $logger->info("({$channel->name}) {$this->event->text}");
    }

}
