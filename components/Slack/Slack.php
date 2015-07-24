<?php namespace Slack;

use Frlnc\Slack\Http\SlackResponseFactory;
use Frlnc\Slack\Http\CurlInteractor;
use Frlnc\Slack\Core\Commander;

class Slack {

    private static $slack;

    private $interactor;
    private $commander;
    private $client;
    private $logger;
    private $messageCounter = 1;

    public function __construct(){
        $slack_key = \slackbot\models\Config::getValue('slack.apikey');
        $this->interactor = new CurlInteractor;
        $this->interactor->setResponseFactory(new SlackResponseFactory);
        $this->commander = new Commander($slack_key, $this->interactor);
    }

    public static function factory($overwrite=false){
        if(!self::$slack || $overwrite)
            self::$slack = new Slack;
        return self::$slack;
    }

    public function setLogger($logger){
        $this->logger = $logger;
    }

    public function getLogger(){
        return $this->logger;
    }

    public function setClient($client){
        $this->client = $client;
    }

    public function execute($cmd, $params=array()){
        return $this->commander->execute($cmd, $params)->toArray();
    }

    public function startRtm(){
        $rtm = $this->execute('rtm.start');
        $state = new SlackState($rtm['body']);
        return $state->url;
    }

    public function send($message){
        $message += ['id'=>$this->messageCounter];
        $this->messageCounter++;
        return $this->client->send(json_encode($message));
    }

}
