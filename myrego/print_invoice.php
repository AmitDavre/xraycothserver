<?php
	
	if(session_id()==''){session_start();}
	ob_start();
	include('../dbconnect/db_connect.php');
	include(DIR.'files/functions.php');
	include(DIR.'files/arrays_'.$lang.'.php');

	$template = array();
	$res = $dbx->query("SELECT * FROM rego_documents WHERE id = 1");
	if($row = $res->fetch_assoc()){
		$template = $row;
	}	
	//var_dump($template);

	$data = array();
	$res = $dbx->query("SELECT * FROM rego_invoices WHERE inv = '".$_REQUEST['inv']."'");
	if($row = $res->fetch_assoc()){
		$data = $row;
		$data['text_total'] = getWordsFromAmount($row['total'], $lang);
		$data['text_net'] = getWordsFromAmount($row['net_amount'], $lang);
		$data['text_wht'] = getWordsFromAmount($row['wht_amount'], $lang);
		$inv_body = unserialize($row['body']);
	}
	//$body = unserialize($data['body']);	
	//var_dump($compinfo); exit;

$style = '	
	<style>
		@page {
			margin: 10px 10px 10px 10px;
		}
		body, html, table {
			font-family: "leelawadee";
		}
		.invoice .invBody {
			border-collapse:collapse;
		}
		.invoice .invBody td.tar, 
		.invoice	.invBody th.tar {
			text-align:right;
		}
		.invoice .invBody td.tal, 
		.invoice .invBody th.tal {
			text-align:left;
		}
		.invoice .invBody td.tac, 
		.invoice .invBody th.tac {
			text-align:center;
		}
		.invoice .invBody thead th {
			border:1px solid #ccc;
			background:#eee;
			padding:4px 8px;
		}
		.invoice .invBody tbody td {
			border:1px solid #ccc;
			padding:3px 8px;
			vertical-align:top;
		}
		.invoice .invBody tfoot td, 
		.invoice .invBody tfoot th {
			border:1px solid #ccc;
			border-bottom:2px solid #bbb;
			padding:3px 8px;
		}
		.receiptTable {
			white-space:nowrap;
			width:100%;
			border:1px solid #ddd; 
			border-collapse:collapse
		}
		.receiptTable tbody th {
			padding:3px 8px;
			white-space:nowrap;
		}
		.receiptTable tbody td {
			padding:4px 8px;
			white-space:nowrap;
			border-bottom:1px solid #ddd;
		}
		.receiptTable .sub, 
		.receiptTable .sub tbody td, 
		.receiptTable .sub tbody tr {
			border-color:#fff !important;
		}
	</style>';


	$html = '<html><body>
	
	<div style="float:left; width:49%">
		<img style="height:80px; margin-bottom:10px" src="'.$template['logo'].'" />
	</div>
	
	<div style="float:right; width:49%; text-align:right">
		<b style="font-size:16px">'.$template[$lang.'_company'].'</b><br>
		'.nl2br($template[$lang.'_address']).'
	</div>
	
	<div style="border-top:1px solid #ccc; width:100%; height:35px; clear:both"></div>
	
	<div style="float:left; width:40%">
		<b style="font-size:20px">'.$lng['Invoice'].'</b>
	</div>
	
	<div style="float:right; width:48%; height:110px">
		<b style="font-size:15px">'.$compinfo[$lang.'_compname'].'</b><br>
		'.nl2br($compinfo[$lang.'_address']).'
			
	</div>
	
	<div style="width:100%; height:35px; font-size:20px; font-weight:bold; clear:both">
		'.$lng['Invoice'].' # : '.$data['inv'].'	
	</div>
	
	<div style="width:100%; padding:10px 0 5px 0">
		<table border="0">
			<tr>
				<td style="padding:0 30px 0 0"><b>'.$lng['Invoice date'].' :</b></td>
				<td style="padding:0 30px 0 0"><b>'.$lng['Due date'].' :</b></td>
				<td style="padding:0 30px 0 0"><b>'.$lng['Client ID'].' :</b></td>
			</tr>
			<tr>
				<td>'.$data['inv_date'].'</td>
				<td>'.$data['inv_due'].'</td>
				<td>'.$data['clientID'].'</td>
			</tr>
		</table>
	</div>
	
	<div class="invoice" style="padding-top:5px">
		<table class="invBody" width="100%" border="0">
			<thead>
			<tr>
				<th class="tac" style="width:30px">#</th>
				<th class="tal">'.$lng['Description'].'</th>
				<th class="tar" style="width:60px">'.$lng['Quantity'].'</th>
				<th class="tar" style="width:90px">'.$lng['Unit price'].'</th>
				<th class="tar" style="width:60px">'.$lng['VAT'].' %</th>
				<th class="tar" style="width:90px">'.$lng['Amount'].'</th>
			</tr>
			</thead>
			<tbody>';
			foreach($inv_body as $k=>$v){
				$html .= '
				<tr>
					<td class="tac">'.$k.'</td>
					<td>'.$v['description'].'</td>
					<td class="tac">'.$v['quantity'].'</td>
					<td class="tar">'.$v['unit'].'</td>
					<td class="tac">'.$v['vat'].'</td>
					<td class="tar">'.$v['amount'].'</td>
				</tr>';
			}
			for($i=$k+1;$i<=9;$i++){	
				$html .= '
				<tr>
					<td class="tac">'.$i.'</td><td>&nbsp;</td><td></td><td></td><td></td><td></td>
				</tr>';
			}
				
	$html .= '
			</tbody>
			<tfoot>
				<tr>
					<td colspan="3" rowspan="4" style="vertical-align:bottom; padding-bottom:8px">
						<p>'.$lng['Deduct WHT'].' &nbsp;'.$data['wht_percent'].'% &nbsp;'.$lng['on'].'&nbsp; '.number_format($data['subtotal'],2).' &nbsp;'.$lng['is'].'&nbsp; '.number_format($data['wht_amount'],2).' '.$lng['Baht'].'<br>
						<b style="font-size:16px">'.$lng['Net to Pay'].' : &nbsp;'.number_format($data['net_amount'],2).' '.$lng['Baht'].'</b><br>
						'.getWordsFromAmount($data['net_amount'], $lang).'</p>
					</td>
					<th class="tal" colspan="2">'.$lng['Discount'].'</th>
					<th class="tar">'.number_format($data['discount'],2).'</th>
				</tr>
				<tr>
					<th class="tal" colspan="2">'.$lng['Sub total'].'</th>
					<th class="tar">'.number_format($data['subtotal'],2).'</th>
				</tr>
				<tr>
					<th class="tal" colspan="2">'.$lng['VAT'].'</th>
					<th class="tar">'.number_format($data['per'],2).'</th>
				</tr>
				<tr>
					<th class="tal" colspan="2">'.$lng['Grand total'].'</th>
					<th class="tar">'.number_format($data['total'],2).'</th>
				</tr>
				<tr>
					<td colspan="6" class="tar" style="border-bottom-width:1px; padding:5px 8px">
						'.$data['text_total'].'
					</td>
				</tr>
				</tfoot>
			</table>
		</div>	
		 
		<div style="height:20px"></div>
		
		<table class="receiptTable" border="0">
			<tbody>
			<tr style="background:#eee; border-bottom:1px solid #ddd">
				<td colspan="4" style="padding:5px 10px"><b>'.$lng['Payment details'].'</b></td>
			</tr>
			<tr style="border-bottom:1px solid #ddd">
				<td style="vertical-align:baseline; text-align:right; width:1px"><b>'.$lng['To'].' : </b></td>
				<td style="vertical-align:baseline; padding-left:0; border-right:1px solid #ddd">
					'.nl2br($template[$lang.'_pay_to']).'
				</td>
				<td style="width:1px; vertical-align:baseline; text-align:right">
					<b>'.$lng['Customer'].'&nbsp;: </b><br>
					<b>'.$lng['Invoice'].' #&nbsp;: </b><br>
					<b>'.$lng['Amount due'].'&nbsp;: </b><br>
					<b>'.$lng['Due date'].'&nbsp;: </b>
				</td>
				<td style="vertical-align:baseline; padding-left:0">
					<b>'.$rego.'</b><br>
					'.$data['inv'].'<br>
					'.number_format($data['net_amount'],2).'<br>
					'.$data['inv_due'].'<br>
				
				</td>
			</tr>
			<tr>
				<td style="vertical-align:top; text-align:right"><b>'.$lng['Bank'].' : </b></td>
				<td style="vertical-align:baseline; padding-left:0; border-right:1px solid #ddd">
					'.nl2br($template[$lang.'_bank_details']).'
				</td>
				<td colspan="2" style="vertical-align:baseline; padding:0">
					<table class="receiptTable sub" border="0" style="border-collapse:collapse; width:100%">
						<tbody>
						  <tr>
							 <td valign="top" style="height:1px; padding:5px 10px"><b>'.$lng['Athorized signature'].' :</b></td>
							 <td rowspan="2"><img style="height:100px; max-width:200px" src="'.$template['stamp'].'?'.time().'" /></td>
						  </tr>
						  <tr>
							 <td valign="top"><img style="width:140px; max-height:80px" src="'.$template['signature'].'?'.time().'" /></td>
						  </tr>
						</tbody>
					</table>	
				</td>
			</tr>
			</tbody>
		</table>';
	
	$html .= '
		<div class="footer" style="position:absolute; bottom:30px; left:30px; right:30px">
			<div style="text-align:center; padding-top:0px; border-top:1px solid #ccc; font-size:13px">
				'.$template[$lang.'_footer'].' 
			</div>
		</div>
	
	</body></html>';
	//echo $style.$html; exit;
				
	ob_clean();
	$filename = 'invoice_'.$data['inv'].'.pdf';
	
	require_once(DIR."mpdf7/vendor/autoload.php");
	$mpdf=new mPDF('utf-8', 'A4-P', 10, '', 10, 10, 10, 10, 8, 8);
	$mpdf->SetTitle($filename);
	$mpdf->SetDisplayMode('fullpage');
	$mpdf->WriteHTML($style,1);
	$mpdf->WriteHTML($html);
	
	//$mpdf->Output($dir,'F');
	//$dba->query("UPDATE rego_invoices SET pdf_invoice = '".$dba->real_escape_string($root)."' WHERE id = '".$_REQUEST['nr']."'");
	$mpdf->Output($filename,'I');
	
	
	$path = 'admin/billing/documents/';
	if(!file_exists(DIR.$path)){
		mkdir(DIR.$path, 0777, true);
	}
	$mpdf->Output(DIR.$path.$filename,'F');

	//$filename = ROOT.$path.$filename.'.pdf';
	$sql = "UPDATE rego_invoices SET pdf_invoice = '".ROOT.$path.$filename."' WHERE inv = '".$data['inv']."'";
	//echo $sql; //exit;
	if($dbx->query($sql)){
		echo 'success';
	}else{
		echo mysqli_error($dbx);
	}
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
?>
