<?php
$this->breadcrumbs=array(
	'抽奖活动'=>array('index'),
	'创建',
);

?>

<h1>创建抽奖活动</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>