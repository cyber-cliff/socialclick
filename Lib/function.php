<?php 

//function PageTitle
function Page_title($title='My Popup'){
	echo $title;	
}

function the_content(){
		
}

function get_userid(){
	return @$_SESSION['userid'];	
}

function in_array_page($needle, $haystack){
		foreach($haystack as $key=>$item){
			if($key==$needle){
				return true;	
			}
		}
		return false;
}
function Generatedpage(){
	global $starttime,$mtime;
	$mtime = microtime(); 
	$mtime = explode(" ",$mtime); 
	$mtime = $mtime[1] + $mtime[0]; 
	$starttime = $mtime;
}
Generatedpage();
function getGeneratedpage(){
   global $starttime,$mtime;
   $mtime = microtime(); 
   $mtime = explode(" ",$mtime); 
   $mtime = $mtime[1] + $mtime[0]; 
   $endtime = $mtime; 
   $totaltime = ($endtime - $starttime); 
   $totaltime = round($totaltime,5);
   $resultEx=  "Page Loadtime ".$totaltime." sec"; 
   return $resultEx;
}

function redirect_link($url){
	if(strpos($url,'http')===0)
		header('Location: '.$url);
	else
		header('Location: http://'.$url);
}

function generateurl($numAlpha=6)
{
   #dbconnect();
   $listAlpha = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
   return str_shuffle(
      substr(str_shuffle($listAlpha),0,$numAlpha)
  );
}

function getUserID(){
	return isset($_SESSION['userid']) ? $_SESSION['userid'] : "";	
}

function file_get_contents_curl($url)
{
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);

    $data = curl_exec($ch);
    curl_close($ch);

    return $data;
}

function number_only($str){
	$slength = strlen($str);
	$returnVal = null;
	for($i=0;$i<$slength;$i++){
		if(is_numeric($str[$i])){
			$returnVal .=$str[$i];
		}
	}
	return $returnVal;
}

function db_con(){ //Get Database connection
		static $dbc;
		if(!isset($dbc)){
			@$dbc = new mysqli(DB_HOST,DB_USER,DB_PWD,DB_NAME);
			$mysqli = $dbc; 
			if($mysqli->connect_error){
				die("<h3>Unable to connect to database ['".$mysqli->connect_error."(<font color=\"#0033FF\">".$mysqli->connect_errno."</font>)']</h3>");
			}
		}
		return $dbc;
}

function datetime_ago($datetime, $full = false) {
    $now = new DateTime;
    $ago = new DateTime($datetime);
    $diff = $now->diff($ago);
	
    $diff->w = floor($diff->d / 7);
    $diff->d -= $diff->w * 7;

    $string = array(
        'y' => 'year',
        'm' => 'month',
        'w' => 'week',
        'd' => 'day',
        'h' => 'hour',
        'i' => 'min',
        's' => 'sec',
    );
    foreach ($string as $k => &$v) {
        if ($diff->$k) {
            $v = $diff->$k . ' ' . $v. ($diff->$k > 1 ? 's' : '');
        } else {
            unset($string[$k]);
        }
    }

    if (!$full) $string = array_slice($string, 0, 1);
    return $string ? implode(', ', $string) . ' ago' : 'just now';
}

function humanTiming ($time)
{
	$time = strtotime($time);
    $time = time() - $time; // to get the time since that moment

    $tokens = array (
        31536000 => 'year',
        2592000 => 'month',
        604800 => 'week',
        86400 => 'day',
        3600 => 'hour',
        60 => 'min',
        1 => 'sec'
    );

    foreach ($tokens as $unit => $text) {
        if ($time < $unit) continue;
        $numberOfUnits = floor($time / $unit);
        return $numberOfUnits.' '.$text.(($numberOfUnits>1)?'s':'')." ago";
    }

}

function datetime_elapsed($time){
	if (version_compare(PHP_VERSION, '5.4.0') >= 0) {
		return datetime_ago($time);
	}else{
		return humanTiming($time);
	}
}


function get_user_timezone(){
	if(!isset($_SESSION['timezone'])) :
		include_once("ip2c/ip2country.php");
		
		if($_SERVER['REMOTE_ADDR']=='127.0.0.1'){
			$_SESSION['timezone']=ini_get('date.timezone');
		}else{
			$ip2country = new ip2country();
			$timezone = $ip2country->get_country_timezonename();
			if ($timezone)
				$_SESSION['timezone'] = $timezone;
			else 
				$_SESSION['timezone'] = ini_get('date.timezone');
		}
	endif;
}

function getCountryList(){
	$mysqli = db_con();
	//$returnVal[] = array("asd","asd");
	$result = $mysqli->query("SELECT code,name FROM `country`");
	if($result){
		while($row = $result->fetch_row()){
			$returnVal[] = $row;
		}	
	}else{
		print $mysqli->connect_error;	
	}
	return $returnVal;	
}


?>