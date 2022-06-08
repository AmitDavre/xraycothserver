<?php
	
	$data = array();
	$sql = "SELECT * FROM ".$_SESSION['rego']['payroll_dbase']." WHERE paid = 'C' ORDER BY emp_name_".$lang." ASC";
	if($res = $dbc->query($sql)){
		while($row = $res->fetch_assoc()){
				$data[$row['id']]['id'] = $row['id'];
				$data[$row['id']]['emp_id'] = $row['emp_id'];
				$data[$row['id']]['month'] = $row['month'];
				$data[$row['id']]['name'] = $row['emp_name_'.$lang];
				$data[$row['id']]['gross'] = number_format($row['gross_income'],2);
				$data[$row['id']]['net'] = number_format($row['net_income'],2);
				$period = $lng['Payroll'].' '.sprintf('%02d',$row['month']).'-'.$cur_year;
		}
	}
	//var_dump($data);
	
	$history = array();
	$sql = "SELECT * FROM ".$cid."_approvals WHERE type = 'payroll' AND year = '$cur_year' ORDER BY month DESC, on_date DESC";
	if($res = $dbc->query($sql)){
		while($row = $res->fetch_assoc()){
				$history[$row['id']][] = $row['month'];
				$history[$row['id']][] = $row['year'];
				$history[$row['id']][] = $row['by_name'];
				$history[$row['id']][] = $row['on_date'];
				$history[$row['id']][] = $row['action'];
				$history[$row['id']][] = $row['comment'];
		}
	}
	//var_dump($history);
	
?>	
<style>
	.table.detailMobTable tbody td.tar {
		text-align:right !important;
	}
	.favatar {
		border:4px solid #ddd;
		padding-top:7px;
		width:50px;
		height:45px;
		xline-height:50px;
		text-align:center;
		font-size:20px;
		border-radius:50%;
		margin-right:10px;
		background:green;
		color:#fff;
	}
	.favatar.green {
		color:#fff;
		background:green;
	}
	.favatar.red {
		color:#fff;
		background:#c00;
	}
</style>

	<div class="container-fluid" style="padding:0">
		<div id="dump"></div>
		
		<div style="padding:10px">							
			<div class="btn-group btn-group-toggle" data-toggle="buttons" style="width:100%">
<!-- 				<label class="btn btn-outline-default <?=$appLeave?>" <?=$appLeave?>onClick="window.location.href='index.php?mn=1701'">
					<input type="radio"><?=$lng['Leave']?>
				</label>
				<label class="btn btn-outline-default <?=$appTime?>" <?=$appTime?>onClick="window.location.href='index.php?mn=1702'">
					<input type="radio"><?=$lng['Time']?>
				</label> -->
				<label class="btn btn-outline-default active <?=$appPayroll?>" <?=$appPayroll?>onClick="window.location.href='index.php?mn=1703'">
					<input type="radio" checked><?=$lng['Payroll']?>
				</label>
			</div>
		</div>
				
		<ul class="nav nav-tabs lined" role="tablist" style="background:#f6f6f6">
			<li class="nav-item">
				<a class="nav-link active" data-toggle="tab" href="#pending" role="tab"><?=$period?></a>
			</li>
			<li class="nav-item">
				<a class="nav-link" data-toggle="tab" href="#history" role="tab"><?=$lng['History']?></a>
			</li>
		</ul>
		
		<div class="tab-content mt-2" style="padding:0 10px">
			
			<div class="tab-pane fade show active" id="pending" role="tabpanel">
				<? if($data){ foreach($data as $k=>$v){ ?>
					<div data-id="<?=$v['id']?>" class="empDetail comment-block">
						<div class="item">
							<div class="avatar">
								<img src="../<?=getEmployeeImg($v['emp_id'])?>">
							</div>
							<div class="in" style="padding:5px 0 10px">
								<div class="comment-header">
									<h4 class="title"><?=$v['name']?></h4>
									<span class="time"><?=$v['emp_id']?></span>
								</div>
								<div class="text">
									Gross income : <?=$v['gross']?><br>
									Net income : <?=$v['net']?>
								</div>
							</div>
						</div>
					</div>
				<? }}else{ ?>
						<div style="padding:0; margin:-10px 0 0; font-size:13px; color:#999"><?=$lng['No data available']?></div>
				<? } ?>
				<?php if($permissionsVar['payroll_result']['view'] == '1' && $permissionsVar['payroll_result']['approve'] == '1' ){?>

				<button id="btnApprove" class="btn btn-default btn-block"><i class="fa fa-thumbs-up fa-lg mr-1"></i><?=$lng['Approve']?> <?=$period?></button>
				<button data-toggle="modal" data-target="#rejectModal" class="btn btn-danger btn-block"><i class="fa fa-thumbs-down fa-lg mr-1"></i><?=$lng['Reject']?> <?=$period?></button>
			<?php } ?>
				
			</div>
			<div class="tab-pane fade show" id="history" role="tabpanel">
			
				<? foreach($history as $k=>$v){ ?>
				<div class="comment-block" xstyle="border-bottom:1px solid #ccc; padding-botton:10px; margin-bottom:10px">
					<div class="item">
						<div class="favatar <? if($v[4] == 'AP'){echo 'green';}else{echo 'red';} ?>">
							<? if($v[4] == 'AP'){ ?>
								<i class="fa fa-thumbs-up"></i>
							<? }else{ ?>
								<i class="fa fa-thumbs-down"></i>
							<? } ?>
						</div>
						<div class="in" style="padding-bottom:8px">
							<div class="comment-header">
								<h4 class="title"><?=$v[2]?></h4>
								<span class="time"><?=$months[$v[0]].' '.$v[1]?></span>
							</div>
							<div class="text">
								<div style="margin-top:-5px; font-size:12px"><?=date('d-m-Y @ H:i', strtotime($v[3]))?></div>
								<? if(!empty($v[5])){ ?>
									<div><b>Reason :</b> <?=$v[5]?></div>
								<? } ?>
							</div>
						</div>
					</div>
				</div>
				<? } ?>
			</div>
			
		</div>
		
		
		<div style="height:60px"></div>
			
	</div>	
	
	<!-- Modal -->
	<div class="modal fade" id="detailModal" tabindex="-1" role="dialog" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-body" style="color:#333">
					<div id="detailTable">

					</div>
					<button data-dismiss="modal" class="btn btn-default btn-block mt-2">Close Popup</button>
				</div>
			</div>
		</div>
	</div>

	<!-- Modal -->
	<div class="modal fade" id="rejectModal" tabindex="-1" role="dialog" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<!--<div class="modal-header">
					<h4 class="modal-title">Reject Payroll</h4>
				</div>-->
				<div class="modal-body" style="color:#333">
					<form id="rejectForm">
						<input type="hidden" name="type" value="payroll"/>
						<input type="hidden" name="action" value="RJ"/>
						<div class="form-group">
							<label for="comment"><b><?=$lng['Reason']?></b></label>
							<textarea placeholder="Can not reject without reason" onKeyUp="$('#btnReject').prop('disabled', false);" rows="4" class="form-control" name="comment"></textarea>
						</div>
						<button type="submit" id="btnReject" class="btn btn-danger">Reject</button>
						<button style="float:right" data-dismiss="modal" class="btn btn-default xbtn-block">Cancel</button>
					</form>
				</div>
			</div>
		</div>
	</div>

			
