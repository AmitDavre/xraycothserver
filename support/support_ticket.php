<?php
	
	if(!isset($_GET['id'])){$_GET['id'] = 0;}
	//var_dump($_GET['id']);
	//if($_SESSION['rego']['platform'] == 'SC'){$bgtxt = '#ffe'; }else{
	
	$bgtxt = '#f6fff6';
	//var_dump($recipients);
	
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
		
	<h2><i class="fa fa-life-ring"></i>&nbsp; <?=$lng['Ticket']?> : #&nbsp;<span class="tid"></span><? //=$lng['Approvals']?></h2>
	<div class="main">
		<div id="dump"></div>
		
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
								<th colspan="2"><?=$lng['Created']?> : <span id="created"></span></th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<th><?=$lng['Subject']?></th>
								<td id="subject"></td>
							</tr>
							<tr>
								<th><?=$lng['Priority']?></th>
								<td id="priority"></td>
							</tr>
							<tr>
								<th><?=$lng['Status']?></th>
								<td class="tal" id="status"></td>
							</tr>
							<tr>
								<th><?=$lng['Type']?></th>
								<td class="tal" id="type"></td>
							</tr>
						</tbody>
					</table>
					<input type="hidden" name="ticket" value="<?=$_GET['id']?>">
					<input type="hidden" name="sender" id="sender">
					<input type="hidden" name="recipients" id="recipients">
					<div class="green" style="padding:5px 10px; font-weight:600"><?=$lng['Reply']?></div>
					<textarea class="green" name="message" id="message" style="width:100%; margin:0 0 10px; padding:15px 20px 20px" rows="12"></textarea>								
					<div style="margin-bottom:5px">
						<input style="margin:0 10px 5px 0; display:inline-block" class="reply_attach" name="attachments[]" type="file" />
					</div>
					<button type="submit" id="btnSubmit" class="btn btn-primary" style="float:left"><i class="fa fa-paper-plane"></i> &nbsp;<?=$lng['Submit reply']?></button>
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
					url: "ajax/get_support_ticket.php",
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
						$('#sender').val(result.data.sender);
						$('#recipients').val(result.data.recipients);
						if(result.data.stat == '1'){
							$("#btnSubmit").prop('disabled', true);
							$("#attachment").prop('disabled', true);
							$("#addAttach").prop('disabled', true);
							$("#message").val('<?=$lng['This ticked is closed, you can not reply anymore']?>');
						}
						$('#messageTable').html(result['body']);
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
			getTickets(id);	

			$("#replyForm").on('submit', function(e){
				e.preventDefault();
				var formData = new FormData($(this)[0]);
				$.ajax({
					url: "ajax/update_support_ticket.php",
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
								message: '<i class="fa fa-check"></i>&nbsp;&nbsp;<?=$lng['Reply send successfuly']?>',
								duration: 3,
							})
							getTickets(id)
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
			})
			
			$(document).on("change", ".reply_attach", function(e){
				$('<input style="margin:0 10px 5px 0; display:inline-block" class="reply_attach" name="attachments[]" type="file" />').insertAfter(this);
			})
			
		
		
		})
	
	</script>























