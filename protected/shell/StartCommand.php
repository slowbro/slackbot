<?php

class StartCommand extends CConsoleCommand {

    public function run($args){
        $slack = Slack\Slack::factory();
        $ws = $slack->startRtm();

        $logger = new \Zend\Log\Logger();
        $writer = new Zend\Log\Writer\Stream("php://output");
        $formatter = new Zend\Log\Formatter\Simple(date('[H:i] ')."%message%");
        $writer->setFormatter($formatter);
        $logger->addWriter($writer);

        $loop = \React\EventLoop\Factory::create();
        $client = new \Devristo\Phpws\Client\WebSocket($ws, $loop, $logger);
        
        $slack->setClient($client);
        $slack->setLogger($logger);

        $client->on("request", function($headers) use ($logger){
            $logger->info("Request object created.");
        });

        $client->on("handshake", function() use ($logger) {
            $logger->info("Handshake received.");
        });

        $client->on("connect", function($headers) use ($logger){
            $logger->info("Connected.");
        });

        $client->on("message", function($message){
            $md = $message->getData();
            $event = new Slack\SlackEvent;
            $event->parse($md);
        });

        $client->open();
        $loop->run();

    }

}
