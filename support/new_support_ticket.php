<?php

	//$recipients = getXrayUsers($dbx);
	//var_dump($recipients);

?>

<style>

</style>
		
	<h2><i class="fa fa-life-ring"></i>&nbsp; <?=$lng['New support ticket']?></h2>
	<div class="main">
		<div id="dump"></div>
		
			<form id="ticketForm" enctype="multipart/form-data">
				<input type="hidden" name="cid" value="<?=$_SESSION['rego']['cid']?>">
				<input type="hidden" name="customer" value="<?=$compinfo[$lang.'_compname']?>">
				<input type="hidden" name="name" value="<?=$_SESSION['rego']['name']?>">
				<input type="hidden" name="email" value="<?=$_SESSION['rego']['username']?>">
				
				<table class="basicTable" border="0" style="width:100%; margin-bottom:10px">
					<thead>
						<tr>
							<th colspan="2"><?=$lng['Create new ticket']?></th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<th><?=$lng['Applicant']?></th>
							<td><?=$_SESSION['rego']['name']?></td>
						</tr>
						<tr>
							<th><i class="man"></i><?=$lng['Type']?></th>
							<td class="nopadding">
								<select name="type" id="type" style="width:100%">
									<!--<option disabled selected value="">Select</option>-->
									<? foreach($sd_type as $k=>$v){ ?>
									<option value="<?=$k?>"><?=$v?></option>
									<? } ?>
								</select>
							</td>
						</tr>
						<tr>
							<th><?=$lng['Priority']?></th>
							<td class="nopadding">
								<select name="priority" style="width:100%">
									<option value="0"><?=$lng['Low']?></option>
									<option value="1"><?=$lng['Medium']?></option>
									<option value="2"><?=$lng['High']?></option>
								</select>
							</td>
						</tr>
						<tr>
							<th><i class="man"></i><?=$lng['Subject']?></th>
							<td class="nopadding"><input type="text" name="subject" id="subject" value=""></td>
						</tr>
						<tr>
							<th><i class="man"></i><?=$lng['Message']?></th>
							<td class="nopadding">
								<textarea name="message" id="message" style="width:100%; margin:0; border:0 !important; resize:vertical" rows="10"></textarea>
							</td>
						</tr>
						<tr>
							<th style="vertical-align:middle"><?=$lng['Attachments']?></th>
							<td id="sdAttachments" class="vatop" style="padding:5px 8px 2px 8px; white-space:normal">
								<input style="margin:0 4px 3px 0; display:inline-block" class="reply_attach" name="attachments[]" type="file" />
							</td>
						</tr>
					</tbody>
				</table>
				<button id="btnSubmit" type="submit" class="btn btn-primary btn-fl"><i class="fa fa-paper-plane"></i> &nbsp;<?=$lng['Submit ticket']?></button>
			</form>
			
				
	</div>
	
	
	<!-- PAGE RELATED PLUGIN(S) -->
	
	<script type="text/javascript">
		
		$(document).ready(function() {
			
			$(document).on("change", ".reply_attach", function(e){
				$('<input style="margin:0 4px 3px 0; display:inline-block" class="reply_attach" name="attachments[]" type="file" />').insertAfter(this);
			})
			
			$("#ticketForm").on('submit', function(e){
				e.preventDefault();
				//alert($("#message").val())
				var msg = 0;
				if($("#type").val() == null){msg = 1;}
				if($.trim($("#subject").val()) == ''){msg = 1;}
				if($.trim($("#message").val()) == ''){msg = 1;}
				if(msg > 0){
					$("body").overhang({
						type: "error",
						message: '<i class="fa fa-exclamation-triangle"></i>&nbsp;&nbsp;<?=$lng['Please fill in required fields']?>',
						duration: 4,
					})
					return false;
				}
				var formData = new FormData($(this)[0]);
				$.ajax({
					url: "ajax/save_support_ticket.php",
					type: "POST", 
					data: formData,
					cache: false,
					processData:false,
					contentType: false,
					success: function(result){
						//$("#dump").html(result); //return false;
						if(result == 'success'){
							$("#btnSubmit").prop('disabled', true);
							$("body").overhang({
								type: "success",
								message: '<i class="fa fa-check"></i>&nbsp;&nbsp;<?=$lng['Ticket send successfuly']?>',
								duration: 2,
							})
						}else{
							$("body").overhang({
								type: "error",
								message: '<i class="fa fa-exclamation-triangle"></i>&nbsp;&nbsp;<?=$lng['Error']?> : '+result,
								duration: 4,
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
			})
		
		})
	
	</script>























