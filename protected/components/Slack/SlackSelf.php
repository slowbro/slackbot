<?php namespace Slack;

class SlackSelf extends \Slack\Base\SlackBaseObject {

    public $id;
    public $name;
    public $prefs = [];
    public $created;
    public $manual_presence;

}
