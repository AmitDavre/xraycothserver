<?php
	if(session_id()==''){session_start(); ob_start();}
	include('../../dbconnect/db_connect.php');
	include(DIR.'files/functions.php');
	include(DIR.'files/arrays_'.$_SESSION['rego']['lang'].'.php');
	include(DIR.'files/payroll_functions.php');

	$name_position = getFormNamePosition($cid);
	$address = unserialize($compinfo['th_addr_detail']);
	//var_dump($address);

	$p = str_replace('-','',$compinfo['tax_id']);
	//if(strlen($p)!== 13){$p = '?????????????';}
	if(strlen($p)!== 13){$p = '0000000000000';}
	$tin = str_split($p);
	
	if($address && $address['postal'] == ''){$address['postal'] = '?????';}
	if(strlen($address['postal']) != 5){$address['postal'] = '?????';}
	$post = str_split($address['postal']);

	for($i=1;$i<=12;$i++){
		$month[$i] = '';
	}
	$month[(int)$_REQUEST['month']] = '<i class="fa">&#xf00c;</i>';
	
	$rfill[1] = ''; 
	$rfill[2] = '';
	$rfill[(int)$_REQUEST['rfill']] = '<i class="fa">&#xf00c;</i>';
	
	$pag[1] = '';
	$pag[2] = '';
	$pag[(int)$_REQUEST['pag']] = '<i class="fa">&#xf00c;</i>';

	foreach($_REQUEST['person'] as $k=>$v){
		if((int)$v == 0){$_REQUEST['person'][$k] = '';}
	}
	foreach($_REQUEST['income'] as $k=>$v){
		if((int)$v == 0){$_REQUEST['income'][$k] = '';}
	}
	foreach($_REQUEST['tax'] as $k=>$v){
		if((int)$v == 0){$_REQUEST['tax'][$k] = '';}
	}
	if((int)$_REQUEST['surcharge'] == 0){$_REQUEST['surcharge'] = '';}
	//var_dump($_REQUEST);

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
			background:url(../../images/pnd1_monthly_th.png?'.time().') no-repeat;
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
	
