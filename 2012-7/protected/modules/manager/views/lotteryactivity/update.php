<?php
$this->breadcrumbs=array(
	'抽奖活动'=>array('admin'),
	$model->name=>array('update','id'=>$model->id),
	'更新',
);

?>

<h1>更新抽奖活动：<?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>