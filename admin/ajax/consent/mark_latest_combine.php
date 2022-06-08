<?
	if(session_id()==''){session_start(); ob_start();}
	include("../../dbconnect/db_connect.php");

		$sql = "SELECT id,user_name, MAX(id) AS request_id FROM rego_consent_log WHERE consent_name = 'Privacy Policy' GROUP BY user_name DESC";
		if($res = $dba->query($sql)){
			while($row = $res->fetch_assoc()){
				$privacyRecords[$row['request_id']] = $row;
			}
		}		

		$sql1 = "SELECT id,user_name, MAX(id) AS request_id FROM rego_consent_log WHERE consent_name = 'Terms & Condition' GROUP BY user_name DESC";
		if($res1 = $dba->query($sql1)){
			while($row1 = $res1->fetch_assoc()){
				$privacyRecords1[$row1['request_id']] = $row1;
			}
		}

		$sql2 = "SELECT id,user_name, MAX(id) AS request_id FROM rego_consent_log WHERE consent_name = 'Cookie Consent' GROUP BY user_name DESC";
		if($res2 = $dba->query($sql2)){
			while($row2 = $res2->fetch_assoc()){
				$privacyRecords2[$row2['request_id']] = $row2;
			}
		}

		$array1 = array_merge($privacyRecords,$privacyRecords1);
		$array2 = array_merge($array1,$privacyRecords2);


		foreach ($array2 as $key => $value) {
			# code...
			$array3[$value['request_id']] =$value;
		}
		echo json_encode($array3);
?>



