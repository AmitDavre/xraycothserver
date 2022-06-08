<?
	if(session_id()==''){session_start(); ob_start();}
	include("../dbconnect/db_connect.php");
	include("../../files/functions.php");
	//$_REQUEST["id"] = 'shr0101';
	//var_dump($_REQUEST); exit;
	//var_dump("SELECT * FROM ".$_SESSION['SDadmin']."_customers WHERE clientID = '".$_REQUEST["id"]."'");
		
	if($res = $dba->query("SELECT * FROM rego_customers WHERE clientID = '".$_REQUEST["id"]."'")){ 
		if($row = $res->fetch_object()){
			
			$_SESSION['rego']['max'] = $row->employees;
			$_SESSION['rego']['version'] = $row->version;
			$_SESSION['rego']['cid'] = strtolower($row->clientID);
			$_SESSION['rego']['type'] = 'sys';
			//$_SESSION['rego']['module'] = 'all';
			//$_SESSION['rego']['teams'] = 'all';
			//$_SESSION['rego']['access'] = array();
			$_SESSION['rego']['emp_id'] = '';//$_SESSION['RGadmin']['id'];
			$_SESSION['rego']['name'] = $_SESSION['RGadmin']['name'];
			$_SESSION['rego']['username'] = $_SESSION['RGadmin']['email'];
			$_SESSION['rego']['phone'] = $_SESSION['RGadmin']['phone'];
			$_SESSION['rego']['img'] = $_SESSION['RGadmin']['img'].'?'.time();
			$_SESSION['rego']['timestamp'] = time();
			$_SESSION['rego']['access_group'] = 'all';
			$_SESSION['rego']['emp_group'] = 's';
			$_SESSION['rego']['customers'] = strtolower($row->clientID);
			
			$_SESSION['rego']['mn_entities'] = '1,2,3,4,5,6,7,8,910,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30';
			$_SESSION['rego']['mn_branches'] = '1,2,3,4,5,6,7,8,910,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30';
			$_SESSION['rego']['mn_divisions'] = '1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30';
			$_SESSION['rego']['mn_departments'] = '1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30';
			$_SESSION['rego']['mn_teams'] = '1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31,32,33,34,35,36,37,38,39,40,41,42,43,44,45,46,47,48,49,50';
			
			$_SESSION['rego']['sel_entities'] = '1,2,3,4,5,6,7,8,910,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30';
			$_SESSION['rego']['sel_branches'] = '1,2,3,4,5,6,7,8,910,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30';
			$_SESSION['rego']['sel_divisions'] = '1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30';
			$_SESSION['rego']['sel_departments'] = '1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30';
			$_SESSION['rego']['sel_teams'] = '1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31,32,33,34,35,36,37,38,39,40,41,42,43,44,45,46,47,48,49,50';
			
			/*$_SESSION['rego']['sel_entities'] = 1;
			$_SESSION['rego']['sel_branches'] = 1;
			$_SESSION['rego']['sel_divisions'] = 1;
			$_SESSION['rego']['sel_departments'] = 1;
			$_SESSION['rego']['sel_teams'] = 1;*/
			
			include('../../settings/ajax/sys_permissions.php');
			//$_SESSION['rego'] .= $sys_permissions;
			//$_SESSION['rego']['access']['myaccount'] = 0;
			$_SESSION['rego'] = array_merge($_SESSION['rego'], $array);
			//var_dump($_SESSION['rego']); exit;
			//ob_clean();
			//echo $_SESSION['subdir'];
			//var_dump($_SESSION['xhr']); exit;
		}else{
			echo 'error';
		}
	}
	
?>
