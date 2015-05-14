<?php

class Lunch extends CActiveRecord
{
    public $day;
    public $food;

    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }

    public function tableName()
    {
        return 'lunch';
    }

    public function rules()
    {
        return array(
            array('day', 'required'),
        );
    }

}
