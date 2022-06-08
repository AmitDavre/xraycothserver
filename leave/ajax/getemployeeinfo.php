<?
	if(session_id()==''){session_start(); ob_start();}
	//$cid = $_SESSION['rego']['cid'];
	include("../../dbconnect/db_connect.php");
	include(DIR.'files/functions.php');
	include(DIR.'leave/functions.php');

	//echo $_REQUEST['emp_id'];

	$leavedate = array();
	$sql = $dbc->query("SELECT en_name FROM ".$cid."_employees WHERE emp_id = '".$_REQUEST['id']."' "); 
	$num_rows = $sql->num_rows;
	if($num_rows > 0){
		while($row = $sql->fetch_assoc()){ 

			$fullname = $row['en_name'];
		}
	}

	


	ob_clean();
	echo $fullname;



?>