<?php
namespace slackbot\models;

class Config extends \yii\db\ActiveRecord
{

	public static function tableName()
	{
		return 'config';
    }

	public function rules()
	{
		return array(
			array('name', 'required'),
		);
	}

    public static function getValue($name){
        $model = Config::findOne(['name' => $name]);
        if(!$model)
            return false;
        return $model->value;
    }

}
