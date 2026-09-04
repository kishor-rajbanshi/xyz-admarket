<?php
// File Name  : display.php
// Created on : 18/11/2017
// Copyright © Renaisoft Solutions Private Limited


class Master //static class
{
	private function __construct()
	{
	}

	static function print_error($message)
	{
		ob_clean();
		echo $message;
		die;
	}

	static	function unsanitize($var)
	{
		return get_magic_quotes_gpc() ? stripslashes($var) : $var;
	}

	static function make_url($url,$url_prefix=array())
	{

		$params=explode("/",$url);
		if(isset($params[0]))
			$params[0]= str_replace('_', '-', $params[0]);
		if(isset($params[1]))
			$params[1]= str_replace('_', '-', $params[1]);
		$url=implode("/", $params);
		
		$folder=substr($_SERVER['SCRIPT_NAME'],0,strrpos($_SERVER['SCRIPT_NAME'],"/"));
// 		if($folder!="")
// 		{
			$folder.="/";
// 		}
		return ( USE_HTTPS ? "https://" : "http://" ).$_SERVER['HTTP_HOST'].$folder.((MOD_REWRITE) ? $url : "index.php?page=".$url);
	}
	
	static function make_base_url($url,$dir_name="")
	{
		$params=explode("/",$url);
		if(isset($params[0]))
			$params[0]= str_replace('_', '-', $params[0]);
		if(isset($params[1]))
			$params[1]= str_replace('_', '-', $params[1]);
		$url=implode("/", $params);
		
		if($dir_name!="")
		{
			$dir_name.="/";
		}
		return ((MOD_REWRITE) ? BASE.$dir_name.$url : BASE.$dir_name."index.php?page=".$url);
	}
	
	static function set_root_cookie($cookie,$value,$expiry=0)
	{
		if(substr(BASE,0,7)=="http://")
		{
			$domain=substr(BASE,7);
		}
		if(substr(BASE,0,8)=="https://")
		{
			$domain=substr(BASE,8);
		}
		if(substr($domain,0,4)=="www.")
		{
			$domain=substr($domain,4);
		}
		$domain=substr($domain,0,strpos($domain,"/"));
		setcookie($cookie,$value,$expiry,"/",$domain);
	}

	static function read_get_param($var)
	{
		//return $_GET[$var];
		
		return (isset($_GET[$var])) ? self::unsanitize(trim($_GET[$var])) : "";
	}

	static function read_post_param($var)
	{
		if(isset($_POST[$var]) && is_array($_POST[$var]))
		{
			$value = array_map('trim', $_POST[$var]);
			$value = array_map('self::unsanitize', $value);
			return $value;
		}
		else 
		{
			return (isset($_POST[$var])) ? self::unsanitize(trim($_POST[$var])) : "";
		}
	}

	static function read_cookie_param($var)
	{
		return (isset($_COOKIE[$var])) ? self::unsanitize(trim($_COOKIE[$var])) : "";
	}

	static function read_request_param($var)
	{
		if(isset($_REQUEST[$var]) && is_array($_REQUEST[$var]))
		{
			$value = array_map('trim', $_REQUEST[$var]);
			$value = array_map('self::unsanitize', $value);
			return $value;
		}
		else 
		{
			return (isset($_REQUEST[$var])) ? self::unsanitize(trim($_REQUEST[$var])) : "";
		}
	}
	
	
	static function get_base_path($dir='')
	{
		$srv_nms=DISPLAY_BASE;
		$srv_nms=str_replace("http://","",$srv_nms);
		$srv_nms=str_replace("https://","",$srv_nms);
		$srv_nms=str_replace("www.","",$srv_nms);
		$srv_pats=$srv_nms;
		$srv_nms_arr=explode("/",$srv_nms);
		$srv_nms=$srv_nms_arr[0];
		$srv_pats=str_replace($srv_nms."/","",$srv_pats);
	
		if($dir !='')
		$srv_pats=$srv_pats.$dir."/";
	
		return '/'.$srv_pats;
	}
	
