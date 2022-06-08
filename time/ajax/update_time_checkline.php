<?

	if(session_id()==''){session_start();}
	ob_start();
	include("../../dbconnect/db_connect.php");
	include("../functions.php");
	//var_dump($_REQUEST); exit;

	$sql = "UPDATE ".$cid."_attendance SET 
		paid_late = '".$dbc->real_escape_string(decimalHours($_REQUEST['paid_late']))."', 
		paid_early = '".$dbc->real_escape_string(decimalHours($_REQUEST['paid_early']))."', 
		unpaid_late = '".$dbc->real_escape_string(decimalHours($_REQUEST['unpaid_late']))."', 
		unpaid_early = '".$dbc->real_escape_string(decimalHours($_REQUEST['unpaid_early']))."', 
		normal_hrs = '".$dbc->real_escape_string(decimalHours($_REQUEST['normal_hrs']))."', 
		ot1 = '".$dbc->real_escape_string(decimalHours($_REQUEST['ot1']))."', 
		ot15 = '".$dbc->real_escape_string(decimalHours($_REQUEST['ot15']))."', 
		ot2 = '".$dbc->real_escape_string(decimalHours($_REQUEST['ot2']))."', 
		ot3 = '".$dbc->real_escape_string(decimalHours($_REQUEST['ot3']))."' 
		WHERE id = '".$_REQUEST['id']."'";
	//echo $sql; exit;
		
	if($dbc->query($sql)){
		ob_clean();	echo 'success'; exit;
	}else{
		ob_clean();	echo 'Error : '.mysqli_error($dbc);
	}
	
