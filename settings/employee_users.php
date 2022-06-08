<?
	
	//$emp_status['incomplete'] = $lng['in-Complete'];
	//$empStatusCount = getEmployeeStatus($cid);
	//var_dump($empStatusCount);
	//foreach($emp_status as $k=>$v){
		//$emp_status[$k] = $v.' ('.$empStatusCount[$k].')';
	//}
	//var_dump($emp_status); exit;

	//var_dump(generateStrongPassword(8, false));
	/*Your password should contain atleast one upper case, one lower case, one digit[0-9], 
   one special character[#?!@$%^&*-] and the minimum length should be 8 characters.*/

    $Empusers = array();
	$sql = "SELECT emp_id, image, ".$_SESSION['rego']['lang']."_name, personal_email, emp_status, allow_login FROM ".$cid."_employees ORDER BY emp_id";
	if($res = $dbc->query($sql)){
		while($row = $res->fetch_assoc()){ 
			$Empusers[] = $row;
		}
	}else{
		echo mysqli_error($dbc);
	}

	$Allusers = array();
	$sql1 = "SELECT username, access, sys_status FROM rego_all_users";
	if($res1 = $dbx->query($sql1)){
		while($row1 = $res1->fetch_assoc()){
			$Allusers[$row1['username']] = $row1['access'];
			$Allusers[$row1['username']]['status'] = $row1['sys_status'];
		}
	}