	static function get_base_domain()
	{
		$srv_nms=DISPLAY_BASE;
		$srv_nms=str_replace("http://","",$srv_nms);
		$srv_nms=str_replace("https://","",$srv_nms);
		$srv_nms=str_replace("www.","",$srv_nms);
		$srv_pats=$srv_nms;
		$srv_nms_arr=explode("/",$srv_nms);
		$srv_nms=$srv_nms_arr[0];
	
		return $srv_nms;
	}
};

class ResultSet
{

	var $sql;
	var $error;
	var $num_records;
	var $result_index;
	var $last_id;
	var $record_set;

	function ResultSet()
	{
		$this->sql="";
		$this->error="";
		$this->num_records=0;
		$this->result_index=0;
		$this->last_id=0;
		$this->record_set="";
	}

	function set_sql($sql)
	{
		$this->sql=$sql;
	}

	function set_error($error)
	{
		$this->error=$error;
	}

	function set_num_records($num_records)
	{
		$this->num_records=$num_records;
	}

	function set_record_set($record_set)
	{
		//echo $record_set;
		$this->record_set=$record_set;
	}

	function set_last_id($last_id)
	{

		$this->last_id=$last_id;
	}

	function set_result_index($result_index=0)
	{
		$this->result_index=$result_index;
		if(DB_INTERFACE=='mysql')
			mysql_data_seek($this->record_set,$result_index);
		if(DB_INTERFACE=='mysqli')
			$this->record_set->data_seek($result_index);
	}

	function get_sql()
	{
		return $this->sql;
	}

	function get_error()
	{
		return $this->error;
	}

	function get_num_records()
	{
		return $this->num_records;
	}

	function get_record_set()
	{
		return $this->record_set;
	}

	function get_last_id()
	{
		return $this->last_id;
	}

	function get_result_index()
	{
		return $this->result_index;
	}

	function fetch_row()
	{
		//if(is_array($this->record_set))
		//{
			$this->result_index++;
			if(DB_INTERFACE=='mysql')
				return mysql_fetch_row($this->record_set);
			if(DB_INTERFACE=='mysqli')
				return $this->record_set->fetch_row();
			
		//}
		//return false;
	}

	function fetch_assoc()
	{
		//if(is_array($this->record_set))
		//{
		//echo "g";
			$this->result_index++;
			if(DB_INTERFACE=='mysql')
				return mysql_fetch_assoc($this->record_set);
			if(DB_INTERFACE=='mysqli')
				return $this->record_set->fetch_assoc();
		//}
		//return false;
	}

	function fetch_array()
	{
	//	if(is_array($this->record_set))
// 		{
			$this->result_index++;
			if(DB_INTERFACE=='mysql')
				return mysql_fetch_array($this->record_set);
			if(DB_INTERFACE=='mysqli')
				return $this->record_set->fetch_array();
// 		}
// 		return false;
	}

};

class DAL //singleton class
{

	private $conn=null;
	
	private function __construct()
	{
		$this->connect();
	}

