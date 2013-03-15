<?php

/**
 * 
 * 论坛
 * @author Administrator
 *
 */
class Forums{
	
	public static function getList( $fid, $type=0, $limit=10 ){
		
		$type != 0 && $sql_type = " and t.type=:type ";
		$sql = "select count(t.tid) as cnt from pw_threads t inner join pw_tmsgs m on t.tid = m.tid where t.fid=:fid ". $sql_type;
		$command = Yii::app()->pwind->createCommand($sql);
		$command->bindParam( ':fid', $fid, PDO::PARAM_STR );
        $type != 0 && $command->bindParam( ':type', $type, PDO::PARAM_INT );
        $cnt = $command->query()->readColumn('0');
    	$pages = new CPagination( $cnt );
		$pages->pageSize = $limit;
		$c = new CDbCriteria();
		$pages->applyLimit($c);
		$pages->pageVar = 'p';
		
        $sql = "select t.*, m.content from pw_threads t inner join pw_tmsgs m on t.tid = m.tid where t.fid=:fid and t.type=:type order by tid desc LIMIT :offset,:limit";
		$command = Yii::app()->pwind->createCommand($sql);
		$command->bindParam( ':fid', $fid, PDO::PARAM_STR );
        $type != 0 && $command->bindParam( ':type', $type, PDO::PARAM_INT );
        $command->bindValue(':offset', $pages->currentPage*$pages->pageSize);
		$command->bindValue(':limit', $pages->pageSize);
		$list = $command->queryAll();
		return array(
		        'list'=>$list,
		        'pages'=>$pages,
		);
	}
	
	public static function getForumTypeId( $fid, $type ){
		
		$sm = new SmartModel('pw_forums');
		$c = new CDbCriteria();
		$c->condition = "fid=:fid";
		$c->params = array( ':fid'=>$fid );
		
		$p = new CPagination();
		$p->setPageSize( $page );
		$p->applyLimit($c);
		
		$f = $sm->find( $c );
		$types = explode( "\t", $f->t_type );
		
		return array_search($type, $types);
	}
	
	public static function stripTags( $str ){
		
		$mc = new MzoneCaller();
		$mc->set_url( 'activity_do.php?act=bbs&action=filter' );
		$mc->add_post( array('str' => $str));
		$rs = $mc->do_request();
		
		return $rs;
	}
}









