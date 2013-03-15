<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'lottery-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

    <div class="row">
        <?php echo $form->labelEx($model,'lotteryactivityId'); ?>
        <?php echo CHtml::encode($model->lotteryActivity->name); ?>
        <?php echo CHtml::activeHiddenField($model,'lotteryactivityId',array('size'=>0,'maxlength'=>0)); ?>
        <?php echo $form->error($model,'groupName'); ?>
    </div>

	<div class="row">
		<?php echo $form->labelEx($model,'name'); ?>
		<?php echo $form->textField($model,'name',array('size'=>60,'maxlength'=>100)); ?>
		<?php echo $form->error($model,'name'); ?>
	</div>


    <div class="row">
        <?php echo $form->labelEx($model,'percent'); ?>
        <?php echo $form->textField($model,'percent',array('size'=>15,'maxlength'=>10)); ?>%
        <?php echo $form->error($model,'percent'); ?>
    </div>
    
    <div class="row">
        <?php echo $form->labelEx($model,'repeat'); ?>
        <?php echo $form->textField($model,'repeat',array('size'=>3,'maxlength'=>2)); ?>【有限制填1，无限制可不填】
        <?php echo $form->error($model,'repeat'); ?>
    </div>
    
    <div class="row">
        <?php echo $form->labelEx($model,'phoneCharge'); ?>
        <?php echo $form->textField($model,'phoneCharge',array('size'=>10,'maxlength'=>10)); ?>元
        <?php echo $form->error($model,'phoneCharge'); ?>
    </div>   
    
    <div class="row">
        <?php echo $form->labelEx($model,'ipTimesLimit'); ?>
        <?php echo $form->textField($model,'ipTimesLimit',array('size'=>10,'maxlength'=>10)); ?>【参与者IP超过此值，不能中此奖项。为0即不限制，需限制时请填写数值(正整数)】<br />同一活动以最小值为准，活动开始后不能更改此值<br /><br />
        <?php echo $form->error($model,'ipTimesLimit'); ?>
    </div>         
    
    <div class="row">
        <?php echo $form->labelEx($model,'limit'); ?>
        <?php echo CHtml::activeDropDownList($model, 'limit', array('1'=>'有限制','2'=>'每天限制','0'=>'无限制')) ?>
        <?php //echo $form->textField($model,'repeat',array('size'=>3,'maxlength'=>2)); ?>
        <?php echo $form->error($model,'limit'); ?>
        
    </div>
    
    <div class="row">
        <?php echo $form->labelEx($model,'smsContent'); ?>
        <?php echo $form->textArea($model,'smsContent',array('rows'=>6, 'cols'=>50)); ?>
        <?php echo $form->error($model,'smsContent'); ?>
    </div>
    
    <div class="row">
        <?php echo $form->labelEx($model,'city'); ?>
        <div>
        <?php echo CHtml::checkBoxList(CHtml::activeName($model, 'city'), explode(',', $model->city), array(
            '广州'=>'广州',
            '佛山市'=>'佛山市',
            '深圳'=>'深圳',
            '肇庆市'=>'肇庆市',
            '揭阳'=>'揭阳',
            '茂名'=>'茂名',
            '江门'=>'江门',
            '珠海'=>'珠海',
            '中山'=>'中山',
            '湛江'=>'湛江',
            '惠州'=>'惠州',
            '东莞'=>'东莞',
            '汕头市'=>'汕头市',
            '阳江'=>'阳江',
            '潮州'=>'潮州',
            '清远'=>'清远',
            '汕尾'=>'汕尾',
            '梅州'=>'梅州',
            '云浮'=>'云浮',
            '河源'=>'河源',
            '韶关市'=>'韶关市',

        ),array('separator'=>' / ')); ?>
        <?php //echo $form->textField($model,'city',array('size'=>60,'maxlength'=>100)); ?>
        <?php echo $form->error($model,'city'); ?>
        </div>
    </div>
    <div class="row">
        <?php echo $form->labelEx($model,'brand'); ?>
        <div>
        <?php echo CHtml::checkBoxList(CHtml::activeName($model, 'brand'), explode(',', $model->brand), array(
            '神州行'=>'神州行',
            '全球通'=>'全球通',
            '动感地带'=>'动感地带',
        ),array('separator'=>' / ')); ?>
        <?php //echo $form->textField($model,'brand',array('size'=>60,'maxlength'=>100)); ?>
        <?php echo $form->error($model,'brand'); ?>
        </div>
    </div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->