<?php
$this->breadcrumbs=array(
	'Lotterysms'=>array('index'),
	'Manage',
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('lotterysms-grid', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>管理奖品SMS</h1>

<?php echo CHtml::link('创建奖品SMS', array('create', 'LotterySms[lotteryId]'=>$_GET['LotterySms']['lotteryId'])); ?>

<?php echo CHtml::link('查找','#',array('class'=>'search-button')); ?>
<div class="search-form" style="display:none">
<?php $this->renderPartial('_search',array(
	'model'=>$model,
)); ?>
</div><!-- search-form -->

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'lotterysms-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
        array(
            'class'=>'CButtonColumn',
            'template'=>'{update}',
            'htmlOptions'=>array('style'=>'width:30px;text-align: center;'),
        ),
        array('header'=>'编号',
            'value'=>'$data->id',
            'name'=>'id',
            'htmlOptions'=>array('style'=>'width:30px;text-align: center;')),
        array('header'=>'奖项名称',
            'value'=>'$data->lottery->name',
            'htmlOptions'=>array('style'=>'width:200px;text-align: left;')),
        array('header'=>'允许使用日期',
            'value'=>'$data->date',
            'name'=>'date',
            'htmlOptions'=>array('style'=>'width:140px;text-align: center;')),
        array('header'=>'SMS内容',
            'value'=>'$data->smsContent',
            'name'=>'smsContent',
            'htmlOptions'=>array('style'=>'width:auto;text-align: center;')),
        array('header'=>'接收人',
            'value'=>'$data->toPhone',
            'name'=>'toPhone',
            'htmlOptions'=>array('style'=>'width:140px;text-align: center;')),
        array('header'=>'昵称',
            'value'=>'$data->username',
            'name'=>'username',
            'htmlOptions'=>array('style'=>'width:140px;text-align: center;')),        
        array('header'=>'发送时间',
            'value'=>'$data->sendDatetime',
            'name'=>'sendDatetime',
            'htmlOptions'=>array('style'=>'width:140px;text-align: center;')),
        array('header'=>'中奖时间',
            'value'=>'$data->editTime?date("Y-m-d H:i:s", $data->editTime):""',
            'name'=>'editTime',
            'htmlOptions'=>array('style'=>'width:140px;text-align: center;')),
        array('header'=>'创建时间',
            'value'=>'date("Y-m-d H:i:s", $data->createTime)',
            'name'=>'createTime',
            'htmlOptions'=>array('style'=>'width:140px;text-align: center;')),
	),
)); ?>
