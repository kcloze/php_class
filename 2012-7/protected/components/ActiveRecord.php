<?php
class ActiveRecord extends CActiveRecord{
    public function behaviors()
    {
        return array(
            // Classname => path to Class
            'ActiveRecordDateBehavior'=>
                'application.behaviors.ActiveRecordDateBehavior',
    	);
    }
     
    public function __get($name){
        switch($name){
            case 'creater':
                $model = Admin::model()->findByPk($this->createrId); 
                return $model!=null?$model->username:'';
                break;
            case 'editor':
                $model = Admin::model()->findByPk($this->editorId); 
                return $model!=null?$model->username:'';
                break;
        }
        return parent::__get($name);
    }    
}