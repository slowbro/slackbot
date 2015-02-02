<?php namespace Slack\Exception;

class SkipActionException extends \Exception {

    public function __construct($message = "SkipAction called.", $code, $previous){
        parent::__construct($message, $code, $previous);
    }

}
