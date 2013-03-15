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
		<?php echo $form->textField($model,'name',array('size'=>60,'maxlength'=>100)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'groupName'); ?>
		<?php echo $form->textField($model,'groupName',array('size'=>0,'maxlength'=>0)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'percent'); ?>
		<?php echo $form->textField($model,'percent',array('size'=>10,'maxlength'=>10)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'status'); ?>
		<?php echo $form->textField($model,'status',array('size'=>20,'maxlength'=>20)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'editorId'); ?>
		<?php echo $form->textField($model,'editorId',array('size'=>20,'maxlength'=>20)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'editTime'); ?>
		<?php echo $form->textField($model,'editTime'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'createrId'); ?>
		<?php echo $form->textField($model,'createrId',array('size'=>20,'maxlength'=>20)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'createTime'); ?>
		<?php echo $form->textField($model,'createTime'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Search'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->