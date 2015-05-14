<?php namespace Slack\Event\Group_joined;

class WarningEventAction extends \Slack\Event\EventAction {

    public function run(){
        $group = $this->state->findGroupById($this->event->channel->id);
        if(!$group)
            throw new \Exception("Attempting to run WarningEventAction on a group we're seemingly not in..?");
        $group->message("Thanks for the invite! Please be aware that whomever runs this bot now has a way to view this channel (via my console output) - if you're not OK with that, please remove me from the group with `/remove hal`");
    }

}
