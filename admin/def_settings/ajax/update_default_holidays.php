<?
	if(session_id()==''){session_start();}
	ob_start();
	include("../../dbconnect/db_connect.php");
	//var_dump($_REQUEST); exit;
	
	$sql = "INSERT INTO rego_default_holidays (id, year, date, cdate, th, en) VALUES (
		'".$dba->real_escape_string($_REQUEST['id'])."', 
		'".$dba->real_escape_string(date('Y', strtotime($_REQUEST['date'])))."', 
		'".$dba->real_escape_string(date('Y-m-d', strtotime($_REQUEST['date'])))."', 
		'".$dba->real_escape_string(date('Y-m-d', strtotime($_REQUEST['cdate'])))."', 
		'".$dba->real_escape_string($_REQUEST['th'])."', 
		'".$dba->real_escape_string($_REQUEST['en'])."') 
		ON DUPLICATE KEY UPDATE 
		year = VALUES(year),
		date = VALUES(date),
		cdate = VALUES(cdate),
		th = VALUES(th),
		en = VALUES(en)";
	//echo $sql;
	//exit;
	
	ob_clean();	
	if($dba->query($sql)){
		echo 'success';
	}else{
		echo mysqli_error($dba);
	}
	exit;
?>