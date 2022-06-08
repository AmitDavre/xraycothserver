<?

	if(session_id()==''){session_start();}
	ob_start();
	include("../../dbconnect/db_connect.php");
	//include("../functions.php");
	//var_dump($_REQUEST); exit;
	
	$sql = "UPDATE ".$cid."_attendance SET 
		comment = '".$dbc->real_escape_string($_REQUEST['comment'])."', 
		remarks = '".$dbc->real_escape_string($_REQUEST['remarks'])."', 
		approved = '1' 
		WHERE id = '".$_REQUEST['id']."'";
	//echo $sql; exit;
		
	if($dbc->query($sql)){
		ob_clean();	echo 'success'; exit;
	}else{
		ob_clean();	echo 'Error : '.mysqli_error($dbc);
	}
	
