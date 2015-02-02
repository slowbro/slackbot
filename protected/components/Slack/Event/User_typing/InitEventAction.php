<?php namespace Slack\Event\User_typing;

class InitEventAction extends \Slack\Event\EventAction {

    public function run(){
        $user = $this->state->findUserById($this->event->user);
        $user->setLastTyping(time());
    }

}
