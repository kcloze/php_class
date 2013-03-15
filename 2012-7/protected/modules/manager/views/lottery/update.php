<?php
$this->breadcrumbs=array(
	'Lotteries'=>array('index'),
	$model->name=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List lottery', 'url'=>array('index')),
	array('label'=>'Create lottery', 'url'=>array('create')),
	array('label'=>'View lottery', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage lottery', 'url'=>array('admin')),
);
?>

<h1>Update lottery <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>