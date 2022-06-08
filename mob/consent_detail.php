<?php
	if(session_id()==''){session_start();}
	
	$logtime = 7200;
	if(isset($_SESSION['rego']['emp_id']) && !empty($_SESSION['rego']['emp_id'])){
		if(time() - $_SESSION['rego']['timestamp'] > $logtime) {
			$_SESSION['rego']['timestamp'] = 0;
			header('location: login.php');
		}else{
			$_SESSION['rego']['timestamp'] = time();
		}
	}else{
		header('location: login.php');
	}	
	
	// print_r($_SESSION);
	include('../dbconnect/db_connect.php');
	include(DIR.'files/arrays_'.$lang.'.php');
	include(DIR.'files/functions.php');
	include(DIR.'files/payroll_functions.php');
	include(DIR.'leave/functions.php');
	
	if(isset($_GET['y'])){
		$_SESSION['rego']['mob_year'] = $_GET['y'];
	}
	
	$cid = $_SESSION['rego']['cid'];
	$scan = false;
	if($res = $dbc->query("SELECT scan_system FROM ".$cid."_leave_time_settings")){
		$xrow = $res->fetch_assoc();
		if($xrow['scan_system'] == 'REGO'){$scan = true;}
	}
	
	if(!isset($_SESSION['rego']['mob_year'])){$_SESSION['rego']['mob_year'] = date('Y');}
	//$_SESSION['rego']['mob_year'] = date('Y');
	$_SESSION['rego']['year_en'] = $_SESSION['rego']['mob_year'];
	$_SESSION['rego']['year_th'] = ((int)$_SESSION['rego']['mob_year'])+543;
	$_SESSION['rego']['curr_month'] = date('m');
	$_SESSION['rego']['cur_month'] = date('n');
	$data = array();
	if($res = $dbc->query("SELECT * FROM ".$cid."_employees WHERE emp_id = '".$_SESSION['rego']['emp_id']."'")){
		$data = $res->fetch_assoc();
	}
	if(empty($data['image'])){$data['image'] = 'images/profile_image.jpg';}
	$_SESSION['rego']['payroll_dbase'] = $cid.'_payroll_'.$_SESSION['rego']['mob_year'];
	$_SESSION['rego']['emp_dbase'] = $cid.'_employees';
	$years = getYears($cid);
			
	//$leave_settings = getLeaveTimeSettings();
	$leave_types = getEmpLeaveTypes($cid);
	unset($leave_types['EL']);
	//var_dump($leave_types); //exit;
	
	$request = array();
	$confirmed = array();
	$assigned = array();

	if(!isset($_GET['mn'])){$_GET['mn'] = 2;}

	// GET REGO STANDARD VAUES 

	$my_dbaname = $prefix.'admin';


	$dba = new mysqli($my_database,$my_username,$my_password,$my_dbaname);
	mysqli_set_charset($dba,"utf8");
	if($dba->connect_error) {
		echo'<p style="width:900px; margin:0 auto; margin-top:20px;" class="box_err">Error: ('.$dba->connect_errno.') '.$dba->connect_error.'<br>Please try again later or report this error to <a href="mailto:admin@regohr.com">admin@regohr.com</a></p>';
	}

	$sql102 = "SELECT * FROM rego_customers WHERE clientID = '".$_SESSION['rego']['cid']."'";

	if($res102 = $dba->query($sql102)){
		if($res102->num_rows > 0){
			if($row102 = $res102->fetch_assoc())
				{
					$versionValue = $row102['version'];  // SELECTED TEAMS STORED IN SESSION 

					$expire_date = $row102['period_end'];
					$diff = strtotime($row102['period_end']) - strtotime(date('d-m-Y'));
					$days_left = floor($diff / (60*60*24));
					if($days_left < 0){$days_left = 0;}
					$_SESSION['rego']['expire'] = $days_left;
					$_SESSION['rego']['status_val'] = $row102['status'];
			
				}
		}
	}


	$standardArray = $_SESSION['rego']['standard'];

	$leaveCheck = $standardArray[$versionValue]['leave'];
	$timeCheck = $standardArray[$versionValue]['time'];

	if(($days_left <= 0) || ($_SESSION['rego']['status_val'] =='0') || ($_SESSION['rego']['status_val'] =='2') || ($_SESSION['rego']['status_val'] =='3'))
	{
		header('location: '.ROOT.'mob/expired.php');
	}





	// check in rego all users table for the consent 
		$sql_consent = "SELECT * FROM rego_all_users WHERE username = '".$_SESSION['rego']['username']."'";
		if($res_consent = $dba->query($sql_consent)){
			if($row_consent = $res_consent->fetch_assoc()){
				$privacyConsent = $row_consent['privacy_consent'];
				$termsConsent = $row_consent['terms_consent'];
				$cookieConsent = $row_consent['cookie_consent'];
				$showConsent = $row_consent['showConsent'];


			}
		}

		$sql_get_consent_setting = "SELECT * FROM rego_default_settings";
			if($res_get_consent_setting = $dba->query($sql_get_consent_setting)){
				if($row_get_consent_setting = $res_get_consent_setting->fetch_assoc()){

				$show_confirmation_text = $row_get_consent_setting['show_confirmation_text'];

			}
		}




		if($cookieConsent != '1' )
		{
			header('location: '.ROOT.'mob/message.php');
		}
		else
		{
			if($show_confirmation_text == '0')
			{
				header('location: '.ROOT.'mob/index.php');
			}
			else
			{
				header('location: '.ROOT.'mob/consent.php');
			}
		}

?>

