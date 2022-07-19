<?
	
	//var_dump($compinfo);
	$today = strtotime(date('d-m-Y'));
	$today1m = strtotime(date('d-m-Y', strtotime("+35 days")));
	//var_dump($today);
	//var_dump($today1m);
	// var 
	$data['trial'] = array();
	$data['expire'] = array();
	//$periods = array();
	$res = $dba->query("SELECT * FROM rego_customers WHERE status > 0 ORDER BY clientID ASC");
	while($row = $res->fetch_assoc()){
		if($row['version'] == 0){
			$data['trial'][$row['clientID']]['compname'] = $row[$lang.'_compname'];
			if(empty($row['th_compname'])){
				$data['trial'][$row['clientID']]['compname'] = $row['en_compname'];
			}
			if(empty($row['en_compname'])){
				$data['trial'][$row['clientID']]['compname'] = $row['th_compname'];
			}
			$data['trial'][$row['clientID']]['vers'] = $row['version'];
			$data['trial'][$row['clientID']]['joiningdate'] = $row['joiningdate'];
			$data['trial'][$row['clientID']]['period_start'] = $row['period_start'];
			$data['trial'][$row['clientID']]['period_end'] = $row['period_end'];
			$data['trial'][$row['clientID']]['name'] = $row['name'];
			$data['trial'][$row['clientID']]['phone'] = $row['phone'];
			$data['trial'][$row['clientID']]['email'] = $row['email'];
			$data['trial'][$row['clientID']]['class'] = '';
			$data['trial'][$row['clientID']]['fa'] = '<i class="fa fa-file-text-o fa-lg"></i>';
			if(strtotime($row['period_end']) <= $today){
				$data['trial'][$row['clientID']]['class'] = 'expired';
				$data['trial'][$row['clientID']]['fa'] = '<i class="fa fa-file-text fa-lg"></i>';
			}
			//$period[$row['clientID']]['start'] = $row['period_end'];
			//$period[$row['clientID']]['end'] = date('d-m-Y', strtotime('+12 months', strtotime($row['period_end'])));
		}else{
			if(strtotime($row['period_end']) <= $today1m){
				$data['expire'][$row['clientID']]['compname'] = $row[$lang.'_compname'];
				if(empty($row['th_compname'])){
					$data['expire'][$row['clientID']]['compname'] = $row['en_compname'];
				}
				if(empty($row['en_compname'])){
					$data['expire'][$row['clientID']]['compname'] = $row['th_compname'];
				}
				$data['expire'][$row['clientID']]['vers'] = $row['version'];
				$data['expire'][$row['clientID']]['version'] = $version[$row['version']];
				$data['expire'][$row['clientID']]['joiningdate'] = $row['joiningdate'];
				$data['expire'][$row['clientID']]['period_start'] = $row['period_start'];
				$data['expire'][$row['clientID']]['period_end'] = $row['period_end'];
				$data['expire'][$row['clientID']]['name'] = $row['name'];
				$data['expire'][$row['clientID']]['phone'] = $row['phone'];
				$data['expire'][$row['clientID']]['email'] = $row['email'];
				$data['expire'][$row['clientID']]['class'] = '';
				$data['expire'][$row['clientID']]['fa'] = '<i class="fa fa-file-text-o fa-lg"></i>';
				if(strtotime($row['period_end']) <= $today){
					$data['expire'][$row['clientID']]['class'] = 'expired';
					$data['expire'][$row['clientID']]['fa'] = '<i class="fa fa-file-text fa-lg"></i>';
				}
				//$period[$row['clientID']]['start'] = $row['period_end'];
				//$period[$row['clientID']]['end'] = date('d-m-Y', strtotime('+12 months', strtotime($row['period_end'])));
			}
		}
	}
	//var_dump($data);
	//var_dump($period);
	
	$invoices = array();
	$res = $dba->query("SELECT * FROM rego_invoices WHERE status = 0 ORDER BY id DESC");
	while($row = $res->fetch_assoc()){
		$invoices[$row['id']]['invoice'] = $row['inv'];
		$invoices[$row['id']]['clientID'] = strtoupper(substr($row['clientID'],0,4).' '.substr($row['clientID'],4));
		$invoices[$row['id']]['customer'] = $row['customer'];
		$invoices[$row['id']]['subscription'] = $row['subscription'];
		$invoices[$row['id']]['inv_date'] = $row['inv_date'];
		$invoices[$row['id']]['inv_due'] = $row['inv_due'];
		$invoices[$row['id']]['total'] = number_format($row['total'],2);
		
		//$invoices[$row['id']]['period'] = $period[strtolower($row['clientID'])]['start'].' / '.$period[strtolower($row['clientID'])]['end'];
		//$invoices[$row['id']]['period_end'] = $period[strtolower($row['clientID'])]['end'];
		
		if(empty($row['rec_id'])){
			$invoices[$row['id']]['rec_id'] = '<a href="index.php?mn=23&nid='.$row['id'].'"><i class="fa fa-file-text-o fa-lg"></i></a>';
			$invoices[$row['id']]['rec_edit'] = '<i style="color:#ccc" class="fa fa-pencil-square-o fa-lg"></i>';
		}else{
			$invoices[$row['id']]['rec_id'] = $row['rec_id'];
			$invoices[$row['id']]['rec_edit'] = '<a href="index.php?mn=23&vid='.$row['id'].'"><i class="fa fa-pencil-square-o fa-lg"></i></a>';
		}		
		$invoices[$row['id']]['rec_date'] = $row['rec_date'];
		
		if(empty($row['pdf_invoice'])){
			$invoices[$row['id']]['pdf_invoice'] = '<a style="cursor:default !important"><i style="color:#ccc" class="fa fa-download fa-lg"></i></a>';
		}else{
			$invoices[$row['id']]['pdf_invoice'] = '<a href="'.$row['pdf_invoice'].'" target="_blank"><i class="fa fa-download fa-lg"></i></a>';
		}
		if(empty($row['pdf_receipt'])){
			$invoices[$row['id']]['pdf_receipt'] = '<a style="cursor:default !important"><i style="color:#ccc" class="fa fa-download fa-lg"></i></a>';
		}else{
			$invoices[$row['id']]['pdf_receipt'] = '<a href="'.$row['pdf_receipt'].'" target="_blank"><i class="fa fa-download fa-lg"></i></a>';
		}
		if(empty($row['pdf_certificate'])){
			$invoices[$row['id']]['pdf_certificate'] = '<a style="cursor:default !important"><i style="color:#ccc" class="fa fa-download fa-lg"></i></a>';
		}else{
			$invoices[$row['id']]['pdf_certificate'] = '<a href="'.$row['pdf_certificate'].'" target="_blank"><i class="fa fa-download fa-lg"></i></a>';
		}
		//var_dump($row);
	}
	//var_dump($invoices);
	
	
	
	
