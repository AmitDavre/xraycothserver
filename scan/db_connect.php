<?php

	header('Content-Type: text/html; charset=utf-8');
	ini_set('date.timezone', 'Asia/Bangkok');
	date_default_timezone_set("Asia/Bangkok");
	//unset($_SESSION['scan']['cid']);

	$protocol = 'http://';
	if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') { $protocol = 'https://';}
	
	define('ROOT', $protocol.$_SERVER['HTTP_HOST'].'/');
	define('DIR', $_SERVER['DOCUMENT_ROOT'].'/');

	if(isset($_COOKIE['scanlang'])) {
		$lang = $_COOKIE['scanlang'];
		$_SESSION['scan']['lang'] = $lang;
	}else{
		$lang = 'th';
		$_SESSION['scan']['lang'] = $lang;
	}
	
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
		$prefix = 'rego_';
	}elseif(strpos($_SERVER['SERVER_NAME'], 'xray.co.th') !== false){
		$my_database = 'localhost';
		$my_username = 'xraycoth_rego';
		$my_password = 'uL4!v*H1Ka6No5';
		$prefix = 'xraycoth_';
	}elseif(strpos($_SERVER['SERVER_NAME'], 'regohr') !== false){
		$my_database = 'localhost';
		$my_username = 'regohrco';
		$my_password = 'uL4!v*H1Ka6No5';
		$demo = false;
		$prefix = 'regohrco_';
	}elseif(strpos($_SERVER['SERVER_NAME'], 'xraydemo') !== false){
		$my_database = 'localhost';
		$my_username = 'xraydemo_wms';
		$my_password = 'Tinkerbell11';
		$demo = false;
		$prefix = 'xraydemo_';
	}
	elseif($_SERVER['SERVER_NAME'] == 'regothailand.com'){
		$my_database = 'localhost';
		$my_username = 'regothai';
		$my_password = 'RvGpbeFGVDzyY1aQ';
		$demo = false;
		$prefix = 'regothai_';
	
	}

	$my_database = 'localhost';
	$my_username = 'xraycoth_rego';
	$my_password = 'uL4!v*H1Ka6No5';
	$demo = false;
	$prefix = 'xraycoth_';

	$my_dbxname = $prefix.'admin';
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
	//var_dump($_SESSION['scan']); exit;
	
	//$cid = 'rego01000';
	if(isset($_SESSION['scan']['cid'])){
		$cid = $_SESSION['scan']['cid'];
		$my_dbcname = $prefix.$cid;
		$dbc = new mysqli($my_database,$my_username,$my_password,$my_dbcname);
		if($dbc->connect_error) {
			echo '<p style="width:900px; margin:0 auto; margin-top:20px;" class="box_err">Error: ('.$dbc->connect_errno.') '.$dbc->connect_error.'<br>Please try again later or report this error to <a href="mailto:admin@regohr.com">xxxadmin@regohr.com</a></p>';
		}else{
			mysqli_set_charset($dbc,"utf8");
		}
	}
	
	
?>















