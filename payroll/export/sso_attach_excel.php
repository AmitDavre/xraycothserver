<?php
	
	$pattern = '%-%%%%-%%%%%-%%-%';
	$title = array(''=>'', 1=>'003',2=>'004',3=>'005');
	$data = array();
	if($res = $dbc->query("SELECT * FROM ".$_SESSION['rego']['payroll_dbase']." WHERE month = '".$_SESSION['rego']['cur_month']."' AND entity = '".$_SESSION['rego']['gov_entity']."' AND branch = '".$_SESSION['rego']['gov_branch']."' AND emp_group = '".$_SESSION['rego']['emp_group']."' AND calc_sso = 1 ORDER by emp_id ASC")){
		$nr=1; $tot_salary = 0; $tot_social = 0;
		while($row = $res->fetch_assoc()){
			$data[$nr]['emp_id'] = $row['emp_id'];
			$empinfo = getEmployeeInfo($cid, $row['emp_id']);
			$fix_allow = 0; 
			for($i=1;$i<=10;$i++){
				$fix_allow += $row['fix_allow_'.$i]; // ????????????????????? from payroll database
			}
			$data[$nr]['title'] = $title[$empinfo['title']];
			$data[$nr]['firstname'] = $empinfo['firstname'];
			$data[$nr]['lastname'] = $empinfo['lastname'];
			$data[$nr]['en_name'] = $empinfo['en_name'];
			if(strlen($empinfo['idcard_nr']) == 13){
				$data[$nr]['idcard_nr'] = vsprintf(str_replace('%','%s',$pattern),str_split($empinfo['idcard_nr']));//$empinfo['idcard_nr'];
			}else{
				$data[$nr]['idcard_nr'] = vsprintf(str_replace('%','%s',$pattern),str_split('0000000000000'));//$empinfo['idcard_nr'];
			}

			//$basicsalary = $row['salary'] + $fix_allow;
			$basicsalary = (($row['salary'] + $fix_allow + $row['remaining_salary']) - ($row['leave_wop_b'] + $row['absence_b'] + $row['late_early_b']) );

			if($sso_act_max == 'act'){
				$basic_salary = $basicsalary;
			}else{
				$basic_salary = ($basicsalary > $rego_settings['sso_max_wage'] ? $rego_settings['sso_max_wage'] : $basicsalary);
			}
			$basic_salary = ($basicsalary < $rego_settings['sso_min_wage'] ? $rego_settings['sso_min_wage'] : $basicsalary);
			$data[$nr]['basic_salary'] = $basic_salary;
			$data[$nr]['ssf'] = $row['social'];
			$tot_salary += $basic_salary; 
			$tot_social+= $row['social'];
			$nr++;
		}
	}
	//var_dump($data);
?>

<div class="A4form" style="width:960px">

	<table style="width:100%; margin-bottom:15px;" border="0">
		<tr>
			<td style="font-size:20px; font-weight:600"><?=$lng['SSO Excel file']?></td>
			<td>
				<button type="button" class="btn btn-primary btn-fr" onclick="window.location.href='<?=ROOT?>payroll/export/download/download_sso_attach_excel.php';"><i class="fa fa-download"></i>&nbsp; <?=$lng['Download']?></button>
			</td>
		</tr>
	</table>
	
<?php

	$table = '
				<table border="0" class="basicTable" style="margin-bottom:10px">
					<tbody>
						<tr>
							<th>'.$lng['Employer account number'].' :</th>
							<td>'.$edata['sso_account'].'</td>
							<th>'.$lng['Branch'].' :</th>
							<td>'.$branch.'</td>
							<th>'.$lng['Period'].' :</th>
							<td>'.$months[$_SESSION['rego']['cur_month']].' '.$_SESSION['rego']['year_'.$lang].'</td>
							<td style="width:100%"></td>
						</tr>
					</tbody>
				</table>';
	$table .= '
				<table border="0" class="basicTable">
					<thead>
						<tr>
							<th class="tac">'.$lng['No'].'</th>
							<th>'.$lng['Tax ID no.'].'</th>
							<th>'.$lng['Title'].'</th>
							<th>'.$lng['First name'].'</th>
							<th>'.$lng['Last name'].'</th>
							<th class="tar">'.$lng['Wage'].'</th>
							<th class="tar">'.$lng['Contribution'].'</th>
						</tr>
					</thead>
					<tbody>';
					
	if($data){ foreach($data as $k=> $v){
		$table .= '	<tr>
							<td class="tac">'.$k.'</td>
							<td>'.$v['idcard_nr'].'</td>
							<td class="tac">'.$v['title'].'</td>
							<td>'.$v['firstname'].'</td>
							<td>'.$v['lastname'].'</td>
							<td class="tar">'.number_format($v['basic_salary'],2).'</td>
							<td class="tar">'.number_format($v['ssf'],2).'</td>
						</tr>';
	}
		$table .= '	<tr>
							<td colspan="5" class="tar" style="font-weight:600">'.$lng['Totals'].'</td>
							<td class="tar" style="font-weight:600">'.number_format($tot_salary,2).'</td>
							<td class="tar" style="font-weight:600">'.number_format($tot_social).'</td>
						</tr>';
	}else{
		$table .= '	<tr>
							<td colspan="7">'.$lng['No data available for this month'].'</td>
						</tr>';
	}
		$table .= '</tbody>
				</table>';

	echo $table;

?>

	
</div>
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