?>
	
	<h2><i class="fa fa-user">
		</i>&nbsp; <?=$lng['Employee users']?>
		<div style="padding:0 40px 0 0; white-space:nowrap; float:right; font-size:15px; color:#333">
			<b>URL : </b><b style="color:#c00; font-size:15px"><?=ROOT.'mob'?></b>
		</div>
	</h2>

	<div class="main">
		<div style="padding:0 0 0 20px" id="dump"></div>

		<form id="import" name="import" enctype="multipart/form-data" style="visibility:hidden; height:0; margin:0; padding:0">
			<input style="visibility:hidden" id="import_employees" type="file" name="file" />
		</form>
         
		 <div id="showTable">
			<table id="datatable" class="dataTable compact nowrap" width="100%">
				<thead>
				<tr>
					<th class="tac par30"><?=$lng['ID']?></th>
					<th data-sortable="false" style="width:1px;" class="tac vam nopad"><i class="fa fa-user fa-lg"></i></th>
					<th class="pad30"><?=$lng['Name']?></th>
					<th data-sortable="false"><?=$lng['Username']?></th>
					<th data-sortable="false"><?=$lng['Status']?></th>
					<th class="tac" data-sortable="false"><?=$lng['Access']?></th>
					<th class="" data-sortable="false"><?=$lng['Change password']?></th>
					
				</tr>
				</thead>

				<tbody>
					<? if($Empusers){  
						 foreach ($Empusers as $value) {
						 
						 	$checkAcc = $Allusers[strtolower($value['personal_email'])];
						 	$tags = explode(',' , $checkAcc);
							$num_tags = count($tags);

						 	if($value['allow_login'] == 1 && $num_tags < 2){
								if($checkAcc['status'] == 1){ $button = ''; }else{
									$button = '<button type="button" class="btn btn-sm btn-primary" value="'.$value['emp_id'].'" id="'.$value['personal_email'].'" onclick="changePassword(this.id, this.value)">Change password</button>';
								}
							}else{
								$button = '';
							}

						 	$select = '<select class="allow_login" style="min-width:100%;width:auto; background:transparent"><option';
							if($value['allow_login'] == 'N'){$select .= ' selected';}
							$select .= ' value="0">'.$lng['No'].'</option><option';
							if($value['allow_login'] == 1){$select .= ' selected';}
							$select .= ' value="1">'.$lng['Yes'].'</option></select>';

							if($value['image'] !=''){
								$imglink = '../'.$value['image'];
							}else{
								$imglink = '../../images/profile_image.jpg';
							}
					?>
						 	
						 	<tr>
						 		<td><span class="emp_id"><?=$value['emp_id']?></span></td>
						 		<td>
						 			<center>
						 				<img style="height:28px; width:28px" src="<?=$imglink.'?'.time()?>" title="" data-toggle="tooltip" data-placement="right" data-original-title="<img src=<?=$imglink.'?'.time();?> >">
						 			</center>
						 		</td>
						 		<td><?=$value[$_SESSION['rego']['lang'].'_name']?></td>
						 		<td><span class="username"><?=$value['personal_email']?></span></td>
						 		<td><span><?=$emp_status[$value['emp_status']]?></span></td>
						 		<td><?=$select?></td>
						 		<td><?=$button?></td>
						 		
						 	</tr>

					<? } } ?>
				</tbody>

			</table>
			<!-- <input type="hidden" id="incomplete" value="0" /> -->
		</div>
			
	</div>

	<!--------Change password ----------->
   <div class="modal fade show" id="passModalE" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="static" data-keyboard="false" style="display: none;" aria-modal="true">
		 <div class="modal-dialog" style="max-widt:450px">
			  <div class="modal-content">
					<div class="modal-header">
						 <h4 class="modal-title" id="myModalLabel"><i class="fa fa-lock"></i>&nbsp; <?=$lng['Edit password']?></h4>
					</div>
					<div class="modal-body" style="padding:10px 25px 25px 25px">
					<span style="font-weight:600; color:#cc0000;" id="pass_msg"></span>
					<form id="changeUserPasswordEU" class="sform" style="padding-top:10px;">
						 <label><?=$lng['Username']?></label>
						 <input name="EmpID" id="EmpID" type="hidden" value="" readonly="readonly">
						 <input name="uname" id="uname" type="text" value="" readonly="readonly">
						 <label><?=$lng['New password']?><i class="man"></i></label>
						 <input name="npass" id="npass" type="text">
						 <button class="btn btn-primary" style="margin-top:15px" type="submit"><i class="fa fa-save"></i> <?=$lng['Change password']?></button>
						 <button class="btn btn-primary" style="margin-top:15px" type="button" onclick="generatepassword();"><i class="fa fa-lock"></i> <?=$lng['Generate password']?></button>
						 <button style="float:right;margin-top:15px" type="button" class="btn btn-primary" data-dismiss="modal" onClick="window.location.reload();"><i class="fa fa-times"></i>&nbsp; <?=$lng['Cancel']?></button>
						<div class="clear"></div>
					</form>
					</div>
					<div class="clear"></div>
			  </div>
		 </div>
	</div>
   <!---------Change password ---------->

   <!-------- Show user exist popupa ------->
   <div class="modal fade show" id="USreExist" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="static" data-keyboard="false" style="display: none;" aria-modal="true">
		 <div class="modal-dialog modal-sm">
			  <div class="modal-content">
					<div class="modal-header">
						 <h4 class="modal-title" id="myModalLabel"><i class="fa fa-lock"></i>&nbsp; <?=$lng['User Exist']?></h4>
					</div>
					<div class="modal-body" style="padding:10px 25px 25px 25px">
						<span style="font-weight:600; color:#cc0000;font-size: 15px;" id="pass_msg">User exists already! an email will be sent to the user.</span>
					
						<div class="clear" style="margin-bottom: 6px;"></div>
						<button style="float:right;margin-top:15px" type="button" class="btn btn-primary btn-sm" data-dismiss="modal" onClick="window.location.reload();"><i class="fa fa-times"></i>&nbsp; <?=$lng['Continue']?></button>
					</div>
			  </div>
		 </div>
	</div>
   <!-------- Show user exist popupa ------->
	 
	
	<script type="text/javascript">
		
		//var headerCount = 1;
		var last_id = null;
		
		$(document).ready(function() {

			var dtable = $('#datatable').DataTable({
				scrollY:        false,
				scrollX:        true,
				scrollCollapse: false,
				fixedColumns:   false,
				lengthChange:  	false,
				searching: 		false,
				ordering: 		true,
				paging: 		false,
				//pageLength: 	rows,
				filter: 		false,
				info: 			false,
				//autoWidth:		false,
				<?=$dtable_lang?>
				//processing: 	false,
				//serverSide: 	true,
				//order: [0, 'desc'],
				//ajax: {url: "ajax/server_get_employee_users.php"},
				columnDefs: [
					  {"targets": [1], "class": 'pad1' },
					  //{"targets": [3,8], "class": 'tac' },
					  {"targets": [5], "class": 'nopad' },
					  {"targets": [6],"width": '60%'},
					  //{"targets": '_all',"searchable": false},
					  //{"targets": eCols,"visible": false}
				],
				
			});
			
			$(document).on("change", ".allow_login", function(e) {
				var val = this.value;
				var id = $(this).closest('tr').find('.emp_id').html();
				var username = $(this).closest('tr').find('.username').html();
				if(username == ''){
					$("body").overhang({
						type: "error",
						message: '<i class="fa fa-exclamation-triangle"></i>&nbsp;&nbsp;eMail address can not be empty.',
						duration: 2,
					})
					//dtable.ajax.reload(null, false);
					window.location.reload();
					return false;
				}

				if(val == 1){
					$.ajax({
						url: "ajax/check_email_exist.php",
						data: {email: username},
						success: function(result){
							if(result != 'userexist'){

								changePassword(username,id);
								
							}else{

								USreExist();

								$.ajax({
										url: "ajax/update_employee_user.php",
										data: {id: id, username: username, val: val},
										success: function(result){
											//$("#dump").html(result); return false;
											//dtable.ajax.reload(null, false);
											//window.location.reload();
											if(result == 'updated'){
												$("body").overhang({
													type: "success",
													message: '<i class="fa fa-exclamation-triangle"></i>&nbsp;&nbsp;This user exist already. ID and picture updated<? //=$lng['xxx']?>',
													duration: 4,
												})
												window.location.reload();
											}else if(result == 'success'){
												$("body").overhang({
													type: "success",
													message: '<i class="fa fa-check"></i>&nbsp;&nbsp;<?=$lng['Employee updated successfuly']?>',
													duration: 4,
												})
											}else{
												$("body").overhang({
													type: "error",
													message: '<i class="fa fa-exclamation-triangle"></i>&nbsp;&nbsp;<?=$lng['Error']?> : '+result,
													duration: 4,
													//closeConfirm: true
												})
												window.location.reload();
											}
										},
										error:function (xhr, ajaxOptions, thrownError){
											$("body").overhang({
												type: "error",
												message: '<i class="fa fa-exclamation-circle"></i>&nbsp;&nbsp;<?=$lng['Sorry but someting went wrong']?> <b><?=$lng['Error']?></b> : '+thrownError,
												duration: 4,
											})
											window.location.reload();
										}
									});
							}
						}
					});
				}


				if(val == 0){
					$.ajax({
						url: "ajax/update_employee_user.php",
						data: {id: id, username: username, val: val},
						success: function(result){
							//$("#dump").html(result); return false;
							//dtable.ajax.reload(null, false);
							
							if(result == 'updated'){
								$("body").overhang({
									type: "success",
									message: '<i class="fa fa-exclamation-triangle"></i>&nbsp;&nbsp;This user exist already. ID and picture updated<? //=$lng['xxx']?>',
									duration: 4,
								})
							}else if(result == 'success'){
								$("body").overhang({
									type: "success",
									message: '<i class="fa fa-check"></i>&nbsp;&nbsp;<?=$lng['Employee updated successfuly']?>',
									duration: 4,
									callback: function () {
										location.reload();
									}
								})
							}else{
								$("body").overhang({
									type: "error",
									message: '<i class="fa fa-exclamation-triangle"></i>&nbsp;&nbsp;<?=$lng['Error']?> : '+result,
									duration: 4,
									//closeConfirm: true
								})
							}

						},
						error:function (xhr, ajaxOptions, thrownError){
							$("body").overhang({
								type: "error",
								message: '<i class="fa fa-exclamation-circle"></i>&nbsp;&nbsp;<?=$lng['Sorry but someting went wrong']?> <b><?=$lng['Error']?></b> : '+thrownError,
								duration: 4,
							})
						}
					})
				}
				
			})
			
		})


		function USreExist(){
			$('#USreExist').modal('show');
		}

		function changePassword(EmpEmail,EmpID){
			if(EmpEmail !=''){
				$("#pass_msg div").remove();
				$('#passModalE input#EmpID').val(EmpID);
				$('#passModalE input#uname').val(EmpEmail);
				$('#passModalE input#npass').val(EmpEmail);
				$('#passModalE').modal('show');
			}
		}

		function generatepassword(){

			var randomPass = randomPassword(10);
			$('#passModalE input#npass').val(randomPass);
		}

		function randomPassword(length) {
		    var chars = "abcdefghijklmnopqrstuvwxyz!@#$%^&*()-+<>ABCDEFGHIJKLMNOP1234567890";
		    var pass = "";
		    for (var x = 0; x < length; x++) {
		        var i = Math.floor(Math.random() * chars.length);
		        pass += chars.charAt(i);
		    }
		    return pass;
		}


		$("#changeUserPasswordEU").submit(function(e) {
			e.preventDefault();
			var formData = $(this).serialize();
			$.ajax({
				url: "ajax/edit_emp_password.php",
				data: formData,
				success: function(response){
					//$("#dump").html(response); return false;

					if(response == 'success'){
						$("#pass_msg").html('<div class="msg_alert nomargin" style="color:#080 !important;">Password changed successfuly!</div>');
						setTimeout(function(){
							$('#passModalE').modal('toggle');
						}, 2000);

						window.location.reload();
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


	
	
	</script>























