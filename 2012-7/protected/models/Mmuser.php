<?php

/**
 * This is the model class for table "activity".
 *
 * The followings are the available columns in table 'activity':
 * @property integer $id
 * @property string $realname
 * @property string $IDtype
 * @property string $IDnumber
 * @property integer $mobile
 * @property string $school
 * @property integer $user_id
 */
class Mmuser extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return Activity the static model class
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
		return 'mm_user';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
        return array(
            array('realname, IDtype,IDnumber,school,mobile', 'required'),
            array('realname', 'length', 'max'=>20),
            array('IDtype', 'length', 'max'=>16),
            array('IDtype','in','range'=>array('身份证'=>'身份证','护照'=>'护照')),
            array('IDnumber', 'length', 'max'=>18),
            array('mobile','length','min'=>11,'max'=>15),
            array('school', 'length', 'max'=>50),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id,realname, school, mobile,IDtype,IDnumber,user_id,regdate', 'safe', 'on'=>'search'),
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
            'realname' => '真实姓名',
            'IDtype' => '证件类型',
            'IDnumber' => '证件号码',
            'mobile' => '联系方式',
            'school' => '学校',
            'user_id' => '用户帐号',
			'regdate' => '报名时间',
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

		$criteria->compare('realname',$this->realname,true);

		$criteria->compare('IDtype',$this->IDtype);

		$criteria->compare('IDnumber',$this->IDnumber,true);

		$criteria->compare('mobile',$this->mobile);

		$criteria->compare('school',$this->school,true);
        $criteria->compare('user_id',$this->user_id,true);
		$criteria->compare('regdate',$this->user_id,true);

		return new CActiveDataProvider('Mmuser', array(
			'criteria'=>$criteria,
		));
	}
}
