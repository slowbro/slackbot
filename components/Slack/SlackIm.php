<?php namespace Slack;

class SlackIm extends \Slack\Base\SlackBaseRoom {

    public $id;
    public $user;
    public $is_im = true;
    public $created;
    public $is_user_deleted;

    public function open($userId){
        $slack = \Slack\Slack::factory();
        $state = \Slack\SlackState::getState();
        $res = $slack->execute('im.open',[
                'user' => $userId
            ]);
        $body = $res['body'];
        if(!$body['ok'])
            throw new \Exception("Unable to open IM channel with {$userId}: ".$body['error']);
        $this->update($body['channel']+['created'=>time(),'is_user_deleted'=>false]);
        $state->addIm($this);
        return true;
    }

    public function leave(){
        $slack = \Slack\Slack::factory();
        $slack->execute('im.close', ['channel'=>$this->id]);
    }

}
