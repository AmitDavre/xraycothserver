<?php
	
	$template = array();
	$res = $dba->query("SELECT * FROM rego_documents WHERE id = 1");
	if($row = $res->fetch_assoc()){
		$template = $row;
	}	
	//var_dump($template);

	$data = array();
	$res = $dba->query("SELECT * FROM rego_invoices WHERE id = ".$_REQUEST['nr']);
	while($row = $res->fetch_assoc()){
		$data = $row;
	}
	$body = unserialize($data['body']);	
	$data['wht_letters'] = getWordsFromAmount($data['net_amount'], $lang);
	$data['letters'] = getWordsFromAmount($data['total'], $lang);
	//var_dump($body); exit;

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
		<b style="font-size:20px">'.$template[$lang.'_inv_type'].'</b>
	</div>
	
	<div style="float:right; width:48%; height:110px">
		<b>'.$data['customer'].'</b><br>
		'.nl2br($data['address']).'
			
	</div>
	
	<div style="width:100%; height:35px; font-size:20px; font-weight:bold; clear:both">
		Invoice # : '.$data['inv'].'	
	</div>
	
	<div style="width:100%; padding:10px 0 5px 0">
		<table border="0">
			<tr>
				<td style="padding:0 20px 0 0"><b>'.$lng['Invoice date'].' :</b></td>
				<td style="padding:0 20px 0 0"><b>'.$lng['Due date'].' :</b></td>
				<td style="padding:0 20px 0 0"><b>'.$lng['Client ID'].' :</b></td>
			</tr>
			<tr>
				<td style="padding-right:20px">'.$data['inv_date'].'</td>
				<td style="padding-right:20px">'.$data['inv_due'].'</td>
				<td style="padding-right:20px">'.$data['clientID'].'</td>
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
				<th class="tar" style="width:60px">'.$lng['VAT'].'&nbsp;%</th>
				<th class="tar" style="width:90px">'.$lng['Amount'].'</th>
			</tr>
			</thead>
			<tbody>';
			foreach($body as $k=>$v){
				if(!empty(trim($v['description']))){
					$nr = $k;
					$html .= '
					<tr>
						<td class="tac">'.$k.'</td>
						<td>'.$v['description'].'</td>';
						if($v['quantity'] != ''){
							$html .= '
							<td class="tar">'.number_format($v['quantity'],2).'</td>
							<td class="tar">'.number_format($v['unit'],2).'</td>
							<td class="tar">'.number_format($v['per'],2).'</td>
							<td class="tar">'.number_format($v['amount'],2).'</td>';
						}else{
							$html .= '
							<td class="tar"></td>
							<td class="tar"></td>
							<td class="tar"></td>
							<td class="tar"></td>';
						}
					$html .= '
					</tr>';
				}
			}
			for($i=$nr+1;$i<=10;$i++){
				$html .= '
				<tr>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
				</tr>';
			}
			$html .= '
				</tbody>
				<tfoot>
				<tr>
					<td colspan="3" rowspan="4" style="vertical-align:bottom; padding-bottom:8px">';
						if($data['wht_percent'] > 0){
							$html .= '
							<p>'.$lng['Deduct WHT'].' &nbsp;'.$data['wht_percent'].'% &nbsp;'.$lng['on'].'&nbsp; '.number_format($data['subtotal'],2).' &nbsp;'.$lng['is'].'&nbsp; '.number_format($data['wht_amount'],2).' '.$lng['Baht'].'</p><br>
							<p style="font-size:16px"><b>'.$lng['Net to Pay'].' : &nbsp;'.number_format($data['net_amount'],2).' '.$lng['Baht'].'</b></p>
							<p>'.$data['wht_letters'].'</p>';
						}
					$html .= '
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
					<th class="tar">'.number_format($data['vat'],2).'</th>
				</tr>
				<tr>
					<th class="tal" colspan="2">'.$lng['Grand total'].'</th>
					<th class="tar">'.number_format($data['total'],2).'</th>
				</tr>
				<tr>
					<td colspan="6" class="tar" style="border-bottom-width:1px; padding:8px">
						'.$data['letters'].'
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
				<td style="vertical-align:baseline; text-align:right; width:1px"><b>'.$lng['Pay to'].' : </b></td>
				<td style="vertical-align:baseline; padding-left:0; border-right:1px solid #ddd">
					'.nl2br($template[$lang.'_pay_to']).'</td>
				<td style="width:1px; vertical-align:baseline; text-align:right">
					<b>'.$lng['Customer'].'&nbsp;: </b><br>
					<b>'.$lng['Invoice'].' #&nbsp;: </b><br>
					<b>'.$lng['Amount due'].'&nbsp;: </b><br>
					<b>'.$lng['Due date'].'&nbsp;: </b>
				</td>
				<td style="vertical-align:baseline; padding-left:0">
					<b>'.$data['customer'].'</b><br>
					'.$data['inv'].'<br>
					'.number_format($data['total'],2).'<br>
					'.$data['inv_due'].'<br>
				
				</td>
			</tr>
			<tr>
				<td style="vertical-align:top; text-align:right"><b>'.$lng['Bank'].' : </b></td>
				<td style="vertical-align:baseline; padding-left:0; border-right:1px solid #ddd">
					'.nl2br($template[$lang.'_bank_details']).'
				</td>
				<td colspan="2" style="vertical-align:baseline">
					<b>'.$lng['Athorized signature'].' :</b>
					<div><img style="width:160px; max-height:80px" src="'.$template['signature'].'?'.time().'" /></div>
					<div style="position:absolute; bottom:110px; right:60px"><img style="height:100px; max-width:180px" src="'.$template['stamp'].'?'.time().'" /></div>
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
	
	$filename = $data['inv'].'.pdf';
	$dir = DIR.'admin/uploads/invoices/'.$filename;
	$root = AROOT.'uploads/invoices/'.$filename;
	
	require_once(DIR."mpdf7/vendor/autoload.php");
	$mpdf=new mPDF('utf-8', 'A4-P', 10, '', 10, 10, 10, 10, 8, 8);
	$mpdf->SetTitle($filename);
	$mpdf->SetDisplayMode('fullpage');
	$mpdf->WriteHTML($style,1);
	$mpdf->WriteHTML($html);
?>
