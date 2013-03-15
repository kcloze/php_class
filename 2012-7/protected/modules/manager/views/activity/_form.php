
<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'activity-form',
	'enableAjaxValidation'=>false,
)); ?>

<?php 
$pages = ActivityResource::model()->getPagesByActivity($model->id);
$pages = array_reverse($pages,TRUE);
$pages['']='';
$pages = array_reverse($pages,TRUE);
?>
	<p class="note"><span class="required">*</span>带星号的是必填项目</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'name'); ?>
		<?php echo $form->textField($model,'name',array('size'=>60,'maxlength'=>200)); ?>
		<?php echo $form->error($model,'name'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'activityType'); ?>
		<?php echo $form->textField($model,'activityType',array('size'=>60,'maxlength'=>200)); ?>
		<?php echo $form->error($model,'activityType'); ?>
	</div>

    <div class="row">
        <?php echo $form->labelEx($model,'activityType'); ?>
        <?php echo CHtml::activeDropDownList($model, 'activityType', array('0'=>'活动','1'=>'专题')) ?> 0:活动，1：专题
        <?php //echo $form->textField($model,'repeat',array('size'=>3,'maxlength'=>2)); ?>
        <?php echo $form->error($model,'activityType'); ?>
        
    </div>

	
	<div class="row">
		<?php echo $form->labelEx($model,'key'); ?>
		<?php echo $form->textField($model,'key',array('size'=>30,'maxlength'=>40)); ?>
		<?php echo $form->error($model,'key'); ?>
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($model,'description'); ?>
		<?php echo $form->textArea($model,'description',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'description'); ?>
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($model,'city'); ?>
		<?php echo $form->textField($model,'city',array('size'=>40,'maxlength'=>200)); ?>
		<?php echo $form->error($model,'city'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'beginTime'); ?>
		<?php 
		$this->widget('ext.my97.JMy97DatePicker',array(
	    'name'=>CHtml::activeName($model,'beginTime'),
	    'value'=>$model->beginTime,
	    'options'=>array('dateFmt'=>'yyyy-MM-dd HH:mm:ss'),
	    ));; 
	    ?>
		<?php echo $form->error($model,'beginTime'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'endTime'); ?>
		<?php
		$this->widget('ext.my97.JMy97DatePicker',array(
	    'name'=>CHtml::activeName($model,'endTime'),
	    'value'=>$model->endTime,
	    'options'=>array('dateFmt'=>'yyyy-MM-dd HH:mm:ss'),
	    ));;  ?>
		<?php echo $form->error($model,'endTime'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'startPage'); ?>
		<?php echo $form->dropDownList($model,'startPage',$pages); ?>
		<?php echo $form->error($model,'startPage'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'closePage'); ?>
		<?php echo $form->dropDownList($model,'closePage',$pages); ?>
		<?php echo $form->error($model,'closePage'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'joinCount'); ?>
		<?php echo $form->textField($model,'joinCount',array('size'=>10,'maxlength'=>200)); ?>
		<?php echo $form->error($model,'joinCount'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'status'); ?>
		<?php echo $form->dropDownList($model,'status',array(
		'publish'=>'发布',
		'close'=>'关闭',
		)); ?>
		<?php echo $form->error($model,'status'); ?>
	</div>


	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->