<script type="text/javascript">
	
	$(document).ready(function() {
	
		$('#rejectForm').submit(function(e) {
			e.preventDefault();
			var data = $(this).serialize();
			$.ajax({
				url: "../payroll/ajax/save_approve_action.php",
				data: data,
				success: function(result){
					//$("#dump").html(result); return false;
					if(result == 'success'){
						alert('<?=$lng['Payroll rejected']?>');
						/*$("body").overhang({
							type: "success",
							message: '<i class="fa fa-check"></i>&nbsp;&nbsp;<?=$lng['Payroll approved']?>',
							duration: 3,
						})*/
					}else{
						alert('<?=$lng['Error']?> : ' + result);
						/*$("body").overhang({
							type: "error",
							message: '<i class="fa fa-exclamation-triangle"></i>&nbsp;&nbsp;<?=$lng['Error']?> : ' + result,
							duration: 4,
						})*/
					}
					setTimeout(function(){
						//$('#btnApprove i').removeClass('fa-refresh fa-spin').addClass('fa-thumbs-up');
						location.reload();
					},1000);
					
					//atable.ajax.reload(null, false);
				},
				error:function (xhr, ajaxOptions, thrownError){
					alert(thrownError);
					/*$("body").overhang({
						type: "error",
						message: '<i class="fa fa-exclamation-triangle"></i>&nbsp;&nbsp;<?=$lng['Error']?> : ' + thrownError,
						duration: 4,
					})*/
				}
			});
		});			
	
		$(document).on('click','#btnApprove', function(e) {
			$('#btnApprove i').removeClass('fa-thumbs-up').addClass('fa-refresh fa-spin');
			$.ajax({
				url: "../payroll/ajax/save_approve_action.php",
				data: {	
					type:'payroll', 
					action:'AP'
				},
				success: function(result){
					//$("#dump").html(result); return false;
					if(result == 'success'){
						alert('<?=$lng['Payroll approved']?>');
						/*$("body").overhang({
							type: "success",
							message: '<i class="fa fa-check"></i>&nbsp;&nbsp;<?=$lng['Payroll approved']?>',
							duration: 3,
						})*/
					}else{
						alert('<?=$lng['Error']?> : ' + result);
						/*$("body").overhang({
							type: "error",
							message: '<i class="fa fa-exclamation-triangle"></i>&nbsp;&nbsp;<?=$lng['Error']?> : ' + result,
							duration: 4,
						})*/
					}
					setTimeout(function(){
						$('#btnApprove i').removeClass('fa-refresh fa-spin').addClass('fa-thumbs-up');
						location.reload();
					},1000);
					
					//atable.ajax.reload(null, false);
				},
				error:function (xhr, ajaxOptions, thrownError){
					alert(thrownError);
					/*$("body").overhang({
						type: "error",
						message: '<i class="fa fa-exclamation-triangle"></i>&nbsp;&nbsp;<?=$lng['Error']?> : ' + thrownError,
						duration: 4,
					})*/
				}
			});
		});			
		
		$('.empDetail').on('click', function() {
			var id = $(this).data('id');
			//alert(id);
			$.ajax({
				url: "ajax/get_payroll_details.php",
				data: {id:$(this).data('id')},
				success:function(result){
					//$('#dump').html(result);
					$('#detailTable').html(result)
					$('#detailModal').modal('toggle')
				},
				error:function (xhr, ajaxOptions, thrownError){
					alert('<?=$lng['Error']?> ' + thrownError);
				}
			});
			
		
		})
		
	})
	
</script>	










