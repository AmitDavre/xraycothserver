<?
	if(session_id()==''){session_start(); ob_start();}
	include("../../dbconnect/db_connect.php");

		$sql = "SELECT * FROM `rego_consent_log` WHERE id NOT IN (SELECT MAX(id) FROM rego_consent_log GROUP BY user_name, consent_name) ";
		if($res = $dba->query($sql)){
			while($row = $res->fetch_assoc()){
				$privacyRecords[$row['id']] = $row;
			}
		}		

		
		echo json_encode($privacyRecords);
?>



