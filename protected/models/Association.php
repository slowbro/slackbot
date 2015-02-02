<?php

class Association extends CActiveRecord
{
	public $slack_id;
	public $slack_name;
    public $pd_id;

    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }

	public function tableName()
	{
		return 'associations';
    }

	public function rules()
	{
		return array(
			array('slack_name,slack_id,pd_id', 'required'),
		);
	}

}
