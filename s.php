<?php 
$u_agent = @$_SERVER['HTTP_USER_AGENT'];

//block list User Agents
$block_agent = array(
					'Mozilla/5.0 (compatible; aiHitBot/2.8; +http://endb-consolidated.aihit.com/)'
				);
$exception_agent = array('facebookexternalhit/1.1 (+http://www.facebook.com/externalhit_uatext.php)');
if(in_array($u_agent,$block_agent)){
	// Block user agents
	exit();	
}
#Short Url Redirect Codingdong
require_once('config.php');
#include_once('Lib/link.php');
#error_reporting(0);

if($_GET){
	if(isset($_GET['c'])){
		$shortcode = @$_GET['c'];
		$Link = new Link($shortcode);
		$ori_url = $Link->getLongUrl();
		
		if(isset($ori_url)){
			if($Link->isExceptCountry()===false){
				redirect_link($Link->getConRedirectUrl());
				$Link->RecordClick();
				exit;	
			}
			
			
			if($Link->OnlyUniqueVisitor()){
				if($Link->isReturnVisitor()){
					ReturnVisitorMsg();
					exit();
				}
			}
			ob_start();
			#header("HTTP/1.1 301 Moved Permanently");
			if(@$_SERVER['HTTP_REFERER']==null){
				header("Location: $ori_url",true,301);
			}else{
				redirect_blank_referrer($ori_url);
			}
			if(in_array($u_agent,$exception_agent)==false)$Link->RecordClick();
			ob_flush();
			exit();
		}
	}
}
function ReturnVisitorMsg(){
echo <<<LALA
	<!DOCTYPE html>
	<html><head></head>
	<body bgcolor="#DFEBFD">
	<center><br><h2>This Url track for unique visitor only</h2></center>
	</body>
	</html>
LALA;
}
?>


<?php function redirect_blank_referrer($url_blank){ ?>
<!DOCTYPE html>
<html>
<head>
<script>
function do_redir()
{
window.frames[0].document.body.innerHTML='<form target="_parent" method="" action="<?php echo $url_blank ?>"></form>';
window.frames[0].document.forms[0].submit()
}
</script>
</head>
<body>
<iframe onload="do_redir();" src="about:blank" style="visibility:hidden"></iframe>
</body>
</html>
<?php } ?>
<!DOCTYPE html>
<html>
<title>Somethings's Wrong</title>
<style type="text/css">
*{padding:0;margin:0;font-family:Arial, Helvetica, sans-serif;}
h3{color:#222}
span{color:#444;}
a{color:#03F}
</style>
<body bgcolor="#E8F3FF">
<div style="width:300px;margin:30px auto;border:1px solid #9CC;padding:10px;background-color:#F9FEFF;text-align:center">
    <h3>Somethings's Wrong :(</h3>
    <span>Cannot link the URL you Click</span><br>
    <a href="<?php echo SITE_URL ?>">HOME</a>
</div>
</body>
</html>