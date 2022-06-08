<?
	//if($_SESSION['xhr']['access']['sys_users']['access'] == 0){echo '<div class="msg_nopermit">You have no permission to enter this module</div>'; exit;}
	
	/*$users = array();
	$sql = "SELECT * FROM rego_all_users";
	if($res = $dba->query($sql)){
		while($row = $res->fetch_assoc()){ 
			$users[] = $row;
		}
	}else{
		echo mysqli_error($dba);
	}*/
	//var_dump($users); exit;
	
	$customers = array();
	$sql = "SELECT * FROM rego_customers WHERE status = 1 ORDER BY clientID ASC";
	if($res = $dba->query($sql)){
		while($row = $res->fetch_assoc()){
			$customers[$row['clientID']] = $row[$lang.'_compname'];
		}
	}
	//var_dump($customers); 


	// echo '<pre>';
	// print_r($customers);
	// echo '</pre>';
	// die();

	
	$users = array();
	foreach($customers as $k=>$v){

	// 	echo '<pre>';
	// print_r($k);
	// echo '</pre>';
	// die();


		if($res = $dba->query("SELECT * FROM rego_all_users")){
			while($row = $res->fetch_assoc()){
				$users[$row['username']] = $row;
				$users[$row['username']]['cid'] = $k;
				//$users[$row['username']]['access'] = $k;
			}
		}else{
			echo mysqli_error($dba);
		}
	}

	// echo '<pre>';
	// print_r($users);
	// echo '</pre>';
	// die();
	//var_dump($users); exit;
	
	/*$users = array();
	foreach($customers as $k=>$v){
		$dbc = @new mysqli($my_database,$my_username,$my_password);
		$dbc = @new mysqli($my_database,$my_username,$my_password,$prefix.$k);
		mysqli_set_charset($dbc,"utf8");
		if($res = $dbc->query("SELECT ref, emp_id, username, name, phone, type, img, status FROM ".$k."_users")){
			while($row = $res->fetch_assoc()){
				$users[$row['username']] = $row;
				$users[$row['username']]['cid'] = $k;
				$users[$row['username']]['access'] = $k;
			}
		}else{
			echo mysqli_error($dbc);
		}
	}*/
	//var_dump($users); 
	
	/*foreach($users as $k=>$v){
		if($res = $dba->query("SELECT * FROM rego_all_users WHERE id = '".$v['ref']."'")){
			if($row = $res->fetch_assoc()){
				$users[$v['username']]['access'] = $row['access'];
			}
		}else{
			echo '<br>'.mysqli_error($dba);
		}
	}*/
	
	//var_dump($users);	exit;
	
	
	
	$password = generateStrongPassword(8, false);//randomPassword();
	//var_dump($password);
	// echo '<pre>';
	// print_r($users);
	// echo '</pre>';

	// die();

