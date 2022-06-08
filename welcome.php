<?
	$welcome = getWelcomeFiles();
	$date_start = date('01-01-Y');
	$date_end = date('01-01-'.(date('Y')+1));
	//var_dump($welcome); exit;
	
?>	
<style>
	.navigator {
		padding:15px 15px 0 0;
		vertical-align:top;
	}
	.navigator ul {
		margin:0;
		padding:0;
		list-style:none;
	}
	.navigator ul li {
		line-height:180%;
	}
	.navigator ul li a {
		font-weight:600;
		text-transform:uppercase;
		text-decoration:none;
		color:#000;
		padding:2px 12px;
		border-radius:2px;
		background:#eee;
		margin-bottom:3px;
		display:block;
	}
	.navigator ul li a:hover,
	.navigator ul li a.activ {
		color:#fff !important;
		background:#666 !important;
	}
	#realData {
		background:#a00;
		color:#fff;
		border-color:#a00;
	}
	#realData:hover {
		background:#fb0;
		border-color:#fb0;
		color:#000;
	}
	.startTable {
		width:100%;
	}
	.startTable th {
		padding:5px;
		text-align:center;
		font-size:20px;
	}
	.startTable td {
		padding:0;
	}
	#prMonth {
		font-size:22px;
		color:#b00;
		text-align:center;
		cursor:pointer;
		margin:0;
		padding:5px;
		line-height:24px;
		font-weight:600;
		border:1px solid #eee;
		background:#eee;
	}
	#prMonth:hover {
		border:1px solid #ccc;
	}
</style>

	<h2><i class="fa fa-handshake-o"></i>&nbsp; <?=$lng['Welcome']?></h2>		
	
	<div class="main" style="top:115px;">
		<div style="padding:0 0 0 20px" id="dump"></div>
			
		<table border="0" style="height:100%; width:100%">
		  <tr>
			 <td class="navigator" style="min-width:280px; width:20%">
				<ul>
					<? foreach($welcome as $k=>$v){ ?>
						<li><a <? if($k==1){echo 'class="activ"';}?> data-id="c<?=$k?>"><i class="fa fa-arrow-circle-right"></i>&nbsp;&nbsp;<?=$v['title']?></a></li>
					<? } ?>
				</ul>
				<? if($sys_settings['demo'] == 0){ ?>
				<button id="demoData" style="width:100%; margin-top:10px; text-align:left" class="btn btn-primary"><i class="fa fa-database"></i>&nbsp;&nbsp;<?=$lng['Use Demo data for testing']?></button>
				<button id="realData" style="width:100%; margin-top:5px; text-align:left" class="btn btn-primary"><i class="fa fa-database"></i>&nbsp;&nbsp;<?=$lng['I will use my real data']?></button>
				<? } if($sys_settings['demo'] == 1){?>
				<button id="realData" style="width:100%; margin-top:5px; text-align:left" class="btn btn-primary"><i class="fa fa-database"></i>&nbsp;&nbsp;<?=$lng['I will use my real data']?></button>
			 	<? } ?>
			 </td>
			 <td style="padding:15px; width:55%; vertical-align:top; line-height:160%; border-left:1px solid #eee; border-right:1px solid #eee">
				<? foreach($welcome as $k=>$v){ ?>
					<div class="content" id="c<?=$k?>" style="height:100%; overflow-y:auto; padding:5px 20px 30px 10px; <? if($k!=1){echo 'display:none';}?>">
						<?=$v['content']?>
					</div>
				<? } ?>
			 </td>
			 <td style="min-width:350px; width:25%; vertical-align:top; padding:15px 0px 0 15px; background:rgba(0,0,0,0.02)">
				<div style="width:100%; min-height:100px; background:#fff; margin:0 auto; padding:55px 20px 20px; box-shadow:0 0 10px rgba(0,0,0,0.2); position:relative; margin-bottom:20px; <? if($sys_settings['demo'] == 0){echo 'opacity:0.4';}?>">
					<h2 style="background:#a00; color:#fff; position:absolute; top:0; left:0; right:0"><i class="fa fa-bell"></i>&nbsp; <?=$lng['Please complete setup tasks first']?></h2>
					<? if($checkSetup){
						echo $checkSetup;
					}else{
						echo '<i class="fa fa-check-square-o"></i>&nbsp; <b>'.$lng['All mandatory System settings are set'].'</b><br>';
					}?>
					<button <? if($sys_settings['demo'] == 0){echo 'disabled';}?> onClick="window.location.href='settings/index.php?mn=600';" class="btn btn-primary" style="margin-top:8px" type="button"><i class="fa fa-cog"></i>&nbsp; <?=$lng['Settings']?></button>
				</div>
				
				<div style="width:100%; min-height:90px; background:#fff; margin:0 auto; padding:55px 20px 20px; box-shadow:0 0 10px rgba(0,0,0,0.2); position:relative; <? if($sys_settings['demo'] == 0){echo 'opacity:0.4';}?>">
					<h2 style="background:#a00; color:#fff; position:absolute; top:0; left:0; right:0"><i class="fa fa-bell"></i>&nbsp; <?=$lng['Employees to complete for payroll']?></h2>
					<? if(checkEmployeesForPayroll($cid)){
						echo checkEmployeesForPayroll($cid);
					}else{
						echo '<i class="fa fa-check-square-o"></i>&nbsp; <b>'.$lng['All employees are set for Payroll'].'</b><br>';
					}?>
					<button <? if($sys_settings['demo'] == 0){echo 'disabled';}?> onClick="window.location.href='employees/index.php?mn=101';" class="btn btn-primary" style="margin-top:8px" type="button"><i class="fa fa-users"></i>&nbsp; <?=$lng['Employee register']?></button>
				</div>
			 </td>
		  </tr>
		</table>

	</div>
		
	<!-- MODAL PAYROLL START DATE -->
	<div class="modal fade" id="prdateModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
		 <div class="modal-dialog" style="min-width:700px">
			  <div class="modal-content" style="border-radius:10px;">
					<div class="modal-body" style="padding:30px">
					<span style="color:#b00; text-align:center; font-size:24px; font-weight:600; background:#eee; padding:5px; border-radius: 5px 5px 0 0; width:100%; display:block">Select Payroll Start Month</span>
					<div class="helpModal" style="padding:10px 10px 15px 0; text-align:justify">
						<?=getHelpfile(3)?>
					</div>
					 <table class="startTable" border="0">
						<tr>
							<td>
								<button id="startButton" class="btn btn-primary" style="margin:0; padding:6px 15px !important; font-size:14px !important" type="button"><i class="fa fa-save"></i>&nbsp;&nbsp;<?=$lng['Save']?></button>
							</td>
							<td style="width:90%; padding:0 10px">
								<input style="width:100%" placeholder="Select Month" readonly id="prMonth" name="prMonth" type="text" />
							</td>
							<td>
								<button class="btn btn-primary" style="margin:0; padding:6px 15px !important; font-size:14px !important" type="button" data-dismiss="modal"><i class="fa fa-times"></i>&nbsp;&nbsp;<?=$lng['Cancel']?></button>
							</td>
						</tr>
					</table>

			  </div>
		 </div>
	</div>
	
