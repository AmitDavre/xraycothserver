 
<?php
	
	//var_dump($emp_status);
	$log_cols = array();
		$log_cols[4] = array('user_id','User ID');
		$log_cols[] = array('username','User Name');
		$log_cols[] = array('privacy_consent','Privacy Policy Consent');
		$log_cols[] = array('terms_consent','Terms & conditions Consent');
		$log_cols[] = array('cookie_consent','Cookie Consent');
	
	$sCols[] = 4;
	//$sCols[] = 5;
	//$sCols[] = 6;
	$sCols[] = 7;
	//$sCols[] = 8;
	//var_dump(json_encode($sCols));
	$now = date('d-m-Y');
	$from = date('d-m-Y',(strtotime ('-3 day', strtotime($now))));
	$until = date('d-m-Y',(strtotime ('+1 day', strtotime($now))));

?>
<link rel="stylesheet" type="text/css" media="screen" href="../assets/css/sumoselect.css?<?=time()?>">
<style>
.SumoSelect{
	padding: 5px 5px 5px 10px !important;
	border:1px #ddd solid !important;
}
.SumoSelect.open > .optWrapper {
	top:29px !important; 
}
</style>
	
	<h2><i class="fa fa-database"></i>&nbsp; Confirmation Log Data</h2>
	<div class="main">
		<div style="padding:0 0 0 20px" id="dump"></div>
	
			<div id="showTable" style="display:none">
				
				<div class="searchFilter">
					<input placeholder="<?=$lng['Filter']?>" id="searchFilter" class="sFilter" type="text" />
					<button id="clearSearchbox" type="button" class="clearFilter btn btn-default btn-sm"><i class="fa fa-times"></i></button>
				</div>			
				
