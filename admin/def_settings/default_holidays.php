<?
	for($i=2019; $i <= (date('Y')+1); $i++){
		$years[$i] = $i;
	}
?>
<style>
</style>
	
	<h2><i class="fa fa-cogs"></i>&nbsp;&nbsp;Default Holidays <span style="float:right; display:none; font-style:italic; color:#b00" id="sAlert"><?=$lng['Data is not updated to last changes made']?></span></h2>
	<div class="main">
		<div style="padding:0 0 0 20px" id="dump"></div>
		
		<div id="showTable" style="display:none">
			<table border="0" style="width:100%; margin-bottom:10px">
				<tr>
					<td style="vertical-align:top;">
						<select id="selYear">
							<? foreach($years as $v){
									echo '<option ';
									if($_SESSION['RGadmin']['cur_year'] == $v){echo 'selected';}
									echo ' value="'.$v.'">Holidays in '.$v.'</option>';
								} ?>
						</select>
					</td>
					<td style="padding-left:10px; width:100%">
						<div style="display:none" id="message"></div>
					</td>
					<? if($_SESSION['RGadmin']['access']['def_settings']['add'] == 1){ ?>
					<td style="vertical-align:top; padding-left:10px">
						<button id="addHoliday" class="btn btn-primary" type="button"><i class="fa fa-plus"></i>&nbsp; Add Holiday</button>
					</td>
					<? } ?>
				</tr>
			</table>

			<table id="holidayTable" class="dataTable inputs nowrap" border="0">
				<thead>
					<tr>
						<th style="width:130px;" class="tal"><?=$lng['Date']?></th>
						<th data-sortable="false" style="width:130px">Company date</th>
						<th data-sortable="false"><?=$lng['Thai']?></th>
						<th data-sortable="false"><?=$lng['English']?></th>
						<th data-sortable="false"><i class="fa fa-edit fa-lg"></i></th>
						<th data-sortable="false"><i class="fa fa-trash fa-lg"></i></th>
					</tr>
				</thead>
				<tbody>

				</tbody>
			</table>
		</div>
		
	</div>
	
	<!-- Modal ADD HOLIDAY -->
	<div class="modal fade" id="modalHoliday" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
		 <div class="modal-dialog" style="width:700px">
			  <div class="modal-content">
					<div class="modal-header">
						<h4 class="modal-title" id="myModalLabel"><i class="fa fa-language"></i>&nbsp;&nbsp;Add Holiday<? //=$lng['Language variables']?></h4>
					</div>
					<div class="modal-body" style="padding:15px 20px 12px">
					<form class="sform" id="holiForm">
						<input name="id" type="hidden" value="0" />
						<label><?=$lng['Date']?><i class="man"></i></label>
						<input readonly class="holiday_date_month nofocus" style="cursor:pointer" name="date" type="text" />
						<label>Company <?=$lng['Date']?><i class="man"></i></label>
						<input readonly class="holiday_date_month nofocus" style="cursor:pointer" name="cdate" type="text" />
						<label><?=$lng['Thai']?><i class="man"></i></label>
						<input name="th" type="text" />
						<label><?=$lng['English']?><i class="man"></i></label>
						<input name="en" type="text" />
						<div style="height:10px"></div>
						<button class="btn btn-primary btn-fr" type="button" data-dismiss="modal"><i class="fa fa-times"></i> <?=$lng['Cancel']?></button>
						<button class="btn btn-primary btn-fr" type="submit"><i class="fa fa-save"></i> <?=$lng['Update']?></button>
					</form>
					</div>
			  </div>
		 </div>
	</div>

