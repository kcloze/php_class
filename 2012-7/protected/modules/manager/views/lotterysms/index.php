<?php
$this->breadcrumbs=array(
	'Lotterysms',
);

$this->menu=array(
	array('label'=>'Create lotterysms', 'url'=>array('create')),
	array('label'=>'Manage lotterysms', 'url'=>array('admin')),
);
?>

<h1>Lotterysms</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
