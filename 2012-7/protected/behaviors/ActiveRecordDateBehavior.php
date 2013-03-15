<?php
class ActiveRecordDateBehavior extends CActiveRecordBehavior
{
 
    public function beforeSave($event)
    {
        if ($this->Owner->isNewRecord) {
            if($this->Owner->hasAttribute('createTime')){
                $this->Owner->createTime = time();
            }
            if($this->Owner->hasAttribute('createrId')){
               $this->Owner->createrId = Yii::app()->user->id;
            }
        } else {
            if($this->Owner->hasAttribute('editTime')){
                $this->Owner->editTime = time();
            }
            if($this->Owner->hasAttribute('editorId')){
               $this->Owner->editorId = Yii::app()->user->id;
            }
         }
    }
    
}
?>
