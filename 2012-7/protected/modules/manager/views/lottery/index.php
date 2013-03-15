<?php
$this->breadcrumbs=array(
	'Lotteries',
);

$this->menu=array(
	array('label'=>'Create lottery', 'url'=>array('create')),
	array('label'=>'Manage lottery', 'url'=>array('admin')),
);
?>

<h1>Lotteries</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
