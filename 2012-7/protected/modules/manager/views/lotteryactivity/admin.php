<?php
$this->breadcrumbs=array(
	'抽奖活动'=>array('admin'),
	'管理',
);

$this->menu=array(
	array('label'=>'List lotteryactivity', 'url'=>array('index')),
	array('label'=>'Create lotteryactivity', 'url'=>array('create')),
);
?>

<h1>抽奖活动列表</h1>

<?php echo CHtml::link('新建抽奖活动', array('create')); ?>

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'lotteryactivity-grid',
	'dataProvider'=>$model->search(),
	//'filter'=>$model,
	'columns'=>array(
        array(
            'class'=>'CButtonColumn',
            'template'=>'{update}',
            'htmlOptions'=>array('style'=>'width:40px;text-align: center;'),
        ),
        array('header'=>'编号',
            'value'=>'$data->id',
            'htmlOptions'=>array('style'=>'width:30px;text-align: center;')),
        array('header'=>'抽奖活动名称',
            'value'=>'CHtml::link($data->name,array("lottery/admin", "Lottery[lotteryactivityId]"=>$data->id))',
            'type'=>'html',
            'htmlOptions'=>array('style'=>'width:200px;text-align: left;')),
        array('header'=>'acttype英文标记',
            'value'=>'$data->acttype',
            'type'=>'html',
            'htmlOptions'=>array('style'=>'width:200px;text-align: left;')),        
        array('header'=>'奖项',
            'value'=>'$data->getLotteryCount()',
            'htmlOptions'=>array('style'=>'width:40px;text-align: center;')),
        array('header'=>'上限/人',
            'value'=>'$data->chargeLimit',
            'htmlOptions'=>array('style'=>'width:30px;text-align: center;')),        
        array('header'=>'卡商概率',
            'value'=>'$data->cardPercent. "%"',
            'htmlOptions'=>array('style'=>'width:30px;text-align: center;')),        
        array('header'=>'说明',
            'value'=>'$data->description',
            'htmlOptions'=>array('style'=>'width:auto;text-align: left;')),
        array('header'=>'开始时间',
            'value'=>'$data->beginTime',
            'htmlOptions'=>array('style'=>'width:140px;text-align: center;')),
        array('header'=>'结束时间',
            'value'=>'$data->endTime',
            'htmlOptions'=>array('style'=>'width:140px;text-align: center;')),
	),
)); ?>
