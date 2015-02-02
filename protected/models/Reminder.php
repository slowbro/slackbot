<?php

class Reminder extends CActiveRecord
{
    public $ts;
    public $channel;
    public $creator;
    public $recipient;
    public $message;

    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }

    public function tableName()
    {
        return 'reminders';
    }

    public function rules()
    {
        return array(
            array('ts,channel,creator,message', 'required'),
        );
    }

}
