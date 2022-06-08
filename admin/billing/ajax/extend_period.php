<?

	if(session_id()==''){session_start();}
	ob_start();
	include("../../dbconnect/db_connect.php");
	var_dump($_REQUEST); //exit;
	$id = strtolower(str_replace(' ', '', $_REQUEST['id']));
	$period = explode(' / ',$_REQUEST['period']);
	var_dump($id); 
	var_dump($period);
	
	
	//exit;
	
	
	$sql = "UPDATE rego_customers SET 
		period_start = '".$period[0]."',
		period_end = '".$period[1]."' 
		WHERE clientID = '".$id."'";
		
	//var_dump($sql); exit;
	ob_clean();
	if($dba->query($sql)){
		echo 'success';
	}else{
		echo mysqli_error($dba);
	}
