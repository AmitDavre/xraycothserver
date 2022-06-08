<?php
	// CUSTOMERS ///////////////////////////////////////////////////////////////////
	header('Content-Type: text/html; charset=utf-8');
	ini_set('date.timezone', 'Asia/Bangkok');
	date_default_timezone_set("Asia/Bangkok");
	$err_mail = 'admin@regohr.com';

	$protocol = 'http://';
	if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') { $protocol = 'https://';}
	//var_dump($protocol);	exit;
	
	define('ROOT', $protocol.$_SERVER['HTTP_HOST'].'/');
	define('DIR', $_SERVER['DOCUMENT_ROOT'].'/');

	if(isset($_COOKIE['agent_lang']) && !isset($_SESSION['agent']['lang'])) {
		$_SESSION['agent']['lang'] = $_COOKIE['agent_lang'];
	}
	if(!isset($_SESSION['agent']['lang'])){$_SESSION['agent']['lang'] = 'th';}
	$lang = $_SESSION['agent']['lang'];
	
	$mainError = "";
	$prefix = '';
	if($_SERVER['SERVER_NAME'] == 'supreme'){
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
	}elseif(strpos($_SERVER['SERVER_NAME'], 'xray.co.th') !== false){
		$my_database = 'localhost';
		$my_username = 'xraycoth_rego';
		$my_password = 'uL4!v*H1Ka6No5';
		$demo = false;
		$prefix = 'xraycoth_';
	}

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
			if($lang == 'en'){
				$lng[$row->code] = $row->en;
			}else{
				$lng[$row->code] = $row->th;
			}
		}
	}
	$agents_mail = 'info@regohr.com';
	if($res = $dbx->query("SELECT agents_mail FROM rego_company_settings")){
		if($row = $res->fetch_assoc()){
			$agents_mail = $row['agents_mail'];
		}
	}
	//var_dump($lng); exit;
	
	
?>















