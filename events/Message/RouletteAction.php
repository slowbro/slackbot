<?php namespace Event\Message;

class RouletteAction extends \Slowbro\Slack\Event\Message\BaseAction {

    protected $trigger = true;
    protected $regex   = '#^roulette#i';

    public function run(){
        $force = preg_replace('#^roulette\s?(spin\s)?#', '', $this->getCleanText());
        $storage = \Storage::factory();
        $ch = $storage->select('RouletteEventAction', $this->message->user);
        if($force == 'status'){
            if(!$ch)
                $this->message->reply("You don't have an active game.");
            else
                $this->message->reply("You have dodged the bullet {$ch['n']} times - it's currently sitting in chamber #".(array_search(1, $ch['c'])+1));
            throw new \Slowbro\Slack\Exception\StopProcessingException;
        }
        if(!$ch){
            $ch = ['n'=>0, 'c'=>[1,0,0,0,0,0]];
            $this->message->reply("You must be new around here.. take this, :gun:. Good luck..");
        } else {
            $this->message->reply("Pushing your luck, eh? Here we go...");
        }
        $result = $this->spinChambers($ch['c'], ($force?$force:'random'));
        if($result == 1){
            $this->message->reply("BANG! You didn't make it. Better luck in the next life!");
            $storage->delete('RouletteEventAction', $this->message->user);
        } else {
            $this->message->reply("Click. You get to see the sunrise.");
            $ch['n']++;
            $storage->insert('RouletteEventAction',$this->message->user, $ch);
        }
        throw new \Slowbro\Slack\Exception\StopProcessingException;
    }

    private function spinChambers(&$ch, $force){
        switch($force){
            case 'soft':
                $this->message->reply("You go for the soft approach, giving the cylinder a short spin, and...");
                $low = 1;
                $high = 10;
                break;
            case 'hard':
                $this->message->reply("You decide to go for the fuck-it approach, spinning the chamber so hard the revolver almost falls from your hand...");
                $low = 30;
                $high = 50;
                break;
            case 'random':
                $this->message->reply("You give the cylinder a thouroughly random spin, and...");
                $low = 1;
                $high = 50;
                break;
            default:
                $this->message->reply("Wait, hold on - what do you mean '$force'? I only know 'soft', 'hard', and 'random'");
                throw new \Slowbro\Slack\Exception\StopProcessingException;
        }
        for($i=0;$i<rand($low,$high);$i++){
            array_push($ch, array_shift($ch));
        }
        return $ch[0];
    }

}
