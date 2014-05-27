<?php 
include_once('config.php');
include_once('Lib/user.php');

$u = new user();
$msg = array();
if(isset($_POST['login'])){
	$usr = $_POST['username'];
	$pwd = $_POST['password'];
	
	if(!empty($usr) || !empty($pwd)){
		if($u->login($usr,$pwd)===true){
			header("Location: ./");
		}else{
			
		}
	}else{
		echo "Please insert username/password";
	}
}


?>