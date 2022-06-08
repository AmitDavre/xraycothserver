<?php

	$template = array();
	$res = $dba->query("SELECT * FROM rego_documents WHERE id = 1");
	if($row = $res->fetch_assoc()){
		$template = $row;
	}	
	//var_dump($template);

	$data = array();
	$res = $dba->query("SELECT * FROM rego_invoices WHERE id = ".$_REQUEST['nr']);
	if($row = $res->fetch_assoc()){
		$data = $row;
		$body = unserialize($row['body']);	
		$data['text_total'] = getWordsFromAmount($row['total'], $lang);
		$data['text_net'] = getWordsFromAmount($row['net_amount'], $lang);
	}
	
	//var_dump($paid_by); //exit;
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
		<b style="font-size:20px">'.$template[$lang.'_rec_type'].'</b>
	</div>
	
	<div style="float:right; width:48%; height:110px">
		<b>'.$data['customer'].'</b><br>
		'.nl2br($data['address']).'
			
	</div>
	
	<div style="width:100%; height:35px; font-size:20px; font-weight:bold; clear:both">
		'.$lng['Receipt'].' # : '.$data['rec_id'].'	
	</div>
	
	<div style="width:100%; padding:10px 0 5px 0">
		<table border="0">
			<tr>
				<td style="padding:0 20px 0 0"><b>'.$lng['Receipt date'].' :</b></td>
				<td style="padding:0 20px 0 0"><b>'.$lng['Client ID'].' :</b></td>
				<td style="padding:0 20px 0 0"><b>'.$lng['Reference'].' :</b></td>
			</tr>
			<tr>
				<td style="padding-right:20px">'.$data['rec_date'].'</td>
				<td style="padding-right:20px">'.$data['clientID'].'</td>
				<td style="padding-right:20px">'.$data['rec_ref'].'</td>
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
							<td class="tar">'.$v['quantity'].'</td>
							<td class="tar">'.$v['unit'].'</td>
							<td class="tar">'.$v['per'].'</td>
							<td class="tar">'.$v['amount'].'</td>';
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
					<td colspan="3" rowspan="4" style="vertical-align:bottom; padding-bottom:5px; line-height:160%">';
						if($data['wht_percent'] > 0){
							$html .= '
						<p>'.$lng['Deduct WHT'].' &nbsp;'.$data['wht_percent'].'% &nbsp;'.$lng['on'].'&nbsp; '.number_format($data['total'],2).' &nbsp;'.$lng['is'].'&nbsp; '.number_format($data['wht_amount'],2).' '.$lng['Baht'].'</p>
						<p style="font-size:16px"><b>'.$lng['Net to Pay'].' : &nbsp;'.number_format($data['net_amount'],2).' '.$lng['Baht'].'</b></p>
						<p>'.$data['text_net'].'</p>';
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
						'.$data['text_total'].'
					</td>
				</tr>
				</tfoot>
			</table>
		</div>';	
		 
		if(!empty($data['rec_note'])){
			$html .= '
			<div style="text-align:left; padding:10px 0 0 0">
				<b>Note : </b>'.$data['rec_note'].'
			</div>';
		}
		
		$html .= '
		<div style="padding:5px; margin-top:15px; border:1px solid #ccc">
		<table border="0" style="width:100%; table-layout:fixed; border-collapse:collapse">
			<tr>
				<td style="vertical-align:top; white-space:nowrap">
					<b>'.$lng['Payment type'].' : </b>&nbsp;'.$paid_by[$data['payment_type']].' &nbsp;<b>'.$lng['on date'].' :</b> &nbsp;'.$data['pay_date'].'<br><br>
					<b>'.$lng['Date'].' : </b>'.$data['rec_date'].'<br>
					<b>'.$lng['Name'].' : </b>'.$data['rec_user'].'
				</td>
				<td style="vertical-align:top">
					<br><br><b>'.$lng['Athorized signature'].' : </b><br>
					<img height="35px" src="'.$template['signature'].'?'.time().'" />
				</td>
				<td style="padding:5px 20px"><center><img height="110px" src="'.$template['stamp'].'?'.time().'" /></center></td>
			</tr>
		</table>
		</div>
		
		<div class="footer" style="position:absolute; bottom:30px; left:30px; right:30px">
			<div style="text-align:center; padding-top:0px; border-top:1px solid #ccc; font-size:13px">
				'.$template[$lang.'_footer'].' 
			</div>
		</div>
	
	</body></html>';
	
	//echo $style.$html; exit;			
	
	$filename = $data['rec_id'].'.pdf';
	$dir = DIR.'admin/uploads/receipts/'.$filename;
	$root = AROOT.'uploads/receipts/'.$filename;
	
	require_once(DIR."mpdf7/vendor/autoload.php");
	$mpdf=new mPDF('utf-8', 'A4-P', 10, '', 10, 10, 10, 10, 8, 8);
	$mpdf->SetTitle($filename);
	$mpdf->SetDisplayMode('fullpage');
	$mpdf->WriteHTML($style,1);
	$mpdf->WriteHTML($html);
	









