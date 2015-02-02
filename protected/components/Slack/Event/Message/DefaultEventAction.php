<?php namespace Slack\Event\Message;

use Slack\SlackMessage;
use Slack\SlackState;

class DefaultEventAction extends \Slack\Event\EventAction {

    protected $message;
    protected $trigger = false;
    protected $regex = false;
    protected $matches = [];

    public function __construct($event){
        parent::__construct($event);
        $this->message = new SlackMessage($this->event->asObject());
        if(
            ($this->trigger && (preg_match("#^{$this->state->self->name}(:|,)?\s#", $this->message->text) === 0)) || # check that we were directly triggered
            ($this->regex && (preg_match($this->regex, $this->getCleanText(), $this->matches) === 0)) #check that our regex matched
          ){
            throw new \Slack\Exception\SkipActionException;
        }
    }

    protected function getCleanText(){
        return preg_replace("#^{$this->state->self->name}(:|,)?\s+#", '', $this->message->text);
    }

}
