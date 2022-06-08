<?
	if(session_id()==''){session_start();}
	ob_start();
	include('../../../../dbconnect/db_connect.php');
	include(DIR.'files/functions.php');



    echo '<pre>';
    print_r($_REQUEST);
    echo '</pre>';

// 		$sql3244 = "SELECT * FROM ".$cid."_employees WHERE emp_id = '".$_REQUEST['emp_id']."'";
// 		if($reasdasds = $dbc->query($sql3244)){
// 			if($rosaddsw = $reasdasds->fetch_assoc()){
// 				$sqlinsertdata = "INSERT INTO ".$cid."_consent_letter ( `emp_id`, `en_name`, `department`, `position`,`branch`,`division`,`team`) VALUES ('".$rosaddsw['emp_id']."','".$rosaddsw['en_name']."','".$rosaddsw['department']."','".$rosaddsw['position']."','".$rosaddsw['branch']."','".$rosaddsw['division']."','".$rosaddsw['team']."')";
// 				$dbc->query($sqlinsertdata);
// 			}
// 		}

		
	



// ob_clean();
// echo "success";
// exit;
