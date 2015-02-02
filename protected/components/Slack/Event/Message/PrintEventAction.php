<?php namespace Slack\Event\Message;

class PrintEventAction extends DefaultEventAction {

    public function run(){
        $logger = $this->slack->getLogger();
        $logger->info((string) $this->message);
    }

}
