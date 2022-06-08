<?php
	
	if(!isset($_GET['id'])){$_GET['id'] = 0;}
	//var_dump($_GET['id']);
	
?>
	
<style>
	table.msgTable {
		width:100%;
		margin-bottom:10px;
	}
	table.msgTable th {
		padding:5px 10px;
		font-weight:600;
	}
	table.msgTable th.green, div.green {
		background:#cdc;
		border:1px solid #cdc;
	}
	table.msgTable th.yellow, div.yellow {
		background:#ddc;
		border:1px solid #ddc;
	}
	table.msgTable td {
		padding:10px 20px 15px;
	}
	table.msgTable td.green, textarea.green {
		background:#f6fff6;
		border:1px solid #cdc;
	}
	table.msgTable td.yellow, textarea.yellow {
		background:#ffe;
		border:1px solid #ddc;
	}
	a.attLink, a.attLink i {
		color:#069;
		text-decoration:none;
	}
	a.attLink:hover, a.attLink:hover i {
		color:#a00;
		xfont-weight:600;
	}
</style>
		
	<h2><i class="fa fa-life-ring"></i>&nbsp; Support ticket : #&nbsp;<span class="tid"></span></h2>
	<div class="main">
		<div style="padding:0 0 0 20px" id="dump"></div>
		
		<table border="0" style="width:100%; height:100%">
			<tr>
				<td style="width:55%; padding-right:20px; vertical-align:top">
					<div id="messageTable" style="height:100%; overflow-Y:auto; padding-right:2px"></div>
				</td>
				<td style="vertical-align:top; width:45%">
					<form id="replyForm" enctype="multipart/form-data">
					<table class="basicTable" border="0" style="width:100%; margin-bottom:10px">
						<thead>
							<tr>
								<th colspan="2">Created : <span id="created"></span></th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<th>Subject</th>
								<td id="subject"></td>
							</tr>
							<tr>
								<th>Priority</th>
								<td id="priority"></td>
							</tr>
							<tr>
								<th>Status</th>
								<td class="tal" id="status"></td>
							</tr>
							<tr>
								<th>Type</th>
								<td class="tal" id="type"></td>
							</tr>
						</tbody>
					</table>
					<input type="hidden" name="ticket" value="<?=$_GET['id']?>">
					<input type="hidden" name="name" value="<?=$_SESSION['RGadmin']['name']?>">
					<input type="hidden" name="sender" id="sender">
					<div class="yellow" style="padding:5px 10px; font-weight:600">Reply</div>
					<textarea class="yellow" name="message" id="message" style="width:100%; margin:0 0 10px; padding:15px 20px 20px" rows="12"></textarea>								
					<div style="margin-bottom:5px">
						<input style="margin:0 10px 5px 0; display:inline-block" class="reply_attach" name="attachments[]" type="file" />
					</div>

					<button type="submit" id="btnSubmit" class="btn btn-primary btn-fl"><i class="fa fa-paper-plane"></i> &nbsp;Submit reply</button>
					<button type="button" id="btnClose" class="btn btn-primary btn-fr">Close ticket</button>
					<button type="button" id="btnReopen" disabled class="btn btn-primary btn-fr">Re-open ticket</button>
					<div class="clear"></div>
					</form>

				</td>
			</tr>
		</table>

	</div>
	
	
	<!-- PAGE RELATED PLUGIN(S) -->
	
	<script type="text/javascript">
		
		$(document).ready(function() {
			var id = <?=json_encode($_GET['id'])?>;
			
			function getTickets(id){
				$.ajax({
					url: AROOT+"support/ajax/get_support_ticket.php",
					type: 'POST',
					data: {id: id},
					dataType: 'json',
					success: function(result){
						//$('#dump').html(result); return false;
						$('#ticket').html(result.data.ticket);
						$('#subject').html(result.data.subject);
						$('.tid').html(result.data.ticket);
						$('#created').html(result.data.created);
						$('#priority').html(result.data.priority);
						$('#status').html(result.data.status);
						$('#type').html(result.data.type);
						$('#sender').html(result.data.sender);
						if(result.data.stat == '1'){
							$("#btnSubmit").prop('disabled', true);
							$("#btnClose").prop('disabled', true);
							$("#btnReopen").prop('disabled', false);
							$("#attachment").prop('disabled', true);
							$("#addAttach").prop('disabled', true);
							$("#message").val('This ticked is closed, you can not reply anymore');
						}
						$('#messageTable').html(result['body']);
					},
					error:function (xhr, ajaxOptions, thrownError){
						alert(thrownError)
					}
				});
			}
			getTickets(id);	
			
			$("#replyForm").on('submit', function(e){
				e.preventDefault();
				var formData = new FormData($(this)[0]);
				$.ajax({
					url: "support/ajax/update_support_ticket.php",
					type: "POST", 
					data: formData,
					cache: false,
					processData:false,
					contentType: false,
					success: function(result){
						//$("#dump").html(result); return false;
						$("#btnSubmit").prop('disabled', true);
						if(result == 'success'){
							$("body").overhang({
								type: "success",
								message: '<i class="fa fa-check"></i>&nbsp;&nbsp;Reply send successfuly',
								duration: 2,
							})
							getTickets(id)
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
			})
		
			$(document).on("change", ".reply_attach", function(e){
				$('<input style="margin:0 10px 5px 0; display:inline-block" class="reply_attach" name="attachments[]" type="file" />').insertAfter(this);
			})
			
			$("#btnClose").on('click', function(e){
				$.ajax({
					url: AROOT+"support/ajax/close_support_ticket.php",
					type: "POST", 
					data: {ticket: id},
					success: function(result){
						//$("#dump").html(result); return false;
						if(result == 'success'){
							$("body").overhang({
								type: "success",
								message: '<i class="fa fa-check"></i>&nbsp;&nbsp;Ticket closed successfuly',
								duration: 2,
							})
							$("#btnSubmit").prop('disabled', true);
							$("#btnClose").prop('disabled', true);
							$("#btnReopen").prop('disabled', false);
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
			})
			$("#btnReopen").on('click', function(e){
				$.ajax({
					url: AROOT+"support/ajax/reopen_support_ticket.php",
					type: "POST", 
					data: {ticket: id},
					success: function(result){
						//$("#dump").html(result); return false;
						if(result == 'success'){
							$("body").overhang({
								type: "success",
								message: '<i class="fa fa-check"></i>&nbsp;&nbsp;Ticket re-opened successfuly',
								duration: 2,
							})
							$("#btnSubmit").prop('disabled', false);
							$("#btnClose").prop('disabled', false);
							$("#btnReopen").prop('disabled', true);
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
			})
		
		
		})
	
	</script>























