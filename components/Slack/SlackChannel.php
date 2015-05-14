<?php namespace Slack;

class SlackChannel extends \Slack\Base\SlackBaseRoom {

    public $id;
    public $name;
    public $is_channel = true;
    public $created;
    public $creator;
    public $is_archived;
    public $is_general;
    public $is_member;

    public $members = [];
    public $topic   = [];
    public $purpose = [];
    
    public function leave(){
        $slack = \Slack\Slack::factory();
        $slack->execute('channels.leave', ['channel'=>$this->id]);
    }

    public function addMember($member_id){
        $this->members[] = $member_id;
        return true;
    }

}
