<?
	if(session_id()==''){session_start();}
	ob_start();
	include("../../dbconnect/db_connect.php");
	//include("../../files/functions.php");
	//include("../functions.php");
	include(DIR.'files/functions.php');
	include(DIR.'leave/functions.php');
	include(DIR.'time/functions.php');
	
	$time_settings = getTimeSettings();
	
	$time_period = getTimePeriod();
	$start = date('Y-m-d', strtotime($time_period['start']));
	$end = date('Y-m-d', strtotime($time_period['end']));
	//var_dump($start); //exit;
	//var_dump($end); //exit;

	$var_allow = getUsedVarAllow($lang);
	$compensations = getCompensations();
	
	//$ids = implode(',', $_REQUEST['ids']);
	$ids = '';
	foreach($_REQUEST['ids'] as $v){$ids .= "'".$v."',";}
	$ids = substr($ids,0,-1);

	//var_dump($ids); exit;
	//var_dump($_REQUEST); exit;
	
	$sql = "UPDATE ".$cid."_attendance SET approved = '1' WHERE id IN (".$ids.")";
	//echo $sql; exit;
		
	if($dbc->query($sql)){
		ob_clean();	
		echo 'success'; exit;
	}else{
		ob_clean();	
		echo mysqli_error($dbc);
	}
	
	
	
	
	
	
	
?>