?>
	<link rel="stylesheet" type="text/css" href="../css/croppie_users.css?<?=time()?>" />
	
	<style>
		.SumoSelect{
			width: 100% !important;
			padding: 3px 0 0 10px !important;
			margin:0 !important;
		}
		.SumoSelect > .optWrapper {
			width: 200px; 
		}
	#upload-demo {
		background-size: 130px auto !important;
	}
	</style>
	

	<h2><i class="fa fa-table"></i>&nbsp;&nbsp;Rego users<? //=$lng['System users']?></h2>
	<div class="main">
		<div style="padding:0 0 0 20px" id="dump"></div>
      
		<table border="0">
			<tr>
				<td>
					<div class="searchFilter">
						<input placeholder="<?=$lng['Filter']?>" id="searchFilter" class="sFilter" type="text" />
						<button id="clearSearchbox" type="button" class="clearFilter btn btn-default btn-sm"><i class="fa fa-times"></i></button>
					</div>
				</td>
				<!--<td>
					<select id="statFilter" class="button">
						<option selected value=""><?=$lng['All employees']?></option>
						<? foreach($emp_status as $k=>$v){
								echo '<option';
								if($k == 1){echo ' selected';}
								echo ' value="'.$k.'">'.$v.'</option>';
							} ?>
					</select>
				</td>-->
				<td>
					<select id="pageLength" class="button btn-fl">
						<option selected value="">Rows / page</option>
						<option value="10">10 Rows / page</option>
						<option value="15">15 Rows / page</option>
						<option value="20">20 Rows / page</option>
						<option value="30">30 Rows / page</option>
						<option value="40">40 Rows / page</option>
						<option value="50">50 Rows / page</option>
					</select>
				</td>
			</tr>
		</table>
		
		<div id="showTable" style="xdisplay:none">        
		<table id="datatable" class="dataTable hoverable selectable nowrap">
			<thead>
				<tr>
					<th class="tac" style="width:1px; padding:0 5px" data-sortable="false"><i class="fa fa-image fa-lg"></i></th>
					<th><?=$lng['Emp. ID']?></th> 
					<th><?=$lng['First name']?></th> 
					<th><?=$lng['Last name']?></th> 
					<th><?=$lng['Username']?></th> 
					<th>User <?=$lng['Type']?></th> 
					<th><?=$lng['Access']?></th> 
					<th>Sys <?=$lng['Status']?></th>
					<th>Emp <?=$lng['Status']?></th>
					<th><?=$lng['Change password']?></th>
					<th><?=$lng['Terms & Conditions']?></th>
					<th><?=$lng['Privacy policy']?></th>
					<th><?=$lng['Cookie Consent']?></th>

					<!-- <th data-sortable="false" style="width:80%"></th> -->
					<!--<th class="tac" style="width:1px"><i data-toggle="tooltip" title="<?=$lng['Edit']?>" class="fa fa-pencil-square-o fa-lg"></i></th>-->
					<!--<th style="width:1px"><i data-toggle="tooltip" title="<?=$lng['Delete']?>" class="fa fa-trash fa-lg"></i></th>-->
				</tr>
			</thead>
			<tbody>
			<? foreach($users as $k=>$v){ 
				if($v['sys_status'] || $v['emp_status'] != 0){ 

					if($v['emp_status'] != 0){
						$empid = $v['emp_id'];
						$firstname = $v['firstname'];
						$lastname = $v['lastname'];
					}else{
						$empid = $firstname = $lastname = '';
					}
				?>

				<tr>
					<td class="nopad">
						<img style="height:28px; width:28px; margin:2px; cursor:default" class="img-tooltip" title="<img src=<?=ROOT.$v['img']?>?<?=time()?> />" data-toggle="tooltip" data-placement="right" src="<?=ROOT.$v['img']?>?<?=time()?>" />
					</td>
					<td><?=$empid?></td>
					<td><?=$firstname?></td>
					<td><?=$lastname?></td>
					<td><?=$k?></td>
					<td class="tac"><?=$user_type[$v['type']]?></td>
					<td class="nopad">
						<? $tmp = explode(',', $v['access']);
						if(count($tmp) > 1){
							echo '<select style="width:auto; background:transparent; padding:0px 6px">';
							foreach($tmp as $a){echo '<option>'.strtoupper($a).'</option>';}
							echo '</select>';
						}else{
							echo '<span style="padding:0 8px">'.strtoupper($tmp[0]).'</span>';
						} ?>
					
					</td>
					<td class="tac"><?=$def_status[$v['sys_status']]?></td>
					<td class="tac"><?=$def_status[$v['emp_status']]?></td>
					<td class="tac">
						<button class="btn btn-primary btn-sm" type="button" onclick="changepassPOPup('<?=$k;?>');"> <?=$lng['Change password']?></button>	
					</td>
				
					<td>
						<?php if ($v['terms_consent'] == '1'){?>

							<button  class="btn btn-primary btn-sm" type="button" > <?=$lng['Agreed'] .' on '.$v['terms_consent_date']?></button>	

						<?php }else if ($v['terms_consent'] == '0'){?>

							<button  class="btn btn-danger btn-sm" type="button" > <?=$lng['Not Agreed'].' on '.$v['terms_consent_date']?></button>	
						<?php } else {?>

							<button  class="btn btn-danger btn-sm" type="button" > <?=$lng['Not Agreed']?></button>	
						<?php } ?>
					</td>
					<td>
						<?php if ($v['privacy_consent'] == '1'){?>

							<button  class="btn btn-primary btn-sm" type="button" > <?=$lng['Agreed'].' on '.$v['privacy_consent_date']?></button>	

						<?php }else if ($v['privacy_consent'] == '0'){?>

							<button  class="btn btn-danger btn-sm" type="button" > <?=$lng['Not Agreed'].' on '.$v['privacy_consent_date']?></button>	
						<?php } else {?>

							<button  class="btn btn-danger btn-sm" type="button" > <?=$lng['Not Agreed']?></button>	
						<?php } ?>
					</td>
					<td>
						<?php if ($v['cookie_consent'] == '1'){?>

							<button  class="btn btn-primary btn-sm" type="button" > <?=$lng['Agreed'].' on '.$v['cookie_consent_date']?></button>	

						<?php }else if ($v['cookie_consent'] == '0'){?>

							<button  class="btn btn-danger btn-sm" type="button" > <?=$lng['Not Agreed'].' on '.$v['cookie_consent_date']?></button>	
						<?php } else {?>

							<button  class="btn btn-danger btn-sm" type="button" > <?=$lng['Not Agreed']?></button>	
						<?php } ?>
					</td>

					<!-- <td></td> -->
				</tr>
			<? } } ?>
			</tbody>
		</table>
		</div>

   </div>
   
	<!-- Modal Add / Edit Users -->
	<div class="modal fade" id="modalUser" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false">
		<div class="modal-dialog" role="document" style="max-width:700px">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title"><i class="fa fa-user"></i>&nbsp;&nbsp;Edit Rego user<? //=$lng['Edit user']?> <span id="aUser"></span></h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body" style="padding:15px 20px 20px">
					<span style="font-weight:600; color:#cc0000; display:none; display:block; margin:-5px 0 5px 0" id="userMess"></span>
					<form id="userForm">
						<input type="hidden" name="id" />
						<!--<input type="hidden" name="prev_password" id="prev_password" />-->

						<table class="basicTable inputs" border="0">
							<tbody>
								<tr>
									<th><i class="man"></i><?=$lng['First name']?></th>
									<td><input placeholder="__" name="firstname" type="text" /></td>
									<td rowspan="6" style="width:150px; vertical-align:top"><img id="userImg" style="display:block; padding-left:10px; width:157px; height:157px" src="../images/profile_image.jpg" /></td>
								</tr>
								<tr>
									<th><i class="man"></i><?=$lng['Last name']?></th>
									<td><input placeholder="__" name="lastname" type="text" /></td>
								</tr>
								<tr>
									<th><?=$lng['Position']?></th>
									<td><input placeholder="__" name="position" type="text" /></td>
								</tr>
								<tr>
									<th><?=$lng['Phone']?></th>
									<td><input placeholder="__" name="phone" type="text" /></td>
								</tr>
								<tr>
									<th><?=$lng['Line ID']?></th>
									<td><input placeholder="__" name="line_id" type="text" /></td>
								</tr>
								<tr>
									<th><i class="man"></i><?=$lng['Username']?> / <?=$lng['email']?></th>
									<td><input placeholder="__" name="username" type="text" /></td>
								</tr>
								<tr>
									<th><i class="man"></i>Assigned companies</th>
									<td colspan="2"><textarea rows="3" placeholder="__" name="access"></textarea></td>
								</tr>
								<tr>
									<th><i class="man"></i>User type<? //=$lng['Type']?></th>
									<td colspan="2">
										<select name="type" style="width:100%">
											<option value="sys">System</option>
											<option value="emp">Employee</option>
										</select>
									</td>
								</tr>
								<tr>
									<th><i class="man"></i>Last visit<? //=$lng['Phone']?></th>
									<td colspan="2"><input placeholder="__" name="last" type="text" /></td>
								</tr>
								<tr>
									<th><i class="man"></i><?=$lng['Status']?></th>
									<td colspan="2">
										<select name="status" id="status" style="width:100%">
											<? foreach($def_status as $k=>$v){ ?>
												<option value="<?=$k?>"><?=$v?></option>
											<? } ?>
										</select>
									</td>
								</tr>
							</tbody>
						</table>
					
						<div class="clear" style="height:15px"></div>
						<? if($_SESSION['RGadmin']['access']['users']['add'] == 1 || $_SESSION['RGadmin']['access']['users']['edit'] == 1){ ?>
						<button id="saveUser" class="btn btn-primary" style="margin-right:5px; float:left" type="submit"><i class="fa fa-save"></i> <?=$lng['Update']?></button>
						<? } ?>
						<button class="btn btn-primary" style="float:right" type="button" data-dismiss="modal"><i class="fa fa-times"></i> <?=$lng['Cancel']?></button>
						<div class="clear"></div>

					</form>
					</div>
			  </div>
		 </div>
	</div>


	<!--------Change password ----------->
   <div class="modal fade show" id="passModalE" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="static" data-keyboard="false" style="display: none;" aria-modal="true">
		 <div class="modal-dialog" style="max-widt:450px">
			  <div class="modal-content">
					<div class="modal-header">
						 <h4 class="modal-title" id="myModalLabel"><i class="fa fa-lock"></i>&nbsp; <?=$lng['Change password']?></h4>
					</div>
					<div class="modal-body" style="padding:10px 25px 25px 25px">
					<span style="font-weight:600; color:#cc0000;" id="pass_msg"></span>
					<form id="changeUserPasswordEU" class="sform" style="padding-top:10px;">
						 <label><?=$lng['Username']?></label>
						 <input name="uname" id="uname" type="text" value="" readonly="readonly">
						 <label><?=$lng['New password']?><i class="man"></i></label>
						 <input name="npass" id="npass" type="text">
						 <button class="btn btn-primary" style="margin-top:15px" type="submit"><i class="fa fa-save"></i> <?=$lng['Change password']?></button>
						<button style="float:right;margin-top:15px" type="button" class="btn btn-primary" data-dismiss="modal"><i class="fa fa-times"></i>&nbsp; <?=$lng['Cancel']?></button>
						<div class="clear"></div>
					</form>
					</div>
					<div class="clear"></div>
			  </div>
		 </div>
	</div>
   <!---------Change password ---------->
	 
	 
	<!-- PAGE RELATED PLUGIN(S) -->
	<script type="text/javascript" src="../js/jquery.autocomplete.js"></script>
	<script type="text/javascript" src="../js/jquery.sumoselect.min.js"></script>

	<script type="text/javascript">

		function changepassPOPup(userEmail){

			if(userEmail !=''){

				$("#pass_msg div").remove();
				$('#passModalE input#uname').val(userEmail);
				$('#passModalE input#npass').val(userEmail);
				//$('#passModalE input#rpass').val(userEmail);
				$('#passModalE').modal('show');
			}
		}

		$("#changeUserPasswordEU").submit(function(e) {
			e.preventDefault();
			var formData = $(this).serialize();
			$.ajax({
				url: ROOT + "admin/ajax/change_user_pass_byadmin.php",
				data: formData,
				success: function(response){
					//$("#dump").html(response); return false;

					if(response == 'success'){
						$("#pass_msg").html('<div class="msg_alert nomargin" style="color:#080 !important;">Password changed successfuly!</div>');
						setTimeout(function(){
							$('#passModalE').modal('toggle');
						}, 2000);
					}else if(response=='empty'){
						$("#pass_msg").html('<div class="msg_alert nomargin">Please fill in required fields!</div>');
					}else if(response=='old'){
						$("#pass_msg").html('<div class="msg_alert nomargin">User not exist!</div>');
					}else if(response=='short'){
						$("#pass_msg").html('<div class="msg_alert nomargin">New password to short, min. 8 characters!</div>');
					}else if(response=='same'){
						$("#pass_msg").html('<div class="msg_alert nomargin">New passwords are not the same!</div>');
					}else{
						$("#pass_msg").html(response);
					}
				},
				error:function (xhr, ajaxOptions, thrownError){
					$("#pass_msg").html(thrownError);
				}
			});
		});

		
		//var heights = window.innerHeight-260;
		var headerCount = 1;
		var last_id;
		
		$(document).ready(function() {
			
			dtable = $('#datatable').DataTable({
				scrollY:       false,
				scrollX:       true,
				scrollCollapse:false,
				fixedColumns:  false,
				lengthChange:  false,
				searching: 		true,
				ordering: 		true,
				paging: 			true,
				pagingType: 'full_numbers',
				pageLength: 	16,
				filter: 			true,
				info: 			true,
				order: [[2, "asc"]],
				<?=$dtable_lang?>
				//processing: 	false,
				//serverSide: 	true,
				/*ajax: {
					url: "ajax/server_rego_users.php",
					type: 'POST',
					"data": function(d){
						//d.status = $('#statFilter').val();
					}
				},*/
				columnDefs: [
					//{ targets: 8, "width": '80%',},
					//{ targets: [0,1,9], "width": '1px',},
					//{ targets: [0], "orderable": false}
				],	
				/*initComplete : function( settings, json ) {
					$('#showTable').fadeIn(200);
					setTimeout(function(){
						dtable.columns.adjust().draw();
					}, 10);
				},*/
				/*"createdRow": function ( row, data, index ) {
					if(data[5].indexOf('exp') != -1){
								$(row).addClass('expired');
						}
					}*/
			});
			$("#searchFilter").keyup(function() {
				var s = $(this).val();
				dtable.search(s).draw();
			});
			$(document).on("click", "#clearSearchbox", function(e) {
				$('#searchFilter').val('');
				dtable.search('').draw();
			})
			$(document).on("change", "#statFilter", function(e) {
				dtable.ajax.reload(null, false);
			})
			$(document).on("change", "#pageLength", function(e) {
				if(this.value > 0){
					dtable.page.len( this.value ).draw();
				}
			})
			
			// EDIT USER ----------------------------------------------------------------------------------- EDIT USER
			$(document).on("click", ".editUser", function(){
				edit = 1;
				var id = $(this).data('id');
				//alert(id)
				$.ajax({
					url: ROOT+"admin/ajax/get_company_user_info.php",
					data: {id: id},
					dataType: 'json',
					success:function(data){
						//$('#dump').html(data); return false;
						$("#userImg").attr('src', ROOT+data.img);
						$('input[name="id"]').val(data.id);
						$('input[name="firstname"]').val(data.firstname);
						$('input[name="lastname"]').val(data.lastname);
						$('input[name="position"]').val(data.position);
						$('input[name="phone"]').val(data.phone);
						$('input[name="line_id"]').val(data.line_id);
						$('input[name="username"]').val(data.username);
						$('textarea[name="access"]').val(data.access);
						$('select[name="type"]').val(data.type);
						$('input[name="last"]').val(data.last);
						$('select[name="status"]').val(data.status);
						$("#status").val(data.status);

						$("#modalUser").modal('toggle');
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
			
			// DELETE USER ----------------------------------------------------------------------------------------------- 
			/*$(document).on("click", ".delUser", function(){
				last_id = $(this).data('id');
				alert(last_id)
			})
			$('.delUser').confirmation({
				container: 'body',
				singleton: true,
				animated: 'fade',
				placement: 'left',
				popout: true,
				html: true,
				title: '<div style="text-align:center"><b><?=$lng['Are you sure']?></b></div>',
				btnOkIcon: '',
				btnCancelIcon: '',
				btnOkLabel: '<?=$lng['Delete']?>',
				btnCancelLabel: '<?=$lng['Cancel']?>',
				onConfirm: function() { 
					//alert(last_id);
					$.ajax({
						url: AROOT+"ajax/delete_admin_user.php",
						data:{id: last_id},
						success: function(result){
							//$('#dump').html(result);
							//return false;
							location.reload();
						},
						error:function (xhr, ajaxOptions, thrownError){
						$("body").overhang({
							type: "error",
							message: '<i class="fa fa-exclamation-circle"></i>&nbsp;&nbsp;<?=$lng['Sorry but someting went wrong']?> <b><?=$lng['Error']?></b> : '+thrownError,
							duration: 4,
						})
						}
					});
				}
			});*/
			
			// SUBMIT USER FORM ------------------------------------------------------------------------------- SUBMIT USER FORM
			$('#userForm').submit(function(e){
				e.preventDefault();
				var err = 0;
				if($('input[name="id"]').val() == ''){err = 1};
				if($('input[name="firstname"]').val() == ''){err = 1};
				if($('input[name="lastname"]').val() == ''){err = 1};
				if($('input[name="username"]').val() == ''){err = 1};
				if($('input[name="last"]').val() == ''){err = 1};
				if($('textarea[name="access"]').val() == ''){err = 1};
				if($('select[name="type"]').val() == null){err = 1};
				if($('select[name="status"]').val() == null){err = 1};
				if(err){
					$("body").overhang({
						type: "warn",
						message: '<i class="fa fa-exclamation-triangle"></i>&nbsp;&nbsp;<?=$lng['Please fill in required fields']?>',
						duration: 4,
						closeConfirm: true
					})
					return false
				}

				var data = $(this).serialize();
				$.ajax({
					url: ROOT + "admin/ajax/save_company_user_data.php",
					type: 'POST',
					data: data,
					success: function(result){
						//$('#dump').html(result); return false;
						dtable.ajax.reload(null, false);
						$("#modalUser").modal('toggle');
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
			
			$('#modalUser').on('hidden.bs.modal', function () {
				$(this).find('form').trigger('reset');
			});
			
		})
	
	</script>















