<?php
#error_reporting(0);
include('config.php');
include_once('Lib/shorturl.php');
error_reporting(0);
if($_POST){
	if(!user::loggedin()){
		$data["status"] = false;
		$data["msg"] = "<span style=\"color:#FF0000\">Please Login to add url</span><br> or <a href='".SITE_URL."register.html'>Register here</a>";
		echo json_encode($data);exit();
	}
	$shorturl = new Shorturl();
	//$pattern = "/(ftp|http|https):\/\/(\w+:{0,1}\w*@)?(\S+)(:[0-9]+)?(\/|\/([\w#!:.?+=&%@!\-\/]))?/";
	$url = @$_POST['url'];
	if($shorturl->ValidateUrl($url)){
		if($shorturl->CreateShortUrl($url)===true){
			$data['msg'] = 'Your Link Successful added';
			$data['status'] = true;
			$data['scode'] = $shorturl->getScode();
		}else{
			$data['msg'] = 'Error please try again';
			$data['status'] = false;
		}
	}else{
		$data['msg'] = 'Error Url is not valid';
		$data['status'] = false;	
	}
}else{
	$data['msg'] = 'Error please try again';
	$data['status'] = false;
}
//header('Content-Type: application/json');
echo json_encode($data);
?>