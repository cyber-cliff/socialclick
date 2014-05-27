<?php 
session_name('socialclick');
session_start();

include('Lib/harryd_error_handler.php');
require('Lib/db.php');
include('Lib/function.php');//Global Function and Must On the top
include_once('Lib/visitor.php');

require('Lib/user.php');
include('Lib/page.php');
include('Lib/shorturl.php');
include('Lib/link.php');
include('Lib/StatsUrl.php');
$db = new db();//init mysqli db

if(basename($_SERVER["SCRIPT_FILENAME"], '')!=='s.php'){// xpaya set kalau script s.php
	get_user_timezone();
	date_default_timezone_set($_SESSION['timezone']);
}

?>