<?
	if(session_id()==''){session_start(); }
	ob_start();

	include('../../dbconnect/db_connect.php');
	include(DIR.'files/functions.php');

	$style = '<style>
			@page {
				margin: 5px 10px 5px 10px;
			}
			body, html, table, div {
				font-family: calibri, sans-serif;
				font-size:12px;
				color:#333;
			}
			div.customer_address {
				position:absolute;
				top:50mm;
				left:115mm;
				padding:15px;
				border:1px solid #eee;
				border-radius:5px;
				right:10mm;
			}
			table.itemsTable {
				width:100%; 
				table-layout:auto;
				border-collapse:collapse;
			}
			table.itemsTable thead th {
				padding:4px 10px;
				background:#eee;
				text-align:right;
				width:12%;
				white-space:nowrap;
			}
			table.itemsTable tbody tr {
				
			}
			table.itemsTable tbody td {
				padding:4px 10px;
				font-weight:400;
				text-align:right;
				white-space:nowrap;
				border-bottom:1px solid #eee;
			}
			table.itemsTable tbody th {
				padding:4px 10px;
				font-weight:600;
				text-align:left;
				white-space:nowrap;
			}
			table.itemsTable thead th.pl0, 
			table.itemsTable tbody td.pl0 {
				padding-left:0;
				width:1%;
				text-align:left;
			}
			table {
				table-layout:auto;
				border-collapse:collapse;
			}
		</style>';

	$data = array();
	$sql = "SELECT * FROM ".$cid."_comm_centers WHERE id = '".$_GET['id']."'";
	if($res = $dbc->query($sql)){
		if($row = $res->fetch_assoc()){
			$data = $row;
			$settings = unserialize($data['settings']);
			
		}
	}

	$areas = unserialize($data['areas']);

	//------------- Header ------------//
	$templateH = array();
	$sqlhh = "SELECT * FROM ".$cid."_document_templates WHERE id = '".$data['headerval']."'";
	if($res = $dbc->query($sqlhh)){
		if($row = $res->fetch_assoc()){
			$templateH = $row;
		}
	}

	$header = '';
	if($templateH['logo'] !=''){
	$header .= '
		<table border="0" style="width:100%; table-layout:auto; border:1px #fff solid; border-bottom:1px solid #ddd;">
			<tr>
				<td style="vertical-align:top; max-height:100px">
					<img style="display:block; max-height:60px; max-width:400px" src="../'.$templateH['logo'].'" />
				</td>
				<td style="vertical-align:top; text-align:right; padding-bottom:8px">
					<span style="font-size:16px; font-weight:600">'.$templateH['company'].'</span><br>
					'.nl2br($templateH['address']).'
				</td>
			<tr>
		</table>';
	}
	//------------- Header ------------//

	//------------- Footer ------------//
	

	$template = array();
	$sqlff = "SELECT * FROM ".$cid."_footer_templates WHERE id = '".$data['footerval']."'";
	if($res = $dbc->query($sqlff)){
		if($row = $res->fetch_assoc()){
			$template = $row;
		}
	}

	$phone = $template['phone'];
	$fax = $template['fax'];
	$email = $template['email'];
	$website = $template['website'];
	$vat = $template['vat'];
	$footer1 = '';
	
	if($phone != ''){
		$footer1 .= '<b>'.$lng['Phone'].' : </b>'.$phone.' ';
	}
	
	if($fax != ''){
		if($footer1 != ''){ 
			$footer1 .= '<b> &nbsp;&bull;&nbsp; </b>';
		}; 
		$footer1 .= '<b>'.$lng['Fax'].' : </b>'.$fax.' ';
	}
	
	if($email != ''){
		if($footer1 != ''){ 
			$footer1 .= '<b> &nbsp;&bull;&nbsp; </b>';
		}; 
		$footer1 .= '<b>'.$lng['email'].' : </b>'.$email.' ';
	}
	
	if($website != ''){
		if($footer1 != ''){ 
			$footer1 .= '<b> &nbsp;&bull;&nbsp; </b>';
		}; 
		$footer1 .= '<b>'.$lng['Website'].' : </b>'.$website.' ';
	}
	
	if($vat != ''){
		if($footer1 != ''){ 
			$footer1 .= '<b> &nbsp;&bull;&nbsp; </b>';
		}; 
		$footer1 .= '<b>'.$lng['VAT'].' : </b>'.$vat.' ';
	}
	
	$bank1 = $template['bank1'];
	$acc1 = $template['acc1'];
	$bic1 = $template['bic1'];
	$footer2 = '';
	
	if($bank1 != ''){
		$footer2 .= '<b>'.$bank1.' : </b>'.$acc1;
	}
	if($bic1 != ''){
		$footer2 .= '<b> - '.$lng['Swift/Bic'].' : </b>'.$bic1.' ';
	}
	
	$bank2 = $template['bank2'];
	$acc2 = $template['acc2'];
	$bic2 = $template['bic2'];

	if($bank2 != ''){
		if($footer2 != ''){ 
			$footer2 .= '<b> &nbsp;&bull;&nbsp; </b>';
		}
		$footer2 .= '<b>'.$bank2.' : </b>'.$acc2;
	}
	if($bic2 != ''){
		$footer2 .= '<b> - '.$lng['Swift/Bic'].' : </b>'.$bic2;
	}
	
	$footer = '
		<table border="0" style="width:100%; table-layout:auto;">
			<tr>
				<td style="padding-top:8px; border-top:1px solid #ccc; text-align:center; font-size:11px;">'
					.$footer1.'<br>'.$footer2.'
				</td>
			</tr>
		</table>
		<div style="text-align:right; font-size:11px">Page {PAGENO} / {nbpg} </div>';

	//------------- Footer ------------//

	//------------- Section ------------//
	$html = '';
	if($areas){
		$html .= '<div style="line-height:140%">';
		foreach($areas as $text){
			$html .= $text;
		}
		$html .= '</div>';
	}
	//------------- Section ------------//

	//------------- attachments ------------//
	if(isset($data)){
		$attached = explode(',', $data['attachments']);

		$html .= '<div style="line-height:140%">';

		//if(isset($attached) && is_array($attached)){
			
			foreach($attached as $k=>$v){ if($v != ''){ $attcont++;

				if($attcont == 1){ $html .= '<h3>Other attachments list:</h3>'; }
				$html .= '<a style="font-size:14px;margin_bottom:10px;" href="'.$v.'" target="_blank">'.$lng['Attachement'].' - '.$attcont.'</a><br>';
			} } 
		//}
		$html .= '</div>';
	}
	//------------- attachments ------------//

	//echo $header.$html.$footer; exit;
	

	include(DIR."mpdf7/vendor/autoload.php");
	//class mPDF ($mode, $format , $default_font_size , $default_font , $margin_left , $margin_right , $margin_top , $margin_bottom , $margin_header , $margin_footer , string $orientation ]]]]]])
	$mpdf = new mPDF('utf-8', 'A4-P', 11, 'verdana', 10, 10, 33, 30, 8, 6);
	$mpdf->SetHTMLHeader($header);
	$mpdf->SetHTMLFooter($footer);
	$mpdf->SetTitle('Communication Center - '.$data['anno_id']);
	$mpdf->SetDisplayMode('fullpage');
	$mpdf->WriteHTML($style,1);
	$mpdf->WriteHTML($html);
	$mpdf->Output('Communication-center_'.$data['anno_id'].'.pdf','I');

	//save in dir....
	$dir = '../../'.$cid.'/commcenter/archive/';
	if (!file_exists($dir)) {
		mkdir($dir, 0755, true);
	}

	$baseName = $_SESSION['rego']['cid'].'_communication-center_'.$data['anno_id'];
	$extension = 'pdf';		
	$filename = getFilename($baseName, $extension, $dir);
	if(file_exists($dir.$baseName.'.pdf')) {
		unlink($dir.$baseName.'.pdf');
	}

	$filePath = $dir.$filename;
	$mpdf->Output($filePath,'F');

	$dbc->query("UPDATE ".$cid."_comm_centers SET pdflink = '".$filename."' WHERE id='".$_GET['id']."' ");
	
	exit;