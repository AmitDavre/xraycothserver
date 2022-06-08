<?php
	if(session_id()==''){session_start();}
	ob_start();
	include('../../dbconnect/db_connect.php');
	

	// $searlize_array = searlize($_REQUEST);

	$newarray = array(

		'date' => $_REQUEST['date'],
		'month' => $_REQUEST['month'],
		'year' => $_REQUEST['year'],
	);



	$filename = $_SERVER["DOCUMENT_ROOT"].'/hr/'.$cid.'/uploads/gov_form_array.txt';
	file_put_contents($filename, print_r(serialize($newarray), true));

	
	
?>