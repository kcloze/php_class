<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('lotteryId')); ?>:</b>
	<?php echo CHtml::encode($data->lotteryId); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('date')); ?>:</b>
	<?php echo CHtml::encode($data->date); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('smsContent')); ?>:</b>
	<?php echo CHtml::encode($data->smsContent); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('toPhone')); ?>:</b>
	<?php echo CHtml::encode($data->toPhone); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('sendDatetime')); ?>:</b>
	<?php echo CHtml::encode($data->sendDatetime); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('status')); ?>:</b>
	<?php echo CHtml::encode($data->status); ?>
	<br />

	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('editorId')); ?>:</b>
	<?php echo CHtml::encode($data->editorId); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('editTime')); ?>:</b>
	<?php echo CHtml::encode($data->editTime); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('createrId')); ?>:</b>
	<?php echo CHtml::encode($data->createrId); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('createTime')); ?>:</b>
	<?php echo CHtml::encode($data->createTime); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('remark')); ?>:</b>
	<?php echo CHtml::encode($data->remark); ?>
	<br />

	*/ ?>

</div>