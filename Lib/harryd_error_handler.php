<?php 
set_error_handler('harry_error_handler', E_ERROR |  E_PARSE);
error_reporting( E_ERROR |  E_PARSE);
mysqli_report(MYSQLI_REPORT_OFF);
function harry_error_handler($number,$text,$theFile,$theLine){
	if(ob_get_length()) ob_clean();
	$errorMsg = 'Error: '. $number . chr(10) . 
				'Message: '. $text . chr(10) .
				'File: '. $theFile . chr(10) . 
				'Line: '. $theLine  ;  
	echo '<pre style="overflow:auto;">'.$errorMsg.'</pre>';
	#exit();
}
?>