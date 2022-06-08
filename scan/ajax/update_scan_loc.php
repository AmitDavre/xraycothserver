<?php

	if(session_id()==''){session_start();}
	ob_start();
	include('../db_connect.php');
	include(DIR.'files/functions.php');
	//var_dump($_REQUEST); exit;

	$selcomp =  $_REQUEST['selcomppp'];
	$selbrancch =  $_REQUEST['selbrancch'];
	$selectoption =  $_REQUEST['selectoption'];
	$locname =  $_REQUEST['locname'];
	$latvalue =  $_REQUEST['latvalue'];
	$longvalue =  $_REQUEST['longvalue'];
	$perivalue =  $_REQUEST['perivalue'];


	$array = array (

		'name' =>$locname,
		'code' =>'',
		'qr' =>'',
		'latitude' =>$latvalue,
		'longitude' =>$longvalue,
		'perimeter' =>$perivalue,

	);

	$location = json_encode($array);

	if($selectoption == '1')
	{
		$locfield = 'loc1';
	}	
	else if($selectoption == '2')
	{
		$locfield = 'loc2';
	}	
	else if($selectoption == '3')
	{
		$locfield = 'loc3';
	}	
	else if($selectoption == '4')
	{
		$locfield = 'loc4';
	}	
	else if($selectoption == '5')
	{
		$locfield = 'loc5';
	}



	$my_dbcname = $prefix.$selcomp;
	$dbc = new mysqli($my_database,$my_username,$my_password,$my_dbcname);
	if($dbc->connect_error) {
		echo '<p style="width:900px; margin:0 auto; margin-top:20px;" class="box_err">Error: ('.$dbc->connect_errno.') '.$dbc->connect_error.'<br>Please try again later or report this error to <a href="mailto:admin@regohr.com">admin@regohr.com</a></p>';
	}else{
		mysqli_set_charset($dbc,"utf8");
	}

	$resbb = $dbc->query("UPDATE ".$selcomp."_branches_data SET ".$locfield." = '".$location."' WHERE ref = '".$selbrancch."'") ;


	echo '1';


	





