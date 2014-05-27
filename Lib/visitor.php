<?php
#header('Content-Type: application/javascript');

include('ip2c/ip2country.php');
$ip2c = new ip2country();
/*
$ip = null;
if(isset($_GET['ip'])){
	if($_GET['ip'] != '') $ip = $_GET['ip'] ;
}else{
	$ip = $ip2c->get_client_ip();
}
function output(){
	global $ip,$ip2c;
	$ipdetails['client_ip'] = $ip;
	$ipdetails['client_country_code'] = $ip2c->get_country_code($ip);
	$ipdetails['client_country_name'] =  $ip2c->get_country_name($ip);
	echo json_encode($ipdetails);
}

output();
*/
?>
