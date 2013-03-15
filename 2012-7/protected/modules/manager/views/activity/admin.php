<?php
$this->breadcrumbs=array(
	'活动管理'=>array('admin'),
);

$this->menu=array(
	array('label'=>'List Activity', 'url'=>array('index')),
	array('label'=>'Create Activity', 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('activity-grid', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>活动列表</h1>

<p>
你可以输入 (<b>&lt;</b>, <b>&lt;=</b>, <b>&gt;</b>, <b>&gt;=</b>, <b>&lt;&gt;</b>
or <b>=</b>) 这些等式来查询数据.
</p>

<?php echo CHtml::link('高级查询','#',array('class'=>'search-button')); ?>
<div class="search-form" style="display:none">
<?php $this->renderPartial('_search',array(
	'model'=>$model,
)); ?>
</div><!-- search-form -->

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'activity-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		array('name'=>'id', 'headerHtmlOptions'=>array('width'=>50)),
		array('name'=>'name',
			'type'=>'html',
			'value'=>'CHtml::link($data->name,array("activityresource/admin","ActivityResource[activityId]"=>$data->id))', 
			'headerHtmlOptions'=>array('width'=>200)),
		array('name'=>'activityType', 'headerHtmlOptions'=>array('width'=>100)),
		array('name'=>'key',
			'value'=>'CHtml::link("'.Yii::app()->params['siteUrl'].'{$data->key}","'.Yii::app()->params['siteUrl'].'{$data->key}",array("target"=>"_blank"))',
			'type'=>'raw'),
		array('name'=>'beginTime', 'headerHtmlOptions'=>array('width'=>120)),
		array('name'=>'endTime', 'headerHtmlOptions'=>array('width'=>120)),
		array('name'=>'city', 'headerHtmlOptions'=>array('width'=>80)),
		array('name'=>'joinCount', 'headerHtmlOptions'=>array('width'=>80)),
		array('name'=>'status', 'headerHtmlOptions'=>array('width'=>50)),
		array(
			'class'=>'CButtonColumn',
			'buttons'=>array(
				'button_1'=>array(
					'label'=>'page',
					'imageUrl'=>Yii::app()->request->baseUrl.'/images/folder_page.png',
					'url'=>'Yii::app()->controller->createUrl("activityresource/admin",array("ActivityResource[activityId]"=>$data->primaryKey))',
				),
			),
			'template'=>'{view} {update} {button_1}',
			'headerHtmlOptions'=>array('width'=>100),
		),
	),
)); ?>
