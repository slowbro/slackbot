<?php namespace Slack\Base;

class SlackBaseRoom extends SlackBaseObject {

    public $is_im = false;
    public $is_group = false;
    public $is_channel = false;

    public function message($text){
        $message = new \Slack\SlackMessage;
        return $message->setChannel($this->id)->setText($text)->send();
    }

}
