<?
	if(session_id()==''){session_start();}
	ob_start();
	include("../../dbconnect/db_connect.php");

	$id = $_REQUEST['shift_schedule']['team_id'];

		// echo '<pre>';
		// print_r($_REQUEST);
		// echo '</pre>';
		// die();

$ssData = 'a:48:{s:7:"team_id";s:4:"main";s:4:"name";s:7:"No Team";s:11:"description";s:22:"Default Shift Schedule";s:6:"noscan";s:1:"1";s:12:"scheduleType";s:6:"select";s:16:"numberOfSchedule";s:6:"select";s:10:"week_setup";s:6:"select";s:17:"variable_off_days";s:2:"na";s:9:"schedule1";s:6:"select";s:10:"range_day1";s:0:"";s:8:"t_hours1";s:0:"";s:8:"b_hours1";s:0:"";s:9:"schedule2";s:3:"off";s:10:"range_day2";s:0:"";s:8:"t_hours2";s:0:"";s:8:"b_hours2";s:0:"";s:9:"schedule3";s:6:"select";s:10:"range_day3";s:0:"";s:8:"t_hours3";s:0:"";s:8:"b_hours3";s:0:"";s:9:"schedule4";s:3:"off";s:10:"range_day4";s:0:"";s:8:"t_hours4";s:0:"";s:8:"b_hours4";s:0:"";s:9:"schedule5";s:6:"select";s:10:"range_day5";s:0:"";s:8:"t_hours5";s:0:"";s:8:"b_hours5";s:0:"";s:9:"schedule6";s:3:"off";s:10:"range_day6";s:0:"";s:8:"t_hours6";s:0:"";s:8:"b_hours6";s:0:"";s:9:"schedule7";s:6:"select";s:10:"range_day7";s:0:"";s:8:"t_hours7";s:0:"";s:8:"b_hours7";s:0:"";s:9:"schedule8";s:3:"off";s:10:"range_day8";s:0:"";s:8:"t_hours8";s:0:"";s:8:"b_hours8";s:0:"";s:10:"early_late";s:3:"yes";s:15:"show_early_late";s:3:"yes";s:12:"accept_early";s:0:"";s:11:"accept_late";s:0:"";s:8:"overtime";s:3:"yes";s:16:"show_overtme_val";s:3:"yes";s:11:"normalhours";s:0:"";s:9:"startdate";s:0:"";}';

	if($_REQUEST['shift_schedule']['noscan'] == '1')
	{
		// if no scan is selectd 
		$sql = "UPDATE ".$cid."_shiftplans_".$cur_year. " SET name = '".$dbc->real_escape_string($_REQUEST['shift_schedule']['name'])."',description = '".$dbc->real_escape_string($_REQUEST['shift_schedule']['description'])."',ss_data = '".$dbc->real_escape_string($ssData)."', dates = '', cycle_details= '', wh_code='', wkd='' WHERE id ='".$id."' ";

	}
	else
	{
		// for 2 scan and 4 scan 
		$sql = "UPDATE ".$cid."_shiftplans_".$cur_year. " SET name = '".$dbc->real_escape_string($_REQUEST['shift_schedule']['name'])."',description = '".$dbc->real_escape_string($_REQUEST['shift_schedule']['description'])."',ss_data = '".$dbc->real_escape_string(serialize($_REQUEST['shift_schedule']))."' WHERE id ='".$id."' ";
	}

	
	ob_clean();	
	if($dbc->query($sql)){
		echo 'success';
	}else{
		echo mysqli_error($dbc);
	}
	exit;
	
?>
