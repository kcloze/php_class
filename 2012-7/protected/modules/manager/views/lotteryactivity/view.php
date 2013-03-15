<?php
$this->breadcrumbs=array(
	'Lotteryactivitys'=>array('index'),
	$model->name,
);

$this->menu=array(
	array('label'=>'List lotteryactivity', 'url'=>array('index')),
	array('label'=>'Create lotteryactivity', 'url'=>array('create')),
	array('label'=>'Update lotteryactivity', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete lotteryactivity', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage lotteryactivity', 'url'=>array('admin')),
);
?>

<h1>View lotteryactivity #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'name',
	),
)); ?>
