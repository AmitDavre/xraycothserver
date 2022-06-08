<?
	
	if(session_id()==''){session_start();}
	ob_start();
	include("../../dbconnect/db_connect.php");
	
	//var_dump($_REQUEST); exit;
	$id = $_REQUEST['id'];
	$entities = $_REQUEST['entities'];
	$divisions = $_REQUEST['divisions'];
	$branches = $_REQUEST['branches'];
	$departments = $_REQUEST['departments'];
	$teams = $_REQUEST['teams'];
	$access = $_REQUEST['access'];
	$access_selection = $_REQUEST['access_selection'];
	$emp_group = $_REQUEST['emp_group'];
	unset($_REQUEST['id'], $_REQUEST['entities'], $_REQUEST['access'], $_REQUEST['branches'], $_REQUEST['divisions'], $_REQUEST['departments'], $_REQUEST['teams'], $_REQUEST['access_selection'], $_REQUEST['emp_group']);
	

	$sql = "UPDATE ".$cid."_users SET 
		permissions = '".$dbc->real_escape_string(serialize($_REQUEST))."', 
		access = '".$dbc->real_escape_string($access)."', 
		entities = '".$dbc->real_escape_string($entities)."', 
		divisions = '".$dbc->real_escape_string($divisions)."', 
		branches = '".$dbc->real_escape_string($branches)."', 
		departments = '".$dbc->real_escape_string($departments)."', 
		teams = '".$dbc->real_escape_string($teams)."', 
		emp_group = '".$dbc->real_escape_string($emp_group)."', 
		access_selection = '".$dbc->real_escape_string($access_selection)."' 
		WHERE id = '".$id."'";
	//echo $sql;
	//exit;
		
	if($dbc->query($sql)){
		ob_clean();
		echo 'success';
		//writeToLogfile($_SESSION['xhr']['cid'], 'action', 'Update user permissions for : '.$user_name.' ('.$user_id.')');
	}else{
		ob_clean();
		echo mysqli_error($dbc);
	}













