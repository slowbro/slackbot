<?php

namespace slackbot\commands;

use yii\console\Controller;
use slackbot\models\Config;

class StartController extends Controller {

    public function actionIndex($args=""){
        $slack = \Slack\Slack::factory();
        $ws = $slack->startRtm();
        # logger
        $logger = new \Zend\Log\Logger();
        $writer = new \Zend\Log\Writer\Stream("php://output");
        $formatter = new \Zend\Log\Formatter\Simple(date('[H:i] ')."%message%");
        $writer->setFormatter($formatter);
        $logger->addWriter($writer);
        $slack->setLogger($logger);

        $loop = \React\EventLoop\Factory::create();
        $connector = new \Ratchet\Client\Factory($loop);
        $connector($ws)->then(
            function(\Ratchet\Client\WebSocket $conn) use ($slack){
                $slack->setClient($conn);

                $conn->on("message", function($message){
                    $event = new \Slack\SlackEvent;
                    $event->parse($message);
                });
            },
            function ($e) use ($loop, $logger){
                $logger->info("Could not connect: {$e->getMessage()}");
                $loop->stop();
            }
        );

        $loop->run();

    }

}
