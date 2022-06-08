<?php
	
	if(session_id()==''){session_start();}; ob_start();
	include('../../dbconnect/db_connect.php');
	include(DIR.'files/functions.php');
	include(DIR.'files/arrays_th.php');
	include(DIR.'files/payroll_functions.php');

	$edata = getEntityData($_SESSION['rego']['gov_entity']);
	$address = unserialize($edata[$_SESSION['rego']['lang'].'_addr_detail']);
	$sso_codes = unserialize($edata['sso_codes']);
	
	$branch = sprintf("%05d",$edata['revenu_branch']);
	$branch = str_split($branch);
	
	$tin = str_replace('-','',$edata['tax_id']);
	if(strlen($tin)!== 13){$tin = '0000000000000';}
	$tin = str_split($tin);

	$name_position = getFormNamePosition($cid);
	
	$pages = 0;
	$data = getPND1attachYear($_SESSION['rego']['gov_entity']);
	$body = $data['rows'];
	$rows = count($body);
	$pages = ceil($rows/7);
	
	$tot = $rows/7;
	$floor = floor($rows/7);
	$per = $tot - $floor;
	$floor = floor($tot);
	$rest = 0;
	if($per < 1){$rest = 7 * $per;}
	
	$s = 1; $e = 7;
	$nr = 1;
	for($i=1;$i<=$floor;$i++){
		$pag[$nr] = array('s'=>$s, 'e'=>$e);
		$s+=7; $e+=7; $nr++;
	}
	if($rest > 0){
		$pag[$nr] = array('s'=>$s, 'e'=>$s+$rest-1);
	}

	//var_dump($data); exit;
	

