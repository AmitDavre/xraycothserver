<?php

	if(session_id()==''){session_start(); ob_start();}
	include('../../dbconnect/db_connect.php');
	include(DIR.'files/functions.php');
	//include(DIR.'files/arrays_'.$_SESSION['rego']['lang'].'.php');
	include(DIR.'files/payroll_functions.php');

	if($res = $dbc->query("SELECT en_addr_detail, en_compname, comp_phone, tax_id, revenu_branch, dig_signature, digi_signature, dig_stamp, digi_stamp FROM ".$cid."_entities_data WHERE ref = '".$_SESSION['rego']['gov_entity']."'")){
		$data = $res->fetch_assoc();
		$address = unserialize($data['en_addr_detail']);
	}else{
		//echo mysqli_error($dbc);
	}

	$form = getPND3attach($_SESSION['rego']['payroll_dbase'],$_SESSION['rego']['gov_month'],$_SESSION['rego']['gov_entity']);
	$emps = count($form['d']); 
	$income = $form['tot_income']; 
	$tax = $form['tot_tax'];
	
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
	
	$rfill[1] = '<img src="../../images/forms/check.png">'; 
	$rfill[2] = '';
	//$rfill[(int)$_REQUEST['rfill']] = '<img src="../../images/forms/check.png">';
	
	$pages = 1;
	if($emps > 7){
		$pages = ceil($emps/7);
	}
	
	$chars = '';
	$s = str_replace(',', '', $tax);
	if($lang == 'en'){
		$locale = 'en_US';
		$fmt = numfmt_create($locale, NumberFormatter::SPELLOUT);
		$chars = numfmt_format($fmt, $s);
	}else{
		$chars = getThaiCharNumber($s);
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
			background:url(../../images/forms/pnd3_en_pdf.png?'.time().') no-repeat;
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

$html = '<html><body style="background:url(../../images/forms/pnd3_en_pdf.png) no-repeat;">
			<div class="field12 tac" style="top:54mm;left:55.4mm">'.$pin[0].'</div>
			<div class="field12 tac" style="top:54mm;left:60.8mm">'.$pin[1].'</div>
			<div class="field12 tac" style="top:54mm;left:63.4mm">'.$pin[2].'</div>
			<div class="field12 tac" style="top:54mm;left:66.2mm">'.$pin[3].'</div>
			<div class="field12 tac" style="top:54mm;left:69.0mm">'.$pin[4].'</div>
			<div class="field12 tac" style="top:54mm;left:74.4mm">'.$pin[5].'</div>
			<div class="field12 tac" style="top:54mm;left:77.4mm">'.$pin[6].'</div>
			<div class="field12 tac" style="top:54mm;left:79.8mm">'.$pin[7].'</div>
			<div class="field12 tac" style="top:54mm;left:82.6mm">'.$pin[8].'</div>
			<div class="field12 tac" style="top:54mm;left:85.4mm">'.$pin[9].'</div>
			<div class="field12 tac" style="top:54mm;left:90.8mm">'.$pin[10].'</div>
			<div class="field12 tac" style="top:54mm;left:93.6mm">'.$pin[11].'</div>
			<div class="field12 tac" style="top:54mm;left:98.8mm">'.$pin[12].'</div>

			<div class="field12 tac" style="top:73.2mm;left:87.4mm">'.$branch[0].'</div>
			<div class="field12 tac" style="top:73.2mm;left:90.2mm">'.$branch[1].'</div>
			<div class="field12 tac" style="top:73.2mm;left:93.0mm">'.$branch[2].'</div>
			<div class="field12 tac" style="top:73.2mm;left:95.6mm">'.$branch[3].'</div>
			<div class="field12 tac" style="top:73.2mm;left:98.4mm">'.$branch[4].'</div>

			<div class="field" style="top:85mm;left:11mm;width:390px">'.$data[$lang."_compname"].'</div>
			
			<div class="field" style="top:92.4mm;left:63mm;width:165px">'.$address["building"].' '.$address["village"].'</div>
			<div class="field" style="top:100mm;left:23mm;width:40px">'.$address["room"].'</div>
			<div class="field" style="top:100mm;left:49mm;width:50px">'.$address["floor"].'</div>

			<div class="field" style="top:100mm;left:68mm;width:59px">'.$address["number"].'</div>
			<div class="field" style="top:100mm;left:90mm;width:50px">'.$address["moo"].'</div>
			
			<div class="field" style="top:107.8mm;left:24mm;width:219px">'.$address["lane"].'</div>
			<div class="field" style="top:107.8mm;left:67mm;width:176px">'.$address["road"].'</div>
			
			<div class="field" style="top:115.6mm;left:26mm;width:150px">'.$address["subdistrict"].'</div>
			<div class="field" style="top:115.6mm;left:70mm;width:145px">'.$address["district"].'</div>
			
			<div class="field" style="top:123.2mm;left:22mm;width:150px">'.$address["province"].'</div>

			<div class="field12 tac" style="top:123.8mm;left:76.4mm">'.$post[0].'</div>
			<div class="field12 tac" style="top:123.8mm;left:79.0mm">'.$post[1].'</div>
			<div class="field12 tac" style="top:123.8mm;left:81.8mm">'.$post[2].'</div>
			<div class="field12 tac" style="top:123.8mm;left:84.4mm">'.$post[3].'</div>
			<div class="field12 tac" style="top:123.8mm;left:87.2mm">'.$post[4].'</div>

			<div class="field" style="top:131.2mm;left:16mm;width:190px">'.$data["comp_phone"].'</div>

			<div class="field" style="top:55.8mm;left:107.6mm">'.$rfill[1].'</div>
			<div class="field" style="top:55.8mm;left:139.2mm">'.$rfill[2].'</div>
			<div class="field" style="top:56.8mm;left:184.6mm;width:50px">???</div>
			
			<div class="field" style="top:91.4mm;left:110mm">'.$month[1].'</div>
			<div class="field" style="top:91.4mm;left:132mm">'.$month[4].'</div>
			<div class="field" style="top:91.4mm;left:150.6mm">'.$month[7].'</div>
			<div class="field" style="top:91.4mm;left:175.6mm">'.$month[10].'</div>
			
			<div class="field" style="top:99.2mm;left:110mm">'.$month[2].'</div>
			<div class="field" style="top:99.2mm;left:132mm">'.$month[5].'</div>
			<div class="field" style="top:99.2mm;left:150.6mm">'.$month[8].'</div>
			<div class="field" style="top:99.2mm;left:175.6mm">'.$month[11].'</div>
			
			<div class="field" style="top:107mm;left:110mm">'.$month[3].'</div>
			<div class="field" style="top:107mm;left:132mm">'.$month[6].'</div>
			<div class="field" style="top:107mm;left:150.6mm">'.$month[9].'</div>
			<div class="field" style="top:107mm;left:175.6mm">'.$month[12].'</div>
			
			<div class="field" style="top:174mm;left:72mm"><img src="../../images/forms/check.png"></div>
			<div class="field tac" style="top:182.6mm;left:168mm;width:32px">'.$emps.'</div>
			
			<div class="field tar w100" style="top:204.6mm;left:97mm">'.$income.'</div>
			<div class="field tar w100" style="top:212.4mm;left:97mm">'.$tax.'</div>
			<div class="field tar w100" style="top:228.2mm;left:97mm">'.$tax.'</div>
			
			<div class="field" style="top:239.8mm;left:76mm">'.$chars.' Baht</div>

			<div class="field tac" style="top:225.4mm;left:80mm; width:190px">'.$name_position['name'].'</div>
			<div class="field" style="top:237.4mm;left:86mm; width:200px">'.$name_position['position'].'</div>
			
			<div class="field" style="top:262mm;left:80mm;width:22px">'.$_SESSION['rego']['formdate']['d'].'</div>
			<div class="field" style="top:262mm;left:98mm;width:90px">'.$_SESSION['rego']['formdate']['m'].'</div>
			<div class="field" style="top:262mm;left:135mm;width:65px">'.$_SESSION['rego']['formdate']['eny'].'</div>
			
			</body></html>';
	//$html = '';
			
	//var_dump(ROOT.$compinfo['dig_signature']);	
	//echo $style.$html; exit;			
	
	require_once("../../mpdf7/vendor/autoload.php");
	$mpdf=new mPDF('utf-8', 'A4-P', 10, '', 8, 8, 10, 10, 8, 8);
	$mpdf->SetTitle($data[$lang.'_compname'].' ('.strtoupper($_SESSION['rego']['cid']).') - PND3 Form - '.$months[(int)$_SESSION['rego']['gov_month']].' '.$_SESSION['rego']['cur_year']);
	$mpdf->SetDisplayMode('fullpage');
	$mpdf->SetFontSize(12);
	$mpdf->WriteHTML($style,1);
	$mpdf->WriteHTML($html);
	
	$dir = DIR.$_SESSION['rego']['cid'].'/archive/';
	$root = ROOT.$_SESSION['rego']['cid'].'/archive/';
	
	$baseName = $_SESSION['rego']['cid'].'_pnd3_form_monthly_en_'.$_SESSION['rego']['curr_month'].'_'.$_SESSION['rego']['year_th'];
	$extension = 'pdf';		
	$filename = getFilename($baseName, $extension, $dir);
	
	$doc = $lng['P.N.D.3 Monthly'].' '.$_SESSION['rego']['curr_month'].'-'.$_SESSION['rego']['year_th'];
	
	$mpdf->Output($filename,'I');
	
	if(isset($_REQUEST['a'])){
		$mpdf->Output($dir.$filename,'F');
		include('save_to_documents.php');
	}
	








