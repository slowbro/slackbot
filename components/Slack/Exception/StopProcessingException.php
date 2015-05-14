<?php namespace Slack\Exception;

class StopProcessingException extends \Exception {

    public function __construct($message = "StopProcessing called."){
        parent::__construct($message);
    }

}
