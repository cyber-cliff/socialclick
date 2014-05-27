<?php 
class Page{
	private $listnav;
	protected $pageheader,$pagebody,$pagefooter,$Title;
	function __construct(){
		$this->listnav = array(
			'register'=>array(
				'title'=>'Register',
				'type'=>'page',
				'incfile'=>'register.php'
			),
			'login'=>array(
				'title'=>'Login',
				'type'=>'page',
				'incfile'=>'login.php'
			),
			'loginx'=>array(
				'title'=>'Login',
				'type'=>'single',
				'incfile'=>'login.php'
			),
			'home_page'=>array(
				'title'=>'Dashboard',
				'type'=>'page',
				'incfile'=>'mylink.php'
			),
			'mylink'=>array(
				'title'=>'Dashboard',
				'type'=>'page',
				'incfile'=>'mylink.php'
			),
			'404'=>array(
				'title'=>'Page Not Found',
				'type'=>'page',
				'incfile'=>'404.php'
			),
			'login_now'=>array(
				'title'=>'Page Not Found',
				'type'=>'single',
				'incfile'=>'sign-in-form.html'
			),
			'details'=>array(
				'title'=>'Details',
				'type'=>'page',
				'incfile'=>'template_details.php'
			),
			'profile'=>array(
				'title'=>'Profile',
				'type'=>'page',
				'incfile'=>'profile.php'
			)
		);
		$this->Title = 'My List Link';
	}
	
	function PageTitle(){
		return $this->Title;
	}
	
	function setTitle($var){$this->Title = $var;}
	
	protected function getNav(){
		return $this->listnav;	
	}
	
	function PageParameter($page=null){
		if($page==null)$p = @$_GET['p'];
		else $p = $page;
		if(isset($p) && @$p!=''){
			if(in_array_page($p,$this->listnav)) return $p;	
			else return '404';
		}else{
			return 'home_page';
		}
	}
	
	protected function PageSwitch(){
		
	}
	
	public function redirectRoot(){ //Redirect to main page
		$stripurl = explode("/",$_SERVER["PHP_SELF"]);
		$redirect = str_replace($stripurl[count($stripurl)-1],"",$_SERVER["PHP_SELF"]);	
		return $redirect;
	}// end redirect function
}


class staticPage extends page{
	private $Inc;
	private $path_tmplt ='templates/';
	private $pagetype;
	public $babi = "babi utan";
	function __construct(){
		parent::__construct();
		$this->NavHandler();
	}
	
	function home(){
		include($this->path_tmplt.'header.php');
		include($this->path_tmplt.'content.php');
		include($this->path_tmplt.'footer.php');
	}
	
	function NavHandler($page=null){
		$current = parent::getNav();
		if($page==null) $current=$current[parent::PageParameter()];
		else $current=$current[parent::PageParameter($page)];
		$this->setTitle($current['title']);
		$this->Inc = $current['incfile'];
		$this->pagetype = $current['type'];
	}
	
	function Load(){
		if($this->pagetype=="page"){
			include($this->path_tmplt.'header.php');
			include($this->path_tmplt.'content.php');
			include($this->path_tmplt.'footer.php');
		}elseif($this->pagetype=="single"){
			include($this->path_tmplt.$this->Inc);
		}
	}
	
	function ContentInc(){
		if(file_exists($this->path_tmplt.$this->Inc)){
			include($this->path_tmplt.$this->Inc);
		}else{
			include($this->path_tmplt.'404.php');
		}
		
	}
	
	function js_script(){
		$all_page_js_src = array(
						"/js/jquery-2.0.3.min.js",
						"/js/src/jquery.fancybox.pack.js?v=2.1.5",
						"/js/fc.js",
						"http://code.jquery.com/ui/1.10.3/jquery-ui.js",
						"/js/main.js",
						"//www.google.com/jsapi",
						"/js/jquery-ui-1.10.4.custom.min.js"
					);
		$main_js = '<script src="/js/jquery-2.0.3.min.js" type="text/javascript"></script>
					<script type="text/javascript" src="/js/src/jquery.fancybox.pack.js?v=2.1.5"></script>
					<script src="/js/fc.js" type="text/javascript"></script>
					<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
					<script src="/js/main.js" type="text/javascript"></script>
					';
		$detail_page_js_src = '
						<link rel="stylesheet" href="/css/chosen.css" type="text/css">
						<script src="/js/jquery-ui-1.10.4.custom.min.js" type="text/javascript"></script>
						<script type="text/javascript" src="//www.google.com/jsapi"></script>
						<script src="/js/chosen.jquery.min.js" type="text/javascript"</script>
						<script src="/js/chosen.proto.min.js" type="text/javascript"</script>
						<script type="text/javascript">google.load(\'visualization\', \'1\', {packages: [\'corechart\']});</script>';
		if($this->PageTitle()=='Details'){
			print $main_js.$detail_page_js_src;
		}else{
			print $main_js;	
		}		
		
	}
}



?>