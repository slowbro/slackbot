<?php

namespace slackbot\commands;

use yii\console\Controller;
use slackbot\models\Config;

class StartController extends Controller {

    public function actionIndex($args=""){
        $slack = \Slowbro\Slack\Client::factory(true, \slackbot\models\Config::getValue('slack.apikey'), \Yii::getAlias("@slackbot/events/"));

        # logger
        $logger = new \Zend\Log\Logger();
        $writer = new \Zend\Log\Writer\Stream("php://output");
        $formatter = new \Zend\Log\Formatter\Simple(date('[H:i] ')."%message%");
        $writer->setFormatter($formatter);
        $logger->addWriter($writer);
        $slack->setLogger($logger);

        $slack->start();
        exit;
    }

}
