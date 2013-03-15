<?php

class SmartdbModel extends ActiveRecord
{
	private $_md;
	private static $_models=array();
	
	protected $_tableName = null;
	/**
	 * Returns the static model of the specified AR class.
	 * @return Admin the static model class
	 */
	public static function model($tableName, $className=__CLASS__)
	{
		if(isset(self::$_models[$className][$tableName]))
			return self::$_models[$className][$tableName];
		else
		{
			$model=self::$_models[$className][$tableName]=new $className($tableName, null);
			//var_dump($model->getDbConnection());
			$model->_md=new CActiveRecordMetaData($model);
			$model->attachBehaviors($model->behaviors());
			return $model;
		}
	}
	
	public function __construct($tableName, $scenario='insert'){
		//self::$db=Yii::app()->pwind;
		$this->_tableName = $tableName;
		parent::__construct($scenario);
	}
	
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return $this->_tableName;
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		//var_dump($this->getMetaData());
		$_md = $this->getMetaData();
		$_rules = array();
		$_columns = $_md->tableSchema->columns;
		
		
		foreach($_columns as $key => $column)
		{
			if(in_array($key, array('id','createrId', 'createTime','editorId','editTime'))) continue;
			
			if(!$column->allowNull){
				$_rules[] = array($key, 'required');
			}
			
			preg_match_all('/(.*?)\((\d*?)\)/', $column->dbType, $matches, PREG_SET_ORDER );
			$_dbType = $matches[0][1]; //bigint(20)
			//分析
			$_size = $matches[0][2];
			
			switch ($_dbType){
				case 'bigint':
				case 'int':
					$_rules[] = array($key, 'numerical', 'integerOnly'=>true);
					break;
				case 'varchar':
					$_rules[] = array($key, 'length', 'max'=>$_size);
					break;
				default:
					$_rules[] = array($key, 'safe');
					break;
			}
			
		}
		
		
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return $_rules;
	}
	public function getDbConnection()
	{
		return Yii::app()->db;
		if(self::$db!==null)
			return self::$db;
		else
		{
			self::$db=Yii::app()->db;
			if(self::$db instanceof CDbConnection)
			{
				self::$db->setActive(true);
				return self::$db;
			}
			else
				throw new CDbException(Yii::t('yii','Active Record requires a "db" CDbConnection application component.'));
		}
	}	

	public function getMetaData()
	{
		if($this->_md!==null)
			return $this->_md;
		else
			return $this->_md=self::model($this->_tableName, get_class($this))->_md;
	}
	
	protected function instantiate($attributes)
	{
		$class=get_class($this);
		$model=new $class($this->_tableName, null);
		return $model;
	}
	
	public function echostr(){
		echo 'yes here';
	}
}