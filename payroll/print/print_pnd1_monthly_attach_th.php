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

	$data = getPND1attach($_SESSION['rego']['payroll_dbase'],$_SESSION['rego']['gov_month'],$_SESSION['rego']['gov_entity']);

	/*echo "<pre>";
	print_r($data);
	echo "</pre>";*/
	

	$pages = 0;
	$body = $data['d'];
	$body2 = $data['d2'];

	$rows = count($body);
	$rows2 = count($body2);
	$trows = $rows+$rows2;

	$pages1 = ceil($rows/8);
	$pages2 = ceil($rows2/8);
	$pages = $pages1+$pages2;

	/*echo $pages1; echo '<br>';
	echo $pages2; echo '<br>';
	echo $pages; echo '<br>';

	die();*/
	
	$tot = $rows/8;
	$floor = floor($rows/8);
	$per = $tot - $floor;
	$floor = floor($tot);
	$rest = 0;
	if($per < 1){$rest = 8 * $per;}
	
	$s = 1; $e = 8;
	$nr = 1;
	for($i=1;$i<=$floor;$i++){
		$pag[$nr] = array('s'=>$s, 'e'=>$e);
		$s+=8; $e+=8; $nr++;
	}
	if($rest > 0){
		$pag[$nr] = array('s'=>$s, 'e'=>$s+$rest-1);
	}


	$tot2 = $rows2/8;
	$floor2 = floor($rows2/8);
	$per2 = $tot2 - $floor2;
	$floor2 = floor($tot2);
	$rest2 = 0;
	if($per2 < 1){$rest2 = 8 * $per2;}
	
	$s2 = 1; $e2 = 8;
	$nr2 = 1;
	for($i2=1;$i2<=$floor2;$i2++){
		$pag2[$nr2] = array('s'=>$s2, 'e'=>$e2);
		$s2+=8; $e2+=8; $nr2++;
	}
	if($rest2 > 0){
		$pag2[$nr2] = array('s'=>$s2, 'e'=>$s2+$rest2-1);
	}

	$Paydate = $_SESSION['rego']['paydate'];
	$getdate = date('d', strtotime($Paydate));
	$getmonth = date('m', strtotime($Paydate));
	$newPaydate = $getdate.'-'.$getmonth.'-'.$_SESSION['rego']['year_'.$lang];

	// echo '<pre>';
	// print_r($pag);
	// print_r($pag2);
	// echo '</pre>';
	// die();

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
			background:url(../../images/forms/pnd1_attach_th_pdf.png) no-repeat;
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



$html = '<html><body style="background:url(../../images/forms/pnd1_attach_th_pdf.png) no-repeat;">';

