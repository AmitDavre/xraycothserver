<?
	if(session_id()==''){session_start(); ob_start();}
	include("../../dbconnect/db_connect.php");
	//var_dump($_REQUEST); exit;
	
	$err = false;
	foreach($_REQUEST['ids'] as $v){
		$sql = "UPDATE ".$cid."_ot_employees SET 
			".$_REQUEST['field']." = '".$dbc->real_escape_string($_REQUEST['status'])."' 
			WHERE `id` = '".$v."'";
		//echo $sql.'<br>'; //exit;
		if(!$dbc->query($sql)){
			$err = true;
		}
	}
	if(!$error){
		ob_clean();	echo 'success';
	}else{
		ob_clean();	echo 'error';
	}
