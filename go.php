<?php 
@$uri = @$_GET['u'];

#header("Location: $uri",true,302);
#Print "<html><body onLoad=\"javascript: window.location='$uri';\"></body></html>";exit();
?>
<!DOCTYPE html>
<html>
<head>
<script>
function do_redir()
{
window.frames[0].document.body.innerHTML='<form target="_parent" method="post" action="<?php echo $uri ?>"></form>';
window.frames[0].document.forms[0].submit()
}
</script>
</head>
<body>
<iframe onload="window.setTimeout(do_redir,1);" src="about:blank" style="visibility:hidden"></iframe>
</body>
</html>