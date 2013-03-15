<?php
$this->breadcrumbs=array(
	'Activity Resources',
);

$this->menu=array(
	array('label'=>'Create ActivityResource', 'url'=>array('create')),
	array('label'=>'Manage ActivityResource', 'url'=>array('admin')),
);
?>

<h1>Activity Resources</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
