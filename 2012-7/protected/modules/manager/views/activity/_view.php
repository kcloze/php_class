<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('name')); ?>:</b>
	<?php echo CHtml::encode($data->name); ?>
	<br />	

	<b><?php echo CHtml::encode($data->getAttributeLabel('beginTime')); ?>:</b>
	<?php echo CHtml::encode($data->beginTime); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('endTime')); ?>:</b>
	<?php echo CHtml::encode($data->endTime); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('startPage')); ?>:</b>
	<?php echo CHtml::encode($data->startPage); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('closePage')); ?>:</b>
	<?php echo CHtml::encode($data->closePage); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('status')); ?>:</b>
	<?php echo CHtml::encode($data->status); ?>
	<br />

	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('description')); ?>:</b>
	<?php echo CHtml::encode($data->description); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('createrId')); ?>:</b>
	<?php echo CHtml::encode($data->createrId); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('createTime')); ?>:</b>
	<?php echo CHtml::encode($data->createTime); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('editorId')); ?>:</b>
	<?php echo CHtml::encode($data->editorId); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('editTime')); ?>:</b>
	<?php echo CHtml::encode($data->editTime); ?>
	<br />

	*/ ?>

</div>