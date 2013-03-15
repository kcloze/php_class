<?php

/**
 * This is the model class for table "lottery".
 *
 * The followings are the available columns in table 'lottery':
 * @property string $id
 * @property string $name
 * @property string $group
 * @property string $percent
percent
 * @property string $status
 * @property string $editorId
 * @property integer $editTime
 * @property string $createrId
 * @property integer $createTime
 */
class Lottery extends ActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return lottery the static model class
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
		return 'lottery';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name, lotteryactivityId, percent', 'required'),
			array('editTime, createTime, status, repeat, limit,phoneCharge,ipTimesLimit', 'numerical', 'integerOnly'=>true),
            array('name', 'length', 'max'=>100),
            array('phoneCharge', 'length', 'max'=>10),
            array('smsContent', 'length', 'max'=>300),
			array('percent', 'length', 'max'=>10),
			array('editorId, createrId, lotteryactivityId', 'length', 'max'=>20),
            array('city, brand', 'safe'),
            
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, name, group, percent, status, editorId, editTime, createrId, createTime, lotteryactivityId', 'safe', 'on'=>'search'),
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
            'lotteryActivity'=>array(self::BELONGS_TO, 'LotteryActivity', 'lotteryactivityId'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'Id',
			'name' => '奖项名称',
			'lotteryactivityId' => '所属抽奖活动',
			'percent' => '中奖率',
			'phoneCharge' => '等价话费金额',
			'ipTimesLimit' => '中奖IP限制',
			'status' => 'Status',
			'editorId' => 'Editor',
			'editTime' => 'Edit Time',
			'createrId' => 'Creater',
			'createTime' => 'Create Time',
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

		$criteria->compare('`groupName`',$this->groupName,true);

		$criteria->compare('percent',$this->percent,true);

		$criteria->compare('status',$this->status,true);

		$criteria->compare('editorId',$this->editorId,true);

		$criteria->compare('editTime',$this->editTime);

		$criteria->compare('createrId',$this->createrId,true);

        $criteria->compare('createTime',$this->createTime);
        
        $criteria->compare('lotteryactivityId',$this->lotteryactivityId);

		return new CActiveDataProvider('lottery', array(
			'criteria'=>$criteria,
		));
	}
    
    public function getSendSmsCount()
    {
        return -1;
        $c = new CDbCriteria();
        $c->condition = 'status = 1 and lotteryId=? and toPhone is not null';
        $c->params = array($this->id);
        return LotterySms::model()->count($c);
    }
    
    public function getSmsCount()
    {
        return -1;
        /*
        $c = new CDbCriteria();
        $c->condition = 'status = 1 and lotteryId=?';
        $c->params = array($this->id);
        return LotterySms::model()->count($c);
        */
    }
    
    protected function beforeSave(){
        if(is_array($this->brand)) {
            $this->brand = implode(',' , $this->brand);    
        }
        if(is_array($this->city)) {
            $this->city = implode(',' , $this->city);
        }
        return parent::beforeSave();
    }
    
    public function getLotteries($lotteryActivityId)
    {
        $c = new CDbCriteria();
        $c->condition = 'lotteryactivityId = ?';
        $c->params = array($lotteryActivityId);
        
        $data = array();
        $results = Lottery::model()->findAll($c);
        foreach($results as $result)
        {
            $data[$result->id] = $result->name;
        }
        return $data;
    }
    
}