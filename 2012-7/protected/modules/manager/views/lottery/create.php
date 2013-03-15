<?php
$this->breadcrumbs=array(
	'Lotteries'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List lottery', 'url'=>array('index')),
	array('label'=>'Manage lottery', 'url'=>array('admin')),
);
?>

<h1>Create lottery</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>