<?php

class LolCommand extends CConsoleCommand {

    public function run($args){
        $slack = new \Slack\Slack;
        $ws = $slack->startRtm();
        echo $ws;
    }

}
