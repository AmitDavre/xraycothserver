<?php
	
	if(session_id()==''){session_start();}
	ob_start();

	include('../../dbconnect/db_connect.php');
	include(DIR.'files/functions.php');
	include(DIR.'files/arrays_th.php');
	include(DIR.'files/payroll_functions.php');
	//var_dump($_REQUEST); exit;
	
	if($res = $dbc->query("SELECT ".$lang."_addr_detail, th_compname, comp_phone, comp_fax, tax_id, revenu_branch, dig_signature, digi_signature, dig_stamp, digi_stamp, sso_account, sso_codes FROM ".$cid."_entities_data WHERE ref = '".$_SESSION['rego']['gov_entity']."'")){
		$edata = $res->fetch_assoc();
		$address = unserialize($edata[$lang.'_addr_detail']);
		$sso_codes = unserialize($edata['sso_codes']);
	}else{
		//echo mysqli_error($dbc);
	}
	$name_position = getFormNamePosition($cid);
	
	include('../gov_forms/gov_sso_form.php');

	$rr[1] = ''; 
	$rr[2] = '';
	$rr[3] = ''; 
	$rr[4] = '';
	$rr[(int)$_REQUEST['rr']] = '<img src="../../images/forms/check.png">';

	
$style = '<link rel="stylesheet" type="text/css" media="screen" href="../fonts/font-awesome/css/font-awesome.min.css">
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
			background:url('.ROOT.'images/forms/sso_th_pdf.png) no-repeat;
			background-image-resize:1;
		}
		div.field {
			position:absolute;
			min-height:12px;
			min-width:20px;
			xbackground:orange;
			font-size:14px;
			line-height:100%;
			white-space:nowrap;
		}
		div.signature {
			width:200px;
			position:absolute;
			text-align:center;
		}
		div.stamp {
			position:absolute;
			text-align:center;
		}
		i.fa {
			font-family: fontawesome;
			font-style:normal;
			color:#036;
		}
		.tac {text-align:center;}
		.tar {text-align:right;}
		.w25 {width:25px;}
		.w100 {width:100px;}
		
	</style>';
	
