<?php
	set_time_limit(600);
	$style = '
		<style>
			@page {
				  margin:0px;
				 }
			body, div, p {
				font-family: "leelawadee", "garuda";
				font-size: 12px;
				line-height:130%;
			}
			table.wrap {
				border-collapse:collapse;
				border:0px solid red;
				width:100%;
				margin:6px 0 0px 0;
			}
			table.topslip {
				border-collapse:collapse;
				border:0px #000 solid;
				width:100%;
				table-layout:fixed;
			}
			table.topslip td {
				padding:0;
				line-height:120%;
				font-size:12px;
				vertical-align:bottom;
				white-space:nowrap;
				font-weight:normal;
				overflow:hidden;
				white-space:nowrap;
			}
			table.payslip {
				border-collapse:collapse;
				border:0px #000 solid;
				table-layout:fixed;
				overflow:hidden;
			}
			table.payslip th, table.payslip td {
				border:1px solid #000;
				padding:1px 4px;
				line-height:normal;
				color:#111;
				font-family: inherit;
				vertical-align:middle;
				font-size:12px;
				overflow:hidden;
				white-space:nowrap;
			}
			table.payslip th {
				text-align:center;
				line-height:100%;
				font-weight:normal;
			}
			table.payslip td {
				font-weight:normal;
				text-align:right;
				padding:5px 8px;
			}
		</style>';
	
	$html = '<html><body>';
	
	while($row = $res->fetch_object()){
		$month = $row->month;
		$pd = getPaydateMonth($month);
		if($lang == 'th'){
			$paydate = substr($pd,0,-4).(substr($pd,-4)+543);
			$period = $months[(int)$month].' '.(substr($pd,-4)+543);
		}else{
			$paydate = substr($pd,0,-4).substr($pd,-4);
			$period = $months[(int)$month].' '.substr($pd,-4);
		}
		$data = getPayslipData($row->emp_id, $month, $lang, $sys_settings['payslip_rate']);
		$empinfo = getEmployeeInfo($cid, $row->emp_id);
		
$html .= '<div style="padding:2px 0;font-weight:bold;border:0px solid #000;font-size:15px;text-align:center">ใบจ่ายเงินเดือน / PAYSLIP</div>
			<table class="topslip" border="0" style="margin:10px 0 0 0">
				<tr>
					<td style="width:70px">รหัส :<br>Emp. Code :</td>
					<td style="width:140px">'.$data['emp_id'].'</td>
					<td style="width:320px; text-align:center; font-size:16px; vertical-align:middle; font-weight:bold">'.$compinfo[$lang.'_compname'].'</td>
					<td style="width:50px">งวดเดือน :<br>Period :</td>
					<td>'.$period.'</td>
				</tr>
			</table>
			
			<table class="topslip" border="0" style=" margin:10px 0 20px 0">
				<tr>
					<td style="width:48px">ชื่อ-สกุล :<br>Name :</td>
					<td style="width:220px">'.$data['name_'.$lang].'</td>
					<td style="width:53px;">ตำแหน่ง :<br>Position :</td>
					<td style="width:210px">'.$data['position'].'</td>
					<td style="width:40px">แผนก :<br>Dept. :</td>
					<td> -</td>
				</tr>
			</table>
			
			<table class="wrap" border="0"><tr><td style="vertical-align:top;text-align:left">
				
				<table class="payslip" border="1">
					<tr>
						<th style="width:165px; height:35px">รายได้<br>Earnings</th>
						<th style="width:60px">จำนวน<br>Number</th>
						<th style="width:90px">จำนวนเงิน<br>Amount</th>
						<th style="width:165px">รายการหัก<br>Deductions</th>
						<th style="width:60px">จำนวน<br>Number</th>
						<th style="width:90px">จำนวนเงิน<br>Amount</th>
					</tr>
					<tr>
						<td style="height:240px; text-align:left; vertical-align:top">';
						foreach($data['earnings'] as $k=>$v){
							$html .= $v[0].'<br>';
						}
			$html .= '</td>
						<td style="vertical-align:top">';
						foreach($data['earnings'] as $k=>$v){
							$html .= $v[1].'<br>';
						}
			$html .= '</td>
						<td style="vertical-align:top">';
						foreach($data['earnings'] as $k=>$v){
							$html .= $v[2].'<br>';
						}
			$html .= '</td>
						<td style="text-align:left; vertical-align:top">';
						foreach($data['deduct'] as $k=>$v){
							$html .= $v[0].'<br>';
						}
			$html .= '</td>
						<td style="vertical-align:top">';
						foreach($data['deduct'] as $k=>$v){
							$html .= $v[1].'<br>';
						}
			$html .= '</td>
						<td style="vertical-align:top">';
						foreach($data['deduct'] as $k=>$v){
							$html .= $v[2].'<br>';
						}
			$html .= '</td>
					</tr>
				</table>
				
				<table class="payslip" border="1" style="margin-top:5px">
					<tr>
						<th style="height:35px;width:225px">รวมเงินได้<br>Total Earnings</th>
						<td style="width:90px">'.$data['gross_income'].'</td>
						<th style="width:225px">รวมรายการหัก<br>Total Deductions</th>
						<td style="width:90px">'.$data['tot_deductions'].'</td>
					</tr>
				</table>
				
				<table class="payslip" border="1" style="margin-top:5px">
					<tr>
						<td style="height:30px">';
						if(isset($field['ytd1'])){ $html .= $data['asalary'];}
			$html .= '</td><td>';
						if(isset($field['ytd2'])){ $html .= $data['atax'];}
			$html .= '</td><td>';
						if(isset($field['ytd3'])){ $html .= $data['aprovfund'];}
			$html .= '</td><td>';
						if(isset($field['ytd4'])){ $html .= $data['asocial'];}
			$html .= '</td><td>';
						if(isset($field['ytd5'])){ $html .= $data['aother'];}
			$html .= '</td>
					</tr>
					<tr>
						<th style="width:126px;height:35px">เงินได้สะสมต่อปี<br>YTD. Income</th>
						<th style="width:126px">ภาษีสะสมต่อปี<br>YTD. Tax</th>
						<th style="width:126px">เงินสะสมกองทุนต่อปี<br>YTD. Prov. Fund</th>
						<th style="width:126px"><span>เงินสะสมกองทุนต่อปี</span><br>YTD. Social SF</th>
						<th style="width:126px">ค่าลดหย่อนอื่นๆ<br>Other Allowance</th>
					</tr>
				</table>

			</td><td style="padding-left:7px;vertical-align:top;text-align:right">
				
				<table class="payslip" border="1">
					<tr>
						<th style="height:35px">วันที่จ่าย<br>Payroll Date<br><img style="height:1px;width:130px" src="../../images/blank.gif" /></th>
					</tr>
					<tr>
						<td style="height:50px; text-align:center; font-size:14px">'.$paydate.'</td>
					</tr>
				</table>
				
				<table class="payslip" border="1" style="margin-top:8px;">
					<tr>
						<th style="height:35px">เลขทืบ้ญชี<br>Acc. No.<br><img style="height:1px;width:130px" src="../../images/blank.gif" /></th>
					</tr>
					<tr>
						<td style="height:50px; text-align:center; font-size:14px">'.$data['account'].'</td>
					</tr>
				</table>
			
				<table class="payslip" border="1" style="margin-top:8px;">
					<tr>
						<th style="height:35px">เงินรับสุทธิ<br>Net to Pay<br><img style="height:1px;width:130px" src="../../images/blank.gif" /></th>
					</tr>
					<tr>
						<td style="height:51px; text-align:center; font-size:14px"><b>'.$data['net_income'].' THB</b></td>
					</tr>
				</table>
			
				<table class="topslip" border="0" style="margin-top:8px;width:130px">
					<tr>
						<td style="height:99px; text-align:center; vertical-align:bottom; font-size:11px">...........................................<br>ลงชื่อพน้กงาน<br>Signature Employee</td>
					</tr>
				</table>

			</td></tr></table>';
			//if($nr % 2 == 0 && $nr < $rows){$html .= '<pagebreak />';}
			$nr ++;
	}

	$html .= '</body></html>';	
	//echo $style.$html; exit;	
	
	require_once("../../mpdf7/vendor/autoload.php");

	//class mPDF ([ string $mode [, mixed $format [, float $default_font_size [, string $default_font [, float $margin_left , float $margin_right , float $margin_top , float $margin_bottom , float $margin_header , float $margin_footer [, string $orientation ]]]]]])
	$mpdf=new mPDF('utf-8',  array(203,139), 8, '', 5, 5, 5, 0, 0, 0);
	$mpdf->SetTitle($compinfo[$lang.'_compname'].' ('.strtoupper($_SESSION['rego']['cid']).') - Payslips '.date('F', mktime(0, 0, 0, $month, 1)).' '.$_SESSION['rego']['cur_year']);
	
	$mpdf->WriteHTML($style,1);
	$mpdf->WriteHTML($html);
	//$mpdf->Output();
	$mpdf->Output($_SESSION['rego']['cid'].'_matrix_blank_payslips_'.$month.'_'.$_SESSION['rego']['cur_year'].'.pdf','I');
	exit;
	
?>





















