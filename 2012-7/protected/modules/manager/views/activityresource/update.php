<?php
$this->breadcrumbs=array(
	'活动管理'=>array('activity/admin'),
	$model->activity->name=>array('activityresource/admin', 'ActivityResource[activityId]'=>$model->activityId),
	$model->name=>array('view','id'=>$model->id),
	'编辑',
);

$this->menu=array(
	array('label'=>'List ActivityResource', 'url'=>array('index')),
	array('label'=>'Create ActivityResource', 'url'=>array('create')),
	array('label'=>'View ActivityResource', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage ActivityResource', 'url'=>array('admin')),
);
?>

<h1>编辑“<?php echo $model->activity->name; ?>”</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>