$html = '<html><body style="background:url(../../images/forms/sso_th_pdf.png) no-repeat;">

			<div class="field" style="top:45.6mm;left:201mm">'.$sso[0].'</div>
			<div class="field" style="top:45.6mm;left:206mm">'.$sso[1].'</div>
			<div class="field" style="top:45.6mm;left:215.8mm">'.$sso[2].'</div>
			<div class="field" style="top:45.6mm;left:220.8mm">'.$sso[3].'</div>
			<div class="field" style="top:45.6mm;left:225.8mm">'.$sso[4].'</div>
			<div class="field" style="top:45.6mm;left:230.8mm">'.$sso[5].'</div>
			<div class="field" style="top:45.6mm;left:235.8mm">'.$sso[6].'</div>
			<div class="field" style="top:45.6mm;left:240.8mm">'.$sso[7].'</div>
			<div class="field" style="top:45.6mm;left:245.8mm">'.$sso[8].'</div>
			<div class="field" style="top:45.6mm;left:255.2mm">'.$sso[9].'</div>
			
			<div class="field" style="top:53.2mm;left:201.2mm">'.$branch[0].'</div>
			<div class="field" style="top:53.2mm;left:206.2mm">'.$branch[1].'</div>
			<div class="field" style="top:53.2mm;left:211.2mm">'.$branch[2].'</div>
			<div class="field" style="top:53.2mm;left:216.2mm">'.$branch[3].'</div>
			<div class="field" style="top:53.2mm;left:221.2mm">'.$branch[4].'</div>
			<div class="field" style="top:53.2mm;left:226.2mm">'.$branch[5].'</div>

			<div class="field" style="top:38.4mm;left:61mm;">'.$edata["th_compname"].'</div>
			
			<div class="field" style="top:54mm;left:66mm;">'.$sso_codes[$_SESSION['rego']['gov_branch']]['line1_th'].'</div>
			<div class="field" style="top:61.6mm;left:26mm;">'.$sso_codes[$_SESSION['rego']['gov_branch']]['line2_th'].'</div>
			
			<div class="field" style="top:69.2mm;left:49mm;">'.$address['postal'].'</div>
			<div class="field" style="top:69.2mm;left:77mm;">'.$compinfo["comp_phone"].'</div>
			<div class="field" style="top:69.2mm;left:116mm;">'.$compinfo["comp_fax"].'</div>
			
			<div class="field" style="top:61.2mm;left:201mm;width:50px">'.$sso_rate.'</div>
			
			<div class="field" style="top:76.8mm;right:163mm; text-align:right">'.$months[(int)$_SESSION['rego']['gov_month']].' พ.ศ. '.$_SESSION['rego']['year_th'].'</div>

			<div class="field tar w100" style="top:97mm;right:172mm">'.$income[0].'</div>
			<div class="field tar w100" style="top:104mm;right:172mm">'.$social.'</div>
			<div class="field tar w100" style="top:111.2mm;right:172mm">'.$social_com.'</div>
			<div class="field tar w100" style="top:118.2mm;right:172mm">'.$total.'</div>
			
			<div class="field tar w25" style="top:97mm;right:163mm;">'.$income[1].'</div>
			<div class="field tar w25" style="top:104mm;right:163mm">00</div>
			<div class="field tar w25" style="top:111.2mm;right:163mm">00</div>
			<div class="field tar w25" style="top:118.2mm;right:163mm">00</div>
			
			<div class="field" style="top:125.5mm;left:33mm;">'.$chars.'</div>
			<div class="field tar w25" style="top:132.2mm;right:172mm">'.$emps.'</div>
		
			<div class="field" style="top:152.6mm;left:24.8mm">'.$rr[1].'</div>
			<div class="field" style="top:159.2mm;left:24.8mm">'.$rr[2].'</div>
			<div class="field" style="top:166.0mm;left:24.8mm">'.$rr[3].'</div>
			<div class="field" style="top:172.6mm;left:24.8mm">'.$rr[4].'</div>
			
			<div class="field" style="top:153.6mm;left:87mm; width:70px">'.$pages.'</div>
			<div class="field" style="top:174mm;left:38mm;">'.$_REQUEST["other"].'</div>';
			
			if($edata['digi_signature'] == 1 && !empty($edata['dig_signature'])){
				$html .= '<div class="signature" style="top:170.4mm;left:82mm"><img width="120mm" src="'.ROOT.$edata['dig_signature'].'?'.time().'" /></div>';
			}if($edata['digi_stamp'] == 1 && !empty($edata['dig_stamp'])){
				$html .= '<div class="stamp" style="top:180mm;left:33mm"><img style="border:0px solid red; width:24mm" src="'.ROOT.$edata['dig_stamp'].'?'.time().'" /></div>';
			}
	
	$html .= '
			<div class="field tac" style="top:182.6mm;left:81mm;width:220px">'.$name_position['name'].'</div>
			<div class="field" style="top:189mm;left:85mm;width:220px">'.$name_position['position'].'</div>
			
			<div class="field" style="top:195.8mm;left:92mm;width:20px">'.$_SESSION['rego']['formdate']['d'].'</div>
			<div class="field" style="top:195.8mm;left:107.4mm;width:90px">'.$_SESSION['rego']['formdate']['thm'].'</div>
			<div class="field" style="top:195.8mm;left:140mm;width:50px">'.$_SESSION['rego']['formdate']['thy'].'</div>

			</body></html>';
			
	//echo $style.$html; exit;			

	require_once(DIR."mpdf7/vendor/autoload.php");
	$mpdf=new mPDF('utf-8', 'A4-L', 10, '', 8, 8, 10, 10, 8, 8);
	$mpdf->SetTitle($compinfo['th_compname'].' ('.strtoupper($cid).') - SSO Form​ - '.$months[$_SESSION['rego']['gov_month']].' '.$_SESSION['rego']['cur_year']);
	$mpdf->SetDisplayMode('fullpage');
	$mpdf->SetFontSize(12);
	$mpdf->WriteHTML($style,1);
	$mpdf->WriteHTML($html);
	//$mpdf->Output($_SESSION['rego']['cid'].'_SSO form_'.$_SESSION['rego']['cur_year'].'_'.$_SESSION['rego']['gov_month'].'.pdf',$action);

	$dir = DIR.$cid.'/archive/';
	$root = ROOT.$cid.'/archive/';
	$baseName = $_SESSION['rego']['cid'].'_sso_form_'.$_SESSION['rego']['curr_month'].'_'.$_SESSION['rego']['year_th'];
	$extension = 'pdf';		
	$filename = getFilename($baseName, $extension, $dir);
	$doc = $lng['SSO Form'].' '.$_SESSION['rego']['curr_month'].'-'.$_SESSION['rego']['year_th'];

	$mpdf->Output($filename,'I');
	
	if(isset($_REQUEST['a'])){
		$mpdf->Output($dir.$filename,'F');
		include('save_to_documents.php');
	}

	exit;





