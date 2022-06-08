<?
	if(session_id()==''){session_start(); ob_start();}
	$cid = $_SESSION['xhr']['cid'];
	include("../../dbconnect/db_connect.php");
	
	if(empty($_REQUEST['opass']) || empty($_REQUEST['npass']) || empty($_REQUEST['rpass'])){
		ob_clean();	echo 'empty'; exit;
	}
	if(strlen($_REQUEST['rpass'])<8){
		ob_clean();	echo 'short'; exit;
	}
	if($_REQUEST['npass'] !== $_REQUEST['rpass']){
		ob_clean();	echo 'same'; exit;
	}
	$pass1 = hash('sha256', $_REQUEST['opass']); 
	$pass3 = hash('sha256', $_REQUEST['rpass']);
	//$pass5 = hash('sha256', 'www');
	
	$res = $dbc->query("SELECT emp_id FROM ".$cid."_employees WHERE password = '".$pass1."' AND `emp_id` = '".$_SESSION['xhr']['id']."'");
	//echo mysqli_error($dbc);
	//echo $_REQUEST['opass'];
	//exit;
	if($res->num_rows > 0){
		$sql = "UPDATE ".$cid."_employees SET password = '".$pass3."' WHERE emp_id = '".$_SESSION['xhr']['id']."'";
		if($dbc->query($sql)){
			ob_clean();	echo 'success'; exit;
		}else{
			ob_clean();	echo 'Error : '.mysqli_error($dbc);
		}
	}else{
		ob_clean();	echo 'old';
	}
	
	
?>