<?
	if(session_id()==''){session_start(); ob_start();}
	include('../../dbconnect/db_connect.php');
	include(DIR.'files/functions.php');
	include(DIR.'files/payroll_functions.php');
	
	$allowances = getAllowances($pr_settings, $_SESSION['rego']['lang']);
	$var_allow = $allowances['var'];
	
	$allCols = getEmptyColumns($dbc, $var_allow);
	$emptyCols = $allCols['ecol'];
	$usedCols = $allCols['ucol'];
	
	$eCols = '';
	foreach($emptyCols as $k=>$v){
		$eCols .= $k.',';
	}
	//if($paid){$eCols .= '30,';}
	//$eCols .= '31,';
	$eCols = '['.substr($eCols,0,-1).']';
	//var_dump($eCols);
	
	$uCols = '';
	foreach($usedCols as $k=>$v){
		$uCols .= $k.',';
	}
	$uCols = '['.substr($uCols,0,-1).']';
	$data['ecol'] = $eCols;
	$data['ucol'] = $uCols;
	//var_dump($uCols);
	//var_dump($eCols);
	
	//var_dump($allCols); exit;
	ob_clean();
	echo json_encode($data);
	exit;
	
?>