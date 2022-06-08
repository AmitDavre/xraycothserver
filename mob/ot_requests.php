
<style type="text/css">
	
	.btn .badge {
     position: relative; 
     top: 0px; 
}


.blink {
      animation: blink 2s steps(5, start) infinite;
      -webkit-animation: blink 1s steps(5, start) infinite;
    }
    @keyframes blink {
      to {
        visibility: hidden;
      }
    }
    @-webkit-keyframes blink {
      to {
        visibility: hidden;
      }
    }


</style>
	<div class="container-fluid" style="padding:0">
            
		<div class="accordion" id="accordionExample1">

			<div class="item">
				<div class="accordion-header">
					<button class="btn collapsed" type="button" data-toggle="collapse" data-target="#Communicationcenter">
						<?=$lng['Communication center']?>  &nbsp; 
						<!-- <span class='badge badge-warning' id='lblCartCount'> <?php echo $count_request; ?> </span> -->
					</button>
				</div>
				<div id="Communicationcenter" class="accordion-body collapse" data-parent="#accordionExample1">
					<div class="accordion-content" style="padding:0">
						<input type="hidden" name="otID_hidden" id="otID_hidden" value="">
						<table class="accordion-table bordered">
							<tbody>
								
								<?= checkAnnouncementForMob($_SESSION['rego']['emp_id']); ?>
									
							</tbody>
						</table>
					</div>
				</div>
			</div>
			<div class="item">
				<div class="accordion-header">
					<button class="btn collapsed" type="button" data-toggle="collapse" data-target="#personal">
						<?=$lng['Pending requests']?>  &nbsp; <span class='badge badge-warning' id='lblCartCount'> <?php echo $count_request; ?> </span>
					</button>
				</div>
				<div id="personal" class="accordion-body collapse" data-parent="#accordionExample1">
					<div class="accordion-content" style="padding:0">
						<input type="hidden" name="otID_hidden" id="otID_hidden" value="">
						<table class="accordion-table bordered">
							<tbody>
								<?php if(!empty($request)){
									foreach ($request as $key_r => $value_r) {?>
									<tr>
										<th style="width:10%"><?=$lng['Date']?></th>
										<td><?php echo $value_r['date'];?></td>

										<th style="width:10%"><?=$lng['From']?></th>
										<td> <?php echo $value_r['from'];?> </td>
										
										<th style="width:10%"><?=$lng['Until']?></th>
										<td><?php echo $value_r['until'];?> </td>

										<td><a data-id="<?=$key_r?>" class="confirm_ot"><i  style ="color: #f79502;font-size: 16px;" class="fa fa-check-circle-o blink" aria-hidden="true"></i></a></td>

									</tr>
								<?php } } ?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		
			<div class="item">
				<div class="accordion-header">
					<button class="btn collapsed" type="button" data-toggle="collapse" data-target="#contact">
						<?=$lng['Confirmed Requests']?>  &nbsp; <span class='badge badge-warning' id='lblCartCount'> <?php echo $count_confirmed; ?> </span>
					</button>
				</div>
				<div id="contact" class="accordion-body collapse" data-parent="#accordionExample1">
					<div class="accordion-content" style="padding:0">
						<table class="accordion-table bordered">
							<tbody>
								<?php if(!empty($confirmed)){

									foreach ($confirmed as $key_c => $value_c) {?>
									<tr>
										<th style="width:10%"><?=$lng['Date']?></th>
										<td><?php echo $value_c['date'];?></td>

										<th style="width:10%"><?=$lng['From']?></th>
										<td> <?php echo $value_c['from'];?> </td>
										
										<th style="width:10%"><?=$lng['Until']?></th>
										<td><?php echo $value_c['until'];?> </td>


									</tr>
								<?php } } ?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		
			<div class="item">
				<div class="accordion-header">
					<button class="btn collapsed" type="button" data-toggle="collapse" data-target="#financial">
						<?=$lng['Assigned Requests']?> &nbsp; <span class='badge badge-warning'> <?php echo $count_assigned; ?> </span>
					</button>
				</div>
				<div id="financial" class="accordion-body collapse" data-parent="#accordionExample1">
					<div class="accordion-content" style="padding:0">
						<table class="accordion-table bordered">
							<tbody>
								<?php 
									if(!empty($assigned))
									{
										foreach ($assigned as $key_a => $value_a) {?>
										<tr>
											<th style="width:10%"><?=$lng['Date']?></th>
											<td><?php echo $value_a['date'];?></td>

											<th style="width:10%"><?=$lng['From']?></th>
											<td> <?php echo $value_a['from'];?> </td>
											
											<th style="width:10%"><?=$lng['Until']?></th>
											<td><?php echo $value_a['until'];?> </td>

										</tr>
								<?php } } ?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		
		</div>
		
		<div style="height:55px"></div>
		
		
		
	</div>				


	<!-- Modal -->
	<div class="modal fade" id="confirmModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel5" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header bg-info" style="padding:0 10px 10px;background: #1e74fde0!important;">
					<h5 class="modal-title" style="padding:8px;color:#fff; width:100%; text-align:center;font-size:15px;">Confirm this OT request<? //=$lng['Delete this leave request']?> ?</h5>
				</div>
				<div class="modal-body" style="color:#333">
						<button id="confirmRequest" type="button" class="btn btn-success btn-lg btn-block tac"><?=$lng['Yes']?></button>
						<button type="button" data-dismiss="modal" class="btn btn-danger btn-lg btn-block tac"><?=$lng['No']?></button>
				</div>
			</div>
		</div>
	</div>
		
		
		
		
<script type="text/javascript">
	
	$(document).on('click', '.confirm_ot', function(e){
		//alert($(this).data('id'))
		otID = $(this).data('id');
		$('#otID_hidden').val(otID);
		$('#confirmModal').modal('toggle');
	})


	$(document).on('click', '#confirmRequest', function(e){

		var otID_value = $('#otID_hidden').val();

		$('#confirmModal').modal('toggle');
		$.ajax({
			url: "ajax/confirm_ot_request.php",
			data: {id: otID_value},
			success: function(result){
				//alert(result);
				window.location.reload();
			},
			error:function (xhr, ajaxOptions, thrownError){
				alert(thrownError);
			}
		});
	})





</script>