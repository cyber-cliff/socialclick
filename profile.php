<?php 
/*
Programmer HarryD
Start Project 2/10/2013
*/
include('config.php');

$user =new User();
$page=new staticPage();
$shorturl = new Shorturl();
global $user;
$page->NavHandler('profile');
$page->Load();//ini mesti paling bawah
?>