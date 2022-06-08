<?php
	if(session_id()==''){session_start();}
	ob_start();
	//$cid = $_SESSION['xhr']['cid'];
	include('../dbconnect/db_connect.php');
	//include(DIR.'files/functions.php');
	
	$sql_details = array(
		'user' => $my_username,
		'pass' => $my_password,
		'db'   => $my_dbaname,
		'host' => $my_database
	);

	$root = ROOT;
	$from = date('Y-m-d', strtotime($_REQUEST['from']));
	$until = date('Y-m-d', strtotime($_REQUEST['until']));
	
	$where = '';
	$typeFilter = $_REQUEST['typeFilter'];


	if($typeFilter == 'showall')
	{
		$filterAll = '1';
	}
	else if($typeFilter == 'showcurrent')
	{
		$filterAll = '2';
	}
	else if($typeFilter == 'showold')
	{
		$filterAll = '3';
	}
	else
	{
		$filterAll = '4';
	}


	$table = 'rego_consent_log';
	$primaryKey = 'id';

	$nr=0;
	
	// $columns[] = array( 'db' => 'name',  'dt' => $nr ); $nr++;
	$columns[] = array( 'db' => 'id',  'dt' => $nr ,'formatter' => function($d, $row ){

		$spanVariable = '<span class="consent_log_'.$d.'" id="consent_log_'.$d.'"> '.$d.'</span>';
		return $spanVariable;
	}); $nr++;	


	$columns[] = array( 'db' => 'user_name',  'dt' => $nr ,'formatter' => function($d, $row ){

		$spanVariable = '<span class="consent_log" > '.$d.'</span>';
		return $spanVariable;
	}); $nr++;	

	$columns[] = array( 'db' => 'consent_name',  'dt' => $nr ,'formatter' => function($d, $row ){

		$spanVariable = '<span class="consent_log" > '.$d.'</span>';
		return $spanVariable;
	}); $nr++;	

	$columns[] = array( 'db' => 'consent_date',  'dt' => $nr ,'formatter' => function($d, $row ){

		$spanVariable = '<span class="consent_log" > '.$d.'</span>';
		return $spanVariable;
	}); $nr++;	


	if($filterAll == '3')
	{
		$columns[] = array( 'db' => 'consent_status',     'dt' => $nr ,'formatter' => function($d, $row ){

		if($d == '1')
		{
			// $return = '<button  style="font-size:13px;float: right;background: #080;border-color: #080;color: #fff;margin-right: 5px;" class="btn btn-lg " id="" type="button">Agreed</button>';
			$return = '<p class="opacityclass btn btn-sm" style="width:50%;background: #080;border-color: #080;color: #fff;padding: 8px;text-align: center;font-weight: 600;margin: 0, auto;margin: 0 auto;display:block;opacity:0.65;">Agreed</p>';
		}
		else if($d == '0')
		{
			// $return = '<button  style="font-size:13px;float: right;background: #c00;border-color: #c00;color: #fff;margin-right: 5px;" class="btn  " id="" type="button">Not Agreed</button>';
			$return ='<p class="opacityclass btn btn-sm" style="width:50%;background: #c00;border-color: #c00;color: #fff;padding: 8px;text-align: center;font-weight: 600;margin: 0, auto;margin: 0 auto;display:block;opacity:0.65;">Not Agreed</p>';
		}

		 return $return;

		}); $nr++;


	}
	else
	{
		$columns[] = array( 'db' => 'consent_status',     'dt' => $nr ,'formatter' => function($d, $row ){

		if($d == '1')
		{
			// $return = '<button  style="font-size:13px;float: right;background: #080;border-color: #080;color: #fff;margin-right: 5px;" class="btn btn-lg " id="" type="button">Agreed</button>';
			$return = '<p class=" btn btn-sm" style="width:50%;background: #080;border-color: #080;color: #fff;padding: 8px;text-align: center;font-weight: 600;margin: 0, auto;margin: 0 auto;display:block;">Agreed</p>';
		}
		else if($d == '0')
		{
			// $return = '<button  style="font-size:13px;float: right;background: #c00;border-color: #c00;color: #fff;margin-right: 5px;" class="btn  " id="" type="button">Not Agreed</button>';
			$return ='<p class=" btn btn-sm" style="width:50%;background: #c00;border-color: #c00;color: #fff;padding: 8px;text-align: center;font-weight: 600;margin: 0, auto;margin: 0 auto;display:block;">Not Agreed</p>';
		}

		 return $return;

		}); $nr++;

	}



	require(DIR.'ajax/ssp.class.php' );
	
	ob_clean();
	echo json_encode(
		SSP::consentsimple($_POST, $sql_details, $table, $primaryKey, $columns, $where,$filterAll)
	);

?>