	private	 function connect()
	{
		if(DB_INTERFACE=='mysql')
		{
			$this->conn=mysql_connect(DB_SERVER, DB_USER, DB_PASSWORD);
			
			if(!$this->conn)
			{
				$err_msg="<br><strong>Error: </strong>Requested page cannot be loaded.";
				if(ER)
				{
					$err_msg.="<br><strong>Details: "."Could not connect : " . mysql_error();
				}
				Master::print_error($err_msg);
				die;
			}
			if(!mysql_select_db(DB_NAME,$this->conn))
			{
				$err_msg="<br><strong>Error: </strong>Requested page cannot be loaded.";
				if(ER)
				{
					$err_msg.="<br><strong>Details: ". mysql_error();
				}
				Master::print_error($err_msg);
				die;
	
			}
			mysql_query("set names ".DB_CHARSET." collate ".DB_COLLATION,$this->conn);
			
			mysql_query("SET SESSION sql_mode = ''",$this->conn);
			
			mysql_query("SET OPTION SQL_BIG_SELECTS=1",$this->conn);			
		}
		
		if(DB_INTERFACE=='mysqli')
		{
			$this->conn=new mysqli(DB_SERVER, DB_USER, DB_PASSWORD,DB_NAME);
			
			if($this->conn->connect_errno > 0){
				$err_msg="<br><strong>Error: </strong>Requested page cannot be loaded.";
				if(ER)
				{
					$err_msg.="<br><strong>Details: "."Could not connect : " . $this->conn->connect_error;
				}
				Master::print_error($err_msg);
				die;				
			}
			
			//$this->conn->multi_query("set names ".DB_CHARSET." collate ".DB_COLLATION.";SET SESSION sql_mode = '';SET OPTION SQL_BIG_SELECTS=1;");

			$this->conn->query("set names ".DB_CHARSET." collate ".DB_COLLATION);
			
			$this->conn->query("SET SESSION sql_mode = ''");
			
			$this->conn->query("SET OPTION SQL_BIG_SELECTS=1");			
		}	
		
		
	}
	
	static function &get_instance()
	{
		static $instance = null;
		if (null === $instance)
		{
			$instance = new DAL();
		}
		return $instance;
	}

	private function keep_alive()
	{
		if(DB_INTERFACE=='mysql')
		if (!mysql_ping ($this->conn)) 
		{
			$this->connect();
		}
		if(DB_INTERFACE=='mysqli')
		if (!$this->conn->ping()) 
		{
			$this->connect();
		}
	}

	function sanitize($var)
	{
		if(DB_INTERFACE=='mysql')
		return mysql_real_escape_string($var,$this->conn);
		if(DB_INTERFACE=='mysqli')
		return $this->conn->real_escape_string($var);
	}


	function execute_query($sqlstring,$values=array())
	{
		$sqlstring=str_replace("'?'","?",$sqlstring);
		if(count($values)>0)
		{
			$sqlstr_arr=explode("?",$sqlstring);
			$i=0;
			$sqlstring="";
			foreach ($values as $v)
			{
				//$sqlstring = preg_replace("/\?/",$this->sanitize($v),$sqlstring,1);
				if(substr($sqlstr_arr[$i], -1) == "%" || substr($sqlstr_arr[$i], -1) == "_"){ // to handle LIKE
					$sqlstring.=$sqlstr_arr[$i].$this->sanitize($v);
				}else if(isset($sqlstr_arr[$i+1]) && (substr($sqlstr_arr[$i+1],0,1) == "%" || substr($sqlstr_arr[$i+1],0,1) == "_")){  // to handle LIKE
					$sqlstring.=$sqlstr_arr[$i].$this->sanitize($v);
				}else{
					$sqlstring.=$sqlstr_arr[$i]."'".$this->sanitize($v)."'";
				}
				$i++;
			}
			$sqlstring.=$sqlstr_arr[$i];
		}

		$query_type=substr($sqlstring,0,strpos($sqlstring," "));
		$last_id=0;
		$num_rows=0;
		
		if(DB_INTERFACE=='mysql')
		{
			$sqlresult=mysql_query($sqlstring,$this->conn);
			$error_string=mysql_error($this->conn);
		}
		if(DB_INTERFACE=='mysqli')
		{
			$sqlresult = $this->conn->query($sqlstring);
			$error_string=$this->conn->error;
		}
		if(strlen($error_string)>0)
		{
			if(ER)
			{
				echo "<br><strong>Query failed :</strong> ".$sqlstring."<br><strong>MySQL error :</strong> ".$error_string;
				flush();
			}
		}
		else
		{
			if(0==strcasecmp($query_type,"insert"))
			{
				/*$last_row_res=mysql_query("SELECT LAST_INSERT_ID() ",$this->conn);
				$last_row=mysql_fetch_row($last_row_res);
				$last_id=$last_row[0];*/
				if(DB_INTERFACE=='mysql')
					$last_id=mysql_insert_id($this->conn);
				if(DB_INTERFACE=='mysqli')
					$last_id=$this->conn->insert_id;
			}
			if(0==strcasecmp($query_type,"select"))
			{
				if(DB_INTERFACE=='mysql')
					$num_rows=mysql_num_rows($sqlresult);
				if(DB_INTERFACE=='mysqli')
					$num_rows=$sqlresult->num_rows; //this  is set in result set and not connection
			}
			else
			{
				if(DB_INTERFACE=='mysql')
					$num_rows=mysql_affected_rows($this->conn);
				if(DB_INTERFACE=='mysqli')
					$num_rows=$this->conn->affected_rows;
			}

		}

		$result=new ResultSet();
		$result->set_sql($sqlstring);
		$result->set_record_set($sqlresult);
		$result->set_last_id($last_id);
		$result->set_num_records($num_rows);
		$result->set_error($error_string);

		return $result;
			
	}

