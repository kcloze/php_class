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
		<?php echo $form->label($model,'lotteryId'); ?>
		<?php echo $form->textField($model,'lotteryId',array('size'=>20,'maxlength'=>20)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'date'); ?>
		<?php echo $form->textField($model,'date'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'smsContent'); ?>
		<?php echo $form->textArea($model,'smsContent',array('rows'=>6, 'cols'=>50)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'toPhone'); ?>
		<?php echo $form->textField($model,'toPhone',array('size'=>20,'maxlength'=>20)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'sendDatetime'); ?>
		<?php echo $form->textField($model,'sendDatetime'); ?>
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

	<div class="row">
		<?php echo $form->label($model,'remark'); ?>
		<?php echo $form->textArea($model,'remark',array('rows'=>6, 'cols'=>50)); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Search'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->