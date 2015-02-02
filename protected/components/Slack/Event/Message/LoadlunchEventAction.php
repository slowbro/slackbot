<?php namespace Slack\Event\Message;

class LoadlunchEventAction extends \Slack\Event\Message\DefaultEventAction {

    protected $trigger = true;
    protected $regex   = '#^loadlunch\s(.*)#i';

    public function run(){
        $tx = $this->matches[1];
        $valid_lines = array();
        $lines = preg_split('#\r?\n#', $tx);
        foreach($lines as $l){
            if(preg_match('#^(\w+,\s)?\w+\s\d+(th|rd|nd|st)#', $l) !== 0)
                $valid_lines[] = $l;
            else
                $this->message->reply("Invalid line: $l");
        }
        $i=0;
        foreach($valid_lines as $line){
            # fuck dashes
            $l = preg_replace('#(\xE2\x80\x93|\xE2\x80\x94)#', '-', $line);
            $s = preg_split('#-(\s+)?#', $l, 2);
            $day = date('mdY', strtotime($s['0']));
            if(!$day){
                $this->message->reply("Invalid date: {$s['0']}");
                continue;
            }
            $l = new \Lunch;
            $l->day = $day;
            $l->food = $s['1'];
            if(!$l->save()){
                $this->message->reply("Could not load {$s['0']}: ".$l->getError());
                continue;
            }
            $i++;
        }
        $this->message->reply("Loaded $i days.");
       
    }

}
