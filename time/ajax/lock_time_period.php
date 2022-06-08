<?
	if(session_id()==''){session_start();}
	ob_start();
	include('../../dbconnect/db_connect.php');
	//include(DIR.'files/functions.php');
	//var_dump($_REQUEST); //exit;
	
	$sdate = date('Y-m-d', strtotime($_REQUEST['sdate']));
	$edate = date('Y-m-d', strtotime($_REQUEST['edate']));
	
	$sql = "UPDATE ".$cid."_attendance SET locked = 1 WHERE date >= '".$sdate."' AND date <= '".$edate."'";
	//echo $sql;

	ob_clean();
	if($dbc->query($sql)){
		echo 'success';
	}else{
		echo mysqli_error($dbc);
	}
	exit;
	
?>
















