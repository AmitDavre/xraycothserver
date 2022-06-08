<?php
	
	if(session_id()==''){session_start(); ob_start();}
	include('../../dbconnect/db_connect.php');
	include(DIR.'files/functions.php');
	include(DIR.'files/arrays_en.php');
	include(DIR.'files/payroll_functions.php');

	if($res = $dbc->query("SELECT en_addr_detail, en_compname, comp_phone, tax_id, revenu_branch, dig_signature, digi_signature, dig_stamp, digi_stamp FROM ".$cid."_entities_data WHERE ref = '".$_SESSION['rego']['gov_entity']."'")){
		$data = $res->fetch_assoc();
		$address = unserialize($data['en_addr_detail']);
	}else{
		//echo mysqli_error($dbc);
	}
	//var_dump($data); exit;
	
	$form = getPND1attach($_SESSION['rego']['payroll_dbase'],$_SESSION['rego']['gov_month'],$_SESSION['rego']['gov_entity']);
	$emps = count($form['d']); 
	$empsd2 = count($form['d2']); 
	$empsTot = $emps + $empsd2;

	$income1 = $form['tot_income'];
	$tax1 = $form['tot_tax'];

	$income2 = $form['tot_income2'];
	$tax2 = $form['tot_tax2'];

	$income = str_replace(',', '', $form['tot_income']) + str_replace(',', '', $form['tot_income2']); 
	$tax = str_replace(',', '', $form['tot_tax']) + str_replace(',', '', $form['tot_tax2']); 
	$income = number_format($income,2);
	$tax = number_format($tax,2);
	
	$name_position = getFormNamePosition($cid);

	for($i=1;$i<=12;$i++){
		$month[$i] = '';
	}
	$month[$cur_month] = '<img src="../../images/forms/check.png">';

	$p = str_replace('-','',$data['tax_id']);
	if(strlen($p)!== 13){$p = '?????????????';}
	$tin = str_split($p);
	
	$branch = sprintf("%05d",$data['revenu_branch']);
	$branch = str_split($branch);

	//$address = unserialize($compinfo['th_addr_detail']);
	if($address && $address['postal'] == ''){$address['postal'] = '?????';}
	if(strlen($address['postal']) != 5){$address['postal'] = '?????';}
	$post = str_split($address['postal']);
	
	$rfill[1] = ''; 
	$rfill[2] = '';
	$rfill[(int)$_REQUEST['rfill']] = '<img src="../../images/forms/check.png">';
	
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
			background:url(../../images/forms/pnd1_en_pdf.png?'.time().') no-repeat;
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
			font-size:13px;
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
	
