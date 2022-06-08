<?
	if(session_id()==''){session_start();}
	ob_start();
	include('../../dbconnect/db_connect.php');
	include(DIR.'files/functions.php');
	
	$time_period = getTimePeriod();
	$start = date('Y-m-d', strtotime($time_period['start']));
	$end = date('Y-m-d', strtotime($time_period['end']));
	
	$data = array();
	$sql = "SELECT status FROM ".$cid."_attendance WHERE date >= '$start' and date <= '$end' AND status != 1";
	if($res = $dbc->query($sql)){
		if($res->num_rows > 0){
			ob_clean();
			echo $res->num_rows;
			exit;
		}
	}
	
	
	
	
	var_dump($data); exit;
	
	/*if($_REQUEST['action'] == 'RJ'){
		$dir = DIR.$cid.'/approvals/';
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
		$sql = "INSERT INTO ".$cid."_approvals (month, type, emp_group, by_name, by_id, action, comment, attachment) VALUES (
			'".$dbc->real_escape_string($_REQUEST['month'])."', 
			'".$dbc->real_escape_string($_REQUEST['type'])."', 
			'".$dbc->real_escape_string($_REQUEST['emp_group'])."', 
			'".$dbc->real_escape_string($_REQUEST['emp_name'])."', 
			'".$dbc->real_escape_string($_REQUEST['emp_id'])."', 
			'".$dbc->real_escape_string($_REQUEST['action'])."', 
			'".$dbc->real_escape_string($_REQUEST['comment'])."', 
			'".$dbc->real_escape_string($attachment)."')";
		
		if($dbc->query($sql)){
			echo '<div style="margin:0px" class="msg_success">Database updated successfuly</div>';
		}else{
			echo '<div class="msg_error">Sorry but someting went wrong&nbsp; <b>Error :</b> '.mysqli_error($dbc).'</div>';
		}
		exit;
	}*/
	
	//if($_REQUEST['action'] == 'RV' || $_REQUEST['action'] == 'AP'){
	if($_REQUEST['action'] == 'AP'){
		$sql = "INSERT INTO ".$cid."_approvals (month, year, type, by_name, by_id, action) VALUES (
			'".$dbc->real_escape_string($_REQUEST['month'])."', 
			'".$dbc->real_escape_string($cur_year)."', 
			'".$dbc->real_escape_string($_REQUEST['type'])."', 
			'".$dbc->real_escape_string($_REQUEST['emp_name'])."', 
			'".$dbc->real_escape_string($_REQUEST['emp_id'])."', 
			'".$dbc->real_escape_string($_REQUEST['action'])."')";
		
		ob_clean();
		if($dbc->query($sql)){
			echo 'success';
		}else{
			echo mysqli_error($dbc);
		}
		exit;
	}
	
?>
















