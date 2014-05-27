<?php 

class StatsUrl{
	private $mysqli;
	private $shortcode;
	private $uaparse;
	private $cbua;//click by user agent
	function __construct($scode=NULL){
		require_once('useragent/UASparser.php');
		$this->mysqli = db::getConnection();
		if($this->mysqli->connect_error) die("Database error ,cant connect");
		$this->shortcode = $scode;
		$this->uaparse = new UAS\Parser(__DIR__.'/useragent/uascache/',null,false,true);
		
		$this->cbua=$this->ClickByUserAgent();
	}
	
	
	
	function ClickByCountry($shortcode=NULL){// Get Click by country ,Query cells country name,country code, Totals click
		$returnVal = array();
		
		if($shortcode==null) $shortcode = $this->shortcode;
		
		$sql = "SELECT country.name,click.ccode,click.totals,click.agent FROM 
				(
				SELECT countrycode as ccode,count(id) as totals,agent 
				FROM click 
				WHERE urlid=(SELECT id FROM url WHERE short_code='".$shortcode."')
				GROUP BY countrycode
				) as click
				LEFT JOIN  country
				ON click.ccode=country.code";		
		
		if($result = $this->mysqli->query($sql)){
			while($row = $result->fetch_row()){
				if($row[0]!=null){
					array_push($returnVal,$row);
				}else{
					array_push($returnVal,array('Unknown Country','XX',$row[2],$row[3]));
				}
			}	
		}
		#$result->close();
		return $returnVal;
	}
	
	function ClickByUserAgent($shortcode=NULL)
	{
		if($shortcode==NULL) $shortcode = $this->shortcode;
		$returnVal = array();
		$sql = "SELECT agent as useragent,count(agent) as totals  FROM click
				INNER JOIN url ON click.urlid=url.id
				WHERE url.short_code='".$shortcode."'
				GROUP BY click.agent";
		if($result = $this->mysqli->query($sql)){
			while($row = $result->fetch_assoc()){
				$ua = $this->uaparse->parse($row['useragent']);
				array_push($returnVal,
					array_merge(
						$row,
						array(
							'os_family'=>$ua['os_family'],
							'ua_family'=>$ua['ua_family'],
							'ua_icon'=>$ua['ua_icon']
						)
					)
				);
			}	
		}else{die("Error db ".$this->mysqli->connect_error);}
		
		return $returnVal;
	}
	
	function ClickByOs(){
		$cbo = array();
		$array = $this->cbua;
		foreach($array as $data) {
			if(!array_key_exists($data['os_family'], $cbo)){
				$cbo[ $data['os_family'] ] = 0;
			}
			$cbo[ $data['os_family'] ] += $data['totals'];
		}
		arsort($cbo);
		return $cbo;
	}
	
	function __destruct(){
		#if(isset($this->mysqli)) $this->mysqli->close();
	}
	
	function getCountryList(){
		$mysqli = $this->mysqli;
		//$returnVal[] = array("asd","asd");
		$result = $mysqli->query("SELECT code,name FROM `country`");
		if($result){
			while($row = $result->fetch_row()){
				$returnVal[] = $row;
			}	
		}else{
			print $mysqli->connect_error;	
		}
		return $returnVal;	
	}
		
}


/*
Contoh
Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/31.0.1650.63 Safari/537.36
Array
(
    [typ] => Browser
    [ua_family] => Chrome
    [ua_name] => Chrome 31.0.1650.63
    [ua_version] => 31.0.1650.63
    [ua_url] => http://www.google.com/chrome
    [ua_company] => Google Inc.
    [ua_company_url] => http://www.google.com/
    [ua_icon] => chrome.png
    [ua_info_url] => http://user-agent-string.info/list-of-ua/browser-detail?browser=Chrome
    [os_family] => Windows
    [os_name] => Windows 7
    [os_url] => http://en.wikipedia.org/wiki/Windows_7
    [os_company] => Microsoft Corporation.
    [os_company_url] => http://www.microsoft.com/
    [os_icon] => windows-7.png
    [device_type] => Personal computer
    [device_icon] => desktop.png
    [device_info_url] => http://user-agent-string.info/list-of-ua/device-detail?device=Personal computer
)

*/
?>