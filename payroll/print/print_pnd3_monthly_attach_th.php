<?php

	if(session_id()==''){session_start();}
	ob_start();
	include('../../dbconnect/db_connect.php');
	include(DIR.'files/functions.php');
	include(DIR.'files/arrays_en.php');
	include(DIR.'files/payroll_functions.php');
	
	$name_position = getFormNamePosition($cid);
	
	if($res = $dbc->query("SELECT th_compname, tax_id, revenu_branch, dig_signature, digi_signature, dig_stamp, digi_stamp FROM ".$cid."_entities_data WHERE ref = '".$_SESSION['rego']['gov_entity']."'")){
		$xdata = $res->fetch_assoc();
	}

	$branch = sprintf("%05d",$xdata['revenu_branch']);
	$branch = str_split($branch);
	
	$p = str_replace('-','',$xdata['tax_id']);
	if(strlen($p)!== 13){$p = '0000000000000';}
	$tin = str_split($p);

	$data = getPND3attach($_SESSION['rego']['payroll_dbase'],$_SESSION['rego']['gov_month'],$_SESSION['rego']['gov_entity']);
	
	$pages = 0;
	$body = $data['d'];
	$rows = count($body);
	$pages = ceil($rows/6);
	
	$tot = $rows/6;
	$floor = floor($rows/6);
	$per = $tot - $floor;
	$floor = floor($tot);
	$rest = 0;
	if($per < 1){$rest = 6 * $per;}
	
	$s = 1; $e = 6;
	$nr = 1;
	for($i=1;$i<=$floor;$i++){
		$pag[$nr] = array('s'=>$s, 'e'=>$e);
		$s+=8; $e+=6; $nr++;
	}
	if($rest > 0){
		$pag[$nr] = array('s'=>$s, 'e'=>$s+$rest-1);
	}

