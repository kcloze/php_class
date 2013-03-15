<?php
$this->breadcrumbs=array(
    '活动管理'=>array('activity/admin'),
    '移动MM'=>array('activityresource/admin', 'ActivityResource[activityId]'=>10),
    '用户管理',
);

$this->menu=array(
    array('label'=>'List ActivityResource', 'url'=>array('index')),
    array('label'=>'Create ActivityResource', 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
    $('.search-form').toggle();
    return false;
});
$('.search-form form').submit(function(){
    $.fn.yiiGridView.update('activity-resource-grid', {
        data: $(this).serialize()
    });
    return false;
});
");
?>

<h1>活动“移动MM”的内容项目</h1>

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
<?
    //$userinfo = UserHelper::getProfile($data->user_id);
?>
<?php $this->widget('zii.widgets.grid.CGridView', array(
    'dataProvider'=>$model->search(),
    'filter'=>$model,
    'columns'=>array(
        array('name'=>'id','htmlOptions'=>array('style'=>'width:50px;text-align: center;')),
        array(
            'name'=>'realname', 
            'type'=>'html',
            'value'=>'CHtml::link($data->realname,array("view","userid"=>$data->user_id))', 
            'htmlOptions'=>array('style'=>'width:150px;text-align: center;')
        ),
        array(
            'name'=>'IDtype',        
            'filter'=>array('身份证'=>'身份证','护照'=>'护照'),
            'htmlOptions'=>array('style'=>'width:70px;text-align: center;')
        ),
        array(
            'name'=>'IDnumber',        
            'filter'=>false,
            'htmlOptions'=>array('style'=>'width:150px;text-align: center;')
        ),
        'school',
        array(
            'name'=>'mobile',        
            'filter'=>false,
            'htmlOptions'=>array('style'=>'width:150px;text-align: center;')
        ),
		array(
            'name'=>'regdate',
            'value'=>'date("Y-m-d H:i:s",$data->regdate)',
            'htmlOptions'=>array('style'=>'width:100px;text-align: center;'),
        ),
         array(
            'name'=>'user_id',
            'value'=>'UserHelper::getUsername($data->user_id)',
            'htmlOptions'=>array('style'=>'width:100px;text-align: center;'),
        ),
        array(
            'class'=>'CButtonColumn',
            'template'=>'{view} {update}',
        ),
    ),
)); ?>

