<?

	$price_schedule = array();
	$sql = "SELECT price_schedule FROM rego_company_settings";
	if($res = $dba->query($sql)){
		if($row = $res->fetch_assoc()){
			$price_schedule = unserialize($row['price_schedule']);
		}
	}else{
		echo 'Error : '.mysqli_error($dba);
	}
	//var_dump($price_schedule);

	// echo '<pre>';
	// print_r($version);
	// echo '</pre>';
	// die();

?>
<style>
table.mytable tbody td {
	text-align:left;
}
table.dataTables tbody tr.expired {
	background:#fff9f9;
}
table.dataTables tbody tr.expired > td {
	color:#a00;
	font-weight:600;
}
table.dataTables tbody tr.remove {
	background: #eff;
}
table.dataTables tbody tr.remove > td  {
	font-weight:600;
	color:#00a;
}

table.mytable thead th {
	text-align:left;
}
.clstatus {
	border:0 !important;
	background:transparent;
}
.delClient * {
	color: #039 !important;
}
.delClient:hover * {
	color: #a00 !important;
}
.tab-content {
  display: flex;
}

.tab-content > .tab-pane {
  display: block; /* undo "display: none;" */
  visibility: hidden;
  margin-right: -100%;
  width: 100%;
}

.tab-content > .active {
  visibility: visible;
}
</style>
	
	<h2><i class="fa fa-list-ul"></i>&nbsp; <?=$lng['Customers register']?><span id="cid"><? //=$acid?></span></h2>
	
	<div class="main">
		<div style="padding:0 0 0 20px" id="dump"></div>
			
		<div id="showTable" style="display:none">
			
					<div>
						<div class="searchFilter" class="btn-left">
							<form>
							<input style="margin:0" placeholder="<?=$lng['Filter']?>" class="sFilter" id="searchFilter" type="text" />
							</form>
							<button id="clearSearchbox" type="button" class="clearFilter"><i class="fa fa-times"></i></button>
						</div>

						<select id="statFilter" class="button btn-left">
							<option value=""><?=$lng['Show all']?></option>
							<? foreach($client_status as $k=>$v){
								echo '<option value="'.$k.'">'.$v.'</option>';
							} ?>
						</select>
					</div>
					<div style="clear:both"></div>
	
			<table id="datatable" class="dataTables hoverable selectable nowrap">
				<thead>
				<tr>
					<th class="par30"><?=$lng['ID']?></th>
					<th class="par30"><?=$lng['Company']?></td>
					<th class="tac" data-orderable="false" style="width:1px"><i data-toggle="tooltip" title="<?=$lng['Go to customer']?>" class="fa fa-external-link fa-lg"></i></th>
					<th class="par30"><?=$lng['Subscription']?></th>
					<th data-orderable="false"><?=$lng['Agent']?></th>
					<th><?=$lng['Expire date']?></th>
					<th data-orderable="false"><?=$lng['Contact']?></th>
					<th data-orderable="false"><?=$lng['Phone']?></th>
					<th data-orderable="false"><?=$lng['email']?></th>
					<th data-orderable="false"><?=$lng['Line ID']?></th>
					<th data-orderable="false" style="width:1px; padding:4px 10px !important"><?=$lng['Status']?></th>
					<th class="tac" data-orderable="false" style="width:1px"><i data-toggle="tooltip" title="<?=$lng['Edit']?> / <?=$lng['Details']?>" class="fa fa-edit fa-lg"></i></th>
					<th class="tac" data-orderable="false" style="width:1px"><i data-toggle="tooltip" title="<?=$lng['Delete']?>" class="fa fa-trash fa-lg"></i></th>
				</tr>
				</thead>
				<tbody>

			  </tbody>
			</table>
		</div>
				
	</div>
	<!--<div style="position:absolute;top:217px; left:0; right:0; background:rgba(255,0,0,0.5); height:660px;"></div>-->

	<!-- Modal Delete Client -->
	<div class="modal fade" id="modalDeleteClient" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header" style="background:#b00; color:#fff">
					<h5 class="modal-title"><i class="fa fa-trash"></i>&nbsp; <?=$lng['Delete Customer']?></h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body" style="padding:20px 25px 25px 25px">
					<div style="display:none; color:#b00; font-weight:600; padding-bottom:5px; font-size:14px" id="delMsg"><?=$lng['Wrong password']?></div>
					<div id="deleteInfo"></div>
					<form>
					<input style="width:100%" placeholder="<?=$lng['Authorised password']?>" id="passDelete" type="password" />
					</form>
					<button disabled style="margin-top:10px; width:100%" class="btn btn-primary" id="deleteButton" type="button"><i class="fa fa-trash"></i>&nbsp;&nbsp;<?=$lng['Delete Customer']?></button>
					</div>
			  </div>
		 </div>
	</div>

	<!-- Modal Edit Client -->
	<div class="modal fade" id="modalEditClient" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false">
		<div class="modal-dialog modal-lg" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title"><i class="fa fa-edit"></i>&nbsp; <b><span id="client"></span> (<span id="clientID"></span>)</b></h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body" style="padding:20px 25px 25px 25px">
				
				<form id="clientForm">
					<input type="hidden" name="hidden_company_id" id="hidden_company_id" value="">
					<input type="hidden" name="id" />
					<input type="hidden" name="emp_platform" value="0" />
	
					<ul class="nav nav-tabs" id="modTab" style="position:relative">
						<li class="nav-item"><a class="nav-link active" data-target="#tab_subscriber" data-toggle="tab"><?=$lng['Subscriber']?></a></li>
						<li class="nav-item"><a class="nav-link" data-target="#tab_address" data-toggle="tab"><?=$lng['Address']?></a></li>
						<li class="nav-item"><a class="nav-link" data-target="#tab_subscription" data-toggle="tab"><?=$lng['Subscription']?></a></li>
						<? //if($_SESSION['RGadmin']['access']['customer']['edit'] == 1 || $_SESSION['admin']['access']['customer']['add'] == 1){ ?>
						<div style="position:absolute; top:0; right:0;">					
						<button class="btn btn-primary" id="submitbtn" type="submit"><i class="fa fa-save"></i>&nbsp;&nbsp;<?=$lng['Update']?></button>
						</div>
						<? //} ?>
					</ul>
					
					<div class="tab-content">
						<div class="tab-pane show active" id="tab_subscriber">
							<table class="basicTable inputs" border="0">
								<tbody>
									<tr>
										<th style="width:5%"><i class="man"></i><?=$lng['First name']?></th>
										<td><input name="firstname" type="text" /></td>
									</tr>
									<tr>
										<th><i class="man"></i><?=$lng['Last name']?></th>
										<td><input name="lastname" type="text" /></td>
									</tr>
									<tr>
										<th><?=$lng['Phone']?></th>
										<td><input name="phone" type="text" /></td>
									</tr>
									<tr>
										<th><?=$lng['Line ID']?></th>
										<td><input name="line_id" type="text" /></td>
									</tr>
									<tr>
										<th><i class="man"></i><?=$lng['email']?> / <?=$lng['Username']?></th>
										<td><input name="email" type="text" /></td>
									</tr>
									<tr>
										<th><?=$lng['Company']?> name Thai</th>
										<td><input name="th_compname" type="text" /></td>
									</tr>
									<tr>
										<th><?=$lng['Company']?> name English</th>
										<td><input name="en_compname" type="text" /></td>
									</tr>
									<tr>
										<th><?=$lng['Tax ID no.']?></th>
										<td><input class="tax_id_number" name="tax_id" type="text" /></td>
									</tr>
									<tr>
										<th>WHT Certificate<? //=$lng['Company registration no.']?></th>
										<td>
											<select name="certificate" id="certificate" style="width:100%">
												<option value="1">Need certificate<? //=$lng['Yes']?></option>
												<option value="0">Don't need certificate<? //=$lng['No']?></option>
											</select>
										</td>
									</tr>
									<tr>
										<th><?=$lng['Subscription date']?></th>
										<td><input id="joiningdate" readonly type="text" /></td>
									</tr>
									<tr>
										<th valign="top"><?=$lng['Remarks']?></th>
										<td><textarea name="remarks" rows="3"></textarea></td>
									</tr>
								</tbody>
							</table>
						</div>
						
						<div class="tab-pane" id="tab_address">
							<table class="basicTable inputs" border="0">
								<tbody>
									<tr>
										<th valign="top">Billing <?=$lng['Address in Thai']?></th>
										<td><textarea name="th_billing" rows="4"></textarea></td>
									</tr>
									<tr>
										<th valign="top">Billing <?=$lng['Address in English']?></th>
										<td><textarea name="en_billing" rows="4"></textarea></td>
									</tr>
								</tbody>
							</table>
						</div>
						
						<div class="tab-pane" id="tab_subscription">
							<table class="basicTable inputs" border="0">
								<tbody>
									<tr>
										<th><?=$lng['Subscription']?></th>
										<td>
											<select id="version" name="version" style="width:100%">
												<? foreach($version as $k=>$v){ ?>
												<option value="<?=$k?>"><?=$v?></option>
												<? } ?>
											</select>
										</td>
									</tr>
									<tr>
										<th><?=$lng['Employee platform']?></th>
										<td>
											<select name="emp_platform" style="width:100%">
												<option value="0"><?=$lng['No']?></option>
												<option value="1"><?=$lng['Yes']?></option>
											</select>
										</td>
									</tr>
									<tr>
										<th style="width:5%"><i class="man"></i><?=$lng['Employees']?></th>
										<td><input class="sel numeric" name="employees" type="text" /></td>
									</tr>
									<!--<tr>
										<th>Price<? //=$lng['Price per Year']?></th>
										<td><input class="sel numeric" name="price" type="text" /></td>
									</tr>
									<tr>
										<th>Discount<? //=$lng['Price per Year']?></th>
										<td><input class="sel numeric" name="discount" type="text" /></td>
									</tr>
									<tr>
										<th>VAT<? //=$lng['Price per Year']?></th>
										<td><input class="sel numeric" name="vat" type="text" /></td>
									</tr>
									<tr>
										<th>WHT<? //=$lng['Price per Year']?></th>
										<td><input class="sel numeric" name="wht" type="text" /></td>
									</tr>
									<tr>
										<th>Net to pay<? //=$lng['Price per Year']?></th>
										<td><input class="sel numeric" name="net" type="text" /></td>
									</tr>-->
									<tr>
										<th><i class="man"></i><?=$lng['Period start']?></th>
										<td><input style="cursor:pointer" readdonly class="date_month" name="period_start" type="text" /></td>
									</tr>
									<tr>
										<th><i class="man"></i><?=$lng['Period end']?></th>
										<td><input style="cursor:pointer" readdonly class="date_month" name="period_end" type="text" /></td>
									</tr>
									<!--<tr>
										<th>Price Year<? //=$lng['Price per Year']?></th>
										<td><input class="sel numeric" name="price_year" type="text" /></td>
									</tr>-->
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
		
		var innerheight = window.innerHeight;
		var dheight = innerheight-317;
		var drows = Math.floor(dheight/30);

		$(document).ready(function() {

			var last_id = null;
			var price_schedule = <?=json_encode($price_schedule)?>;
			var id;
			
			$(document).on("click", ".delClient", function(){
				last_id = $(this).closest('tr').find('.editClient').data('id');
				$("#delMsg").hide();
				//alert(last_id)
				$.ajax({
					url: "ajax/get_delete_info.php",
					data:{id: last_id},
					dataType: 'json',
					success: function(result){
						//$('#dump').html(result); return false;
						$('#deleteInfo').html(result['string']);
						$("#modalDeleteClient").modal('toggle');
						if(!result['error']){
							$('#deleteButton').prop('disabled', false);
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
			});
			$(document).on("click", "#deleteButton", function(){
				$("#delMsg").hide();
				if($("#passDelete").val() != '<?=$del_password?>'){
					$("#delMsg").fadeIn(200);
					return false;
				}
				//return false;
				$("#deleteButton i").removeClass('fa-trash').addClass('fa-refresh fa-spin');
				$("#deleteButton").prop('disabled', true);
				$.ajax({
					url: "ajax/delete_customer_from_server.php",
					data:{id: last_id},
					success: function(result){
						//$('#dump').html(result); return false;
						if($.trim(result) == 'success'){
							setTimeout(function(){
								$("#modalDeleteClient").modal('toggle');
								$("#deleteButton i").removeClass('fa-refresh fa-spin').addClass('fa-trash');
								$("#passDelete").val('');
								$("#deleteButton").prop('disabled', false);
								dtable.ajax.reload(null, false);
							}, 3000);
						}else{
							$("#delMsg").html(result).fadeIn(200);
							$("#deleteButton i").removeClass('fa-refresh fa-spin').addClass('fa-trash');
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
			});
			
			$("#certificate").on('change', function(e){
				$("#version").trigger('change');
				if(this.value == 'Y'){
					$("#reqTax").show();
				}else{
					$("#reqTax").hide();
				}
			})
			$("#version").on('change', function(e){ 
				var version = this.value;
				var price = parseFloat(price_schedule[version]['price_year']);
				var discount = parseFloat(price_schedule[version]['discount']);
				var price_year = parseFloat(price - discount); 
				var vat = parseFloat((price_year * 0.07).toFixed(2));
				var wht = 0;
				if($('#certificate').val() == 'Y'){
					var wht = parseFloat((price_year * 0.03).toFixed(2));
				}
				var net = parseFloat((price_year + vat - wht).toFixed(2));
				
				$('input[name="employees"]').val(price_schedule[version]['max_employees']);
				$('input[name="price"]').val(price);
				$('input[name="discount"]').val(discount);
				$('input[name="vat"]').val(vat);
				$('input[name="wht"]').val(wht);
				$('input[name="net"]').val(net);
				$('input[name="price_year"]').val(price_year);
				if(version != 10){
					$('input[name="emp_platform"]').val(1);
				}else{
					$('input[name="emp_platform"]').val(0);
				}
				
				//alert(version);
				//alert(price_schedule[version]['max_employees']);
			})

			$(document).on('click', '.editClient', function() {
				$("#clientForm").trigger('reset');
				id = $(this).data('id');
				//alert(id)
				$.ajax({
					url: "ajax/get_customer.php",
					dataType: "json",
					data: {id: id},
					success: function(data){
						//$('#dump').html(data); return false;
						$('#client').html(data.en_compname);
						$('#clientID').html((data.clientID).toUpperCase());
						$('#hidden_company_id').val(data.clientID);
						$('input[name="id"]').val(data.id);
						//alert(data.firstname)
						$('input[name="firstname"]').val(data.firstname);
						$('input[name="lastname"]').val(data.lastname);
						$('input[name="phone"]').val(data.phone);
						$('input[name="email"]').val(data.email);
						$('input[name="th_compname"]').val(data.th_compname);
						$('input[name="en_compname"]').val(data.en_compname);
						$('input[name="tax_id"]').val(data.tax_id);
						$('select[name="certificate"]').val(data.certificate);
						if(data.certificate == 'Y'){
							$("#reqTax").show();
						}else{
							$("#reqTax").hide();
						}
						$('#joiningdate').val(data.joiningdate);
						$('textarea[name="remarks"]').val(data.remarks);

						//$('textarea[name="address"]').val(data.address);
						//$('textarea[name="en_address"]').val(data.en_address);
						$('textarea[name="th_billing"]').val(data.th_billing);
						$('textarea[name="en_billing"]').val(data.en_billing);
						console.log(data.version);

						//if its 400 then disable the button 
						// if its stnadard then enable only professional and elite 
						// if its professional then enable only elite 

						if(data.version == '400')
						{
							$('select[name="version"] option[value="300"]').remove();
							$('select[name="version"] option[value="200"]').remove();
							$('select[name="version"] option[value="100"]').remove();
							$('select[name="version"] option[value="50"]').remove();
							$('select[name="version"] option[value="20"]').remove();
							$('select[name="version"] option[value="10"]').remove();
							$('select[name="version"] option[value="0"]').remove();
						}
						if(data.version == '300')
						{
							$('select[name="version"] option[value="200"]').remove();
							$('select[name="version"] option[value="100"]').remove();
							$('select[name="version"] option[value="50"]').remove();
							$('select[name="version"] option[value="20"]').remove();
							$('select[name="version"] option[value="10"]').remove();
							$('select[name="version"] option[value="0"]').remove();
						}
						if(data.version == '200')
						{
							$('select[name="version"] option[value="100"]').remove();
							$('select[name="version"] option[value="50"]').remove();
							$('select[name="version"] option[value="20"]').remove();
							$('select[name="version"] option[value="10"]').remove();
							$('select[name="version"] option[value="0"]').remove();
						}							
						if(data.version == '100')
						{
							$('select[name="version"] option[value="50"]').remove();
							$('select[name="version"] option[value="20"]').remove();
							$('select[name="version"] option[value="10"]').remove();
							$('select[name="version"] option[value="0"]').remove();
						}						
						if(data.version == '50')
						{
							$('select[name="version"] option[value="20"]').remove();
							$('select[name="version"] option[value="10"]').remove();
							$('select[name="version"] option[value="0"]').remove();
						}						
						if(data.version == '20')
						{
							$('select[name="version"] option[value="10"]').remove();
							$('select[name="version"] option[value="0"]').remove();
						}							
						if(data.version == '10')
						{
							$('select[name="version"] option[value="0"]').remove();
						}					

						$('select[name="version"]').val(data.version);
						$('select[name="emp_platform"]').val(data.emp_platform);
						$('input[name="employees"]').val(data.employees);
						$('input[name="price"]').val(data.price);
						$('input[name="discount"]').val(data.discount);
						$('input[name="vat"]').val(data.vat);
						$('input[name="wht"]').val(data.wht);
						$('input[name="net"]').val(data.net);
						$('input[name="price_year"]').val(data.price_year);
						
						$('input[name="period_start"]').val(data.period_start);
						$('input[name="period_end"]').val(data.period_end);

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
				var err = 0;
				if($('input[name="firstname"]').val() == '' ||
					$('input[name="lastname"]').val() == '' ||
					$('input[name="email"]').val() == '' || 
					$('input[name="employees"]').val() == 0 || 
					$('input[name="period_start"]').val() == '' || 
					$('input[name="period_end"]').val() == ''
					){err = 1;
				}
				//if($('select[name="certificate"]').val() == 'Y' && $('input[name="tax_id"]').val() == ''){err = 1;}
				if(err){
					alert('Please fill in all required fields'); return false;
				}
				
				$("#subButton i").removeClass('fa-save').addClass('fa-refresh fa-spin');
				var data = $(this).serialize();

				$.ajax({
					url: "ajax/update_customer.php",
					data: data,
					success: function(result){
						//$('#dump').html(result); return false;
						$("body").overhang({
							type: "success",
							message: '<i class="fa fa-check"></i>&nbsp;&nbsp;Data updated successfuly<? //=$lng['Data updated successfuly']?>',
							duration: 2,
						})
						setTimeout(function(){
							$("#modalEditClient").modal('toggle');
							$("#clientForm").trigger('reset');
							dtable.ajax.reload(null, false);
						}, 500);
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
			
			
			var dtable = $('#datatable').DataTable({
				scrollY:       false,
				scrollX:       false,
				scrollCollapse:false,
				fixedColumns:  false,
				lengthChange:  false,
				searching: 		true,
				ordering: 		true,
				paging: 			true,
				pageLength: 	drows,
				filter: 			true,
				info: 			false,
				//autoWidth:		false,
				<?=$dtable_lang?>
				processing: 	false,
				serverSide: 	true,
				ajax: {
					url: "ajax/server_get_customers.php",
					type: 'POST',
					"data": function(d){
						d.status = $('#statFilter').val();
					}
				},
				columnDefs: [
					{ targets: 1, "width": '80%'},
					//{ targets: 4, "class": 'tac'},
					{ targets: [2,8], "width": '1px'},
					{ targets: 10, "class": 'nopadding tal'}
				],	
				initComplete : function( settings, json ) {
					$('#showTable').fadeIn(200);
					setTimeout(function(){
						dtable.columns.adjust().draw();
					}, 10);
				},
				"createdRow": function ( row, data, index ) {
					if(data[5].indexOf('exp') != -1){
							$(row).addClass('expired');
					}
					if(data[10].indexOf('rem') != -1){
            			$(row).addClass('remove');
						$('td', row).eq(12).addClass('delClient');
						//alert(data[12])
					}

					if (data[10].indexOf('data-id="0"') != -1) {
					  $(row).addClass('expired');
					}
					if (data[10].indexOf('data-id="2"') != -1) {
					  $(row).addClass('expired');
					} 
					if (data[10].indexOf('data-id="3"') != -1) {
					  $(row).addClass('expired');
					} 

				}
			});
			$("#searchFilter").keyup(function() {
				dtable.search(this.value).draw();
			});
			$("#clearSearchbox").click(function() {
				$("#searchFilter").val('');
				dtable.search('').draw();
			});
			$(document).on("change", "#statFilter", function(e) {
				dtable.ajax.reload(null, false);
			})

			$(document).on('change', '.clstatus', function(){
				//alert($(this).data("id"));
				var status = this.value;
				var id = $(this).closest('tr').find('.cid').html();
				//alert(status)
				$.ajax({
					url: "ajax/change_customer_status.php",
					data: {status: status, id: id},
					success: function(response){
						//$('#dump').html(response);
						dtable.ajax.reload(null, false);
					},
					error:function (xhr, ajaxOptions, thrownError){
						$("#message").html('<div class="msg_error nomargin"><?=$lng['Something went wrong try again']?>&nbsp; <?=$lng['Error']?> : '+thrownError+'</div>').hide().fadeIn(200);
						setTimeout(function(){$("#message").fadeOut();}, 4000);
					}
				});
				
			});	
			
			
		});
	
	</script>




	
