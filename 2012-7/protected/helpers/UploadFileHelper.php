<?php

class UploadFileHelper{

	//检查允许上传的文件类型 $allow_file_types格式：|jpg|gif|jpeg|	
	static public function CheckFileType($filename,$allow_file_types='|.JPG|'){
		
		if(empty($filename)){
			return false;
		}
		$extname = self::GetFileType($filename);
	    if ($allow_file_types && stristr($allow_file_types, '|' . strtoupper($extname) . '|') === false){
	    	return false;
    	}else{
    		return $extname;
    	}
	}
	
	//检查是否超过大小
	static public function CheckFileSize($filename){
		if($filename['error'] == 1 || $filename['error'] == 2){
			return false;
		}
		return true;
	}
	//保存图片至指定目录
	static public function SaveFile($file,$dir,$newfilename='',$sign=''){

		$extname = self::GetFileType($file['name']);
		if(empty($newfilename)){
			$newfilename = self::RandomFileName();
		}
	 	if (!move_uploaded_file($file['tmp_name'], $dir.$newfilename.$sign.$extname)){
            echo 'false';
	 		return false;
        }
        return $newfilename.$sign.$extname;
	}
	//文件后缀名
    function GetFileType($path){
        $pos = strrpos($path, '.');
        if ($pos !== false){
            return strtolower(substr($path, $pos));
		}else{
            return '';
        }
    }	

	//生成指定目录不重名的文件名
    function RandomFileName(){
        $str = '';
        for($i = 0; $i < 9; $i++){
            $str .= mt_rand(0, 9);
        }
        return time() . $str;
    }    
	
	
	//入库操作
	static public function Insert($data){
		$sql = "INSERT INTO attchment (filedesc, act_type,cover,filegroup,date,source,pai,changetime) VALUES (:filedesc,:act_type,:cover,:filegroup,:date,:source,:pai,:changetime)";
		$connection = Yii::app()->db;
		$command = $connection->createCommand($sql);
		$changetime = date('Y-m-d H:i:s');
        $command->bindParam(":filedesc",$data['filedesc'],PDO::PARAM_STR);
		$command->bindParam(":act_type",$data['act_type'],PDO::PARAM_STR);
        $command->bindParam(":cover", $data['cover'],PDO::PARAM_STR);
        $command->bindParam(":filegroup",$data['filegroup'],PDO::PARAM_STR);
		$command->bindParam(":date",$data['date'],PDO::PARAM_STR);
        $command->bindParam(":pai", $data['pai'],PDO::PARAM_STR);
		$command->bindParam(":source", $data['source'],PDO::PARAM_STR);
        $command->bindParam(":changetime",$changetime,PDO::PARAM_STR);
        $command->query();
	}

	//UPDATE操作
	static public function Update($data){
		$sql = "UPDATE attchment set filedesc=:filedesc, act_type=:act_type,cover=:cover,filegroup=:filegroup,date=:date,source=:source,pai=:pai,changetime=:changetime where id=:id";
		$connection = Yii::app()->db;
		$command = $connection->createCommand($sql);
		$changetime = date('Y-m-d H:i:s');
        $command->bindParam(":id",$data['id'],PDO::PARAM_STR);
		$command->bindParam(":filedesc",$data['filedesc'],PDO::PARAM_STR);
		$command->bindParam(":act_type",$data['act_type'],PDO::PARAM_STR);
        $command->bindParam(":cover", $data['cover'],PDO::PARAM_STR);
        $command->bindParam(":filegroup",$data['filegroup'],PDO::PARAM_STR);
		$command->bindParam(":date",$data['date'],PDO::PARAM_STR);
        $command->bindParam(":source", $data['source'],PDO::PARAM_STR);
        $command->bindParam(":pai", $data['pai'],PDO::PARAM_STR);
        $command->bindParam(":changetime",$changetime,PDO::PARAM_STR);
        $command->query();
	}
	
	
	static public function GetAttList($data,$order = 'id' ,$pageSize= '20'){
		$cnt = self::GetAttCount($data);
		$orderby = ' order by '.$order . ' desc';
		$pages = new CPagination ( $cnt );
		$pages->pageSize = $pageSize;
		$pages->pageVar="p";
		$pages->setCurrentPage(intval($_GET['p']));
		$connection = Yii::app()->db;
		$sql = 'select * from attchment where act_type=:act_type'. $orderby.' limit '.$pages->getOffset().','. $pages->getLimit();

        $command = $connection->createCommand($sql);
        $command->bindParam(":act_type",$data['act_type'],PDO::PARAM_STR);
        
        $rows=$command->queryAll(); 
        if($rows == null){
            return array();
        }
        return $rows;
	}
	
	static public function GetAttCount($data){
		$connection = Yii::app()->db;
		$sql = 'select count(*) cnt from attchment where act_type=:act_type';
		
        $command = $connection->createCommand($sql);
        $command->bindParam(":act_type",$data['act_type'],PDO::PARAM_STR);

        $cnt_tmp = $command->query();
	
		if( !$one = $cnt_tmp->read() ){
			throw new CHttpException(400, Yii::t('AdminModule.Appbox', 'data error') );
		}
		return $one['cnt'];
		
	}
	
	static public function GetAttRow($id){
		$connection = Yii::app()->db;
		$sql = 'select * from attchment where id=:id';

        $command = $connection->createCommand($sql);
        $command->bindParam(":id",$id,PDO::PARAM_STR);
        $rows=$command->queryRow();
          
        if($rows == null){
            return array();
        }
    	return $rows;
	}	
	
		
  
}