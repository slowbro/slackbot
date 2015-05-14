<?php namespace Slack;

use Slack\SlackState;

class SlackMessage extends \Slack\Base\SlackBaseObject {

    const TYPE_CHANNEL = '#';
    const TYPE_GROUP   = '&';
    const TYPE_IM      = '!';

    private $subtype;

    public $channel;
    public $user;
    public $text;
    public $ts;
    public $team;

    /* bot related */
    public $bot_id;
    public $username;
    public $icons = [];

    /* delete/edit related */
    public $hidden;
    public $deleted_ts;
    public $event_ts;
    public $edited = [];
    private $message;

    /* group/channel related */
    public $topic;
    public $purpose;
    public $name;
    public $members;

    /* file related */
    public $file = [];
    public $upload;
    public $comment = [];

    public function __toString(){
        $state = SlackState::getState();
        $user = $state->findUserById($this->user);
        $type = $this->getChannelType();
        $chanObj = $this->getChannel();
        $channelString = '/'.($type==self::TYPE_IM?'IM':$type.$chanObj->name);
        return "({$user->name}$channelString) {$this->text}";
    }

    public function setChannel($id){
        $this->channel = $id;
        return $this;
    }

    public function setText($text){
        $this->text = $text;
        return $this;
    }

    public function send(){
        $slack = \Slack\Slack::factory();
        $state = SlackState::getState();
        $message = [
            'type' => 'message',
            'channel' => $this->channel,
            'text' => $this->text
        ];
        $ret = $slack->send($message);
        $logger = $slack->getLogger();
        $type = $this->getChannelType();
        $chanObj = $this->getChannel();
        $channelString = '/'.($type==self::TYPE_IM?'IM':$type.$chanObj->name);
        $logger->info("({$state->self->name}$channelString) {$this->text}");
        return $ret;
    }

    public function getChannelType(){
        switch(substr($this->channel, 0, 1)){
            case 'C':
                return self::TYPE_CHANNEL;
                break;
            case 'G':
                return self::TYPE_GROUP;
                break;
            case 'D':
                return self::TYPE_IM;
                break;
        }
        throw new \Exception("Unknown channel prefix: '".substr($this->channel, 0, 1)."'");
    }

    public function getChannel(){
        $state = SlackState::getState();
        $type = $this->getChannelType();
        switch($type){
            case self::TYPE_CHANNEL:
                return $state->findChannelById($this->channel);
                break;
            case self::TYPE_GROUP:
                return $state->findGroupById($this->channel);
                break;
            case self::TYPE_IM:
                return $state->findImById($this->channel);
                break;
        }
        return false;
    }

    public function getUser(){
        $state = SlackState::getState();
        return $state->findUserById($this->user);
    }

    public function reply($text, $namePrefix=true){
        $message = new SlackMessage;
        if($namePrefix){
            $user = $this->getUser();
            $text = $user->name.': '.$text;
        }
        return $message->setChannel($this->channel)->setText($text)->send();
    }

}
