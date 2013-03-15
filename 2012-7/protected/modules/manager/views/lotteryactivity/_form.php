<?php 
$beginTimeId = CHtml::activeId($model, 'beginTime');
$endTimeId = CHtml::activeId($model, 'endTime');
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'lotteryactivity-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'name'); ?>
		<?php echo $form->textField($model,'name',array('size'=>60,'maxlength'=>100)); ?>
		<?php echo $form->error($model,'name'); ?>
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($model,'acttype'); ?>
		<?php echo $form->textField($model,'acttype',array('size'=>60,'maxlength'=>100)); ?>如果需要限制IP参与人次，此项必填，且标记名须一致
		<?php echo $form->error($model,'acttype'); ?>
	</div>	
	
	<div class="row">
		<?php echo $form->labelEx($model,'chargeLimit'); ?>
		<?php echo $form->textField($model,'chargeLimit',array('size'=>60,'maxlength'=>10)); ?>元
		<?php echo $form->error($model,'chargeLimit'); ?>
	</div>	
	
	<div class="row">
		<?php echo $form->labelEx($model,'cardPercent'); ?>
		<?php echo $form->textField($model,'cardPercent',array('size'=>60,'maxlength'=>10)); ?>% 当此值为0[包括0.000000]时，则卡商的中奖率与普通用户相同。
		<br /> 如果此值超过奖品中奖率，此设定无效!【最大值：0.99,最小值0】
		<?php echo $form->error($model,'cardPercent'); ?>
	</div>		
    
    <div class="row">
        <?php echo $form->labelEx($model,'description'); ?>
        <?php echo $form->textArea($model,'description',array('rows'=>6, 'cols'=>50)); ?>
        <?php echo $form->error($model,'description'); ?>
    </div>
    
    <div class="row">
        <?php echo $form->labelEx($model,'beginTime'); ?>
        <?php
        $this->widget('ext.my97.JMy97DatePicker',array(
	    'name'=>CHtml::activeName($model,'beginTime'),
	    'value'=>$model->beginTime,
	    'options'=>array('dateFmt'=>'yyyy-MM-dd HH:mm:ss'),
	    )); 
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
	    ));  
         ?>
        <?php echo $form->error($model,'endTime'); ?>
    </div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->