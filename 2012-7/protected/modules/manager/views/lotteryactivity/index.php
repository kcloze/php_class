<?php
$this->breadcrumbs=array(
	'Lotteryactivitys',
);

$this->menu=array(
	array('label'=>'Create lotteryactivity', 'url'=>array('create')),
	array('label'=>'Manage lotteryactivity', 'url'=>array('admin')),
);
?>

<h1>Lotteryactivitys</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