for($page=1;$page <= $pages;$page++){
	
	if($pages1 >= $page){ 
		//echo $page; echo ' = 1<br>';
		//-----------for PND1 40(1)------------//
		if($body){

			$html .= '
				<div class="field" style="top:7.4mm;left:152.2mm">'.$tin[0].'</div>
				<div class="field" style="top:7.4mm;left:158.4mm">'.$tin[1].'</div>
				<div class="field" style="top:7.4mm;left:162.6mm">'.$tin[2].'</div>
				<div class="field" style="top:7.4mm;left:166.8mm">'.$tin[3].'</div>
				<div class="field" style="top:7.4mm;left:171.0mm">'.$tin[4].'</div>
				<div class="field" style="top:7.4mm;left:177.0mm">'.$tin[5].'</div>
				<div class="field" style="top:7.4mm;left:181.2mm">'.$tin[6].'</div>
				<div class="field" style="top:7.4mm;left:185.4mm">'.$tin[7].'</div>
				<div class="field" style="top:7.4mm;left:189.6mm">'.$tin[8].'</div>
				<div class="field" style="top:7.4mm;left:193.8mm">'.$tin[9].'</div>
				<div class="field" style="top:7.4mm;left:200.4mm">'.$tin[10].'</div>
				<div class="field" style="top:7.4mm;left:204.4mm">'.$tin[11].'</div>
				<div class="field" style="top:7.4mm;left:210.6mm">'.$tin[12].'</div>
				
				<div class="field" style="top:16.8mm;left:260.6mm">'.$branch[0].'</div>
				<div class="field" style="top:16.8mm;left:264.2mm">'.$branch[1].'</div>
				<div class="field" style="top:16.8mm;left:267.8mm">'.$branch[2].'</div>
				<div class="field" style="top:16.8mm;left:271.8mm">'.$branch[3].'</div>
				<div class="field" style="top:16.8mm;left:275.8mm">'.$branch[4].'</div>
				
				<div class="field" style="top:23mm;left:37.0mm"><img src="../../images/forms/check.png"></div>
				
				<div class="field" style="top:38.2mm;left:239.2mm; width:30px; text-align:center">'.$page.'</div>
				<div class="field" style="top:38.2mm;left:261.2mm; width:45px; text-align:center">'.$pages.'</div>';
		
			$tax = 59.6;
			$bot = 66.2;
			$tot_income = 0;
			$tot_tax = 0;



			for($i=$pag[$page]['s'];$i<=$pag[$page]['e'];$i++){
				$tmp = str_replace('-','',$body[$i]['tax_id']);
				if(strlen($tmp)!== 13){$tmp = '0000000000000';}
				$etax = str_split($tmp);
				$tot_income += str_replace(',','',$body[$i]['grossincome']);
				$tot_tax += str_replace(',','',$body[$i]['tax']);
				$html .= '
						<div class="field" style="top:'.($bot-4).'mm;left:18.0mm; width:43px; text-align:center">'.$i.'</div>
						<div class="field" style="top:'.$tax.'mm;left:33.0mm">'.$etax[0].'</div>
						<div class="field" style="top:'.$tax.'mm;left:39.2mm">'.$etax[1].'</div>
						<div class="field" style="top:'.$tax.'mm;left:43.4mm">'.$etax[2].'</div>
						<div class="field" style="top:'.$tax.'mm;left:47.6mm">'.$etax[3].'</div>
						<div class="field" style="top:'.$tax.'mm;left:51.8mm">'.$etax[4].'</div>
						<div class="field" style="top:'.$tax.'mm;left:58.2mm">'.$etax[5].'</div>
						<div class="field" style="top:'.$tax.'mm;left:62.4mm">'.$etax[6].'</div>
						<div class="field" style="top:'.$tax.'mm;left:66.6mm">'.$etax[7].'</div>
						<div class="field" style="top:'.$tax.'mm;left:70.8mm">'.$etax[8].'</div>
						<div class="field" style="top:'.$tax.'mm;left:75.0mm">'.$etax[9].'</div>
						<div class="field" style="top:'.$tax.'mm;left:81.2mm">'.$etax[10].'</div>
						<div class="field" style="top:'.$tax.'mm;left:85.4mm">'.$etax[11].'</div>
						<div class="field" style="top:'.$tax.'mm;left:91.6mm">'.$etax[12].'</div>
						<div class="field" style="top:'.$bot.'mm;left:38.0mm;width:200px">'.$body[$i]['firstname'].'</div>
						<div class="field" style="top:'.$bot.'mm;left:104.0mm;width:200px">'.$body[$i]['lastname'].'</div>
						
						<div class="field" style="top:'.$bot.'mm;left:176.0mm">'.$newPaydate.'</div>
						
						<div class="nfield" style="top:'.$bot.'mm;left:202.0mm">'.$body[$i]['grossincome'].'</div>
						<div class="nfield" style="top:'.$bot.'mm;left:237.4mm">'.$body[$i]['tax'].'</div>
						<div class="field" style="top:'.$bot.'mm;right:18.8mm; width:25px; text-align:right"><i>('.$v['type'].')</i></div>';
				$tax += 13.4;		
				$bot += 13.4;
			}
		
			$tot = 167.4;
			$html .= '
					<div class="nfield" style="top:'.$tot.'mm;left:202.0mm">'.number_format($tot_income,2).'</div>
					<div class="nfield" style="top:'.$tot.'mm;left:237.4mm">'.number_format($tot_tax,2).'</div>';
						
					if($xdata['digi_signature'] == 1 && !empty($xdata['dig_signature'])){
						$html .= '<div class="signature" style="top:177.4mm;left:206.8mm"><img width="49mm" src="'.ROOT.$xdata['dig_signature'].'?'.time().'" /></div>';
					}
					if($xdata['digi_stamp'] == 1 && !empty($xdata['dig_stamp'])){
						$html .= '<div class="stamp" style="top:174mm;left:165mm"><img height="24mm" src="'.ROOT.$xdata['dig_stamp'].'?'.time().'" /></div>';
					}
			
			$html .= '
					<div class="field tac" style="top:175.0mm;left:206.4mm;width:195px">'.$name_position['name'].'</div>
					<div class="field tac" style="top:187mm;left:207mm;width:195px">'.$name_position['position'].'</div>
					
					<div class="field" style="top:193.0mm;left:209mm;width:20px">'.$_SESSION['rego']['formdate']['d'].'</div>
					<div class="field" style="top:193.0mm;left:224.0mm;width:90px">'.$_SESSION['rego']['formdate']['thm'].'</div>
					<div class="field" style="top:193.0mm;left:252mm;width:50px">'.$_SESSION['rego']['formdate']['thy'].'</div>';
			
			if($page < $pages){
				$html .= '<pagebreak />';
			}
		}

	}

	if($page > $pages1){
		//echo $page; echo ' = 2<br>';
		//-----------for PND1 40(2)------------//
		if(!empty($data['d2'])){
			
			//$html .= '<pagebreak />';
			
				$html .= '
						<div class="field" style="top:7.4mm;left:152.2mm">'.$tin[0].'</div>
						<div class="field" style="top:7.4mm;left:158.4mm">'.$tin[1].'</div>
						<div class="field" style="top:7.4mm;left:162.6mm">'.$tin[2].'</div>
						<div class="field" style="top:7.4mm;left:166.8mm">'.$tin[3].'</div>
						<div class="field" style="top:7.4mm;left:171.0mm">'.$tin[4].'</div>
						<div class="field" style="top:7.4mm;left:177.0mm">'.$tin[5].'</div>
						<div class="field" style="top:7.4mm;left:181.2mm">'.$tin[6].'</div>
						<div class="field" style="top:7.4mm;left:185.4mm">'.$tin[7].'</div>
						<div class="field" style="top:7.4mm;left:189.6mm">'.$tin[8].'</div>
						<div class="field" style="top:7.4mm;left:193.8mm">'.$tin[9].'</div>
						<div class="field" style="top:7.4mm;left:200.4mm">'.$tin[10].'</div>
						<div class="field" style="top:7.4mm;left:204.4mm">'.$tin[11].'</div>
						<div class="field" style="top:7.4mm;left:210.6mm">'.$tin[12].'</div>
						
						<div class="field" style="top:16.8mm;left:260.6mm">'.$branch[0].'</div>
						<div class="field" style="top:16.8mm;left:264.2mm">'.$branch[1].'</div>
						<div class="field" style="top:16.8mm;left:267.8mm">'.$branch[2].'</div>
						<div class="field" style="top:16.8mm;left:271.8mm">'.$branch[3].'</div>
						<div class="field" style="top:16.8mm;left:275.8mm">'.$branch[4].'</div>
						
						<div class="field" style="top:29.5mm;left:119.0mm"><img src="../../images/forms/check.png"></div>
						
						<div class="field" style="top:38.2mm;left:239.2mm; width:30px; text-align:center">'.$page.'</div>
						<div class="field" style="top:38.2mm;left:261.2mm; width:45px; text-align:center">'.$pages.'</div>';
				
				$tax = 59.6;
				$bot = 66.2;
				$tot_income = 0;
				$tot_tax = 0;

				if($body2){
					$pagecc = $page - $pages1;
					for($i=$pag2[$pagecc]['s'];$i<=$pag2[$pagecc]['e'];$i++){
						$tmp = str_replace('-','',$body2[$i]['tax_id']);
						if(strlen($tmp)!== 13){$tmp = '0000000000000';}
						$etax = str_split($tmp);
						$tot_income += str_replace(',','',$body2[$i]['grossincome']);
						$tot_tax += str_replace(',','',$body2[$i]['tax']);
						$html .= '
								<div class="field" style="top:'.($bot-4).'mm;left:18.0mm; width:43px; text-align:center">'.$i.'</div>
								<div class="field" style="top:'.$tax.'mm;left:33.0mm">'.$etax[0].'</div>
								<div class="field" style="top:'.$tax.'mm;left:39.2mm">'.$etax[1].'</div>
								<div class="field" style="top:'.$tax.'mm;left:43.4mm">'.$etax[2].'</div>
								<div class="field" style="top:'.$tax.'mm;left:47.6mm">'.$etax[3].'</div>
								<div class="field" style="top:'.$tax.'mm;left:51.8mm">'.$etax[4].'</div>
								<div class="field" style="top:'.$tax.'mm;left:58.2mm">'.$etax[5].'</div>
								<div class="field" style="top:'.$tax.'mm;left:62.4mm">'.$etax[6].'</div>
								<div class="field" style="top:'.$tax.'mm;left:66.6mm">'.$etax[7].'</div>
								<div class="field" style="top:'.$tax.'mm;left:70.8mm">'.$etax[8].'</div>
								<div class="field" style="top:'.$tax.'mm;left:75.0mm">'.$etax[9].'</div>
								<div class="field" style="top:'.$tax.'mm;left:81.2mm">'.$etax[10].'</div>
								<div class="field" style="top:'.$tax.'mm;left:85.4mm">'.$etax[11].'</div>
								<div class="field" style="top:'.$tax.'mm;left:91.6mm">'.$etax[12].'</div>
								<div class="field" style="top:'.$bot.'mm;left:38.0mm;width:200px">'.$body2[$i]['firstname'].'</div>
								<div class="field" style="top:'.$bot.'mm;left:104.0mm;width:200px">'.$body2[$i]['lastname'].'</div>
								
								<div class="field" style="top:'.$bot.'mm;left:176.0mm">'.$newPaydate.'</div>
								
								<div class="nfield" style="top:'.$bot.'mm;left:202.0mm">'.$body2[$i]['grossincome'].'</div>
								<div class="nfield" style="top:'.$bot.'mm;left:237.4mm">'.$body2[$i]['tax'].'</div>
								<div class="field" style="top:'.$bot.'mm;right:18.8mm; width:25px; text-align:right"><i>('.$v['type'].')</i></div>';
						$tax += 13.4;		
						$bot += 13.4;
					}
				
					$tot = 167.4;
					$html .= '
							<div class="nfield" style="top:'.$tot.'mm;left:202.0mm">'.number_format($tot_income,2).'</div>
							<div class="nfield" style="top:'.$tot.'mm;left:237.4mm">'.number_format($tot_tax,2).'</div>';
								
							if($xdata['digi_signature'] == 1 && !empty($xdata['dig_signature'])){
								$html .= '<div class="signature" style="top:177.4mm;left:206.8mm"><img width="49mm" src="'.ROOT.$xdata['dig_signature'].'?'.time().'" /></div>';
							}
							if($xdata['digi_stamp'] == 1 && !empty($xdata['dig_stamp'])){
								$html .= '<div class="stamp" style="top:174mm;left:165mm"><img height="24mm" src="'.ROOT.$xdata['dig_stamp'].'?'.time().'" /></div>';
							}
					
					$html .= '
							<div class="field tac" style="top:175.0mm;left:206.4mm;width:195px">'.$name_position['name'].'</div>
							<div class="field tac" style="top:187mm;left:207mm;width:195px">'.$name_position['position'].'</div>
							
							<div class="field" style="top:193.0mm;left:209mm;width:20px">'.$_SESSION['rego']['formdate']['d'].'</div>
							<div class="field" style="top:193.0mm;left:224.0mm;width:90px">'.$_SESSION['rego']['formdate']['thm'].'</div>
							<div class="field" style="top:193.0mm;left:252mm;width:50px">'.$_SESSION['rego']['formdate']['thy'].'</div>';
					
					if($page < $pages){
						$html .= '<pagebreak />';
					}
				}	
		}
	}

}//loop close

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