	function read_single_column($sqlstring,$values=array())
	{
		$sqlstring=str_replace("'?'","?",$sqlstring);
// 		foreach ($values as $v)
// 		$sqlstring = preg_replace("/\?/",$this->sanitize($v),$sqlstring,1);

		$sqlstr_arr=explode("?",$sqlstring);
		$i=0;
		$sqlstring="";
		foreach ($values as $v)
		{
			//$sqlstring = preg_replace("/\?/",$this->sanitize($v),$sqlstring,1);
			if(substr($sqlstr_arr[$i], -1) == "%" || substr($sqlstr_arr[$i], -1) == "_"){ // to handle LIKE
				$sqlstring.=$sqlstr_arr[$i].$this->sanitize($v);
			}else if(isset($sqlstr_arr[$i+1]) && (substr($sqlstr_arr[$i+1],0,1) == "%" || substr($sqlstr_arr[$i+1],0,1) == "_")){  // to handle LIKE
				$sqlstring.=$sqlstr_arr[$i].$this->sanitize($v);
			}else{
				$sqlstring.=$sqlstr_arr[$i]."'".$this->sanitize($v)."'";
			}
			$i++;
		}
		$sqlstring.=$sqlstr_arr[$i];
		
		
		if(stristr($sqlstring,"limit")=="")
		$sqlstring.=" limit 0,1 ";
		//echo $sqlstring;
		if(DB_INTERFACE=='mysql')
		{
			$sqlresult=mysql_query($sqlstring,$this->conn);
			$error_string=mysql_error($this->conn);
		}
		if(DB_INTERFACE=='mysqli')
		{
			$sqlresult = $this->conn->query($sqlstring);
			$error_string=$this->conn->error;
		}
		if(strlen($error_string)>0)
		{
			if(ER)
			{
				echo "<br><strong>Query failed :</strong> ".$sqlstring."<br><strong>MySQL error :</strong> ".$error_string;
				flush();
			}
		}
		if(DB_INTERFACE=='mysql')
		{
			$row=mysql_fetch_row($sqlresult);
		}
		if(DB_INTERFACE=='mysqli')
		{
			$row=$sqlresult->fetch_row();
		}
		return $row[0];
	}
};

class Configuration 
{
	var $conf=array();
	var $conf_table="config";
	var $conf_field="name";
	var $val_field="value";

	private function __construct()
	{
	}
	
	static function &get_instance()
	{
		static $instance = null;
		if (null === $instance)
		{
			$instance = new Configuration();
			$instance->init();
		}
		return $instance;
	}

