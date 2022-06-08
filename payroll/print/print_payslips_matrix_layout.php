<?php
	set_time_limit(600);
	$style = '
		<style>
			@page {
				  margin:5mm 0 0 0;
				 }
			body, div, p {
				font-family: "leelawadee", "garuda";
				font-size: 12px;
				line-height:130%;
			}
			body {
				background:#fff url(images/xpayslip_a5.jpg) no-repeat; 
				background-image-resize:6;
			}
			div.field {
				position:absolute;
				min-height:12px;
				border:0em solid #fff;
				font-size:12px;
				line-height:100%;
			}
			div.bold{
				font-weight:normal;
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
		//var_dump($data); exit;
		
		$html .= '
		<div class="field bold" style="top:12mm; left:19mm; width:30mm">'.$data['emp_id'].'</div>
		<div class="field bold" style="top:14mm; left:58mm; width:85mm; text-align:center; font-size:15px">'.$compinfo[$lang.'_compname'].'</div>
		<div class="field bold" style="top:12mm; left:156mm; width:40mm">'.$period.'</div>
		
		<div class="field bold" style="top:20mm; left:16mm; width:68mm">'.$data['name_'.$lang].'</div>
		<div class="field bold" style="top:20mm; left:97mm; width:45mm">'.$data['position'].'</div>
		<div class="field bold" style="top:20mm; left:153mm; width:45mm"> -</div>
		
		<div class="field bold" style="top:39.5mm; left:6mm; width:37mm; line-height:4.4mm; letter-spacing:-0.5px">';
			foreach($data['earnings'] as $k=>$v){
				$html .= $v[0].'<br>';
			}
		$html .= '</div>
		<div class="field bold" style="top:39.5mm; left:44mm; width:12mm; line-height:4.4mm; text-align:right">';
			foreach($data['earnings'] as $k=>$v){
				$html .= $v[1].'<br>';
			}
		$html .= '</div>
		<div class="field bold" style="top:39.5mm; left:58mm; width:23mm; line-height:4.4mm; text-align:right">';
			foreach($data['earnings'] as $k=>$v){
				$html .= $v[2].'<br>';
			}
		$html .= '</div>
		
		<div class="field bold" style="top:39.5mm; left:86mm; width:37mm; line-height:4.4mm; letter-spacing:-0.5px">';
			foreach($data['deduct'] as $k=>$v){
				$html .= $v[0].'<br>';
			}
		$html .= '</div>
		<div class="field bold" style="top:39.5mm; left:124mm; width:12mm; line-height:4.4mm; text-align:right">';
			foreach($data['deduct'] as $k=>$v){
				$html .= $v[1].'<br>';
			}
		$html .= '</div>
		<div class="field bold" style="top:39.5mm; left:138mm; width:23mm; line-height:4.4mm; text-align:right">';
			foreach($data['deduct'] as $k=>$v){
				$html .= $v[2].'<br>';
			}
		$html .= '</div>
		
		<div class="field bold" style="top:43mm; left:166mm; width:32mm; text-align:center; font-size:13px">'.$paydate.'</div>
		<div class="field bold" style="top:72mm; left:166mm; width:32mm; text-align:center; font-size:13px">'.$data['account'].'</div>
		<div class="field bold" style="top:99mm; left:166mm; width:32mm; text-align:center; font-size:13px">'.$data['net_income'].'</div>
		
		<div class="field bold" style="top:112.5mm; left:44mm; width:37mm; text-align:right;">'.$data['gross_income'].'</div>
		<div class="field bold" style="top:112.5mm; left:124mm; width:37mm; text-align:right;">'.$data['tot_deductions'].'</div>
		
		<div class="field bold" style="top:121.5mm; left:5mm; width:28mm; text-align:right;">';
		if(isset($field['ytd1'])){ $html .= $data['asalary'];}
		$html .= '</div>
		<div class="field bold" style="top:121.5mm; left:37mm; width:28mm; text-align:right;">';
		if(isset($field['ytd2'])){ $html .= $data['atax'];}
		$html .= '</div>
		<div class="field bold" style="top:121.5mm; left:69mm; width:28mm; text-align:right;">';
		if(isset($field['ytd3'])){ $html .= $data['aprovfund'];}
		$html .= '</div>
		<div class="field bold" style="top:121.5mm; left:101mm; width:28mm; text-align:right;">';
		if(isset($field['ytd4'])){ $html .= $data['asocial'];}
		$html .= '</div>
		<div class="field bold" style="top:121.5mm; left:133mm; width:28mm; text-align:right;">';
		if(isset($field['ytd5'])){ $html .= $data['aother'];}
		$html .= '</div>';
		$nr++;
		if($nr < $rows){$html .= '<pagebreak />';}
	}
	
	$html .= '</body></html>';	
	//echo $style.$html; exit; 
	
	require_once("../../mpdf7/vendor/autoload.php");
	//class mPDF ([ string $mode [, mixed $format [, float $default_font_size [, string $default_font [, float $margin_left , float $margin_right , float $margin_top , float $margin_bottom , float $margin_header , float $margin_footer [, string $orientation ]]]]]])
	$mpdf=new mPDF('utf-8', array(203,139), 8, '', 0, 0, 0, 0, 0, 0);
	$mpdf->SetTitle($compinfo[$lang.'_compname'].' ('.strtoupper($_SESSION['rego']['cid']).') - Payslips '.date('F', mktime(0, 0, 0, $month, 1)).' '.$_SESSION['rego']['cur_year']);
	
	$mpdf->WriteHTML($style,1);
	$mpdf->WriteHTML($html);
	//$mpdf->Output();
	$mpdf->Output($_SESSION['rego']['cid'].'_matrix_preprinted_payslips_'.$month.'_'.$_SESSION['rego']['cur_year'].'.pdf','I');
	
	exit;
	
?>





















