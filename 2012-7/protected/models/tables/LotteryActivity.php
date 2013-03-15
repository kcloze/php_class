<?php

/**
 * This is the model class for table "lotteryactivity".
 *
 * The followings are the available columns in table 'lotteryactivity':
 * @property string $id
 * @property string $name
 */
class LotteryActivity extends ActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return lotteryactivity the static model class
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
		return 'lotteryactivity';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name', 'required'),
            array('name', 'length', 'max'=>100),
            array('acttype','length', 'max'=>15),
            array('chargeLimit','length', 'max'=>10),
            array('chargeLimit','numerical', 'integerOnly'=>true),
            array('cardPercent', 'length', 'max'=>10),
            array('cardPercent','numerical', 'min'=>0,'max'=>0.99),
            array('beginTime, endTime, description', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, name', 'safe', 'on'=>'search'),
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
            'name' => '抽奖活动名称',
            'acttype' => 'acttype标记',
			'chargeLimit' => '每客户中话费上限',
			'cardPercent' => '卡商中奖率限制',
			'beginTime' => '开始日期',
            'endTime' => '结束日期',
			'description' => '说明',
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
        $criteria->order = 'id DESC';
		return new CActiveDataProvider('lotteryactivity', array(
			'criteria'=>$criteria,
		));
	}
    
    public function getLotteryCount()
    {
        $c = new CDbCriteria();
        $c->condition = 'lotteryactivityId=?';
        $c->params = array($this->id);
        $count = Lottery::model()->count($c);
        return $count;
    }
    
    protected function beforeSave(){
        if(empty($this->beginTime)) $this->beginTime = null;
        if(empty($this->endTime)) $this->endTime = null;
        
        return parent::beforeSave();
    }

}