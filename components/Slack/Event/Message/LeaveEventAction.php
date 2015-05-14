<?php namespace Slack\Event\Message;

class LeaveEventAction extends \Slack\Event\Message\DefaultEventAction {

    protected $trigger = true;
    protected $regex   = '#^leave$#i';

    public function run(){
        $room = $this->message->getChannel();
        $room->message("Bye!");
        $room->leave();
    }

}
