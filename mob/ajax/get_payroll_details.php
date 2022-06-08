<?
	if(session_id()==''){session_start();}; ob_start();
	include('../../dbconnect/db_connect.php');
	//include(DIR.'files/functions.php');
	
	$data = array();
	$other = 0;
	$employee = '';

	$sql = "SELECT * FROM ".$_SESSION['rego']['payroll_dbase']." WHERE id = '".$_REQUEST['id']."'";
	if($res = $dbc->query($sql)){
		while($row = $res->fetch_assoc()){
				$employee = $row['emp_id'].' : '.$row['emp_name_'.$lang];
				$other += $row['notice_payment'] + $row['remaining_salary'] + $row['paid_leave'] + $row['other_income'] + $row['severance'];
				$data[$row['id']]['days'] = number_format($row['paid_days'],2);
				$data[$row['id']]['salary'] = number_format($row['salary'],2);
				$data[$row['id']]['ot'] = number_format($row['total_otb'],2);
				$data[$row['id']]['fix'] = number_format($row['total_fix_allow'],2);
				$data[$row['id']]['var'] = number_format($row['total_var_allow'],2);
				$data[$row['id']]['other'] = number_format($other,2);
				$data[$row['id']]['absence'] = number_format($row['tot_absence'],2);
				$data[$row['id']]['deduct'] = number_format(($row['tot_deduct_before'] + $row['tot_deduct_after'] + $row['legal_deductions']),2);
				$data[$row['id']]['sso'] = number_format($row['social'],2);
				$data[$row['id']]['pvf'] = number_format($row['pvf_employee'],2);
				$data[$row['id']]['tax'] = number_format($row['tax'],2);
				$data[$row['id']]['gross'] = number_format($row['gross_income'],2);
				$data[$row['id']]['advance'] = number_format($row['advance'],2);
				$data[$row['id']]['net'] = number_format($row['net_income'],2);
		}
	}
	
	$table = '<table class="detailMobTable table table-sm table-bordered table-striped"><tbody>';
	foreach($data as $k=>$v){
		$table .= '
			<tr>
				<th>'.$lng['Days paid'].'</th><td>'.$v['days'].'</td>
			</tr>
			<tr>
				<th>'.$lng['Salary'].'</th><td>'.$v['salary'].'</td>
			</tr>
			<tr>
				<th>'.$lng['Fix. allowances'].'</th><td>'.$v['fix'].'</td>
			</tr>
			<tr>
				<th>'.$lng['Overtime'].'</th><td>'.$v['ot'].'</td>
			</tr>
			<tr>
				<th>'.$lng['Var. allowances'].'</th><td>'.$v['var'].'</td>
			</tr>
			<tr>
				<th>'.$lng['Other income'].'</th><td>'.$v['other'].'</td>
			</tr>
			<tr>
				<th>'.$lng['Absence'].'</th><td>'.$v['absence'].'</td>
			</tr>
			<tr>
				<th>'.$lng['Deductions'].'</th><td>'.$v['deduct'].'</td>
			</tr>
			<tr>
				<th>'.$lng['SSO'].'</th><td>'.$v['sso'].'</td>
			</tr>
			<tr>
				<th>'.$lng['PVF'].'</th><td>'.$v['pvf'].'</td>
			</tr>
			<tr>
				<th>'.$lng['Tax'].'</th><td>'.$v['tax'].'</td>
			</tr>
			<tr>
				<th>'.$lng['Gross income'].'</th><td>'.$v['gross'].'</td>
			</tr>
			<tr>
				<th>'.$lng['Advance'].'</th><td>'.$v['advance'].'</td>
			</tr>
			<tr>
				<th>'.$lng['Net income'].'</th><td>'.$v['net'].'</td>
			</tr>';
	}	
	$table .= '</tbody></table>';
	
	$result['table'] = $table;
	$result['employee'] = $employee;
	
	//echo $table; exit;

	ob_clean();
	echo json_encode($result);
















