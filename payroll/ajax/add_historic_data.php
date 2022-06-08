<?

	if(session_id()==''){session_start();}
	ob_start();
	include('../../dbconnect/db_connect.php');
	include(DIR.'files/functions.php');
	//var_dump($_SESSION); exit;

	//$missing_emps = getMissingEmployeesForHistoricData($cid, $_REQUEST['months'], $_REQUEST['ids']);
	//var_dump($missing_emps); //exit;

	// echo '<pre>';
	// print_r($_REQUEST);
	// echo '</pre>';

	// exit;
	
	$employees = getEmployees($cid,0);
	//var_dump($employees); exit;
	
	$sql = "INSERT IGNORE INTO ".$cid."_historic_data (id, emp_id, entity, emp_group, month, emp_name_en, emp_name_th) VALUES ";
	foreach($_REQUEST['ids'] as $k=>$v){
		$sql .= "(
		'".$dbc->real_escape_string($v.$_REQUEST['month'])."', 
		'".$dbc->real_escape_string($v)."',
		'".$dbc->real_escape_string($employees[$v]['entity'])."', 
		'".$dbc->real_escape_string($employees[$v]['emp_group'])."', 
		'".$dbc->real_escape_string($_REQUEST['month'])."', 
		'".$dbc->real_escape_string($employees[$v]['en_name'])."', 
		'".$dbc->real_escape_string($employees[$v]['th_name'])."'),";
	}
	$sql = substr($sql,0,-1);
	//var_dump($sql); exit;
		
		//ob_clean();
		if($dbc->query($sql)){
			echo 'ok';
		}else{
			echo mysqli_error($dbc);
		}	
	exit;		
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
