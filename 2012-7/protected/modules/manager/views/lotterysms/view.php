<?php
$this->breadcrumbs=array(
	'Lotterysms'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List lotterysms', 'url'=>array('index')),
	array('label'=>'Create lotterysms', 'url'=>array('create')),
	array('label'=>'Update lotterysms', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete lotterysms', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage lotterysms', 'url'=>array('admin')),
);
?>

<h1>View lotterysms #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'lotteryId',
		'date',
		'smsContent',
		'toPhone',
		'sendDatetime',
		'status',
		'editorId',
		'editTime',
		'createrId',
		'createTime',
		'remark',
	),
)); ?>
