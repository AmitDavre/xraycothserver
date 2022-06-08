<?
	if(session_id()==''){session_start(); ob_start();}
	include('../../dbconnect/db_connect.php');
	include(DIR.'files/functions.php');
	include(DIR.'files/payroll_functions.php');
	//var_dump($_REQUEST); exit;
	
	$usedCols = getUsedPayrollColumns($dbc);
	//var_dump($usedCols); //exit;
	$fix_allow = getUsedFixAllow($_SESSION['rego']['lang']);
	$fixAllow = getFixAllowances($pr_settings);
	$var_allow = getUsedVarAllow($_SESSION['rego']['lang']);

	//$allowances = getAllowances($pr_settings, $_SESSION['rego']['lang']);
	//$allow_names = $allowances[$_SESSION['rego']['lang']];
	//var_dump($allow_names);
	
	$data = array();
	$res = $dbc->query("SELECT * FROM ".$_SESSION['rego']['payroll_dbase']." WHERE id = '".$_REQUEST["id"]."' AND month = '".$_SESSION['rego']['cur_month']."'");
	$row = $res->fetch_assoc();
	$data['Basic salary'] = $row['basic_salary'];
	$data['Salary'] = $row['salary'];
	$data['Actual days'] = $row['actual_days'];
	$data['Paid days'] = $row['paid_days'];
	$data['OT 1'] = $row['ot1h'];
	$data['OT 1.5'] = $row['ot15h'];
	$data['OT 2'] = $row['ot2h'];
	$data['OT 3'] = $row['ot3h'];
	$data['OT Baht'] = $row['ootb'];
	//$data['ot_rate'] = $row['basic_salary']/30/8;
	
	$emp_id = $row['emp_id'];
	$modify_tax = 0;
	$emp_data = getEmployeeInfo($cid, $emp_id);
	
	$tot_fixed = 0;
	$wage = $emp_data['base_salary'];
	foreach($fixAllow as $k=>$v){
		if($v['rate'] == 'Y'){
			$wage += $emp_data['fix_allow_'.$k];
		}
		$tot_fixed += $emp_data['fix_allow_'.$k];
	}
	$data['ot_rate'] = $emp_data['hour_rate']; // get hourrate from employee register ////////////////
	if(!empty($emp_data)){
		$modify_tax = $emp_data['modify_tax'];
	}

	$data['fix'] = array();
	if(isset($usedCols['FA'])){
		foreach($usedCols['FA'] as $k=>$v){
			$data['fix'][$k] = round($row['fix_allow_'.$k],2);
		}
	}
	
	$data['var'] = array();
	if(isset($usedCols['VA'])){
		foreach($usedCols['VA'] as $k=>$v){
			$data['var'][$k] = round($row['var_allow_'.$k],2);
		}
	}
	
	$tot_deductions = 0;//$row['tot_absence'] + $row['uniform'] + $row['deduct_2'] + $row['deduct_3'] + $row['pvf_employee'] + $row['social'];
	$tot_other_income = $row['other_income'] + $row['severance'] + $row['remaining_salary'] + $row['notice_payment'] + $row['paid_leave'];
	
	//var_dump($data); //exit;
	//var_dump($row); //exit;
	
	$mytable = '
		<input type="hidden" value="'.$row['id'].'" name="prid">
		<input type="hidden" value="'.$row['emp_id'].'" name="emp_id">
		<input type="hidden" value="'.$emp_data['hour_rate'].'" name="hour_rate">
		
		<table class="excelTable" style="margin-top:5px">
			<thead>
				<tr style="background:#a00">
					<th colspan="10">'.$lng['General information'].'</th>
				</tr>
				<tr class="title">
					<th>'.$lng['Days paid'].'</th>
					<th>'.$lng['Absence'].' Hrs</th>
					<th>'.$lng['Leave WOP'].' Hrs</th>
					<th>'.$lng['Late Early'].' Hrs</th>
					<th>'.$lng['Modify tax'].'</th>
					<th>'.$lng['Hour rate'].'</th>
					<th style="width:50%"></th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td><input class="float72 sel notnull" type="text" name="paid_days" value="'.$row['paid_days'].'"></td>
					<td><input class="float72 sel notnull" type="text" name="absence" value="'.$row['absence'].'"></td>
					<td><input class="float72 sel notnull" type="text" name="leave_wop" value="'.$row['leave_wop'].'"></td>
					<td><input class="float72 sel notnull" type="text" name="late_early" value="'.$row['late_early'].'"></td>
					<td><input class="sel notnull neg_numeric" type="text" name="modify_tax" value="'.$modify_tax.'"></td>
					<td><input readonly tabindex="-1" class="nofoc" type="text" id="hr_rate" value="'.number_format($data['ot_rate'],2).'"></td>
					<td></td>
				</tr>
			</tbody>
		</table>
		
		<table class="excelTable" style="margin-top:5px">
			<thead>
				<tr style="background:#a00">
					<th colspan="10">'.$lng['OT calculation'].'</th>
				</tr>
				<tr class="title">
					<th>'.$lng['OT base'].'</th>
					<th>'.$lng['OT rate'].'</th>
					<th>'.$lng['OT 1'].'</th>
					<th>'.$lng['OT 1.5'].'</th>
					<th>'.$lng['OT 2'].'</th>
					<th>'.$lng['OT 3'].'</th>
					<th>'.$lng['Other OT'].'</th>
					<th style="width:50%"></th>
					<th>'.$lng['Total'].'</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td><input class="float72 sel notnull" type="text" id="salary1" name="salary1" value="'.$wage.'"></td>
					<td><input readonly tabindex="-1" class="nofoc" type="text" id="rate1" value="'.number_format($data['ot_rate'],2).'"></td>
					<td><input class="float72 sel notnull" type="text" name="ot1_1" value="'.$row['ot1h'].'"></td>
					<td><input class="float72 sel notnull" type="text" name="ot15_1" value="'.$row['ot15h'].'"></td>
					<td><input class="float72 sel notnull" type="text" name="ot2_1" value="'.$row['ot2h'].'"></td>
					<td><input class="float72 sel notnull" type="text" name="ot3_1" value="'.$row['ot3h'].'"></td>
					<td></td>
					<td></td>
					<td></td>
				</tr>
				<tr>
					<td><input class="float72 sel notnull" type="text" id="salary2" name="salary2" value="0"></td>
					<td><input readonly tabindex="-1" class="nofoc" type="text" id="rate2" value="'.number_format(0,2).'"></td>
					<td><input class="float72 sel notnull" type="text" name="ot1_2" value="0"></td>
					<td><input class="float72 sel notnull" type="text" name="ot15_2" value="0"></td>
					<td><input class="float72 sel notnull" type="text" name="ot2_2" value="0"></td>
					<td><input class="float72 sel notnull" type="text" name="ot3_2" value="0"></td>
					<td></td>
					<td></td>
					<td></td>
				</tr>
				<tr>
					<td></td>
					<td></td>
					<td style="font-weight:600"><input tabindex="-1" readonly class="nofoc" type="text" id="tot1" value="'.number_format($row['ot1b'],2).'"></td>
					<td style="font-weight:600"><input tabindex="-1" readonly class="nofoc" type="text" id="tot15" value="'.number_format($row['ot15b'],2).'"></td>
					<td style="font-weight:600"><input tabindex="-1" readonly class="nofoc" type="text" id="tot2" value="'.number_format($row['ot2b'],2).'"></td>
					<td style="font-weight:600"><input tabindex="-1" readonly class="nofoc" type="text" id="tot3" value="'.number_format($row['ot3b'],2).'"></td>
					<td><input class="float72 sel notnull" type="text" name="other_ot" value="'.$row['ootb'].'"></td>
					<td></td>
					<td style="font-weight:600"><input tabindex="-1" readonly class="nofoc" type="text" id="tot_ot" value="'.number_format($row['total_otb'],2).'"></td>
				</tr>
			</tbody>
		</table>';
		
	$mytable .= '	
		<table class="excelTable" style="margin-top:5px">
			<thead>
				<tr>
					<th colspan="10">'.$lng['Allowances'].'</th>
				</tr>
			</thead>
		</table>';
				
		if($data['fix']){
			$mytable .= '<table class="excelTable"><thead><tr class="title">';	
			foreach($data['fix'] as $k=>$v){
				$mytable .= '<th class="tar">'.$fix_allow[$k].'</th>';
			}
			$mytable .= '<th style="width:80%"></th><th>'.$lng['Fixed'].'</th></tr></thead>';
			$mytable .= '</tbody></tr>';
			if($data['fix']){ foreach($data['fix'] as $k=>$v){
				$mytable .= '<td class="input"><input style="background:#ffd" name="fix['.$k.']" class="tar sel float72 notnull" type="text" value="'.$v.'" /></td>';
			}}
			$mytable .= '<td></td><td style="font-weight:600"><input tabindex="-1" readonly class="nofoc" type="text" id="tot_fix_allow" value="'.number_format($row['total_fix_allow'],2).'"></td>';
			
			$mytable .= '</tr></tbody></table>';
		}else{
			$mytable .= '<div style="font-weight:600; color:#a00; padding:1px 8px">'.$lng['There are no fixed allowances for this employee'].'</div>';
		}
		
		if($data['var']){
			$mytable .= '<table class="excelTable"><thead><tr class="title">';	
			foreach($data['var'] as $k=>$v){
				$mytable .= '<th class="tar">'.$var_allow[$k].'</th>';
			}
			$mytable .= '<th style="width:80%"></th><th>'.$lng['Variable'].'</th></tr></thead>';
			$mytable .= '</tbody></tr>';
			if($data['var']){ foreach($data['var'] as $k=>$v){
				$mytable .= '<td class="input"><input style="background:#ffd" name="var['.$k.']" class="tar sel float72 notnull" type="text" value="'.$v.'" /></td>';
			}}
			$mytable .= '<td></td><td style="font-weight:600"><input tabindex="-1" readonly class="nofoc" type="text" id="tot_var_allow" value="'.number_format($row['total_var_allow'],2).'"></td>';
			
			$mytable .= '</tr></tbody></table>';
		}else{
			$mytable .= '<div style="font-weight:600; color:#a00; padding:1px 8px">'.$lng['There are no variable allowances for this employee'].'</div>';
		}
		
	$mytable .= '	
		<table class="excelTable" style="margin-top:5px">
			<thead>
				<tr>
					<th colspan="10">'.$lng['Other income'].'</th>
				</tr>
				<tr class="title">
					<th>'.$lng['Other income'].'</th>
					<th>'.$lng['Severance'].'</th>
					<th>'.$lng['Remaining salary'].'</th>
					<th>'.$lng['Notice payment'].'</th>
					<th>'.$lng['Paid leave'].'</th>
					<th style="width:50%"></th>
					<th>'.$lng['Total'].'</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td><input class="float72 sel notnull" type="text" name="other_income" value="'.$row['other_income'].'"></td>
					<td><input class="float72 sel notnull" type="text" name="severance" value="'.$row['severance'].'"></td>
					<td><input class="float72 sel notnull" type="text" name="remaining_salary" value="'.$row['remaining_salary'].'"></td>
					<td><input class="float72 sel notnull" type="text" name="notice_payment" value="'.$row['notice_payment'].'"></td>
					<td><input class="float72 sel notnull" type="text" name="paid_leave" value="'.$row['paid_leave'].'"></td>
					<td></td>
					<td style="font-weight:600"><input tabindex="-1" readonly class="nofoc" type="text" id="oth_income" value="'.number_format($tot_other_income,2).'"></td>
				</tr>
			</tbody>
		</table>
		
		<table class="excelTable" style="margin-top:5px">
			<thead>
				<tr>
					<th colspan="10">'.$lng['Deductions'].'</th>
				</tr>
				<tr class="title">
					<th>'.$lng['Absence'].'</th>';
					//<th>'.$lng['Uniform'].'</th>
					//<th>'.$lng['Other deduct'].'</th>
					$mytable .= '
					<th>'.$lng['PVF'].'</th>
					<th>'.$lng['SSO'].'</th>
					<th style="width:50%"></th>
					<th>'.$lng['Total'].'</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td><input readonly tabindex="-1" class="nofoc" type="text" id="tot_absence" value="'.number_format($row['tot_absence'],2).'"></td>';
					//<td><input class="float72 sel notnull" type="text" name="uniform" value="'.$row['uniform'].'"></td>
					//<td><input class="float72 sel notnull" type="text" name="deduct_3" value="'.$row['deduct_3'].'"></td>
					$mytable .= '
					<td><input class="float72 sel notnull" type="text" name="pvf" value="'.$row['pvf_employee'].'"></td>
					<td><input class="float72 sel notnull" type="text" name="sso" value="'.$row['social'].'"></td>
					<td></td>
					<td style="font-weight:600"><input tabindex="-1" readonly class="nofoc" type="text" id="tot_deduct" value="'.number_format($tot_deductions,2).'"></td>
				</tr>
			</tbody>
		</table>
		
	<table style="width:100%"><tr><td style="width:50%;vertical-align:top">
		
		<table class="excelTable" style="margin-top:5px">
			<thead>
				<tr>
					<th colspan="2">'.$lng['Financials'].'</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td class="tal pad18">'.$lng['Salary'].'</td>
					<td><input class="float72 sel notnull" type="text" id="salary" name="salary" value="'.$row['salary'].'"></td>
				</tr>
				<tr>
					<td class="tal pad18">'.$lng['Overtime'].'</td>
					<td><input tabindex="-1" readonly class="nofoc" type="text" id="total_ot" value="'.number_format($row['total_otb'],2).'"></td>
				</tr>
				<tr>
					<td class="tal pad18">'.$lng['Allowances'].'</td>
					<td><input tabindex="-1" readonly class="nofoc" type="text" id="total_allow" value="'.number_format(($row['total_var_allow']+$row['total_fix_allow']),2).'"></td>
				</tr>
				<tr>
					<td class="tal pad18">'.$lng['Other income'].'</td>
					<td><input tabindex="-1" readonly class="nofoc" type="text" id="other_income" value="'.number_format($tot_other_income,2).'"></td>
				</tr>
				<tr style="background:#f6f6f6">
					<td class="tal pad18" style="font-weight:600">'.$lng['Total income'].'</td>
					<td style="font-weight:600; width:100px"><input tabindex="-1" readonly class="nofoc" type="text" id="tot_income" value="'.number_format($row['gross_income'],2).'"></td>
				</tr>
			</tbody>
		</table>
		
	</td><td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th><td style="width:50%;vertical-align:top">
	
		<table class="excelTable" style="margin-top:5px">
			<thead>
				<tr>
					<th colspan="2">'.$lng['Tax calculation'].'</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td class="tal pad18">'.$lng['Gross income'].'</td>
					<td><input tabindex="-1" readonly class="nofoc" type="text" id="gross" value="'.number_format($row['gross_income'],2).'"></td>
				</tr>
				<tr>
					<td class="tal pad18">'.$lng['Deductions'].'</td>
					<td><input tabindex="-1" readonly class="nofoc" type="text" id="tot_deductions" value="'.number_format($tot_deductions,2).'"></td>
				</tr>
				<tr>
					<td class="tal pad18">'.$lng['Taxable income'].'</td>
					<td><input tabindex="-1" readonly class="nofoc" type="text" id="taxable" value="'.number_format(($row['gross_income']-$tot_deductions),2).'"></td>
				</tr>
				<tr>
					<td class="tal pad18">'.$lng['Tax'].'</td>
					<td><input tabindex="-1" readonly class="nofoc" type="text" id="tax" value="'.number_format($row['tax_month'],2).'"></td>
				</tr>
				<tr style="background:#f6f6f6">
					<td class="tal pad18" style="font-weight:600">'.$lng['Net income'].'</td>
					<td style="font-weight:600; width:100px"><input tabindex="-1" readonly class="nofoc" type="text" id="net_income" value="'.number_format($row['net_income'],2).'"></td>
				</tr>
			</tbody>
		</table>

	</td></tr></table>';
	
			
	echo $mytable;


?>





