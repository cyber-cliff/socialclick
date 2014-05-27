<?php 
if(isset($_GET['id'])){
	$country_code = @$_GET['id'];
	$headers = apache_request_headers(); 
	$file = file_exists($country_code.".gif") ? $country_code.".gif" : "unk.gif";
		//$file = "$country_code.gif";
		//$expires = 60*60*24*14;
		header("Pragma: public");
		//header("Cache-Control: maxage=".$expires);
		//header('Expires: ' . gmdate('D, d M Y H:i:s', time()+$expires) . ' GMT');
		header('Content-Type: image/gif');
		//header('Cache-Control: max-age=0');
		header('Content-Length: ' . filesize($file));
		
		    // Checking if the client is validating his cache and if it is current.
		if (isset($headers['If-Modified-Since']) && (strtotime($headers['If-Modified-Since']) == filemtime($file))) {
			// Client's cache IS current, so we just respond '304 Not Modified'.
			header('Last-Modified: '.gmdate('D, d M Y H:i:s', filemtime($file)).' GMT', true, 304);
		} else {
			// Image not cached or cache outdated, we respond '200 OK' and output the image.
			header('Last-Modified: '.gmdate('D, d M Y H:i:s', filemtime($file)).' GMT', true, 200);
			header('Content-Length: '.filesize($file));
			header('Content-Type: image/gif');
			print file_get_contents($file);
		}
		
		exit();
}




?>