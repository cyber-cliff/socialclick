<?php 
include_once('config.php');
include_once('Lib/user.php');
#error_reporting(E_ALL);
//$data = null;

/* LOGIN AJAX */
if(isset($_GET['login'])){
	$User = new User();
	$usr = $_POST['username'];
	$pwd = $_POST['password'];
	if(!empty($usr) || !empty($pwd)){
		$keeplogin = isset($_POST['keeploggedin']) ? true : false;;
		if($User->login($usr,$pwd,$keeplogin)){
			$data['login_status']='true';
			$data['error']="";
		}else{
			$data['login_status']=false;
			$data['error']="<b>Error : </b>Incorrect username/email/password";
		}
	}else{
		$data['login_status']=false;
		$data['error']="<b>Error : </b>Please insert username or email and password";	
	}
echo json_encode($data);
}

/* SET URL SETTINGS */
if(isset($_GET['set_opt'])){
	if(!user::loggedin()){
		$data["status"] = false;
		$data["msg"] = "Save failed try again, \n You are not loggin";
		echo json_encode($data);exit();
	}
	$surl = new Shorturl();
	//$ouv = @$_POST['ouv']=='true'? true : false;
	//$hf = @$_POST['hf']=='true'? true : false;
	
	$scode = @$_POST['shortcode'];
	$opt = @$_POST;
	if($surl->update_opt($scode,$opt)===true){
		$data["status"] = true;
		$data["msg"] = "Save Successful";
		//$data['thepost'] = $_POST['country_not_on_list'];	
	}else{
		$data["status"] = false;
		$data["msg"] = "Save failed try again";
	}
	echo json_encode($data);
}

/* URL DETAILS GET STATS */
if(isset($_GET['details'])){
	$d_act = $_GET['details'];
	//CBC click by Country | CBO click by os
	$Stats=new StatsUrl($_POST['sc']);
	if($d_act=='CBC'){
		
	}else if($d_act=='CBO'){
		$data['cols'] = array(
					array("id"=> 'os', 'label'=> 'Platforms', 'type'=> 'string'),
					array("id"=> 'click', 'label'=> 'Click', 'type'=> 'number'),
					array("id"=> 'click', 'label'=> 'Click', 'type'=> 'string','role'=>'style')
				);
		$data['rows'] = array();
		foreach($Stats->ClickByOs() as $key=>$out){
			array_push($data['rows'],array('c'=>array(array('v'=>$key),array('v'=>$out))));
		}
		print json_encode($data);
	}
	
}

?>