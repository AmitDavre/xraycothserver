<?php

	header('Content-Type: text/html; charset=utf-8');
	ini_set('date.timezone', 'Asia/Bangkok');
	date_default_timezone_set("Asia/Bangkok");
	$err_mail = 'info@regohr.com';
	//$lang = 'en';
	
	if(session_id()==''){session_start();} 
	if(!isset($_GET['v'])){$_GET['v'] = 10;}
	if(!isset($_GET['mn'])){$_GET['mn'] = 1;}
	if(!isset($_SESSION['lang'])){$_SESSION['lang'] = 'th';}
	//$_SESSION['lang'] = 'en';
	$lang = $_SESSION['lang'];

	$protocol = 'http://';
	if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') { $protocol = 'https://';}
	/*echo '<pre>';
	var_dump($_SERVER['SERVER_NAME']);	
	var_dump($protocol);	
	echo '</pre>';*/
	//exit;
	
	$mainError = "";
	$prefix = '';
	if($_SERVER['SERVER_NAME'] == 'regothailand'){
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
	}elseif(strpos($_SERVER['SERVER_NAME'], 'xraydemo') !== false){
		$my_database = 'localhost';
		$my_username = 'xraydemo_wms';
		$my_password = 'Tinkerbell11';
		$demo = false;
		$prefix = 'xraydemo_';
	}elseif($_SERVER['SERVER_NAME'] == 'regothailand.com'){
		$my_database = 'localhost';
		$my_username = 'regothai';
		$my_password = 'tmGgpuCGHbjkUAek';
		$demo = false;
		$prefix = 'regothai_';
	}
	//var_dump($_SERVER['SERVER_NAME']);
	
	$my_dbxname = $prefix.'regoadmin';
	$dbx = @new mysqli($my_database,$my_username,$my_password,$my_dbxname);
	if($dbx->connect_error) {
		echo'<div style="width:100%; margin:30px;"><b>Error :</b> ('.$dbx->connect_errno.') '.$dbx->connect_error.'<br>Please try again later or report this error to the Xray Administrator <a href="mailto:'.$err_mail.'">'.$err_mail.'</a></p>'; exit;
	}else{
		mysqli_set_charset($dbx,"utf8");
	}
	
	$lng = array();
	if($res = $dbx->query("SELECT * FROM rego_application_language")){
		while($row = $res->fetch_object()){
			if($_SESSION['lang'] == 'en'){
				$lng[$row->code] = $row->en;
			}else{
				$lng[$row->code] = $row->th;
			}
		}
	}
	//var_dump($lng); exit;
	
	
?>















