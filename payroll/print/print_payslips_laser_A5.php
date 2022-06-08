<?php
	set_time_limit(600);
	
	$style = '
		<style>
			@page {
				  margin:0px;
				 }
			body, div, p {
				font-family: "leelawadee", "garuda";
				font-size: 13px;
				
			}
			table.wrap {
				border-collapse:collapse;
				border:0px solid red;
				width:100%;
				margin:6px 0 0px 0;
			}
			.topslipTable {
				width:100%;
				border-collapse:collapse;
			}
			.topslipTable td {
				white-space:nowrap;
				padding:0px 0;
			}
			.payslipTable {
				width:100%;
				border-collapse:collapse;
				font-family:Arial, Helvetica, sans-serif;
			}
			.payslipTable th,
			.payslipTable td {
				white-space:nowrap;
				padding:5px 10px;
				border:1px solid #666;
			}
			.tar {
				text-align:right;
			}
			.tac {
				text-align:center;
			}
			.tal {
				text-align:left;
			}
			.vat {
				vertical-align:top;
			}
		</style>';
	
	$html = '<html><body>';
	
	while($row = $res->fetch_object()){
		if(!isset($_GET['month'])){
			$month = $row->month;
		}
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
		//var_dump($empinfo);

	$html .= '<table class="topslipTable" border="0" style="margin-bottom:4px; font-size:18px">
							<tr>
								<td style="max-width:150px"><img height="30px" src="../../'.$compinfo['logofile'].'"></td>
								<th style="padding-left:10px; white-space:nowrap" class="tal">'.$compinfo[$lang.'_compname'].'</th>
								<th style="white-space:nowrap" class="tar">ใบจ่ายเงินเดือน/PAYSLIP</th>
							</tr>
						</table>';

	$html .= '<table class="topslipTable" border="0" style="margin-bottom:6px">
							<tr>
								<td>รหัส / Emp.ID : <b>'.$data['emp_id'].'</b></td>
								<td class="tar">งวดเดือน / Period : <b>'.$period.'</b></td>
							</tr>
							<tr>
								<td>ชื่อ-สกุล / Name : <b>'.$title[$empinfo['title']].' '.$data['name_'.$lang].'</b></td>
								<td class="tar">วันจ่าย / Payment date : <b>'.$paydate.'</b></td>
							</tr>
							<tr>
								<td>แผนก / Department : <b>';
								if($sys_settings['show_department'] && !empty($data['department'])){$html .= $data['department'];}else{$html .= ' - ';}
				$html .= '</b>
								</td>
								<td class="tar">ตำแหน่ง / Position : <b>';
								if($sys_settings['show_position'] && !empty($data['position'])){$html .= $data['position'];}else{$html .= ' - ';}
				$html .= '</b>
								</td>
							</tr>
							<tr>
								<td>ธนาคาร / Bank : <b>';
								if($sys_settings['show_bankinfo'] && !empty($data['bank'])){$html .= $data['bank'];}else{$html .= ' - ';}
				$html .= '</b>
								</td>
								<td class="tar">บัญชี / Account : <b>';
								if($sys_settings['show_bankinfo'] && !empty($data['account'])){$html .= $data['account'];}else{$html .= ' - ';}
				$html .= '</b>
								</td>							
							</tr>
						</table>';

	$html .= '<table class="payslipTable" border="0" style="margin-bottom:5px">
							<tr>
								<th style="width:28%">รายได้<br>Earnings</th>
								<th style="width:9%">จำนวน<br>Number</th>
								<th style="width:13%">จำนวนเงิน<br>Amount</th>
								<th style="width:28%">รายการหัก<br>Deductions</th>
								<th style="width:9%">จำนวน<br>Number</th>
								<th style="width:13%">จำนวนเงิน<br>Amount</th>
							</tr>
							<tr>
								<td style="height:240px;line-height:130%;" class="tal vat">';
								foreach($data['earnings'] as $k=>$v){
									$html .= $v[0].'<br>';
								}
		  $html .= '</td>
								<td class="tar vat" style="line-height:130%">';
								foreach($data['earnings'] as $k=>$v){
									$html .= $v[1].'<br>';
								}
			$html .= '</td>
								<td class="tar vat" style="line-height:130%">';
								foreach($data['earnings'] as $k=>$v){
									$html .= $v[2].'<br>';
								}
			$html .= '</td>
								<td class="tal vat" style="line-height:130%">';
								foreach($data['deduct'] as $k=>$v){
									$html .= $v[0].'<br>';
								}
			$html .= '</td>
								<td class="tar vat" style="line-height:130%">';
								foreach($data['deduct'] as $k=>$v){
									$html .= $v[1].'<br>';
								}
			$html .= '</td>
								<td class="tar vat" style="line-height:130%">';
								foreach($data['deduct'] as $k=>$v){
									$html .= $v[2].'<br>';
								}
			$html .= '</td>
							</tr>
							<tr>
								<th colspan="2" class="tar">รวมเงินได้ / Total Earnings</th>
								<th class="tar">'.$data['gross_income'].'</th>
								<th colspan="2" class="tar">รวมรายการหัก / Total Deductions</th>
								<th class="tar">'.$data['tot_deductions'].'</th>
							</tr>
							<tr>
								<th colspan="5" class="tar">เงินรับสุทธ / Net to Pay</th>
								<th class="tar">'.$data['net_income'].'</th>
							</tr>
						</table>';
				
	$html .= '<table class="payslipTable" border="0">
							<tr>
								<td class="tac">';
								if($field['ytd1']){$html .= $data['asalary'];}
			$html .= '</td>
								<td class="tac">';
								if($field['ytd2']){$html .= $data['atax'];}
			$html .= '</td>
								<td class="tac">';
								if($field['ytd3']){$html .= $data['aprovfund'];}
			$html .= '</td>
								<td class="tac">';
								if($field['ytd4']){$html .= $data['asocial'];}
			$html .= '</td>
								<td class="tac">';
								if($field['ytd5']){$html .= $data['aother'];}
			$html .= '</td>
							</tr>
							<tr>
								<th style="width:20%">เงินได้สะสมต่อปี<br>YTD. Income</th>
								<th style="width:20%">ภาษีสะสมต่อปี<br>YTD. Tax</th>
								<th style="width:20%">เงินสะสมกองทุนต่อปี<br>YTD. Prov. Fund</th>
								<th style="width:20%"><span>เงินสะสมกองทุนต่อปี</span><br>YTD. Social SF</th>
								<th style="width:20%">ค่าลดหย่อนอื่นๆ<br>Other Allowance</th>
							</tr>
						</table>';
						//<pagebreak />
			$nr ++;
	}

	$html .= '</body></html>';	
	//echo $style.$html; exit;	
	
	require_once("../../mpdf7/vendor/autoload.php");

	//class mPDF ([ string $mode [, mixed $format [, float $default_font_size [, string $default_font [, float $margin_left , float $margin_right , float $margin_top , float $margin_bottom , float $margin_header , float $margin_footer [, string $orientation ]]]]]])
	$mpdf=new mPDF('utf-8', 'A5-L', 8, '', 7, 7, 5, 5, 0, 0);
	$mpdf->SetTitle($compinfo[$lang.'_compname'].' ('.strtoupper($_SESSION['rego']['cid']).') - Payslips '.date('F', mktime(0, 0, 0, $month, 1)).' '.$_SESSION['rego']['cur_year']);
	
	$mpdf->WriteHTML($style,1);
	$mpdf->WriteHTML($html);
	
	$dir = DIR.$_SESSION['rego']['cid'].'/archive/';
	$root = ROOT.$_SESSION['rego']['cid'].'/archive/';
	
	$baseName = $_SESSION['rego']['cid'].'_payslips_'.$_SESSION['rego']['curr_month'].'-'.$_SESSION['rego']['year_'.$lang];
	$extension = 'pdf';		
	$filename = getFilename($baseName, $extension, $dir);
	$doc = $lng['Payslips'].' '.$_SESSION['rego']['curr_month'].'-'.$_SESSION['rego']['year_'.$lang];
	
	if(isset($_GET['a'])){
		$mpdf->Output(iconv("UTF-8", "TIS-620",$dir.$filename),'F');
	}
	
	$mpdf->Output($filename,'I');
	
	if(isset($_GET['a'])){
		include('save_to_documents.php');
	}
	exit;	
	
	
	
	
	
	
	
	
	//$mpdf->Output();
	$mpdf->Output($_SESSION['rego']['cid'].'_payslips_'.$month.'_'.$_SESSION['rego']['cur_year'].'.pdf','I');
	
	if(isset($_GET['a'])){
		include('save_to_documents.php');
	}
	
?>





















