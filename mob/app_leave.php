<?php

	$leave_types = getLeaveTypes($cid);
	$pending = array();
	$approved = array();
	$history = array();
	$res = $dbc->query("SELECT * FROM ".$cid."_leaves ORDER BY start DESC"); 
	while($row = $res->fetch_assoc()){
		if($row['status'] == 'RQ'){
			$pending[$row['id']]['emp_id'] = $row['emp_id'];
			$pending[$row['id']]['name'] = $row['name'];
			$pending[$row['id']]['leave_type'] = $row['leave_type'];
			$pending[$row['id']]['days'] = $row['days'];
			$pending[$row['id']]['start'] = date('d-m-Y', strtotime($row['start']));
			$pending[$row['id']]['end'] = date('d-m-Y', strtotime($row['end']));
			$pending[$row['id']]['status'] = $row['status'];
			$pending[$row['id']]['created'] = $row['created'];
		}elseif($row['status'] == 'AP' || ($row['status'] == 'RJ') || ($row['status'] == 'CA')){	
			$history[$row['id']]['emp_id'] = $row['emp_id'];
			$history[$row['id']]['name'] = $row['name'];
			$history[$row['id']]['leave_type'] = $row['leave_type'];
			$history[$row['id']]['days'] = $row['days'];
			$history[$row['id']]['start'] = date('d-m-Y', strtotime($row['start']));
			$history[$row['id']]['end'] = date('d-m-Y', strtotime($row['end']));
			$history[$row['id']]['status'] = $row['status'];
			$history[$row['id']]['approved_by'] = $row['approved_by'];
			$history[$row['id']]['rejected_by'] = $row['rejected_by'];
			$history[$row['id']]['canceled_by'] = $row['canceled_by'];
			$history[$row['id']]['approved'] = $row['approved'];
			$history[$row['id']]['rejected'] = $row['rejected'];
			$history[$row['id']]['canceled'] = $row['canceled'];
			$history[$row['id']]['comment'] = $row['comment'];
		}
	}
	//var_dump($history); exit;
	$status_color = array('RQ'=>'bg-blue-light','CA'=>'bg-yellow-dark','AP'=>'bg-green-dark','RJ'=>'bg-red-light','TA'=>'bg-night-light');
	if($lang == 'en'){
		$leave_status['RQ'] = 'Pending';
	}else{
		$leave_status['RQ'] = 'อยู่ระหว่างดำเนินการ';
	}