$style = '
<style>
		@page {
			margin: 10px 100px 10px 10px;
		}
		body, html {
			font-family: "leelawadee", "garuda";
			font-family: "garuda";
			font-size:14px;
			color:#036;
			position:relative;
		}
		body {
			background:url(../../images/forms/pnd1_kor_th_attach.png) no-repeat;
			background-image-resize:1;
		}
		.main {
			position:relative;
			page-break-after:always;
			background:red;
			display:block;
			border:1px solid green;
		}
		div.field {
			position:absolute;
			min-height:12px;
			min-width:20px;
			font-size:14px;
			line-height:100%;
			white-space:nowrap;
			font-weight:normal;
		}
		div.address {
			position:absolute;
			xheight:12px;
			xwidth:350px;
			font-size:12px;
			line-height:100%;
			white-space:nowrap;
			font-weight:normal;
		}
		div.nfield {
			position:absolute;
			min-height:12px;
			width:125px;
			font-size:14px;
			line-height:100%;
			white-space:nowrap;
			font-weight:normal;
			text-align:right;
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

$html = '<html><body style="background:url(../../images/forms/pnd1_kor_th_attach.png) no-repeat;">';

for($page=1;$page <= $pages;$page++){
	
	$top = 8;
	$html .= '
			<div class="field" style="top:'.$top.'mm;left:146.4mm">'.$tin[0].'</div>
			<div class="field" style="top:'.$top.'mm;left:152.6mm">'.$tin[1].'</div>
			<div class="field" style="top:'.$top.'mm;left:156.8mm">'.$tin[2].'</div>
			<div class="field" style="top:'.$top.'mm;left:161.2mm">'.$tin[3].'</div>
			<div class="field" style="top:'.$top.'mm;left:165.4mm">'.$tin[4].'</div>
			<div class="field" style="top:'.$top.'mm;left:171.6mm">'.$tin[5].'</div>
			<div class="field" style="top:'.$top.'mm;left:175.6mm">'.$tin[6].'</div>
			<div class="field" style="top:'.$top.'mm;left:179.8mm">'.$tin[7].'</div>
			<div class="field" style="top:'.$top.'mm;left:184.0mm">'.$tin[8].'</div>
			<div class="field" style="top:'.$top.'mm;left:188.2mm">'.$tin[9].'</div>
			<div class="field" style="top:'.$top.'mm;left:194.6mm">'.$tin[10].'</div>
			<div class="field" style="top:'.$top.'mm;left:198.8mm">'.$tin[11].'</div>
			<div class="field" style="top:'.$top.'mm;left:205.0mm">'.$tin[12].'</div>
			
			<div class="field" style="top:17.2mm;left:260.8mm">'.$branch[0].'</div>
			<div class="field" style="top:17.2mm;left:264.4mm">'.$branch[1].'</div>
			<div class="field" style="top:17.2mm;left:268.0mm">'.$branch[2].'</div>
			<div class="field" style="top:17.2mm;left:272.0mm">'.$branch[3].'</div>
			<div class="field" style="top:17.2mm;left:276.0mm">'.$branch[4].'</div>
			
			<div class="field" style="top:22.4mm;left:37.4mm"><img src="../../images/forms/check.png"></div>
			
			<div class="field" style="top:38.2mm;left:239.0mm; width:30px; text-align:center">'.$page.'</div>
			<div class="field" style="top:38.2mm;left:259.2mm; width:45px; text-align:center">'.$pages.'</div>';
	
	$tax = 59.0;
	$bot = 66.0;
	$tot_income = 0;
	$tot_tax = 0;
	if($body){
		for($i=$pag[$page]['s'];$i<=$pag[$page]['e'];$i++){
			$tmp = str_replace('-','',$body[$i]['tax_id']);
			if(strlen($tmp)!== 13){$tmp = '0000000000000';}
			$etax = str_split($tmp);
			$tot_income += str_replace(',','',$body[$i]['grossincome']);
			$tot_tax += str_replace(',','',$body[$i]['tax']);
			$html .= '
					<div class="field" style="top:'.($bot-3).'mm;left:18.0mm; width:43px; text-align:center">'.$i.'</div>
					
					<div class="field" style="top:'.$tax.'mm;left:34.0mm">'.$etax[0].'</div>
					<div class="field" style="top:'.$tax.'mm;left:40.2mm">'.$etax[1].'</div>
					<div class="field" style="top:'.$tax.'mm;left:44.4mm">'.$etax[2].'</div>
					<div class="field" style="top:'.$tax.'mm;left:48.6mm">'.$etax[3].'</div>
					<div class="field" style="top:'.$tax.'mm;left:52.8mm">'.$etax[4].'</div>
					<div class="field" style="top:'.$tax.'mm;left:59.2mm">'.$etax[5].'</div>
					<div class="field" style="top:'.$tax.'mm;left:63.4mm">'.$etax[6].'</div>
					<div class="field" style="top:'.$tax.'mm;left:67.6mm">'.$etax[7].'</div>
					<div class="field" style="top:'.$tax.'mm;left:71.8mm">'.$etax[8].'</div>
					<div class="field" style="top:'.$tax.'mm;left:76.0mm">'.$etax[9].'</div>
					<div class="field" style="top:'.$tax.'mm;left:82.2mm">'.$etax[10].'</div>
					<div class="field" style="top:'.$tax.'mm;left:86.4mm">'.$etax[11].'</div>
					<div class="field" style="top:'.$tax.'mm;left:92.6mm">'.$etax[12].'</div>
					
					<div class="field" style="top:'.($tax+0.6).'mm;left:103.0mm;width:200px">'.$body[$i]['firstname'].'</div>
					<div class="field" style="top:'.($tax+0.6).'mm;left:155.0mm;width:200px">'.$body[$i]['lastname'].'</div>
					
					<div class="address" style="top:'.$bot.'mm;left:105.6mm">'.$body[$i]['address'].'</div>
					
					<div class="nfield" style="top:'.$bot.'mm;left:202.2mm">'.$body[$i]['grossincome'].'</div>
					<div class="nfield" style="top:'.$bot.'mm;left:237.6mm">'.$body[$i]['tax'].'</div>
					<div class="field" style="top:'.$bot.'mm;right:18.8mm; width:25px; text-align:right"><i>(1)</i></div>';
			$tax += 13.8;		
			$bot += 13.8;
		}
	}
	$tot = 157.4;
	$html .= '
			<div class="nfield" style="top:'.$tot.'mm;left:202.2mm">'.number_format($tot_income,2).'</div>
			<div class="nfield" style="top:'.$tot.'mm;left:237.6mm">'.number_format($tot_tax,2).'</div>';
			
			if($edata['digi_signature'] == 1 && !empty($edata['dig_signature'])){
				$html .= '<div class="signature" style="top:175.4mm;left:206.8mm"><img width="49mm" src="'.ROOT.$edata['dig_signature'].'?'.time().'" /></div>';
			}
			if($edata['digi_stamp'] == 1 && !empty($edata['dig_stamp'])){
				$html .= '<div class="stamp" style="top:169mm;left:165mm"><img height="24mm" src="'.ROOT.$edata['dig_stamp'].'?'.time().'" /></div>';
			}

	$html .= '
			<div class="field tac" style="top:173.4mm;left:206.4mm;width:195px">'.$name_position['name'].'</div>
			<div class="field tac" style="top:185.6mm;left:207mm;width:195px">'.$name_position['position'].'</div>
			
			<div class="field" style="top:191.2mm;left:210mm;width:20px">'.$_SESSION['rego']['formdate']['d'].'</div>
			<div class="field" style="top:191.2mm;left:224.0mm;width:90px">'.$_SESSION['rego']['formdate']['thm'].'</div>
			<div class="field" style="top:191.2mm;left:253mm;width:50px">'.$_SESSION['rego']['formdate']['thy'].'</div>';
	
	if($page < $pages){
		$html .= '<pagebreak />';
	}
}	
	$html .= '</body> </html>';


	//echo $style.$html; exit;	
	//$_SESSION['rego']['pnd1_pages'] = $pages;
	ob_clean();
	require_once(DIR."mpdf7/vendor/autoload.php");
	$mpdf = new mPDF('utf-8', 'A4-L', 11, '', 8, 8, 8, 8, 5, 5);
	$mpdf->SetDisplayMode('fullpage');
	//$mpdf->SetFontSize(9);
	$mpdf->SetTitle($edata['th_compname'].' ('.strtoupper($_SESSION['rego']['cid']).') - ภ.​ง.​ด.​1 ใบ​แนบ​ - '.$months[(int)$_SESSION['rego']['gov_month']].' '.$_SESSION['rego']['cur_year']);
	
	$mpdf->WriteHTML($style,1);
	$mpdf->WriteHTML($html);

	$dir = DIR.$_SESSION['rego']['cid'].'/archive/';
	$root = ROOT.$_SESSION['rego']['cid'].'/archive/';
	$baseName = $_SESSION['rego']['cid'].'_pnd1_attachment_kor_'.$_SESSION['rego']['year_'.$lang];
	$extension = 'pdf';		
	$filename = getFilename($baseName, $extension, $dir);
	$doc = $lng['P.N.D.1 Attachment Kor'].' '.$_SESSION['rego']['year_'.$lang];
	
	$mpdf->Output($filename,'I');
	
	if(isset($_REQUEST['a'])){
		$mpdf->Output($dir.$filename,'F');
		include('save_to_documents.php');
	}
	
	exit;










