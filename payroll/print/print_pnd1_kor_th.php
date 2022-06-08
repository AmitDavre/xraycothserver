<?php
	
	if(session_id()==''){session_start(); ob_start();}
	include('../../dbconnect/db_connect.php');
	include(DIR.'files/functions.php');
	include(DIR.'files/payroll_functions.php');
	//var_dump($_REQUEST); exit;
	
	include('../gov_forms/inc_pnd1_kor_form.php');
	$name_position = getFormNamePosition($cid);
	
	$rfill[1] = ''; 
	$rfill[2] = '';
	$rfill[(int)$_REQUEST['rfill']] = '<img src="../../images/forms/check.png">';
	
$style = '
	<style>
		@page {
			margin: 10px 100px 10px 10px;
		}
		body, html {
			font-family: "leelawadee", "garuda";
			font-family: "leelawadee";
			font-size:12px;
			color:#036;
		}
		body {
			background:url(../../images/forms/pnd1_kor_th_pdf.png) no-repeat;
			background-image-resize:6;
		}
		div.field {
			position:absolute;
			min-height:12px;
			min-width:20px;
			border:0px solid red;
			font-size:12px;
			line-height:100%;
			white-space:nowrap;
			xbackground:orange;
			color:#036;
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
	
$html = '<html><body style="background:url(../../images/forms/pnd1_kor_th_pdf.png) no-repeat;">
			<div class="field" style="top:36.8mm;left:59.4mm">'.$pin[0].'</div>
			<div class="field" style="top:36.8mm;left:65mm">'.$pin[1].'</div>
			<div class="field" style="top:36.8mm;left:69mm">'.$pin[2].'</div>
			<div class="field" style="top:36.8mm;left:72.8mm">'.$pin[3].'</div>
			<div class="field" style="top:36.8mm;left:76.8mm">'.$pin[4].'</div>
			<div class="field" style="top:36.8mm;left:82.4mm">'.$pin[5].'</div>
			<div class="field" style="top:36.8mm;left:86.2mm">'.$pin[6].'</div>
			<div class="field" style="top:36.8mm;left:90.2mm">'.$pin[7].'</div>
			<div class="field" style="top:36.8mm;left:93.8mm">'.$pin[8].'</div>
			<div class="field" style="top:36.8mm;left:97.6mm">'.$pin[9].'</div>
			<div class="field" style="top:36.8mm;left:103.2mm">'.$pin[10].'</div>
			<div class="field" style="top:36.8mm;left:107mm">'.$pin[11].'</div>
			<div class="field" style="top:36.8mm;left:112.8mm">'.$pin[12].'</div>
	
			<div class="field" style="top:46.6mm;left:97.6mm">'.$branch[0].'</div>
			<div class="field" style="top:46.6mm;left:101.4mm">'.$branch[1].'</div>
			<div class="field" style="top:46.6mm;left:105.2mm">'.$branch[2].'</div>
			<div class="field" style="top:46.6mm;left:109mm">'.$branch[3].'</div>
			<div class="field" style="top:46.6mm;left:112.8mm">'.$branch[4].'</div>
	
			<div class="field" style="top:44.6mm;left:180mm;width:70px">'.$_SESSION['rego']['year_th'].'</div>
			
			<div class="field" style="top:52.8mm;left:19mm;width:370px">'.$edata[$lang."_compname"].'</div>
			
			<div class="field" style="top:59mm;left:36mm;width:80px">'.$address["building"].'</div>
			<div class="field" style="top:59mm;left:94mm;width:45px">'.$address["room"].'</div>
			<div class="field" style="top:59mm;left:110.6mm;width:23px">'.$address["floor"].'</div>

			<div class="field" style="top:64.8mm;left:26mm;width:50px">'.$address["number"].'</div>
			<div class="field" style="top:64.6mm;left:46mm;width:50px">'.$address["moo"].'</div>
			<div class="field" style="top:64.4mm;left:67mm;width:200px">'.$address["lane"].'</div>

			<div class="field" style="top:69.8mm;left:26mm;width:190px">'.$address["road"].'</div>
			<div class="field" style="top:69.8mm;left:94mm;width:87px">'.$address["subdistrict"].'</div>

			<div class="field" style="top:75mm;left:33mm;width:150px">'.$address["district"].'</div>
			<div class="field" style="top:75mm;left:81mm;width:135px">'.$address["province"].'</div>
			
			<div class="field" style="top:81.4mm;left:38mm">'.$post[0].'</div>
			<div class="field" style="top:81.4mm;left:41.8mm">'.$post[1].'</div>
			<div class="field" style="top:81.4mm;left:45.6mm">'.$post[2].'</div>
			<div class="field" style="top:81.4mm;left:49.4mm">'.$post[3].'</div>
			<div class="field" style="top:81.4mm;left:53.4mm">'.$post[4].'</div>
	
			<div class="field" style="top:65.4mm;right:81.8mm">'.$rfill[1].'</div>
			<div class="field" style="top:65.4mm;right:52.0mm">'.$rfill[2].'</div>
			<div class="field tac" style="top:66.6mm;right:16mm;width:30px">000'.$_REQUEST['fill'].'</div>
	
			<div class="field" style="top:114.2mm;left:91.6mm"><img src="../../images/forms/check.png"></div>

			<div class="field" style="top:115.3mm;left:179mm;width:25px">'.$pages.'</div>
			<div class="field" style="top:127.2mm;left:155mm;width:130px">'.$_REQUEST['control1'].'</div>
			<div class="field" style="top:131.8mm;left:167.8mm;width:65px">'.$_REQUEST['control2'].'</div>
	
			<div class="field" style="top:171.2mm;left:42mm;width:60px">'.$_REQUEST['docnr'].'</div>
			<div class="field" style="top:171.2mm;left:70mm;width:60px">'.$_REQUEST['docdate'].'</div>
	
			<div class="field tar w60" style="top:152.8mm;right:82mm">'.$emps.'</div>
			<div class="field tar w60" style="top:201mm;right:82mm">'.$emps.'</div>
	
			<div class="field tar w100" style="top:152.8mm;right:52.2mm">'.number_format($income,2).'</div>
			<div class="field tar w100" style="top:201mm;right:52.2mm">'.number_format($income,2).'</div>

			<div class="field tar w100" style="top:152.8mm;right:20.2mm">'.number_format($tax,2).'</div>
			<div class="field tar w100" style="top:201mm;right:20.2mm">'.number_format($tax,2).'</div>';
			
			if($edata['digi_signature'] && !empty($edata['dig_signature'])){
				$html .= '<div class="signature" style="top:228mm;left:79mm"><img width="190px" src="'.ROOT.'/'.$edata['dig_signature'].'?'.time().'" /></div>';
			}if($edata['digi_stamp'] && !empty($edata['dig_stamp'])){
				$html .= '<div class="stamp" style="top:223mm;right:30mm"><img width="90px" src="'.ROOT.'/'.$edata['dig_stamp'].'?'.time().'" /></div>';
			}
			
	$html .= '		
			<div class="field tac" style="top:226.8mm;left:79mm; width:205px">'.$name_position['name'].'</div>
			<div class="field" style="top:238.8mm;left:84mm; width:210px">'.$name_position['position'].'</div>
			
			<div class="field" style="top:245mm;left:84mm;width:30px">'.$_SESSION['rego']['formdate']['d'].'</div>
			<div class="field" style="top:245mm;left:97.6mm;width:90px">'.$_SESSION['rego']['formdate']['thm'].'</div>
			<div class="field" style="top:245mm;left:130.2mm;width:60px">'.$_SESSION['rego']['formdate']['thy'].'</div>
			
			</body></html>';
			
	//echo $style.$html; exit;			
	
	require_once(DIR."mpdf7/vendor/autoload.php");
	$mpdf=new mPDF('utf-8', 'A4-P', 9, '', 8, 8, 10, 10, 8, 8);
	$mpdf->SetTitle($edata[$lang.'_compname'].' ('.strtoupper($_SESSION['rego']['cid']).') - PND1 Kor Form - '.$months[(int)$_SESSION['rego']['gov_month']].' '.$_SESSION['rego']['cur_year']);
	$mpdf->SetDisplayMode('fullpage');
	$mpdf->SetFontSize(12);
	$mpdf->WriteHTML($style,1);
	$mpdf->WriteHTML($html);

	$dir = DIR.$_SESSION['rego']['cid'].'/archive/';
	$root = ROOT.$_SESSION['rego']['cid'].'/archive/';
	$baseName = $_SESSION['rego']['cid'].'_pnd1_kor_'.$_SESSION['rego']['year_'.$lang];
	$extension = 'pdf';		
	$filename = getFilename($baseName, $extension, $dir);
	$doc = $lng['P.N.D.1 Kor'].' '.$_SESSION['rego']['year_'.$lang];
	
	$mpdf->Output($filename,'I');
	
	if(isset($_REQUEST['a'])){
		$mpdf->Output($dir.$filename,'F');
		include('save_to_documents.php');
	}









