<?php
	
	if(session_id()==''){session_start(); ob_start();}
	include('../../dbconnect/db_connect.php');
	include(DIR.'files/functions.php');
	//include(DIR.'files/arrays_'.$_SESSION['rego']['lang'].'.php');
	include(DIR.'files/payroll_functions.php');
	
	//var_dump($_REQUEST); exit;

	if($res = $dbc->query("SELECT th_addr_detail, th_compname, comp_phone, tax_id, revenu_branch, dig_signature, digi_signature, dig_stamp, digi_stamp FROM ".$cid."_entities_data WHERE ref = '".$_SESSION['rego']['gov_entity']."'")){
		$data = $res->fetch_assoc();
		$address = unserialize($data['th_addr_detail']);
	}else{
		//echo mysqli_error($dbc);
	}

	$emps = 0; $income = 0; $tax = 0;
	if($res = $dbc->query("SELECT gross_income, tax_month, calc_tax FROM ".$_SESSION['rego']['payroll_dbase']." WHERE month = '".$_SESSION['rego']['gov_month']."' AND entity = '".$_SESSION['rego']['gov_entity']."' AND emp_group = '".$_SESSION['rego']['emp_group']."'")){
		while($row = $res->fetch_object()){
			//$empinfo = getEmployeeInfo($_SESSION['rego']['cid'], $row->emp_id);
			if($row->calc_tax){
				$emps ++;
				$income += $row->gross_income;
				$tax += $row->tax_month;
			}
		}
	}
	
	$name_position = getFormNamePosition($cid);

	for($i=1;$i<=12;$i++){
		$month[$i] = '';
	}
	$month[$_SESSION['rego']['gov_month']] = '<img src="../../images/forms/check.png">';

	$pin = str_replace('-','',$data['tax_id']);
	if(strlen($pin)!== 13){$pin = '?????????????';}
	$pin = str_split($pin);
	
	$branch = sprintf("%05d",$data['revenu_branch']);
	$branch = str_split($branch);

	//$address = unserialize($compinfo['th_addr_detail']);
	if($address && $address['postal'] == ''){$address['postal'] = '?????';}
	if(strlen($address['postal']) != 5){$address['postal'] = '?????';}
	$post = str_split($address['postal']);
	
	$rfill[1] = ''; 
	$rfill[2] = '';
	$rfill[(int)$_REQUEST['rfill']] = '<img src="../../images/forms/check.png">';
	
	$xfill[1] = ''; 
	$xfill[2] = '';
	$xfill[3] = '';
	$xfill[(int)$_REQUEST['xfill']] = '<img src="../../images/forms/check.png">';
	
	$pages = 1;
	if($emps > 7){
		$pages = ceil($emps/7);
	}

$style = '
	<style>
		@page {
			margin: 10px 100px 10px 10px;
		}
		body, html {
			font-family: "leelawadee", "garuda";
			font-family: "leelawadee";
			font-size:14px;
			color:#036;
		}
		body {
			background:url(../../images/forms/pnd3_th_pdf.png?'.time().') no-repeat;
			background-image-resize:6;
		}
		div.field, div.field14, div.field13n, div.field13c, div.field11, div.field12 {
			position:absolute;
			min-height:12px;
			min-width:20px;
			border:0px solid red;
			font-size:12px;
			line-height:100%;
			white-space:nowrap;
			xbackground:orange;
		}
		div.field14 {
			font-size:14px;
		}
		div.signature {
			width:200px;
			position:absolute;
			border:0px solid red;
			text-align:center;
		}
		div.stamp {
			position:absolute;
			border:0px solid red;
			text-align:center;
		}
		i.fa {
			font-family: fontawesome;
			font-style:normal;
			color:#000;
		}
		.tac {text-align:center;}
		.tar {text-align:right;}
		.w25 {width:25px;}
		.w60 {width:60px;}
		.w100 {width:100px;}

	</style>';
	
