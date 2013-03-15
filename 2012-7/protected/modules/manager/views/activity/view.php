<?php
$this->breadcrumbs=array(
	'活动管理'=>array('admin'),
	$model->name,
);

$this->menu=array(
	array('label'=>'List Activity', 'url'=>array('index')),
	array('label'=>'Create Activity', 'url'=>array('create')),
	array('label'=>'Update Activity', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete Activity', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Activity', 'url'=>array('admin')),
	array('label'=>'List All pages', 'url'=>array('activitypage/admin', 'ActivityPage[activityId]'=>$model->id)),
);
?>

<h1><?php echo $model->name; ?></h1>
<?php echo CHtml::link('编辑活动',array('update', 'id'=>$model->id))?>
&nbsp;
&nbsp;
<?php echo CHtml::link('管理活动内容',array('activityresource/admin', 'ActivityResource[activityId]'=>$model->id))?>



<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'name',
		'key',
		'beginTime',
		'endTime',
		'startPage',
		'closePage',
		'status',
		'description',
		'createrId',
		'createTime',
		'editorId',
		'editTime',
	),
)); ?>
