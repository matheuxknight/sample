<?php

class Cronjob extends CActiveRecord
{
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public function tableName()
	{
		return 'Cronjob';
	}

	public function rules()
	{
		return array(
			array('name', 'required'),
			array('name', 'length', 'max'=>200),
			array('url', 'length', 'max'=>200),
			array('delay', 'length', 'max'=>200),
		);
	}

	public function relations()
	{
		return array(
		);
	}

	public function attributeLabels()
	{
		return array(
			'id'=>'Id',
			'name'=>'Name',
			'url'=>'Url',
			'delay'=>'Time',
			'enable'=>'Enable',
			'phpcode'=>'Script',
		);
	}



}


