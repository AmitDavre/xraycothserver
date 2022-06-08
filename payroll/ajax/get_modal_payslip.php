<?php
	if(session_id()==''){session_start(); ob_start();}
	include('../../dbconnect/db_connect.php');
	include(DIR.'files/functions.php');
	include(DIR.'files/arrays_'.$_SESSION['rego']['lang'].'.php');
	include(DIR.'files/payroll_functions.php');
	//include('../inc/tax_modulle.php');
//$_REQUEST["id"] = 10080;

	//$data = getPayslipData($_REQUEST["id"],$_SESSION['rego']['cur_month'],$_SESSION['rego']['lang'],'em');
	$data = getPayslipData($_REQUEST["id"],$_SESSION['rego']['cur_month'],$_SESSION['rego']['lang'],$sys_settings['payslip_rate']);
	//$data = getPayslipData($row->emp_id, $month, $lang, $pr_settings['payslip_rate']);
	//var_dump($data); exit;
	$table = '
			<table style="width:100%;margin-top:6px" border="0">
				<tr>
					<td><b>'.$lng['Employee'].' :</b> '.$data['emp_id'].' - '.$data['name_'.$_SESSION['rego']['lang']].'</td>
					<td><b>'.$lng['Position'].' :</b> '.$data['position'].'</td>
					<td class="tar"><b>'.$lng['Period'].' :</b> '.$data['period'].'</td>
				</tr>
			</table>
			<table class="payslipTable" style="margin-top:3px" border="0">
				<tr>
					<th style="width:35%">'.$lng['Earnings'].'</th>
					<th class="tac">'.$lng['Number'].'</th>
					<th class="tac">&nbsp;&nbsp;&nbsp;&nbsp;'.$lng['Amount'].'&nbsp;&nbsp;&nbsp;&nbsp;</th>
					<th style="width:35%">'.$lng['Deductions'].'</th>
					<th class="tac">'.$lng['Number'].'</th>
					<th class="tac">&nbsp;&nbsp;&nbsp;&nbsp;'.$lng['Amount'].'&nbsp;&nbsp;&nbsp;&nbsp;</th>
				</tr>
					<tr style="height:250px">
						<td valign="top" style="text-align:left">';
						foreach($data['earnings'] as $k=>$v){
							$table .= $v[0].'<br>';
						}
		$table .= ' </td>
						<td valign="top">';
						foreach($data['earnings'] as $k=>$v){
							$table .= $v[1].'<br>';
						}
		$table .= ' </td>
						<td valign="top">';
						foreach($data['earnings'] as $k=>$v){
							$table .= $v[2].'<br>';
						}
		$table .= ' </td>
						<td valign="top" style="text-align:left">';
						foreach($data['deduct'] as $k=>$v){
							$table .= $v[0].'<br>';
						}
		$table .= ' </td>
						<td valign="top">';
						foreach($data['deduct'] as $k=>$v){
							$table .= $v[1].'<br>';
						}
		$table .= ' </td>
						<td valign="top">';
						foreach($data['deduct'] as $k=>$v){
							$table .= $v[2].'<br>';
						}
		$table .= ' </td>
					</tr>
					<tr>
						<th class="tar">'.$lng['Total earnings'].'</th>
						<td colspan="2">'.$data['gross_income'].'</td>
						<th class="tar">'.$lng['Total deductions'].'</th>
						<td colspan="2">'.$data['tot_deductions'].'</td>
					</tr>
					<tr>
						<th colspan="4" class="tar">'.$lng['Net to pay'].'</th>
						<td colspan="2"><b>'.$data['net_income'].'</b></td>
					</tr>
			</table>
			<table class="payslipTable" style="table-layout:fixed; margin-top:10px">
				<tr>
					<td>'.$data['asalary'].'</td>
					<td>'.$data['atax'].'</td>';
					if($sys_settings['calc_pvf'] && $data['aprovfund'] > 0){$table .= '<td>'.$data['aprovfund'].'</td>';}	
				$table .= '	
					<td>'.$data['asocial'].'</td>
					<td>'.$data['aother'].'</td>
				</tr>
				<tr>
					<th class="tac" style="white-space:normal; line-height:100%; padding:6px">'.$lng['YTD. Income'].'</th>
					<th class="tac" style="white-space:normal; line-height:100%">'.$lng['YTD. Tax'].'</th>';
				if($sys_settings['calc_pvf'] && $data['aprovfund'] > 0){$table .= '<th class="tac" style="white-space:normal; line-height:100%">'.$lng['YTD. Prov. Fund'].'</th>';}	
				$table .= '	
					<th class="tac" style="white-space:normal; line-height:100%">'.$lng['YTD. Social SF'].'</th>
					<th class="tac" style="white-space:normal; line-height:100%">'.$lng['YTD. Other allowance'].'</th>
				</tr>
			</table>
			
			';
	
	echo $table;
	exit;
?>