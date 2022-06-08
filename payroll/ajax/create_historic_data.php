<?

	if(session_id()==''){session_start();}
	ob_start();
	include('../../dbconnect/db_connect.php');
	include(DIR.'files/functions.php');
	//var_dump($_REQUEST); exit;

	$employees = getEmployees($cid, $_SESSION['rego']['selpr_entities']);
	
	//$dbc->query("TRUNCATE ".$cid.'_historic_data_'.$_SESSION['rego']['cur_year']);
	$sm = $_REQUEST['sMonth'];
	$em = $_REQUEST['eMonth'];
	
	$sql = "INSERT IGNORE INTO ".$cid."_historic_data (id, emp_id, month, emp_name_en, emp_name_th, entity, branch, division, department, team, emp_group, position) VALUES ";
	while($sm <= $em){
		foreach($employees as $k=>$v){
			$sql .= "(
			'".$dbc->real_escape_string($k.$sm)."', 
			'".$dbc->real_escape_string($k)."', 
			'".$dbc->real_escape_string($sm)."', 
			'".$dbc->real_escape_string($employees[$k]['en_name'])."', 
			'".$dbc->real_escape_string($employees[$k]['th_name'])."', 
			'".$dbc->real_escape_string($employees[$k]['entity'])."', 
			'".$dbc->real_escape_string($employees[$k]['branch'])."', 
			'".$dbc->real_escape_string($employees[$k]['division'])."', 
			'".$dbc->real_escape_string($employees[$k]['department'])."', 
			'".$dbc->real_escape_string($employees[$k]['team'])."', 
			'".$dbc->real_escape_string($employees[$k]['emp_group'])."', 
			'".$dbc->real_escape_string($employees[$k]['position'])."'),";
		}
		$sm ++;
	}
	$sql = substr($sql,0,-1);
	//var_dump($sql); exit;
	ob_clean();
	if($dbc->query($sql)){
		echo 'success';
	}else{
		echo mysqli_error($dbc);
	}	
	exit;	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
