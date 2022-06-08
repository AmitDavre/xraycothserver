<?
	if(session_id()==''){session_start();}
	ob_start();
	include('../../dbconnect/db_connect.php');
	$dir = ROOT.$cid.'/uploads/';
	
	$sql_details = array(
		'user' => $my_username,
		'pass' => $my_password,
		'db'   => $my_dbcname,
		'host' => $my_database
	);
	$table = $cid."_metascandata";

	// echo $table;
	// die();


	$primaryKey = 'id';
	$date_start = date('Y-m-d', strtotime($_REQUEST['sdate']));
	$date_end = date('Y-m-d', strtotime($_REQUEST['edate']));
	
	$where = "";
	if($_REQUEST['valid'] != ''){
		$where .= "status = '".$_REQUEST['valid']."'";
	}
	if(!empty($_REQUEST['period'])){
		if($where != ''){$where .= ' AND ';}
		$where .= "period = '".$_REQUEST['period']."'";
	}	
	if(!empty($_REQUEST['emp_id'])){
		if($where != ''){$where .= ' AND ';}
		$where .= "emp_id = '".$_REQUEST['emp_id']."'";
	}

	if($date_start!='1970-01-01' && $date_end!='1970-01-01')
	{
		if($where != ''){$where .= ' AND ';}
		$where .= " (datescan BETWEEN '".$date_start."' AND '".$date_end."')";
	}

	$nr=0;
	
	$columns[] = array( 'db' => 'emp_id', 'dt' => $nr); $nr++;
	
	$columns[] = array( 'db' => 'emp_name', 'dt' => $nr); $nr++;
	
	$columns[] = array( 'db' => 'datescan', 'dt' => $nr, 'formatter' => function($d, $row ){

		if($d == '1970-01-01')
		{
			$datescan = '';
		}
		else
		{
			$datescan = date('d-m-Y',strtotime($d));
		}

		return $datescan;
	}); $nr++;
	



	$columns[] = array( 'db' => 'timescan', 'dt' => $nr); $nr++;
	$columns[] = array( 'db' => 'shift_plan_value', 'dt' => $nr); $nr++;
	$columns[] = array( 'db' => 'in_or_out', 'dt' => $nr, 'formatter' => function($d, $row ){

		return '<span id="inORout">'.$d.'</span>';
	}); $nr++;


	$columns[] = array( 'db' => 'linkedPlan', 'dt' => $nr, 'formatter' => function($d, $row ){
			if($d != '')
			{
				$statusVal = 'Linked';
			}
			else
			{
				$statusVal = 'Unlinked';
			}

		return $statusVal;
	}); $nr++;


	$columns[] = array( 'db' => 'picture', 'dt' => $nr, 'formatter' => function($d, $row ){

		if($d)
		{
			$pictureval = '<img style="height:100px;width:125px;" src="'.ROOT.''.$_REQUEST['cidd'].'/time/selfies/'.$d.'">';
		}
		else
		{
			$pictureval = '<img style="height:30px;width:30px;" src="'.ROOT.'assets/images/dummy_profile_image.png">';
		}


		return $pictureval;
	}); $nr++;



	
	$columns[] = array( 'db' => 'picture', 'dt' => $nr); $nr++;
	

	require(DIR.'ajax/ssp.class.php' );
	
	// var_dump(SSP::simple( $_POST, $sql_details, $table, $primaryKey, $columns, $where)); exit;
	
	// echo $where;

	// die();


	
	ob_clean();
	echo json_encode(
		SSP::scansimple( $_POST, $sql_details, $table, $primaryKey, $columns, $where)
	);



?>