<?php namespace Event\Message;

class PasswordAction extends \Slowbro\Slack\Event\Message\BaseAction {

    protected $trigger = true;
    protected $regex   = '#^(give|make) me (a|[0-9]+) passwords?#i';

    public function run(){
        if($this->matches[2] == 'a'){
            $i = 1;
        } else {
            if($this->matches[2] > 20){
                $this->message->reply("That's too many.. how many passwords do you need?!");
                throw new \Slowbro\Slack\Exception\StopProcessingException;
            }
            $i = $this->matches[2];
        }
        $user = $this->message->getUser();
        $msg = "Here's your password(s):\n";
        for ($o=0;$o<$i;$o++){
            $msg .= $this->random_password()."\n";
        }
        $this->message->reply("Ok, I IMed ".($i>1?'those':'it')." to you.");
        $user->im($msg);
        throw new \Slowbro\Slack\Exception\StopProcessingException;
    }

    private function random_password( $length = 12 ) {
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()_-=+;:,.?";
        $password = substr( str_shuffle( $chars ), 0, $length );
        return $password;
    }

}
