<?php
$this->breadcrumbs=array(
	'活动管理'=>array('admin'),
	'创建活动',
);

$this->menu=array(
	array('label'=>'List Activity', 'url'=>array('index')),
	array('label'=>'Manage Activity', 'url'=>array('admin')),
);
?>

<h1>创建一个活动</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>