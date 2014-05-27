<?php 
#error_reporting(0);
class Shorturl{
	private $db;
	private $table = 'url';
	private $scode;
	function __construct(){
		//$this->InsertShortUrl('harrysd.tk');
		//$this->loadList('14');
		$this->db_con();
	}
	
	function __destruct(){
		#if(isset($this->db))
		#$this->db->close();
	}
	
	
	private function db_con(){
		if(!isset($this->db)){
		$this->db = db::getConnection();
		}
		if($this->db->connect_error){
			//header('Location: '.SITE_URL);
		}
	}
	
	
	public function CreateShortUrl($url){
		if(@$url!=''){
			if($this->InsertShortUrl($url)===true){
				return true;
			}else{
				return false;	
			}
		}else{
			
		}
		return false;
	}
	
	private function InsertShortUrl($url){
		$this->db_con();
		$sql_url = "INSERT INTO $this->table (`id`, `long_url`, `short_code`, `date_created`, `userid`, `title`, options) 
				VALUES (null,?,?,now(),?,?,?)";
		#$mysqli =new mysqli();
		$mysqli = $this->db;
		if(!$this->hasHTTP($url)){
			$url = 'http://'.$url;	
		}
		$options_url = array("ouv"=>false,"hf"=>false);
		$options_url = serialize($options_url);
		$longurl = $mysqli->real_escape_string($url);
		$shortcode = $this->genShortCode();
		$title=$this->getTitle($url);
		$usrid = getUserID();
		
		$stmt = $mysqli->prepare($sql_url);
		$stmt->bind_param('ssiss',$longurl,$shortcode,$usrid,$title,$options_url);
		
		if($stmt->execute()){
			$this->scode = $shortcode;
			$sql_url_options = "INSERT INTO 
						`url_options`(`id`, `url_id_fk`, `only_unique_visit`, `hide_referrer`, `except_country`, `con_redirect_url`)
						 VALUES (NULL,".$stmt->insert_id.", 0, 0,NULL,NULL)";
			$mysqli->query($sql_url_options);
			return true;	
		}else{
			if($stmt->errno==1062){
				if(strrpos($stmt->error,'short_code')){
					return $this->InsertShortUrl($url);
				}
			}	
		}
	}
	
	function genShortCode($numAlpha=6){
	   $listAlpha = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
	   return str_shuffle(substr(str_shuffle($listAlpha),0,$numAlpha));
	}
	
	function getScode(){return $this->scode;}
	
	function getTitle($Url){
		if($this->hasHTTP($Url)==false){
			$Url = 'http://'.$Url;	
		}
		$str = $this->file_get_contents_curl($Url);
		if(strlen(@$str)>0){
			preg_match("/\<title\>(.*)\<\/title\>/",@$str,$title);
			if(strlen(@$title[1])===0) return $Url;
			else return $title[1];
		}else{
			return $Url;
		}
	}
	
	function file_get_contents_curl($url)
	{
		$ch = curl_init();
		$user_agent = "facebookexternalhit/1.1 (+http://www.facebook.com/externalhit_uatext.php)";
		
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_USERAGENT, $user_agent);
		curl_setopt($ch,CURLOPT_REFERER, 'https://www.facebook.com/l.php');
		#curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
	
		$data = curl_exec($ch);
		curl_close($ch);
	
		return $data;
	}
	
	static function hasHTTP($Url){
		if(strpos($Url,'http')===0){
			return true;
		}else{
			return false;
		}
	}	
	
	function urlStatus($url){
		 	$ch = @curl_init();
            @curl_setopt($ch, CURLOPT_URL,$url);
            @curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.1) Gecko/20061204 Firefox/2.0.0.1");
            @curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            @curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
            @curl_setopt($ch, CURLOPT_TIMEOUT, 10);

            $response       = @curl_exec($ch);
            $errno          = @curl_errno($ch);
            $error          = @curl_error($ch);

                    $response = $response;
                    $info = @curl_getinfo($ch);
			return $info['http_code'];	
	}
	
	function ValidateUrl($url){
		if($this->hasHTTP($url)==false){
			$url = 'http://'.$url;	
		}
		if(filter_var($url,FILTER_VALIDATE_URL)){
			return true;	
		}else{
			return false;	
		}
	}	
	
	public function loadList($userid,$start=0,$perpage=10){
		$this->db_con();
		$mysqli = $this->db;
				
		$userid = $mysqli->real_escape_string($userid);
		$start = $mysqli->real_escape_string($start);
		//$sql = "SELECT id,long_url,short_code,date_created,title FROM url WHERE userid=? ORDER BY date_created DESC";
		$sql =  "SELECT url.id,url.long_url,url.short_code,url.date_created,url.title,count(click.urlid) as click FROM url 
				LEFT JOIN click
				ON url.id=click.urlid
				WHERE url.userid=?
				GROUP BY url.id ORDER BY date_created DESC LIMIT $start,$perpage";

		$stmt = $mysqli->prepare($sql);
		$stmt->bind_param('i',$userid);
		if(!$stmt->execute()){
			#$stmt->close();
			die($stmt->error);	
		}else{
			$stmt->bind_result($id,$long_url,$short_code,$date_created,$title,$click);
			$stmt->store_result();
			if($stmt->num_rows>0){
				while($stmt->fetch()){
					@$returnVal[] = array($id,$long_url,$short_code,$date_created,$title,$click);
				}
			}else{
				@$returnVal = null;
			}
		}
		#$stmt->free_result();
		#$stmt->close();
		return $returnVal;
	}
	
	function update_opt($scode,$opt){
		if(get_userid()==null || get_userid()==''){return false;exit;}
		$this->db_con();
		$mysqli = $this->db;
		//$opt = serialize($opt);
		$only_unique_visit = (isset($opt['ouv'])) ? 1 : 0;
		$hide_referrer = (isset($opt['hf'])) ? 1 : 0;
		
		$filter_country = implode(',',$opt['filter_country']);
		$filter_country = !isset($filter_country) ? "NULL" : "'".$filter_country."'";
		$condition_url = $opt['country_not_on_list'];
		
		$userid = get_userid();
		$sql = "UPDATE `url_options` 
				SET 
					`only_unique_visit`='".$only_unique_visit."',
					`hide_referrer`='".$hide_referrer."',
					`except_country`=".$filter_country.",
					`con_redirect_url`='".$condition_url."'
				WHERE  url_id_fk=(SELECT id FROM url WHERE short_code='$scode' AND url.userid='$userid') ";
		if($mysqli->query($sql)){
			return true;
		}
	}
	
	function totalLink($userid){
		$this->db_con();
		$sql = "SELECT count(id) as total FROM url WHERE userid=$userid";
		if($res=@$this->db->query($sql)){
			$r =$res->fetch_row();
			return $r[0];
		}else{
			return false;
		}
	}
}

?>