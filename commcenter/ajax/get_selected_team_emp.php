<?
	if(session_id()==''){session_start(); ob_start();}
	include('../../dbconnect/db_connect.php');

	$sbranches = str_replace(',', "','", implode(',', $_REQUEST['locations']));
	$sdivisions = str_replace(',', "','", implode(',', $_REQUEST['divisions']));
	$sdepartments = str_replace(',', "','", implode(',', $_REQUEST['departments']));
	$steams = str_replace(',', "','", implode(',', $_REQUEST['teams']));

	$where = "branch IN ('".$sbranches."')";
	$where .= " AND division IN ('".$sdivisions."')";
	$where .= " AND department IN ('".$sdepartments."')";
	$where .= " AND team IN ('".$steams."')";

	$data = array();
	$res = $dbc->query("SELECT * FROM ".$_SESSION['rego']['cid']."_employees WHERE emp_status = '1' AND ".$where." ");
	while($row = $res->fetch_assoc()){
		$data[] = $row;
	}

	$trData = '';
	foreach ($data as $key => $row) {

		if($row['peComm'] == 1){
			$email = $row['personal_email'];
		}elseif($row['weComm'] == 1){
			$email = $row['work_email'];
		}else{
			$email = $row['personal_email'];
		}

		$trData .= '<tr id="'.$row['emp_id'].'" data-id="'.$row['emp_id'].'">
						<td>'.$row['emp_id'].'</td>
						<td>'.$row['firstname'].' '.$row['lastname'].'</td>
						<td>'.$email.'</td>
						<td>'.$noyes01[$row['allow_login']].'</td>
						<td>'.$teams[$row['team']]['code'].'</td>
						<td>
							<a><i title="Remove" id="'.$row['emp_id'].'" onclick="removeRowemp(this)" class="fa fa-trash fa-lg text-danger"></i></a>
						</td>
					</tr>';
	}

	ob_clean();
	echo $trData;
	
?>