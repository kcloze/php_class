<?php

/**
 * This is the model class for table "activity".
 *
 * The followings are the available columns in table 'activity':
 * @property string $id
 * @property string $name
 * @property string $beginTime
 * @property string $endTime
 * @property string $startPage
 * @property string $closePage
 * @property integer $status
 * @property string $description
 * @property string $createrId
 * @property integer $createTime
 * @property string $editorId
 * @property integer $editTime
 */
class activity extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return activity the static model class
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
		return 'activity';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name, beginTime, createrId, createTime', 'required'),
			array('status, createTime, editTime', 'numerical', 'integerOnly'=>true),
			array('name', 'length', 'max'=>200),
			array('startPage, closePage, createrId, editorId', 'length', 'max'=>20),
			array('endTime, description', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, name, beginTime, endTime, startPage, closePage, status, description, createrId, createTime, editorId, editTime', 'safe', 'on'=>'search'),
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
			'name' => 'Name',
			'beginTime' => 'Begin Time',
			'endTime' => 'End Time',
			'startPage' => 'Start Page',
			'closePage' => 'Close Page',
			'status' => 'Status',
			'description' => 'Description',
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

		$criteria->compare('name',$this->name,true);

		$criteria->compare('beginTime',$this->beginTime,true);

		$criteria->compare('endTime',$this->endTime,true);

		$criteria->compare('startPage',$this->startPage,true);

		$criteria->compare('closePage',$this->closePage,true);

		$criteria->compare('status',$this->status);

		$criteria->compare('description',$this->description,true);

		$criteria->compare('createrId',$this->createrId,true);

		$criteria->compare('createTime',$this->createTime);

		$criteria->compare('editorId',$this->editorId,true);

		$criteria->compare('editTime',$this->editTime);

		return new CActiveDataProvider('activity', array(
			'criteria'=>$criteria,
		));
	}
}