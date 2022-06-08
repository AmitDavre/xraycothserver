<?

	header('Content-Type: text/html; charset=utf-8');
	ini_set('date.timezone', 'Asia/Bangkok');
	date_default_timezone_set("Asia/Bangkok");
	$err_mail = 'admin@rego.com';
	
	$protocol = 'http://';
	if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') { $protocol = 'https://'; }
	//var_dump($protocol);	exit;
	
	define('ROOT', $protocol.$_SERVER['HTTP_HOST'].'/');
	define('AROOT', $protocol.$_SERVER['HTTP_HOST'].'/admin/');
	define('DIR', $_SERVER['DOCUMENT_ROOT'].'/');

	if(isset($_COOKIE['lang']) && !isset($_SESSION['RGadmin']['lang'])) {
		$_SESSION['RGadmin']['lang'] = $_COOKIE['lang'];
	}
	if(!isset($_SESSION['RGadmin']['lang'])){$_SESSION['RGadmin']['lang'] = 'en';}
	$lang = $_SESSION['RGadmin']['lang'];

	$mainError = "";
	$prefix = '';
	if($_SERVER['SERVER_NAME'] == 'census'){
		ini_set('show_errors', 'on');
		error_reporting(E_ALL);
		ini_set('xdebug.var_display_max_depth', -1);
		ini_set('xdebug.var_display_max_children', -1);
		ini_set('xdebug.var_display_max_data', -1);
		$my_database = 'localhost';
		$my_username = 'root';
		$my_password = '';
		$demo = true;
		$prefix = 'rego_';
		//define('SERVERNAME','//localhost/newXray/');
	}elseif(strpos($_SERVER['SERVER_NAME'], 'pkfpeople') !== false){
		$my_database = 'localhost';
		$my_username = 'pkfpeople';
		$my_password = '5cee9e8a871c5ab';
		$demo = false;
		$prefix = 'pkfpeople_';
	}

	$my_dbaname = $prefix.'admin';
	$dba = new mysqli($my_database,$my_username,$my_password,$my_dbaname);
	mysqli_set_charset($dba,"utf8");
	if($dba->connect_error) {
		echo'<div style="width:100%; margin:30px;"><b>Error :</b> ('.$dba->connect_errno.') '.$dba->connect_error.'<br>Please try again later or report this error to the Xray Administrator <a href="mailto:'.$err_mail.'">'.$err_mail.'</a></p>'; exit;
	}
	
	$lng = array();
	if($res = $dba->query("SELECT * FROM rego_application_language")){
		while($row = $res->fetch_object()){
			if($lang == 'en'){
				$lng[$row->code] = $row->en;
			}else{
				$lng[$row->code] = $row->th;
			}
		}
	}
	if($lang == 'en'){
		$lng['REGO Standards'] = 'PKF Standards';
	}else{
		$lng['REGO Standards'] = '*PKF Standards';
	}
	//var_dump($lng); exit;
	$brand = 'pkf';
	$www_title = 'PKF People';
	$client_prefix = 'pkf';
	$default_logo = 'images/pkf_people.png';
	$del_password = 'Supreme';

	$compinfo['th_compname'] = ''; 
	$compinfo['en_compname'] = '';
	$dateformat = 'd-m-Y';
	$from_email = 'noreply@pkfpeople.com';
	$reply_email = 'noreply@pkfpeople.com';
	$info_mail = 'info@xrayhr.com';
	$admin_mail = 'info@xrayhr.com';
	$support_email = 'support@xrayict.com';
	
	if($res = $dba->query("SELECT * FROM rego_company_settings")){
		if($row = $res->fetch_assoc()){
			$compinfo = $row;
		}
	}
	//var_dump($compinfo);