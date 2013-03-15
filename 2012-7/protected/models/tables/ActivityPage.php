<?php

/**
 * This is the model class for table "ActivityPage".
 *
 * The followings are the available columns in table 'ActivityPage':
 * @property string $id
 * @property string $activityId
 * @property string $beginTime
 * @property string $endTime
 * @property string $title
 * @property string $description
 * @property string $content
 * @property string $createrId
 * @property integer $createTime
 * @property string $editorId
 * @property integer $editTime
 */
class ActivityPage extends ActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return ActivityPage the static model class
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
		return 'ActivityPage';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('title', 'required'),
			array('createTime, editTime', 'numerical', 'integerOnly'=>true),
			array('activityId, createrId, editorId', 'length', 'max'=>20),
			array('title', 'length', 'max'=>200),
			array('beginTime, endTime, description, content', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, activityId, beginTime, endTime, title, description, content, createrId, createTime, editorId, editTime', 'safe', 'on'=>'search'),
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
			'activity'=>array(self::BELONGS_TO, 'Activity', 'activityId'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'Id',
			'activityId' => 'Activity',
			'beginTime' => 'Begin Time',
			'endTime' => 'End Time',
			'title' => 'Title',
			'description' => 'Description',
			'content' => 'Content',
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

		$criteria->compare('activityId',$this->activityId,true);

		$criteria->compare('beginTime',$this->beginTime,true);

		$criteria->compare('endTime',$this->endTime,true);

		$criteria->compare('title',$this->title,true);

		$criteria->compare('description',$this->description,true);

		$criteria->compare('content',$this->content,true);

		$criteria->compare('createrId',$this->createrId,true);

		$criteria->compare('createTime',$this->createTime);

		$criteria->compare('editorId',$this->editorId,true);

		$criteria->compare('editTime',$this->editTime);

		return new CActiveDataProvider('ActivityPage', array(
			'criteria'=>$criteria,
		));
	}
}