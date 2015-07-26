<?php namespace Event\Message;

class ReactionAction extends \Slowbro\Slack\Event\Message\BaseAction {

    private $albumList = array(
        [
            'title'    => 'nope',
            'synonyms' => array('no'),
            'album'    => 'JNzjB'
        ],
        [
            'title'    => 'abandon thread',
            'synonyms' => array('abandon', 'fuck this shit'),
            'album'    => 'aYJkp'
        ],
        [
            'title'    => 'disgust',
            'synonyms' => array('disapproval'),
            'album'    => 'AXues'
        ],
        [
            'title'    => 'excited',
            'synonyms' => array('amazed','nailed it'),
            'album'    => '1GOKT'
        ],
        [
            'title'    => 'clapping',
            'synonyms' => array('clap'),
            'album'    => 'NzuZS'
        ],
        [
            'title'    => 'shut up',
            'synonyms' => array('stfu'),
            'album'    => 'FGIfa'
        ],
        [
            'title'    => 'upset',
            'synonyms' => array('sad','mad','angry'),
            'album'    => 'qfkyX'
        ],
        [
            'title'    => 'not bad',
            'synonyms' => array('agreement'),
            'album'    => 'LoNV2'
        ],
        [
            'title'    => 'popcorn',
            'synonyms' => array('dis gon be gud'),
            'album'    => 'LPRbU'
        ],
        [
            'title'    => 'mind blown',
            'synonyms' => array(),
            'album'    => 'FEnwc'
        ],
        [
            'title'    => 'wat',
            'synonyms' => array('wut', 'what', 'wut', 'confused'),
            'album'    => 'ywmyw'
        ],
        [
            'title'    => 'deal with it',
            'synonyms' => array(),
            'album'    => 'K21Ft'
        ],
        [
            'title'    => 'cool story bro',
            'synonyms' => array(),
            'album'    => 'yIdY2'
        ],
        [
            'title'    => 'haters gonna hate',
            'synonyms' => array(),
            'album'    => 'yGacg'
        ],
        [
            'title'    => 'give a fuck',
            'synonyms' => array('give a damn'),
            'album'    => 'cB34U'
        ],
        [
            'title'    => 'lol',
            'synonyms' => array('laughing'),
            'album'    => 's16Zv'
        ],
        [
            'title'    => 'feels',
            'synonyms' => array(),
            'album'    => 'SL6aO'
        ],
        [
            'title'    => 'adventure time',
            'synonyms' => array(),
            'album'    => 'RRqv0'
        ],
        [
            'title'    => 'dancing',
            'synonyms' => array('dance'),
            'album'    => 'wy22z'
        ],
        [
            'title'    => "didn't read lol",
            'synonyms' => array('didnt read lol'),
            'album'    => 'tVg8K'
        ],
    );

    protected $trigger = true;
    protected $regex = '#^reaction\s+#';

    public function run(){
        $channel = $this->message->getChannel();
        $query = preg_replace('#^reaction\s+#', '', $this->getCleanText());
        if($query === "list")
            return $this->reactionList();
        elseif($query === "list full")
            return $this->reactionList(true);
        $i_id = \slackbot\models\Config::getValue('imgur.client_id');
        $i_secret = \slackbot\models\Config::getValue('imgur.client_secret');
        if(!$i_id || !$i_secret)
            throw new Exception('Missing Imgur Configuration');
        if(!$imgur = new \Imgur\Client())
            throw new Exception("Can't connect to the Imgur API.");
        $imgur->setOption('client_id', $i_id);
        $imgur->setOption('client_secret', $i_secret);
        #determine album id
        $a_id = $this->resolveAlbum($query);
        if(!$a_id){
            $this->message->reply("I don't know of an album for '$query'.");
        } else {
            $album = $imgur->api('album')->album($a_id);
            $images = $album->getImages();
            $key = array_rand($images);
            $img = $images[$key];
            $channel->message($img->getLink());
        }
        throw new \Slowbro\Slack\Exception\StopProcessingException;
    }

    protected function reactionList($full=false){
        $list = array();
        foreach($this->albumList as $a){
            if($full)
                $list[] = $a['title'].($a['synonyms']?" (".implode(', ', $a['synonyms']).")":'');
            else
                $list [] = $a['title'];
        }
        asort($list);
        $this->message->reply("Here are the reactions I know.".($full?" Synonyms are in parenthesis.":'')."\n".implode(', ', $list));
    }

    protected function resolveAlbum($in){
        $in = strtolower($in);
        foreach($this->albumList as $a){
            if($a['title'] === $in || in_array($in, $a['synonyms']))
                return $a['album'];
        }
        return false;
    }

}
