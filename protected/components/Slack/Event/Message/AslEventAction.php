<?php namespace Slack\Event\Message;

class AslEventAction extends DefaultEventAction {

    protected $trigger = true;
    protected $regex = '#^a/s/l(\?)?#';

    protected $l = [
            'internet',
            'cali',
            'usa',
            'uk',
            'mars'
        ];
    protected $s = [
            'm',
            'f',
            'bot',
            'omnipresent ball of gas'
        ];

    public function run(){
        $a = rand(0,100);
        $ws = array_rand($this->s);
        $wl = array_rand($this->l);
        $this->message->reply("$a/{$this->s[$ws]}/{$this->l[$wl]}");
        throw new \Slack\Exception\StopProcessingException;
    }

}
