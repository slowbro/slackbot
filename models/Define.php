<?php

class Define extends CActiveRecord
{
    public $user;
    public $word;
    public $url;


    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }

    public function tableName()
    {
        return 'defines';
    }

    public function rules()
    {
        return array(
            array('user,word,url', 'required'),
        );
    }

}
