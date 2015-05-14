<?php namespace Slack\Event;

use Slack\Slack;
use Slack\SlackState;

class EventAction {

    protected $event;
    protected $slack;
    protected $state;
    public static $sort = 0;

    public function __construct($event){
        $this->event = $event;
        $this->slack = Slack::factory();
        $this->state = SlackState::getState();
    }

    public function run(){

    }

}
