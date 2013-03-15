<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'activity-resource-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>
	<?php echo CHtml::activeHiddenField($model,'writekey'); ?>
	<div class="row">
		<?php echo $form->labelEx($model,'activityId'); ?>
		<?php 
		$model_id = isset($_GET['activityId']) && $_GET['activityId'] ? $_GET['activityId'] : $model->activityId;
		$activity = Activity::model()->findByPk($model_id);
		if($activity):
		?>
			<?php echo $activity->linkMe();?>
			<?php echo CHtml::hiddenField(CHtml::activeName($model, 'activityId'), $model_id);?>
		<?php else:?>
			<?php echo $form->textField($model,'activityId',array('size'=>20,'maxlength'=>20)); ?>
		<?php endif;?>
		<?php echo $form->error($model,'activityId'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'name'); ?>
		<?php echo $form->dropDownList($model,'type',array(
		'Page'=>'页面',
		'Html'=>'模块',
		'Script'=>'脚本',
		'Wml'=>'WAP',
		)); ?>
		<?php echo $form->textField($model,'name',array('size'=>60,'maxlength'=>200)); ?>
		<?php echo $form->error($model,'name'); ?>
		<?php echo $form->error($model,'type'); ?>
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($model,'key'); ?>
		<?php echo $form->textField($model,'key',array('size'=>30,'maxlength'=>40)); ?>
		<?php echo $form->error($model,'key'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'description'); ?>
		<?php echo $form->textArea($model,'description',array('rows'=>3, 'cols'=>50)); ?>
		<?php echo $form->error($model,'description'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'content'); ?>
		<?php 
		echo $form->textArea($model,'content',array('rows'=>70, 'cols'=>100));
		/* switch($model->type){
			case 'Page':
				echo MHtml::activeTinyMceTextarea($model,'content',array('rows'=>20, 'cols'=>90, 'fullPage'=>true));
				echo CHtml::hiddenField('folder', 'activity/'. $activity->id . '/');
				break;
			case 'Html':
				echo MHtml::activeTinyMceTextarea($model,'content',array('rows'=>20, 'cols'=>90, 'fullPage'=>false));
				echo CHtml::hiddenField('folder', 'activity/'. $activity->id . '/');
				break;
			case 'Wml':
			case 'Script':
			default:
				echo $form->textArea($model,'content',array('rows'=>20, 'cols'=>90));
				break;
		} */?>
		<?php echo $form->error($model,'content'); ?>
	</div>
<script type="text/javascript">
function toggleEditor(id) {
	if (!tinyMCE.get(id))
		tinyMCE.execCommand('mceAddControl', false, id);
	else
		tinyMCE.execCommand('mceRemoveControl', false, id);
}
</script>
<a href="javascript:toggleEditor('<?php echo MHtml::activeId($model, 'content')?>');">Add/Remove editor</a>

	
	<div class="row">
		<?php echo $form->labelEx($model,'beginTime'); ?>
		<?php echo MHtml::activeDateField($model,'beginTime'); ?>
		<?php echo $form->error($model,'beginTime'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'endTime'); ?>
		<?php echo MHtml::activeDateField($model,'endTime'); ?>
		<?php echo $form->error($model,'endTime'); ?>
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