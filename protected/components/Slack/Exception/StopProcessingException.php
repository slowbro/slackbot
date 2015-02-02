<?php namespace Slack\Exception;

class StopProcessingException extends \Exception {

    public function __construct($message = "StopProcessing called.", $code, $previous){
        parent::__construct($message, $code, $previous);
    }

}
