<?php namespace Slack;

class SlackGroup extends \Slack\Base\SlackBaseRoom {

    public $id;
    public $name;
    public $is_group = true;
    public $created;
    public $creator;
    public $is_archived;

    public $members = [];
    public $topic = [];
    public $purpose = [];

    public function leave(){
        $slack = \Slack\Slack::factory();
        $state = \Slack\SlackState::getState();
        $ret = $slack->execute('groups.close', ['channel'=>$this->id]);
        var_dump($ret);
        $state->removeGroupById($this->id);
    }

}
