<?
	if(session_id()==''){session_start(); ob_start();}
	//$cid = $_SESSION['xhr']['cid'];
	include("../../dbconnect/db_connect.php");
	include(DIR.'files/functions.php');
	include(DIR.'time/functions.php');
	
	/*function xdate_range($first, $last, $step = '+1 day', $output_format = 'd-m-Y' ) {
		$dates = array();
		$current = strtotime($first);
		$last = strtotime($last);
		while( $current <= $last ) {
			$w = (int)date('w', $current);
			if($w == 0){$w = 7;}
			$dates[date($output_format, $current)]['m'] = date('m', $current);
			$dates[date($output_format, $current)]['d'] = $w;
			$dates[date($output_format, $current)]['w'] = (int)date('W', $current);
			$dates[date($output_format, $current)]['time'] = '';
			$current = strtotime($step, $current);
		}
		return $dates;
	}*/
	//var_dump($_REQUEST); //exit;
	
	
	$plan = $_REQUEST;
	$start = $plan['startdate'];
	//$days = xdate_range($start,'10-01-2019');
	$work_hrs = getWorkingHours($cid);
	
	//echo '$days<br>';
	//var_dump($days); 
	//var_dump($work_hrs); 
	//exit;
	
	$current = strtotime($_REQUEST['startdate']);
	$last = strtotime("mon jan ".(date('Y')+1));
	$dates = array();
		
	if($plan['shiftType'] == 'nw' || $plan['shiftType'] == '12d' || $plan['shiftType'] == '12n'){
		while($current < $last){
			foreach($plan['week']['day'] as $k=>$v){
				if($v!='0'){
					$dates[$current]['from1'] = $work_hrs[$v]['f1'];
					$dates[$current]['until1'] = $work_hrs[$v]['u1'];
					$dates[$current]['from2'] = $work_hrs[$v]['f2'];
					$dates[$current]['until2'] = $work_hrs[$v]['u2'];
					$dates[$current]['hours'] = $work_hrs[$v]['hours'];
					$dates[$current]['break'] = $work_hrs[$v]['break'];
					$dates[$current]['end'] = $work_hrs[$v]['end'];
					$dates[$current]['plan'] = $v;
				}else{
					$dates[$current]['from1'] = '';
					$dates[$current]['until1'] = '';
					$dates[$current]['from2'] = '';
					$dates[$current]['until2'] = '';
					$dates[$current]['hours'] = '';
					$dates[$current]['break'] = '';
					$dates[$current]['end'] = '';
					$dates[$current]['plan'] = 'OFF';
				}
				$current = strtotime('+1 day', $current);
			}
		}
	}
	//var_dump($plan); exit;
	
	if($plan['shiftType']=='3x8' || $plan['shiftType']=='2x8' || $plan['shiftType']=='2x12'){
		while($current < $last){
			foreach($plan['sequence'] as $seq=>$type){
				for($i=1;$i<=(int)$plan['quant'][$seq];$i++){
					foreach($plan['week'][$seq] as $k=>$v){
						if($v!='0'){
							$dates[$current]['from1'] = $work_hrs[$v]['f1'];
							$dates[$current]['until1'] = $work_hrs[$v]['u1'];
							$dates[$current]['from2'] = $work_hrs[$v]['f2'];
							$dates[$current]['until2'] = $work_hrs[$v]['u2'];
							$dates[$current]['hours'] = $work_hrs[$v]['hours'];
							$dates[$current]['break'] = $work_hrs[$v]['break'];
							$dates[$current]['end'] = $work_hrs[$v]['end'];
							$dates[$current]['plan'] = $v;
						}else{
							$dates[$current]['from1'] = '';
							$dates[$current]['until1'] = '';
							$dates[$current]['from2'] = '';
							$dates[$current]['until2'] = '';
							$dates[$current]['hours'] = '';
							$dates[$current]['break'] = '';
							$dates[$current]['end'] = '';
							$dates[$current]['plan'] = 'OFF';
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
				if($v!='0'){
					$dates[$current]['from1'] = $work_hrs[$v]['f1'];
					$dates[$current]['until1'] = $work_hrs[$v]['u1'];
					$dates[$current]['from2'] = $work_hrs[$v]['f2'];
					$dates[$current]['until2'] = $work_hrs[$v]['u2'];
					$dates[$current]['hours'] = $work_hrs[$v]['hours'];
					$dates[$current]['break'] = $work_hrs[$v]['break'];
					$dates[$current]['end'] = $work_hrs[$v]['end'];
					$dates[$current]['plan'] = $v;
				}else{
					$dates[$current]['from1'] = '';
					$dates[$current]['until1'] = '';
					$dates[$current]['from2'] = '';
					$dates[$current]['until2'] = '';
					$dates[$current]['hours'] = '';
					$dates[$current]['break'] = '';
					$dates[$current]['end'] = '';
					$dates[$current]['plan'] = 'OFF';
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
	//var_dump($work_hrs['DWW']); 
	//var_dump($dates); 
	//exit;
	
	
	$dbname = $cid."_shiftplans_".$cur_year;

	$sql = "INSERT INTO `".$dbname."` (code, name, description, plan, dates) VALUES(
	'".$dbc->real_escape_string($plan['code'])."',
	'".$dbc->real_escape_string($plan['name'])."',
	'".$dbc->real_escape_string($plan['description'])."',
	'".serialize($plan)."',
	'".serialize($dates)."') 
		ON DUPLICATE KEY UPDATE 
		name = VALUES(name),
		description = VALUES(description),
		plan = VALUES(plan),
		dates = VALUES(dates)";
		
	//echo $sql;
	//exit;
		
	if($dbc->query($sql)){
		//$err_msg = '<div class="msg_success">'.$lng['updateemployeesuccess'].'</div>';
		ob_clean();
		echo 'success';
		//$_SESSION['xhr']['access'] = $_REQUEST;
		// notify XRAY from changes ---------------------------------------------------------------------------------------------------------
		/*require '../PHPMailer/PHPMailerAutoload.php';	
		
		$mail_subject = 'Employee register from  '.strtoupper($cid).' - '.$compinfo['th_compname'].' has been updated.';
		$from = $info_email;
		$from_name = $compname;
		$body = '<html>
				<head>
				<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
				</head>
				<body>Employee '.$_REQUEST['emp_id'].' : '.$_REQUEST['firstname'].' '.$_REQUEST['lastname'].' ('.$_REQUEST['eng_name'].') has been updated on '.date('d-m-Y @ H:i').'</body>
			</html>';
		$mailto = $info_email;
		$nameto = "X-RAY HR";
		
		$mail = new PHPMailer;
		$mail->CharSet = 'UTF-8';
		$mail->From = $from;
		$mail->FromName = $compname;
		$mail->addAddress($mailto, $nameto); 
		//$mail->addReplyTo($_SESSION['xray']['email'], $_SESSION['xray']['name']);
		$mail->isHTML(true);                                  
		$mail->Subject = $mail_subject;
		$mail->Body = $body;
		$mail->WordWrap = 100;*/
		//echo $body;
		/*if(!$mail->send()) {
			//echo $mail->ErrorInfo;
		}*/
	}else{
		//$err_msg = '<div class="msg_error">'.$lng['Error'].': '.mysqli_error($dbc).'</div>';
		ob_clean();
		echo mysqli_error($dbc);
	}
	exit;
	
?>














