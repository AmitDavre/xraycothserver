<?
	if(session_id()==''){session_start(); ob_start();}
	//$cid = $_SESSION['rego']['cid'];
	include("../../dbconnect/db_connect.php");
	include(DIR.'files/functions.php');
	include(DIR.'leave/functions.php');

	//echo $_REQUEST['emp_id'];

	$leavedate = array();
	$sql = $dbc->query("SELECT date FROM ".$cid."_leaves_data WHERE emp_id = '".$_REQUEST['emp_id']."' "); 
	$num_rows = $sql->num_rows;
	if($num_rows > 0){
		while($row = $sql->fetch_assoc()){ 

			$changeformat = date('d-m-Y', strtotime($row['date']));
			$leavedate[] = $changeformat;
		}
	}

	

	if(!empty($leavedate)){

		$implodeArray = implode(',', $leavedate);
	}else{
		$implodeArray = array();
	}

	ob_clean();
	echo $implodeArray;



?>