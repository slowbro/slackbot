<?php

class Config extends CActiveRecord
{
	public $name;
	public $value;

    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }

	public function tableName()
	{
		return 'config';
    }

	public function rules()
	{
		return array(
			array('name', 'required'),
		);
	}

    static public function getValue($name){
        $model = Config::model()->find('name=:name', array('name'=>$name));
        if(!$model)
            return false;
        return $model->value;
    }

}
