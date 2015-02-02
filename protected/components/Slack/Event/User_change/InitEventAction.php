<?php namespace Slack\Event\User_change;

class InitEventAction extends \Slack\Event\EventAction {

    public function run(){
        $newdata = $this->event->user;
        $user = $this->state->findUserById($newdata->id);
        if(!$user)
            throw new \Exception("Received user_change for '{$user->name}' but I can't find that user?");
        $user->update($newdata);
    }

}
