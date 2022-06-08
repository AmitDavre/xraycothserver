<?
	if(session_id()==''){session_start(); ob_start();}
	include('../../dbconnect/db_connect.php');
	include(DIR.'files/functions.php');

	$ccid = $_REQUEST['ccid'];

	$checkcc = $dbc->query("SELECT * FROM ".$_SESSION['rego']['cid']."_comm_centers WHERE id='".$ccid."'");
	if($checkcc->num_rows > 0){
		$row = $checkcc->fetch_assoc();
		
		$sent_to = $row['sent_to'];
		$getEmployeeTeam = getEmployeeTeam($_REQUEST['empid']);

		if($sent_to !=''){
			$explodesentto = explode(',', $sent_to);
			if(in_array($getEmployeeTeam, $explodesentto)){
				$myteam = $sent_to;
			}else{
				$myteam = $sent_to.','.$getEmployeeTeam;
			}
		}else{
			$myteam = $getEmployeeTeam;
		}


		$alreadyEmp = $row['sel_emp_ids'];
		if($alreadyEmp !=''){
			$newEMployee = $alreadyEmp.','.$_REQUEST['empid'];
			$dbc->query("UPDATE ".$_SESSION['rego']['cid']."_comm_centers SET sent_to = '".$myteam."', sel_emp_ids='".$newEMployee."' WHERE id='".$ccid."'");
		}else{
			$dbc->query("UPDATE ".$_SESSION['rego']['cid']."_comm_centers SET sent_to = '".$myteam."', sel_emp_ids='".$_REQUEST['empid']."' WHERE id='".$ccid."'");
		}
	}

	ob_clean();
	echo 'success';



	/*$data = array();
	$res = $dbc->query("SELECT * FROM ".$_SESSION['rego']['cid']."_employees WHERE emp_id = '".$_REQUEST['empid']."' ");
	while($row = $res->fetch_assoc()){
		$data[] = $row;
	}

	$trData = '';
	foreach ($data as $key => $row) {

		$trData .= '<tr id="'.$row['emp_id'].'" data-id="'.$row['emp_id'].'">
						<td>'.$row['emp_id'].'</td>
						<td>'.$row['firstname'].' '.$row['lastname'].'</td>
						<td>'.$row['personal_email'].'</td>
						<td>'.$noyes01[$row['allow_login']].'</td>
						<td>
							<a><i title="Remove" id="'.$row['emp_id'].'" onclick="removeRowemp(this)" class="fa fa-trash fa-lg text-danger"></i></a>
						</td>
					</tr>';
	}

	ob_clean();
	echo $trData;
	*/


?>