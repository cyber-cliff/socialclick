<?php 
class Link{
	private $longurl;
	private $shortcode;
	private $mysqli;
	private $unique_visitor = false;
	private $hide_referrer = false;
	private $except_country_list = array();
	private $con_redirect_url ; //Kalau country tiada dalam senarai akan redirect Ni url
	private $urlid;
	private $total_click;
	function __construct($scode=null){
		$this->open_con();
		if($scode){
			$this->shortcode = $this->mysqli->real_escape_string($scode);
			$this->Query()==false ? print "Error DB ".$this->mysqli->errno :false;
		}
		
		#$this->isExceptCountry();
	}
	function open_con(){
		if(!isset($this->mysqli)) :
		@$this->mysqli = db::getConnection();
		if($this->mysqli->connect_error) die($this->mysqli->error);
		endif;
	}
	
	private function Query(){
		$sql = "SELECT tb1.*,tb2.* FROM
				(SELECT 
				url.id, url.long_url, url.short_code,
				url.date_created, url.userid,url.title ,count(click.urlid) as totalclick
				FROM url
				LEFT JOIN click ON url.id=click.urlid
				WHERE short_code='".$this->shortcode."' ORDER BY url.id
				)tb1,                              
				(SELECT `url_id_fk`, `only_unique_visit`, `hide_referrer`,`except_country`, `con_redirect_url` FROM `url_options`)as tb2
				WHERE tb2.url_id_fk=tb1.id";
		
		if($result = $this->mysqli->query($sql)){
			if($result->num_rows>0){
				$rows = $result->fetch_assoc();
				$this->longurl = $rows['long_url'];
				$this->urlid = $rows['id'];
				$this->total_click = $rows['totalclick'];
				
				
				//$options = unserialize($rows['options']);
				$this->unique_visitor = $rows['only_unique_visit']==1 ? true : false;
				$this->hide_referrer = $rows['hide_referrer']==1 ? true : false;
				$this->except_country_list = $rows['except_country'];
				$this->con_redirect_url = trim($rows['con_redirect_url']);
				return true;
			}
		}else{
			return false;
		}
	}
	
	function OnlyUniqueVisitor(){return $this->unique_visitor;}
	function HideReferrer(){return $this->hide_referrer;}
	function TotalClick(){return $this->total_click;}
	function Except_country_list(){return $this->except_country_list;}
	function getConRedirectUrl(){
		$url =  $this->con_redirect_url;
		if(!isset($url)) $url = URL_SITE;
		return $url;
	}
	
	function isExceptCountry(){//Filter country
		global $ip2c;
		$country_code = $ip2c->get_country_code();
		$list = explode(',',$this->Except_country_list());
		if($list==NULL || in_array($country_code,$list)){
			return true;
		}else{
			return false;
		}
	}
	
	function isReturnVisitor(){
		$query = "SELECT u.id FROM click c,url u
				 WHERE u.short_code='".$this->shortcode."' AND c.urlid=u.id AND c.ipaddr='".$_SERVER['REMOTE_ADDR']."'";
		if($res = $this->mysqli->query($query)){
			if($res->num_rows>0){ 
				$res->free();
				return true;
			}
		}else{
			echo "<font color=\"#0000FF\"> error {$this->mysqli->errno} </font>";
		}
	}
	
	function RecordClick(){
		global $ip2c;
		$time_now = time();
		if(!isset($this->urlid)) exit();
		$sql = "INSERT INTO `click`(`id`, `countrycode`, `ipaddr`, `urlid`, `timestamp`, `agent`,referrer) 
				VALUES (null,?,?,?,NOW(),?,?)";
		$user_agent = $_SERVER["HTTP_USER_AGENT"];
		$c_code =$ip2c->get_country_code();
		//$country_code = $c_code==null ? 'XX' : $c_code;
		$ip = $_SERVER['REMOTE_ADDR'];
		$referrer = $_SERVER['HTTP_REFERER']!=NULL ? $_SERVER['HTTP_REFERER'] : NULL;
		$stmt = $this->mysqli->prepare($sql);
		$stmt->bind_param('ssiss',$c_code,$ip,$this->urlid,$user_agent,$referrer);
		if($stmt->execute()){
			return true;
		}else{
			die($stmt->error);	
		}
		//$stmt->free_result();
	}
	
	public function getRandomLink($max=10){
		$linkList = array();//Return results
		$mysqli = $this->mysqli;
		$userid = (get_userid()!=null && get_userid()!='') ? get_userid() : 0;
		$sql = "SELECT usr.*,url.title FROM
				(
				SELECT (select short_code FROM url WHERE userid=users.id ORDER BY RAND() LIMIT 1) as short_code
				FROM users WHERE users.id <> ".$userid." 
				ORDER BY RAND() LIMIT 10
				)usr 
				JOIN url ON url.short_code=usr.short_code
				WHERE usr.short_code IS NOT NULL";
		/*
		$sql = "SELECT url.short_code,url.title,url.userid FROM url,`users` 
				WHERE  url.userid=users.id AND users.id <>".$userid."
				ORDER BY RAND() LIMIT $max ";
		*/
		if($res =  $mysqli->query($sql)){
			while($row=$res->fetch_assoc()){
				array_push($linkList,$row);
			}
			return $linkList;
		}
		#$mysqli->close();
	}
	
	
	function getDetails($s_code){
		
	}
	
	function __destruct(){
		#if(@$this->mysqli->ping()) $this->mysqli->close();
	}
	
	function getLongUrl(){return $this->longurl;}
	
	function getUrlID(){return $this->urlid;}
}

?>