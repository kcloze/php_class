<?php
$this->breadcrumbs=array(
    $model->lottery->lotteryActivity->name => array('lottery/admin', 'Lottery[lotteryactivityId]'=>$model->lottery->lotteryactivityId),
    $model->lottery->name => array('lotterysms/admin', 'LotterySms[lotteryId]'=>$model->lotteryId),
    '编辑奖品SMS：'.$model->id,
);
?>

<h1>编辑奖品SMS： <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>