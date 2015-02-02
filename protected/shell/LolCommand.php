<?php

class LolCommand extends CConsoleCommand {

    public function run($args){
        $slack = new Slack;
        $ws = $slack->startRtm();
        echo $ws;
    }

}
