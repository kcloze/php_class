<?php
/**
 * class dbquery
 *
 * @package    	Web Development Frameworks
 * @author     	Gilang <gilang@kresnadi.web.id>
 * @License    	Free GPL
 * @link 		http://www.kresnadi.web.id
 */

class dbquery {
	
	var $db_connect_id;
	var $query_result;
	var $row 			= array();
	var $rowset 		= array();
	var $num_queries 	= 0;
	
	private $user 		= "";
	private $password 	= "";
	private $server 	= "";
	private $dbname		= "";
	
	/*AUTOLOAD CLASS*/
	
	function __construct(){
	global $conf;
	
		$this->user 		= $conf['db']['user'];
		$this->password 	= $conf['db']['pass'];
		$this->server 		= $conf['db']['host'];
		$this->showerror	= $conf['db']['showerror'];
		$this->dbname 		= $conf['db']['database'];
		return $this->sql_db();
	}	
	
	/*SQL CONNECTION*/
	
	// Fungsi Open Koneksi database MySQL
	function sql_db()
	{
		$this->db_connect_id = mysql_pconnect($this->server, $this->user, $this->password);
		if(!$this->db_connect_id === false) {
			$dbselect = mysql_select_db($this->dbname);
			if(!$dbselect) {
				mysql_close($this->db_connect_id);
				$this->db_connect_id = $dbselect;
			}
			return $this->db_connect_id;
		} else {
			if($this->showerror) {
				print_r($this->sql_error());
			}
			exit();
		}
	}

	// Fungsi Close Koneksi database MySQL
	function sql_close()
	{
		if($this->db_connect_id)
		{
			if($this->query_result)
			{
				mysql_free_result($this->query_result);
			}
			$result = mysql_close($this->db_connect_id);
			return $result;
		}
		else
		{
			return false;
		}
	}

	// Fungsi Query database MySQL
	function cleanValue($_value) {
		$_value = stripslashes(strip_tags($_value));
		$_value = str_replace(array('delete',
									'DELETE',
									'rm -',
									'!',
									'UNION',
									'union',
									'|',
									'?',
									'&',
									'=',
									'-',
									'`',
									"'",
									'"',
									'\\\\',
									'\\',
									'//',
									'/',
									',',
									';',
									':',
									'*',
									'>',
									'<'
								   ), '', $_value);
		return trim($_value);
	}

	function set_query($query)
	{
		// Remove any pre-existing queries
		unset($this->query_result);
		if($query != "") {
			$this->query_result = mysql_query($query, $this->db_connect_id);
		}
		if($this->query_result) {
			unset($this->row[$this->query_result]);
			unset($this->rowset[$this->query_result]);
			return $this->query_result;
		} else {
			$err = $this->sql_error($query);
		}
	}

	// Fungsi Menghitung row Query database MySQL
	function sql_numrows($query)
	{
		$this->set_query($query);
		if($result = mysql_num_rows($this->query_result)) {
			return $result;
		} else {
			$err = $this->sql_error($query);			
		}
	}

	// Fungsi Query database MySQL mengembalikan dalam bentuk ROW
	function sql_fetchrow($query_id = 0)
	{
		if(!$query_id)
		{
			$query_id = $this->query_result;
		}
		if($query_id>0)
		{
			$this->row[$query_id] = mysql_fetch_assoc($query_id);
			return $this->row[$query_id];
		}
		else
		{
			return false;
		}
	}

	// Fungsi Query database MySQL mengembalikan dalam bentuk ROW
	function sql_fetchrowset($query_id = 0)
	{
		if(!$query_id)
		{
			$query_id = $this->query_result;
		}
		if($query_id)
		{
			unset($this->rowset[$query_id]);
			unset($this->row[$query_id]);
			while($this->rowset[$query_id] = mysql_fetch_array($query_id))
			{
				$result[] = $this->rowset[$query_id];
			}
			return $result;
		}
		else
		{
			return false;
		}
	}

	// Fungsi Menampilkan SQL Error
	function sql_error($query="")
	{
		$result["query"] = $query;
		$result["message"] = mysql_error();
		$result["code"] = mysql_errno();

		echo "Database Error : ".$result['message']."<br/>Code : ".$result['code']."<br/>Query : ".$result['query'];
	}
	
	/*QUERY GENERATOR*/
		