$html = '<html><body style="background:url(../../images/forms/pnd1_en_pdf.png) no-repeat;">

			<div class="field14 tac" style="top:35.8mm;left:58mm">'.$tin[0].'</div>
			<div class="field14 tac" style="top:35.8mm;left:64mm">'.$tin[1].'</div>
			<div class="field14 tac" style="top:35.8mm;left:68mm">'.$tin[2].'</div>
			<div class="field14 tac" style="top:35.8mm;left:72mm">'.$tin[3].'</div>
			<div class="field14 tac" style="top:35.8mm;left:76mm">'.$tin[4].'</div>
			<div class="field14 tac" style="top:35.8mm;left:81.8mm">'.$tin[5].'</div>
			<div class="field14 tac" style="top:35.8mm;left:85.8mm">'.$tin[6].'</div>
			<div class="field14 tac" style="top:35.8mm;left:89.6mm">'.$tin[7].'</div>
			<div class="field14 tac" style="top:35.8mm;left:93.6mm">'.$tin[8].'</div>
			<div class="field14 tac" style="top:35.8mm;left:97.8mm">'.$tin[9].'</div>
			<div class="field14 tac" style="top:35.8mm;left:104mm">'.$tin[10].'</div>
			<div class="field14 tac" style="top:35.8mm;left:107.8mm">'.$tin[11].'</div>
			<div class="field14 tac" style="top:35.8mm;left:114mm">'.$tin[12].'</div>

			<div class="field" style="top:42.8mm;left:188mm;width:70px">'.$_SESSION['rego']['year_th'].'</div>

			<div class="field14 tac" style="top:46.4mm;left:98.6mm">'.$branch[0].'</div>
			<div class="field14 tac" style="top:46.4mm;left:102.6mm">'.$branch[1].'</div>
			<div class="field14 tac" style="top:46.4mm;left:106.4mm">'.$branch[2].'</div>
			<div class="field14 tac" style="top:46.4mm;left:110.4mm">'.$branch[3].'</div>
			<div class="field14 tac" style="top:46.4mm;left:114.4mm">'.$branch[4].'</div>

			<div class="field" style="top:52.2mm;left:14.4mm;width:390px">'.$data["en_compname"].'</div>
			
			<div class="field" style="top:58.2mm;left:39mm;width:165px">'.$address["building"].'</div>
			<div class="field" style="top:58.2mm;left:89mm;width:40px">'.$address["room"].'</div>
			<div class="field" style="top:58.2mm;left:108mm;width:43px">'.$address["floor"].'</div>

			<div class="field" style="top:63.6mm;left:25.4mm;width:60px">'.$address["number"].'</div>
			<div class="field" style="top:63.6mm;left:46mm;width:50px">'.$address["moo"].'</div>
			<div class="field" style="top:63.6mm;left:60.6mm;width:219px">'.$address["lane"].'</div>

			<div class="field" style="top:68.8mm;left:22mm;width:177px">'.$address["road"].'</div>
			<div class="field" style="top:68.8mm;left:85mm;width:152px">'.$address["subdistrict"].'</div>

			<div class="field" style="top:74.0mm;left:24mm;width:145px">'.$address["district"].'</div>
			<div class="field" style="top:74.0mm;left:80mm;width:150px">'.$address["province"].'</div>

			<div class="field14 tac" style="top:80.2mm;left:30.2mm">'.$post[0].'</div>
			<div class="field14 tac" style="top:80.2mm;left:34mm">'.$post[1].'</div>
			<div class="field14 tac" style="top:80.2mm;left:37.8mm">'.$post[2].'</div>
			<div class="field14 tac" style="top:80.2mm;left:41.6mm">'.$post[3].'</div>
			<div class="field14 tac" style="top:80.2mm;left:45.4mm">'.$post[4].'</div>

			<div class="field" style="top:80.4mm;left:62mm;width:190px">'.$data["comp_phone"].'</div>

			<div class="field" style="top:94mm;left:30mm">'.$rfill[1].'</div>
			<div class="field" style="top:94mm;left:64mm">'.$rfill[2].'</div>
			<div class="field" style="top:95.2mm;left:99.2mm;width:50px">'.$_REQUEST['fill'].'</div>
			
			<div class="field" style="top:111.4mm;left:178.6mm;width:50px">'.$pages.'</div>
			<div class="field" style="top:125.4mm;left:158mm;width:130px">'.$_REQUEST['pnd1_controlnr'].'</div>
			<div class="field" style="top:110mm;left:93.6mm"><img src="../../images/forms/check.png"></div>

			<div class="field" style="top:50.2mm;left:120.2mm">'.$month[1].'</div>
			<div class="field" style="top:50.2mm;left:140.6mm">'.$month[4].'</div>
			<div class="field" style="top:50.2mm;left:156.4mm">'.$month[7].'</div>
			<div class="field" style="top:50.2mm;left:179.6mm">'.$month[10].'</div>
			
			<div class="field" style="top:58.2mm;left:120.2mm">'.$month[2].'</div>
			<div class="field" style="top:58.2mm;left:140.6mm">'.$month[5].'</div>
			<div class="field" style="top:58.2mm;left:156.4mm">'.$month[8].'</div>
			<div class="field" style="top:58.2mm;left:179.6mm">'.$month[11].'</div>
			
			<div class="field" style="top:66.8mm;left:120.2mm">'.$month[3].'</div>
			<div class="field" style="top:66.8mm;left:140.6mm">'.$month[6].'</div>
			<div class="field" style="top:66.8mm;left:156.4mm">'.$month[9].'</div>
			<div class="field" style="top:66.8mm;left:179.6mm">'.$month[12].'</div>
			
			<div class="field" style="top:167mm;left:69mm;width:80px">'.$_REQUEST['docnr'].'</div>
			<div class="field" style="top:167mm;left:94mm;width:100px">'.$_REQUEST['docdate'].'</div>
			
			<div class="field tar w60" style="top:148.4mm;right:81.8mm">'.$emps.'</div>
			<div class="field tar w60" style="top:185.5mm;right:81.8mm">'.$empsd2.'</div>
			<div class="field tar w60" style="top:198mm;right:81.6mm">'.$empsTot.'</div>

			<div class="field tar w100" style="top:148.4mm;right:50.8mm">'.$income1.'</div>
			<div class="field tar w100" style="top:185.5mm;right:50.8mm">'.$income2.'</div>
			<div class="field tar w100" style="top:198mm;right:50.8mm">'.$income.'</div>

			<div class="field tar w100" style="top:148.4mm;right:20mm">'.$tax1.'</div>
			<div class="field tar w100" style="top:185.5mm;right:20mm">'.$tax2.'</div>
			<div class="field tar w100" style="top:198mm;right:20mm">'.$tax.'</div>

			<div class="field tar w100" style="top:210.4mm;right:20mm">'.$tax.'</div>

			<div class="field tac" style="top:246.2mm;left:81mm; width:190px">'.$name_position['name'].'</div>
			<div class="field" style="top:252.2mm;left:87mm; width:200px">'.$name_position['position'].'</div>';
			
			if($data['digi_signature'] == 1 && !empty($data['dig_signature'])){
				$html .= '<div class="signature" style="top:235mm;left:81mm"><img width="49mm" src="'.ROOT.$data['dig_signature'].'?'.time().'" /></div>';
			}
			if($data['digi_stamp'] == 1 && !empty($data['dig_stamp'])){
				$html .= '<div class="stamp" style="top:234mm;left:155mm"><img height="24mm" src="'.ROOT.$data['dig_stamp'].'?'.time().'" /></div>';
			}
	
	$html .= '		
			<div class="field" style="top:258.2mm;left:85mm;width:22px">'.$_SESSION['rego']['formdate']['d'].'</div>
			<div class="field" style="top:258.2mm;left:100mm;width:90px">'.$_SESSION['rego']['formdate']['m'].'</div>
			<div class="field" style="top:258.2mm;left:127mm;width:60px">'.$_SESSION['rego']['formdate']['eny'].'</div>
			
			</body></html>';
			
	//var_dump(ROOT.$compinfo['dig_signature']);	
	//echo $style.$html; exit;			
	
	require_once("../../mpdf7/vendor/autoload.php");
	$mpdf=new mPDF('utf-8', 'A4-P', 10, '', 8, 8, 10, 10, 8, 8);
	$mpdf->SetTitle($data['en_compname'].' ('.strtoupper($_SESSION['rego']['cid']).') - PND1 Form - '.$months[(int)$_SESSION['rego']['gov_month']].' '.$_SESSION['rego']['cur_year']);
	$mpdf->SetDisplayMode('fullpage');
	$mpdf->SetFontSize(12);
	$mpdf->WriteHTML($style,1);
	$mpdf->WriteHTML($html);
	
	$dir = DIR.$_SESSION['rego']['cid'].'/archive/';
	$root = ROOT.$_SESSION['rego']['cid'].'/archive/';
	
	$baseName = $_SESSION['rego']['cid'].'_pnd1_form_en_'.$_SESSION['rego']['curr_month'].'_'.$_SESSION['rego']['year_en'];
	$extension = 'pdf';		
	$filename = getFilename($baseName, $extension, $dir);
	
	$doc = 'P.N.D.1 Monthly English '.$_SESSION['rego']['curr_month'].'-'.$_SESSION['rego']['year_en'];
	
	$mpdf->Output($filename,'I');
	
	if(isset($_REQUEST['a'])){
		$mpdf->Output($dir.$filename,'F');
		include('save_to_documents.php');
	}
	








