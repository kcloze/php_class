<?php
/**
* 优惠信息自动采集，发帖，入库
* daxiu 2011-12-27
*/
class PreferentialCommand extends CConsoleCommand { 
    public $area_port;
    public $city;
    public $cateid;
    public $adminuid;
    
    public function run($args){
        $this->area_port = array(
        "GZ"=>"20","SZ"=>"755","DG"=>"769","FS"=>"757","ST"=>"754",
        "ZH"=>"756","HZ"=>"752","ZS"=>"760","JM"=>"750","SG"=>"751",
        "HY"=>"762","MZ"=>"753","SW"=>"660","YJ"=>"662","ZJ"=>"759",
        "MM"=>"668","ZQ"=>"758","QY"=>"763","CZ"=>"768","JY"=>"663","YF"=>"766"
        );
        $this->city = array(
			'111'=>'全省',
			'20'=>'广州',
			'755'=>'深圳',
			'756'=>'珠海',
			'754'=>'汕头',
			'751'=>'韶关',
			'762'=>'河源',
			'753'=>'梅州',
			'752'=>'惠州',
			'660'=>'汕尾',
			'769'=>'东莞',
			'760'=>'中山',
			'750'=>'江门',
			'757'=>'佛山',
			'662'=>'阳江',
			'759'=>'湛江',
			'668'=>'茂名',
			'758'=>'肇庆',
			'763'=>'清远',
			'768'=>'潮州',
			'663'=>'揭阳',
			'766'=>'云浮',
		); 
        $this->cateid = 81;
        //发布优惠信息的管理员，名叫 ·猪头方片三· 2154840
        $this->adminuid = 2154840;
        
        $baseurl = 'http://gd.10086.cn';
        $needurl = 'http://gd.10086.cn/whatsnew/discount/discounlist.jsp';
        $command=Yii::app()->db->createCommand();
        //$maxCollectId=$command->select('max(collectInfoId) as num')->from('news_articles')->where('CateId=:cateid',array(':cateid'=>$this->cateid))->queryScalar();
        //!$maxCollectId && $maxCollectId = 0;
        foreach($this->area_port as $key=>$port){
            $brandCode = 0;
            $typeSid = 1;
            $data = array();
            while(1){
                $url = $needurl.'?branchCode='.$key.'&brandCode='.$brandCode.'&typeSid='.$typeSid;
                $content = @file_get_contents($url);
                $content = iconv("gbk","utf-8",$content);
                $pre_str = "/<span class=\"tit\"><a href=\"(.+?)\" +[^>]+>(.+?)<\/a><\/span>(.+?)<b>优惠时间:<\/b>(.+?)至(.+?)<b>优惠地区:<\/b>/is";
                preg_match_all ($pre_str, $content, $outs, PREG_SET_ORDER);
                foreach($outs as $out){
                    $tid = substr($out[1],strpos($out[1],'=')+1);
                    if(!$tid) continue;
                    $command->reset();
                    $haveId=$command->select('id')->from('news_articles')->where('CateId=:cateid and collectInfoId=:collectInfoId',array(':cateid'=>$this->cateid,':collectInfoId'=>$tid))->queryScalar();
                    if($haveId) continue; //如果当前信息Id已存在已采集的信息Id，不入库 继续
                    $newurl = $baseurl.$out[1]; 
                    $title = $out[2];
                    $begintime = trim($out[4]);
                    $endtime = str_replace(array('　','&#12288;','<br>','\n','<br />','<br/>'),array('','','','','',''),trim($out[5]));
                    if($begintime < '2012-03-01') continue;
                    if($endtime && $endtime < date('Y-m-d')) continue;
                    $content_2 = @file_get_contents($newurl);
                    if ($content_2 === false)  continue;

                    $pre_str_2 = "/<b class=\"blue_02\">(.+?)<div class=\"column\">/is";
                    preg_match_all ($pre_str_2, $content_2, $outs_2, PREG_SET_ORDER);
                    $out_tmp = '<p>'.substr($outs_2[0][0],0,-130);
                    $out_tmp = iconv("gbk","utf-8",$out_tmp);
                    $out_tmp = str_replace("<br />","<br/>",$out_tmp);
                    
                    //自动发帖
                    /**/
                    $bbs_tid = UserHelper::mzone_auto_bbs($this->adminuid, 9,1,addslashes($title),addslashes($out_tmp),0);
                    if(!$bbs_tid){
                        //自动发帖失败
                        echo   $newurl." ruku false<br />";
                        continue;                      
                    }

                    //自动采编入库
                    $columns = array(
                        'Title' => $title,
                        'Description' => $newurl,
                        'Content' => $out_tmp,
                        'Status' => 1, //默认采集入库时是审核的
                        'Url' => '/read.php?tid='.$bbs_tid,
                        'WapUrl' => '/bbs/read.php?tid='.$bbs_tid,
                        'CreatedDate' => date('Y-m-d H:i:s'),
                        'UserId' => $this->adminuid,
                        'CateId' => $this->cateid,
                        'City' => $port,
                        'ActiveStartDate' => $begintime,
                        'ActiveEndDate' => $endtime,
                        'collectInfoId' => $tid,
                    );
                   
                   /*$sql="INSERT INTO `news_articles` (`Title`, `Description`, `Content`, `Status`, `Url`, `WapUrl`, `CreatedDate`, `UserId`, `CateId`, `City`, `ActiveStartDate`, `ActiveEndDate`, `collectInfoId`) VALUES ('".$title."', '".$newurl."', 'testcontent', 1, '/read.php?tid=".$bbs_tid."', '/bbs/read.php?tid=".$bbs_tid."', '".date('Y-m-d H:i:s')."', ".$this->adminuid.", ".$this->cateid.", ".$port.", '".$begintime."', '".$endtime."', ".$tid.")";

                    echo  $sql;
                    unset($command);
                    $command=Yii::app()->db->createCommand($sql);
                    $command->query();
                    */
                    $command->reset();
                    $command->insert('news_articles',$columns);
                                        
                    echo   $newurl." ruku success<br />";
                }//end foreach
 
                $typeSid = $typeSid+1;
                if($typeSid == 5 and $brandCode == 0){
                    //取完全省的，取动感地带的
                    $brandCode = 3;
                    $typeSid = 1;
                }
                if($typeSid == 5 and $brandCode == 3){
                    break;// out of while
                }
            }//end while
        }//end foreach
    }
}