<script type="text/javascript">
	
	$(document).ready(function() {
		
		var start = <?=json_encode($date_start)?>;
		var end = <?=json_encode($date_end)?>;
		
		$('#prMonth').datepicker({
			format: "dd-mm-yyyy",
			autoclose: true,
			inline: true,
			language: lang,//lang+'-th',
			todayHighlight: true,
			viewMode: "months", 
			minViewMode: "months",
			startDate: start,
			endDate: end,
		}).on('changeMonth', function(e){
			//alert(new Date(e.date).getMonth()+1)
		})

		$('#demoData').on('click', function(){
			$.ajax({
				url: "ajax/upload_demodata.php",
				success: function(data) {
					//$('#dump').html(data); return false;
					$("body").overhang({
						type: "success",
						message: '<i class="fa fa-check"></i>&nbsp;&nbsp;<?=$lng['Demodata uploaded successfuly']?> . . . Please wait for reload . . .',
						duration: 2,
					})
					setTimeout(function(){
						location.reload();
					},1500);
				},
				error: function (xhr, ajaxOptions, thrownError) {
					$("body").overhang({
						type: "error",
						message: '<i class="fa fa-exclamation-circle"></i>&nbsp;&nbsp;<?=$lng['Sorry but someting went wrong']?> <b><?=$lng['Error']?></b> : '+thrownError,
						duration: 4,
					})
				}
			});
		})
		
		$('#realData').on('click', function(){
			$('#prdateModal').modal('toggle');
		})
		$('#startButton').on('click', function(){
			if($('#prMonth').val() == ''){
				$("body").overhang({
					type: "error",
					message: '<i class="fa fa-exclamation-triangle"></i>&nbsp;&nbsp;Please select Payroll Start Month<? //=$lng['Please fill in required fields']?>',
					duration: 4,
				})
				return false;
			}
			$.ajax({
				url: "ajax/delete_demodata.php",
				data: {prMonth: $('#prMonth').val()},
				success: function(result) {
					//$('#dump').html(result); return false;
					$('#prdateModal').modal('toggle');
					if(result == 'success'){
						$("body").overhang({
							type: "success",
							message: '<i class="fa fa-check"></i>&nbsp;&nbsp;<?=$lng['Demodata deleted successfuly']?> . . . Please wait for reload . . .',
							duration: 2,
						})
						setTimeout(function(){
							location.reload();
						},1500);
					}
				},
				error: function (xhr, ajaxOptions, thrownError) {
					$("body").overhang({
						type: "error",
						message: '<i class="fa fa-exclamation-circle"></i>&nbsp;&nbsp;<?=$lng['Sorry but someting went wrong']?> <b><?=$lng['Error']?></b> : '+thrownError,
						duration: 4,
					})
				}
			});
		})
		
		$('.navigator a').on('click', function(){
			var id = $(this).data('id');
			$('.navigator a').removeClass('activ');
			$(this).addClass('activ');
			$('.content').hide();
			$('#'+id).show();
			
		})
		
	});
	
</script>


