<!-- 				<div class="dpicker btn-fl">
					<input readonly placeholder="From" class="xdate_month" id="from" style="width:120px" type="text" value="<?=$from?>" />
					<button data-toggle="tooltip" title="From" onclick="$('#from').focus()" type="button"><i class="fa fa-calendar"></i></button>
				</div>
				
				<div class="dpicker btn-fl">
					<input readonly placeholder="Until" class="xdate_month" id="until" style="width:120px" type="text" value="<?=$until?>" />
					<button data-toggle="tooltip" title="Until" onclick="$('#until').focus()" type="button"><i class="fa fa-calendar"></i></button>
				</div> -->
				
				<select id="typeFilter" class="button btn-fl">
					<option selected value="select">Select</option>
					<option value="showall">Show All</option>
					<option value="showcurrent">Show Current</option>
					<option value="showold">Show Old Logs</option>
				</select>

				<button style="display: none;" class="btn btn-primary" id="subButton" type="button"><i class="fa fa-trash"></i>&nbsp;&nbsp;<?=$lng['Remove Logs']?></button>
				
				<? //if($_SESSION['rego']['access']['employee']['export'] == 1){ ?>
				<!--<button disabled id="expLogdata" type="button" class="btn btn-primary btn-fr"><i class="fa fa-upload"></i> Export data</button>-->	
				<? //} ?>
         <div class="clear"></div>
				    
			<table id="datatable" class="dataTable hoverable selectable nowrap">
				<thead>
				<tr>
					<!-- <th style="width:1px" class="tac vam"><i class="fa fa-user fa-lg"></i></th> -->
					<th >User ID</th>
					<th class="par30">User Name</th>
		<!-- 			<th  class="par30">Privacy Policy Consent</th>
					<th  class="par30">Privacy Policy Consent Date</th>
					<th >Terms & Conditions Consent</th>
					<th >Terms & Conditions Consent Date</th>
					<th >Cookie Consent</th>
					<th >Cookie Consent Date</th> --> 
					<th> Consent Name</th> 
					<th> Date & Time</th>
					<th> Consent Status</th>
	



	

				</tr>
				</thead>

			</table>
			<input type="hidden" id="incomplete" value="0" />
         </div>
			
		</div>
	</div>


	<!-- Modal REMOVE LOGS -->
	<div class="modal fade" id="removeLogs" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
		 <div class="modal-dialog" style="max-width:392px">
			  <div class="modal-content">
					<div class="modal-header">
						 <h4 class="modal-title" id="myModalLabel"><i class="fa fa-trash"></i>&nbsp; <?=$lng['Remove logs from Date']?></h4>
					</div>
					<div class="modal-body" style="padding:10px 25px 25px 25px">
						 <label><?=$lng['Select date']?> <i class="man"></i></label>
						 <input style="width:100%;" readonly="readonly" name="select_remove_date" id="select_remove_date" type="text" />
						 <button id="removeLogBtn" class="btn btn-primary" style="margin-top:15px" type="button"><i class="fa fa-save"></i> <?=$lng['Submit']?></button>
						<button style="float:right;margin-top:15px" type="button" class="btn btn-primary" data-dismiss="modal"><i class="fa fa-times"></i>&nbsp; <?=$lng['Cancel']?></button>
						<div class="clear"></div>
					</div>
					<div class="clear"></div>
			  </div>
		 </div>
	</div>	



	<!-- PAGE RELATED PLUGIN(S) -->
	<script type="text/javascript" src="../assets/js/jquery.sumoselect.js"></script>

	<script type="text/javascript">
		
		var height = window.innerHeight-303;
		var headerCount = 1;
		
		$(document).ready(function() {
			
			var year = <?=json_encode($_SESSION['RGadmin']['cur_year'])?>;
			
			var removeDate = $('#select_remove_date').datepicker({
				format: "dd-mm-yyyy",
				autoclose: true,
				inline: true,
				language: '<?=$lang?>-en',
				startView: 'year',
				todayHighlight: true,

			});			

			var from = $('#from').datepicker({
				format: "dd-mm-yyyy",
				autoclose: true,
				inline: true,
				language: '<?=$lang?>-en',//lang+'-th',
				startView: 'year',
				todayHighlight: true,
				//startDate : startYear,
				//endDate   : endYear
			}).on('changeDate', function(e){
				$('#until').datepicker('setDate', '').datepicker('setStartDate', from.val()).focus();
			});


			
			var until = $('#until').datepicker({
				format: "dd-mm-yyyy",
				autoclose: true,
				inline: true,
				language: '<?=$lang?>-en',//lang+'-th',
				startView: 'year',
				todayHighlight: true,
				//startDate : startYear,
				//endDate   : endYear
			}).on('changeDate', function(e){
				dtable.ajax.reload(null, false);
			});
			
			var mySelect = $('#showCols').SumoSelect({
				//okCancelInMulti:true, 
				//selectAll:true,
				csvDispCount:1,
				outputAsCSV : true,
				showTitle : false,
				placeholder: '<?=$lng['Show Hide Columns']?>',
				captionFormat: '<?=$lng['Show Hide Columns']?>',
				captionFormatAllSelected: '<?=$lng['Show Hide Columns']?>',
			});
			
			var rows = Math.floor(height/29.64);
			
			var dtable = $('#datatable').DataTable({
				scrollY:        false,
				scrollX:        false,
				scrollCollapse: false,
				//fixedColumns:   false,
				lengthChange:  false,
				searching: 		true,
				ordering: 		true,
				paging: 			true,
				pageLength: 	rows,
				filter: 			true,
				info: 			false,
				<?=$dtable_lang?>
				processing: 	false,
				serverSide: 	true,
				//autoWidth:		false,
				order: [[3, 'desc']],
				ajax: {
					url: "ajax/server_get_confirmation_logdata.php",
					type: 'POST',
					data: function(d){

					if($('#searchFilter1').is(":checked")){
						var filterLatest = '1';
					}else{
						var filterLatest = '0';
					}

					var typeFilter = $('#typeFilter').val();

						d.searchFilter1 = filterLatest;
						d.typeFilter = typeFilter;
						// d.from = $('#from').val();
						// d.until = $('#until').val();
					}
				},

				initComplete : function( settings, json ) {
					$('#showTable').fadeIn(200);
					// run ajax here 

					dtable.columns.adjust().draw();




						$.each(json.cols, function(index,value){
							var column = dtable.column(value);

						
		        			// column.visible(!column.visible());				
						})





				},
				"createdRow": function ( row, data, index ) {


	





					$.ajax({
						url: ROOT+"admin/ajax/consent/mark_latest_consent_privacy.php",
						type: 'POST',
						success: function(result){
							var returnedData = JSON.parse(result);
							 $.each(returnedData, function(key,val) {             
								// $('span#consent_log_'+key).closest('tr').css('background','#ffb');
								$('span.consent_log_'+key).css('color','#00a');
								$('span.consent_log_'+key).css('font-weight','600');
								$('span.consent_log_'+key).closest('td').next().css({'font-weight' : '600', 'color' : '#00a'});
								$('span.consent_log_'+key).closest('td').next().next().css({'font-weight' : '600', 'color' : '#00a'});
								$('span.consent_log_'+key).closest('td').next().next().next().css({'font-weight' : '600', 'color' : '#00a'});

						      });
						},
			
					});					

					$.ajax({
						url: ROOT+"admin/ajax/consent/mark_latest_consent_terms.php",
						type: 'POST',
						success: function(result){
							var returnedData = JSON.parse(result);
							 $.each(returnedData, function(key,val) {             
								// $('span#consent_log_'+key).closest('tr').css('background','#ffb');
								$('span.consent_log_'+key).css('color','#00a');
								$('span.consent_log_'+key).css('font-weight','600');
								$('span.consent_log_'+key).closest('td').next().css({'font-weight' : '600', 'color' : '#00a'});
								$('span.consent_log_'+key).closest('td').next().next().css({'font-weight' : '600', 'color' : '#00a'});
								$('span.consent_log_'+key).closest('td').next().next().next().css({'font-weight' : '600', 'color' : '#00a'});
						      });
						},
			
					});					

					$.ajax({
						url: ROOT+"admin/ajax/consent/mark_latest_consent_cookie.php",
						type: 'POST',
						success: function(result){
							var returnedData = JSON.parse(result);
							 $.each(returnedData, function(key,val) {             
								// $('span#consent_log_'+key).closest('tr').css('background','#ffb');
								$('span.consent_log_'+key).css('color','#00a');
								$('span.consent_log_'+key).css('font-weight','600');
								$('span.consent_log_'+key).closest('td').next().css({'font-weight' : '600', 'color' : '#00a'});
								$('span.consent_log_'+key).closest('td').next().next().css({'font-weight' : '600', 'color' : '#00a'});
								$('span.consent_log_'+key).closest('td').next().next().next().css({'font-weight' : '600', 'color' : '#00a'});
						      });
						},
			
					});					

					$.ajax({
						url: ROOT+"admin/ajax/consent/mark_old_consent.php",
						type: 'POST',
						success: function(result){
							var returnedData = JSON.parse(result);
							 $.each(returnedData, function(key,val) {    

		
								$('span.consent_log_'+key).closest('td').next().next().next().next().css({'opacity' : '0.65'});
						      });
						},
			
					});

			
						
				
				}
			});
			setTimeout(function(){
				//$("#statFilter").trigger('change');
			},50);
			$("#searchFilter").keyup(function() {
				var s = $(this).val();
				dtable.search(s).draw();



				// dtable.search('255, 255, 187').draw();



			});


			$(document).on("change", "#typeFilter", function(e) {
				var dropdownV = $(this).val();
				if(dropdownV == 'showold')
				{
					$('#subButton').css('display','block');

				}
				else
				{
					$('#subButton').css('display','none');
				}
			});			

			$(document).on("click", "#subButton", function(e) {
	
				$('#removeLogs').modal('show');
				
			});


			$(document).on("click", "#searchFilter1", function(e) {
				// $('#searchFilter').val('');
				// dtable.search('').draw();

					$.ajax({
						url: ROOT+"admin/ajax/consent/mark_latest_combine.php",
						type: 'POST',
						success: function(result){
							var returnedData = JSON.parse(result);
							 $.each(returnedData, function(key,val) {    

									var s = $("#searchFilter").val();

									if($('#searchFilter1').is(":checked")){
										var filterLatest = '1';
									}else{
										var filterLatest = '0';
									}

									if(filterLatest == '1')
									{
										dtable.search(key).draw();
									}
									else
									{
										dtable.search(s).draw();
									}



						      });
						},
			
					});	




			})			


			$(document).on("click", "#clearSearchbox", function(e) {
				$('#searchFilter').val('');
				dtable.search('').draw();
			})

			$(document).on("change", "#typeFilter", function(e) {
				dtable.ajax.reload(null, false);
			})
			$(document).on("change", "#depFilter", function(e) {
				var s = $(this).val();
				dtable.column(5).search(s).draw();
			})

			$(document).on("click", "#exportEmployees", function(e){
				$("#modalExportFields").modal('toggle');
			})
			$('#exportForm').on("submit", function(e) {
				e.preventDefault();
				var data = $(this).serialize();
				$.ajax({
					url: ROOT+"employees/ajax/update_employee_export_fields.php",
					data: data,
					type: 'POST',
					success: function(result){
						//$('#dump').html(result);
						window.location.href = 'employees/export_employee_register_excel.php?'+$('#action').val();
						$("#modalExportFields").modal('toggle');
					},
					error:function (xhr, ajaxOptions, thrownError){
						$('#message').html('<div style="margin:0" class="msg_alert">'+thrownError+'</div>').hide().fadeIn(400);
					}
				});
			})			


			$('#removeLogBtn').on("click", function(e) {
				var removeDate= $('#select_remove_date').val();

				if(removeDate == '')
				{
						$("body").overhang({
							type: "error",
							message: '<i class="fa fa-exclamation-triangle"></i>&nbsp;&nbsp;<?=$lng['Sorry but someting went wrong']?> <b><?=$lng['Error']?></b> : Please select the date to proceed.',
							duration: 8,
							closeConfirm: "true",
						})

					return false;
				}


				$.ajax({
					url: "ajax/remove_consent_log_data.php",
					data: { removeDate: removeDate },
					success: function(result){
						if($.trim(result) == 'success'){
							$("body").overhang({
								type: "success",
								message: '<i class="fa fa-check"></i>&nbsp;&nbsp;Consent log deleted successfuly',
								duration: 2,
							})
							setTimeout(function(){
								// location.href = ROOT+'index.php?mn=461';
								$('#removeLogs').modal('hide');
								dtable.columns.adjust().draw();
							},1000);
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
							message: '<i class="fa fa-exclamation-triangle"></i>&nbsp;&nbsp;<?=$lng['Sorry but someting went wrong']?> <b><?=$lng['Error']?></b> : '+thrownError,
							duration: 8,
							closeConfirm: "true",
						})
					}
				});

			})	
				
			
		})
	
	</script>





































