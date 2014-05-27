<?php 
class db{
	public static $mysqliObj;
	
	function __construct(){
	
	}
	
	static function getConnection(){
		static $mysqliObj;
		if(!isset($mysqliObj)){
			@$db=new mysqli(DB_HOST,DB_USER,DB_PWD,DB_NAME);
			$mysqliObj = $db;
			self::$mysqliObj = $db;
			if($mysqliObj->connect_error){
				die("<h3>Unable to connect to database ['".$mysqliObj->connect_error."(<font color=\"#0033FF\">".$mysqliObj->connect_errno."</font>)']</h3>");	
			}
		}
		
		return self::$mysqliObj;
	}
	
	static function con(){
		return self::$mysqliObj;	
	}
	
	function __destruct(){
		if(isset(self::$mysqliObj)){
			self::$mysqliObj->close();
		}
	}

}
?>