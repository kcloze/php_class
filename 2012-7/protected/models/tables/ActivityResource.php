<?php

/**
 * This is the model class for table "ActivityResource".
 *
 * The followings are the available columns in table 'ActivityResource':
 * @property string $id
 * @property string $name
 * @property string $description
 * @property string $content
 * @property string $type
 * @property string $createrId
 * @property integer $createTime
 * @property string $editorId
 * @property integer $editTime
 */
class ActivityResource extends ActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return ActivityResource the static model class
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
		return 'activityresource';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name, activityId', 'required'),
			array('activityId, createrId, editorId, createTime, editTime', 'numerical', 'integerOnly'=>true),
			array('name', 'length', 'max'=>40),
			array('key, writekey', 'length', 'max'=>40),
			array('description', 'length', 'max'=>100),
			array('type, status', 'length', 'max'=>20),
			array('content, beginTime, endTime', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, name, description, content, type, createrId, createTime, editorId, editTime', 'safe', 'on'=>'search'),
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
			'id' => '编号',
			'name' => '名称',
			'description' => '说明',
			'content' => '内容',
			'type' => '类型',
			'key' => '访问路径',
			'beginTime' => '开始时间',
			'endTime' => '结束时间',
			'status' => '状态',
			'createrId' => '创建人',
			'createTime' => '创建时间',
			'editorId' => '编辑人',
			'editTime' => '编辑时间',
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

		$criteria->compare('activityId',$this->activityId,FALSE);
		
		$criteria->compare('name',$this->name,true);

		$criteria->compare('description',$this->description,true);

		$criteria->compare('content',$this->content,true);

		$criteria->compare('type',$this->type,true);

		$criteria->compare('createrId',$this->createrId,true);

		$criteria->compare('createTime',$this->createTime);

		$criteria->compare('editorId',$this->editorId,true);

		$criteria->compare('editTime',$this->editTime);
		
		$cdp = new CActiveDataProvider('ActivityResource', array(
			'criteria'=>$criteria,
		));
		
		$p = new CPagination(ActivityResource::model()->count($criteria));
        $p->setPageSize(100);
        
        $cdp->setPagination( $p );
		
		return $cdp; 
	}
	
	protected function beforeSave(){
		if(empty($this->beginTime)) $this->beginTime = null;
		if(empty($this->endTime)) $this->endTime = null;
		
		return parent::beforeSave();
	}
	
	public function linkMe($text=null)
	{
		$text = $text ? $text:$this->name;
		return CHtml::link($text, array('activityresource/view', 'id'=>$this->id));
	}
	
	public function render($debug = false)
	{
		//如果这个资源状态是未发布，或者已过期
		if($this->status != 'publish' || ($this->beginTime != null && strtotime($this->beginTime)>mktime())
			|| ($this->endTime != null && strtotime($this->endTime)<mktime()))
		{
			if($this->type == 'page'){
				throw new CHttpException(404,'The requested page does not exist.');
				return;
			}
			return '';
		}
		
		if($debug){
			Yii::app()->clientScript->registerScriptFile($baseUrl . '/js/debug.js');	
		}
		
		switch($this->type){
			case 'Wml':
				Header("Content-type: text/vnd.wap.wml;charset=Utf-8");  
				Header("Cache-Control: no-cache, must-revalidate");  
				Header("Pragma: no-cache");  
				return $this->renderPage($debug);
			case 'Page':
				return $this->renderPage($debug);
			case 'Html':
				return $this->parseContent($debug);
			case 'Script':
				return $this->renderScript($debug);
		}
	}
	
	private function renderPage($debug){
		$_html = $this->parseContent($debug);
		$_pageName = 'page_' . $this->id . '.php';
		file_put_contents(Yii::app()->basePath . '/cache/' . $_pageName, $_html);
		include 'protected/cache/'.$_pageName;
	}
	
	private function renderScript($debug){
		$_scriptName = 'script_' . $this->id . '.php';
		file_put_contents(Yii::app()->basePath . '/cache/' . $_scriptName, $this->content);
		$_html = '<?php include "protected/cache/'. $_scriptName . '" ?>';
		$_html = $debug?"<div class=\"debug\" rsid=\"{$this->id}\">".$_html."</div>":$_html;
		return $_html;
	}
	
	private function parseContent($debug = false)
	{
		$_content = $this->content;
		//分析脚本
		
		
		//分析模块
		//$matchs = null;
		preg_match_all('/([\[|{]{2})(.*?)([\]|}]{2})/', $_content, $matches, PREG_SET_ORDER );
		
		//需要过滤掉$matches中重复的
		
		foreach($matches as $match){
			$rs = ActivityResource::model()->find('name=?', array($match[2]));
			if($rs){
				switch ($match[1]){
					case '[[':
						$_html = $debug?"<div class=\"debug\" rsid=\"{$this->id}\">".$rs->render($debug)."</div>":$rs->render($debug);
						$_content = str_replace($match[0], $_html, $_content);
					case '{{':
						$_content = str_replace($match[0], $rs->getUrl(), $_content);	
				}
			}
		}
		
		return $_content;
	}
	
	public function getPagesByActivity($activityId)
	{
		$c = new CDbCriteria();
		$c->condition = "activityId=:activityId AND type='Page'";
		$c->params = array(':activityId'=>$activityId);
		$results = $this->findAll($c);
		$data = array();
		foreach($results as $row)
		{
			$data[$row->id] = $row->name;
		}
		//var_dump($data);die;
		return $data;
	}
	
	public function getUrl()
	{
		return Yii::app()->params['siteUrl'] . '/' . $this->activity->key . '/' . ($this->key?$this->key:$this->id);
	}
}