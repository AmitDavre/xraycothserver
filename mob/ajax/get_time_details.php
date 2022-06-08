<?
	if(session_id()==''){session_start();}
	ob_start();
	include('../../dbconnect/db_connect.php');
	include(DIR.'time/functions.php');
	//$_REQUEST['id'] = 10007;
	$data = array();

	$sql = "SELECT * FROM ".$cid."_attendance_".$_SESSION['rego']['mob_year']." WHERE id = '".$_REQUEST['id']."'";
	if($res = $dbc->query($sql)){
		if($row = $res->fetch_assoc()){
			$data['date'] = $row['date'];
			//$data['plan'] = $row['plan'];
			$data['f1'] = $row['f1'];
			$data['u1'] = $row['u1'];
			$data['f2'] = $row['f2'];
			$data['u2'] = $row['u2'];
			$data['scan1'] = $row['scan1'];
			$data['scan2'] = $row['scan2'];
			$data['scan3'] = $row['scan3'];
			$data['scan4'] = $row['scan4'];
			$data['late_early'] = dateHours($row['paid_late'] + $row['paid_early'] + $row['unpaid_late'] + $row['unpaid_early']);
			$data['actual_hrs'] = dateHours($row['actual_hrs']);
			$data['planned_hrs'] = dateHours($row['planned_hrs']);
			$data['normal_hrs'] = dateHours($row['normal_hrs']);
			$data['ot'] = dateHours($row['ot1'] + row['ot15'] + row['ot5'] + row['ot3']);
			$data['paid_hrs'] = dateHours($row['normal_hrs'] + $row['ot1'] + row['ot15'] + row['ot5'] + row['ot3']);
		}
	}
	$table = '
		<table class="table table-bordered table-sm" style="width:100%; margin:0">
			<thead>
				<tr>
					<td colspan="5" style="background:#eee" class="tac">'.date('D d-m-Y', strtotime($data['date'])).'</td>
				</tr>
			</head>
			<tbody>
				<tr>
					<td>Plan Hrs</td>
					<td class="tac">'.$data['f1'].'</td>
					<td class="tac">'.$data['u1'].'</td>
					<td class="tac">'.$data['f2'].'</td>
					<td class="tac">'.$data['u2'].'</td>
				</tr>
				<tr>
					<td>Log Hrs</td>
					<td class="tac">'.$data['scan1'].'</td>
					<td class="tac">'.$data['scan2'].'</td>
					<td class="tac">'.$data['scan3'].'</td>
					<td class="tac">'.$data['scan4'].'</td>
				</tr>
				<tr>
					<td>Planned Hrs</td>
					<td colspan="4">'.$data['planned_hrs'].'</td>
				</tr>
				<tr>
					<td>Actual Hrs</td>
					<td colspan="4">'.$data['actual_hrs'].'</td>
				</tr>
				<tr>
					<td>Normal Hrs</td>
					<td colspan="4">'.$data['normal_hrs'].'</td>
				</tr>
				<tr>
					<td>Ot Hrs</td>
					<td colspan="4">'.$data['ot'].'</td>
				</tr>
				<tr>
					<td>Paid Hrs</td>
					<td colspan="4">'.$data['paid_hrs'].'</td>
				</tr>
				<tr>
					<td>Late/Early</td>
					<td colspan="4">'.$data['late_early'].'</td>
				</tr>
			<tbody>
		</table>';
	
	
	
	
	
	
	
	
	
	//var_dump($table); exit;
	ob_clean();
	echo json_encode($table);


?>


















