<?php

	if(session_id()==''){session_start();}
	ob_start();
	 
	include('../../dbconnect/db_connect.php');
	include(DIR.'files/functions.php');
	include(DIR.'files/arrays_en.php');
	include(DIR.'files/payroll_functions.php');
	
	if($res = $dbc->query("SELECT ".$lang."_addr_detail, th_compname, comp_phone, comp_fax, tax_id, revenu_branch, dig_signature, digi_signature, dig_stamp, digi_stamp, sso_account, sso_codes FROM ".$cid."_entities_data WHERE ref = '".$_SESSION['rego']['gov_entity']."'")){
		$edata = $res->fetch_assoc();
		$address = unserialize($edata[$lang.'_addr_detail']);
		$sso_codes = unserialize($edata['sso_codes']);
	}else{
		//echo mysqli_error($dbc);
	}
	$name_position = getFormNamePosition($cid);

	$branch = sprintf("%06d",$edata['revenu_branch']);
	$branch = str_split($branch);

	$sso_id = str_replace('-', '', $edata['sso_account']);
	if(strlen($sso_id) !== 10){$sso_id = '0000000000';}
	$sso_id = str_split($sso_id);

	$data = getSSOattach($_SESSION['rego']['payroll_dbase'],$_SESSION['rego']['gov_month'],'th', $pr_settings['sso_act_max']);
	$body = $data['d'];
	/*foreach($body as $k=>$v){
		$body[] = $v;
	}*/
	//$c = count($data['d']);
	//var_dump($body); exit;
	
	$pages = 0;
	$rows = count($body);
	$pages = ceil($rows/10);
	$tot = $rows/10;
	$floor = floor($rows/10);
	$per = $tot - $floor;
	$floor = floor($tot);
	$rest = 0;
	if($per < 1){$rest = 10 * $per;}
	$s = 1; $e = 10;
	$nr = 1;
	for($i=1;$i<=$floor;$i++){
		$pag[$nr] = array('s'=>$s, 'e'=>$e);
		$s+=10; $e+=10; $nr++;
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
			color:#036;
			position:relative;
		}
		body {
			background:url(../../images/forms/sso_attach_th_pdf.png) no-repeat;
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

$html = '<html><body style="background:url(../../images/forms/sso_attach_th_pdf.png) no-repeat;">';

for($page=1;$page <= $pages;$page++){
	$top = 26.6;
	$bra = 33.8;
	$html .= '
			<div class="field" style="top:19.8mm;left:49.0mm">'.$months[$_SESSION['rego']['gov_month']].'</div>
			<div class="field" style="top:19.8mm;left:93.0mm">'.$_SESSION['rego']['year_th'].'</div>
			<div class="field" style="top:27.2mm;left:52.0mm">'.$compinfo['th_compname'].'</div>
			
			<div class="field" style="top:'.$top.'mm;left:220.6mm">'.$sso_id[0].'</div>
			<div class="field" style="top:'.$top.'mm;left:225.6mm">'.$sso_id[1].'</div>
			<div class="field" style="top:'.$top.'mm;left:233.2mm">'.$sso_id[2].'</div>
			<div class="field" style="top:'.$top.'mm;left:238.2mm">'.$sso_id[3].'</div>
			<div class="field" style="top:'.$top.'mm;left:243.4mm">'.$sso_id[4].'</div>
			<div class="field" style="top:'.$top.'mm;left:248.6mm">'.$sso_id[5].'</div>
			<div class="field" style="top:'.$top.'mm;left:253.6mm">'.$sso_id[6].'</div>
			<div class="field" style="top:'.$top.'mm;left:258.6mm">'.$sso_id[7].'</div>
			<div class="field" style="top:'.$top.'mm;left:263.8mm">'.$sso_id[8].'</div>
			<div class="field" style="top:'.$top.'mm;left:271.2mm">'.$sso_id[9].'</div>
			
			<div class="field" style="top:'.$bra.'mm;left:220.6mm">'.$branch[0].'</div>
			<div class="field" style="top:'.$bra.'mm;left:225.6mm">'.$branch[1].'</div>
			<div class="field" style="top:'.$bra.'mm;left:230.8mm">'.$branch[2].'</div>
			<div class="field" style="top:'.$bra.'mm;left:235.8mm">'.$branch[3].'</div>
			<div class="field" style="top:'.$bra.'mm;left:240.8mm">'.$branch[4].'</div>
			<div class="field" style="top:'.$bra.'mm;left:245.8mm">'.$branch[5].'</div>
			
			<div class="field" style="top:19.8mm;left:210mm; width:30px; text-align:center">'.$page.'</div>
			<div class="field" style="top:19.8mm;left:246mm; width:45px; text-align:center">'.$pages.'</div>';
	
	$tax = 65.2;
	$tot_income = 0;
	$tot_sso = 0;
	if($body){
		for($i=$pag[$page]['s'];$i<=$pag[$page]['e'];$i++){
			$tmp = str_replace('-','',$body[$i]['tax_id']);
			if(strlen($tmp)!== 13){$tmp = '0000000000000';}
			$etax = str_split($tmp);
			$tot_income += str_replace(',','',$body[$i]['basic_salary']);
			//$tot_sso += str_replace(',','',$body[$i]['sso']);
			$tot_sso += $body[$i]['sso'];
			$html .= '
					<div class="field" style="top:'.($tax+0.8).'mm;left:20.0mm; width:43px; text-align:center">'.$i.'</div>
					<div class="field" style="top:'.$tax.'mm;left:37.8mm">'.$etax[0].'</div>
					<div class="field" style="top:'.$tax.'mm;left:45.2mm">'.$etax[1].'</div>
					<div class="field" style="top:'.$tax.'mm;left:50.4mm">'.$etax[2].'</div>
					<div class="field" style="top:'.$tax.'mm;left:55.6mm">'.$etax[3].'</div>
					<div class="field" style="top:'.$tax.'mm;left:60.6mm">'.$etax[4].'</div>
					<div class="field" style="top:'.$tax.'mm;left:68.2mm">'.$etax[5].'</div>
					<div class="field" style="top:'.$tax.'mm;left:73.2mm">'.$etax[6].'</div>
					<div class="field" style="top:'.$tax.'mm;left:78.4mm">'.$etax[7].'</div>
					<div class="field" style="top:'.$tax.'mm;left:83.4mm">'.$etax[8].'</div>
					<div class="field" style="top:'.$tax.'mm;left:88.4mm">'.$etax[9].'</div>
					<div class="field" style="top:'.$tax.'mm;left:96.0mm">'.$etax[10].'</div>
					<div class="field" style="top:'.$tax.'mm;left:101.0mm">'.$etax[11].'</div>
					<div class="field" style="top:'.$tax.'mm;left:108.6mm">'.$etax[12].'</div>
					
					<div class="field" style="top:'.($tax+1).'mm;left:118.0mm;width:200px">'.$body[$i]['title'].' '.$body[$i]['firstname'].' '.$body[$i]['lastname'].'</div>
					
					<div class="nfield" style="top:'.($tax+1).'mm;left:200.4mm">'.$body[$i]['basic_salary'].'</div>
					<div class="nfield" style="top:'.($tax+1).'mm;left:235.4mm">'.$body[$i]['sso'].'</div>';
			$tax += 7.6;		
		}
	}
	$tot = 144;
	$html .= '
			<div class="nfield" style="top:'.$tot.'mm;left:200.4mm">'.number_format($tot_income,2).'</div>
			<div class="nfield" style="top:'.$tot.'mm;left:235.4mm">'.number_format($tot_sso).'</div>';
				
			if($edata['digi_signature'] == 1 && !empty($edata['dig_signature'])){
				$html .= '<div class="signature" style="top:159mm;left:210mm"><img width="49mm" src="'.ROOT.$edata['dig_signature'].'?'.time().'" /></div>';
			}
			if($edata['digi_stamp'] == 1 && !empty($edata['dig_stamp'])){
				$html .= '<div class="stamp" style="top:155.4mm;left:172mm"><img height="24mm" src="'.ROOT.$edata['dig_stamp'].'?'.time().'" /></div>';
			}
	
	$html .= '
			<div class="field tac" style="top:156.6mm;left:208.4mm;width:210px">'.$name_position['name'].'</div>
			<div class="field tal" style="top:169.0mm;left:212mm;width:225px">'.$name_position['position'].'</div>
			
			<div class="field" style="top:175.4mm;left:217mm;width:20px">'.$_SESSION['rego']['formdate']['d'].'</div>
			<div class="field" style="top:175.4mm;left:235.0mm;width:90px">'.$_SESSION['rego']['formdate']['thm'].'</div>
			<div class="field" style="top:175.4mm;left:270mm;width:50px">'.$_SESSION['rego']['formdate']['thy'].'</div>';
	
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
	
	$mpdf->SetTitle($compinfo['th_compname'].' ('.strtoupper($_SESSION['rego']['cid']).') - สปส.1-10 ใบ​แนบ​ - '.$months[(int)$_SESSION['rego']['gov_month']].' '.$_SESSION['rego']['cur_year']);
	
	$mpdf->WriteHTML($style,1);
	$mpdf->WriteHTML($html);

	$dir = DIR.$_SESSION['rego']['cid'].'/archive/';
	$root = ROOT.$_SESSION['rego']['cid'].'/archive/';
	$baseName = $_SESSION['rego']['cid'].'_sso_attachment_'.$_SESSION['rego']['curr_month'].'_'.$_SESSION['rego']['year_'.$lang];
	$extension = 'pdf';		
	$filename = getFilename($baseName, $extension, $dir);
	$doc = $lng['SSO Attachment'].' '.$_SESSION['rego']['curr_month'].'-'.$_SESSION['rego']['year_'.$lang];

	$mpdf->Output($filename,'I');
	
	if(isset($_REQUEST['a'])){
		$mpdf->Output($dir.$filename,'F');
		include('save_to_documents.php');
	}
	
	exit;











