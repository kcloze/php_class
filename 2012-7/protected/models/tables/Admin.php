<?php

/**
 * This is the model class for table "Admin".
 *
 * The followings are the available columns in table 'Admin':
 * @property string $id
 * @property string $username
 * @property string $password
 * @property string $createrId
 * @property integer $createTime
 * @property string $editorId
 * @property integer $editTime
 */
class Admin extends ActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return Admin the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'admin';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('username, createrId, createTime', 'required'),
			array('createTime, editTime', 'numerical', 'integerOnly'=>true),
			array('username, createrId, editorId', 'length', 'max'=>20),
			array('password', 'length', 'max'=>100),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, username, password, createrId, createTime, editorId, editTime', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'Id',
			'username' => 'Username',
			'password' => 'Password',
			'createrId' => 'Creater',
			'createTime' => 'Create Time',
			'editorId' => 'Editor',
			'editTime' => 'Edit Time',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id,true);

		$criteria->compare('username',$this->username,true);

		$criteria->compare('password',$this->password,true);

		$criteria->compare('createrId',$this->createrId,true);

		$criteria->compare('createTime',$this->createTime);

		$criteria->compare('editorId',$this->editorId,true);

		$criteria->compare('editTime',$this->editTime);

		return new CActiveDataProvider('Admin', array(
			'criteria'=>$criteria,
		));
	}
}