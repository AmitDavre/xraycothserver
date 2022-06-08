<?
	if(session_id()==''){session_start(); ob_start();}
	include("../../dbconnect/db_connect.php");
	
	//var_dump($_REQUEST); exit;
	
	$val = 0;
	if($_REQUEST['val'] == 'true'){$val = 1;}
	$sql = "UPDATE `".$cid."_attendance` SET `".$_REQUEST['fld']."` = '".$val."' WHERE `id` = '".$_REQUEST['id']."'";
	//echo $sql; exit;
		
	if($dbc->query($sql)){
		ob_clean();	echo 'success'; exit;
	}else{
		ob_clean();	echo mysqli_error($dbc);
	}