<?php

	///================== New functions for CC ========
	function AllSystemUsersList(){
		global $dbc;
		$users = array();
		$sql = "SELECT id, name FROM ".$_SESSION['rego']['cid']."_users WHERE type = 'sys'";
		if($res = $dbc->query($sql)){
			while($row = $res->fetch_assoc()){
				$users[$row['id']] = $row['name'];
			}
		}
		return $users;
	}

	function getEmployeeTeam($empid){
		global $cid;
		global $dbc;
		$team = '';
		if($res = $dbc->query("SELECT team FROM ".$cid."_employees WHERE emp_id = '".$empid."'")){
			if($row = $res->fetch_assoc()){
				$team = $row['team'];
			}
		}
		return $team;
	}

	function AllSystemUsersApproverList(){
		global $dbc;
		$users = array();
		$sql = "SELECT * FROM ".$_SESSION['rego']['cid']."_users WHERE type = 'sys'";
		if($res = $dbc->query($sql)){
			while($row = $res->fetch_assoc()){

				$permission = unserialize($row['permissions']);
				if($permission['comm_center']['approve'] == 1){
					$users[$row['id']] = $row['name'];
				}
			}
		}
		return $users;
	}

	function AllSystemUsersEmails(){
		global $dbc;
		$users = array();
		$sql = "SELECT id, username FROM ".$_SESSION['rego']['cid']."_users WHERE type = 'sys'";
		if($res = $dbc->query($sql)){
			while($row = $res->fetch_assoc()){
				$users[$row['id']] = $row['username'];
			}
		}
		return $users;
	}

	function checkAnnouncement(){

		global $cid;
		global $dbc;
		global $lng;
		$msg = '';

		$sql = $dbc->query("SELECT * FROM ".$cid."_comm_centers WHERE status='3' AND anno_mode !='1' ORDER BY id DESC");
		if($sql->num_rows > 0){
			while($row = $sql->fetch_assoc()) {

				if($row['pdflink'] !=''){
					$pdflink = ROOT.$cid.'/commcenter/archive/'.$row['pdflink'];
				}else{
					$pdflink = '#';
				}

				$publish_on = $row['publish_on'];
				$current_dt = date('Y-m-d H:i');

				$Teamaccess = $_SESSION['rego']['sel_teams'];
				$onlyseeAnno = $row['sent_to'];

				$explodeTeamacc = explode(',', $Teamaccess);
				$explodeonlysee = explode(',', $onlyseeAnno);
				$resultBoth = array_intersect($explodeTeamacc, $explodeonlysee);

				if(!empty($resultBoth)){

					if(strtotime($current_dt) > strtotime($publish_on) && $row['sent'] == 1){

						$msg .= '<b>&nbsp; <i class="fa fa-arrow-circle-right"></i>&nbsp; <a target="_blank" href="'.$pdflink.'">'.$row['anno_id'].'</a></b><br>';
					}
				}
			}
		}else{
			return '<b><i class="fa fa-exclamation-circle"></i>&nbsp;&nbsp;'.$lng['No announcement found'].'</b>';
		}

		return $msg;
	}


	function checkAnnouncementForMob($empid){

		global $cid;
		global $dbc;
		global $lng;

		$AllSystemUsersList = AllSystemUsersList();
		$msg = '';

		$sql = $dbc->query("SELECT * FROM ".$cid."_comm_centers WHERE status='3' AND anno_mode !='1' ORDER BY id DESC ");
		if($sql->num_rows > 0){
			while($row = $sql->fetch_assoc()) {

				if($row['pdflink'] !=''){
					$pdflink = ROOT.$cid.'/commcenter/archive/'.$row['pdflink'];
				}else{
					$pdflink = '#';
				}

				$publish_on = $row['publish_on'];
				$current_dt = date('Y-m-d H:i');

				$onlyseeAnno = $row['sel_emp_ids'];
				$explodeonlysee = explode(',', $onlyseeAnno);

				if(in_array($empid, $explodeonlysee)){

					if(strtotime($current_dt) > strtotime($publish_on) && $row['sent'] == 1){

						$msg .= '<tr class="p-3"><td style="width:2%;border-bottom:none;"><b><i style="vertical-align:top;" class="fa fa-arrow-circle-right fa-lg"></i></b></td><td style="width:98%;border-bottom:none;"><b><a tabindex="-1" download="'.$row['pdflink'].'" href="'.$pdflink.'">'.$lng['Communication center'].' - '.$row['anno_id'].' from '.date('d M, Y', strtotime($row['date'])).' from '.$AllSystemUsersList[$row['username']].'</a></b></td></tr>';
					}
				}
			}
		}else{
			return '<tr><td style="width: 100%"><b><i class="fa fa-exclamation-circle"></i>&nbsp;&nbsp;'.$lng['No announcement found'].'</b></td></tr>';
		}

		return $msg;
	}

	function getEmployeedatas($empids){
		global $cid;
		global $dbc;
		global $lng;
		$empidss = str_replace(',', "','", $empids);
		$sentto_data = array();
		$sql = "SELECT * FROM ".$cid."_employees WHERE emp_id IN ('".$empidss."') AND emp_status = '1' ";
		if($res = $dbc->query($sql)){
			if($res->num_rows > 0){
				while($row = $res->fetch_assoc()){
					$sentto_data[] = $row;
				}
			}
		}

		return $sentto_data;
	}

	function getEmployeelistCC($empids){
		global $cid;
		global $dbc;
		global $lng;
		$sentto_data = array();

		if($empids !=''){
			$empidss = str_replace(',', "','", $empids);
			$sql = "SELECT * FROM ".$cid."_employees WHERE emp_id NOT IN ('".$empidss."') AND emp_status = '1' ";
		}else{
			$sql = "SELECT * FROM ".$cid."_employees WHERE emp_status = '1' ";
		}

		if($res = $dbc->query($sql)){
			if($res->num_rows > 0){
				while($row = $res->fetch_assoc()){
					$sentto_data[] = $row;
				}
			}
		}

		return $sentto_data;
	}

	function getsenttoEmp($teams){
		global $cid;
		global $dbc;
		global $lng;
		$sentto_data = array();
		$sql = "SELECT emp_id, firstname, lastname, personal_email, allow_login FROM ".$cid."_employees WHERE team IN (".$teams.") AND emp_status = '1' ";
		if($res = $dbc->query($sql)){
			if($res->num_rows > 0){
				while($row = $res->fetch_assoc()){
					$sentto_data[] = $row;
				}
			}
		}

		return $sentto_data;
	}


	///================== New functions for CC ========

	
	function getEntityData($entity){
		global $dbc;
		$edata = array();
		if($res = $dbc->query("SELECT ".$_SESSION['rego']['lang']."_addr_detail, ".$_SESSION['rego']['lang']."_compname, comp_phone, comp_email, tax_id, revenu_branch, dig_signature, digi_signature, dig_stamp, digi_stamp, logofile, sso_account, sso_codes, banks, en_address, th_address FROM ".$_SESSION['rego']['cid']."_entities_data WHERE ref = '".$entity."'")){
			$edata = $res->fetch_assoc();
		}
		return $edata;
	}
	
	function getEntityBranches($entity){
		global $cid;
		global $dbc;
		$data = array();
		if($res = $dbc->query("SELECT sso_codes FROM ".$cid."_entities_data WHERE ref = $entity")){
			if($row = $res->fetch_assoc()){
				$data = unserialize($row['sso_codes']);
			}
		}
		return $data;
	}

 	function getBranchCode($entity){
		global $cid;
		global $dbc;
		$code = '';
		if($res = $dbc->query("SELECT sso_code FROM ".$cid."_branches_data WHERE ref = $entity")){
			if($row = $res->fetch_assoc()){
				$code = $row['sso_code'];
			}
		}
		return $code;
	}

	function getPayrollSelections($entity){
		global $teams;
		foreach($teams as $k=>$v){
			if($v['entity'] == $entity){
				$branch[$v['branch']] = $v['branch'];
				$division[$v['division']] = $v['division'];
				$department[$v['department']] = $v['department'];
				$team[$k] = $k;
			}
		}

		/*$_SESSION['rego']['pr_entities'] = $_SESSION['rego']['mn_entities'];
		$_SESSION['rego']['pr_branches'] = implode(',', $branch);
		$_SESSION['rego']['pr_divisions'] = implode(',', $division);
		$_SESSION['rego']['pr_departments'] = implode(',', $department);
		$_SESSION['rego']['pr_teams'] = implode(',', $team);*/

		$_SESSION['rego']['pr_entities'] = $_SESSION['rego']['mn_entities'];
		$_SESSION['rego']['pr_branches'] = $_SESSION['rego']['mn_branches'];
		$_SESSION['rego']['pr_divisions'] = $_SESSION['rego']['mn_divisions'];
		$_SESSION['rego']['pr_departments'] = $_SESSION['rego']['mn_departments'];
		$_SESSION['rego']['pr_teams'] = $_SESSION['rego']['mn_teams'];
		
		/*$_SESSION['rego']['selpr_entities'] = $entity;
		$_SESSION['rego']['selpr_branches'] = implode(',', $branch);
		$_SESSION['rego']['selpr_divisions'] = implode(',', $division);
		$_SESSION['rego']['selpr_departments'] = implode(',', $department);
		$_SESSION['rego']['selpr_teams'] = implode(',', $team);*/

		$_SESSION['rego']['selpr_entities'] = $_SESSION['rego']['sel_entities'];
		$_SESSION['rego']['selpr_branches'] = $_SESSION['rego']['sel_branches'];
		$_SESSION['rego']['selpr_divisions'] = $_SESSION['rego']['sel_divisions'];
		$_SESSION['rego']['selpr_departments'] = $_SESSION['rego']['sel_departments'];
		$_SESSION['rego']['selpr_teams'] = $_SESSION['rego']['sel_teams'];

		reset($branch);
		$_SESSION['rego']['gov_entity'] = $entity;
		$_SESSION['rego']['gov_branch'] = $branch[key($branch)];
	}
	
	function getEntityBanks($entity){
		global $cid;
		global $dbc;
		$data = array();
		$sql = "SELECT * FROM ".$cid."_entities_data WHERE ref = $entity";
		if($res = $dbc->query($sql)){
			if($row = $res->fetch_assoc()){
				$data = $row['banks'];
			}
		}
		return unserialize($data);
	}
	
	function getEntities(){
		global $cid;
		global $dbc;
		$data = array();
		$sql = "SELECT * FROM ".$cid."_entities";
		if($res = $dbc->query($sql)){
			while($row = $res->fetch_assoc()){
				$data[$row['id']] = $row;
			}
		}
		return $data;
	}
	
	function getBranches(){
		global $cid;
		global $dbc;
		$data = array();
		$sql = "SELECT * FROM ".$cid."_branches";
		if($res = $dbc->query($sql)){
			while($row = $res->fetch_assoc()){
				$data[$row['id']] = $row;
			}
		}
		return $data;
	}
	
	function getDivisions(){
		global $cid;
		global $dbc;
		$data = array();
		$sql = "SELECT * FROM ".$cid."_divisions";
		if($res = $dbc->query($sql)){
			while($row = $res->fetch_assoc()){
				$data[$row['id']] = $row;
			}
		}
		return $data;
	}
	
	function getDepartments(){
		global $cid;
		global $dbc;
		$data = array();
		$sql = "SELECT * FROM ".$cid."_departments";
		if($res = $dbc->query($sql)){
			while($row = $res->fetch_assoc()){
				$data[$row['id']] = $row;
			}
		}
		return $data;
	}
	
	function getTeams(){
		global $cid;
		global $dbc;
		$data = array();
		$sql = "SELECT * FROM ".$cid."_teams";
		if($res = $dbc->query($sql)){
			while($row = $res->fetch_assoc()){
				$data[$row['id']] = $row;
			}
		}
		return $data;
	}
	
	function getPositions(){
		global $cid;
		global $dbc;
		$data = array();
		$sql = "SELECT * FROM ".$cid."_positions";
		if($res = $dbc->query($sql)){
			while($row = $res->fetch_assoc()){
				$data[$row['id']] = $row;
			}
		}
		return $data;
	}
	
	
	function getLeaveData($date, $id){
		global $dbc;
		$leaves = array();
		if($res = $dbc->query("SELECT * FROM ".$_SESSION['rego']['cid']."_leaves_data WHERE date = '$date' AND emp_id = '$id'")){
			while($row = $res->fetch_assoc()){
				$leaves[$row['emp_id'].'_'.strtotime($row['date'])][$row['leave_type']] = array('days'=>$row['days'], 'day'=>$row['day'], 'hours'=>$row['hours'], 'paid'=>$row['paid']);
			}
		}
		return $leaves;
	}

	function getTimePeriod(){
		global $dbc;
		$data = array();
		$month = $_SESSION['rego']['cur_year'].'_'.$_SESSION['rego']['cur_month'];
		if($res = $dbc->query("SELECT * FROM ".$_SESSION['rego']['cid']."_payroll_months WHERE month = '$month'")){
			$row = $res->fetch_assoc();
			$data['start'] = $row['time_start'];
			$data['end'] = $row['time_end'];
		}
		return $data;
	}

	function getLeavePeriod(){
		global $dbc;
		$data = array();
		$month = $_SESSION['rego']['cur_year'].'_'.$_SESSION['rego']['cur_month'];
		if($res = $dbc->query("SELECT * FROM ".$_SESSION['rego']['cid']."_payroll_months WHERE month = '$month'")){
			$row = $res->fetch_assoc();
			$data['start'] = $row['leave_start'];
			$data['end'] = $row['leave_end'];
		}
		return $data;
	}

	function getUsedPayrollMonths(){
		global $dbc;
		$data = array();
		if($res = $dbc->query("SELECT month, paid FROM ".$_SESSION['rego']['payroll_dbase']." ORDER by LENGTH(month) ASC, month ASC")){
			while($row = $res->fetch_assoc()){
				$data[$row['month']] = $row['paid'];
			}
		}
		return $data;
	}

	function getPayrollPeriod(){
		global $dbc;
		$data = array();
		$month = $_SESSION['rego']['cur_year'].'_'.$_SESSION['rego']['cur_month'];
		if($res = $dbc->query("SELECT * FROM ".$_SESSION['rego']['cid']."_payroll_months WHERE month = '$month'")){
			$row = $res->fetch_assoc();
			$data['start'] = $row['payroll_start'];
			$data['end'] = $row['payroll_end'];
		}
		return $data;
	}

	function generateStrongPassword($length = 9, $add_dashes = false, $available_sets = 'lud'){
		$sets = array();
		if(strpos($available_sets, 'l') !== false)
			$sets[] = 'abcdefghjkmnpqrstuvwxyz';
		if(strpos($available_sets, 'u') !== false)
			$sets[] = 'ABCDEFGHJKMNPQRSTUVWXYZ';
		if(strpos($available_sets, 'd') !== false)
			$sets[] = '23456789';
		if(strpos($available_sets, 's') !== false)
			$sets[] = '!@#$%&*?';
		$all = '';
		$password = '';
		foreach($sets as $set)
		{
			$password .= $set[array_rand(str_split($set))];
			$all .= $set;
		}
		$all = str_split($all);
		for($i = 0; $i < $length - count($sets); $i++)
			$password .= $all[array_rand($all)];
		$password = str_shuffle($password);
		if(!$add_dashes)
			return $password;
		$dash_len = floor(sqrt($length));
		$dash_str = '';
		while(strlen($password) > $dash_len)
		{
			$dash_str .= substr($password, 0, $dash_len) . '-';
			$password = substr($password, $dash_len);
		}
		$dash_str .= $password;
		return $dash_str;
	}


	function langDate($date, $lang){
		if($lang == 'en'){return $date;}
		if($lang == 'th'){return $date;}
		return date('d-m-', strtotime($date)).(date('Y', strtotime($date))+543);
	}
	
	function getHistoryLock(){
		global $cid;
		global $dbc;
		$lock = 1;
		$sql = "SELECT pr_startdate, history FROM ".$cid."_sys_settings";
		if($res = $dbc->query($sql)){
			$row = $res->fetch_assoc();
			if($row['history'] == 0 && date('n', strtotime($row['pr_startdate'])) > 1){	
				$lock = 0;
			}
		}
		return $lock;
	}

	function getHolidaysDates(){
		global $dbc;
		global $cid;
		$data = array();
		$res = $dbc->query("SELECT cdate FROM ".$cid."_holidays WHERE year = '".$_SESSION['rego']['cur_year']."'"); 
		while($row = $res->fetch_assoc()){
			$data[strtotime($row['cdate'])] = $row['cdate'];
		}
		return $data;
	}
	
	function getEmailTemplate($field){
		global $dbx;
		$data['sub'] = '';
		$data['body'] = '';
		$sql = "SELECT * FROM rego_default_email_templates WHERE name = '".$field."'";
		if($res = $dbx->query($sql)){
			if($row = $res->fetch_assoc()){
				$data['sub'] = $row['subject_'.$_SESSION['rego']['lang']];
				$data['body'] = $row['body_'.$_SESSION['rego']['lang']];
			}
		}
		return $data;
	}

	function getWorkingDays($from, $to, $holidays, $ignore) {
		 $from = new DateTime($from);
		 $to = new DateTime($to);
		 $to->modify('+1 day');
		 $interval = new DateInterval('P1D');
		 $periods = new DatePeriod($from, $interval, $to);
		 $days = 0;
		 foreach ($periods as $period) {
			  if (in_array($period->format('N'), $ignore)) continue;
			  if (in_array($period->format('Y-m-d'), $holidays)) continue;
			  if (in_array($period->format('*-m-d'), $holidays)) continue;
			  $days++;
		 }
		 return $days;
	}	
	
	function countWorkingdaysMonth($year, $month, $ignore) {
		 $count = 0;
		 $counter = mktime(0, 0, 0, $month, 1, $year);
		 while (date("n", $counter) == $month) {
			  if (in_array(date("w", $counter), $ignore) == false) {
					$count++;
			  }
			  $counter = strtotime("+1 day", $counter);
		 }
		 return $count;
	}

	function getDefaultShiftplan(){
		global $cid;
		global $dbc;
		$data = array();
		$sql = "SELECT shiftplan FROM ".$cid."_leave_time_settings";
		if($res = $dbc->query($sql)){
			if($row = $res->fetch_object()){
				$data = unserialize($row->shiftplan);
			}
		}
		return $data;
	}
	
	function writeToLogfile($field, $message){
		global $dbx;
		$rego = substr(strtoupper($_SESSION['rego']['cid']), 0, 4).' '.substr($_SESSION['rego']['cid'], 4);
		$ua = getBrowser();
		$sql = "INSERT INTO rego_logdata (user_id, user_name, cid, user_ip, user_img, server_ip, platform, browser, uri, $field) VALUES (
			'".$dbx->real_escape_string($_SESSION['rego']['emp_id'])."', 
			'".$dbx->real_escape_string($_SESSION['rego']['name'])."', 
			'".$dbx->real_escape_string($rego)."', 
			'".$dbx->real_escape_string($_SERVER['REMOTE_ADDR'])."', 
			'".$dbx->real_escape_string($_SESSION['rego']['img'])."', 
			'".$dbx->real_escape_string($_SERVER['SERVER_ADDR'])."', 
			'".$dbx->real_escape_string($ua['platform'])."', 
			'".$dbx->real_escape_string($ua['name'].' '.$ua['version'])."', 
			'".$dbx->real_escape_string('URI'/*$_SERVER['REQUEST_URI']*/)."', 
			'".$dbx->real_escape_string($message)."')";
		//return $sql;
        if($dbx->query($sql)){
			return 'ok';
        }else{
			return 'error : '.mysqli_error($dbx);
		}
   }	

	function getYears(){
		global $lang;
		global $cid;
		global $dbc;
		$data = array();
		if($res = $dbc->query("SELECT years FROM ".$cid."_sys_settings")){
			if($row = $res->fetch_assoc()){
				$tmp = $row['years'];
				$years = explode(',', $tmp);
				foreach($years as $v){
					if($lang == 'en'){
						$data[$v] = (int)$v;
					}else{
						$data[$v] = (int)$v+543;
					}
				}
			}
		}
		return $data;
	}


	function convertetYears($yearvalue,$lang)
	{
		if ($lang == 'en') {
			$newyear = (int)$yearvalue;
		} else {
			$newyear = (int)$yearvalue + 543;
		}
		return $newyear;
	}

	function getCustomers($access){
		global $dbx;
		$data = array();
		$tmp = explode(',', $access);
		if(count($tmp) > 1){
			$ids = "";
			foreach($tmp as $v){$ids .= "'".$v."',";}
			$ids = substr($ids,0,-1);
			$sql = "SELECT * FROM rego_customers WHERE clientID IN($ids) ORDER BY clientID";
			if($res = $dbx->query($sql)){
				while($row = $res->fetch_assoc()){
					$data[$row['clientID']] = $row[$_SESSION['rego']['lang'].'_compname'];
				}
			}
		}
		return $data;
	}

	function getCustomersforpayroll($access){
		global $dbx;
		$data = array();
		
		$sql = "SELECT * FROM rego_customers WHERE clientID = '".$access."' ORDER BY clientID";
		if($res = $dbx->query($sql)){
			while($row = $res->fetch_assoc()){
				$data[$row['clientID']] = $row[$_SESSION['rego']['lang'].'_compname'].' - '.$row['clientID'];
			}
		}
		
		return $data;
	}
	
	function getPayrollPeriods($lang){
		global $dbc;
		global $months;
		global $sys_settings;
		$date = str_replace('/','-',$sys_settings['pr_startdate']);
		$date = str_replace('/','-',$sys_settings['pr_startdate']);
		$start_year = (int)date('Y',strtotime($date));
		$month = (int)date('m', strtotime($date));
		if($start_year == $_SESSION['rego']['cur_year']){
			$start_month = (int)date('m',strtotime($date));
		}elseif($start_year < $_SESSION['rego']['cur_year']){
			$start_month = 1;
		}else{
			$start_month = date('m');
		}
		
		//$period['to_unlock'] = array(); 
		$period['to_lock'] = array(); 
		$period['period'] = array(); 
		$nr = $start_month;
		$new = true;
		
		if($res = $dbc->query("SELECT month, paid FROM ".$_SESSION['rego']['payroll_dbase']." WHERE MONTH >= $start_month and paid != 'H' GROUP by month ORDER by LENGTH(month) ASC, month ASC")){
			while($row = $res->fetch_assoc()){
				if($row['paid'] == 'Y'){
					$icon = '<i class="fa fa-ban"></i>&nbsp;';
					//$period['to_unlock'][(int)$row['month']] = $months[(int)$row['month']].' '.$_SESSION['rego']['year_'.$lang];
				}else{
					$icon = '<i class="fa fa-pencil-square-o"></i>';
					$period['to_lock'][(int)$row['month']] = '<i class="fa fa-lock"></i> '.$months[(int)$row['month']].' '.$_SESSION['rego']['year_'.$lang];
					$new = false;
				}
				$period['period'][(int)$row['month']] = $icon.' '.$months[(int)$row['month']].' '.$_SESSION['rego']['year_'.$lang];
				$nr = $row['month'];
				
			}
			$period['unlock'][$nr] = $months[$nr].' '.$_SESSION['rego']['year_'.$lang];
			if(isset($period['to_lock'][$nr])){$period['unlock'] = array();}
			
			if(!empty($period['period'])){
				$m = $nr;
				if($m < 12){$m ++;}
				if($new){
					$period['period'][$m] = '<i class="fa fa-plus-circle"></i>&nbsp; '.$months[$m].' '.$_SESSION['rego']['year_'.$lang];
				}
			}else{
				$period['period'][$start_month] = '<i class="fa fa-pencil-square-o"></i>&nbsp; '.$months[$start_month].' '.$_SESSION['rego']['year_'.$lang];
			}
			
		}
		return $period;
	}
	
	function getHelpfile($page){
		global $dbx;
		$data = array();
		$res = $dbx->query("SELECT * FROM rego_helpfiles WHERE page = '".$page."'");
		if($row = $res->fetch_assoc()){
			$data = $row[$_SESSION['rego']['lang'].'_content'];
		}
		return $data;
	}	
	
	function getWelcomeFiles(){
		global $dbx;
		$data = array();
		$res = $dbx->query("SELECT * FROM rego_welcomefiles ORDER BY page ASC");
		while($row = $res->fetch_assoc()){
			$data[$row['page']]['title'] = $row[$_SESSION['rego']['lang'].'_title'];
			$data[$row['page']]['content'] = $row[$_SESSION['rego']['lang'].'_content'];
		}
		return $data;
	}	

	function getAllmonthSSo(){
		global $dbc;
		$data = array();
		$res = $dbc->query("SELECT * FROM ".$_SESSION['rego']['cid']."_payroll_months");
		while($row = $res->fetch_assoc()){
			$data[$row['month']] = $row['sso_eMax'];
		}
		return $data;
	}

	function getPaydate($cid){
		global $dbc;
		$paydate = '';
		$month = $_SESSION['rego']['cur_year'].'_'.$_SESSION['rego']['cur_month'];
		if($res = $dbc->query("SELECT * FROM ".$_SESSION['rego']['cid']."_payroll_months WHERE month = '$month'")){
			if($row = $res->fetch_assoc()){
				$paydate = $row['paydate'];
			}
		}
		if(empty($paydate)){
			$tmp = date('01-'.$_SESSION['rego']['curr_month'].'-Y');
			$paydate = date('t-m-Y', strtotime($tmp));
		}
		return $paydate;
	}
	
	function getPaydateMonth($month){
		global $cid;
		global $dbc;
		$paydate = '';
		if($res = $dbc->query("SELECT * FROM ".$_SESSION['rego']['cid']."_payroll_months WHERE month = '".$_SESSION['rego']['cur_year']."_".$month."'")){
			if($row = $res->fetch_assoc()){
				$paydate = $row['paydate'];
			}
		}
		return $paydate;
	}
	
	function getFormdate($cid){
		global $dbc;
		global $months;
		$thm = array(1=>"มกราคม", 2=>"กุมภาพันธ์", 3=>"มีนาคม", 4=>"เมษายน", 5=>"พฤษภาคม", 6=>"มิถุนายน", 7=>"กรกฏาคม", 8=>"สิงหาคม", 9=>"กันยายน", 10=>"ตุลาคม", 11=>"พฤษจิกายน", 12=>"ธันวาคม");
		$month = $_SESSION['rego']['cur_year'].'_'.$_SESSION['rego']['gov_month'];
		$formdate = '';
		$sql = "SELECT formdate FROM ".$cid."_payroll_months WHERE month = '".$month."'";
		if($res = $dbc->query($sql)){
			if($row = $res->fetch_assoc()){
				$formdate = $row['formdate'];
			}
		}
		if(empty($formdate)){
			$formdate = date('t-'.$_SESSION['rego']['cur_month'].'-Y');
		}
		$_SESSION['rego']['formdate']['endate'] = date('d-m-Y', strtotime($formdate));
		$_SESSION['rego']['formdate']['thdate'] = date('d-m-', strtotime($formdate)).(date('Y', strtotime($formdate))+543);
		$_SESSION['rego']['formdate']['d'] = date('d', strtotime($formdate));
		$_SESSION['rego']['formdate']['m'] = $months[date('n', strtotime($formdate))];
		$_SESSION['rego']['formdate']['thm'] = $thm[date('n', strtotime($formdate))];
		$_SESSION['rego']['formdate']['eny'] = date('Y', strtotime($formdate));
		$_SESSION['rego']['formdate']['thy'] = date('Y', strtotime($formdate))+543;
		return $formdate;
	}

	function getLockedMonth($month){
		global $dbc;
		$locked = false;
		if($res = $dbc->query("SELECT paid FROM ".$_SESSION['rego']['payroll_dbase']." WHERE month = '".$month."'")){
			while($row = $res->fetch_assoc()){
				if($row['paid'] == 'Y'){
					$locked = true;
				}
			}
		}
		return $locked;
	}


	function getLeaveTimeSettings(){
		global $dbc;
		$row = array();
		if($res = $dbc->query("SELECT * FROM ".$_SESSION['rego']['cid']."_leave_time_settings")){
			$row = $res->fetch_assoc();
		}
		return $row;
	}

	
	// FUNCTIONS /////////////////////////////////////////////////////////////////////
	function contains($needle, $haystack){
		 return strpos($haystack, $needle) !== false;
	}
	
	function getAge($date){	
		$bday = new DateTime($date);
		$today = new DateTime('now');
		$diff = $today->diff($bday);
		$age = '';
		
		if($_SESSION['rego']['lang'] == 'en'){
			if($diff->y > 0){
				$age .= $diff->y;
				if($diff->y == 1){$age .= ' year, ';}else{$age .= ' years, ';}
				//if($diff->m == 1){$age .= $diff->m.' month, ';}else{$age .= $diff->m.' months, ';}
				//if($diff->d == 1){$age .= $diff->d.' day';}else{$age .= $diff->d.' days';}
			}
			if($diff->m > 0){
				$age .= $diff->m;
				if($diff->m == 1){$age .= ' month, ';}else{$age .= ' months, ';}
			}
			if($diff->d > 0){
				$age .= $diff->d;
				if($diff->d == 1){$age .= ' day';}else{$age .= ' days';}
			}
		}else{
			if($diff->y > 0){
				$age .= $diff->y.' ปี ';
				//$age .= $diff->m.' เดือน ';
				//$age .= $diff->d.' วัน';
			}
			if($diff->m > 0){
				$age .= $diff->m.' เดือน ';
			}
			if($diff->d > 0){
				$age .= $diff->d.' วัน';
			}
		}
		return $age;
	}	
	
	function getNameFromNumber($num) {
		$numeric = $num % 26;
		$letter = chr(65 + $numeric);
		$num2 = intval($num / 26);
		if ($num2 > 0) {
		return getNameFromNumber($num2 - 1) . $letter;
		} else {
		return $letter;
		}
	}	

	function getBrowser() { 
		$u_agent = $_SERVER['HTTP_USER_AGENT']; 
		$bname = 'Unknown';
		$platform = 'Unknown';
		$version= "";
	
		//First get the platform?
		if (preg_match('/linux/i', $u_agent)) {
			$platform = 'linux';
		}elseif (preg_match('/macintosh|mac os x/i', $u_agent)) {
			$platform = 'mac';
		}elseif (preg_match('/windows|win32/i', $u_agent)) {
			$platform = 'windows';
		}
		
		// Next get the name of the useragent yes seperately and for good reason
		if(preg_match('/MSIE/i',$u_agent) && !preg_match('/Opera/i',$u_agent)) { 
			$bname = 'Internet Explorer'; 
			$ub = "MSIE"; 
		}elseif(preg_match('/Firefox/i',$u_agent)){ 
			$bname = 'Mozilla Firefox'; 
			$ub = "Firefox"; 
		}elseif(preg_match('/Chrome/i',$u_agent)){ 
			$bname = 'Google Chrome'; 
			$ub = "Chrome"; 
		}elseif(preg_match('/Safari/i',$u_agent)){ 
			$bname = 'Apple Safari'; 
			$ub = "Safari"; 
		}elseif(preg_match('/Opera/i',$u_agent)){ 
			$bname = 'Opera'; 
			$ub = "Opera"; 
		}elseif(preg_match('/Netscape/i',$u_agent)){ 
			$bname = 'Netscape'; 
			$ub = "Netscape"; 
		} 
		
		// finally get the correct version number
		$known = array('Version', $ub, 'other');
		$pattern = '#(?<browser>' . join('|', $known) .
		')[/ ]+(?<version>[0-9.|a-zA-Z.]*)#';
		if (!preg_match_all($pattern, $u_agent, $matches)) {
			// we have no matching number just continue
		}
		
		// see how many we have
		$i = count($matches['browser']);
		if ($i != 1) {
			//we will have two since we are not using 'other' argument yet
			//see if version is before or after the name
			if (strripos($u_agent,"Version") < strripos($u_agent,$ub)){
				$version= $matches['version'][0];
			}else {
				$version= $matches['version'][1];
			}
		}else {
			$version= $matches['version'][0];
		}
		
		// check if we have a number
		if ($version==null || $version=="") {$version="?";}
		
		return array(
			'userAgent' => $u_agent,
			'name'      => $bname,
			'version'   => $version,
			'platform'  => $platform,
			'pattern'    => $pattern
		);
	} 
	
	function getWordsFromAmount($number, $lang){
		if($lang == 'en'){
			$f = new NumberFormatter("en", NumberFormatter::SPELLOUT);
			return '*** '.ucfirst($f->format($number)).' Baht ***';
		}else{
			//$number = $number;
			if((int)$number == 0){return '*** ศูนย์บาท ***'; exit;}
			$digit=array('ศูนย์','หนึ่ง','สอง','สาม','สี่','ห้า','หก','เจ็ด','แปด','เก้า','สิบ','สิบเอ็ด');
			$digit2=array('','สิบ','ร้อย','พัน','หมื่น','แสน','ล้าน');
			//$digit=array('null','nung ','song ','sam ','see ','ha ','hok ','tjet ','paet ','kaow ','sip ','et ');
			//$digit2=array('','sip ','roi ','pan ','meung ','sean ','lan ');
			if(strpos($number, '.') !== false){
				$explode_number = explode(".",$number);
				$num0=$explode_number[0]; // เลขจำนวนเต็ม
				$num1=$explode_number[1]; // หลักทศนิยม
			}else{
				$num0=$number; // เลขจำนวนเต็ม
				$num1=''; // หลักทศนิยม
			}
			$bathtext1 = '';// เลขจำนวนเต็ม
			$didit2_chk=strlen($num0)-1;
			for($i=0;$i<=strlen($num0)-1;$i++){
				$cut_input_number=substr($num0,$i,1);
				if($cut_input_number==0){ // ถ้าเลข 0 ไม่ต้องใส่ค่าอะไร
					//$bathtext1.=''."".$digit2[$didit2_chk]; 
				}elseif($cut_input_number==2 && $didit2_chk==1){ // ถ้าเลข 2 อยู่หลักสิบ
					$bathtext1.='ยี่'.$digit2[$didit2_chk]; 
				}elseif($cut_input_number==1 && $didit2_chk==1){ // ถ้าเลข 1 อยู่หลักสิบ
					$bathtext1.= ''."".$digit2[$didit2_chk]; 
				}elseif($cut_input_number==1 && $didit2_chk==0){ // ถ้าเลข 1 อยู่หลักหน่วย
					if(substr($num0,$i-1,1)==0){
						$bathtext1.= 'หนึ่ง'.$digit2[$didit2_chk];
					}else{
						$bathtext1.= 'เอ็ด'.$digit2[$didit2_chk];
					} 
				}else{
					$bathtext1.= $digit[$cut_input_number]."".$digit2[$didit2_chk];
				}
				$didit2_chk=$didit2_chk-1;
			}
			$bathtext1.='บาท';
			//$bathtext1.='Bath ';
			// เลขทศนิยม
			$didit2_chk=strlen($num1)-1;
			$satang = false;
			for($i=0;$i<=strlen($num1)-1;$i++){
				$cut_input_number=substr($num1,$i,1);
				if($cut_input_number==0){ // ถ้าเลข 0 ไม่ต้องใส่ค่าอะไร
				
				}elseif($cut_input_number==2 && $didit2_chk==1){ // ถ้าเลข 2 อยู่หลักสิบ
					$bathtext1.='ยี่'.$digit2[$didit2_chk]; 
					//$bathtext1.='yee'."".$digit2[$didit2_chk]; 
					$satang = true;
				}elseif($cut_input_number==1 && $didit2_chk==1){ // ถ้าเลข 1 อยู่หลักสิบ
					$bathtext1.= $digit2[$didit2_chk];
					$satang = true;
				}elseif($cut_input_number==1 && $didit2_chk==0){ // ถ้าเลข 1 อยู่หลักหน่วย
					if(substr($num1,$i-1,1)==0){
					$bathtext1.= 'หนึ่ง'.$digit2[$didit2_chk];
					$satang = true;
					//$bathtext1.= 'neung '."".$digit2[$didit2_chk];
				}else{
					$bathtext1.= 'เอ็ด'.$digit2[$didit2_chk];
					//$bathtext1.= 'et '."".$digit2[$didit2_chk];
				} 
				$satang = true;
			}else{
				$bathtext1.= $digit[$cut_input_number]."".$digit2[$didit2_chk];
				$satang = true;
			}
			$didit2_chk=$didit2_chk-1;
			}
			if($satang){$bathtext1 .='สตางค์';}else{$bathtext1 .= 'ถ้วน';}
			return '*** '.$bathtext1.' ***';
		}
	}

	function getFilename($base, $ext, $dir){
		$filename = $base.'.'.$ext;
		$file = $dir.$filename;
		$counter = 1;				
		while(file_exists($file)) {
			 $filename = $base.' ('.$counter.').'.$ext;
			 $file = $dir.$base.' ('.$counter.').'.$ext;
			 $counter++;
		};
		return $filename;
	}

	function getThaiCharNumber($number){
		if((int)$number == 0){return '';}
		$digit=array('ศูนย์','หนึ่ง','สอง','สาม','สี่','ห้า','หก','เจ็ด','แปด','เก้า','สิบ','สิบเอ็ด');
		$digit2=array('','สิบ','ร้อย','พัน','หมื่น','แสน','ล้าน');
		//$digit=array('null','nung ','song ','sam ','see ','ha ','hok ','tjet ','paet ','kaow ','sip ','et ');
		//$digit2=array('','sip ','roi ','pan ','meung ','sean ','lan ');
		if(strpos($number, '.') !== false){
			$explode_number = explode(".",$number);
			$num0=$explode_number[0]; // เลขจำนวนเต็ม
			$num1=$explode_number[1]; // หลักทศนิยม
		}else{
			$num0=$number; // เลขจำนวนเต็ม
			$num1=''; // หลักทศนิยม
		}
		$bathtext1 = '';// เลขจำนวนเต็ม
		$didit2_chk=strlen($num0)-1;
		for($i=0;$i<=strlen($num0)-1;$i++){
			$cut_input_number=substr($num0,$i,1);
			if($cut_input_number==0){ // ถ้าเลข 0 ไม่ต้องใส่ค่าอะไร
				//$bathtext1.=''."".$digit2[$didit2_chk]; 
				$bathtext1.=''; 
			}elseif($cut_input_number==2 && $didit2_chk==1){ // ถ้าเลข 2 อยู่หลักสิบ
				$bathtext1.='ยี่'.$digit2[$didit2_chk]; 
			}elseif($cut_input_number==1 && $didit2_chk==1){ // ถ้าเลข 1 อยู่หลักสิบ
				$bathtext1.= ''."".$digit2[$didit2_chk]; 
			}elseif($cut_input_number==1 && $didit2_chk==0){ // ถ้าเลข 1 อยู่หลักหน่วย
				if(substr($num0,$i-1,1)==0){
					$bathtext1.= 'หนึ่ง'.$digit2[$didit2_chk];
				}else{
					$bathtext1.= 'เอ็ด'.$digit2[$didit2_chk];
				} 
			}else{
				$bathtext1.= $digit[$cut_input_number].$digit2[$didit2_chk];
			}
			$didit2_chk=$didit2_chk-1;
		}
		$bathtext1.='บาท';
		//$bathtext1.='Bath ';
		// เลขทศนิยม
		$didit2_chk=strlen($num1)-1;
		$satang = false;
		for($i=0;$i<=strlen($num1)-1;$i++){
			$cut_input_number=substr($num1,$i,1);
			if($cut_input_number==0){ // ถ้าเลข 0 ไม่ต้องใส่ค่าอะไร
			
			}elseif($cut_input_number==2 && $didit2_chk==1){ // ถ้าเลข 2 อยู่หลักสิบ
				$bathtext1.='ยี่'.$digit2[$didit2_chk]; 
				//$bathtext1.='yee'."".$digit2[$didit2_chk]; 
				$satang = true;
			}elseif($cut_input_number==1 && $didit2_chk==1){ // ถ้าเลข 1 อยู่หลักสิบ
				$bathtext1.= $digit2[$didit2_chk];
				$satang = true;
			}elseif($cut_input_number==1 && $didit2_chk==0){ // ถ้าเลข 1 อยู่หลักหน่วย
				if(substr($num1,$i-1,1)==0){
				$bathtext1.= 'หนึ่ง'.$digit2[$didit2_chk];
				$satang = true;
				//$bathtext1.= 'neung '."".$digit2[$didit2_chk];
			}else{
				$bathtext1.= 'เอ็ด'.$digit2[$didit2_chk];
				//$bathtext1.= 'et '."".$digit2[$didit2_chk];
			} 
			$satang = true;
		}else{
			$bathtext1.= $digit[$cut_input_number].$digit2[$didit2_chk];
			$satang = true;
		}
		$didit2_chk=$didit2_chk-1;
		}
		if($satang){$bathtext1 .='สตางค์';}else{$bathtext1 .= 'ถ้วน';}
		
		return $bathtext1;
	}

	function dateRange($first, $last, $step = '+1 day', $output_format = 'd-m-Y') {
		 $dates = array();
		 $current = strtotime($first);
		 $last = strtotime($last);
		 while( $current <= $last ) {
			  //$dates[date('n',$current)][date('d-m-Y',$current)] = array(substr(date('l',$current),0,2), date($output_format, $current), date('w',$current));
			  $dates[] = date($output_format, $current);
			  $current = strtotime($step, $current);
		 }
		 return $dates;
	}


	// CHECK ... /////////////////////////////////////////////////////////////////////
	function checkSetupData($cid){
		global $dbc;
		global $lng;
		$msg = '';
		$check = '';
		$msg = checkCompanySettings($cid);
		//$msg .= checkSystemSettings($cid);
		if(!empty($msg)){$check .= '<i class="fa fa-arrow-circle-right"></i>&nbsp; <b><a href="'.ROOT.'settings/index.php?mn=6010">'.$lng['Company settings'].'</a></b><br>'.$msg.'<div style="height:5px"></div>';}
		
		$msg = checkEntitySettings($cid);
		if(!empty($msg)){$check .= '<i class="fa fa-arrow-circle-right"></i>&nbsp; <b><a href="'.ROOT.'settings/index.php?mn=6011&id=1">'.'Entity settings'.'</a></b><br>'.$msg.'<div style="height:5px"></div>';}
		return $check;
	}
	
	function checkCompanySettings($cid){
		global $dbc;
		global $lng;
		$msg = '';
		$caret = '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <i class="fa fa-caret-right"></i>&nbsp ';
		if($res = $dbc->query("SELECT * FROM ".$cid."_company_settings")){
			if($row = $res->fetch_assoc()){
				if(empty($row['th_compname'])){$msg .= $caret.$lng['Company name in Thai'].'<br>';}
				if(empty($row['en_compname'])){$msg .= $caret.$lng['Company name in English'].'<br>';}
				if(empty($row['tax_id'])){$msg .= $caret.$lng['Company Tax ID'].'<br>';}
			}
		}
		return $msg;
	}
		
	function checkEntitySettings($cid){
		global $dbc;
		global $lng;
		$msg = '';
		$caret = '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <i class="fa fa-caret-right"></i>&nbsp ';
		if($res = $dbc->query("SELECT * FROM ".$cid."_entities_data WHERE ref = 1")){
			if($row = $res->fetch_assoc()){
				if(empty($row['th_compname'])){$msg .= $caret.$lng['Company name in Thai'].'<br>';}
				if(empty($row['en_compname'])){$msg .= $caret.$lng['Company name in English'].'<br>';}
				if(empty($row['tax_id'])){$msg .= $caret.$lng['Company Tax ID'].'<br>';}
				if(empty($row['revenu_branch'])){$msg .= $caret.'Company Revenu branch'.'<br>';}
				if(empty($row['sso_account'])){$msg .= $caret.'Company SSO account'.'<br>';}
				if(empty($row['th_address'])){$msg .= $caret.'Address in Thai (PDF Forms)'.'<br>';}
				$addr = unserialize($row['th_addr_detail']);
				if(empty($addr['number'])){$msg .=  $caret.$lng['Address details'].' - '.$lng['Number'].'<br>';}
				if(empty($addr['moo'])){$msg .= $caret.$lng['Address details'].' - '.$lng['Moo'].'<br>';}
				//if(empty($addr['lane'])){$msg .= $caret.'Thai address detail - Lane<br>';}
				//if(empty($addr['subdistrict'])){$msg .= $caret.$lng['Address details'].' - '.$lng['Sub district'].'<br>';}
				if(empty($addr['district'])){$msg .= $caret.$lng['Address details'].' - '.$lng['District'].'<br>';}
				if(empty($addr['province'])){$msg .= $caret.$lng['Address details'].' - '.$lng['Province'].'<br>';}
				if(empty($addr['postal'])){$msg .= $caret.$lng['Address details'].' - '.$lng['Postal code'].'<br>';}
				$tmp = unserialize($row['sso_codes']);
				if(empty($tmp[1]['code'])){$msg .=  $caret.'SSO Code<br>';}
				if(empty($tmp[1]['th'])){$msg .= $caret.'SSO Name Thai<br>';}
				if(empty($tmp[1]['line1_th'])){$msg .= $caret.'SSO address Thai<br>';}
				if(empty($tmp[1]['line2_th'])){$msg .= $caret.'SSO District & Province Thai<br>';}
				if(empty($tmp[1]['postal_th'])){$msg .= $caret.'SSO Postal code Thai<br>';}
				if(empty($row['banks'])){$msg .= $caret.'No bank selected'.'<br>';}
			}
		}
		return $msg;
	}
	
	function checkPayrollSettings($cid){
		global $dbc;
		global $lng;
		$msg = '';
		$caret = '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <i class="fa fa-caret-right"></i>&nbsp ';
		if($res = $dbc->query("SELECT * FROM ".$cid."_settings")){
			if($row = $res->fetch_assoc()){
				//if(empty($row['tax_idnr'])){$msg .= $caret.$lng['Company Tax ID'].'<br>';}
				//if(empty($row['sso_idnr'])){$msg .= $caret.$lng['Social Security Fund'].'<br>';}
				//$addr = unserialize($row['th_addr_detail']);
				//if(empty($row['pr_startdate'])){$msg .= $caret.$lng['Payroll startdate'].'<br>';}
				//if(empty($addr['number'])){$msg .=  $caret.$lng['Address details'].' - '.$lng['Number'].'<br>';}
				//if(empty($addr['moo'])){$msg .= $caret.$lng['Address details'].' - '.$lng['Moo'].'<br>';}
				//if(empty($addr['lane'])){$msg .= $caret.'Thai address detail - Lane<br>';}
				//if(empty($addr['subdistrict'])){$msg .= $caret.$lng['Address details'].' - '.$lng['Sub district'].'<br>';}
				//if(empty($addr['district'])){$msg .= $caret.$lng['Address details'].' - '.$lng['District'].'<br>';}
				//if(empty($addr['province'])){$msg .= $caret.$lng['Address details'].' - '.$lng['Province'].'<br>';}
				//if(empty($addr['postal'])){$msg .= $caret.$lng['Address details'].' - '.$lng['Postal code'].'<br>';}
			}
		}
		return $msg;
	}
	
	function checkEmployeesForPayroll($cid){
		global $dbc;
		global $lng;
		$msg = '';
		$caret = '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <i class="fa fa-caret-right"></i>&nbsp ';
		//if($res = $dbc->query("SELECT emp_id, title, first_name, last_name, base_salary, idcard_nr, startdate FROM ".$cid."_employees WHERE pr_calculation = 'Y' AND emp_status = '1'")){
		
		$sql = "SELECT emp_id, title, firstname, en_name, th_name, lastname, base_salary, joining_date, emp_status, team FROM ".$cid."_employees WHERE emp_status = '1' ORDER BY emp_id ASC";
		
		if($res = $dbc->query($sql)){
			if($res->num_rows > 0){
				while($row = $res->fetch_assoc()){
					$missing = array();
					$name = $row[$_SESSION['rego']['lang'].'_name'];
					if($_SESSION['rego']['lang'] == 'en'){
						if(empty($name)){$name = 'no Name';}
					}else{
						if(empty($name)){$name = 'ไม่มีชื่อ';}
					}
					if(empty($row['title'])){
						$missing[$row['emp_id']][] = $lng['Title'];
					}
					if(empty($row['firstname'])){
						$missing[$row['emp_id']][] = $lng['First name'];
					}
					if(empty($row['lastname'])){
						$missing[$row['emp_id']][] = $lng['Last name'];
					}
					if(empty($row['joining_date'])){
						$missing[$row['emp_id']][] = $lng['Joining date'];
					}
					if(empty($row['base_salary'])){
						$missing[$row['emp_id']][] = $lng['Basic salary'];
					}
					if(empty($row['team'])){
						$missing[$row['emp_id']][] ='Team';// $lng['Team'];
					}
					if($missing){
						$msg .= '<b>&nbsp; <i class="fa fa-arrow-circle-right"></i>&nbsp; '.$row['emp_id'].' - <a href="employees/index.php?mn=1021&id='.$row['emp_id'].'#personal">'.$name.'</a></b><br>';
						foreach($missing[$row['emp_id']] as $k=>$v){
							$msg .= $caret.$v.'<br>';
						}
					}
				}
			}else{
				return '<b><i class="fa fa-exclamation-circle"></i>&nbsp;&nbsp;'.$lng['Employee register is empty'].'</b><br>';
			}
		}
		return $msg;
	}

	function checkExpiryDates(){
		global $cid;
		global $dbc;
		global $lng;
		$msg = '';
		$caret = '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <i class="fa fa-caret-right"></i>&nbsp ';
		
		$sql = "SELECT * FROM ".$cid."_employees WHERE emp_status = 1 ORDER BY emp_id ASC";
		
		if($res = $dbc->query($sql)){
			if($res->num_rows > 0){
				while($row = $res->fetch_assoc()){
					$missing = array();
					$name = $row[$_SESSION['rego']['lang'].'_name'];
					$today = strtotime(date('Y-m-d', strtotime('+15day', strtotime(date('d-m-Y')))));
					
					if(!empty($row['idcard_exp'])){
						if(strtotime($row['idcard_exp']) < $today){
							$missing[$row['emp_id']][] = $lng['ID card expire'].' : '.$row['idcard_exp'];
						}
					}
					if(!empty($row['drvlicense_exp'])){
						if(strtotime($row['drvlicense_exp']) < $today){
							$missing[$row['emp_id']][] = $lng['Driver licence expire'].' : '.$row['drvlicense_exp'];
						}
					}
					
					if($missing){
						$msg .= '<b>&nbsp; <i class="fa fa-arrow-circle-right"></i>&nbsp; '.$row['emp_id'].' - <a href="employees/index.php?mn=1021&id='.$row['emp_id'].'#personal">'.$name.'</a></b><br>';
						foreach($missing[$row['emp_id']] as $k=>$v){
							$msg .= $caret.$v.'<br>';
						}
					}
				}
			}else{
				return '<b><i class="fa fa-exclamation-circle"></i>&nbsp;&nbsp;'.$lng['No warnings or expiry dates'].'</b>';
			}
		}
		return $msg;
	}

	function updateEmployeesForPayroll($cid){
		global $dbc;
		$pr_stat = array();
		$sql = "SELECT emp_id, title, firstname, lastname, th_name, base_salary, idcard_nr, startdate, emp_status FROM ".$cid."_employees";
		if($res = $dbc->query($sql)){
			if($res->num_rows > 0){
				while($row = $res->fetch_assoc()){
					if($row['emp_status'] == '' || 
						$row['title'] == '' || 
						$row['firstname'] == '' || 
						$row['lastname'] == '' || 
						//$row['idcard_nr'] == '' || 
						$row['base_salary'] == 0 || 
						$row['startdate'] == ''){
						$pr_stat[$row['emp_id']] = 1;
					}else{
						$pr_stat[$row['emp_id']] = 0;
					}
				}
			}
		}
		if($pr_stat){
			foreach($pr_stat as $k=>$v){
				$dbc->query("UPDATE ".$cid."_employees SET pr_status = '".$v."' WHERE emp_id = '".$k."'");
			}
		}
	}
	
	
	// EMPLOYEES /////////////////////////////////////////////////////////////////////

	function getSystemUsers($team){
		global $dbc;
		$sql = "SELECT id, name FROM ".$_SESSION['rego']['cid']."_users WHERE type in ('sys' ,'app','appr') AND teams LIKE'%".$team."%'";

		// echo $sql;
		// die();
		if($res = $dbc->query($sql)){
			while($row = $res->fetch_assoc()){
				$users[$row['id']] = $row['name'];
			}
		}
		return $users;
	}
	function getSystemUser($id){
		global $dbc;
		$sql = "SELECT name, username FROM ".$_SESSION['rego']['cid']."_users WHERE id = '$id'";
		if($res = $dbc->query($sql)){
			if($row = $res->fetch_assoc()){
				$user = array($row['username'], $row['name']);
			}
		}
		return $user;
	}

	function getEmployeeColumns(){
		global $dbc;
		$cols = array();
		$sql = "SELECT * FROM ".$_SESSION['rego']['cid']."_users WHERE user_id = '".$_SESSION['rego']['emp_id']."'";
		if($res = $dbc->query($sql)){
			if($row = $res->fetch_assoc()){
				$cols = unserialize($row['emp_cols']);
			}
		}
		if(empty($cols)){$cols = array(4,5,6,7,8,12,13,14,15);}
		return $cols;
	}

	function getEmployeeStatus($cid){
		global $dbc;
		$data = array(1=>0,2=>0,3=>0,0=>0,7=>0);
		$sql = "SELECT emp_id, title, emp_status, idcard_nr, base_salary, joining_date, en_name, firstname, lastname FROM ".$cid."_employees WHERE emp_group = 's'";
		if($res = $dbc->query($sql)){
			while($row = $res->fetch_assoc()){
				if($row['title'] == '' ||
					$row['firstname'] == '' || 
					$row['lastname'] == '' || 
					$row['joining_date'] == '' || 
					$row['base_salary'] == 0){ 
					$data[7] += 1;
				}
				$data[$row['emp_status']] += 1;
			}
		}
		return $data;

	}	function getEmployeeStatusTeam($cid,$team){
		global $dbc;
		$data = array(1=>0,2=>0,3=>0,0=>0,7=>0);
		$sql = "SELECT emp_id, title, emp_status, idcard_nr, base_salary, joining_date, en_name, firstname, lastname FROM ".$cid."_employees WHERE emp_group = 's' AND team IN(".$team.")";
		if($res = $dbc->query($sql)){
			while($row = $res->fetch_assoc()){
				if($row['title'] == '' ||
					$row['firstname'] == '' || 
					$row['lastname'] == '' || 
					$row['joining_date'] == '' || 
					$row['base_salary'] == 0){ 
					$data[7] += 1;
				}
				$data[$row['emp_status']] += 1;
			}
		}
		return $data;
	}

	function getEmployees($cid, $entity){
		global $dbc;
		//global $departments;
		global $positions;
		//$positions[0] = '';
		//$departments = getDepartmentNames($cid, $_SESSION['rego']['lang']);
		$data = array();
		
		$where = '';
		if($entity > 0){
			$sentities = str_replace(',', "','", $entity);
			$where = "WHERE emp_group = '".$_SESSION['rego']['emp_group']."' AND entity IN ('".$sentities."')";
		}
		
		$res = $dbc->query("SELECT * FROM ".$cid."_employees $where ORDER BY emp_id ASC");
		if($res->num_rows > 0){
			while($row = $res->fetch_assoc()){
				$data[$row['emp_id']]['emp_id'] = $row['emp_id'];
				$data[$row['emp_id']]['sid'] = $row['sid'];
				$data[$row['emp_id']]['title'] = $row['title'];
				$data[$row['emp_id']]['firstname'] = $row['firstname'];
				$data[$row['emp_id']]['lastname'] = $row['lastname'];
				$data[$row['emp_id']]['th_name'] = $row['th_name'];
				$data[$row['emp_id']]['en_name'] = $row['en_name'];
				
				$data[$row['emp_id']]['entity'] = $row['entity'];
				$data[$row['emp_id']]['branch'] = $row['branch'];
				$data[$row['emp_id']]['division'] = $row['division'];
				$data[$row['emp_id']]['department'] = $row['department'];
				$data[$row['emp_id']]['team'] = $row['team'];
				$data[$row['emp_id']]['emp_group'] = $row['emp_group'];
				$data[$row['emp_id']]['position'] = $row['position'];
				$data[$row['emp_id']]['phone'] = $row['personal_phone'];
				$data[$row['emp_id']]['email'] = $row['personal_email'];
				$data[$row['emp_id']]['idcard_nr'] = $row['idcard_nr'];
				$data[$row['emp_id']]['emp_status'] = $row['emp_status'];
				if(!empty($row['image'])){
					$data[$row['emp_id']]['image'] = $row['image'];
				}else{
					$data[$row['emp_id']]['image'] = 'images/profile_image.jpg';
				}
				$data[$row['emp_id']]['bank'] = $row['bank_code'];
				$data[$row['emp_id']]['account'] = $row['bank_account'];
				$data[$row['emp_id']]['shiftplan'] = $row['shiftplan'];
				$data[$row['emp_id']]['contract_type'] = $row['contract_type'];
				$data[$row['emp_id']]['calc_base'] = $row['calc_base'];
				$data[$row['emp_id']]['pay_type'] = $row['pay_type'];
			}
		}
		return $data;
	}

	function getActiveEmployees(){
		global $dbc;
		global $cid;
		global $positions;
		//$positions[0] = '';
		//$departments = getDepartmentNames($cid, $_SESSION['rego']['lang']);
		$data = array();
		$res = $dbc->query("SELECT * FROM ".$cid."_employees WHERE emp_status = 1 ORDER BY emp_id ASC");
		if($res->num_rows > 0){
			while($row = $res->fetch_assoc()){
				$data[$row['emp_id']]['emp_id'] = $row['emp_id'];
				$data[$row['emp_id']]['sid'] = $row['sid'];
				$data[$row['emp_id']]['title'] = $row['title'];
				$data[$row['emp_id']]['firstname'] = $row['firstname'];
				$data[$row['emp_id']]['lastname'] = $row['lastname'];
				$data[$row['emp_id']]['th_name'] = $row['th_name'];
				$data[$row['emp_id']]['en_name'] = $row['en_name'];
				if($row['position'] > 0){
					$data[$row['emp_id']]['position'] = $positions[$row['position']];
				}else{
					$data[$row['emp_id']]['position'] = '';
				}
				$data[$row['emp_id']]['phone'] = $row['personal_phone'];
				$data[$row['emp_id']]['email'] = $row['personal_email'];
				$data[$row['emp_id']]['idcard_nr'] = $row['idcard_nr'];
				$data[$row['emp_id']]['emp_status'] = $row['emp_status'];
				if(!empty($row['image'])){
					$data[$row['emp_id']]['image'] = $row['image'];
				}else{
					$data[$row['emp_id']]['image'] = 'images/profile_image.jpg';
				}
				$data[$row['emp_id']]['bank'] = $row['bank_code'];
				$data[$row['emp_id']]['account'] = $row['bank_account'];
				$data[$row['emp_id']]['shiftplan'] = $row['shiftplan'];
			}
		}
		return $data;
	}

		function getActiveInactiveEmployees(){
		global $dbc;
		global $cid;
		global $positions;
		//$positions[0] = '';
		//$departments = getDepartmentNames($cid, $_SESSION['rego']['lang']);
		$data = array();
		$res = $dbc->query("SELECT * FROM ".$cid."_employees ORDER BY emp_id ASC");
		if($res->num_rows > 0){
			while($row = $res->fetch_assoc()){
				$data[$row['emp_id']]['emp_id'] = $row['emp_id'];
				$data[$row['emp_id']]['sid'] = $row['sid'];
				$data[$row['emp_id']]['title'] = $row['title'];
				$data[$row['emp_id']]['firstname'] = $row['firstname'];
				$data[$row['emp_id']]['lastname'] = $row['lastname'];
				$data[$row['emp_id']]['th_name'] = $row['th_name'];
				$data[$row['emp_id']]['en_name'] = $row['en_name'];
				if($row['position'] > 0){
					$data[$row['emp_id']]['position'] = $positions[$row['position']];
				}else{
					$data[$row['emp_id']]['position'] = '';
				}
				$data[$row['emp_id']]['phone'] = $row['personal_phone'];
				$data[$row['emp_id']]['email'] = $row['personal_email'];
				$data[$row['emp_id']]['idcard_nr'] = $row['idcard_nr'];
				$data[$row['emp_id']]['emp_status'] = $row['emp_status'];
				if(!empty($row['image'])){
					$data[$row['emp_id']]['image'] = $row['image'];
				}else{
					$data[$row['emp_id']]['image'] = 'images/profile_image.jpg';
				}
				$data[$row['emp_id']]['bank'] = $row['bank_code'];
				$data[$row['emp_id']]['account'] = $row['bank_account'];
				$data[$row['emp_id']]['shiftplan'] = $row['shiftplan'];
				$data[$row['emp_id']]['teams'] = $row['teams'];
			}
		}
		return $data;
	}

	function getTimeEmployees(){
		global $dbc;
		global $cid;
		global $positions;
		//$positions[0] = '';
		//$departments = getDepartmentNames($cid, $_SESSION['rego']['lang']);
		$data = array();
		$res = $dbc->query("SELECT * FROM ".$cid."_employees WHERE emp_status = 1 AND shiftplan != '' ORDER BY emp_id ASC");
		if($res->num_rows > 0){
			while($row = $res->fetch_assoc()){
				$data[$row['emp_id']]['emp_id'] = $row['emp_id'];
				$data[$row['emp_id']]['sid'] = $row['sid'];
				$data[$row['emp_id']]['title'] = $row['title'];
				$data[$row['emp_id']]['firstname'] = $row['firstname'];
				$data[$row['emp_id']]['lastname'] = $row['lastname'];
				$data[$row['emp_id']]['th_name'] = $row['th_name'];
				$data[$row['emp_id']]['en_name'] = $row['en_name'];
				if($row['position'] > 0){
					$data[$row['emp_id']]['position'] = $positions[$row['position']];
				}else{
					$data[$row['emp_id']]['position'] = '';
				}
				$data[$row['emp_id']]['phone'] = $row['personal_phone'];
				$data[$row['emp_id']]['email'] = $row['personal_email'];
				$data[$row['emp_id']]['idcard_nr'] = $row['idcard_nr'];
				$data[$row['emp_id']]['emp_status'] = $row['emp_status'];
				if(!empty($row['image'])){
					$data[$row['emp_id']]['image'] = $row['image'];
				}else{
					$data[$row['emp_id']]['image'] = 'images/profile_image.jpg';
				}
				$data[$row['emp_id']]['bank'] = $row['bank_code'];
				$data[$row['emp_id']]['account'] = $row['bank_account'];
				$data[$row['emp_id']]['shiftplan'] = $row['shiftplan'];
				$data[$row['emp_id']]['teams'] = $row['teams'];
			}
		}
		return $data;
	}	

	function getTimeEmployeesCOpy(){
		global $dbc;
		global $cid;
		global $positions;
		//$positions[0] = '';
		//$departments = getDepartmentNames($cid, $_SESSION['rego']['lang']);
		$data = array();
		$res = $dbc->query("SELECT * FROM ".$cid."_employees WHERE emp_status = 1 AND teams != '' ORDER BY emp_id ASC");
		if($res->num_rows > 0){
			while($row = $res->fetch_assoc()){
				$data[$row['emp_id']]['emp_id'] = $row['emp_id'];
				$data[$row['emp_id']]['sid'] = $row['sid'];
				$data[$row['emp_id']]['title'] = $row['title'];
				$data[$row['emp_id']]['firstname'] = $row['firstname'];
				$data[$row['emp_id']]['lastname'] = $row['lastname'];
				$data[$row['emp_id']]['th_name'] = $row['th_name'];
				$data[$row['emp_id']]['en_name'] = $row['en_name'];
				if($row['position'] > 0){
					$data[$row['emp_id']]['position'] = $positions[$row['position']];
				}else{
					$data[$row['emp_id']]['position'] = '';
				}
				$data[$row['emp_id']]['phone'] = $row['personal_phone'];
				$data[$row['emp_id']]['email'] = $row['personal_email'];
				$data[$row['emp_id']]['idcard_nr'] = $row['idcard_nr'];
				$data[$row['emp_id']]['emp_status'] = $row['emp_status'];
				if(!empty($row['image'])){
					$data[$row['emp_id']]['image'] = $row['image'];
				}else{
					$data[$row['emp_id']]['image'] = 'images/profile_image.jpg';
				}
				$data[$row['emp_id']]['bank'] = $row['bank_code'];
				$data[$row['emp_id']]['account'] = $row['bank_account'];
				$data[$row['emp_id']]['shiftplan'] = $row['shiftplan'];
				$data[$row['emp_id']]['teams'] = $row['teams'];
				$data[$row['emp_id']]['time_reg'] = $row['time_reg'];
			}
		}
		return $data;
	}
	

	function getEmployeeImg($id){
		global $cid;
		global $dbc;
		$image = '';
		if($res = $dbc->query("SELECT image FROM ".$cid."_employees WHERE emp_id = '".$id."'")){
			if($row = $res->fetch_assoc()){
				$image = $row['image'];
			}
		}
		if(empty($image)){$image = 'images/profile_image.jpg';}
		return $image;
	}

	function getEmployeeWageType($id){
		global $cid;
		global $dbc;
		$data = 'month';
		if($res = $dbc->query("SELECT wage_type FROM ".$cid."_employees WHERE emp_id = '".$id."'")){
			if($row = $res->fetch_assoc()){
				$data = $row['wage_type'];
			}
		}
		return $data;
	}

	function getEmployeeInfo($cid, $id){
		global $dbc;
		$data = array();
		$sql = "SELECT * FROM ".$cid."_employees WHERE emp_id = '".$id."'";
		if($res = $dbc->query($sql)){
			if($row = $res->fetch_assoc()){
				$data = $row;
			}
		}
		return $data;
	}
	function getEmployeeName($id){
		global $dbc;
		global $cid;
		$data = array();
		$sql = "SELECT emp_id, th_name, en_name FROM ".$cid."_employees WHERE sid = '".$id."'";
		if($res = $dbc->query($sql)){
			if($row = $res->fetch_assoc()){
				$data = $row;
			}
		}
		return $data;
	}

	function getEmpinfo($db_employee, $id){
		global $dbc;
		//global $db_employee;
		//return $db_employee; exit;
		$data = array();	
		$sql = "(SELECT * FROM $db_employee WHERE emp_id = '".$id."')";
		if($res = $dbc->query($sql)){
			if($row = $res->fetch_assoc()){
				$data = $row;
			}
		}
		return $data;
	}

	function getJsonUserEmployees($cid, $lang){
		global $dbc;
		$data = array();
		$sql = "SELECT emp_id, ".$lang."_name FROM ".$cid."_employees WHERE emp_status = 1 OR emp_status = 5 ORDER BY emp_id ASC";
		if($res = $dbc->query($sql)){
			if($res->num_rows > 0){
				while($row = $res->fetch_assoc()){
					$data[] = array('data'=>$row['emp_id'], 'value'=>$row[$lang.'_name']);
				}
			}
		}
		return $data;
		//return mysqli_error($dbc);
	}


	

	function getJsonIdsEmployees(){
		global $dbc;
		global $cid;
		global $lang;
		$data = array();
		$sql = "SELECT emp_id, ".$lang."_name, personal_phone, image FROM ".$cid."_employees WHERE emp_status = 1 ORDER BY emp_id ASC";
		if($res = $dbc->query($sql)){
			if($res->num_rows > 0){
				while($row = $res->fetch_assoc()){
					$image = $row['image'];
					if(empty($row['image'])){$image = 'images/profile_image.jpg';}
					$data[] = array('data'=>$row['emp_id'], 'value'=>$row['emp_id'].' - '.$row[$lang.'_name'], 'name'=>$row[$lang.'_name'], 'phone'=>$row['personal_phone'], 'image'=>$image);
				}
			}
		}
		return $data;
		//return mysqli_error($dbc);
	}

	function getEmployeesByBank($cid, $id, $bank, $filter){
		global $dbc;
		if($filter == 'other'){
			$where = " AND bank_code != '".$bank."'";
		}else if($filter == 'all'){
			$where = "";
		}else{
			$where = " AND bank_code = '".$bank."'";
		}
		$data = array();
		$sql = "SELECT emp_id, en_name, th_name, title, bank_code, bank_account, bank_branch, bank_account_name FROM ".$cid."_employees WHERE emp_id = '".$id."' AND bank_code != '' AND bank_account != '' ".$where;
		if($res = $dbc->query($sql)){
			if($row = $res->fetch_assoc()){
				$data = $row;
			}
		}
		return $data;
	}
	
	
	// DEDUCTIONS /////////////////////////////////////////////////////////////////////
	function getUsedFixDeduct($lang){
		global $sys_settings;
		$deduct = unserialize($sys_settings['fix_deduct']);
		$data = array();
		foreach($deduct as $k=>$v){
			if($v['apply'] == 1){
				$data[$k] = $v[$lang];
			}
		}
		return $data;
	}	

	function getUsedVarDeduct($lang){
		global $sys_settings;
		$deduct = unserialize($sys_settings['var_deduct']);
		$data = array();
		foreach($deduct as $k=>$v){
			if($v['apply'] == 1){
				$data[$k] = $v[$lang];
			}
		}
		return $data;
	}	

	function getFixDeductNames(){
		global $sys_settings;
		$deduct = unserialize($sys_settings['fix_deduct']);
		$data = array();
		foreach($deduct as $k=>$v){
			$data[$k] = $v[$_SESSION['rego']['lang']];
		}
		return $data;
	}	
	function getVarDeductNames(){
		global $sys_settings;
		$deduct = unserialize($sys_settings['var_deduct']);
		$data = array();
		foreach($deduct as $k=>$v){
			$data[$k] = $v[$_SESSION['rego']['lang']];
		}
		return $data;
	}	
	
	
	// ALLOWANCES /////////////////////////////////////////////////////////////////////
	function getUsedVarAllow($lang){
		global $sys_settings;
		$allow = unserialize($sys_settings['var_allow']);
		$data = array();
		if($lang == 'both'){
			foreach($allow as $k=>$v){
				if($v['apply'] == 1){
					$data[$k]['en'] = $v['en'];
					$data[$k]['th'] = $v['th'];
				}
			}
		}else{
			foreach($allow as $k=>$v){
				if($v['apply'] == 1){
					$data[$k] = $v[$lang];
				}
			}
		}
		return $data;
	}

	function getUsedFixDeduction($lang){
		global $sys_settings;
		$allow = unserialize($sys_settings['fix_deduct']);
		$data = array();
		foreach($allow as $k=>$v){
			if($v['apply'] == 1){
				$data[$k] = $v[$lang];
			}
		}
		return $data;
	}

	function getUsedVarDeduction($lang){
		global $sys_settings;
		$allow = unserialize($sys_settings['var_deduct']);
		$data = array();
		foreach($allow as $k=>$v){
			if($v['apply'] == 1){
				$data[$k] = $v[$lang];
			}
		}
		return $data;
	}



	function getUsedFixAllow($lang){
		global $sys_settings;
		$allow = unserialize($sys_settings['fix_allow']);
		$data = array();
		foreach($allow as $k=>$v){
			if($v['apply'] == 1){
				$data[$k] = $v[$lang];
			}
		}
		return $data;
	}	

	function getFixAllowances(){
		global $sys_settings;
		$tmp = unserialize($sys_settings['fix_allow']);
		$data = array();
		foreach($tmp as $k=>$v){
			if($v['apply'] == 1){
				$data[$k] = $v;
			}
		}
		return $data;
	}	
	
	function getFixAllowNames(){
		global $sys_settings;
		$allow = unserialize($sys_settings['fix_allow']);
		$data = array();
		foreach($allow as $k=>$v){
			$data[$k] = $v[$_SESSION['rego']['lang']];
		}
		return $data;
	}	
	function getVarAllowNames(){
		global $sys_settings;
		$allow = unserialize($sys_settings['var_allow']);
		$data = array();
		foreach($allow as $k=>$v){
			$data[$k] = $v[$_SESSION['rego']['lang']];
		}
		return $data;
	}	

	function getVarAllowances(){
		global $sys_settings;
		$tmp = unserialize($sys_settings['var_allow']);
		$data = array();
		foreach($tmp as $k=>$v){
			if($v['apply'] == 1){
				$data[$k] = $v;
			}
		}
		return $data;
	}	

	// function isValidDate($date, $format = 'H:i') {
	// 	if($date == '-'){return false;}
	// 	$d = DateTime::createFromFormat($format, $date);
	// 	return $d && $d->format($format) == $date;
	// }

	// function decimalHours($time){
	// 	if(!empty($time)){
	// 		$hms = explode(":", $time);
	// 		if(isset($hms[1])){
	// 			return ($hms[0] + ($hms[1]/60));// + ($hms[2]/3600));
	// 		}else{
	// 			return $hms[0];
	// 		}
	// 	}
	// }
	
	// function addHours($hours,$add){
	// 	$a = new DateTime($hours);
	// 	$aa = new DateInterval("P0000-00-00T$add:00");
	// 	$a->add($aa);
	// 	$h = (int)$a->format('H');
	// 	return $h.$a->format(':i');
	// }
	
	// function intHours($time){
	// 	if(!empty($time)){
	// 		$hms = explode(":", $time);
	// 		return (int)$hms[0].':'.$hms[1];
	// 	}
	// }
	
	// function dateHours($number){
	// 	if(!empty($number)){
	// 		$number = number_format($number,20);
	// 		$tmp = explode(".", $number);
	// 		$deci = '0.'.$tmp[1];
	// 		$deci = $deci*60;
	// 		$deci = number_format(((float)$deci),2);
	// 		return ($tmp[0].':'.sprintf("%02d",$deci));
	// 	}else{
	// 		return '-';
	// 	}
	// }

	// function dateHoursEmpty($number){
	// 	if(!empty($number)){
	// 		$number = number_format($number,20);
	// 		$tmp = explode(".", $number);
	// 		$deci = '0.'.$tmp[1];
	// 		$deci = $deci*60;
	// 		$deci = number_format(((float)$deci),2);
	// 		return ($tmp[0].':'.sprintf("%02d",$deci));
	// 	}else{
	// 		return '';
	// 	}
	// }


		function getTimeSelections($entity){
		global $teams;
		foreach($teams as $k=>$v){
			if($v['entity'] == $entity){
				$branch[$v['branch']] = $v['branch'];
				$division[$v['division']] = $v['division'];
				$department[$v['department']] = $v['department'];
				$team[$k] = $k;
			}
		}



		$_SESSION['rego']['t_entities'] = $_SESSION['rego']['mn_entities'];
		$_SESSION['rego']['t_branches'] = $_SESSION['rego']['mn_branches'];
		$_SESSION['rego']['t_divisions'] = $_SESSION['rego']['mn_divisions'];
		$_SESSION['rego']['t_departments'] = $_SESSION['rego']['mn_departments'];
		$_SESSION['rego']['t_teams'] = $_SESSION['rego']['mn_teams'];

		$_SESSION['rego']['selt_entities'] = $_SESSION['rego']['sel_entities'];
		$_SESSION['rego']['selt_branches'] = $_SESSION['rego']['sel_branches'];
		$_SESSION['rego']['selt_divisions'] = $_SESSION['rego']['sel_divisions'];
		$_SESSION['rego']['selt_departments'] = $_SESSION['rego']['sel_departments'];
		$_SESSION['rego']['selt_teams'] = $_SESSION['rego']['sel_teams'];

	}

	function encrypt_decrypt($string, $action = 'encrypt')
	{
	    $encrypt_method = "AES-256-CBC";
	    $secret_key = 'AA74CDCC2BBRT935136HH7B63C27'; // user define private key
	    $secret_iv = '5fgf5HJ5g27'; // user define secret key
	    $key = hash('sha256', $secret_key);
	    $iv = substr(hash('sha256', $secret_iv), 0, 16); // sha256 is hash_hmac_algo
	    if ($action == 'encrypt') {
	        $output = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
	        $output = base64_encode($output);
	    } else if ($action == 'decrypt') {
	        $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
	    }
	    return $output;
	}

	function everdayConsentCheckTerms($terms_consent_date, $days,$email,$terms_consent_change,$limitDay,$dropdownValue)
	{
		global $dbx;


		$terms_consent_date_value = $terms_consent_date; // m-d-Y format

		$formatDate = DateTime::createFromFormat("m-d-Y H:i:s" , $terms_consent_date_value);
		$formatDate->format('Y-m-d H:i:s');

		$hours = $days; // hours amount (integer) you want to add
		$modified = (clone $formatDate)->add(new DateInterval("PT{$hours}H")); // use clone to avoid modification of $now object
		$dateWith24Hours = $modified->format('Y-m-d H:i:s'); 

		$formatDate1 = DateTime::createFromFormat("m-d-Y H:i:s" , $terms_consent_date_value);
		$formattedDate1=  $formatDate1->format('Y-m-d H:i:s');

		$currentDate = date('Y-m-d H:i:s');

		$datetime1 = new DateTime($currentDate);
		$datetime2 = new DateTime($dateWith24Hours);

		$interval = $datetime1->diff($datetime2);
		$days2 = $interval->days ;

		if($dropdownValue == '1')
		{
			// if($days2 >= $limitDay)
			// {
				// reset the consent for terms 
				$dbx->query("UPDATE rego_all_users SET terms_consent = '0'  ,  terms_consent_change = '1'  WHERE username = '".$email."'");
			// }
		}
		else if($dropdownValue == '2')
		{



			if($days2 >= $limitDay)
			{
				// reset the consent for terms 
				$dbx->query("UPDATE rego_all_users SET terms_consent = '0'  ,  terms_consent_change = '0' WHERE username = '".$email."'");
			}

			else if($terms_consent_change == '1')
			{

				$dbx->query("UPDATE rego_all_users SET terms_consent = '0'  ,  terms_consent_change = '0' WHERE username = '".$email."'");
			}


		}		
		else if($dropdownValue == '3')
		{
			if($days2 >= $limitDay)
			{
				// reset the consent for terms 
				$dbx->query("UPDATE rego_all_users SET terms_consent = '0'  ,  terms_consent_change = '0' WHERE username = '".$email."'");
			}
			else if($terms_consent_change == '1')
			{
				$dbx->query("UPDATE rego_all_users SET terms_consent = '0'  ,  terms_consent_change = '0' WHERE username = '".$email."'");
			}
		}		
		else if($dropdownValue == '4')
		{
			if($days2 >= $limitDay)
			{
				// reset the consent for terms 
				$dbx->query("UPDATE rego_all_users SET terms_consent = '0'  ,  terms_consent_change = '0' WHERE username = '".$email."'");
			}
			else if($terms_consent_change == '1')
			{
				$dbx->query("UPDATE rego_all_users SET terms_consent = '0'  ,  terms_consent_change = '0' WHERE username = '".$email."'");
			}
		}		
		else if($dropdownValue == '5')
		{
			// reset the consent for terms 
			if($terms_consent_change == '1')
			{
				$dbx->query("UPDATE rego_all_users SET terms_consent = '0'  ,  terms_consent_change = '0' WHERE username = '".$email."'");
			}
		}
	}	


	function everdayConsentCheckPrivacy($terms_consent_date, $days,$email,$terms_consent_change,$limitDay,$dropdownValue)
	{
		global $dbx;
		

		$terms_consent_date_value = $terms_consent_date; // m-d-Y format

		$formatDate = DateTime::createFromFormat("m-d-Y H:i:s" , $terms_consent_date_value);
		$formatDate->format('Y-m-d H:i:s');

		$hours = $days; // hours amount (integer) you want to add
		$modified = (clone $formatDate)->add(new DateInterval("PT{$hours}H")); // use clone to avoid modification of $now object
		$dateWith24Hours = $modified->format('Y-m-d H:i:s'); 

		$formatDate1 = DateTime::createFromFormat("m-d-Y H:i:s" , $terms_consent_date_value);
		$formattedDate1=  $formatDate1->format('Y-m-d H:i:s');

		$currentDate = date('Y-m-d H:i:s');

		$datetime1 = new DateTime($currentDate);
		$datetime2 = new DateTime($dateWith24Hours);

		$interval = $datetime1->diff($datetime2);
		$days2 = $interval->days ;

		if($dropdownValue == '1')
		{
			// if($days2 >= $limitDay)
			// {
				// reset the consent for terms 
				$dbx->query("UPDATE rego_all_users SET privacy_consent = '0' , privacy_consent_change = '1' WHERE username = '".$email."'");
			// }
		}
		else if($dropdownValue == '2')
		{
			if($days2 >= $limitDay)
			{
				// reset the consent for terms 
				$dbx->query("UPDATE rego_all_users SET privacy_consent = '0'  ,  privacy_consent_change = '0' WHERE username = '".$email."'");
			}
			else if($terms_consent_change == '1')
			{
				$dbx->query("UPDATE rego_all_users SET privacy_consent = '0'  ,  privacy_consent_change = '0' WHERE username = '".$email."'");
			}
		}		
		else if($dropdownValue == '3')
		{
			if($days2 >= $limitDay)
			{
				// reset the consent for terms 
				$dbx->query("UPDATE rego_all_users SET privacy_consent = '0'  ,  privacy_consent_change = '0' WHERE username = '".$email."'");
			}
			else if($terms_consent_change == '1')
			{
				$dbx->query("UPDATE rego_all_users SET privacy_consent = '0'  ,  privacy_consent_change = '0' WHERE username = '".$email."'");
			}
		}		
		else if($dropdownValue == '4')
		{
			if($days2 >= $limitDay)
			{
				// reset the consent for terms 
				$dbx->query("UPDATE rego_all_users SET privacy_consent = '0'  ,  privacy_consent_change = '0' WHERE username = '".$email."'");			
			}
			else if($terms_consent_change == '1')
			{
				$dbx->query("UPDATE rego_all_users SET privacy_consent = '0'  ,  privacy_consent_change = '0' WHERE username = '".$email."'");
			}
		}		
		else if($dropdownValue == '5')
		{
			// reset the consent for terms 
			if($terms_consent_change == '1')
			{
				$dbx->query("UPDATE rego_all_users SET privacy_consent = '0'  ,  privacy_consent_change = '0' WHERE username = '".$email."'");
			}
		}
	}	

	function everdayConsentCheckCookie($terms_consent_date, $days,$email,$terms_consent_change,$limitDay,$dropdownValue)
	{
		global $dbx;
		

		$terms_consent_date_value = $terms_consent_date; // m-d-Y format

		$formatDate = DateTime::createFromFormat("m-d-Y H:i:s" , $terms_consent_date_value);
		$formatDate->format('Y-m-d H:i:s');

		$hours = $days; // hours amount (integer) you want to add
		$modified = (clone $formatDate)->add(new DateInterval("PT{$hours}H")); // use clone to avoid modification of $now object
		$dateWith24Hours = $modified->format('Y-m-d H:i:s'); 

		$formatDate1 = DateTime::createFromFormat("m-d-Y H:i:s" , $terms_consent_date_value);
		$formattedDate1=  $formatDate1->format('Y-m-d H:i:s');

		$currentDate = date('Y-m-d H:i:s');

		$datetime1 = new DateTime($currentDate);
		$datetime2 = new DateTime($dateWith24Hours);

		$interval = $datetime1->diff($datetime2);
		$days2 = $interval->days ;

		if($dropdownValue == '1')
		{
			// if($days2 >= $limitDay)
			// {
				// reset the consent for terms 
				$dbx->query("UPDATE rego_all_users SET cookie_consent = '0' , cookie_consent_change = '1' WHERE username = '".$email."'");
			// }
		}
		else if($dropdownValue == '2')
		{
			if($days2 >= $limitDay)
			{
				// reset the consent for terms 
				$dbx->query("UPDATE rego_all_users SET cookie_consent = '0'  ,  cookie_consent_change = '0' WHERE username = '".$email."'");
			}
			else if($terms_consent_change == '1')
			{
				$dbx->query("UPDATE rego_all_users SET cookie_consent = '0'  ,  cookie_consent_change = '0' WHERE username = '".$email."'");
			}
		}		
		else if($dropdownValue == '3')
		{
			if($days2 >= $limitDay)
			{
				// reset the consent for terms 
				$dbx->query("UPDATE rego_all_users SET cookie_consent = '0'  ,  cookie_consent_change = '0' WHERE username = '".$email."'");
			}
			else if($terms_consent_change == '1')
			{
				$dbx->query("UPDATE rego_all_users SET cookie_consent = '0'  ,  cookie_consent_change = '0' WHERE username = '".$email."'");
			}
		}		
		else if($dropdownValue == '4')
		{
			if($days2 >= $limitDay)
			{
				// reset the consent for terms 
				$dbx->query("UPDATE rego_all_users SET cookie_consent = '0'  ,  cookie_consent_change = '0' WHERE username = '".$email."'");	
			}
			else if($terms_consent_change == '1')
			{
				$dbx->query("UPDATE rego_all_users SET cookie_consent = '0'  ,  cookie_consent_change = '0' WHERE username = '".$email."'");
			}
		}		
		else if($dropdownValue == '5')
		{
			// reset the consent for terms 
			if($terms_consent_change == '1')
			{
				$dbx->query("UPDATE rego_all_users SET cookie_consent = '0'  ,  cookie_consent_change = '0' WHERE username = '".$email."'");
			}
		}
	}
 









