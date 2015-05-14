<?php namespace Slack\Event\Message;

class WolframParserEventAction extends DefaultEventAction {

    protected $trigger = true;
    protected $regex = '#^((what|when|where|why|how|who)((\')?s)?|(are|is|do))\s#';

    public function run(){
        $appId = \slackbot\models\Config::getValue('wa.appid');
        $wa = new \ConnorVG\WolframAlpha\WolframAlpha($appId);
        $ans = $wa->easyQuery($this->getCleanText());
        if(is_array($ans)){
            $a = str_replace('Result:', '', $ans[0]);
            $this->message->reply(trim($a));
        } else {
            $this->message->reply("I don't know :(");
        }
        throw new \Slack\Exception\StopProcessingException;
    }

}