	function init()
	{
		
		$this->memsuccess=0;
		if(defined('MEMCACHE_HOST') && defined('MEMCACHE_PORT') && MEMCACHE_HOST !='' && MEMCACHE_PORT !='')
		{
		$this->mem_cache =new Memcached();
		$this->mem_cache->addServer(MEMCACHE_HOST,MEMCACHE_PORT);
	
		$memstatus = $this->mem_cache->getStats();
	
		if(isset($memstatus[MEMCACHE_HOST.":".MEMCACHE_PORT]) && $memstatus[MEMCACHE_HOST.":".MEMCACHE_PORT]["pid"] > 0)
			$this->memsuccess=1;
		}
		if($this->memsuccess ==0)
		{
				$configarraypath=PATH_TO_ROOT.CACHE_DIR.'/configuration/configuration.php';
	
				$configarray=array();
	
				include($configarraypath);
	
				if($configurationarray)
				{
					$configarray=json_decode(str_replace("\'","'",$configurationarray));
	
					foreach($configarray as $key=>$value)
					{
						$this->conf["'".$key."'"]=$value;
					}
				}
				else
				{
					$db= DAL::get_instance();
					$res=$db->execute_query("select * from ".TABLE_PREFIX.$this->conf_table);
					while($row=$res->fetch_row())
					{
						$this->conf["'".$row[1]."'"]=$row[2];
					}
				}
		}
	}
	

	function read($conf)
	{
		if($this->memsuccess ==1)
		{
			$mem_cache_result = $this->mem_cache->get($conf);
	
			if($this->mem_cache->getResultCode() != Memcached::RES_SUCCESS)
			{
				$db= DAL::get_instance();
	
				$res=$db->execute_query("SELECT * from ".TABLE_PREFIX.$this->conf_table." WHERE `name`='?' limit 0,1",array($conf));
				$row=$res->fetch_row();
	
				if($this->mem_cache->getResultCode() == Memcached::RES_NOTFOUND)
				{
					if($row[1] == "")
						$this->mem_cache->set($conf,"");
						else
							$this->mem_cache->set($row[1],$row[2]);
				}
	
				if($row[1] != "")
					$mem_cache_result = $row[2];
					else
						$mem_cache_result ="";
	
			}
			return $mem_cache_result;
		}
		else
		{
			if(isset($this->conf["'".$conf."'"]))
				return $this->conf["'".$conf."'"];
			else
				return "";
		}
	}
	

	function update($conf,$val)
	{
		$db= DAL::get_instance();
		$res=$db->execute_query("update ".TABLE_PREFIX.$this->conf_table." set ".$this->val_field."=? where ".$this->conf_field."=?",array($val,$conf));
		$this->conf["'".$conf."'"]=$val;
	}

	function insert($conf,$val)
	{
		$db= DAL::get_instance();

		$res=$db->execute_query("INSERT INTO ".TABLE_PREFIX."config  (`id`,`name`, `value`) VALUES (?,?,?)",array('',$conf,$val));
	}
};

class Controller
{
	private $page="";
	private $controller="";
	private $action="";
	private $page_params=array();
	private $dir_path="";

	private $view_scalars=array();
	private $view_results=array();
	private $view_arrays=array();
	//	var $form="";
	private static $notice="";
	private static $notice_type="";
	private static $notice_enabled=TRUE;
	private static $title="";

	function __construct()
	{
	}

	function before_execute()
	{
	}

	function after_execute()
	{
	}

	function before_render()
	{
	}

	function after_render()
	{
	}

	function set_page($page)
	{
		$this->page=$page;
	}

	function get_page()
	{
		return  $this->page;
	}

	function set_controller($controller)
	{
		$this->controller=$controller;
	}

	function get_controller()
	{
		return $this->controller;
	}

	function set_action($action)
	{
		$this->action=$action;
	}

	function get_action()
	{
		return $this->action;
	}

	function set_page_params($page_params)
	{
		$this->page_params=$page_params;
	}

	function get_page_params()
	{
		return $this->page_params;
	}
	
	function set_dir_path($dir_path)
	{
		$this->dir_path=$dir_path;
	}
	
	function get_dir_path()
	{
		return $this->dir_path;
	}


	
	function read_page_param($position)
	{
		return ($position > count($this->page_params)) ? "" : $this->page_params[($position-1)]; // unsanitize is already done by AdDispatcher
	}

	function read_get_param($var)
	{
		return Master::read_get_param($var);
	}

	function read_post_param($var)
	{
		return Master::read_post_param($var);
	}

	function read_cookie_param($var)
	{
		return Master::read_cookie_param($var);
	}