	function sqlQuery($sql, $type="query") {
		$field 	= "";
		$fvals 	= "";
		$cols 	= "";
		$vals 	= "";
		$cond 	= "";
		$order 	= "";
		$limit 	= "";

		//GENERATE FIELD
		if(isset($sql['field']) && !isset($sql['fvals'])) {
		$koma	= "";
			for($i=0;$i<count($sql['field']);$i++) {
				$field .= $koma.$sql['field'][$i];
				$koma	= ", ";
			}
		}
		
		//GENERATED COLS ONLY
		if(isset($sql['cols'])) {
		$koma	= "";
			for($i=0;$i<count($sql['cols']);$i++) {
				$cols .= $koma.$sql['cols'][$i];
				$koma	= ", ";
			}
		}
		
		//GENERATED VALS ONLY
		if(isset($sql['vals'])) {
		$koma = "";
			for($i=0;$i<count($sql['vals']);$i++) {
				$comparator = "'".$sql['vals'][$i]."'";
				if(isset($sql['ctype'])) {
					switch ($sql['ctype'][$i]) {
						case "int" :
							$comparator = $sql['vals'][$i];
							break;
						case "fkey" :
							$comparator = $sql['vals'][$i];
							break;
						default:
							$comparator = "'".$sql['vals'][$i]."'";
							break;
					}
				}
				$vals .= $koma.$comparator;
				$koma	= ", ";
			}
		}
		
		//GENERATE WHERE CONDITION
		$space = "";
		$colandvals = "";
		if(isset($sql['cols'])) {
			for($i=0;$i<count($sql['cols']);$i++) {
				$comparator = "'".$sql['vals'][$i]."'";
				if(isset($sql['ctype'])) {
					switch ($sql['ctype'][$i]) {
						case "int" :
							$comparator = $sql['vals'][$i];
							break;
						case "fkey" :
							$comparator = $sql['vals'][$i];
							break;
						default:
							$comparator = "'".$sql['vals'][$i]."'";
							break;
					}
				}
				$colandvals .= $space.$sql['cols'][$i]."=".$comparator;
				$space = " AND ";
			}
			$cond = " WHERE ".$colandvals;
		}
		if(isset($sql['cond'])){
			$cond .= " ".$sql['cond'];
		}

		//GENERATE LIMIT CONDITION
		if(isset($sql['limit'])) {
			if(isset($sql['offset']) || $sql['offset']=="0") {
				$limit = " LIMIT ".$sql['offset'].",".$sql['limit'];
			} else {
				$limit = " LIMIT ".$sql['limit'];
			}
		}
		//GENERATE ORDER CONDITION
		if(isset($sql['order'])) {
			$order = " ORDER BY ".$sql['order'];
		}
				
		switch ($type) {
			case "query" :
				$sql_query = "SELECT ".$field." FROM ".$sql['table'].$cond.$order.$limit;
				$this->set_query($sql_query);
				break;				
			case "numrow" :
				//Bikin kondisi
				$sql_query = "SELECT ".$field." FROM ".$sql['table'].$cond;
				$sql_query = $this->sql_numrows($sql_query);
				break;
			case "insert" :
				//Bikin kondisi
				$sql_query = "INSERT INTO ".$sql['table']." (".$cols.") VALUES (".$vals.")";
				$this->set_query($sql_query);
				break;
			case "update" :
				//Bikin update parameter
				$updateContent 	= "";
				$koma			= "";
				$colandvals		= "";
				
				if(isset($sql['fvals'])) {
					for($i=0;$i<count($sql['field']);$i++) {
						$comparator = "'".$sql['fvals'][$i]."'";
						if(isset($sql['ftype'])) {
							switch ($sql['ftype'][$i]) {
								case "int" :
									$comparator = $sql['fvals'][$i];
									break;
								case "fkey" :
									$comparator = $sql['fvals'][$i];
									break;
								default:
									$comparator = "'".$sql['fvals'][$i]."'";
									break;
							}
						}
						$colandvals .= $koma.$sql['field'][$i]."=".$comparator;
						$koma	= ", ";
					}
					$updateContent = " SET ".$colandvals;
				}

				$sql_query = "UPDATE ".$sql['table'].$updateContent.$cond;
				$this->set_query($sql_query);
				break;
			case "delete" :
				//Bikin kondisi
				$sql_query = "DELETE FROM  ".$sql['table'].$cond;
				//$this->set_query($sql_query);
				break;
		}
	return $sql_query;				
	}

	function createOffset($LIMIT,$hal) {
		$result =array();
		if ($hal=="") {
			$result[0]=0; //Offset
			$result[1]=1; //Halaman
		} else {
			$result[1]=$hal;
			$result[0]=($LIMIT*($result[1]-1));
		}
		return $result;
	}

	function createPage($offset,$numrows,$limit,$searching,$pages,$column,$key="",$ranges="")
	{
		$paging = array();
		$paging['offset'] 	= $offset;
		$paging['numrows'] 	= $numrows;
		$paging['limit'] 		= $limit;
		$paging['thishal'] 	= $pages;
		$paging['tothal'] 	= ceil($numrows/$limit);
		if($searching!="") {$paging['searchUrl']="&kword=".$searching;} else {$paging['searchUrl']="";}
		if($column!="") {$paging['colsUrl']="&opt=".$column;} else {$paging['colsUrl']="";}
		if($key!="") {$paging['colsUrl'].="&key=".$key;}
		if($ranges!="") {$paging['colsUrl'].=$ranges;}

		//Previous Pages
		if ($pages!=1) {
			$prev=$pages;
			$paging['prev']=$prev-1;
		}

		//Next Pages
		if ($pages < $paging['tothal']) {
			$next=$pages;
			$paging['next']=$next+1;
		}
		return $paging;
	}

	function rs_not_null($value) {
		if (is_array($value)) {
			if (sizeof($value) > 0) {
				return true;
			} else {
				return false;
			}
		} else {
			if (($value != '') && (strtolower($value) != 'null') && (strlen(trim($value)) > 0)) {
				return true;
			} else {
				return false;
			}
		}
	}
}
?>