<?php 
require('../config.php');
$mysqli = new mysqli();
$mysqli->connect(DB_HOST,DB_USER,DB_PWD,DB_NAME);
if($mysqli->connect_error) die("<h3>Cannot Connect Database Budu!! (".$mysqli->connect_errno.")</h3>");

$file_sql = @file_get_contents('../zone.sql');

if($mysqli->multi_query($file_sql)!==true){
	die("Error ".$mysqli->error);
}else{
	echo "success import baby";	
}

?>