$html = '<html><body style="background:url(../../images/forms/pnd3_th_pdf.png) no-repeat;">

			<div class="field14 tac" style="top:36.6mm;left:56.6mm">'.$pin[0].'</div>
			
			<div class="field14 tac" style="top:36.6mm;left:62.4mm">'.$pin[1].'</div>
			<div class="field14 tac" style="top:36.6mm;left:66.6mm">'.$pin[2].'</div>
			<div class="field14 tac" style="top:36.6mm;left:70.6mm">'.$pin[3].'</div>
			<div class="field14 tac" style="top:36.6mm;left:74.6mm">'.$pin[4].'</div>
			
			<div class="field14 tac" style="top:36.6mm;left:80.6mm">'.$pin[5].'</div>
			<div class="field14 tac" style="top:36.6mm;left:84.6mm">'.$pin[6].'</div>
			<div class="field14 tac" style="top:36.6mm;left:88.6mm">'.$pin[7].'</div>
			<div class="field14 tac" style="top:36.6mm;left:92.6mm">'.$pin[8].'</div>
			<div class="field14 tac" style="top:36.6mm;left:96.6mm">'.$pin[9].'</div>
			
			<div class="field14 tac" style="top:36.6mm;left:102.6mm">'.$pin[10].'</div>
			<div class="field14 tac" style="top:36.6mm;left:106.6mm">'.$pin[11].'</div>
			
			<div class="field14 tac" style="top:36.6mm;left:112.6mm">'.$pin[12].'</div>

			<div class="field" style="top:43mm;left:186mm;width:70px">'.$_SESSION['rego']['year_th'].'</div>

			<div class="field14 tac" style="top:47.6mm;left:97.4mm">'.$branch[0].'</div>
			<div class="field14 tac" style="top:47.6mm;left:101.2mm">'.$branch[1].'</div>
			<div class="field14 tac" style="top:47.6mm;left:105.0mm">'.$branch[2].'</div>
			<div class="field14 tac" style="top:47.6mm;left:109mm">'.$branch[3].'</div>
			<div class="field14 tac" style="top:47.6mm;left:113mm">'.$branch[4].'</div>

			<div class="field" style="top:54mm;left:14mm;width:390px">'.$data["th_compname"].'</div>
			
			<div class="field" style="top:59.4mm;left:32mm;width:165px">'.$address["building"].'</div>
			<div class="field" style="top:59.4mm;left:66.2mm;width:40px">'.$address["room"].'</div>
			<div class="field" style="top:59.4mm;left:78.8mm;width:43px">'.$address["floor"].'</div>
			<div class="field" style="top:59.4mm;left:93mm;width:43px">'.$address["village"].'</div>

			<div class="field" style="top:65mm;left:21.4mm;width:60px">'.$address["number"].'</div>
			<div class="field" style="top:65mm;left:54.4mm;width:50px">'.$address["moo"].'</div>
			<div class="field" style="top:65mm;left:73mm;width:219px">'.$address["lane"].'</div>

			<div class="field" style="top:70.8mm;left:21mm;width:176px">'.$address["road"].'</div>
			<div class="field" style="top:70.8mm;left:76mm;width:150px">'.$address["subdistrict"].'</div>

			<div class="field" style="top:76.2mm;left:28mm;width:145px">'.$address["district"].'</div>
			<div class="field" style="top:76.2mm;left:76mm;width:150px">'.$address["province"].'</div>

			<div class="field14 tac" style="top:81.2mm;left:31.8mm">'.$post[0].'</div>
			<div class="field14 tac" style="top:81.2mm;left:35.6mm">'.$post[1].'</div>
			<div class="field14 tac" style="top:81.2mm;left:39.4mm">'.$post[2].'</div>
			<div class="field14 tac" style="top:81.2mm;left:43.2mm">'.$post[3].'</div>
			<div class="field14 tac" style="top:81.2mm;left:47.0mm">'.$post[4].'</div>

			<div class="field" style="top:81.8mm;left:55mm;width:190px">'.$data["comp_phone"].'</div>

			<div class="field" style="top:95mm;left:28.2mm">'.$rfill[1].'</div>
			<div class="field" style="top:95mm;left:62.2mm">'.$rfill[2].'</div>
			<div class="field" style="top:96.2mm;left:98mm;width:50px">'.$_REQUEST['fill'].'</div>
			
			<div class="field" style="top:109.6mm;left:44.2mm">'.$xfill[1].'</div>
			<div class="field" style="top:109.6mm;left:85mm">'.$xfill[2].'</div>
			<div class="field" style="top:109.6mm;left:126mm">'.$xfill[3].'</div>
			
			<div class="field" style="top:121.2mm;left:96.2mm"><img src="../../images/forms/check.png"></div>
			
			<div class="field tac" style="top:122.2mm;left:181mm;width:38px">'.$_REQUEST['emps'].'</div>
			<div class="field tac" style="top:128.4mm;left:181mm;width:38px">'.$_REQUEST['atts'].'</div>
			
			<div class="field" style="top:50.6mm;left:118.6mm">'.$month[1].'</div>
			<div class="field" style="top:50.8mm;left:138.6mm">'.$month[4].'</div>
			<div class="field" style="top:50.8mm;left:159.2mm">'.$month[7].'</div>
			<div class="field" style="top:50.8mm;left:178.4mm">'.$month[10].'</div>
			
			<div class="field" style="top:59mm;left:118.6mm">'.$month[2].'</div>
			<div class="field" style="top:59mm;left:138.6mm">'.$month[5].'</div>
			<div class="field" style="top:59mm;left:159.2mm">'.$month[8].'</div>
			<div class="field" style="top:59mm;left:178.4mm">'.$month[11].'</div>
			
			<div class="field" style="top:67.6mm;left:118.6mm">'.$month[3].'</div>
			<div class="field" style="top:67.6mm;left:138.6mm">'.$month[6].'</div>
			<div class="field" style="top:67.6mm;left:159.2mm">'.$month[9].'</div>
			<div class="field" style="top:67.6mm;left:178.4mm">'.$month[12].'</div>

			<div class="field tar w100" style="top:178mm;right:27.8mm">'.number_format($income,2).'</div>
			<div class="field tar w100" style="top:184.4mm;right:27.8mm">'.number_format($tax,2).'</div>
			<div class="field tar w100" style="top:197.2mm;right:27.8mm">'.number_format($tax,2).'</div>
			';
			
	$html .= '			
			<div class="field tac" style="top:231.4mm;left:80mm; width:190px">'.$name_position['name'].'</div>
			<div class="field" style="top:237.4mm;left:86mm; width:200px">'.$name_position['position'].'</div>';
			
			if($data['digi_signature'] == 1 && !empty($data['dig_signature'])){
				$html .= '<div class="signature" style="top:220.6mm;left:78.4mm"><img width="49mm" src="'.ROOT.$data['dig_signature'].'?'.time().'" /></div>';
			}
			if($data['digi_stamp'] == 1 && !empty($data['dig_stamp'])){
				$html .= '<div class="stamp" style="top:223mm;left:159mm"><img height="24mm" src="'.ROOT.$data['dig_stamp'].'?'.time().'" /></div>';
			}
	
	$html .= '		
			<div class="field" style="top:243.2mm;left:82.4mm;width:22px">'.$_SESSION['rego']['formdate']['d'].'</div>
			<div class="field" style="top:243.2mm;left:96mm;width:90px">'.$_SESSION['rego']['formdate']['thm'].'</div>
			<div class="field" style="top:243.2mm;left:127mm;width:65px">'.$_SESSION['rego']['formdate']['thy'].'</div>
			
			</body></html>';
			
	//var_dump(ROOT.$compinfo['dig_signature']);	
	//echo $style.$html; exit;			
	
	require_once("../../mpdf7/vendor/autoload.php");
	$mpdf=new mPDF('utf-8', 'A4-P', 10, '', 8, 8, 10, 10, 8, 8);
	$mpdf->SetTitle($data['th_compname'].' ('.strtoupper($_SESSION['rego']['cid']).') - PND3 Form - '.$months[(int)$_SESSION['rego']['gov_month']].' '.$_SESSION['rego']['cur_year']);
	$mpdf->SetDisplayMode('fullpage');
	$mpdf->SetFontSize(12);
	$mpdf->WriteHTML($style,1);
	$mpdf->WriteHTML($html);
	
	$dir = DIR.$_SESSION['rego']['cid'].'/archive/';
	$root = ROOT.$_SESSION['rego']['cid'].'/archive/';
	
	$baseName = $_SESSION['rego']['cid'].'_pnd3_form_monthly_th_'.$_SESSION['rego']['curr_month'].'_'.$_SESSION['rego']['year_th'];
	$extension = 'pdf';		
	$filename = getFilename($baseName, $extension, $dir);
	
	$doc = $lng['P.N.D.3 Monthly'].' '.$_SESSION['rego']['curr_month'].'-'.$_SESSION['rego']['year_th'];
	
	$mpdf->Output($filename,'I');
	
	if(isset($_REQUEST['a'])){
		$mpdf->Output($dir.$filename,'F');
		include('save_to_documents.php');
	}
	








