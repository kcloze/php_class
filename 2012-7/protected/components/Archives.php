<?php
/** * 采编项目记录
 *  * @author mc009 */

class Archives {
	
	/**
	 * 单例模式
	 * @mixed Arvhives $_inst
	 */
	private static $_inst = null;
	
    /**
     * 栏目id
     * @var int
     */
    private $arctype_id;
    private $limit = 10;
    
    /**
     * 分页类
     * @mixed CPagination
     */
 	private $page;
	
	private $params = array();
    

    public function __construct(  ){

    	$this->db = Yii::app()->pwind;
    }
    
    /**
     * 
     * 初始化查询
     * @param $arctype_id
     * @param $param
     * @return Archives
     */
    public function init( $arctype_id, $param = array() ){
    	$this->params = $param;
    	$this->arctype_id = $arctype_id;
    	return $this;
    }
    
    /**
     * 
     * 设置当前查询需要的变量
     * @param $key
     * @param $value
     */
    public function setParam( $params ){
		$this->params = $params;
    }

    public function getList( $limit, $orderby = ' pubdate desc ' ){
    	    	
    	if( ! $tables = Arctype::getTable( $this->arctype_id ) ){
            return false;
        }

        $a = array(); 
        foreach( $this->params as $key=>$one ){
        	$a[] = " and $one[0] ";
        }
        /*
        $sql = "select count( m.id ) as count from {$tables['maintable']} m left join {$tables['addtable']} a on m.id = a.aid 
        	left join `mzone_arctype` as arc on m.typeid=arc.id
            where m.typeid = :arctype_id AND m.arcrank > -1 ". implode( ',', $a ). " limit :limit " ;
        
        */
        
        $sql = "select m.*,a.*,tp.typedir,tp.typename,tp.corank,tp.isdefault,tp.defaultname,tp.namerule,tp.namerule2,tp.ispart,
			tp.moresite,tp.siteurl,tp.sitepath,tp.typedir from {$tables['maintable']} m left join {$tables['addtable']} a on m.id = a.aid
        		left join `mzone_arctype` as tp on m.typeid=tp.id
            where m.typeid = :arctype_id AND m.arcrank > -1 ". implode( ' ', $a ). " order by ". $orderby. " limit :limit";

		$command = $this->db->createCommand( $sql );
        $command->bindParam( ':arctype_id', $this->arctype_id, PDO::PARAM_STR );
        $command->bindParam( ':limit', $limit, PDO::PARAM_INT );
    	foreach( $this->params as $key=>$one ){
    		if( isset( $one[1] ) )
        		$command->bindParam( ":$key", $one[1], PDO::PARAM_STR );
        }
        
        $rs = array();
        foreach( $command->queryAll() as $row ) {
        	$row['shorttitle'] = empty($row['shorttitle']) ? $row['title'] :$row['shorttitle'];
			$row['dir'] = 'promotion';
        	
			if($row['sourceid']){
				$row['filename'] = $row['url'] = Yii::app()->params['bbshost'] . 'read.php?tid='.$row['sourceid'];
			}elseif($row['wapurl']){
				$row['filename'] = $row['url'] = $row['wapurl'];
			}
			
			if(isset($row['nativeplace'])){
				$row['cityname'] = CityHelper::getCityName($row['nativeplace']);
			}
            $rs[] = $row;
        }
        
        return $rs;
    }
    
    public function getListHtml( $limit, $orderby = 'aid desc' ){
    	
    	$rs = $this->getList( $limit, $orderby );
    	
    	$str_rs = "<ul>%li%</ul>";
        $arr_li = array();
        foreach( $rs as $one ){
        	$arr_li[] = "<li>【{$one[source]}】<a href='{$one[url]}' target='_blank'>{$one[title]}</a></li>"; 
        }
        $str_rs = str_replace("%li%", implode( "\r\n", $arr_li ), $str_rs  );
        return $str_rs;
    }
    
	/**
	 * @return the $page
	 */
	public function getPage() {
		return $this->page;
	}

	/**
	 * @param field_type $page
	 */
	public function setPage($page) {
		$this->page = $page;
	}
	
	
	/**
	 * 单例模式
	 * @return Archives $_inst 
	 */
	public static function inst(){
		if( null != self::$_inst ){
			return $_inst;
		}
		
		$inst = new Archives();
		return self::$_inst = $inst; 
	}
	
}