	function read_request_param($var)
	{
		return Master::read_request_param($var);
	}

	function dispatch($page,$dir_path='')
	{
		AdDispatcher::dispatch($page,$dir_path);
	}

	function render()
	{ 
		require($this->dir_path.VIEW_DIR.DS.$this->controller.DS.$this->action.".php");
	}

	function make_url($url,$url_prefix=array())
	{
		return Master::make_url($url,$url_prefix);
	}

	function make_base_url($url,$dir_name="")
	{
		return Master::make_base_url($url,$dir_name);
	}

	function set_variable($var,$val,$escape=1)
	{
		if($var!="")
		{
			// 			if(is_scalar($val ))
			if(is_numeric($val) || is_string($var) || is_bool($val))
			{
				if($escape==1)
				{
					$val=htmlspecialchars($val,ENT_QUOTES,DEFAULT_CHARSET);
				}
				$this->view_scalars[$var]=$val;
			}
			else
			{
				if(ER)
				{
					echo "<br><strong>set_variable() failed :</strong> ".'$val'." parameter must be a <strong>scalar</strong>.";
					flush();
				}
			}
		}
	}

	function get_variable($var,$return_flag=1)
	{
		if($var!="")
		{
			$ret=array_key_exists("$var",$this->view_scalars) ? $this->view_scalars[$var] : "";
			if($return_flag)
			{
				return $ret;
			}
			else
			{
				echo $ret;
			}
		}
	}

	function set_result($var,$res,$raw_fields=array() )
	{
		if($res instanceof ResultSet)
		{
			if($res->get_num_records()>0)
			{
				$res->set_result_index(0);
			}
			//$tmp=clone $res;
			$arr=array();
			while($row=$res->fetch_assoc())
			{
				foreach ($row as $key => $value)
				{
					if(!in_array($key, $raw_fields))
					{
						$row[$key] = htmlspecialchars($value,ENT_QUOTES,DEFAULT_CHARSET);
					}
				}
				$arr[]=$row;
			}
			$this->view_results[$var]= $arr;
			if($res->get_num_records()>0)
			{
				$res->set_result_index(0);
			}
		}
		else
		{
			if(ER)
			{
				echo "<br><strong>set_result() failed :</strong> ".'$res'." parameter must be an object of <strong>ResultSet</strong> class.";
				flush();
			}
		}
	}

	function get_result($var)
	{
		if($var!="")
		{
			$ret=array_key_exists("$var",$this->view_results) ? $this->view_results[$var] : "";
			return $ret;
		}
	}

	function set_array($var,$arr,$raw_fields=array(),$dimensions=2 )
	{
		if(is_array($arr))
		{
			if($dimensions==1)
			{
				foreach ($arr as $key => $value)
				{
					if(is_array($value))
					{
						if(ER)
						{
							echo "<br><strong>set_array() failed :</strong> ".'$arr'." parameter must be 1D array.";
							flush();
						}
						return;
					}
					if(!in_array($key, $raw_fields))
					{
						$arr[$key] = htmlspecialchars($value,ENT_QUOTES,DEFAULT_CHARSET);
					}
				}
			}
			elseif($dimensions==2)
			{
				foreach ($arr as $key => $value)
				{
					if(!is_array($value))
					{
						if(ER)
						{
							echo "<br><strong>set_array() failed :</strong> ".'$arr'." parameter must be 2D array.";
							flush();
						}
						return;
					}
					foreach ($value as $k => $v)
					{
						if(is_array($v))
						{
							if(ER)
							{
								echo "<br><strong>set_array() failed :</strong> ".'$arr'." parameter must be 2D array.";
								flush();
							}
							return;
						}
						if(!in_array($k, $raw_fields))
						{
							$arr[$key][$k] = htmlspecialchars($v,ENT_QUOTES,DEFAULT_CHARSET);
						}
					}
				}
			}
			else
			{
				if(ER)
				{
					echo "<br><strong>set_array() failed :</strong> ".'$arr'." parameter must be 1D array or 2D array.";
					flush();
				}
				return;
			}
			$this->view_arrays[$var]= $arr;
		}
		else
		{
			if(ER)
			{
				echo "<br><strong>set_array() failed :</strong> ".'$arr'." parameter must be an array.";
				flush();
			}
		}
	}

