<div class="wide form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

	<div class="row">
		<?php echo $form->label($model,'id'); ?>
		<?php echo $form->textField($model,'id'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'realname'); ?>
		<?php echo $form->textField($model,'realname'); ?>
	</div>
    <div class="row">
        <?php echo $form->label($model,'IDtype'); ?>
        <?php echo $form->textField($model,'IDtype'); ?>
    </div>
     <div class="row">
        <?php echo $form->label($model,'IDnumber'); ?>
        <?php echo $form->textField($model,'IDnumber'); ?>
    </div>   
	<div class="row">
		<?php echo $form->label($model,'mobile'); ?>
		<?php echo $form->textField($model,'mobile'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'school'); ?>
		<?php echo $form->textField($model,'school'); ?>
	</div>


	<div class="row buttons">
		<?php echo CHtml::submitButton('Search'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->