?>	
	<div class="container-fluid" style="padding:0">
		
		<div style="padding:10px">							
			<div class="btn-group btn-group-toggle" data-toggle="buttons" style="width:100%">
				<label class="btn btn-outline-default active <?=$appLeave?>" <?=$appLeave?>onClick="window.location.href='index.php?mn=1701'">
					<input type="radio" checked><?=$lng['Leave']?>
				</label>
				<!-- <label class="btn btn-outline-default <?=$appTime?>" <?=$appTime?>onClick="window.location.href='index.php?mn=1702'">
					<input type="radio"><?=$lng['Time']?>
				</label>
				<label class="btn btn-outline-default <?=$appPayroll?>" <?=$appPayroll?>onClick="window.location.href='index.php?mn=1703'">
					<input type="radio"><?=$lng['Payroll']?>
				</label> -->
			</div>
		</div>
		
		<ul class="nav nav-tabs lined" role="tablist" style="background:#f6f6f6">
			<li class="nav-item">
				<a class="nav-link active" data-toggle="tab" href="#pending" role="tab"><?=$lng['Pending']?></a>
			</li>
			<li class="nav-item">
				<a class="nav-link" data-toggle="tab" href="#history" role="tab"><?=$lng['History']?></a>
			</li>
		</ul>
		
		<div class="tab-content mt-2" style="padding:0 10px">
				
				<div class="tab-pane fade show active" id="pending" role="tabpanel">
					<? if($pending){ foreach($pending as $k=>$v){ 
							if($v['days'] == 1){$d = $lng['day'];}else{$d = $lng['days'];}?>
						<div class="comment-block">
							<div class="item">
								<div class="avatar">
									<img src="../<?=getEmployeeImg($v['emp_id'])?>">
								</div>
								<div class="in" style="padding:5px 0 10px">
									<div class="comment-header">
										<h4 class="title"><?=$v['name']?></h4>
										<span class="time"><?=substr($v['created'],0,10)?></span>
									</div>
									<div class="text">
										<?=$leave_types[$v['leave_type']][$lang]?> - <?=$v['days']?> <?=$d?><br>
										<?=date('D d-m-Y', strtotime($v['start']))?> - <?=date('D d-m-Y', strtotime($v['end']))?>
									</div>
									<div class="comment-footer" style="padding:0">
										<?php if($permissionsVar['leave_application']['approve'] == '1' ){?>
										<button onclick="getapprovedataid(<?=$k?>);" data-id="<?=$k?>" id="appBtn" data-toggle="modal" data-target="#ApproveDialog" class="btn btn-success btn-sm" style="margin-right:8px"><?=$lng['Approve']?></button>
										<button onclick="getrejectdataid(<?=$k?>);" data-id="<?=$k?>" id="rejBtn" data-toggle="modal" data-target="#RejectDialog" class="btn btn-danger btn-sm" style="margin-right:8px"><?=$lng['Reject']?></button>
										
										<button onclick="getcanceldataid(<?=$k?>);" data-id="<?=$k?>" id="canBtn" data-toggle="modal" data-target="#CancelDialog" class="btn btn-info btn-sm"><?=$lng['Cancel']?></button>
										<?php } ?>
										
									</div>
								</div>
							</div>
						</div>
					<? }}else{ ?>
							<div style="padding:0; margin:-10px 0 0; font-size:13px; color:#999"><?=$lng['No data available']?></div>
					<? } ?>
					
				</div>
				
				<div class="tab-pane fade" id="history" role="tabpanel">
					<? if($history){ ?>
					<? foreach($history as $k=>$v){ 
							if($v['days'] == 1){$d = $lng['day'];}else{$d = $lng['days'];}
							?>
						<div class="comment-block">
							<div class="item">
								<div class="avatar">
									<img src="../<?=getEmployeeImg($v['emp_id'])?>" class="<?=$v['status']?>">
								</div>
								<div class="in" style="padding:5px 0 10px">
									<div class="comment-header">
										<h4 class="title"><?=$v['name']?></h4>
										<span class="time2">
											<? if($v['status'] == 'AP'){ ?>
											<?=str_replace(' @ ', '<br>',$v['approved'])?>
											<? }elseif($v['status'] == 'RJ'){ ?>
											<?=str_replace(' @ ', '<br>',$v['rejected'])?>
											<? }elseif($v['status'] == 'CA'){ ?>
											<?=str_replace(' @ ', '<br>',$v['canceled'])?>
											<? } ?>
										</span>
									</div>
									<div class="text">
										<?=$leave_types[$v['leave_type']][$lang]?> - <?=$v['days']?> <?=$d?><br>
										<?=date('D d-m-Y', strtotime($v['start']))?> - <?=date('D d-m-Y', strtotime($v['end']))?>
									</div>
									<div class="comment-footer">
										
										<? if($v['status'] == 'AP'){ ?>
										<div class="item">
											<i class="fa fa-thumbs-up fa-lg mr-1" style="color:#34C759"></i>
											<?=$v['approved_by']?>
										</div>
										
										<? }elseif($v['status'] == 'RJ'){ ?>
										<div class="item">
											<i class="fa fa-thumbs-down fa-lg mr-1" style="color:#EC4433"></i>
											<?=$v['rejected_by']?>
										</div>
										<div class="item"><i class="fa fa-commenting-o fa-lg mr-1" style="color:#999"></i>
											<?=$v['comment']?>
										</div>

										<? }elseif($v['status'] == 'CA'){ ?>
										<div class="item">
											<i class="fa fa-times-circle fa-lg mr-1" style="color:#0077CC"></i>
											<?=$v['canceled_by']?>
										</div>
										<div class="item"><i class="fa fa-commenting-o fa-lg mr-1" style="color:#999"></i>
											<?=$v['comment']?>
										</div>
										<? } ?>
									
									</div>
								</div>
							</div>
						</div>
					<? }}else{ ?>
							<div style="padding:0; margin:-10px 0 0; font-size:13px; color:#999"><?=$lng['No data available']?></div>
					<? } ?>
					
				</div>
		</div>
		
		<div style="height:60px"></div>
			
	</div>	
			
	<!-- RejectDialog -->
	<div class="modal fade dialogbox" id="RejectDialog" data-backdrop="static" tabindex="-1" role="dialog">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title">Reject reason</h5>
				</div>
				<div class="modal-body" style="padding:15px 15px 0; margin:0">
					<textarea id="rejectReason" rows="3" style="width:100%; border:1px solid #ddd; resize:vertical; padding:5px 10px; min-height:90px" placeholder="..."></textarea>
				</div>
				<div class="modal-footer" style="padding:5px 15px 15px; border:0; margin:">
					<input type="hidden" name="hiddenrejectid" id="hiddenrejectid" value="">

					<button id="rejectBtn" style="float:left" class="btn btn-danger m-0"><?=$lng['Reject']?></button>
					<button data-dismiss="modal" class="btn btn-outline-default m-0"><?=$lng['Close']?></button>
				</div>
			</div>
		</div>
	</div>
	
	<!-- CancelDialog -->
	<div class="modal fade dialogbox" id="CancelDialog" data-backdrop="static" tabindex="-1" role="dialog">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title">Cancel reason</h5>
				</div>
				<div class="modal-body" style="padding:15px 15px 0; margin:0">
					<textarea id="cancelReason" rows="3" style="width:100%; border:1px solid #ddd; resize:vertical; padding:5px 10px; min-height:90px" placeholder="..."></textarea>
				</div>
				<div class="modal-footer" style="padding:5px 15px 15px; border:0; margin:">
					<input type="hidden" name="hiddencancelid" id="hiddencancelid" value="">

					<button id="cancelBtn" style="float:left" class="btn btn-info m-0"><?=$lng['Cancel']?></button>
					<button data-dismiss="modal" class="btn btn-outline-default m-0"><?=$lng['Close']?></button>
				</div>
			</div>
		</div>
	</div>
	
	<!-- ApproveDialog -->
	<div class="modal fade dialogbox" id="ApproveDialog" data-backdrop="static" tabindex="-1" role="dialog">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title">Approve leave</h5>
				</div>
				<div class="modal-footer" style="padding:15px; border:0; margin:">
					<input type="hidden" name="hiddenapproveid" id="hiddenapproveid" value="">
					<button id="approveBtn" style="float:left" class="btn btn-success m-0"><?=$lng['Approve']?></button>
					<button data-dismiss="modal" class="btn btn-outline-default m-0"><?=$lng['Close']?></button>
				</div>
			</div>
		</div>
	</div>
	
	
	
	
