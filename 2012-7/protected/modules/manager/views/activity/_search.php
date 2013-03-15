<div class="wide form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

	<div class="row">
		<?php echo $form->label($model,'id'); ?>
		<?php echo $form->textField($model,'id',array('size'=>20,'maxlength'=>20)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'name'); ?>
		<?php echo $form->textField($model,'name',array('size'=>60,'maxlength'=>200)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'beginTime'); ?>
		<?php echo $form->textField($model,'beginTime'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'endTime'); ?>
		<?php echo $form->textField($model,'endTime'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'startPage'); ?>
		<?php echo $form->textField($model,'startPage',array('size'=>20,'maxlength'=>20)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'closePage'); ?>
		<?php echo $form->textField($model,'closePage',array('size'=>20,'maxlength'=>20)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'status'); ?>
		<?php echo $form->textField($model,'status'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'description'); ?>
		<?php echo $form->textArea($model,'description',array('rows'=>6, 'cols'=>50)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'createrId'); ?>
		<?php echo $form->textField($model,'createrId',array('size'=>20,'maxlength'=>20)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'createTime'); ?>
		<?php echo $form->textField($model,'createTime'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'editorId'); ?>
		<?php echo $form->textField($model,'editorId',array('size'=>20,'maxlength'=>20)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'editTime'); ?>
		<?php echo $form->textField($model,'editTime'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Search'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->