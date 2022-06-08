<?php
	
	if(session_id()==''){session_start();}
	ob_start();
	include('../dbconnect/db_connect.php');
	/*$_REQUEST['ctax'] = '0000000000000';
	$_REQUEST['xtax'] = '0000000000000';
	$_REQUEST['ccompany'] = '0000000000000';
	$_REQUEST['caddress'] = '0000000000000';
	$_REQUEST['xcompany'] = '0000000000000';
	$_REQUEST['xaddress'] = '0000000000000';*/
	
	$template = array();
	$res = $dbx->query("SELECT * FROM rego_documents WHERE id = 1");
	if($row = $res->fetch_assoc()){
		$template = $row;
	}	
	//var_dump($template); exit;
	
	$ctax = str_replace('-','',$_REQUEST['ctax']);
	if(strlen($ctax)!== 13){$ctax = '?????????????';}
	$ctax = str_split($ctax);
	
	$xtax = str_replace('-','',$_REQUEST['xtax']);
	if(strlen($xtax)!== 13){$xtax = '?????????????';}
	$xtax = str_split($xtax);
	
	//var_dump($_REQUEST); exit;
	
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
			background:url(../../images/wht_certificate.png?'.time().');
			background-repeat: no-repeat;
			background-image-resize:6;
		}
		div.field, div.field14, div.field13n, div.field13c, div.field11, div.field12 {
			position:absolute;
			min-height:12px;
			min-width:20px;
			border:0px solid red;
			font-size:13px;
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
	
$html = '<html>
			<body>
			
			<div class="field14 tac" style="top:30.4mm;left:133.8mm">'.$ctax[0].'</div>
			<div class="field14 tac" style="top:30.4mm;left:140mm">'.$ctax[1].'</div>
			<div class="field14 tac" style="top:30.4mm;left:144.4mm">'.$ctax[2].'</div>
			<div class="field14 tac" style="top:30.4mm;left:148.8mm">'.$ctax[3].'</div>
			<div class="field14 tac" style="top:30.4mm;left:153mm">'.$ctax[4].'</div>
			<div class="field14 tac" style="top:30.4mm;left:159.4mm">'.$ctax[5].'</div>
			<div class="field14 tac" style="top:30.4mm;left:163.6mm">'.$ctax[6].'</div>
			<div class="field14 tac" style="top:30.4mm;left:167.8mm">'.$ctax[7].'</div>
			<div class="field14 tac" style="top:30.4mm;left:172.2mm">'.$ctax[8].'</div>
			<div class="field14 tac" style="top:30.4mm;left:176.4mm">'.$ctax[9].'</div>
			<div class="field14 tac" style="top:30.4mm;left:182.8mm">'.$ctax[10].'</div>
			<div class="field14 tac" style="top:30.4mm;left:187mm">'.$ctax[11].'</div>
			<div class="field14 tac" style="top:30.4mm;left:193.8mm">'.$ctax[12].'</div>
			<div class="field" style="top:36.8mm;left:20mm">'.$_REQUEST["ccompany"].'</div>
			<div class="field" style="top:45mm;left:22mm">'.$_REQUEST["caddress"].'</div>
			
			<div class="field14 tac" style="top:54.8mm;left:133.8mm">'.$xtax[0].'</div>
			<div class="field14 tac" style="top:54.8mm;left:140mm">'.$xtax[1].'</div>
			<div class="field14 tac" style="top:54.8mm;left:144.4mm">'.$xtax[2].'</div>
			<div class="field14 tac" style="top:54.8mm;left:148.8mm">'.$xtax[3].'</div>
			<div class="field14 tac" style="top:54.8mm;left:153mm">'.$xtax[4].'</div>
			<div class="field14 tac" style="top:54.8mm;left:159.4mm">'.$xtax[5].'</div>
			<div class="field14 tac" style="top:54.8mm;left:163.6mm">'.$xtax[6].'</div>
			<div class="field14 tac" style="top:54.8mm;left:167.8mm">'.$xtax[7].'</div>
			<div class="field14 tac" style="top:54.8mm;left:172.2mm">'.$xtax[8].'</div>
			<div class="field14 tac" style="top:54.8mm;left:176.4mm">'.$xtax[9].'</div>
			<div class="field14 tac" style="top:54.8mm;left:182.8mm">'.$xtax[10].'</div>
			<div class="field14 tac" style="top:54.8mm;left:187mm">'.$xtax[11].'</div>
			<div class="field14 tac" style="top:54.8mm;left:193.8mm">'.$xtax[12].'</div>
			<div class="field" style="top:62.4mm;left:20mm">'.$_REQUEST["xcompany"].'</div>
			<div class="field" style="top:71.6mm;left:22mm">'.$_REQUEST["xaddress"].'</div>
			
			<div class="field" style="top:87.6mm;left:140.6mm"><b>X</b></div>
			
			<div class="field tar" style="top:217mm;right:68.6mm;width:90px">'.date('d-m-').(date('Y')+543).'</div>
			<div class="field tar" style="top:217mm;right:38mm;width:90px">'.number_format($_REQUEST["price_sub"],2).'</div>
			<div class="field tar" style="top:217mm;right:12.8mm;width:90px">'.number_format($_REQUEST["price_wht"],2).'</div>

			<div class="field tar" style="top:229.8mm;right:38mm;width:90px">'.number_format($_REQUEST["price_sub"],2).'</div>
			<div class="field tar" style="top:229.8mm;right:12.8mm;width:90px">'.number_format($_REQUEST["price_wht"],2).'</div>

			<div class="field" style="top:236.8mm;left:68mm">'.$_REQUEST["text_wht"].'</div>

			<div class="field" style="top:268mm;left:122mm;width:30px">'.date('d').'</div>
			<div class="field" style="top:268mm;left:132mm;width:80px">'.$months[date('n')].'</div>
			<div class="field" style="top:268mm;left:155mm;width:30px">'.(date('Y')+543).'</div>

			</body>
			</html>';
			
	//echo $style.$html; exit;			
	
	require_once(DIR."mpdf7/vendor/autoload.php");
	$mpdf=new mPDF('utf-8', 'A4-P', 10, '', 8, 8, 10, 10, 8, 8);
	$filename = $rego.' - WHT certificate.pdf';
	$mpdf->SetTitle($filename);
	$mpdf->SetDisplayMode('fullpage');
	$mpdf->SetFontSize(12);
	$mpdf->WriteHTML($style,1);
	$mpdf->WriteHTML($html);
	
	//ob_end_clean();
	$mpdf->Output($filename,'I');
	exit;










