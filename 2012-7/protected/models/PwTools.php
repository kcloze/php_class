<?php

/**
 * This is the model class for table "pw_tools".
 *
 * The followings are the available columns in table 'pw_tools':
 * @property integer $id
 * @property integer $ttype
 * @property string $name
 * @property string $filename
 * @property string $descrip
 * @property integer $vieworder
 * @property string $logo
 * @property integer $state
 * @property string $price
 * @property string $creditype
 * @property integer $type
 * @property integer $stock
 * @property string $conditions
 * @property integer $tool_validate
 * @property integer $tool_validate_date
 * @property string $tflag
 */
class PwTools extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return PwTools the static model class
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
		return 'pw_tools';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('ttype, conditions, tool_validate_date', 'required'),
			array('ttype, vieworder, state, type, stock, tool_validate, tool_validate_date', 'numerical', 'integerOnly'=>true),
			array('name, filename', 'length', 'max'=>20),
			array('descrip, price', 'length', 'max'=>255),
			array('logo', 'length', 'max'=>100),
			array('creditype', 'length', 'max'=>10),
			array('tflag', 'length', 'max'=>15),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, ttype, name, filename, descrip, vieworder, logo, state, price, creditype, type, stock, conditions, tool_validate, tool_validate_date, tflag', 'safe', 'on'=>'search'),
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
			'ttype' => 'Ttype',
			'name' => 'Name',
			'filename' => 'Filename',
			'descrip' => 'Descrip',
			'vieworder' => 'Vieworder',
			'logo' => 'Logo',
			'state' => 'State',
			'price' => 'Price',
			'creditype' => 'Creditype',
			'type' => 'Type',
			'stock' => 'Stock',
			'conditions' => 'Conditions',
			'tool_validate' => 'Tool Validate',
			'tool_validate_date' => 'Tool Validate Date',
			'tflag' => 'Tflag',
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
		$criteria->compare('ttype',$this->ttype);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('filename',$this->filename,true);
		$criteria->compare('descrip',$this->descrip,true);
		$criteria->compare('vieworder',$this->vieworder);
		$criteria->compare('logo',$this->logo,true);
		$criteria->compare('state',$this->state);
		$criteria->compare('price',$this->price,true);
		$criteria->compare('creditype',$this->creditype,true);
		$criteria->compare('type',$this->type);
		$criteria->compare('stock',$this->stock);
		$criteria->compare('conditions',$this->conditions,true);
		$criteria->compare('tool_validate',$this->tool_validate);
		$criteria->compare('tool_validate_date',$this->tool_validate_date);
		$criteria->compare('tflag',$this->tflag,true);

		return new CActiveDataProvider(get_class($this), array(
			'criteria'=>$criteria,
		));
	}
}