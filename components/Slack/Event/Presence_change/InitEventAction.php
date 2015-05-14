<?php namespace Slack\Event\Presence_change;

class InitEventAction extends \Slack\Event\EventAction {

    public function run(){
        $user = $this->state->findUserById($this->event->user);
        if(!$user)
            throw new \Exception("presence_change: Unknown user: {$this->event->user}");
        $user->setPresence($this->event->presence);
    }

}
