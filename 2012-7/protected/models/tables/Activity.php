<?php

/**
 * This is the model class for table "activity".
 *
 * The followings are the available columns in table 'activity':
 * @property string $id
 * @property string $name
 * @property integer $beginTime
 * @property integer $endTime
 * @property string $startPage
 * @property string $closePage
 * @property integer $status
 * @property string $description
 * @property string $createrId
 * @property integer $createTime
 * @property string $editorId
 * @property integer $editTime
 */
class Activity extends ActiveRecord
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
			array('name, key, beginTime', 'required'),
			array('activityType,createTime, editTime, joinCount', 'numerical', 'integerOnly'=>true),
			array('status', 'length', 'max'=>20),
			array('key', 'length', 'max'=>40),
			array('name, city', 'length', 'max'=>200),
			array('startPage, closePage, createrId, editorId', 'length', 'max'=>20),
			array('description, endTime', 'safe'),
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
			'homepage'=>array(self::BELONGS_TO, 'ActivityResource', 'startPage'),
			'closepage'=>array(self::BELONGS_TO, 'ActivityResource', 'closePage'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => '编号',
			'name' => '名称',
			'activityType' => '活动类别',
			'key' => '访问路径',
			'beginTime' => '开始时间',
			'endTime' => '结束时间',
			'startPage' => '活动主页',
			'closePage' => '活动结束页',
			'status' => '状态',
			'description' => '说明',
			'createrId' => '创建人',
			'createTime' => '创建时间',
			'editorId' => '编辑人',
			'editTime' => '编辑时间',
			'city' => '活动范围',
			'joinCount' => '参加人数',
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

		$criteria->compare('activityType',$this->activityType,true);
		
		$criteria->compare('beginTime',$this->beginTime);

		$criteria->compare('endTime',$this->endTime);

		$criteria->compare('startPage',$this->startPage,true);

		$criteria->compare('closePage',$this->closePage,true);

		$criteria->compare('status',$this->status);

		$criteria->compare('description',$this->description,true);

		$criteria->compare('createrId',$this->createrId,true);

		$criteria->compare('createTime',$this->createTime);

		$criteria->compare('editorId',$this->editorId,true);

		$criteria->compare('editTime',$this->editTime);
		
        $criteria->order = 'id DESC';
        
		return new CActiveDataProvider('activity', array(
			'criteria'=>$criteria,
		));
	}
	
	public function searchAct()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id,true);

		$criteria->compare('name',$this->name,true);

		$criteria->compare('activityType',$this->activityType,true);
		
		$criteria->compare('beginTime',$this->beginTime);

		$criteria->compare('endTime',$this->endTime);

		$criteria->compare('startPage',$this->startPage,true);

		$criteria->compare('closePage',$this->closePage,true);

		$criteria->compare('status',$this->status);

		$criteria->compare('description',$this->description,true);

		$criteria->compare('createrId',$this->createrId,true);

		$criteria->compare('createTime',$this->createTime);

		$criteria->compare('editorId',$this->editorId,true);

		$criteria->compare('editTime',$this->editTime);
		
		$criteria->condition = 'activityType=:activityType';
		$criteria->params = array(':activityType'=>1);		
		
		
        $criteria->order = 'id DESC';
        
        
        
		return new CActiveDataProvider('activity', array(
			'criteria'=>$criteria,
		));
	}	
	
	public function linkMe($text=null)
	{
		$text = $text ? $text:$this->name;
		return CHtml::link($text, array('activity/view', 'id'=>$this->id));
	}
	
	protected function beforeSave(){
		if(empty($this->beginTime)) $this->beginTime = null;
		if(empty($this->endTime)) $this->endTime = null;
		
		return parent::beforeSave();
	}
	
}