<script>

	var headerCount = 1;

	$(document).ready(function() {
		
		var dtable = $('#holidayTable').DataTable({
			scrollY:       false,
			scrollX:       false,
			scrollCollapse:false,
			fixedColumns:  false,
			lengthChange:  false,
			searching: 		false,
			ordering: 		true,
			paging: 			true,
			pageLength: 	16,
			filter: 			false,
			info: 			false,
			autoWidth:		false,
			<?=$dtable_lang?>
			processing: 	false,
			serverSide: 	true,
			order: [[0, 'asc']],
			ajax: {
				url: "def_settings/ajax/server_get_default_holidays.php",
				data: function(d){
					d.year = $('#selYear').val();
				}
			},
			columnDefs: [
				{ targets: [4,5], class: 'tac',},
				{ targets: [0,1,4,5], "width": '1px',},
			],	
			initComplete : function( settings, json ) {
				$('#showTable').fadeIn(400)
				dtable.columns.adjust().draw()
			}
		});
		
		$('#selYear').on('change', function(){
			//dtable.ajax.reload(null, false);
			dtable.ajax.reload();
		})
		
		$('#addHoliday').on('click', function(){
			$('input[name="id"]').val(0)
			$('#modTitle').html('Add Holiday')
			$('#modalHoliday').modal('toggle')
		})
		
		$(document).on('click', '.editHoliday', function(){
			var id = $(this).data('id');
			$.ajax({
				url: "def_settings/ajax/get_default_holiday.php",
				data: {id: id},
				dataType: 'json',
				success: function(data){
					//$("#dump").html(data);
					$('#modTitle').html('Edit Holiday')
					$('input[name="id"]').val(data.id)
					$('input[name="date"]').val(data.date)
					$('input[name="cdate"]').val(data.cdate)
					$('input[name="th"]').val(data.th)
					$('input[name="en"]').val(data.en)
					$('#modalHoliday').modal('toggle')
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
		})
		
		$('#modalHoliday').on('hidden.bs.modal', function () {
			$("#holiForm").trigger('reset');
			$("#modMessage").hide()
		});

		$("#holiForm").submit(function(e){ 
			e.preventDefault();
			$("#modMessage").hide()
			if($('input[name="date"]').val()=='' || $('input[name="cdate"]').val()=='' || $('input[name="th"]').val()=='' || $('input[name="en"]').val()==''){
				$("body").overhang({
					type: "error",
					message: '<i class="fa fa-exclamation-triangle"></i>&nbsp;&nbsp;<?=$lng['Please fill in all the fields']?>',
					duration: 4,
				})
				return false;
			}
			var data = $(this).serialize();
			$.ajax({
				url: "def_settings/ajax/update_default_holidays.php",
				data: data,
				success: function(result){
					if(result == 'success'){
						$("body").overhang({
							type: "success",
							message: '<i class="fa fa-check"></i>&nbsp;&nbsp;Data updated successfuly',
							duration: 2,
						})
						dtable.ajax.reload(null, false);
						setTimeout(function(){$('#modalHoliday').modal('toggle');},300);
					}else{
						$("body").overhang({
							type: "error",
							message: '<i class="fa fa-exclamation-triangle"></i>&nbsp;&nbsp;<?=$lng['Error']?> : '+result,
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

		$(document).ajaxComplete(function( event,request, settings ) {
			$('.delHoliday').confirmation({
				container: 'body',
				rootSelector: '.delHoliday',
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
					$.ajax({
						url: "def_settings/ajax/delete_default_holiday.php",
						data: {id: $(this).data('id')},
						success: function(result){
							//$('#dump').html(result);
							dtable.ajax.reload(null, false);
						},
						error:function (xhr, ajaxOptions, thrownError){
							$("body").overhang({
								type: "error",
								message: '<i class="fa fa-exclamation-triangle"></i>&nbsp;&nbsp;<?=$lng['Sorry but someting went wrong']?> Error : ' + thrownError,
								duration: 8,
								closeConfirm: "true",
							})
						}
					});
				}
			});
		});
		
		$('.holiday_date_month').datepicker({
			format: "D dd-mm-yyyy",
			autoclose: true,
			inline: true,
			language: '<?=$lang?>-en',//lang+'-th',
			language: 'en-en',//lang+'-th',
			//viewMode: 'years',
			startView: 'year',
			todayHighlight: true,
			//startDate : startYear,
			//endDate   : endYear
		})

	});

</script>	













