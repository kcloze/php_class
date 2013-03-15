<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('name')); ?>:</b>
	<?php echo CHtml::encode($data->name); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('groupName')); ?>:</b>
	<?php echo CHtml::encode($data->group); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('percent')); ?>:</b>
	<?php echo CHtml::encode($data->percent); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('status')); ?>:</b>
	<?php echo CHtml::encode($data->status); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('editorId')); ?>:</b>
	<?php echo CHtml::encode($data->editorId); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('editTime')); ?>:</b>
	<?php echo CHtml::encode($data->editTime); ?>
	<br />

	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('createrId')); ?>:</b>
	<?php echo CHtml::encode($data->createrId); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('createTime')); ?>:</b>
	<?php echo CHtml::encode($data->createTime); ?>
	<br />

	*/ ?>

</div>