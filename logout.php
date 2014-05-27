<?php 
include_once('config.php');
include_once('Lib/user.php');
$u = new user();
$u->logout();
header("Location: ./");
?>