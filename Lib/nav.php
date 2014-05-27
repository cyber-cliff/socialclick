<?php 
class nav 
{
	private $listnav;
	#private $process;
	private $page;
	function __construct(){
		$this->listnav = array(
			'register'=>array(
				'include'=>'../Lib/register.php',
				'type'=>'../Lib/static'
			),
			'login'=>array(
				'include'=>'../Lib/login.php',
				'type'=>'static'
			),
			'content'=>array(
				'include'=>'../Lib/content.php',
				'type'../Lib/=>'static'
			),
			'login_now'=>array(
				'include'../Lib/=>'login.php',
				'type'../Lib/=>'static'
			),
			'signin'=>array(
				'include'../Lib/=>'sign-in-form.html',
				'type'../Lib/=>'single'
			),
			'login_process'=>array(
				'include'../Lib/=>'login_process.php',
				'type'=>'single'
			)
		);
		/*
		$this->process = array(
			'login'=>array('type'=>'process'),
			'logout'=>'logout.php'
		);
		*/						
		
	}
	
	function inc(){
		if(isset($_GET['p'])){
			$this->page=$_GET['p'];	
		}else{
			$this->page ='content';	
		}
		return new IncPage($this->listnav[$this->page]);	
	}		
	
	public function redirectRoot(){ //Redirect to main page
		$stripurl = explode("/",$_SERVER["PHP_SELF"]);
		$redirect = str_replace($stripurl[count($stripurl)-1],"",$_SERVER["PHP_SELF"]);	
		return $redirect;
	}// end redirect function
}

class IncPage{
	private $nav;
	function __construct($nav){
		$this->nav = $nav;
	}
	public function Type(){
		return $this->nav['type'];
	}
    public function IncFile(){
		return $this->nav['include'];
	}
}

?>