<script type="text/javascript">

	function getapprovedataid(id)
	{
		$('#hiddenapproveid').val(id);
	}
		
	function getrejectdataid(id)
	{
		$('#hiddenrejectid').val(id);
	}	

	function getcanceldataid(id)
	{
		$('#hiddencancelid').val(id);
	}
	
	$(document).ready(function() {
		
		$(document).on("click", "#approveBtn", function(e){

			$.ajax({
				url: "../leave/ajax/save_leave_action.php",
				data: {row_id: $('#hiddenapproveid').val(), action: 'approve'},
				success:function(result){
					$("#ApproveDialog").modal('toggle');
					setTimeout(function(){window.location.reload();},300);
				},
				error:function (xhr, ajaxOptions, thrownError){
					alert('<?=$lng['Error']?> ' + thrownError);
				}
			});
		});

		$(document).on("click", "#rejectBtn", function(e){
			var reason = $('#rejectReason').val();
			$.ajax({
				url: "../leave/ajax/save_leave_action.php",
				data: {row_id: $('#hiddenrejectid').val(), comment: reason, action: 'reject'},
				success:function(result){
					$("#RejectDialog").modal('toggle');
					setTimeout(function(){window.location.reload();},300);
				},
				error:function (xhr, ajaxOptions, thrownError){
					alert('<?=$lng['Error']?> ' + thrownError);
				}
			});
		});

		$(document).on("click", "#cancelBtn", function(e){
			var reason = $('#cancelReason').val();
			//alert(reason);
			$.ajax({
				url: "../leave/ajax/save_leave_action.php",
				data: {row_id: $('#hiddencancelid').val(), comment: reason, action: 'cancel'},
				success:function(result){
					$("#CancelDialog").modal('toggle');
					setTimeout(function(){window.location.reload();},300);
				},
				error:function (xhr, ajaxOptions, thrownError){
					alert('<?=$lng['Error']?> ' + thrownError);
				}
			});
		});

	})
	
</script>	










