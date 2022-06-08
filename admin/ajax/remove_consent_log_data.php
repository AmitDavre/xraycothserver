<?
	if(session_id()==''){session_start();}
	ob_start();
	include("../dbconnect/db_connect.php");

	$removedate  = $_REQUEST['removeDate']. ' 24:00:00' ;

	$sql_consent = "SELECT * FROM rego_consent_log WHERE ID NOT IN (SELECT MAX(id) FROM rego_consent_log GROUP BY user_name, consent_name) AND consent_date < '".$removedate."'";
		if($res_consent = $dba->query($sql_consent)){
			while($row_consent = $res_consent->fetch_assoc()){
				$logData[] = $row_consent; // 0
			


			}
		}	


	foreach ($logData as $key => $value) {

			$sql = "DELETE FROM rego_consent_log WHERE id = '".$value['id']."'";
			$dba->query($sql);
	}

	ob_clean();
	echo 'success';
	// exit;
	
?>














