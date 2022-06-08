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

	$data = getPND1attach($_SESSION['rego']['payroll_dbase'],$_SESSION['rego']['gov_month'],$_SESSION['rego']['gov_entity']);
	
	$pages = 0;
	$body = $data['d'];
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
			background:url(../../images/pnd1_attach_en.jpg) no-repeat;
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
			<div class="field" style="top:10.6mm;left:243mm">'.$xdata['tax_id'].'</div>
			
			<div class="field" style="top:28.4mm;left:258.8mm">'.$xdata['revenu_branch'].'</div>
			
			<div class="field" style="top:33.2mm;left:24.6mm"><i class="fa">&#xf00c;</i></div>
			
			<div class="field" style="top:33mm;left:248.8mm; width:30px; text-align:center">'.$page.'</div>
			<div class="field" style="top:33mm;left:264mm; width:45px; text-align:center">'.$pages.'</div>';
	
	$tax = 73;
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
					<div class="field" style="top:'.($tax+2).'mm;left:8mm; width:43px; text-align:center">'.$i.'</div>
					<div class="field" style="top:'.$tax.'mm;left:29.0mm">'.$body[$i]['tax_id'].'</div>
					<div class="field" style="top:'.($tax+4.8).'mm;left:30mm;width:200px">'.$body[$i]['firstname'].'</div>
					<div class="field" style="top:'.($tax+4.8).'mm;left:108.0mm;width:200px">'.$body[$i]['lastname'].'</div>
					
					<div class="field" style="top:'.($tax+4.8).'mm;left:174.0mm">'.$_SESSION['rego']['paydate'].'</div>
					
					<div class="nfield" style="top:'.($tax+4.8).'mm;left:194.0mm">'.$body[$i]['grossincome'].'</div>
					<div class="nfield" style="top:'.($tax+4.8).'mm;left:229mm">'.$body[$i]['tax'].'</div>
					<div class="field" style="top:'.($tax+3).'mm;right:18.8mm; width:25px; text-align:right"><i>(1)</i></div>';
			$tax += 11.4;		
		}
	}
	$html .= '
			<div class="nfield" style="top:154mm;left:194mm">'.number_format($tot_income,2).'</div>
			<div class="nfield" style="top:154mm;left:229mm">'.number_format($tot_tax,2).'</div>';
				
			/*if($xdata['digi_signature'] == 1 && !empty($xdata['dig_signature'])){
				$html .= '<div class="signature" style="top:177.4mm;left:206.8mm"><img width="49mm" src="'.ROOT.$xdata['dig_signature'].'?'.time().'" /></div>';
			}
			if($xdata['digi_stamp'] == 1 && !empty($xdata['dig_stamp'])){
				$html .= '<div class="stamp" style="top:174mm;left:165mm"><img height="24mm" src="'.ROOT.$xdata['dig_stamp'].'?'.time().'" /></div>';
			}*/
	
	$html .= '
			<div class="field" style="top:168mm;left:203mm;width:195px">'.$name_position['name'].'</div>
			<div class="field" style="top:179.8mm;left:204mm;width:195px">'.$name_position['position'].'</div>
			
			<div class="field" style="top:186mm;left:214mm;width:20px">'.$_SESSION['rego']['formdate']['d'].'</div>
			<div class="field" style="top:186mm;left:227mm;width:90px">'.$_SESSION['rego']['formdate']['m'].'</div>
			<div class="field" style="top:186mm;left:255mm;width:50px">'.$_SESSION['rego']['formdate']['eny'].'</div>';
	
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
	$mpdf->SetTitle($xdata['en_compname'].' ('.strtoupper($_SESSION['rego']['cid']).') - PND1 Attachment - '.$months[(int)$_SESSION['rego']['gov_month']].' '.$_SESSION['rego']['cur_year']);
	
	$mpdf->WriteHTML($style,1);
	$mpdf->WriteHTML($html);

	$dir = DIR.$_SESSION['rego']['cid'].'/archive/';
	$root = ROOT.$_SESSION['rego']['cid'].'/archive/';
	$baseName = $_SESSION['rego']['cid'].'_pnd1_monthly_attachment_'.$_SESSION['rego']['curr_month'].'_'.$_SESSION['rego']['year_en'];
	$extension = 'pdf';		
	$filename = getFilename($baseName, $extension, $dir);
	$doc = $lng['P.N.D.1 Monthly Attachment'].' '.$_SESSION['rego']['curr_month'].'-'.$_SESSION['rego']['year_en'];
	
	$mpdf->Output($filename,'I');
	
	if(isset($_REQUEST['a'])){
		$mpdf->Output($dir.$filename,'F');
		include('save_to_documents.php');
	}
	
	exit;












