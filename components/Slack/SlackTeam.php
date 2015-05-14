<?php namespace Slack;

class SlackTeam extends \Slack\Base\SlackBaseObject {

    public $id;
    public $name;
    public $email_domain;
    public $domain;
    public $msg_edit_window_mins;
    public $over_storage_limit;
    public $prefs = [];

}
