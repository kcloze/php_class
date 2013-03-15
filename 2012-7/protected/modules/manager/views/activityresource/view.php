<?php
$this->breadcrumbs=array(
	'Activity Resources'=>array('index'),
	$model->name,
);

$this->menu=array(
	array('label'=>'List ActivityResource', 'url'=>array('index')),
	array('label'=>'Create ActivityResource', 'url'=>array('create')),
	array('label'=>'Update ActivityResource', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete ActivityResource', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage ActivityResource', 'url'=>array('admin')),
);
?>

<h1>View ActivityResource #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'name',
		'description',
		'content',
		'type',
		'createrId',
		'createTime',
		'editorId',
		'editTime',
	),
)); ?>
