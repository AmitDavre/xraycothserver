<?
	if(session_id()==''){session_start();}
	ob_start();
	include('../../../../dbconnect/db_connect.php');
	include(DIR.'files/functions.php');



	$sql_get_from_consent_letter = "SELECT * FROM ".$cid."_consent_letter ";
	if($result_from_consent_letter = $dbc->query($sql_get_from_consent_letter)){
		while($row_from_consent_letter = $result_from_consent_letter->fetch_assoc()){
			$data_from_consent_letter[$row_from_consent_letter['emp_id']] = $row_from_consent_letter['emp_id'];
		}
	}


	if($_REQUEST['emp_id'] == 'all')
	{
		// add all
		$sql3244 = "SELECT * FROM ".$cid."_employees ";
		if($reasdasds = $dbc->query($sql3244)){
			while($rosaddsw = $reasdasds->fetch_assoc()){


				if (!in_array($rosaddsw['emp_id'], $data_from_consent_letter)) 
				{
					$sqlinsertdata = "INSERT INTO ".$cid."_consent_letter ( `emp_id`, `en_name`, `department`, `position`,`branch`,`division`,`team`) VALUES ('".$rosaddsw['emp_id']."','".$rosaddsw['en_name']."','".$rosaddsw['department']."','".$rosaddsw['position']."','".$rosaddsw['branch']."','".$rosaddsw['division']."','".$rosaddsw['team']."')";
					$dbc->query($sqlinsertdata);
				}
			}
		}	

	}
	else 
	{

		$sql3244 = "SELECT * FROM ".$cid."_employees WHERE emp_id = '".$_REQUEST['emp_id']."'";
		if($reasdasds = $dbc->query($sql3244)){
			if($rosaddsw = $reasdasds->fetch_assoc()){
				$sqlinsertdata = "INSERT INTO ".$cid."_consent_letter ( `emp_id`, `en_name`, `department`, `position`,`branch`,`division`,`team`) VALUES ('".$rosaddsw['emp_id']."','".$rosaddsw['en_name']."','".$rosaddsw['department']."','".$rosaddsw['position']."','".$rosaddsw['branch']."','".$rosaddsw['division']."','".$rosaddsw['team']."')";
				$dbc->query($sqlinsertdata);
			}
		}

		
	}



ob_clean();
echo "success";
exit;

	




?>