?>
<style>
table.mytable tbody td {
	text-align:left;
}
table.mytable thead th {
	text-align:left;
}
.clstatus {
	border:0 !important;
	background:transparent;
}
table.basicTable tr.expired, 
table.basicTable tr.expired td a {
	color:#b00;
	background:#fff9f9;
}
table.basicTable tr.expired > td {
	font-weight:600;
}
.extButton {
	width:100%;
	padding:0px;
	border-radius:2px !important;
}
</style>
	
	<h2><i class="fa fa-list-ul"></i>&nbsp; <?=$lng['Billing list']?><span id="cid"><? //=$acid?></span></h2>
	<div class="main">
		<div style="padding:0 0 0 20px" id="dump"></div>
			
		<ul class="nav nav-tabs" id="myTab">
			<li class="nav-item"><a class="nav-link" data-target="#tab_trial" data-toggle="tab">Free Trial customers<? //=$lng['Company']?></a></li>
			<li class="nav-item"><a class="nav-link" data-target="#tab_extension" data-toggle="tab">Customers eligible for extension<? //=$lng['Address']?></a></li>
			<li class="nav-item"><a class="nav-link" data-target="#tab_unfinished" data-toggle="tab">Unfinished Bills<? //=$lng['Positions']?></a></li>
			<li class="nav-item"><a class="nav-link" data-target="#tab_list" data-toggle="tab">Billing list<? //=$lng['Positions']?></a></li>
		</ul>
         
		<div class="tab-content" style="padding:15px 15px; height:calc(100% - 40px)">
			
			<div class="tab-pane" id="tab_trial">
				<table class="basicTable nowrap">
					<thead>
					<tr>
						<th><?=$lng['ID']?></th>
						<th><?=$lng['Customer']?></td>
						<th><?=$lng['Joining date']?></th>
						<th>Start period<? //=$lng['Joining date']?></th>
						<th>End period<? //=$lng['Expire date']?></th>
						<th><?=$lng['Contact']?></th>
						<th><?=$lng['Phone']?></th>
						<th><?=$lng['email']?></th>
						<th style="width:80%"></th>
						<th><i data-toggle="tooltip" data-placement="top" title="Create invoice<? //=$lng['Delete from payroll']?>" class="fa fa-file-text-o fa-lg"></i></th>
						<th><i class="fa fa-times-circle fa-lg"></i></th>
					</tr>
					</thead>
					<tbody>
					<? if($data['trial']){ foreach($data['trial'] as $k=>$v){
						echo '
						<tr class="'.$v['class'].'">
							<td>'.strtoupper(substr($k,0,4).' '.substr($k,4)).'</td>
							<td>'.$v['compname'].'</td>
							<td>'.$v['joiningdate'].'</td>
							<td>'.$v['period_start'].'</td>
							<td>'.$v['period_end'].'</td>
							<td>'.$v['name'].'</td>
							<td>'.$v['phone'].'</td>
							<td><a href="mailto:'.$v['email'].'">'.$v['email'].'</a></td>
							<td></td>
							<td><a href="index.php?mn=21&new='.$k.'&ver='.$v['vers'].'">'.$v['fa'].'</a></td>
							<td><a><i class="fa fa-times-circle fa-lg"></i></a></td>
						</tr>';
					}}else{
						echo '
						<tr>
							<td colspan="14">No data available</td>
						</tr>';
					}?>
				  </tbody>
				</table>
				
			</div>
			
			<div class="tab-pane" id="tab_extension">
				<table class="basicTable nowrap">
					<thead>
					<tr>
						<th><?=$lng['ID']?></th>
						<th><?=$lng['Customer']?></td>
						<th><?=$lng['Subscription']?></th>
						<th><?=$lng['Joining date']?></th>
						<th>Start period<? //=$lng['Joining date']?></th>
						<th>End period<? //=$lng['Expire date']?></th>
						<th><?=$lng['Contact']?></th>
						<th><?=$lng['Phone']?></th>
						<th><?=$lng['email']?></th>
						<th style="width:80%"></th>
						<th><i data-toggle="tooltip" data-placement="top" title="Create invoice<? //=$lng['Delete from payroll']?>" class="fa fa-file-text-o fa-lg"></i></th>
						<th><i class="fa fa-times-circle fa-lg"></i></th>
					</tr>
					</thead>
					<tbody>
					<? if($data['expire']){ foreach($data['expire'] as $k=>$v){
						echo '
						<tr class="'.$v['class'].'">
							<td>'.strtoupper(substr($k,0,4).' '.substr($k,4)).'</td>
							<td>'.$v['compname'].'</td>
							<td>'.$v['version'].'</td>
							<td>'.$v['joiningdate'].'</td>
							<td>'.$v['period_start'].'</td>
							<td>'.$v['period_end'].'</td>
							<td>'.$v['name'].'</td>
							<td>'.$v['phone'].'</td>
							<td><a href="mailto:'.$v['email'].'">'.$v['email'].'</a></td>
							<td></td>
							<td><a href="index.php?mn=21&new='.$k.'&ver='.$v['vers'].'">'.$v['fa'].'</a></td>
							<td><a><i class="fa fa-times-circle fa-lg"></i></a></td>
						</tr>';
					}}else{
						echo '
						<tr>
							<td colspan="14">No data available</td>
						</tr>';
					}?>
				  </tbody>
				</table>
			
			</div>
			
			<div class="tab-pane" id="tab_unfinished">
				<table class="basicTable nowrap">
					<thead>
					<tr>
						<th><?=$lng['Invoice']?> #</th>
						<th><?=$lng['ID']?></th>
						<th><?=$lng['Customer']?></td>
						<th><?=$lng['Subscription']?></td>
						<th><?=$lng['Date']?></th>
						<th><?=$lng['Due date']?></th>
						<th class="tar"><?=$lng['Amount']?></th>
						<th>Receipt ID</th>
						<th>Receipt date</th>
						<!--<th style="cursor:default" class="tac" data-toggle="tooltip" title="Extend period">Extend Period</th>-->
						<th style="width:50%"></th>
						<th colspan="2" style="cursor:default" class="tac" data-toggle="tooltip" title="Edit / Download">Invoice</th>
						<!--<th style="cursor:default" class="tac" data-toggle="tooltip" data-placement="left" title="Download invoice">Inv.</th>-->
						<th colspan="2" style="cursor:default" class="tac" data-toggle="tooltip" title="Edit / Download">Receipt</th>
						<!--<th style="cursor:default" class="tac" data-toggle="tooltip" data-placement="left" title="Download receipt">Rec.</th>-->
						<th style="cursor:default" data-toggle="tooltip" title="Download certificate">Cert.</th>
						<th style="cursor:default" data-toggle="tooltip" xdata-placement="left" title="Finish"><i class="fa fa-fast-forward"></i></th>
					</tr>
					</thead>
					<tbody>
					<? if($invoices){ foreach($invoices as $k=>$v){
						echo '
						<tr>
							<td style="font-weight:600">'.$v['invoice'].'</td>
							<td class="cid">'.$v['clientID'].'</td>
							<td>'.$v['customer'].'</td>
							<td>'.$version[$v['subscription']].'<input type="hidden" id="inv_version" value="'.$v['subscription'].'"></td>
							<td>'.$v['inv_date'].'</td>
							<td>'.$v['inv_due'].'</td>
							<td class="tar">'.$v['total'].'</td>
							<td style="font-weight:600">'.$v['rec_id'].'</td>
							<td>'.$v['rec_date'].'</td>
							<td></td>
							<td class="tac"><a href="index.php?mn=21&id='.$k.'"><i class="fa fa-pencil-square-o fa-lg"></i></a></td>
							<td class="tac">'.$v['pdf_invoice'].'</td>
							<td class="tac">'.$v['rec_edit'].'</td>
							<td class="tac">'.$v['pdf_receipt'].'</td>
							<td class="tac">'.$v['pdf_certificate'].'</td>
							<td class="tac"><a data-id="'.$k.'" class="finish_inv"><i class="fa fa-fast-forward"></i></a></td>
						</tr>';
						//<td style="padding:0 2px"><button style="border:0" class="extButton btn btn-default btn-xs">'.$v['period'].'</button></td>
					}}else{
						echo '
						<tr>
							<td colspan="15">No data available</td>
						</tr>';
					}?>
				  </tbody>
				</table>
			
			</div>
			
			<div class="tab-pane" id="tab_list">
				<div id="showTable" style="display:none">
					<table border="0" width="100%" style="margin-bottom:8px">
						<tr>
							<td>
								<div class="searchFilter" style="margin:0">
									<input style="margin:0" placeholder="<?=$lng['Filter']?>" class="sFilter" id="searchFilter" type="text" />
									<button id="clearSearchbox" type="button" class="clearFilter"><i class="fa fa-times"></i></button>
								</div>
							</td>
							<td style="padding:0 10px; width:95%"><div id="message" style="display:none"></div></td>
						</tr>
					</table>
			
					<table id="datatable" class="dataTables nowrap">
						<thead>
						<tr>
							<th class="par30"><?=$lng['Invoice']?> #</th>
							<th data-orderable="false"><?=$lng['Customer']?></td>
							<th data-orderable="false"><?=$lng['Date']?></th>
							<th data-orderable="false"><?=$lng['Due date']?></th>
							<th data-orderable="false"><?=$lng['Amount']?></th>
							<th data-orderable="false"><?=$lng['Inc. VAT']?></th>
							<th data-orderable="false"><?=$lng['Expire date']?></th>
							<th data-orderable="false">Receipt ID</th>
							<th data-orderable="false">Receipt date</th>
							<th data-orderable="false"></th>
							<!--<th class="tac" data-orderable="false">Inv.</th>-->
							<th class="tac" data-orderable="false">Inv.</th>
							<!--<th class="tac" data-orderable="false">Rec.</th>-->
							<th class="tac" data-orderable="false">Rec.</th>
							<th data-orderable="false">Cert.</th>
						</tr>
						</thead>
						<tbody>
	
					  </tbody>
					</table>
				</div>
			
			</div>
			
		</div>

	</div>

	<!-- Modal Edit Client -->
	<div class="modal fade" id="modalEditClient" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		 <div class="modal-dialog" style="width:800px">
			  <div class="modal-content">
					<div class="modal-header">
						<div type="button" class="close" data-dismiss="modal">
							<span aria-hidden="true"><i class="fa fa-times"></i></span>
							<span class="sr-only"><?=$lng['Close']?></span>                
						</div>
						 <h4 class="modal-title" id="myModalLabel"><i class="fa fa-edit"></i>&nbsp; Edit : <b><span id="clientID"></span></b></h4>
					</div>
					<div class="modal-body" style="padding:20px 25px 25px 25px">
					<div id="modal_msg"></div>
					
					<form id="clientForm">
					<input type="hidden" name="client_id" id="client_id" />
					<ul class="nav nav-tabs" id="myTab" style="position:relative">
						<li class="active"><a data-target="#tab_customer" data-toggle="tab">Customer info<? //=$lng['Client info']?></a></li>
						<li><a data-target="#tab_contact" data-toggle="tab"><?=$lng['Contact']?></a></li>
						<li><a data-target="#tab_billing" data-toggle="tab">Billing info<? //=$lng['Contact']?></a></li>
						<? if($_SESSION['RGadmin']['access']['customer']['edit'] == 1 || $_SESSION['admin']['access']['customer']['add'] == 1){ ?>
						<button class="btn btn-primary btn-sm" style="position:absolute; top:0; right:0;" id="submitbtn" type="submit"><i class="fa fa-save"></i>&nbsp;&nbsp;<?=$lng['Update']?></button>
						<? } ?>
					</ul>
					
					<div class="tab-content" style="padding:5px 10px; border:1px #ccc solid; border-top:0; min-height:350px;">
						
						<div class="tab-pane active" id="tab_customer">
							<table class="basicTable inputs" border="0">
								<tbody>
								<tr>
									<th><i class="man"></i><?=$lng['Company name in Thai']?></th>
									<td><input name="th_compname" id="th_compname" type="text" /></td>
								</tr>
								<tr>
									<th><i class="man"></i><?=$lng['Company name in English']?></th>
									<td><input name="en_compname" id="en_compname" type="text" /></td>
								</tr>
								<tr>
									<th valign="top"><i class="man"></i><?=$lng['Branch']?></th>
									<td><input name="branch" id="branch" type="text" /></td>
								</tr>
								<tr>
									<th><?=$lng['Company phone']?></th>
									<td><input name="comp_phone" type="text" /></td>
								</tr>
								<tr>
									<th><?=$lng['Company fax']?></th>
									<td><input name="comp_fax" type="text" /></td>
								</tr>
								<tr>
									<th><?=$lng['Company eMail']?></th>
									<td><input name="comp_email" type="text" /></td>
								</tr>
								<tr>
									<th valign="top"><?=$lng['Remarks']?></th>
									<td><textarea name="remarks" rows="5"></textarea></td>
								</tr>
								</tbody>
							</table>
						</div>
						
						<div class="tab-pane" id="tab_contact">
							<table class="basicTable inputs" border="0">
								<tbody>
								<tr>
									<th><i class="man"></i><?=$lng['First name']?> : </th>
									<td><input name="firstname" id="firstname" type="text" /></td>
								</tr>
								<tr>
									<th><i class="man"></i><?=$lng['Last name']?> : </th>
									<td><input name="lastname" id="lastname" type="text" /></td>
								</tr>
								<tr>
									<th><?=$lng['Position']?> : </th>
									<td><input name="position" type="text" /></td>
								</tr>
								<tr>
									<th><?=$lng['Phone']?> : </th>
									<td><input name="phone" type="text" /></td>
								</tr>
								<tr>
									<th><i class="man"></i><?=$lng['email']?> : </th>
									<td><input name="email" id="email" type="text" /></td>
								</tr>
								</tbody>
							</table>
						</div>
						
						<div class="tab-pane" id="tab_billing">
							<table class="basicTable inputs" border="0">
								<tbody>
								<tr>
									<th style="width:5%"><i class="man"></i><?=$lng['Joining date']?></th>
									<td><input readonly style="cursor:pointer" class="date_month" name="joiningdate" id="joiningdate" type="text" /></td>
								</tr>
								<tr>
									<th><i class="man"></i>Version<? //=$lng['Prefered language']?></th>
									<td>
										<select name="version" id="version" style="width:100%">
											<!--<option disabled selected value=""><?=$lng['Select']?></option>-->
											<? foreach($version as $k=>$v){ ?>
											<option value="<?=$k?>"><?=$v?></option>
											<? } ?>
										</select>
									</td>
								</tr>
								<tr>
									<th style="width:5%"><i class="man"></i>Employees<? //=$lng['Joining date']?></th>
									<td><input class="sel numeric" name="employees" id="employees" type="text" /></td>
								</tr>
								<tr>
									<th valign="top">Company <?=$lng['Address in Thai']?></th>
									<td><textarea name="th_address" id="th_address" rows="3"></textarea></td>
								</tr>
								<tr>
									<th valign="top">Company <?=$lng['Address in English']?></th>
									<td><textarea name="en_address" rows="3"></textarea></td>
								</tr>
								<tr>
									<th valign="top">Billing <?=$lng['Address in Thai']?></th>
									<td><textarea name="th_billing" id="th_address" rows="3"></textarea></td>
								</tr>
								<tr>
									<th valign="top">Billing <?=$lng['Address in English']?></th>
									<td><textarea name="en_billing" rows="3"></textarea></td>
								</tr>
								<tr>
									<th>Tax ID no.<? //=$lng['Company registration no.']?></th>
									<td><input name="tax_id" id="tax_id" type="text" /></td>
								</tr>
								</tbody>
							</table>
						</div>
						
					</div>
					</form>
					<div class="clear"></div>
			  </div>
		 </div>
	</div>

	<script>
		var headerCount = 1;
		$(document).ready(function() {

			$(document).on('click', '.create_receipt', function() {
				var id = $(this).closest('tr').find('.id').data('id');
				window.location.href='index.php?mn=23&id='+id;
			})
			
			$(document).on('click', '.edit_receipt', function() {
				var id = $(this).closest('tr').find('.id').data('id');
				window.location.href='index.php?mn=23&id='+id;
			})
			
			$(document).on('click', '.edit_invoice', function() {
				var id = $(this).closest('tr').find('.id').data('id');
				window.location.href='index.php?mn=21&vid='+id;
			})
			
			$(document).on('click', '.finish_inv', function() {
				var id = $(this).data('id');
				var cid = $(this).closest('tr').find('.cid').html();
				//var ver = $(this).closest('tr').find('.cid').html();
				//alert(id)
				$.ajax({
					url: ROOT+"admin/billing/ajax/finish_invoice.php",
					//dataType: "json",
					data: {id: id, cid: cid, version: $('#inv_version').val()},
					success: function(result){
						//$('#dump').html(result); return false;
						if(result == 'success'){
							setTimeout(function(){location.reload();}, 300);
						}
					},
					error:function (xhr, ajaxOptions, thrownError){
						$("body").overhang({
							type: "error",
							message: '<i class="fa fa-exclamation-circle"></i>&nbsp;&nbsp;<?=$lng['Sorry but someting went wrong']?> <b><?=$lng['Error']?></b> : '+thrownError,
							duration: 4,
						})
					}
				});
			})
			
			$(document).on('click', '.editClient', function() {
				var id = $(this).data('id');
				//alert(id)
				$.ajax({
					url: ROOT+"admin/ajax/get_customer.php",
					dataType: "json",
					data: {id: id},
					success: function(data){
						//$('#dump').html(data);
						$('#clientID').html(data.en_compname);
						$('#client_id').val(data.clientID);
						$('#joiningdate').val(data.joiningdate);
						$('#employees').val(data.employees);
						
						$('#th_compname').val(data.th_compname);
						$('#en_compname').val(data.en_compname);
						$('#branch').val(data.branch);
						$('input[name="comp_phone"]').val(data.comp_phone);
						$('input[name="comp_fax"]').val(data.comp_fax);
						$('input[name="comp_email"]').val(data.comp_email);
						$('textarea[name="remarks"]').val(data.remarks);

						$('textarea[name="th_address"]').val(data.th_address);
						$('textarea[name="en_address"]').val(data.en_address);
						$('textarea[name="th_billing"]').val(data.th_billing);
						$('textarea[name="en_billing"]').val(data.en_billing);
						$('input[name="tax_id"]').val(data.tax_id);
						
						$('#firstname').val(data.firstname);
						$('#lastname').val(data.lastname);
						$('input[name="position"]').val(data.position);
						$('input[name="phone"]').val(data.phone);
						$('#email').val(data.email);
						//compname = data.en_compname;
						$("#modalEditClient").modal('toggle');
						
					},
					error:function (xhr, ajaxOptions, thrownError){
						$("body").overhang({
							type: "error",
							message: '<i class="fa fa-exclamation-circle"></i>&nbsp;&nbsp;<?=$lng['Sorry but someting went wrong']?> <b><?=$lng['Error']?></b> : '+thrownError,
							duration: 4,
						})
					}
				});
			});
			
			$("#clientForm").on('submit', function(e){ 
				e.preventDefault();
				//if(validateNewclient(this)==false){return false;}
				var data = $(this).serialize();
				$("#subButton i").removeClass('fa-save').addClass('fa-rotate-right fa-spin');
				$.ajax({
					url: ROOT+"admin/ajax/update_customer.php",
					data: data,
					success: function(result){
						//$('#dump').html(result); return false;
						$("body").overhang({
							type: "success",
							message: '<i class="fa fa-check"></i>&nbsp;&nbsp;Data updated successfuly<? //=$lng['Data updated successfuly']?>',
							duration: 2,
						})
						setTimeout(function(){$("#modalEditClient").modal('toggle');}, 500);
						$("#clientForm").trigger('reset');
						dtable.ajax.reload(null, false);
					},
					error:function (xhr, ajaxOptions, thrownError){
						$("body").overhang({
							type: "error",
							message: '<i class="fa fa-exclamation-circle"></i>&nbsp;&nbsp;<?=$lng['Sorry but someting went wrong']?> <b><?=$lng['Error']?></b> : '+thrownError,
							duration: 4,
						})
					}
				});
			});
			
			dtable = $('#datatable').DataTable({
				scrollY:       false,
				scrollX:       false,
				scrollCollapse:false,
				fixedColumns:  false,
				lengthChange:  false,
				searching: 		true,
				ordering: 		true,
				paging: 			true,
				pageLength: 	16,
				filter: 			true,
				info: 			false,
				autoWidth:		false,
				<?=$dtable_lang?>
				processing: 	false,
				serverSide: 	true,
				order: [0, 'desc'],
				ajax: {
					url: AROOT+"billing/ajax/server_get_billing.php",
					type: 'POST'
				},
				columnDefs: [
					{ targets: [0,4,5,7], "class": 'bold',},
					{ targets: [9], "width": '50%',},
					{ targets: [10,11,12], "class": 'tac',},
					{ targets: [4,5], "class": 'tar',}
				],	
				initComplete : function( settings, json ) {
					$('#showTable').fadeIn(200);
					setTimeout(function(){dtable.columns.adjust().draw();}, 10);
				}
			});
			$("#searchFilter").keyup(function() {
				dtable.search(this.value).draw();
			});
			$("#clearSearchbox").click(function() {
				$("#searchFilter").val('');
				dtable.search('').draw();
			});
			
			$(document).on('change', '.clstatus', function(){
				//alert($(this).data("id"));
				var status = this.value;
				var id = $(this).closest('tr').find('.cid').html();
				//alert(status)
				$.ajax({
					url: ROOT+"admin/ajax/change_customer_status.php",
					data: {status: status, id: id},
					success: function(response){
						//$('#dump').html(response);
					},
					error:function (xhr, ajaxOptions, thrownError){
						$("#message").html('<div class="msg_error nomargin"><?=$lng['Something went wrong try again']?>&nbsp; <?=$lng['Error']?> : '+thrownError+'</div>').hide().fadeIn(200);
						setTimeout(function(){$("#message").fadeOut();}, 4000);
					}
				});
				
			});	
			
			/*$(document).on('click', '.extButton', function(){
				var period = $(this).text();
				var id = $(this).closest('tr').find('.cid').html();
				//alert(id);
				//return false;
				$.ajax({
					url: ROOT+"admin/billing/ajax/extend_period.php",
					data: {period: period, id: id},
					success: function(response){
						$('#dump').html(response);
					},
					error:function (xhr, ajaxOptions, thrownError){
						alert(thrownError);
					}
				});
				
			});*/	
			
			$('a[data-toggle="tab"]').on('shown.bs.tab', function(e) {
				localStorage.setItem('activeInvoice', $(e.target).data('target'));
				dtable.columns.adjust().draw();
			});
			var activeTab = localStorage.getItem('activeInvoice');
			if(activeTab){
				$('#myTab a[data-target="' + activeTab + '"]').tab('show');
			}else{
				$('#myTab a[data-target="#tab_trial"]').tab('show');
			}
			
		});
	
	</script>




	
