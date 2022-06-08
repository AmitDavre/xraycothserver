<?
	if(session_id()==''){session_start(); ob_start();}
	//$cid = $_SESSION['xhr']['cid'];
	include("../../dbconnect/db_connect.php");
	include(DIR.'files/functions.php');
	include(DIR.'time/functions.php');
	
	//var_dump($_REQUEST); exit;
	
	$plan = $_REQUEST;
	$start = $plan['startdate'];
	//$days = xdate_range($start,'10-01-2019');
	//$work_hrs = getWorkingHours($cid);
	$shiftplan = getDefaultShiftplan($cid);
	$holidays = getHolidaysDates();
	
	//echo '$days<br>';
	//var_dump($days); 
	//var_dump($shiftplan); exit;
	
	$current = strtotime($_REQUEST['startdate']);
	$last = strtotime("mon jan ".(date('Y')+1));
	$dates = array();
		
	if($plan['shiftType'] == 'nw' || $plan['shiftType'] == '12d' || $plan['shiftType'] == '12n'){
		while($current < $last){
			foreach($plan['week']['day'] as $k=>$v){
				
				if($v == '0'){
					//$dates[$current]['date'] = date('d-m-Y', $current);
					$dates[$current]['from1'] = '';
					$dates[$current]['until1'] = '';
					$dates[$current]['from2'] = '';
					$dates[$current]['until2'] = '';
					$dates[$current]['hours'] = '';
					$dates[$current]['break'] = '';
					$dates[$current]['end'] = '';
					$dates[$current]['plan'] = 'OFF';
				}else{
					//$dates[$current]['date'] = date('d-m-Y', $current);
					$dates[$current]['from1'] = $shiftplan[$v]['f1'];
					$dates[$current]['until1'] = $shiftplan[$v]['u1'];
					$dates[$current]['from2'] = $shiftplan[$v]['f2'];
					$dates[$current]['until2'] = $shiftplan[$v]['u2'];
					$dates[$current]['hours'] = $shiftplan[$v]['hours'];
					$dates[$current]['break'] = $shiftplan[$v]['break'];
					$dates[$current]['end'] = $shiftplan[$v]['end'];
					$dates[$current]['plan'] = $v;
				} 
				if($_REQUEST['holidays'] == 'Y' && isset($holidays[$current])){
					//$dates[$current]['date'] = date('d-m-Y', $current);
					$dates[$current]['from1'] = '';
					$dates[$current]['until1'] = '';
					$dates[$current]['from2'] = '';
					$dates[$current]['until2'] = '';
					$dates[$current]['hours'] = '';
					$dates[$current]['break'] = '';
					$dates[$current]['end'] = '';
					$dates[$current]['plan'] = 'HD';
				}
				
				$current = strtotime('+1 day', $current);
			}
		}
	}
	//var_dump($dates); exit;
	
	if($plan['shiftType']=='3x8' || $plan['shiftType']=='2x8' || $plan['shiftType']=='2x12'){
		while($current < $last){
			foreach($plan['sequence'] as $seq=>$type){
				for($i=1;$i<=(int)$plan['quant'][$seq];$i++){
					foreach($plan['week'][$seq] as $k=>$v){
						if($v == '0'){
							$dates[$current]['from1'] = '';
							$dates[$current]['until1'] = '';
							$dates[$current]['from2'] = '';
							$dates[$current]['until2'] = '';
							$dates[$current]['hours'] = '';
							$dates[$current]['break'] = '';
							$dates[$current]['end'] = '';
							$dates[$current]['plan'] = 'OFF';
						}else{
							$dates[$current]['from1'] = $shiftplan[$v]['f1'];
							$dates[$current]['until1'] = $shiftplan[$v]['u1'];
							$dates[$current]['from2'] = $shiftplan[$v]['f2'];
							$dates[$current]['until2'] = $shiftplan[$v]['u2'];
							$dates[$current]['hours'] = $shiftplan[$v]['hours'];
							$dates[$current]['break'] = $shiftplan[$v]['break'];
							$dates[$current]['end'] = $shiftplan[$v]['end'];
							$dates[$current]['plan'] = $v;
						}
						if($_REQUEST['holidays'] == 'Y' && isset($holidays[$current])){
							//$dates[$current]['date'] = date('d-m-Y', $current);
							$dates[$current]['from1'] = '';
							$dates[$current]['until1'] = '';
							$dates[$current]['from2'] = '';
							$dates[$current]['until2'] = '';
							$dates[$current]['hours'] = '';
							$dates[$current]['break'] = '';
							$dates[$current]['end'] = '';
							$dates[$current]['plan'] = 'HD';
						}
						$current = strtotime('+1 day', $current);
					}
					
				}
			}
		}
	}
	//var_dump($dates); exit;
	
	if($plan['shiftType']=='rd'){
		while($current < $last){
			foreach($plan['day'] as $k=>$v){
				if($v == '0'){
					$dates[$current]['from1'] = '';
					$dates[$current]['until1'] = '';
					$dates[$current]['from2'] = '';
					$dates[$current]['until2'] = '';
					$dates[$current]['hours'] = '';
					$dates[$current]['break'] = '';
					$dates[$current]['end'] = '';
					$dates[$current]['plan'] = 'OFF';
				}else{
					$dates[$current]['from1'] = $shiftplan[$v]['f1'];
					$dates[$current]['until1'] = $shiftplan[$v]['u1'];
					$dates[$current]['from2'] = $shiftplan[$v]['f2'];
					$dates[$current]['until2'] = $shiftplan[$v]['u2'];
					$dates[$current]['hours'] = $shiftplan[$v]['hours'];
					$dates[$current]['break'] = $shiftplan[$v]['break'];
					$dates[$current]['end'] = $shiftplan[$v]['end'];
					$dates[$current]['plan'] = $v;
				}
				if($_REQUEST['holidays'] == 'Y' && isset($holidays[$current])){
					//$dates[$current]['date'] = date('d-m-Y', $current);
					$dates[$current]['from1'] = '';
					$dates[$current]['until1'] = '';
					$dates[$current]['from2'] = '';
					$dates[$current]['until2'] = '';
					$dates[$current]['hours'] = '';
					$dates[$current]['break'] = '';
					$dates[$current]['end'] = '';
					$dates[$current]['plan'] = 'HD';
				}
				$current = strtotime('+1 day', $current);
			}
			for($i=1;$i<=$plan['offdays'];$i++){
				$dates[$current]['from1'] = '';
				$dates[$current]['until1'] = '';
				$dates[$current]['from2'] = '';
				$dates[$current]['until2'] = '';
				$dates[$current]['hours'] = '';
				$dates[$current]['break'] = '';
				$dates[$current]['end'] = '';
				$current = strtotime('+1 day', $current);
			}
		}
	}

	$mplan[$plan['code']] = $plan;
	//var_dump($shiftplan['DWW']); 
	//var_dump($plan); 
	//exit;
	
	$dbname = $cid.'_shiftplans_'.$cur_year;
	$sql = "INSERT INTO ".$dbname." (id, code, name, description, plan, dates) VALUES(
	'".$dbc->real_escape_string($plan['id'])."',
	'".$dbc->real_escape_string($plan['code'])."',
	'".$dbc->real_escape_string($plan['name'])."',
	'".$dbc->real_escape_string($plan['description'])."',
	'".serialize($plan)."',
	'".serialize($dates)."') 
		ON DUPLICATE KEY UPDATE 
		code = VALUES(code),
		name = VALUES(name),
		description = VALUES(description),
		plan = VALUES(plan),
		dates = VALUES(dates)";
		
	//echo $sql;
	//exit;
		
	if($dbc->query($sql)){
		ob_clean();
		echo 'success';
	}else{
		ob_clean();
		echo mysqli_error($dbc);
	}
	exit;
	
?>














