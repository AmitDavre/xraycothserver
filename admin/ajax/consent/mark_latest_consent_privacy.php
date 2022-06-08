<?
	if(session_id()==''){session_start(); ob_start();}
	include("../../dbconnect/db_connect.php");

		$sql = "SELECT id,user_name, MAX(id) AS request_id FROM rego_consent_log WHERE consent_name = 'Privacy Policy' GROUP BY user_name DESC";
		if($res = $dba->query($sql)){
			while($row = $res->fetch_assoc()){
				$privacyRecords[$row['request_id']] = $row;
			}
		}


		echo json_encode($privacyRecords);
?>



