
<div class="main-item">
  <?php 
function notempty(){
	$test=false;
	foreach($_POST as $key=>$out){
		if(empty($out)){
			$test = true;
			break;
		}	
	}
	return $test;
}
$error = null;
if(isset($_POST['register'])){
if(isset($_POST['username']) && isset($_POST['password']) && isset($_POST['cpassword']) && isset($_POST['email'])){
	global $user;
	$usr =$_POST['username'];$pwd =$_POST['password'];$cpwd=$_POST['cpassword'];$email=$_POST['email'];
	if(!notempty()){
		if($pwd===$cpwd){
			$result = $user->register($usr,$pwd,$email);
			if($result!==true){
				$error = $result;
			}
		}else{
			$error ="Password not match.";	
		}
	}else{
		$error ="All field are required.";
	}	 
}}
#var_dump($_POST);
?>
  <div id="regform">
  	<br>
  	<div style="color:#333;font-family: 'Freight Sans', 'lucida grande',tahoma,verdana,arial,sans-serif;font-weight:normal;font-size:24px">Register and start exchange link</div>
    <div style="margin:0 2px;color:red;background-color:#FFCCFF">
      <?php if(isset($error))echo $error ?>
    </div>
    <form method="post" action="">
      <input name="username" type="text" placeholder="username" maxlength="32" required="required" onkeypress="return alpha(event);"<?php if(isset($_POST['username'])){echo "value='{$_POST['username']}'";} ?>/>
      <input name="password" type="password" placeholder="password" maxlength="32" required="required" />
      <input name="cpassword" type="password" placeholder="comfirm password" maxlength="32" required="required" />
      <input name="email" type="email" placeholder="ex: jack@gmail.com" maxlength="32" required="required" <?php if(isset($_POST['email'])){echo "value='{$_POST['email']}'";} ?> />
      <input type="submit" name="register" value="register" />
    </form>
  </div>
</div>
