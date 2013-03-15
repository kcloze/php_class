<?php 
$dateId = CHtml::activeId($model, 'date');
$script = <<<EOF
$('#{$dateId}').datepicker({ dateFormat: 'yy-mm-dd' });
EOF;
$this->clientScript->registerScript('lotterysms-form-js', $script);
?>


<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'lotterysms-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'lotteryId'); ?>
        <?php echo CHtml::encode($model->lottery->lotteryActivity->name . ' >> ' .$model->lottery->name); ?>
        <?php echo CHtml::activeHiddenField($model,'lotteryId'); ?>
		
		<?php echo $form->error($model,'lotteryId'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'date'); ?>
		<?php echo $form->textField($model,'date'); ?>
		<?php echo $form->error($model,'date'); ?>
	</div>
    
    <div class="row">
        <?php echo $form->labelEx($model,'remark'); ?>
        <?php echo $form->textArea($model,'remark',array('rows'=>6, 'cols'=>50)); ?>
        <?php echo $form->error($model,'remark'); ?>
    </div>
    
    <?php if($model->isNewRecord): ?>
	<div class="row">
		<?php echo CHtml::encode('重复'); ?>
		<?php echo CHtml::textField('count', 1); ?>
	</div>
    <?php endif ?>
    
	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->