	function get_array($var)
	{
		if($var!="")
		{
			$ret=array_key_exists("$var",$this->view_arrays) ? $this->view_arrays[$var] : "";
			return $ret;
		}
	}

	function set_root_cookie($cookie,$value,$expiry=0)
	{
		Master::set_root_cookie($cookie,$value,$expiry);
	}


	function escape($var)
	{
		return htmlspecialchars($var,ENT_QUOTES,DEFAULT_CHARSET);
		
	}
	
	function get_base_path($dir='')
	{
		return Master::get_base_path($dir);
	}
	
	function get_base_domain()
	{
		return Master::get_base_domain();
	}
};

class AdDispatcher //static class
{
	private static $dispatch_counter=0;

	private function __construct()
	{
	}

	static function get_dispatch_counter()
	{
		return self::$dispatch_counter;
	}

	static function set_dispatch_counter($dispatch_counter)
	{
		self::$dispatch_counter=$dispatch_counter;
	}

	static function instantiate_controller($classname,$dir_path,$route)
	{
		if(array_key_exists($classname,$GLOBALS['action_list']))
		{
			require_once(CONTROL_DIR.DS."application-controller.php");
			
			require_once(CONTROL_DIR.DS.$classname."-controller.php");
			
			$classname.="Controller";
			$classname=ucfirst($classname);
			
			$instance=new $classname;
			
			return $instance;
		}
		else
		{
			$err_msg="<br><strong>Error: </strong>Requested page cannot be loaded.";
			if(ER)
			$err_msg.="<br><strong>Details: </strong><strong>$classname-controller.php</strong> not found.";		
			Master::print_error($err_msg);
			die;
		}
	}

	static function dispatch($page="",$dir_path='',$route=1)
	{
		self::$dispatch_counter++;

		$params="";
		$count=0;
		
		// Reading controller,action names
		if($page=="")
		{
			$page= isset($_GET['page']) ? $_GET['page'] : "index/index";
		}
		$page =	Master::unsanitize($page);
		if($page=="index.php")
		{
			$page="";
		}
		
		$params=explode("/",$page);
		$count=count($params);
			
		$controller = str_replace( "-", "_", ($count>0) ? ( ($params[0]!="") ? $params[0] : "index" ) : "index" );
		//TODO: check if required => $controller=str_replace("-","_",$controller);
		$action = str_replace( "-", "_", ($count>1) ? ( ($params[1]!="") ? $params[1] : "index" ) : "index" );
			
		$valueparams=array();
		for($i=0;$i<($count-2);$i++)
		{
			$valueparams[$i]=$params[$i+2];
		}
		
		
	
		
		// Loading and Initalizing Controller Object
		$controller_instance=self::instantiate_controller($controller,$dir_path,$route);
		
		
		
			if(in_array($action,$GLOBALS['action_list'][$controller]))
			{
				$actionMethod= $action.'_action';
				
				$controller_instance->set_page($page);
				$controller_instance->set_controller($controller);
				$controller_instance->set_action($action);
				$controller_instance->set_page_params($valueparams);
				$controller_instance->set_dir_path($dir_path);
				
				$controller_instance->before_execute();
				$controller_instance->$actionMethod();
				$controller_instance->after_execute();	
				
				$controller_instance->before_render();
				$controller_instance->render();
				$controller_instance->after_render();				
				
				return;
			}
			else
			{
				$err_msg="<br><strong>Error: </strong>Requested page cannot be loaded.";
				if(ER)
				$err_msg.="<br><strong>Details: </strong><strong>{$action}_action()</strong> not found in $dir_path.$controller-controller.php";
				
				Master::print_error($err_msg);
				die;			
			}
	}
};

class Helper // static class
{



};
?>
