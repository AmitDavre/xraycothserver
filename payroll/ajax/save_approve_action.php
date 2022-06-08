<?
	if(session_id()==''){session_start();}
	ob_start();
	include('../../dbconnect/db_connect.php');

	//var_dump($_FILES);
	//var_dump($_SESSION['rego']['cur_month']); exit;
	
	if($_REQUEST['action'] == 'RJ'){
		$dir = 	'../../'.$cid.'/approvals/';
		//var_dump($dir);
		if(!file_exists($dir)) {
			mkdir($dir, 0777, true);
		}
		$attachment = '';
		if(!empty($_FILES['attach']['tmp_name'])){
			$filename = date('dmY@His').'_'.$_FILES['attach']['name'];
			if(move_uploaded_file($_FILES['attach']['tmp_name'],$dir.$filename)){
				$attachment = $cid.'/approvals/'.$filename;
			}
		}
		//var_dump($attachment); exit;
		$sql = "INSERT INTO ".$cid."_approvals (month, year, type, by_name, by_id, action, comment, attachment) VALUES (
			'".$dbc->real_escape_string($_SESSION['rego']['cur_month'])."', 
			'".$dbc->real_escape_string($_SESSION['rego']['cur_year'])."', 
			'".$dbc->real_escape_string($_REQUEST['type'])."', 
			'".$dbc->real_escape_string($_SESSION['rego']['name'])."', 
			'".$dbc->real_escape_string($_SESSION['rego']['id'])."', 
			'".$dbc->real_escape_string($_REQUEST['action'])."', 
			'".$dbc->real_escape_string($_REQUEST['comment'])."', 
			'".$dbc->real_escape_string($attachment)."')";
		
		if($dbc->query($sql)){
			ob_clean(); echo 'success';
		}else{
			ob_clean(); echo mysqli_error($dbc);
		}
		exit;
	}
	
	if($_REQUEST['action'] == 'AP'){
		$sql = "INSERT INTO ".$cid."_approvals (month, year, type, by_name, by_id, action) VALUES (
			'".$dbc->real_escape_string($_SESSION['rego']['cur_month'])."', 
			'".$dbc->real_escape_string($_SESSION['rego']['cur_year'])."', 
			'".$dbc->real_escape_string($_REQUEST['type'])."', 
			'".$dbc->real_escape_string($_SESSION['rego']['name'])."', 
			'".$dbc->real_escape_string($_SESSION['rego']['id'])."', 
			'".$dbc->real_escape_string($_REQUEST['action'])."')";
		
		if($dbc->query($sql)){
			ob_clean(); echo 'success';
		}else{
			ob_clean(); echo mysqli_error($dbc);
		}
		exit;
	}
	
?>
















