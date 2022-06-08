<?

	if(session_id()==''){session_start(); ob_start();}
	include("../../dbconnect/db_connect.php");
	include(DIR."files/functions.php");
	include(DIR."leave/functions.php");


	$leavedate = array();
	$sql = $dbc->query("SELECT * FROM ".$cid."_monthly_shiftplans_".$_SESSION['rego']['cur_year']." WHERE emp_id = '".$_REQUEST['emp_id']."' Order by month ASC"); 
	$num_rows = $sql->num_rows;
	if($num_rows > 0){
		while($row = $sql->fetch_assoc()){ 

			$leavedate[] = $row;

		}
	}

	ob_clean();
	print_r($leavedate);

?>