$style = '
<style>
		@page {
			margin: 10px 100px 10px 10px;
		}
		body, html {
			font-family: "leelawadee", "garuda";
			font-family: "garuda";
			font-size:14px;
			color:#000;
			position:relative;
		}
		body {
			background:url(../../images/forms/pnd3_attach_th_pdf.png) no-repeat;
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

$html = '<html><body>';

for($page=1;$page <= $pages;$page++){
	
	$html .= '
			<div class="field" style="top:8.4mm;left:132.6mm">'.$tin[0].'</div>
			<div class="field" style="top:8.4mm;left:138.8mm">'.$tin[1].'</div>
			<div class="field" style="top:8.4mm;left:143.0mm">'.$tin[2].'</div>
			<div class="field" style="top:8.4mm;left:147.2mm">'.$tin[3].'</div>
			<div class="field" style="top:8.4mm;left:151.4mm">'.$tin[4].'</div>
			<div class="field" style="top:8.4mm;left:157.8mm">'.$tin[5].'</div>
			<div class="field" style="top:8.4mm;left:162.0mm">'.$tin[6].'</div>
			<div class="field" style="top:8.4mm;left:166.0mm">'.$tin[7].'</div>
			<div class="field" style="top:8.4mm;left:170.2mm">'.$tin[8].'</div>
			<div class="field" style="top:8.4mm;left:174.4mm">'.$tin[9].'</div>
			<div class="field" style="top:8.4mm;left:180.8mm">'.$tin[10].'</div>
			<div class="field" style="top:8.4mm;left:185.0mm">'.$tin[11].'</div>
			<div class="field" style="top:8.4mm;left:191.2mm">'.$tin[12].'</div>
			
			<div class="field" style="top:7.8mm;left:269.8mm">'.$branch[0].'</div>
			<div class="field" style="top:7.8mm;left:273.8mm">'.$branch[1].'</div>
			<div class="field" style="top:7.8mm;left:277.8mm">'.$branch[2].'</div>
			<div class="field" style="top:7.8mm;left:282.0mm">'.$branch[3].'</div>
			<div class="field" style="top:7.8mm;left:286.4mm">'.$branch[4].'</div>
			
			<div class="field" style="top:14.2mm;left:247mm; width:30px; text-align:center">'.$page.'</div>
			<div class="field" style="top:14.2mm;left:268mm; width:45px; text-align:center">'.$pages.'</div>';
	
	$tax = 42.4;
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
					<div class="field" style="top:'.($tax+6).'mm;left:7.0mm; width:43px; text-align:center">'.$i.'</div>
					<div class="field" style="top:'.$tax.'mm;left:21.6mm">'.$etax[0].'</div>
					<div class="field" style="top:'.$tax.'mm;left:27.8mm">'.$etax[1].'</div>
					<div class="field" style="top:'.$tax.'mm;left:32.0mm">'.$etax[2].'</div>
					<div class="field" style="top:'.$tax.'mm;left:36.2mm">'.$etax[3].'</div>
					<div class="field" style="top:'.$tax.'mm;left:40.4mm">'.$etax[4].'</div>
					<div class="field" style="top:'.$tax.'mm;left:46.8mm">'.$etax[5].'</div>
					<div class="field" style="top:'.$tax.'mm;left:51.0mm">'.$etax[6].'</div>
					<div class="field" style="top:'.$tax.'mm;left:55.2mm">'.$etax[7].'</div>
					<div class="field" style="top:'.$tax.'mm;left:59.4mm">'.$etax[8].'</div>
					<div class="field" style="top:'.$tax.'mm;left:63.6mm">'.$etax[9].'</div>
					<div class="field" style="top:'.$tax.'mm;left:69.8mm">'.$etax[10].'</div>
					<div class="field" style="top:'.$tax.'mm;left:74.0mm">'.$etax[11].'</div>
					<div class="field" style="top:'.$tax.'mm;left:80.2mm">'.$etax[12].'</div>
					<div class="field" style="top:'.($tax+6.2).'mm;left:26.0mm;width:200px">'.$body[$i]['firstname'].'</div>
					<div class="field" style="top:'.($tax+6.2).'mm;left:97.0mm;width:200px">'.$body[$i]['lastname'].'</div>
					<div class="field" style="top:'.($tax+12.2).'mm;left:28.0mm;width:500px">'.$body[$i]['address'].'</div>

					<div class="field" style="top:'.($tax+6).'mm;left:143mm">'.$_SESSION['rego']['paydate'].'</div>
					
					<div class="field tac" style="top:'.($tax+6).'mm;left:166mm; width:140px">'.$lng['Wage'].'</div>
					<div class="nfield" style="top:'.($tax+6).'mm;left:176mm">3</div>
					<div class="nfield" style="top:'.($tax+6).'mm;left:212.6mm">'.$body[$i]['grossincome'].'</div>
					<div class="nfield" style="top:'.($tax+6).'mm;left:247.2mm">'.$body[$i]['tax'].'</div>
					<div class="field" style="top:'.($tax+6).'mm;right:9mm; width:25px; text-align:right"><i>('.$body[$i]['type'].')</i></div>';
			$tax += 19.6;		
		}
	}

	$html .= '
			<div class="nfield" style="top:159mm;left:212.8mm">'.number_format($tot_income,2).'</div>
			<div class="nfield" style="top:159mm;left:247.4mm">'.number_format($tot_tax,2).'</div>';
				
			if($xdata['digi_signature'] == 1 && !empty($xdata['dig_signature'])){
				$html .= '<div class="signature" style="top:179.4mm;left:213.8mm"><img width="49mm" src="'.ROOT.$xdata['dig_signature'].'?'.time().'" /></div>';
			}
			if($xdata['digi_stamp'] == 1 && !empty($xdata['dig_stamp'])){
				$html .= '<div class="stamp" style="top:174mm;left:175mm"><img height="24mm" src="'.ROOT.$xdata['dig_stamp'].'?'.time().'" /></div>';
			}
	
	$html .= '
			<div class="field tac" style="top:177.4mm;left:213mm;width:195px">'.$name_position['name'].'</div>
			<div class="field" style="top:189.8mm;left:220mm;width:195px">'.$name_position['position'].'</div>
			
			<div class="field" style="top:196.0mm;left:219mm;width:20px">'.$_SESSION['rego']['formdate']['d'].'</div>
			<div class="field" style="top:196.0mm;left:234.0mm;width:90px">'.$_SESSION['rego']['formdate']['thm'].'</div>
			<div class="field" style="top:196.0mm;left:266mm;width:50px">'.$_SESSION['rego']['formdate']['thy'].'</div>';
	
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
	$mpdf->SetTitle($xdata['th_compname'].' ('.strtoupper($_SESSION['rego']['cid']).') - ภ.​ง.​ด.​1 ใบ​แนบ​ - '.$months[(int)$_SESSION['rego']['gov_month']].' '.$_SESSION['rego']['cur_year']);
	
	$mpdf->WriteHTML($style,1);
	$mpdf->WriteHTML($html);

	$dir = DIR.$_SESSION['rego']['cid'].'/archive/';
	$root = ROOT.$_SESSION['rego']['cid'].'/archive/';
	$baseName = $_SESSION['rego']['cid'].'_pnd1_monthly_attachment_'.$_SESSION['rego']['curr_month'].'_'.$_SESSION['rego']['year_'.$lang];
	$extension = 'pdf';		
	$filename = getFilename($baseName, $extension, $dir);
	$doc = $lng['P.N.D.1 Monthly Attachment'].' '.$_SESSION['rego']['curr_month'].'-'.$_SESSION['rego']['year_'.$lang];
	
	$mpdf->Output($filename,'I');
	
	if(isset($_REQUEST['a'])){
		$mpdf->Output($dir.$filename,'F');
		include('save_to_documents.php');
	}
	
	exit;












