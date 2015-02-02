<?php

class Todo extends CActiveRecord
{
    public $id;
    public $channel;
    public $number;
    public $status;
    public $message;

    public $maxNumber;

    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }

    public function tableName()
    {
        return 'todo';
    }

    public function rules()
    {
        return array(
            array('channel,number,status,message', 'required'),
        );
    }

    public function add($chan, $text){
        $todo = new Todo;
        $todo->channel = $chan;
        $todo->status = 'open';
        $todo->number = $this->getChannelNextNumber($chan);
        $todo->message = $text;
        return $todo->save();
    }

    public function mark(){

    }

    public function del($chan, $number){
        $t = self::model()->find('channel=:chan and number=:num', array('chan'=>$chan,'num'=>$number));
        if(!$t)
            return false;
        $t->delete();
        $fix_t = self::model()->findAll('channel=:chan and number>:num', array('chan'=>$chan,'num'=>$number));
        foreach($fix_t as $ft){
            $ft->number--;
            $ft->save();
        }
    }

    public function getChannelNextNumber($channel){
        $c = new CDbCriteria;
        $c->select = 'max(number) as maxNumber';
        $c->condition = 'channel=:channel';
        $c->params = array('channel'=>$channel);
        $row = self::model()->find($c);
        return $row['maxNumber']+1;
    }

    public static function getChannelTodo($channel){
        $r = self::model()->findAll(array(
                    'condition' => 'channel=:channel',
                    'params'    => array(':channel'=>$channel),
                    'order'     => 'number'
                    ));
        return $r;
    }

}
