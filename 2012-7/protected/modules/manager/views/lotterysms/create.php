<?php
$this->breadcrumbs=array(
    $model->lottery->lotteryActivity->name => array('lottery/admin', 'Lottery[lotteryactivityId]'=>$model->lottery->lotteryactivityId),
    $model->lottery->name => array('lotterysms/admin', 'LotterySms[lotteryId]'=>$model->lotteryId),
	'创建奖品SMS',
);

$this->menu=array(
	array('label'=>'List lotterysms', 'url'=>array('index')),
	array('label'=>'Manage lotterysms', 'url'=>array('admin')),
);
?>

<h1>新建奖品短信</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>