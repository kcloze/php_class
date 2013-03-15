<?php
if($_GET['activityId']){
	$activity = Activity::model()->findByPk($_GET['activityId']);
}


$this->breadcrumbs=array(
	'活动管理'=>array('activity/admin'),
	$activity->name=>array('activityresource/admin', 'ActivityResource[activityId]'=>$model->activityId),
	'创建',
);

$this->menu=array(
	array('label'=>'List ActivityResource', 'url'=>array('index')),
	array('label'=>'Manage ActivityResource', 'url'=>array('admin')),
);
?>

<h1>创建活动“<?php echo $activity->name?>”的内容</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>