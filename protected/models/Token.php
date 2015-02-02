<?php

class Token extends CActiveRecord
{
	public $token;
	public $type;

    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }

	public function tableName()
	{
		return 'tokens';
    }

	public function rules()
	{
		return array(
			array('token, type', 'required'),
		);
	}

}
