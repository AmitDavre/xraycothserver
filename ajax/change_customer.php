<?
	if(session_id()==''){session_start();}
	include("../dbconnect/db_connect.php");
	$_SESSION['rego']['cid'] = $_REQUEST['cid'];


	$sql = "SELECT * FROM rego_all_users WHERE LOWER(username) = '".$_SESSION['rego']['username']."'";
	if($res = $dbx->query($sql)){
		if($all_users = $res->fetch_assoc()){
		}
	}

		$_SESSION['rego']['timestamp'] = time();
		$_SESSION['rego']['cid'] = $all_users['emp_access'];
		$_SESSION['rego']['type'] = $all_users['type'];
		$_SESSION['rego']['emp_id'] = $all_users['emp_id'];
		$_SESSION['rego']['fname'] = $all_users['firstname'];
		$_SESSION['rego']['name'] = $all_users['firstname'].' '.$all_users['lastname'];
		$_SESSION['rego']['username'] = $all_users['username'];
		$_SESSION['rego']['img'] = $all_users['img'].'?'.time();



	$my_dbcname = $prefix.$_REQUEST['cid'];
	$dbcc = new mysqli($my_database,$my_username,$my_password,$my_dbcname);
	if($dbcc->connect_error) {
		echo '<p style="width:900px; margin:0 auto; margin-top:20px;" class="box_err">Error 2 : ('.$dbcc->connect_errno.') '.$dbcc->connect_error.'<br>Please try again later or report this error to the PKF Admin</p>';
	}else{
		mysqli_set_charset($dbcc,"utf8");
	}



	$sqls = "SELECT * FROM ".$_REQUEST['cid']."_users WHERE ref = '".$all_users['id']."'";


	if($ress = $dbcc->query($sqls))
	{
		if($ress->num_rows > 0){
			$com_users = $ress->fetch_assoc();

			
		}
	}



		$array['timestamp'] = time();
		$array['id'] = $com_users['id'];
		$array['ref'] = $com_users['ref'];
		$array['cid'] = $_REQUEST['cid'];
		$array['customers'] = $all_users['access'];
		$array['type'] = $com_users['type'];
		$array['emp_id'] = $com_users['emp_id'];
		$array['fname'] = $com_users['firstname'];
		$array['name'] = $com_users['name'];
		//$array['phone'] = $com_users['phone'];
		$array['username'] = $com_users['username'];
		$array['img'] = $com_users['img'].'?'.time();
		
		$array['mn_entities'] = $com_users['entities'];
		$array['mn_branches'] = $com_users['branches'];
		$array['mn_divisions'] = $com_users['divisions'];
		$array['mn_departments'] = $com_users['departments'];
		$array['mn_teams'] = $com_users['teams'];
		
		$array['sel_entities'] = $com_users['entities'];
		$array['sel_branches'] = $com_users['branches'];
		$array['sel_divisions'] = $com_users['divisions'];
		$array['sel_departments'] = $com_users['departments'];
		$array['sel_teams'] = $com_users['teams'];
		
		$array['access_group'] = $com_users['emp_group'];
		if($com_users['emp_group'] == 'all'){
			$array['emp_group'] = 's';
		}else{
			$array['emp_group'] = $com_users['emp_group'];
		}
		$tmp = unserialize($com_users['permissions']);
		if(!$tmp){$tmp = array();}

		$_SESSION['rego'] = array_merge($array, $tmp);


		$lang = $_SESSION['rego']['lang'];

		$_SESSION['rego']['lang'] = $lang;

		$months = array(1=>"มกราคม", "กุมภาพันธ์", "มีนาคม", "เมษายน", "พฤษภาคม", "มิถุนายน", "กรกฏาคม", "สิงหาคม", "กันยายน", "ตุลาคม", "พฤษจิกายน", "ธันวาคม");
		$days = array(1=>'วันจันทร์','วันอังคาร','วันพุธ','วันพฤหัสบดี','วันศุกร์','วันเสาร์','วันอาทิตย์');


		if($lang == 'en'){
			$_SESSION['rego']['cur_date'] = date('l j F Y');

		}else{
			$_SESSION['rego']['cur_date'] = $days[date('N')].' '.date('j').' '.$months[date('n')].' '.(date('Y')+543);
		}

		$_SESSION['rego']['standard'] = array();
		$resssss = $dbx->query("SELECT * FROM rego_company_settings");
		if($rowsss = $resssss->fetch_assoc()){

		}
		$_SESSION['rego']['standard'] = unserialize($rowsss['standard']);

		$sys_settings = array();
		if($resasdas = $dbc->query("SELECT * FROM ".$_REQUEST['cid']."_sys_settings")){
			$sys_settings = $resasdas->fetch_assoc();
			$_SESSION['rego']['cur_year'] = $sys_settings['cur_year'];
			$_SESSION['rego']['year_en'] = $sys_settings['cur_year'];
			$_SESSION['rego']['year_th'] = ((int)$sys_settings['cur_year']+543);
		}


		$_SESSION['rego']['cur_month'] = $sys_settings['cur_month'];
		if($_SESSION['rego']['cur_month'] < 1 || $_SESSION['rego']['cur_month'] > 12){
			$_SESSION['rego']['cur_month'] = date('n');
		}
		$_SESSION['rego']['curr_month'] = sprintf("%02d", $_SESSION['rego']['cur_month']);
		$dateformat = 'd-m-Y';
		$cur_month = $_SESSION['rego']['cur_month'];
		$curr_month = $_SESSION['rego']['curr_month'];
		$cur_year = $_SESSION['rego']['cur_year'];
		$_SESSION['rego']['gov_month'] = $_SESSION['rego']['cur_month'];







		$sqlaaa = "SELECT * FROM rego_customers WHERE clientID = '".$_REQUEST['cid']."'";
		if($reaaaas = $dbx->query($sqlaaa)){
			if($rowaaa = $reaaaas->fetch_assoc()){
				$_SESSION['rego']['version'] = $rowaaa['version'];
				$standard = $rowaaa['version'];
				$_SESSION['rego']['max'] = $rowaaa['employees'];
				$_SESSION['rego']['emp_platform'] = $rowaaa['emp_platform'];
				$_SESSION['rego']['phone'] = $rowaaa['phone'];
				$_SESSION['rego']['email'] = $rowaaa['email'];
				$expire_date = $rowaaa['period_end'];
				$diff = strtotime($rowaaa['period_end']) - strtotime(date('d-m-Y'));
				$days_left = floor($diff / (60*60*24));
				if($days_left < 0){$days_left = 0;}
				if($rowaaa['version'] > 0 && $days_left < 30){$buyrego = true; $BuyRego = $lng['Extend REGO'];}
				if($rowaaa['version'] == 0){$buyrego = true;}
				if($rowaaa['status'] == 2){$buyrego = false;}
				if($days_left > 35){$buyrego = false;}
				$_SESSION['rego']['expire'] = $days_left;
				$_SESSION['rego']['status_val'] = $rowaaa['status'];
			}
		}
		


		$_SESSION['rego']['payroll_dbase'] = $_REQUEST['cid'].'_payroll_'.$_SESSION['rego']['cur_year'];
		$_SESSION['rego']['emp_dbase'] = $_REQUEST['cid'].'_employees';
		$_SESSION['rego']['period'] = $lng['Select period'];


	
	$sql1 = "SELECT * FROM rego_all_users WHERE LOWER(username) = '".$_SESSION['rego']['username']."'";
	if($res1 = $dbx->query($sql1)){
		if($all_users = $res1->fetch_assoc()){
			if($all_users['emp_access'] != $_REQUEST['cid']){

				// CHANGE LAST FIELD IN REGO_COMPANY_USERS /////////////////////////////////////
				$res = $dbx->query("UPDATE rego_all_users SET last = '".$_REQUEST['cid']."' WHERE username = '".$_SESSION['rego']['username']."'");
				echo mysqli_error($dbx);
			}
		}
	}

	
