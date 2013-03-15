<?php
$this->breadcrumbs=array(
    '活动管理'=>array('activity/admin'),
    '移动MM'=>array('activityresource/admin', 'ActivityResource[activityId]'=>10),
    '用户列表'=>array('mmuser/admin'),
    '作品管理',
);

$this->menu=array(
    array('label'=>'List ActivityResource', 'url'=>array('index')),
    array('label'=>'Create ActivityResource', 'url'=>array('create')),
);

$fieltype = array('1'=>'游戏类','2'=>'软件类','3'=>'音乐类');
?>
<div class="grid-view">
<table cellspacing="0" cellpadding="0" style="border:1px solid #D0E3EF;border-collapse:separate;">
<tr><th style="background-color: #E5F1F4">用户信息</th><th width="1" style="background-color:#d0e3ef;padding: 0;"></th><th style="background-color: #E5F1F4">作品列表</th></tr>
    <tr class="odd">
        <td width="23%" valign="top" style="vertical-align:top;">
            <ul style="list-style-position : outside;list-style-type : none;padding-left: 5px;line-height: 25px;">
                <li><?php echo '真实姓名：'.$model[0]['realname'];?></li>
                <li><?php echo '证件类型：'.$model[0]['IDtype'];?></li>
                <li><?php echo '证件号码：'.$model[0]['IDnumber'];?></li>
                <li><?php echo '联系方式：'.$model[0]['mobile'];?></li>
                <li><?php echo '所在学校：'.$model[0]['school'];?></li>
                <li><?php echo '官网昵称：'.UserHelper::getUsername($model[0]['user_id']);?></li>
            </ul>
         
        </td><td style="background-color:#d0e3ef;padding: 0;"></td>
        <td style="padding: 0;">
            <?php
            if($workinfo[0]['filename']!=''){
            foreach($workinfo as $data){
            echo CHtml::beginForm('update?id='.$data['id'],'post',array('id'=>'checkfilestatus'));
            //echo CHtml::hiddenField('Mmworks[id]',$data['id']);
             $covertype = substr($data['filecover'],-3);
             //'/act/files/activity/10/mm/.tmb/thumb_10_cover_resize_300_400.gif';
             $coverdir = '/act/files/activity/10/mm/.tmb/thumb_'.$data['id'].'_cover_resize_135_180.'.$covertype;
            ?>
            
            <table border='1' style="border:1px solid #D0E3EF;border-collapse:separate;">            
                <tr>
                <td rowspan="7" width="135"><a href="/act/files/activity/10/mm/<?php echo $data['id'];?>_work.rar"><img src="<?php echo $coverdir;?>" alt="点击下载" title="点击下载" /></a></td></tr>
                <tr>
                <td><?php echo '作品类型：'.$fieltype[$data['activitytype_id']];?></td>
                <td><?php echo '作品名称：'.$data['filename'];?></td>
                </tr>
                <tr>
                <td>审核状态：
                <?php echo CHtml::dropDownList('Mmworks[filestatus]', $data['filestatus'], array('审核失败'=>'审核失败','审核中'=>'审核当中','审核成功'=>'审核成功'),array('onchange'=>'this.form.submit()'));?>
                </td>
                <td><?php echo '提交时间：'.date('Y-m-d H:i:s',$data['applytime']);?></td>
                </tr>
                <tr>
                <td><?php echo '下载次数：'.$data['download'];?></td>
                <td><?php echo '点击次数：'.$data['point'];?></td>
                </tr>
                <tr>
                <td><?php echo '总得分数：'.$data['score'];?></td>
                <td><?php echo '20强排名：'.$data['firstresult'];?></td>
                </tr> 
                <tr>
                <td><?php echo '10强排名：'.$data['secongdresult'];?></td>
                <td><?php echo '&nbsp;&nbsp;3甲排名：'.$data['thirdresult'];?></td>
                </tr>
                <tr>
                <td colspan="2"><?php echo '作品介绍：'.$data['filecontent'];?></td>
                </tr>              
            </table>
            <?php
            echo CHtml::endForm();
            }//end foreach
            }else{
                echo '<h4>该用户没有上传作品!</h4>';
            }
            ?>
        </td>
    </tr>
</table>
</div>
