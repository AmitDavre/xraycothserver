<?
	if(session_id()==''){session_start();}
	ob_start();
	include('../../dbconnect/db_connect.php');
	include(DIR.'files/functions.php');
	include(DIR.'leave/functions.php');
	include(DIR.'files/arrays_'.$lang.'.php');
	
	$leave_settings = getLeaveTimeSettings();
	$period_start = $leave_settings['pr_leave_start'];

	// echo '<pre>';
	// print_r($leave_settings);
	// echo '</pre>';
	// die();
	$leave_periods = getMonthlyPeriod($period_start);

	updateLeaveDatabase($cid);

	$sql = "UPDATE `".$cid."_leaves_data` SET `lock` = 1 WHERE `date` >= '".$leave_periods['start']."' AND `date` < '".$leave_periods['end']."' AND `status` = 'TA'";
	// $sql = "UPDATE `".$cid."_leaves_data` SET `lock` = 1 WHERE `date` >= '".$leave_periods['start']."' AND `date` < '2021-09-01' AND `status` = 'TA'";

	
	ob_clean();
	if(!$dbc->query($sql)){
		echo mysqli_error($dbc);
	}else{
		echo 'success';
	}
	
	
	
	exit;
	
	
	
	
	$approved = 1;
	$data = array();
	$sql = "SELECT id, certificate, status FROM ".$cid."_leaves_data";
	echo $sql;
	if($res = $dbc->query($sql)){
		while($row = $res->fetch_assoc()){
			if($row['certificate'] == 1 && ($row['status'] == 'TA' || $row['status'] == 'AP')){
				$data[$row['id']] = 1;
			}else{
				$data[$row['id']] = 0;
				$approved = 0;
			}
		}
	}else{
		echo mysqli_error($dbc);
	}
	var_dump($data); exit;
	
	//echo $sql;
	if($data){ 
		foreach($data as $k=>$v){
			$sql = "UPDATE ".$cid."_leaves_data SET `lock` = '".$v."' WHERE id = '".$k."'"; 
			$dbc->query($sql);
		} 
	}
	//var_dump($data);
	ob_clean();
	if($approved){
		echo 'ok';
	}else{
		echo 'error';
	}
	exit;

?>
















