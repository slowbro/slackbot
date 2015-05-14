<?php namespace Slack\Event\Message;

class InitEventAction extends DefaultEventAction {

    public function run(){
        $logger = $this->slack->getLogger();
        $logger->info((string) $this->message);
    }

}
