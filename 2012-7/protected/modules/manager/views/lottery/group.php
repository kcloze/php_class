<?php
$this->breadcrumbs=array(
	'Lotteries'=>array('index'),
	'Manage',
);

$this->menu=array(
	array('label'=>'List lottery', 'url'=>array('index')),
	array('label'=>'Create lottery', 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('lottery-grid', {
		data: $(this).serialize()
	});
	return false;
});
");
?>
<?php echo CHtml::link('创建一个抽奖活动', array('create')); ?>

<ul>
<?php foreach($model as $item):?>
<li>
<?php echo CHtml::link($item->groupName, array('admin', 'Lottery[groupName]'=>$item->groupName)); ?>
</li>
<?php endforeach ?>
</ul>