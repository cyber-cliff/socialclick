<?php 
global $user;

if(isset($_POST['changePass'])){
	$error_msg = '';
	$success_msg = '';
	if(!empty($_POST['oldpass']) && !empty($_POST['newpass']) && !empty($_POST['verpass']) ){
		$oldpass = trim($_POST['oldpass']);
		$newpass = trim($_POST['newpass']);
		$verpass = trim($_POST['verpass']);
		if($newpass==$verpass){
			if($user->changePass($oldpass,$newpass)){
				$success_msg = 'Password Change Successful';
			}else{
				$error_msg = 'Password Change Failed';
			}
		}else{
			$error_msg='password not match';
		}
	}else{
		$error_msg = "*all field are required";
		#if(empty($_POST['oldpass']));
		#if(empty($_POST['newpass']));
		#if(empty($_POST['verpass']));
	}
}
?>

<div id="profile">
	<div id="profile_details">
    	<table cellpadding="5" cellspacing="5">
        	<tr><td>Username</td><td>:</td><td><b><?= $user->getUsername() ?></b></td></tr>
            <tr><td>Email</td><td>:</td><td><b><?= $user->getEmail() ?></b></td></tr>
        </table>
    </div><hr />
<?php if($user->loggedin()) :?>
  <form action="" method="post">
     <table cellspacing="5" style="color:#333">
      <tr>
        <td colspan="3"><b>Change Password</b></td>
      </tr>
      <tr><td colspan="3"><span style="color:#090"><?=@$success_msg?></span></td></tr>
      <tr>
        <td>Old password</td>
        <td> : </td>
        <td><input type="password" name="oldpass" maxlength="32" /></td>
      </tr>
      <tr>
        <td>New password</td>
        <td> : </td>
        <td><input type="password" name="newpass" maxlength="32" /></td>
      </tr>
      <tr>
        <td>Re-enter password</td>
        <td> : </td>
        <td><input type="password" name="verpass" maxlength="32" /></td>
      </tr>
      <tr>
        <td colspan="3" align="left" width="200px">
            <input class="bluebutton" style="padding:2px 4px;" type="submit" value="Submit" name="changePass"/>
            <span class="error_msg"><?= @$error_msg ?></span>
        </td>
      </tr>
    </table>
  </form><hr /><?php endif ?>
</div>
