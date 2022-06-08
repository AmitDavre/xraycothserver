<?
	if(session_id()==''){session_start(); ob_start();}
	//$cid = $_SESSION['rego']['cid'];
	include("../../dbconnect/db_connect.php");
	include(DIR.'files/functions.php');
	include(DIR.'leave/functions.php');
	//var_dump($_REQUEST);	exit;
	$leave_types = getSelLeaveTypes($cid);
	//var_dump($leave_types);	
	//$_REQUEST['emp_id'] = 'DEMO-001';
	
	$lcolors = array(0=>'#aaf', 1=>'#8d8');
	$leave_colors = array(
		'#57889c',
		'#92a2a8',
		'#4c4f53',
		'#356e35',
		'#71843f',
		'#496949',
		'#a90329',
		'#b09b5b',
		'#c79121',
		'#a57225',
		'#ac5287',
		'#a8829f',
		'#6e587a',
		'#404040',
		'#d5e7ec',
		'#ffffff',
		'#525252',
		'#6e3671',
		'#568a89',
		'#a65858');
	
	$status_colors = array(
		'TA'=>'#C4DAEE',
		'RQ'=>'#C5B8EE',
		'AP'=>'#9CE7B9',
		'RJ'=>'#F593D3',
		'CA'=>'#F8CD96');
	
	$nr=0;
	foreach($leave_types as $k=>$v){
		$leave_types[$k]['class'] = $leave_colors[$nr]; $nr++;
	}
	//var_dump($leave_types);	
	
	$json = array();
	
	//$res = $dbc->query("SELECT * FROM ".$cid."_leaves_data WHERE emp_id = '".$_REQUEST['emp_id']."' AND status = 'TA'");
	$res = $dbc->query("SELECT * FROM ".$cid."_leaves_data WHERE emp_id = '".$_REQUEST['emp_id']."'");
	while($row = $res->fetch_assoc()){
		//$xjson[] = $row;
		//var_dump($row['date']);
		$json[] = array(
			'id'=>$row['id'],
			'type'=>'leave',
			'start'=>date('Y-m-d',strtotime($row['date'])),
			'title'=>'<b>'.$row['leave_type'].'</b> <span>'.$leave_types[$row['leave_type']][$_SESSION['rego']['lang']].'</span>',
			//'icon'=>'fa-times',
			//'background'=>'',
			'color'=>$status_colors[$row['status']]);
	}
	//var_dump($json); exit;
	$hd = getHolidays($_SESSION['rego']['cur_year']);
	//var_dump($hd); //exit;
	foreach($hd as $k=>$v){
		$json[] = array(
			'id'=>$k,
			'type'=>'hol',
			'start'=>date('Y-m-d', strtotime($v['cdate'])),
			'title'=>'<b>Holiday</b> <span>'.$v[$_SESSION['rego']['lang']].'</span>',
			//'leave'=>'',
			'color'=>'#fb0');
	}
	
	
	
	//var_dump($json); exit;
	ob_clean();
 	echo json_encode($json);
	exit;

?>