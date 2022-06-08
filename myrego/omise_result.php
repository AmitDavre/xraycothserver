<?php

	if(session_id()==''){session_start();}
	ob_start();
	include("../dbconnect/db_connect.php");

	require_once '../omise-php/lib/Omise.php';
	define('OMISE_PUBLIC_KEY', 'pkey_test_5ibhcak45cr121wkt2l');
	define('OMISE_SECRET_KEY', 'skey_test_5ibh4x3ig39fffijqck');
	define('OMISE_API_VERSION', '2019-05-29');

	$charge = OmiseCharge::retrieve($_SESSION['charge_id']);
	unset($_SESSION['charge_id']);
	//var_dump($charge['status']);
	if($charge['status'] == 'successful'){
		//include('ajax/payment_success.php');
	}
	//var_dump($charge['status']); //exit;
	//var_dump($_REQUEST); exit;
	header('location: https://census.xraydemo.com/myrego/index.php?mn=7&status='.$charge['status']);
	exit;
