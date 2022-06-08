<?
	if(session_id()==''){session_start();}
	ob_start();
	
	include("../dbconnect/db_connect.php");
	$id = strtolower($_REQUEST['priceTable']['customer']);
	//var_dump($_REQUEST); exit;
	
	$sql = "UPDATE ".$_SESSION['SDadmin']."_customers SET 
		price_table = '".$dba->real_escape_string(serialize($_REQUEST['priceTable']))."',
		project_table = '".$dba->real_escape_string(serialize($_REQUEST['projectTable']))."' 
		WHERE clientID = '".$id."'";
	ob_clean();
	if($dba->query($sql)){
		$err_msg = 'ok';
	}else{
		$err_msg = mysqli_error($dba);
	}
	echo $err_msg;
	exit;