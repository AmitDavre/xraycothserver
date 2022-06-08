<?
	//if($_SESSION['xhr']['access']['sys_users']['access'] == 0){echo '<div class="msg_nopermit">You have no permission to enter this module</div>'; exit;}
	
	$users = array();
	$sql = "SELECT * FROM rego_users WHERE user_id != '5a6effb9c34ab' ORDER by LENGTH(user_id) ASC, user_id ASC";
	$res = $dba->query($sql);
	if(mysqli_error($dba)){
		echo '<tr><td colspan="11" style="font-size:13px; color:#bb0000;">'.$lng['Error'].' : '.mysqli_error($dba).'</td></tr>';
	}else{
		if($res->num_rows > 0){
			while($row = $res->fetch_assoc()){ 
				$users[] = $row;
			}
		}
	}
	//var_dump($users); exit;
	
	$clients = array();
	$sql = "SELECT * FROM rego_customers ORDER BY clientID ASC";
	if($res = $dba->query($sql)){
		while($row = $res->fetch_assoc()){
			$clients[$row['clientID']] = $row['en_compname'];
		}
	}
	//var_dump($clients);
	
	$password = generateStrongPassword(8, false);//randomPassword();
	//var_dump($password);

?>
	<link rel="stylesheet" type="text/css" href="../assets/css/croppie_users.css?<?=time()?>" />
	
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
	

	<h2><i class="fa fa-table"></i>&nbsp;&nbsp;<?=$lng['System users']?></h2>
	<div class="main">
		<div style="padding:0 0 0 20px" id="dump"></div>
		
		<table class="dataTable hoverable selectable nowrap pad010">
			<thead>
				<tr>
					<th class="tac" style="width:1px; padding:0 5px"><i class="fa fa-image fa-lg"></i></th>
					<th class="tac" style="width:1px"><i data-toggle="tooltip" title="<?=$lng['Edit']?>" class="fa fa-pencil-square-o fa-lg"></i></th>
					<th class="tac" style="width:1px"><i data-toggle="tooltip" title="<?=$lng['Set permissions']?>" class="fa fa-cog fa-lg"></i></th>
					<th><?=$lng['Name']?></th> 
					<th><?=$lng['Position']?></th> 
					<th><?=$lng['Phone']?></th> 
					<th><?=$lng['email']?></th> 
					<th style="width:1px"><?=$lng['Status']?></th>
					<th style="width:20%"><?=$lng['Remarks']?></th>
					<th ><?=$lng['Terms & Conditions']?></th>
					<th ><?=$lng['Privacy policy']?></th>
					<th ><?=$lng['Cookie Consent']?></th>
					<th style="width:1px"><i data-toggle="tooltip" title="<?=$lng['Delete']?>" class="fa fa-trash fa-lg"></i></th>
				</tr>
			</thead>
			<tbody>
		<?
		if($users){
			foreach($users as $k=>$v){ ?>
			 <tr>
				<td class="tac" style="padding:0 !important;">
					<center><img style="height:28px; width:28px; margin:2px; cursor:default" class="img-tooltip" data-id="<?=$v['user_id']?>" title="<img src=<?=ROOT.$v['img']?> />" data-toggle="tooltip" data-placement="right" src="<?=ROOT.$v['img']?>" /></center>
				</td>
				<td class="tac"><a class="editUser" data-id="<?=$v['user_id']?>"><i class="fa fa-edit fa-lg"></i></a></td>
				<td class="tac"><a class="permissions" data-id="<?=$v['user_id']?>"><i class="fa fa-cog fa-lg"></i></a></td>
				<td><?=$v['name']?></td>
				<td><?=$v['position']?></td>
				<td><?=$v['phone']?></td>
				<td><?=$v['email']?></td>
				<td><?=$def_status[$v['status']]?></td>
				<td style="overflow:hidden; white-space:nowrap"><?=$v['remarks']?></td>
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
						<?php if ($v['e_login_cookies'] == '1'){?>

							<button  class="btn btn-primary btn-sm" type="button" > <?=$lng['Agreed'].' on '.$v['cookie_consent_date']?></button>	

						<?php }else if ($v['e_login_cookies'] == '0'){?>

							<button  class="btn btn-danger btn-sm" type="button" > <?=$lng['Not Agreed'].' on '.$v['cookie_consent_date']?></button>	
						<?php } else {?>

							<button  class="btn btn-danger btn-sm" type="button" > <?=$lng['Not Agreed']?></button>	
						<?php } ?>
					</td>
				<td style="width:1px"><a class="delUser" data-id="<?=$v['user_id']?>"><i class="fa fa-trash fa-lg"></i></a></td>
			</tr>
			<? }
		}else{
			echo '<tr><td colspan="11" style="font-size:13px;color:#b00;text-align:center;padding:5px 10px !important">'.$lng['No data available in Database'].'</td></tr>';
		} ?>  
			</tbody>
		</table>
		<? if($_SESSION['RGadmin']['access']['users']['add'] == 1){ ?>
		<button style="margin-top:10px;" type="button" class="btn btn-primary" id="add_user"><i class="fa fa-plus"></i>&nbsp; <?=$lng['Add new user']?></button>
		<? } ?>
		<div class="clear" style="height:20px"></div>

      <form id="accessForm" style="position:relative">
         <fieldset disabled>
         
					<table class="basicTable" style="margin-top:0px; width:100%; table-layout:auto">
            <thead>
               <tr>
                  <th class="tal" colspan="15" style="padding:4px; vertical-align: bottom !important; font-size:18px">
                     <table border="0" style="width:100%;">
                        <tr>
                           <td style="padding:4px"><img id="permImg" style="height:50px; display:inline-block;" src="../images/profile_image.jpg" /></td>
                           <td>
										<span style="display:inline-block; padding-top:20px"><?=$lng['Set permissions for']?> : <span style="color:#a00" id="sysUser"></span></span>
									</td>
                           <td style="width:90%; padding-top:20px"><label><input id="selAll" disabled type="checkbox" class="checkbox style-0"><span> <?=$lng['Select all']?></span></label></td>
									<td style="vertical-align:bottom">
									<? if($_SESSION['RGadmin']['access']['users']['edit'] == 1 || $_SESSION['RGadmin']['access']['users']['add'] == 1){ ?>
									<button type="submit" class="btn btn-primary" id="save_settings"><i class="fa fa-save"></i>&nbsp; <?=$lng['Update permissions']?></button>
									<? } ?>
									</td>
                        </tr>
                     </table>
                  </th> 
               </tr>
            </thead>
            <tbody>
							<tr>
								<th class="tal"><?=$lng['Customers Setup']?></th>
								<td>
									<input name="customer[access]" type="hidden" value="0"  />
									<label><input disabled type="checkbox" name="customer[access]" value="1" class="cUser checkbox style-0"><span><?=$lng['Access']?></span></label>
								</td>
								<td>
									<input name="customer[add]" type="hidden" value="0"  />
									<label><input disabled type="checkbox" name="customer[add]" value="1" class="cClient checkbox style-0"><span><?=$lng['Add new']?></span></label>
								</td>
								<td>
									<input name="customer[edit]" type="hidden" value="0"  />
									<label><input disabled type="checkbox" name="customer[edit]" value="1" class="cClient checkbox style-0"><span><?=$lng['Edit']?></span></label>
								</td>
								<td>
									<input name="customer[price]" type="hidden" value="0"  />
									<label><input disabled type="checkbox" name="customer[delete]" value="1" class="cClient checkbox style-0"><span><?=$lng['Price settings']?></span></label>
								</td>
								<td colspan="20" style="width:80%"></td>
							</tr>
									 
							<tr>
								<th class="tal"><?=$lng['System users']?></th>
								<td>
									<input name="users[access]" type="hidden" value="0"  />
									<label><input disabled type="checkbox" name="users[access]" value="1" class="cUser checkbox style-0"><span><?=$lng['Access']?></span></label>
								</td>
								<td>
									<input name="users[add]" type="hidden" value="0"  />
									<label><input disabled type="checkbox" name="users[add]" value="1" class="cUser checkbox style-0"><span><?=$lng['Add new']?></span></label>
								</td>
								<td>
									<input name="users[edit]" type="hidden" value="0"  />
									<label><input disabled type="checkbox" name="users[edit]" value="1" class="cUser checkbox style-0"><span><?=$lng['Edit']?></span></label>
								</td>
								<td>
									<input name="users[delete]" type="hidden" value="0"  />
									<label><input disabled type="checkbox" name="users[delete]" value="1" class="cUser checkbox style-0"><span><?=$lng['Delete']?></span></label>
								</td>
								<td colspan="10"></td>
							</tr>
									 
							<tr>
								<th class="tal"><?=$lng['Default settings']?></th>
								<td>
									<input name="def_settings[access]" type="hidden" value="0"  />
									<label><input disabled type="checkbox" name="def_settings[access]" value="1" class="cUser checkbox style-0"><span><?=$lng['Access']?></span></label>
								</td>
								<td>
									<input name="def_settings[edit]" type="hidden" value="0"  />
									<label><input disabled type="checkbox" name="def_settings[edit]" value="1" class="cUser checkbox style-0"><span><?=$lng['Edit']?></span></label>
								</td>
								<td>
									<input name="def_settings[add]" type="hidden" value="0"  />
									<label><input disabled type="checkbox" name="def_settings[add]" value="1" class="cUser checkbox style-0"><span><?=$lng['Add new']?></span></label>
								</td>
								<td>
									<input name="def_settings[delete]" type="hidden" value="0"  />
									<label><input disabled type="checkbox" name="def_settings[delete]" value="1" class="cUser checkbox style-0"><span><?=$lng['Delete']?></span></label>
								</td>
								<td colspan="9"></td>
							</tr>
									 
							<tr>
								<th class="tal"><?=$lng['Company settings']?></th>
								<td>
									<input name="comp_settings[access]" type="hidden" value="0"  />
									<label><input disabled type="checkbox" name="comp_settings[access]" value="1" class="cUser checkbox style-0"><span><?=$lng['Access']?></span></label>
								</td>
								<td>
									<input name="comp_settings[edit]" type="hidden" value="0"  />
									<label><input disabled type="checkbox" name="comp_settings[edit]" value="1" class="cUser checkbox style-0"><span><?=$lng['Edit']?></span></label>
								</td>
								<td colspan="11"></td>
							</tr>
									 
							<tr>
								<th class="tal"><?=$lng['Support desk']?></th>
								<td>
									<input name="support[access]" type="hidden" value="0"  />
									<label><input disabled type="checkbox" name="support[access]" value="1" class="cUser checkbox style-0"><span><?=$lng['Access']?></span></label>
								</td>
								<td>
									<input name="support[gen]" type="hidden" value="0"  />
									<label><input disabled type="checkbox" name="support[gen]" value="1" class="cUser checkbox style-0"><span><?=$lng['General']?></span></label>
								</td>
								<td>
									<input name="support[con]" type="hidden" value="0"  />
									<label><input disabled type="checkbox" name="support[con]" value="1" class="cUser checkbox style-0"><span><?=$lng['Confidential']?></span></label>
								</td>
								<td>
									<input name="support[bug]" type="hidden" value="0"  />
									<label><input disabled type="checkbox" name="support[bug]" value="1" class="cUser checkbox style-0"><span><?=$lng['Bug report']?></span></label>
								</td>
								<td colspan="9"></td>
							</tr>
							
							<!--<tr>
								<th class="tal"><?=$lng['Billing']?></th>
								<td>
									<input name="billing[access]" type="hidden" value="0"  />
									<label><input disabled type="checkbox" name="billing[access]" value="1" class="cUser checkbox style-0"><span><?=$lng['Access']?></span></label>
								</td>
								<td></td>
								<td></td>
								<td colspan="10"></td>
							</tr>-->
									 
							<tr>
								<th class="tal"><?=$lng['Help']?> / <?=$lng['Welcome']?> / <?=$lng['Promo']?></th>
								<td>
									<input name="help[access]" type="hidden" value="0"  />
									<label><input disabled type="checkbox" name="help[access]" value="1" class="cUser checkbox style-0"><span><?=$lng['Access']?></span></label>
								</td>
								<td></td>
								<td></td>
								<td colspan="10"></td>
							</tr>
									 
							<tr>
								<th class="tal"><?=$lng['Price table']?></th>
								<td>
									<input name="price[access]" type="hidden" value="0"  />
									<label><input disabled type="checkbox" name="price[access]" value="1" class="cUser checkbox style-0"><span><?=$lng['Access']?></span></label>
								</td>
								<td></td>
								<td></td>
								<td colspan="10"></td>
							</tr>
		
							<tr>
								<th class="tal"><?=$lng['Terms']?> / <?=$lng['Privacy']?> <? //=$lng['Price table']?></th>
								<td>
									<input name="privacy[access]" type="hidden" value="0"  />
									<label><input disabled type="checkbox" name="privacy[access]" value="1" class="cUser checkbox style-0"><span><?=$lng['Access']?></span></label>
								</td>
								<td></td>
								<td></td>
								<td colspan="10"></td>
							</tr>				
							<tr>
								<th class="tal"><?=$lng['Logdata']?><? //=$lng['Price table']?></th>
								<td>
									<input name="logdata[access]" type="hidden" value="0"  />
									<label><input disabled type="checkbox" name="logdata[access]" value="1" class="cUser checkbox style-0"><span><?=$lng['Access']?></span></label>
								</td>
								<td></td>
								<td></td>
								<td colspan="10"></td>
							</tr>
		
							<tr>
								<th class="tal"><?=$lng['Language list']?></th>
								<td>
									<input name="language[access]" type="hidden" value="0"  />
									<label><input disabled type="checkbox" name="language[access]" value="1" class="cUser checkbox style-0"><span><?=$lng['Access']?></span></label>
								</td>
								<td></td>
								<td></td>
								<td colspan="10"></td>
							</tr>
		
							<tr>
								<th class="tal"><?=$lng['Agents']?></th>
								<td>
									<input name="agents[access]" type="hidden" value="0"  />
									<label><input disabled type="checkbox" name="agents[access]" value="1" class="cUser checkbox style-0"><span><?=$lng['Access']?></span></label>
								</td>
								<td></td>
								<td></td>
								<td colspan="10"></td>
							</tr>
            </tbody>
         	</table>	
		 
		 	</fieldset>
      </form>

   </div>
   
	<!-- Modal Add / Edit Users -->
	<div class="modal fade" id="modalUser" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false">
		<div class="modal-dialog" role="document" style="max-width:650px">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title"><i class="fa fa-user"></i>&nbsp;&nbsp;Admin system user<? //=$lng['Add new user']?> <span id="aUser"></h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
					<div class="modal-body" style="padding:15px 20px 20px">
					<span style="font-weight:600; color:#cc0000; display:none; display:block; margin:-5px 0 5px 0" id="userMess"></span>
					<form id="userForm">
						<input type="hidden" name="user_id" id="user_id" />
						<input type="hidden" name="img" id="img" value="../../images/profile_image.jpg" />
						<!--<input type="hidden" name="prev_password" id="prev_password" />-->
						<table border="0" style="width:100%">
						  <tr>
						  <td class="vat" style="padding:0 15px 0 0; border-right:1px #eee solid; width:80%">
								
								<table class="basicTable inputs" border="0">
									<tbody>
									<tr>
										<th><i class="man"></i><?=$lng['Name']?></th>
										<td><input placeholder="__" name="name" id="name" type="text" value="" /></td>
									</tr>
									<tr>
										<th><?=$lng['Position']?></th>
										<td><input placeholder="__" name="position" id="position" type="text" value="" /></td>
									</tr>
									<tr>
										<th><i class="man"></i><?=$lng['Username']?></th>
										<td><input placeholder="__" class="nofocus" name="username" id="username" type="text" value="" /></td>
									</tr>
									<tr class="hideRow">
										<th><i class="man"></i><?=$lng['Password']?></th>
										<td><input autocomplete="off" name="password" id="password" type="text" value="<?=$password?>" /></td>
									</tr>
									<tr>
										<th><?=$lng['Phone']?></th>
										<td><input placeholder="__" name="phone" id="phone" type="text" value="" /></td>
									</tr>
									<tr>
										<th><i class="man"></i><?=$lng['email']?></th>
										<td><input placeholder="__" name="email" id="email" type="text" value="" /></td>
									</tr>
									<tr style="display:none">
										<th>Assign clients</th>
										<td style="padding:1px 0 0 4px">
											<select multiple="multiple" name="clients" id="selCustomers" >
											<? foreach($clients as $k=>$v){
													echo '<option value="'.$k.'">'.$v.'</option>';
											} ?>
											</select>
										</td>
									</tr>
									<tr>
										<th><i class="man"></i><?=$lng['Status']?></th>
										<td colspan="2">
											<select name="status" id="status" style="width:100%">
												<option selected disabled><?=$lng['Select']?></option>
												<? foreach($def_status as $k=>$v){ ?>
													<option value="<?=$k?>"><?=$v?></option>
												<? } ?>
											</select>
										</td>
									</tr>
									<tr>
										<th><?=$lng['Remarks']?></th>
										<td><textarea placeholder="__" rows="3" name="remarks" id="remarks"></textarea></td>
									</tr>
									</tbody>
								</table>
								
								</td>
						  	<td class="vat" style="width:1%; padding:5px 0 0 15px;">
									<div id="upload-demo" style="margin:0 0 2px;"></div>
									<input style="height:0; visibility:hidden" id="selectUserImg" type="file" name="user_img" />
									<? if($_SESSION['RGadmin']['access']['users']['add'] == 1 || $_SESSION['RGadmin']['access']['users']['edit'] == 1){ ?>
									<button onclick="$('#selectUserImg').click();" type="button" id="userBut" style="width:100%; margin:0" class="btn btn-primary btn-xs"><i class="fa fa-user"></i>&nbsp;&nbsp;<?=$lng['Select picture']?></button>
									<? } ?>
											
								</td>
						  </tr>
						  </table>
					
						<div class="clear" style="height:15px"></div>
						<? if($_SESSION['RGadmin']['access']['users']['add'] == 1 || $_SESSION['RGadmin']['access']['users']['edit'] == 1){ ?>
						<button id="saveUser" class="btn btn-primary" style="margin-right:5px; float:left" type="button"><i class="fa fa-save"></i>&nbsp; <?=$lng['Update']?></button>
						<? } ?>
						<button class="btn btn-primary" style="float:right" type="button" data-dismiss="modal"><i class="fa fa-times"></i>&nbsp; <?=$lng['Cancel']?></button>
						<div class="clear"></div>

					</form>
					</div>
			  </div>
		 </div>
	</div>
	 
	 
	<!-- PAGE RELATED PLUGIN(S) -->
	<script type='text/javascript' src="../assets/js/croppie.js?<?=time()?>"></script>
	<script type="text/javascript" src="../assets/js/jquery.autocomplete.js"></script>
	<script type="text/javascript" src="../assets/js/jquery.sumoselect.min.js"></script>

	<script type="text/javascript">
		
		//var heights = window.innerHeight-260;
		//var headerCount = 1;
		//var scrY = heights;//true;
		
		$(document).ready(function() {
			//alert('ready')
			
			var sedit = 1;//<?//=json_encode($_SESSION['xhr']['access']['sys_users']['edit'])?>;
			var edit = 0;
			var user_id;
		
			// SHOW ACCESS FORM ------------------------------------------------------------------------------------------- SHOW ACCESS FORM
			$(document).on("click", "#selAll", function(e){
				$('input[type="checkbox"]').prop('checked', this.checked);
			})
			$(document).on("click", ".permissions", function(e){
				e.preventDefault();
				user_id = $(this).data('id');
				$('input[type="checkbox"]').prop('checked',false);
				//$('input[type="checkbox"]').prop('disabled',true);
				//alert(id);
				$.ajax({
					url: "ajax/get_admin_access_data.php",
					dataType: 'json',
					data: {id: user_id},
					success:function(data){
						//alert(ROOT+data.img);
						$("#sysUser").html(data.name);
						$("#permImg").prop('src', ROOT+data.img);
						if(sedit == 1){
							$('#save_settings').css('display','block')
							$('#save_settings').prop('disabled',false)
							$('#accessForm fieldset').prop('disabled',false)
							$('input[type="checkbox"]').prop('disabled',false);
						}
						$('input[type="checkbox"]').prop('checked',false);
						$.each(data.access, function (key, val) {
							$.each(val, function (k, v) {
								//alert(key+'-'+k+'-'+v)
								if(v == 1){$('input[name="'+key+'['+k+']"]').prop('checked',true)}
							});
						});
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
			
			// SUBMIT ACCESS FORM --------------------------------------------------------------------------------------- SUBMIT ACCESS FORM
			$(document).on('submit','form#accessForm', function(e){
				e.preventDefault();
				var data = $(this).serialize();
				data += '&user_id='+user_id;
				//data += '&user_name='+emp_info[user_id];
				//alert(data);
				$.ajax({
					url: "ajax/save_admin_user_permissions.php",
					type: 'POST',
					data: data,
					success: function(result){
						//$('#dump').html(result);
						if(result == 'ok'){
							$("body").overhang({
								type: "success",
								message: '<i class="fa fa-check"></i>&nbsp;&nbsp;<?=$lng['Permissions updated successfuly']?>',
								duration: 2,
							})
						}else{
							$("body").overhang({
								type: "warn",
								message: '<i class="fa fa-exclamation-triangle"></i>&nbsp;&nbsp;<?=$lng['Error']?> : '+result,
								duration: 4,
								closeConfirm: true
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
				});
				return false;
					
			});
			
			var $uploadCrop;
			var maxSize = 2000;
			var minHeight = 130;
			var minWidth = 130;
			$uploadCrop = $('#upload-demo').croppie({
				viewport: {
					width: 130,
					height: 130,
					type: 'square'
				},
				boundary: {
					width: 160,
					height: 160
				},
				mouseWheelZoom: true,
				showZoom: true
			});

			$('#selectUserImg').on('change', function () { 
				$("#userBut i").removeClass('fa-user').addClass('fa-repeat fa-spin');
				readFile(this);
			});
			
			function readFile(input) {
				if(input.files && input.files[0]) {
					var file = input.files[0];
					var reader = new FileReader();
					reader.onload = function (e) {
						var img = new Image;
						var t = file.type.split('/')[1];
						var n = file.name;
						var s = ~~(file.size/1024);
						var msg = "";
						if(s > maxSize){msg = '<?=$lng['Filesize is to bigg']?> ('+(s/1024).format(2)+' Mb) - Max. '+(maxSize/1000)+' Mb';}
						if(t != 'jpeg' && t != 'png' && t != 'jpg'){msg = "<?=$lng['Please use only ... files']?>";}
						if(msg!=''){
							$("body").overhang({
								type: "warn",
								message: '<i class="fa fa-exclamation-triangle"></i>&nbsp;&nbsp;<?=$lng['Error']?> : '+msg,
								duration: 4,
								closeConfirm: true
							})
							$("p#selpic i").removeClass('fa-repeat fa-spin').addClass('fa-user');
							$("#upload").val('');
							return false;
						}
						img.src = e.target.result;
						img.onload = function() {
							$("#userBut i").removeClass('fa-repeat fa-spin').addClass('fa-user');
						};
						//$('#klik').html('');
						$('#orig').attr('src', e.target.result);
						$uploadCrop.croppie('bind', {
							url: e.target.result
						});
						$('.upload-demo').addClass('ready');
						$("#message").fadeOut(400);
					}
					reader.readAsDataURL(input.files[0]);
				}else{
				  swal("Sorry - you're browser doesn't support the FileReader API");
				}
			};
			
			// SAVE USER check_admin_username ------------------------------------------------------------------------------------ SAVE USER
			$('#saveUser').on('click', function () { 
				if(edit == 1){$('form#userForm').submit(); return false;}
				var username = $('#username').val();
				//alert('saveUser')
				$.ajax({
					url: "ajax/check_admin_username.php",
					data: {username: username},
					success:function(result){
						//$('#dump').html(result);
						if(result == 'exist'){
							$("body").overhang({
								type: "warn",
								message: '<i class="fa fa-exclamation-triangle"></i>&nbsp;&nbsp;<?=$lng['This username exist already']?>',
								duration: 4,
								closeConfirm: true
							})
							return false;
						}
						if(result == 'ok'){
							$('#userMess').html('').hide();
							$('form#userForm').submit();						
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
			
			// SUBMIT USER FORM ------------------------------------------------------------------------------- SUBMIT USER FORM
			$(document).on('submit','form#userForm', function(e){
				e.preventDefault();
				//alert(edit)
				//if(checkPassword($('#password').val()) != ''){
					//$('#userMess').html('<div class="msg_alert"><b>Weak password !</b><br>'+checkPassword($('#password').val())+'</div>').hide().fadeIn(400); return false;
				//}
				var err = true;
				//if($('#user_id').val()==''){err = false};
				if(edit == 0 && $('#username').val()==''){err = false};
				if(edit == 0 && $('#password').val()==''){err = false};
				if($('#name').val()==''){err = false};
				//if($('#phone').val()==''){err = false};
				if($('#email').val()==''){err = false};
				//if($('#selCustomers').val()==null){err = false};
				if($('#status').val()==null){err = false};
				//var err = true;
				if(err==false){
					$("body").overhang({
						type: "warn",
						message: '<i class="fa fa-exclamation-triangle"></i>&nbsp;&nbsp;<?=$lng['Please fill in required fields']?>',
						duration: 4,
						closeConfirm: true
					})
					return false
				}
				if(edit == 0 && $('#password').val().length < 8){
					$("body").overhang({
						type: "warn",
						message: '<i class="fa fa-exclamation-triangle"></i>&nbsp;&nbsp;<?=$lng['Password to short min 8 characters']?>',
						duration: 4,
						closeConfirm: true
					})
					return false
				};

				var data = new FormData($(this)[0]);
				//if(edit == 0){
					//data.append('access', '<?//=serialize(array())?>');
				//}
				//var data = $(this).serialize();
				var file = $('#selectUserImg')[0].files[0];
				if(!file){
					$.ajax({
						url: "ajax/save_admin_user_data.php",
						type: 'POST',
						data: data,
						async: false,
						cache: false,
						contentType: false,
						processData: false,
						success: function(result){
							//$('#dump').html(result);
							$("#modalUser").modal('toggle');
							setTimeout(function(){location.reload();},400);
						},
						error:function (xhr, ajaxOptions, thrownError){
							$("body").overhang({
								type: "error",
								message: '<i class="fa fa-exclamation-circle"></i>&nbsp;&nbsp;<?=$lng['Sorry but someting went wrong']?> <b><?=$lng['Error']?></b> : '+thrownError,
								duration: 4,
							})
						}
					});
					return false;
				}
				var reader = new FileReader();
				reader.readAsDataURL(file);
				reader.onload = function (e) {
					var img = new Image;
					img.src = e.target.result;
					img.onload = function() {
					$uploadCrop.croppie('result', {
						type: 'canvas',
						size: 'viewport'
					}).then(function (resp) {
						data.append('img_data', resp); 
						$.ajax({
							url: "ajax/save_admin_user_data.php",
							type: 'POST',
							data: data,
							async: false,
							cache: false,
							contentType: false,
							processData: false,
							success: function(result){
								//$('#dump').html(result);
								$("#modalUser").modal('toggle');
								setTimeout(function(){location.reload();},400);
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
					};
				}
			});
			$('#modalUser').on('hidden.bs.modal', function () {
				$(this).find('form').trigger('reset');
				$('#userMess').html('');
				$('#myModalLabel').html('<i class="fa fa-user"></i>&nbsp;&nbsp;<?=$lng['Add new user']?>');
				$('#upload-demo').css('background-image', 'url(../../images/profile_image.jpg)');
				$('#upload-demo').removeClass('ready');
				$uploadCrop.croppie('bind', {url : ''})
				$('.hideRow').css('display','table-row');
				$("#username").prop('disabled', false);
				$("#password").prop('disabled', false);
				//$('#selCustomers')[0].sumo.reload();
				edit = 0;
				//$("#adminMess").html('');
			});
			
			// ADD USER ----------------------------------------------------------------------------------------------------------- ADD USER
			$(document).on("click", "#add_user", function(){
				edit = 0;
				$('.hideRow').css('display','row');
				//$("#username").prop('disabled', false);
				$('#myModalLabel').html('<i class="fa fa-user"></i>&nbsp;&nbsp;<?=$lng['Add new user']?> <span id="aUser"></span>');
				$.ajax({
					url: "ajax/get_admin_user_ID.php",
					success:function(data){
						//$('#dump').html(data);
						$("#user_id").val(data);
					},
					error:function (xhr, ajaxOptions, thrownError){
						$("body").overhang({
							type: "error",
							message: '<i class="fa fa-exclamation-circle"></i>&nbsp;&nbsp;<?=$lng['Sorry but someting went wrong']?> <b><?=$lng['Error']?></b> : '+thrownError,
							duration: 4,
						})
					}
				});
				$("#modalUser").modal('toggle');
			})
			
			// EDIT USER DATA ----------------------------------------------------------------------------------------------- EDIT USER DATA
			$(document).on("click", ".editUser", function(){
				edit = 1;
				var id = $(this).data('id');
				//alert(id)
				$.ajax({
					url: "ajax/get_admin_user_info.php",
					data: {id: id},
					dataType: 'json',
					success:function(data){
						//$('#dump').html(result);
						$('.hideRow').css('display','none');
						$("#username").prop('disabled', true);
						$("#img").val(data.img);
						$("#password").prop('disabled', true);
						$('#myModalLabel').html('<i class="fa fa-user"></i>&nbsp;&nbsp;<?=$lng['Edit user']?>');
						$('#upload-demo').css('background-image', 'url('+ROOT+data.img+'?<?=time()?>)');
						$("#user_id").val(data.user_id);
						$("#name").val(data.name);
						$("#username").val(data.username);
						$("#position").val(data.position);
						$.each(data.clients, function(i, item){
							$('#selCustomers')[0].sumo.selectItem(item);
						})
						$("#phone").val(data.phone);
						$("#email").val(data.email);
						$("#status").val(data.status);
						$("#remarks").val(data.remarks);

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
			$('.delUser').confirmation({
				container: 'body',
				rootSelector: '.delUser',
				singleton: true,
				animated: 'fade',
				placement: 'left',
				popout: true,
				html: true,
				title 			: '<?=$lng['Are you sure']?>',
				btnOkClass 		: 'btn btn-danger',
				btnOkLabel 		: '<?=$lng['Delete']?>',
				btnCancelClass 	: 'btn btn-success',
				btnCancelLabel 	: '<?=$lng['Cancel']?>',
				onConfirm: function() { 
					var last_id = $(this).data('id');
					$.ajax({
						url: "ajax/delete_admin_user.php",
						data:{id: last_id},
						success: function(result){
							//$('#dump').html(result);
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
			});
			
		})
	
	</script>















