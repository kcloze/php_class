<?php
$this->breadcrumbs=array(
	'活动管理'=>array('admin'),
	$model->name=>array('view','id'=>$model->id),
	'编辑',
);

$this->menu=array(
	array('label'=>'List Activity', 'url'=>array('index')),
	array('label'=>'Create Activity', 'url'=>array('create')),
	array('label'=>'View Activity', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage Activity', 'url'=>array('admin')),
);
?>

<h1>编辑活动 “<?php echo $model->name; ?>”</h1>
<?php echo CHtml::link('查看活动',array('view', 'id'=>$model->id))?>
&nbsp;
&nbsp;
<?php echo CHtml::link('管理活动内容',array('activityresource/admin', 'ActivityResource[activityId]'=>$model->id))?>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>