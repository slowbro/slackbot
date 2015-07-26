<?php namespace Event\Message;

class WaAction extends \Slowbro\Slack\Event\Message\BaseAction {

    protected $trigger = true;
    protected $regex = '#^wa\s+#';

    public function run(){
        $query = preg_replace('#^wa\s+#', '', $this->getCleanText());
        if(!$query)
            return false;
        $appId = \slackbot\models\Config::getValue('wa.appid');
        $wa = new \ConnorVG\WolframAlpha\WolframAlpha($appId);
        $ans = $wa->easyQuery($query);
        if(is_array($ans)){
            $a = implode("\n", $ans);
            $this->message->reply("\n".$a);
        } else {
            $this->message->reply($ans);
        }
        throw new \Slowbro\Slack\Exception\StopProcessingException;
    }

}