$html = '<html><body>

			<div class="field14 tac" style="top:35.4mm;left:58mm">'.$tin[0].'</div>
			
			<div class="field14 tac" style="top:35.4mm;left:63.8mm">'.$tin[1].'</div>
			<div class="field14 tac" style="top:35.4mm;left:67.8mm">'.$tin[2].'</div>
			<div class="field14 tac" style="top:35.4mm;left:71.6mm">'.$tin[3].'</div>
			<div class="field14 tac" style="top:35.4mm;left:75.6mm">'.$tin[4].'</div>
			
			<div class="field14 tac" style="top:35.4mm;left:81.9mm">'.$tin[5].'</div>
			<div class="field14 tac" style="top:35.4mm;left:85.9mm">'.$tin[6].'</div>
			<div class="field14 tac" style="top:35.4mm;left:89.8mm">'.$tin[7].'</div>
			<div class="field14 tac" style="top:35.4mm;left:93.8mm">'.$tin[8].'</div>
			<div class="field14 tac" style="top:35.4mm;left:97.6mm">'.$tin[9].'</div>
			
			<div class="field14 tac" style="top:35.4mm;left:103.8mm">'.$tin[10].'</div>
			<div class="field14 tac" style="top:35.4mm;left:107.8mm">'.$tin[11].'</div>
			
			<div class="field14 tac" style="top:35.4mm;left:113.8mm">'.$tin[12].'</div>

			<div class="field" style="top:42.8mm;left:186mm;width:70px">'.$_SESSION['rego']['year_th'].'</div>

			<div class="field14 tac" style="top:46mm;left:98.6mm">'.$_REQUEST['branch'][0].'</div>
			<div class="field14 tac" style="top:46mm;left:102.4mm">'.$_REQUEST['branch'][1].'</div>
			<div class="field14 tac" style="top:46mm;left:106.2mm">'.$_REQUEST['branch'][2].'</div>
			<div class="field14 tac" style="top:46mm;left:110mm">'.$_REQUEST['branch'][3].'</div>
			<div class="field14 tac" style="top:46mm;left:114mm">'.$_REQUEST['branch'][4].'</div>

			<div class="field" style="top:52.2mm;left:14.4mm;width:390px">'.$compinfo["th_compname"].'</div>
			
			<div class="field" style="top:58mm;left:32mm;width:165px">'.$address["building"].'</div>
			<div class="field" style="top:58mm;left:67mm;width:40px">'.$address["room"].'</div>
			<div class="field" style="top:58mm;left:80mm;width:43px">'.$address["floor"].'</div>
			<div class="field" style="top:58mm;left:95mm;width:43px">'.$address["village"].'</div>

			<div class="field" style="top:63.6mm;left:21.4mm;width:60px">'.$address["number"].'</div>
			<div class="field" style="top:63.6mm;left:55.2mm;width:22px">'.$address["moo"].'</div>
			<div class="field" style="top:63.6mm;left:75mm;width:219px">'.$address["lane"].'</div>

			<div class="field" style="top:69mm;left:21mm;width:177px">'.$address["road"].'</div>
			<div class="field" style="top:69mm;left:77mm;width:152px">'.$address["subdistrict"].'</div>

			<div class="field" style="top:74.5mm;left:29.4mm;width:145px">'.$address["district"].'</div>
			<div class="field" style="top:74.5mm;left:77.6mm;width:150px">'.$address["province"].'</div>

			<div class="field14 tac" style="top:80mm;left:33mm">'.$post[0].'</div>
			<div class="field14 tac" style="top:80mm;left:36.8mm">'.$post[1].'</div>
			<div class="field14 tac" style="top:80mm;left:40.6mm">'.$post[2].'</div>
			<div class="field14 tac" style="top:80mm;left:44.4mm">'.$post[3].'</div>
			<div class="field14 tac" style="top:80mm;left:48.2mm">'.$post[4].'</div>

			<div class="field" style="top:80.4mm;left:57mm;width:190px">'.$compinfo["comp_phone"].'</div>

			<div class="field" style="top:95.6mm;left:30mm">'.$rfill[1].'</div>
			<div class="field" style="top:95.6mm;left:64mm">'.$rfill[2].'</div>
			<div class="field" style="top:95.2mm;left:99.2mm;width:50px">'.$_REQUEST['fill'].'</div>
			
			<div class="field" style="top:111.8mm;left:93.6mm">'.$pag[1].'</div>
			<div class="field" style="top:118.6mm;left:93.6mm">'.$pag[2].'</div>

			<div class="field" style="top:111.4mm;left:178.6mm;width:50px">'.$_REQUEST['pnd1_att_pages'].'</div>
			<div class="field" style="top:118.4mm;left:178.6mm;width:50px">'.$_REQUEST['pnd1_disk_pages'].'</div>
			<div class="field" style="top:123.2mm;left:158mm;width:130px">'.$_REQUEST['pnd1_controlnr'].'</div>
			
			<div class="field14 tac" style="top:42.4mm;left:158mm"><i style="font-size:11px" class="fa">&#xf00c;</i></div>
			<div class="field" style="top:51.8mm;left:119.8mm">'.$month[1].'</div>
			<div class="field" style="top:51.8mm;left:139.8mm">'.$month[4].'</div>
			<div class="field" style="top:51.8mm;left:160.4mm">'.$month[7].'</div>
			<div class="field" style="top:51.8mm;left:179.6mm">'.$month[10].'</div>
			
			<div class="field" style="top:60mm;left:119.8mm">'.$month[2].'</div>
			<div class="field" style="top:60mm;left:139.8mm">'.$month[5].'</div>
			<div class="field" style="top:60mm;left:160.4mm">'.$month[8].'</div>
			<div class="field" style="top:60mm;left:179.6mm">'.$month[11].'</div>
			
			<div class="field" style="top:68.4mm;left:119.8mm">'.$month[3].'</div>
			<div class="field" style="top:68.4mm;left:139.8mm">'.$month[6].'</div>
			<div class="field" style="top:68.4mm;left:160.4mm">'.$month[9].'</div>
			<div class="field" style="top:68.4mm;left:179.6mm">'.$month[12].'</div>
			
			<div class="field" style="top:159mm;left:43.6mm;width:80px">'.$_REQUEST['docnr'].'</div>
			<div class="field" style="top:159mm;left:78mm;width:100px">'.$_REQUEST['docdate'].'</div>
			
			<div class="field tar w60" style="top:148.4mm;right:81.8mm">'.$_REQUEST['person'][0].'</div>
			<div class="field tar w60" style="top:166.8mm;right:81.6mm">'.$_REQUEST['person'][1].'</div>
			<div class="field tar w60" style="top:178.8mm;right:81.6mm">'.$_REQUEST['person'][2].'</div>
			<div class="field tar w60" style="top:185.2mm;right:81.6mm">'.$_REQUEST['person'][3].'</div>
			<div class="field tar w60" style="top:191.6mm;right:81.6mm">'.$_REQUEST['person'][4].'</div>
			<div class="field tar w60" style="top:198mm;right:81.6mm">'.$_REQUEST['totperson'].'</div>

			<div class="field tar w100" style="top:148.4mm;right:50.8mm">'.$_REQUEST['income'][0].'</div>
			<div class="field tar w100" style="top:166.8mm;right:50.8mm">'.$_REQUEST['income'][1].'</div>
			<div class="field tar w100" style="top:178.8mm;right:50.8mm">'.$_REQUEST['income'][2].'</div>
			<div class="field tar w100" style="top:185.2mm;right:50.8mm">'.$_REQUEST['income'][3].'</div>
			<div class="field tar w100" style="top:191.6mm;right:50.8mm">'.$_REQUEST['income'][4].'</div>
			<div class="field tar w100" style="top:198mm;right:50.8mm">'.$_REQUEST['totincome'].'</div>

			<div class="field tar w100" style="top:148.4mm;right:20mm">'.$_REQUEST['tax'][0].'</div>
			<div class="field tar w100" style="top:166.8mm;right:20mm">'.$_REQUEST['tax'][1].'</div>
			<div class="field tar w100" style="top:178.8mm;right:20mm">'.$_REQUEST['tax'][2].'</div>
			<div class="field tar w100" style="top:185.2mm;right:20mm">'.$_REQUEST['tax'][3].'</div>
			<div class="field tar w100" style="top:191.6mm;right:20mm">'.$_REQUEST['tax'][4].'</div>
			<div class="field tar w100" style="top:198mm;right:20mm">'.$_REQUEST['tottax'].'</div>

			<div class="field tar w100" style="top:204.2mm;right:20mm">'.$_REQUEST['surcharge'].'</div>
			<div class="field tar w100" style="top:210.4mm;right:20mm">'.$_REQUEST['total'].'</div>

			<div class="field tac" style="top:240mm;left:81.4mm; width:190px">'.$name_position['name'].'</div>
			<div class="field" style="top:246.2mm;left:83.6mm; width:200px">'.$name_position['position'].'</div>';
			
			if($compinfo['digi_signature'] == 1 && !empty($compinfo['dig_signature'])){
				$html .= '<div class="signature" style="top:235mm;left:81mm"><img width="49mm" src="'.ROOT.$compinfo['dig_signature'].'?'.time().'" /></div>';
			}
			if($compinfo['digi_stamp'] == 1 && !empty($compinfo['dig_stamp'])){
				$html .= '<div class="stamp" style="top:234mm;left:155mm"><img height="24mm" src="'.ROOT.$compinfo['dig_stamp'].'?'.time().'" /></div>';
			}
	
	$html .= '		
			<div class="field" style="top:258mm;left:85mm;width:22px">'.$_SESSION['rego']['formdate']['d'].'</div>
			<div class="field" style="top:258mm;left:99mm;width:90px">'.$_SESSION['rego']['formdate']['thm'].'</div>
			<div class="field" style="top:258mm;left:127mm;width:60px">'.$_SESSION['rego']['formdate']['thy'].'</div>
			
			</body></html>';
			
	//var_dump(ROOT.$compinfo['dig_signature']);	
	echo $style.$html; exit;			
	
	require_once("../../mpdf7/vendor/autoload.php");
	$mpdf=new mPDF('utf-8', 'A4-P', 10, '', 8, 8, 10, 10, 8, 8);
	//$mpdf->SetTitle($pr_settigs['th_compname'].' ('.strtoupper($_SESSION['rego']['cid']).') - PND1 Form '.$months[(int)$data['month']].' '.$_SESSION['rego']['curr_year']);
	$mpdf->SetTitle($compinfo['th_compname'].' ('.strtoupper($_SESSION['rego']['cid']).') - PND1 Form - '.$months[(int)$_SESSION['rego']['gov_month']].' '.$_SESSION['rego']['cur_year']);
	$mpdf->SetDisplayMode('fullpage');
	$mpdf->SetFontSize(12);
	$mpdf->WriteHTML($style,1);
	$mpdf->WriteHTML($html);
	
	$dir = DIR.$_SESSION['rego']['cid'].'/archive/';
	$root = ROOT.$_SESSION['rego']['cid'].'/archive/';
	
	$baseName = $_SESSION['rego']['cid'].'_pnd1_form_monthly_'.$_SESSION['rego']['curr_month'].'_'.$_SESSION['rego']['year_'.$lang];
	$extension = 'pdf';		
	$filename = getFilename($baseName, $extension, $dir);
	
	$doc = $lng['P.N.D.1 Monthly'].' '.$_SESSION['rego']['curr_month'].'-'.$_SESSION['rego']['year_'.$lang];
	
	$mpdf->Output($filename,'I');
	
	if(isset($_REQUEST['a'])){
		$mpdf->Output($dir.$filename,'F');
		include('save_to_documents.php');
	}
	








