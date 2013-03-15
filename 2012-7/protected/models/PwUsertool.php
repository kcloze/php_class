<?php

/**
 * This is the model class for table "pw_usertool".
 *
 * The followings are the available columns in table 'pw_usertool':
 * @property integer $id
 * @property integer $uid
 * @property integer $tstate
 * @property integer $toolid
 * @property integer $nums
 * @property integer $sellnums
 * @property string $stype
 * @property string $sellprice
 * @property string $buydate
 * @property string $usedate
 * @property string $enddate
 */
class PwUsertool extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return PwUsertool the static model class
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
		return 'pw_usertool';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('tstate, buydate', 'required'),
			array('uid, tstate, toolid, nums, sellnums', 'numerical', 'integerOnly'=>true),
			array('stype', 'length', 'max'=>6),
			array('sellprice', 'length', 'max'=>255),
			array('usedate, enddate', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, uid, tstate, toolid, nums, sellnums, stype, sellprice, buydate, usedate, enddate', 'safe', 'on'=>'search'),
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
			'id' => 'ID',
			'uid' => 'Uid',
			'tstate' => 'Tstate',
			'toolid' => 'Toolid',
			'nums' => 'Nums',
			'sellnums' => 'Sellnums',
			'stype' => 'Stype',
			'sellprice' => 'Sellprice',
			'buydate' => 'Buydate',
			'usedate' => 'Usedate',
			'enddate' => 'Enddate',
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

		$criteria->compare('id',$this->id);
		$criteria->compare('uid',$this->uid);
		$criteria->compare('tstate',$this->tstate);
		$criteria->compare('toolid',$this->toolid);
		$criteria->compare('nums',$this->nums);
		$criteria->compare('sellnums',$this->sellnums);
		$criteria->compare('stype',$this->stype,true);
		$criteria->compare('sellprice',$this->sellprice,true);
		$criteria->compare('buydate',$this->buydate,true);
		$criteria->compare('usedate',$this->usedate,true);
		$criteria->compare('enddate',$this->enddate,true);

		return new CActiveDataProvider(get_class($this), array(
			'criteria'=>$criteria,
		));
	}
}