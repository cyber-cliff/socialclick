<?php 

class user extends db{
	private $db;
	private $mysqli;
	private $table = 'url';
	function __construct(){
		$this->db = db::getConnection();
		$this->CheckLoginCookie();
	}
	
	private function open_con(){
		if(isset($this->db)) $this->db = db::getConnection();//parent::getConnection();
	}
	
	function __destruct(){
		#if(isset($this->db)) 
		#$this->db->close();
	}
	
	function login($usr,$pwd,$keeploggedin = false){
		#$mysql = new mysqli();
		$mysql = $this->db;
		#$usr = preg_replace("/[^A-Za-z0-9?! ]/"
		$usr = $mysql->real_escape_string($usr);
		$pwd = md5($pwd);
		$sqlLogin = "SELECT id,username,password,email FROM users WHERE (username='".$usr."' or email='".$usr."') AND password='".$pwd."'";
		$result = $mysql->query($sqlLogin);
		if($result->num_rows){
			$sevendays = time() + (7 * 24 * 60 * 60);
			$row = $result->fetch_assoc();
			
			//SESSION
			$this->setUserid($row['id']);
			$_SESSION['loggedin']=true;
			$_SESSION['username'] = $row['username'];
			$_SESSION['email'] = $row['email'];
			
			//COOKIES
			$cookiehash = md5(sha1(base64_encode(LOGIN_KEY.$row['id'].$row['username'])));
			setcookie("sc_uid",$row['id'],$sevendays,'/');
			setcookie("sc_user", $row['username'], $sevendays,'/');
			if($keeploggedin==true)setcookie('sc_lhash',$cookiehash,$sevendays);
			
			$lastLogin_update = "UPDATE `users` SET `lastlogin` = NOW() WHERE  `users`.`id` ={$row['id']}";
			@$mysql->query($lastLogin_update);
			
			#$result->close();
			return true;
		}else{
			#$result->close();
			return false;	
		}	
	}
	
	function CheckLoginCookie(){
		if(user::loggedin()==false){
			$login_hash = @$_COOKIE['sc_lhash'];
			$uid = 	@$_COOKIE['sc_uid'];
			$user = @$_COOKIE['sc_user'];
			if(!empty($login_hash))
			{
				$cookiehash = md5(sha1(base64_encode(LOGIN_KEY.$uid.$user)));
				
				if($login_hash==$cookiehash)
				{
					//$mysqli = new MySQLi();
					$mysqli = $this->db;
					$sqlLogin = "SELECT id,username,password,email FROM users WHERE username='".$user."' AND id=".$uid;
					if($result = $mysqli->query($sqlLogin))
					{
						$row = $result->fetch_assoc();
						//SET SESSIONS
						$this->setUserid($uid);//session $_SESSION['loggedin'] automatic
						$_SESSION['username'] = $user;
						$_SESSION['email'] = $row['email'];
						
						$lastLogin_update = "UPDATE `users` SET `lastlogin` = NOW() WHERE  `users`.`id` =$uid";
						@$mysqli->query($lastLogin_update);
					}else{
						echo "<h2>database error $mysqli->errno</h2>";
						exit();	
					}
				}
			}
		}
	}
	
	static function loggedin(){
		if(isset($_SESSION['loggedin'])){
			if($_SESSION['loggedin']==true){
				return true;
			}else{
				return false;
			}
		}else{
			return false;	
		}
	}
	
	function logout(){
		//unset($_SESSION['loggedin']);
		//unset($_SESSION['id']);
		//$_SESSION['username'] = null;
		$_SESSION = array();
		setcookie("sc_user", $this->getUsername(), time() - (7 * 24 * 60 * 60));
		setcookie("sc_uid", $this->getUsername(), time() - (7 * 24 * 60 * 60));
		setcookie("sc_lhash", $this->getUsername(), time() - (7 * 24 * 60 * 60));
		//session_destroy();
	}
	
	function register($usr,$pwd,$email){
		//$mysql = new mysqli(db_host,db_user,db_pwd,'8share');
		$this->open_con();
		$mysql = new mysqli();
		$mysql = $this->db;
		$usr =$mysql->real_escape_string(trim($usr));
		$pwd = md5(trim($pwd));
		$email = $mysql->real_escape_string(trim($email));
		$stmt = $mysql->prepare("INSERT INTO users (username,password,email,date_created) VALUES (?,?,?,CURRENT_TIMESTAMP)") ;
		$stmt->bind_param('sss',$usr,$pwd,$email);
		if(!$stmt->execute()){
			if($stmt->errno==1062){
				if(strrpos($stmt->error,'username'))
					$error = "Username <b>$usr</b> already exist";
				elseif(strrpos($stmt->error,'email'))
					$error = "Email <b>$email</b> already used";		
			}else{
				$error = "{$stmt->error} [$stmt->errno]";
			}
			$stmt->close();
			//ob_clean();
			return @$error;
		}else{
			$stmt->close();
			return true;
		}
	}
	
	function changePass($oldpass,$newPass){
		if($this->loggedin()==false){}
		$this->open_con();
		$mysqli = new mysqli();
		$mysqli = $this->db;
		$oldpass = md5($oldpass);
		$newPass = md5($newPass);
		//$sql = "UPDATE  users SET password = '$newPass' WHERE password='$oldpass' AND users.id={$this->getUserid()}";
		$sql = sprintf("UPDATE  users SET password = '%s' WHERE password='%s' AND users.id=%d",$newPass,$oldpass,$this->getUserid());
		if($mysqli->query($sql)===true){
			if($mysqli->affected_rows>0)return true;	
		}else{
			if($mysqli->connect_error){
				die($mysqli->error);
				return false;	
			}	
		}
	}
	
	function SessionStart(){
		session_start();
		//$_SESSION['loggedin']=null;	
	}
	
	function setUserid($val){
		$_SESSION['userid'] = $val;
		$_SESSION['loggedin']=true;
	}
	
	function getUserid(){
		return @$_SESSION['userid'];	
	}
	
	function getUsername(){
		if(isset($_SESSION['username'])){
			if(@$_SESSION['username']!=null || @$_SESSION['username']!='') return @$_SESSION['username'];
			else return "";
		}else{
			return "";	
		}
	}
	
	function getEmail(){
		if(isset($_SESSION['email'])){
			if(@$_SESSION['email']!=null || @$_SESSION['email']!='') return @$_SESSION['email'];
			else return "";
		}else{
			return "";	
		}
	}

}

?>