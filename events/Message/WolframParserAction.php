<?php namespace Event\Message;

class WolframParserAction extends \Slowbro\Slack\Event\Message\BaseAction {

    protected $trigger = true;
    protected $regex = '#^((what|when|where|why|how|who|which)((\')?s)?|(are|is|do))\s#';

    public function run(){
        try {
            $appId = \slackbot\models\Config::getValue('wa.appid');
            $wa = new \ConnorVG\WolframAlpha\WolframAlpha($appId);
            $ans = $wa->easyQuery($this->getCleanText());
            $dym = $wa->didyoumean;
            if(is_array($ans)){
                $a = preg_replace('#(Result|Response):#', '', $ans[0]);
                $this->message->reply(trim($a));
            } else {
                $this->message->reply("I don't know :(");
            }
        } catch (\Exception $e){
            $this->message->reply("Something went wrong: ".$e->getMessage());
            echo $e;
        } finally {
            throw new \Slowbro\Slack\Exception\StopProcessingException;
        }
    }

}
