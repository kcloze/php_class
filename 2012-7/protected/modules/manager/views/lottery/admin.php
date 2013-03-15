<?php
$this->breadcrumbs=array(
	'活动奖项'=>array('admin'),
	'管理',
);

?>

<h1>活动奖项</h1>

<?php echo CHtml::link('新建一个奖项', array('create', 'Lottery[lotteryactivityId]'=>$_GET['Lottery']['lotteryactivityId'])); ?>

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'lottery-grid',
	'dataProvider'=>$model->search(),
	//'filter'=>$model,
	'columns'=>array(
        array(
            'class'=>'CButtonColumn',
            'template'=>'{update}',
            'htmlOptions'=>array('style'=>'width:40px;text-align: center;'),
        ),
        array('header'=>'导出数据',
            'value'=>'CHtml::link("导出",array("lotterysms/export", "LotterySms[lotteryId]"=>$data->id,"LotterySms[name]"=>$data->name),array(\'target\'=>\'_blank\'))',
            'type'=>'html',
            'htmlOptions'=>array('style'=>'width:auto;text-align: left;')),
        array('header'=>'奖项名称',
            'value'=>'CHtml::link($data->name,array("lotterysms/admin", "LotterySms[lotteryId]"=>$data->id))',
            'type'=>'html',
            'htmlOptions'=>array('style'=>'width:auto;text-align: left;')),
        array('header'=>'奖品SMS',
            'value'=>'$data->getSmsCount() . "/" . $data->getSendSmsCount()',
            'type'=>'html',
            'htmlOptions'=>array('style'=>'width:80px;text-align: center;')),
        array('header'=>'中奖率',
            'value'=>'$data->percent . "%"',
            'htmlOptions'=>array('style'=>'width:100px;text-align: center;')),
        array('header'=>'限制中奖',
            'value'=>'$data->limit',
            'htmlOptions'=>array('style'=>'width:80px;text-align: center;')),
        array('header'=>'允许地市',
            'value'=>'$data->city',
            'htmlOptions'=>array('style'=>'width:150px;text-align: left;')),
        array('header'=>'允许品牌',
            'value'=>'$data->brand',
            'htmlOptions'=>array('style'=>'width:150px;text-align: left;')),
        array('header'=>'等价话费金额',
            'value'=>'$data->phoneCharge',
            'htmlOptions'=>array('style'=>'width:60px;text-align: center;')),        
        array('header'=>'中奖IP限制',
            'value'=>'$data->ipTimesLimit',
            'htmlOptions'=>array('style'=>'width:60px;text-align: center;')),        
        array('header'=>'抽奖活动',
            'value'=>'CHtml::link($data->lotteryActivity->name,array("lottery/admin", "Lottery[lotteryactivityId]"=>$data->lotteryactivityId))',
            'type'=>'html',
            'htmlOptions'=>array('style'=>'width:200px;text-align: left;')),
	),
)); ?>
