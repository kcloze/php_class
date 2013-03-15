<?php

/**
 * This is the model class for table "lotterysms".
 *
 * The followings are the available columns in table 'lotterysms':
 * @property string $id
 * @property string $lotteryId
 * @property string $date
 * @property string $smsContent
 * @property string $toPhone
 * @property string $sendDatetime
 * @property string $status
 * @property string $editorId
 * @property integer $editTime
 * @property string $createrId
 * @property integer $createTime
 * @property string $remark
 */
class LotterySms extends ActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return lotterysms the static model class
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
		return 'lotterysms';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('lotteryId, date', 'required'),
			array('editTime, createTime', 'numerical', 'integerOnly'=>true),
			array('lotteryId, toPhone, status, editorId, createrId', 'length', 'max'=>20),
			array('smsContent', 'length', 'max'=>300),
			array('sendDatetime, remark', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, lotteryId, date, smsContent, toPhone, sendDatetime, status, editorId, editTime, createrId, createTime, remark', 'safe', 'on'=>'search'),
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
            'lottery'=>array(self::BELONGS_TO, 'Lottery', 'lotteryId'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'Id',
			'lotteryId' => '所属奖项',
			'date' => '允许发送日期',
			'smsContent' => '奖项发送内容',
			'toPhone' => '接收手机号码',
			'sendDatetime' => '发送时间',
			'status' => 'Status',
			'editorId' => 'Editor',
			'editTime' => 'Edit Time',
			'createrId' => 'Creater',
			'createTime' => 'Create Time',
			'remark' => '备注',
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

		$criteria->compare('lotteryId',$this->lotteryId,false);

		$criteria->compare('date',$this->date,true);

		$criteria->compare('smsContent',$this->smsContent,true);

		$criteria->compare('toPhone',$this->toPhone,true);

		$criteria->compare('sendDatetime',$this->sendDatetime,true);

		$criteria->compare('status',$this->status,true);

		$criteria->compare('editorId',$this->editorId,true);

		$criteria->compare('editTime',$this->editTime);

		$criteria->compare('createrId',$this->createrId,true);

		$criteria->compare('createTime',$this->createTime);

		$criteria->compare('remark',$this->remark,true);
        $criteria->order = 'editTime DESC';
		return new CActiveDataProvider('lotterysms', array(
			'criteria'=>$criteria,
            'pagination'=>array('pageSize'=>100),
		));
	}
}