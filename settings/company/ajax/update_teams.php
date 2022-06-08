<?

	if(session_id()==''){session_start();}
	ob_start();
	include("../../../dbconnect/db_connect.php");
	
	foreach($_REQUEST['teams'] as $key=>$val){
		foreach($val as $k=>$v){
			if(empty($v)){ob_clean(); echo 'empty'; exit;}
		}


		// $idValeachss = strtolower($val['code']);
		// $spaceStripeachsss = $dbc->real_escape_string($idValeachss);

		// $sqlFteamsaa = "SELECT * FROM ".$cid."_teams WHERE code = '".$spaceStripeachsss."'";
		// if($resFteamsaaa = $dbc->query($sqlFteamsaa))
		// {
		// 	if($rowaa = $resFteamsaaa->fetch_assoc())
		// 	{
		// 		echo 'empty'; exit;
		// 	}
		// }
	


	}

	// die();
	
	$sql = "INSERT INTO ".$cid."_teams (id, code, th, en, entity, branch, division, department) VALUES ";
	foreach($_REQUEST['teams'] as $k=>$v){
		if(!empty($v['code'])){
			$sql .= "('".$k."',";
			$sql .= "'".$dbc->real_escape_string($v['code'])."',";
			$sql .= "'".$dbc->real_escape_string($v['th'])."',";
			$sql .= "'".$dbc->real_escape_string($v['en'])."',";
			$sql .= "'".$dbc->real_escape_string($v['entity'])."',";
			$sql .= "'".$dbc->real_escape_string($v['branch'])."',";
			$sql .= "'".$dbc->real_escape_string($v['division'])."',";
			$sql .= "'".$dbc->real_escape_string($v['department'])."'),";
		}
	}
	$sql = substr($sql,0,-1);
	$sql .= " ON DUPLICATE KEY UPDATE 
		th=VALUES(th), 
		en=VALUES(en),
		entity=VALUES(entity),
		branch=VALUES(branch),
		division=VALUES(division),
		department=VALUES(department)";
	//echo($sql); exit;




		$teams = array();
	$trimmedvalues = array();
	if(isset($_REQUEST['teams'])){
		foreach($_REQUEST['teams'] as $k=>$v)
		{
			$teams['en'][$v['code']] = $v['en'];
			$teams['th'][$v['code']] = $v['th'];
			$teams['code_id'][$v['code']] = $v['code'];


			$idValeach = strtolower($v['code']);
			// $spaceStripeach = preg_replace('/\s+/', '', $idValeach);

			$spaceStripeach = $dbc->real_escape_string($idValeach);


			

			$sqlFteams = "SELECT * FROM ".$cid."_shiftplans_".$cur_year." WHERE id = '".$spaceStripeach."'";
			if($resFteams = $dbc->query($sqlFteams))
			{
				if($row = $resFteams->fetch_assoc())
				{

				}
				else
				{	
					$ssData = 'a:48:{s:7:"team_id";s:4:"main";s:4:"name";s:7:"No Team";s:11:"description";s:22:"Default Shift Schedule";s:6:"noscan";s:1:"1";s:12:"scheduleType";s:6:"select";s:16:"numberOfSchedule";s:6:"select";s:10:"week_setup";s:6:"select";s:17:"variable_off_days";s:2:"na";s:9:"schedule1";s:6:"select";s:10:"range_day1";s:0:"";s:8:"t_hours1";s:0:"";s:8:"b_hours1";s:0:"";s:9:"schedule2";s:3:"off";s:10:"range_day2";s:0:"";s:8:"t_hours2";s:0:"";s:8:"b_hours2";s:0:"";s:9:"schedule3";s:6:"select";s:10:"range_day3";s:0:"";s:8:"t_hours3";s:0:"";s:8:"b_hours3";s:0:"";s:9:"schedule4";s:3:"off";s:10:"range_day4";s:0:"";s:8:"t_hours4";s:0:"";s:8:"b_hours4";s:0:"";s:9:"schedule5";s:6:"select";s:10:"range_day5";s:0:"";s:8:"t_hours5";s:0:"";s:8:"b_hours5";s:0:"";s:9:"schedule6";s:3:"off";s:10:"range_day6";s:0:"";s:8:"t_hours6";s:0:"";s:8:"b_hours6";s:0:"";s:9:"schedule7";s:6:"select";s:10:"range_day7";s:0:"";s:8:"t_hours7";s:0:"";s:8:"b_hours7";s:0:"";s:9:"schedule8";s:3:"off";s:10:"range_day8";s:0:"";s:8:"t_hours8";s:0:"";s:8:"b_hours8";s:0:"";s:10:"early_late";s:3:"yes";s:15:"show_early_late";s:3:"yes";s:12:"accept_early";s:0:"";s:11:"accept_late";s:0:"";s:8:"overtime";s:3:"yes";s:16:"show_overtme_val";s:3:"yes";s:11:"normalhours";s:0:"";s:9:"startdate";s:0:"";}';

					$idVal = strtolower($v['code']);
					$spaceStrip = preg_replace('/\s+/', '', $idVal);
					$sqlIPlan = "INSERT INTO ".$cid."_shiftplans_".$cur_year." (id, code,name,ss_data,th_name) VALUES ";
					$sqlIPlan .= "('".$dbc->real_escape_string($spaceStrip)."', ";
					$sqlIPlan .= "'".$dbc->real_escape_string($v['en'])."',";
					$sqlIPlan .= "'".$dbc->real_escape_string($v['en'])."',";
					$sqlIPlan .= "'".$dbc->real_escape_string($ssData)."',";
					$sqlIPlan .= "'".$dbc->real_escape_string($v['th'])."')";
					$resPlan = $dbc->query($sqlIPlan);
				}
			}

		
			$idVal1 = strtolower($v['code']);
			$spaceStrip1 = preg_replace('/\s+/', '', $idVal1);
			// update record  

			$sql4 = "UPDATE ".$cid."_shiftplans_".$cur_year." SET code = '".$v['en']."' , name  = '".$v['en']."', th_name  = '".$v['th']."' WHERE id = '".$spaceStrip1."'";
			$dbc->query($sql4);

		}
		$_REQUEST['teams'] = serialize($teams);

		$sql5 = "UPDATE ".$cid."_sys_settings SET teams = '".serialize($teams)."'  WHERE id = '1'";
			$dbc->query($sql5);

	}


// echo '<pre>';
// print_r($_REQUEST['teams']);
// echo '</pre>';

// die();





	
	if($dbc->query($sql)){
		ob_clean();
		echo 'success';
	}else{
		ob_clean();
		echo mysqli_error($dbc);
	}














