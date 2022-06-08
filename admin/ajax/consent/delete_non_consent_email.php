<?
	if(session_id()==''){session_start(); ob_start();}
	include("../../dbconnect/db_connect.php");



	$sql = "SELECT  non_email FROM rego_default_settings ";
	if($res = $dba->query($sql)){
		if($row = $res->fetch_assoc()){
			$emailnon = $row['non_email'];
		}
	}		


	$unserlizearray = unserialize($emailnon);

	unset($unserlizearray[$_REQUEST['valuee']]);

	$newarray = serialize($unserlizearray);

	$sql = "UPDATE rego_default_settings SET non_email = '".$newarray."'  ";

	if($dba->query($sql)){
		ob_clean();
		echo 'success';
	}else{
		echo mysqli_error($dba);
	}





