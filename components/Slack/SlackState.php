<?php namespace Slack;

use Slack\SlackSelf;
use Slack\SlackTeam;
use Slack\SlackChannel;
use Slack\SlackUser;

class SlackState {

    private static $state;

    public $url;

    public $self;
    public $team;

    private $bots;
    private $channels;
    private $groups;
    private $ims;
    private $users;

    public function __construct($initialState){
        $this->url  = $initialState['url'];
        $this->self = new SlackSelf($initialState['self']);
        $this->team = new SlackTeam($initialState['team']);
        
        foreach($initialState['bots'] as $bot){
            $this->addBot($bot);
        }
        foreach($initialState['channels'] as $channel){
            $this->addChannel($channel);
        }
        foreach($initialState['groups'] as $group){
            $this->addGroup($group);
        }
        foreach($initialState['ims'] as $im){
            $this->addIm($im);
        }
        foreach($initialState['users'] as $user){
            $this->addUser($user);
        }
        self::$state = $this;
    }

    public static function getState(){
        if(!self::$state)
            throw new Exception("Attempting to get Slack state before it was initialized.");
        return self::$state;
    }

    /**
     *
     * Bot Functions
     *
     **/

    public function addBot($bot){
        $chan = new SlackBot($bot);
        $this->bots[] = $chan;
        return $chan;
    }

    public function updateBot($bot){
        $c = $this->findBotById($bot['id']);
        if(!$c)
            return false;
        return $c->update($bot);
    }

    public function findBotById($id){
        foreach($this->bots as $bot){
            if($bot->id == $id)
                return $bot;
        }
        return false;
    }

    public function findBotByName($name){
        foreach($this->bots as $bot){
            if($bot->name == $name)
                return $bot;
        }
        return false;
    }

    /**
     *
     * Channel Functions
     *
     **/

    public function addChannel($channel){
        $chan = new SlackChannel($channel);
        $this->channels[] = $chan;
        return $chan;
    }

    public function updateChannel($channel){
        $c = $this->findChannelById($channel['id']);
        if(!$c)
            return false;
        return $c->update($channel);
    }

    public function findChannelById($id){
        foreach($this->channels as $channel){
            if($channel->id == $id)
                return $channel;
        }
        return false;
    }

    public function findChannelByName($name){
        foreach($this->channels as $channel){
            if($channel->name == $name)
                return $channel;
        }
        return false;
    }

    /**
     *
     * Group Functions
     *
     **/

    public function addGroup($group){
        $grp = new SlackGroup($group);
        $this->groups[] = $grp;
        return $grp;
    }

    public function updateGroup($group){
        $g = $this->findGroupById($group['id']);
        if(!$g)
            return false;
        return $g->update($group);
    }

    public function findGroupById($id){
        foreach($this->groups as $group){
            if($group->id == $id)
                return $group;
        }
        return false;
    }

    public function findGroupByName($name){
        foreach($this->groups as $group){
            if($group->name == $name)
                return $group;
        }
        return false;
    }

    public function removeGroupById($id){
        foreach($this->groups as $key=>$group){
            if($group->id == $id){
                unset($this->groups[$key]);
                return true;
            }
        }
        return false;
    }

    /**
     *
     * IM Functions
     *
     **/

    public function addIm($im){
        if($im instanceof \Slack\SlackIm){
            $imm = $im;
        } else {
            $imm = new SlackIm($im);
        }
        $this->ims[] = $imm;
        return $imm;
    }

    public function updateIm($im){
        $i = $this->findImById($im['id']);
        if(!$i)
            return false;
        return $i->update($im);
    }

    public function findImById($id){
        foreach($this->ims as $im){
            if($im->id == $id)
                return $im;
        }
        return false;
    }

    public function findImByUser($user){
        foreach($this->ims as $im){
            if($im->user == $user)
                return $im;
        }
        return false;
    }


    /**
     *
     * User Functions
     *
     **/

    public function addUser($user){
        $usr = new SlackUser($user);
        $this->users[] = $usr;
        return $usr;
    }

    public function updateUser($user){
        $u = $this->findUserById($user['id']);
        if(!$u)
            return false;
        return $u->update($user);
    }

    public function findUserById($id){
        foreach($this->users as $user){
            if($user->id == $id)
                return $user;
        }
        return false;
    }

    public function findUserByName($name){
        foreach($this->users as $user){
            if($user->name == $name)
                return $user;
        }
        return false;
    }

}
