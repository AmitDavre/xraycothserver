<?

	$user = array();
	$res = $dbx->query("SELECT * FROM rego_company_users WHERE username = '".$_SESSION['rego']['username']."'");
	if($row = $res->fetch_assoc()){
		$user = $row;
	}
	$tmp = explode(',', $user['access']);
	
	$companies = array();
	$res = $dbx->query("SELECT * FROM rego_customers ORDER BY clientID ASC");
	while($row = $res->fetch_assoc()){
		if(in_array($row['clientID'], $tmp)){
			$companies[$row['clientID']]['company'] = $row['en_compname'];
			$companies[$row['clientID']]['subscriber'] = $row['name'];
			$companies[$row['clientID']]['version'] = $row['version'];
			$companies[$row['clientID']]['status'] = $row['status'];
			$companies[$row['clientID']]['period_start'] = $row['period_start'];
			$companies[$row['clientID']]['period_end'] = $row['period_end'];
			$companies[$row['clientID']]['employees'] = $row['employees'];
		}
	}
	//var_dump($user);
	//var_dump($companies);
	//exit;

?>	
<style>
 input, select {
	padding:0px 10px 0 10px !important;
 }
 input:disabled, select:disabled {
 	color:#999;
	padding:1px 10px 0 10px !important;
 }
</style>

<!-- MY PROFILE FORM /////////////////////////////////////////////////////////////////////////////-->
<div id="mr_profile" style="border:0px solid red; position:absolute; top:90px; left:0; right:0; bottom:5px">

	<div style="width:25%; height:100%; float:left">
		<div style="height:calc(100% - 36px); overflow-y:auto; padding:15px">
			<form id="profileForm">
				<fieldset disabled>
				<input name="id" type="hidden" value="<?=$user['id']?>">
				<input name="prev_username" type="hidden" value="<?=$user['username']?>">
				<table class="basicTable inputs" border="0">
					<thead>
						<tr style="line-height:110%">
							<th colspan="4">
								<?=$lng['My profile']?> <a id="editProfile" style="float:right"><i class="fa fa-edit fa-lg"></i></a>
							</th>
						</tr>
					</thead>
					<tbody>
					<tr>
						<th><i class="man"></i><?=$lng['First name']?> :</th>
						<td><input type="text" name="firstname" value="<?=$user['firstname']?>"></td>
					</tr>
					<tr>
						<th><i class="man"></i><?=$lng['Last name']?> :</th>
						<td><input type="text" name="lastname" value="<?=$user['lastname']?>"></td>
					</tr>
					<tr>
						<th><?=$lng['Position']?> :</th>
						<td><input type="text" name="position" value="<?=$user['position']?>"></td>
					</tr>
					<tr>
						<th><?=$lng['Phone']?> :</th>
						<td><input type="text" name="phone" value="<?=$user['phone']?>"></td>
					</tr>
					<tr>
						<th><?=$lng['Line ID']?> :</th>
						<td><input type="text" name="line_id" value="<?=$user['line_id']?>"></td>
					</tr>
					<tr>
						<th><i class="man"></i><?=$lng['Username']?> :</th>
						<td><input type="text" name="username" value="<?=$user['username']?>"></td>
					</tr>
					</tbody>
				</table>
				<button id="updateBtn" style="margin-top:10px" class="btn btn-primary" type="submit"><i class="fa fa-save"></i>&nbsp; <?=$lng['Update']?></button>
				</fieldset>
			</form>
		</div>
	</div>

	<div style="width:75%; height:100%; background:#fff; float:right; border-left:1px solid #ccc">
		<div style="height:calc(100% - 36px); overflow-y:auto; padding:15px">
			<div id="dump2"></div>
			<table class="basicTable" border="0">
				<thead>
					<tr style="line-height:110%">
						<th colspan="3" class="tac"><?=$lng['Access overview']?></th>
						<th colspan="5" class="tac"><?=$lng['Subscription']?></th>
					</tr>
					<tr style="line-height:110%">
						<th><?=$lng['Rego ID']?></th>
						<th><?=$lng['Company']?></th>
						<th><?=$lng['Subscriber']?></th>
						<th><?=$lng['Current']?></th>
						<th><?=$lng['Status']?></th>
						<th><?=$lng['Period start']?></th>
						<th><?=$lng['Period end']?></th>
						<th><?=$lng['Max employees']?></th>
					</tr>
				</thead>
				<tbody>
				<? foreach($companies as $k=>$v){ ?>
					<tr>
						<td><a data-cid="<?=$k?>" class="changeCustomer"><b><?=substr(strtoupper($k), 0, 4).'&nbsp;'.substr($k, 4)?></b></a></td>
						<td><?=$v['company']?></td>
						<td><?=$v['subscriber']?></td>
						<td><?=$version[$v['version']]?></td>
						<td><?=$client_status[$v['status']]?></td>
						<td><?=$v['period_start']?></td>
						<td><?=$v['period_end']?></td>
						<td><?=$v['employees']?></td>
					</tr>
				<? } ?>
				</tbody>
			</table>
		</div>
	</div>

</div>

<script type="text/javascript">
	
	$(document).ready(function() {
		
		$('#editProfile').on('click', function(){
			$('#profileForm fieldset').prop('disabled', function(i, v){ return !v; });
			
		})

		$("#profileForm").submit(function (e) {
			e.preventDefault();
			var err = 0;
			if($('input[name="firstname"]').val() == ''){err = 1;}
			if($('input[name="lastname"]').val() == ''){err = 1;}
			if($('input[name="username"]').val() == ''){err = 1;}
			if(err){
				$("body").overhang({
					type: "warn",
					message: '<i class="fa fa-exclamation-triangle"></i>&nbsp;&nbsp;<?=$lng['Please fill in required fields']?>',
					duration: 4,
					closeConfirm: true
				})
				setTimeout(function(){$("#submitbtn i").removeClass('fa-refresh fa-spin').addClass('fa-save');},500);
				return false;
			}
			
			var formData = $(this).serialize();
			$.ajax({
				url: ROOT+"myrego/ajax/update_my_profile.php",
				data: formData,
				success: function(response){
					//$('#dump2').html(response); return false;
					if(response == 'success'){
						$("body").overhang({
							type: "success",
							message: '<i class="fa fa-check"></i>&nbsp;&nbsp;<?=$lng['Data updated successfuly']?>',
							duration: 2,
							closeConfirm: true
						})
						$('#profileForm fieldset').prop('disabled', true);
					}else if(response == 'exist'){
						$("body").overhang({
							type: "warn",
							message: '<i class="fa fa-exclamation-triangle"></i>&nbsp;&nbsp;This username exist already.<? //=$lng['xxx']?>',
							duration: 4,
							closeConfirm: true
						})
					}else{
						$("body").overhang({
							type: "warn",
							message: '<i class="fa fa-exclamation-triangle"></i>&nbsp;&nbsp;<?=$lng['Error']?> : '+response,
							duration: 4,
							closeConfirm: true
						})
					}
				},
				error:function (xhr, ajaxOptions, thrownError){
					$("body").overhang({
						type: "error",
						message: '<i class="fa fa-exclamation-triangle"></i>&nbsp;&nbsp;<?=$lng['Sorry but someting went wrong']?> <b><?=$lng['Error']?></b> : '+thrownError,
						duration: 8,
						closeConfirm: "true",
					})
				}
			});
		});
		
		
	});
	
</script>

















