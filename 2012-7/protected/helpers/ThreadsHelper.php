<?php
/**
 * 论坛帖子模块
 */
class ThreadsHelper{
	/**
	 * 主帖
	 * $condition = 'award=:award';
	 * $params = array(':award'=>'jay,money,100');
	 */
	
	public static function getList($condition,$params,$page=10){
		$c = new CDbCriteria();
        $c->condition = $condition;
        $c->params = $params;
        $c->order = 'postdate DESC';
        $smart = SmartModel::model('pw_threads');
        $p = new CPagination($smart->count($c));
        $p->setPageSize($page);
        $p->pageVar="p1";
        $p->applyLimit($c);
        $res = $smart->findAll($c);	
		return array('p'=>$p,'res'=>$res);
	}
	
    public static function getTmsgsList($condition, $limit = '0, 8',$order = 'postdate desc'){
        $sql = 'select * from pw_threads as th,pw_tmsgs as tg where th.tid=tg.tid '.$condition.' order by '.$order.' limit '.$limit;

        $command = Yii::app()->pwind->createCommand($sql);
        
        $rows=$command->queryALL(); 
        if($rows == null){
        	return array();
        }
        foreach ($rows as $key=>$value){
        	$attachment = self::getAttach($value['tid']);
        	$rows[$key]['thumburl'] = $attachment[0];
        	$rows[$key]['thumburl'] = $attachment[0];
        }
        return $rows;
    }
    
    
    public static function getTmsgsCount($condition){
        $sql = 'select count(*) from pw_threads as th,pw_tmsgs as tg where th.tid=tg.tid '.$condition;

        $command = Yii::app()->pwind->createCommand($sql);
        
        $rows=$command->queryScalar();
        if($rows){
            return $rows;
        }
        return 0;
    }     

    

	/**
	 * 回帖
	 */
	public static function getPostlist($condition,$params,$page=10){
		$c = new CDbCriteria();
        $c->condition = $condition;
        $c->params = $params;
        $c->order = 'postdate DESC';
        $smart = SmartModel::model('pw_posts');
        $count = $smart->count($c);
        $p = new CPagination($count);
        $p->setPageSize($page);
        $p->pageVar="p1";
        $p->applyLimit($c);
        $res = $smart->findAll($c);	
		return array('p'=>$p,'res'=>$res,'count'=>$count);
	}
	
	/**
	 * 获取主题帖子附件
	**/	
	public static function getAttach($tid){
		$attachment = array();
		$c = new CDbCriteria();
        $c->condition = 'tid=:tid and pid=:pid';
        $c->params = array(':tid'=>$tid,':pid'=>'0');
        $smart = SmartModel::model('pw_attachs');
        $res = $smart->findAll($c);
		foreach ($res as $at) {

			$attachment[] = Yii::app()->params['basehost'] . '/bbs/attachment/' . $at->attachurl;
		}

		return $attachment;
	}
}