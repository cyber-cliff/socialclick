<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8" />
<title>Login</title>
<style type="text/css">
*{
	margin:0;
	padding:0;
}
body {
	background-color:#222;background:url('images/carbonfiber.png');
	font-family:Arial, Helvetica, sans-serif;
}
#wrap {
	width:100%;
}
.login {
	position:relative;
	width:350px;
	height:auto;
	margin:0 auto;
	margin-top:10%;
}
.login h2 {
	text-align:center;
	color:#FF0000;
	font-family:"Times New Roman", Times, serif;font-size:36px;
	text-shadow:-1px -1px 0 #ccc,  
    1px -1px 0 #ccc,
    -1px 1px 0 #ccc,
     1px 1px 0 #ccc;
}
.loginform {
	background:#E9E9E9;
	border-radius:5px;
	border:1px solid #CCC;
}
.loginform form {
	padding:10px 0;
}
.input{
	width:200px;
	margin:8px auto;
}
.input span{color:#585858}
.input input,.submit input {
	display:block;
	margin:0 auto;
	padding:2px 4px;
	margin:0px auto;
	border:1px solid #888;
	border-radius:3px;
	font-size:18px;
}
.submit{margin-top:10px;}
.submit input{}
.submit input:active{background:#222}
.submit input:hover{background:#DDDDDD}
</style>
<script src="js/jquery-2.0.3.min.js" type="text/javascript"></script>
<script type="text/javascript">
$(document).ready(function(e) {
	$('#loginbutton').click(function(e) {
		e.preventDefault();
        var username = $('#username').val();
		var password = $('#password').val();
		
		$.ajax({
			type:"POST",
			url:"ajax.php?login=true",
			dateType:'json',
			data:$('form').serialize()
		}).done(function(msg){
			var bab = $.parseJSON(msg);
				alert(bab.error);
		});
    });
});
</script>
</head>
<body>
<div id="wrap">
  <div class="login">
    <h2>Social Click Login</h2>
    <div class="loginform">
      <form method="post" action="" id="form">
      	<div class="input">
        <span>Username</span>
        <input name="username" type="text"  id="username" />
        </div>
        <div class="input">
        <span>Password</span>
        <input name="password" type="password" id="password"  />
        </div>
        <div class="submit">
        <input name="login" type="submit"  value="Login" id="loginbutton"/>
        </div>
      </form>
    </div>
  </div>
</div>
</body>
</html>