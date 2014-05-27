<?php 
global $user,$nav,$page,$user; 
?>
<!DOCTYPE html>
<html>
<head>
<title><?= $this->PageTitle(); ?> | Social Click</title>
<meta charset="utf-8" />
<META NAME="ROBOTS" CONTENT="NOINDEX, NOFOLLOW">

<?php $this->js_script() ?>
<link rel="stylesheet" href="/js/src/jquery.fancybox.css?v=2.1.5" type="text/css" media="screen" />
<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css">
<link rel="stylesheet" href="/templates/style.css" type="text/css">

<script type="application/javascript">
$(function(){
	 $('div#clickbycountry,tr.listlink td a,tr.listlink td span').tooltip();
});
$(document).ready(function(e) {
	$('.fancy').fancybox({maxWidth	: 800,maxHeight	: 600});	
	$(".various").fancybox({
		maxWidth	: 800,
		maxHeight	: 600,
		fitToView	: true,
		width		: '70%',
		height		: '70%',
		autoSize	: true,
		closeClick	: false,
		openEffect	: 'none',
		closeEffect	: 'none'
	});	
	
	$('#addlink').click(function(e) {
		var theurl = $('#theurl').val();
		theurl = theurl.trim();
        $.ajax({
			type:"POST",
			url:'addurl.php',
			data:{url:theurl},
			dateType:'json',
			beforeSend:function(xhr){
				if(theurl.length>0){
					$('#addlink').attr('disabled','disabled');
					$('input#theurl').attr('disabled','disabled');
					showLoading();
				}else{
					xhr.abort();
				}
			},
			success:function(data){
				hideLoading();
				if(isJSON(data)){
					var d = $.parseJSON(data);
					if(d.status){
						$('#addlink').removeAttr('disabled');
						$('input#theurl').removeAttr('disabled');
						//window.location.href = '<?php echo SITE_URL ?>details.php?s_c='+d.scode;
						addurlDialogBox(d.msg,true,'<?php echo SITE_URL ?>details.php?s_c='+d.scode);
					}else{
						addurlDialogBox(d.msg);
						$('#addlink').removeAttr('disabled')
						$('input#theurl').removeAttr('disabled');	
					}
				}else{
					alert("failed add url");
					$('#addlink').removeAttr('disabled')
					$('input#theurl').removeAttr('disabled');	
				}
			}	
		});
    });
	
});
</script>
</head>
<body>
<div id="wrap">
<div id="header">
  <div class="title"> <a href="./" id="logox"></a>
    <h2>Social Click</h2>
  </div>
  <div id="navbar">
    <div id="navbarleft">
      <ul id="nav">
        <li><a href="<?php echo SITE_URL; ?>">Home</a></li>
        <?php if($user->loggedin()!=true) {?>
        <li><a href="register.html">Register</a></li>
        <?php }?>
      </ul>
    </div>
    <div id="navbarright">
      <ul id="navright">
        <?php if($user->loggedin()!=true) {?>
        <li><a class="rlogin" href="<?php echo SITE_URL; ?>?p=loginx" id="need_login">Login</a></li>
        <?php }else{?>
        <li><a class="rlogin" href="<?php echo SITE_URL; ?>logout.php" id="logout">logout</a></li>
        <?php } ?>
      </ul>
    </div>
  </div>
</div>
