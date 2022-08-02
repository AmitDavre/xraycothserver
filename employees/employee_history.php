<?php
	
	if(!$_SESSION['rego']['employee_history']['view']){ 
		echo '<div class="msg_nopermit">You have no access to this page</div>'; exit;
	}
//
	$update = 1;
	if(isset($_SESSION['rego']['empID'])){ // EDIT EMPLOYEE ////////////////////////////////////////////////
		$empID = $_SESSION['rego']['empID'];
		$res = $dbc->query("SELECT emp_id, image, th_name, en_name, personal_phone, personal_email, joining_date, med_attachments, med_contact, med_phone, med_smoker, med_alert, med_allergies, med_disabilities, med_medication FROM ".$cid."_employees WHERE emp_id = '".$empID."'");
		$data = $res->fetch_assoc();
		if(empty($data['image'])){$data['image'] = 'images/profile_image.jpg';}
	}
	//var_dump($data); exit;

	$medical_attach = unserialize($data['med_attachments']);
	if(empty($medical_attach)){
		$medical_attach = array();
	}
	
?>
	
	<style>
		input, select, textarea {
			background:transparent !important;
		}
		.submitBtn {
			top:0 !important;
			right:0;
		}
		
	</style>

   <h2 style="position:relative">
		<span><i class="fa fa-users fa-mr"></i> <?=$lng['Historical records']?>&nbsp; <i class="fa fa-arrow-circle-right"></i> </span>
		<span><?=$data['emp_id']?> : <?=$data[$lang.'_name']?></span>
		<span style="display:none; font-style:italic; color:#b00; padding-left:30px" id="sAlert"><i class="fa fa-exclamation-triangle fa-mr"></i><?=$lng['Data is not updated to last changes made']?></span>
	</h2>
	
	<? include('employee_image_inc.php')?>
	
	<div class="pannel main_pannel employee-profile">
			
		<ul class="nav nav-tabs">
			<!--<li class="nav-item"><a class="nav-link" href="#tab_his_log" data-toggle="tab"><?=$lng['Log']?></a></li>-->
			<li class="nav-item"><a class="nav-link" href="#tab_his_career" data-toggle="tab"><?=$lng['Career']?></a></li>
			<li class="nav-item"><a class="nav-link" href="#tab_his_medical" data-toggle="tab"><?=$lng['Medical']?></a></li>
			<li class="nav-item"><a class="nav-link" href="#tab_his_discipline" data-toggle="tab"><?=$lng['Discipline']?></a></li>
		</ul>

		<div class="tab-content" style="height:calc(100% - 30px)">
			<div style="padding:0 0 0 20px" id="dump"></div>
			
			<div class="tab-pane" id="tab_his_log">
				<div id="showTable" style="display:none">
					<table class="basicTable" id="logTable">
						<thead>
							<tr>
								<th><?=$lng['Field']?></th>
								<th><?=$lng['Previous value']?></th>
								<th><?=$lng['Current value']?></th>
								<th><?=$lng['Date']?></th>
								<th style="width:50%"><?=$lng['Changed by']?></th>
							</tr>
						</thead>
						<tbody>

						</tbody>
					</table>
				</div>
			</div>
			
			<div class="tab-pane" id="tab_his_career">
				<table style="width:100%; height:100%">
					<tr>
						<td style="width:50%; padding-right:15px; vertical-align:top">
							<div id="caTable" style="display:none">
								<table id="career_table" class="dataTables nowrap hoverable selectable">
									<thead>
										<tr>
											<th data-sortable="false"><?=$lng['Start date']?></th>
											<th data-sortable="false" style="width:40%"><?=$lng['Position']?></th>
											<th data-sortable="false" style="width:40%"><?=$lng['Department']?></th>
											<th data-sortable="false"><?=$lng['Salary']?></th>
											<th data-visible="false">x</th>
										</tr>
								 </thead>
								</table>
								<button id="addCareer" class="btn btn-primary btn-xs" style="margin:8px 0 0 0" type="button"><i class="fa fa-plus fa-mr"></i><?=$lng['Add Career']?></button>
							</div>
						</td>
						<td id="caColor" style="width:50%; background:rgba(200,255,200,0.1); padding:0px; vertical-align:top">
							<div style="overflow-y:auto; height:100%; border:1px solid #eee; padding:1px 5px 5px; position:relative">
							<form id="careerForm">
								<input type="hidden" name="id" id="caID" value="0">
								<input type="hidden" name="field" value="career">
								<input type="hidden" name="emp_id" value="<?=$empID?>">
								<table id="careerTable" class="basicTable nowrap" style="width:100%; display:none">
									<thead>
										<tr style="background:transparent">
											<th colspan="4"><?=$lng['Career path']?> <span id="caAction"></span></th>
										</tr>
									</thead>	
									<tbody>
										<tr>
											<th><i class="man"></i><?=$lng['Position']?></th>
											<td style="padding:0">
												<input type="text" name="position" placeholder="...">
											</td>
										</tr>
										<tr>
											<th><?=$lng['Department']?></th>
											<td style="padding:0">
												<input type="text" name="department" placeholder="...">
											</td>
										</tr>
										<tr>
											<th><i class="man"></i> <?=$lng['Start date']?></th>
											<td colspan="3" style="padding:0">
												<input readonly style="cursor:pointer" class="datepick"  type="text" name="start_date" placeholder="...">
											</td>
										</tr>
										<tr>
											<th><?=$lng['End date']?></th>
											<td colspan="3" style="padding:0">
												<input readonly style="cursor:pointer" class="datepick"  type="text" name="end_date" placeholder="...">
											</td>
										</tr>
										<tr>
											<th><?=$lng['Salary']?></th>
											<td style="padding:0">
												<input type="text" name="salary" placeholder="...">
											</td>
										</tr>
										<tr>
											<th><?=$lng['Benefits']?></th>
											<td style="padding:0">
												<textarea data-autoresize style="resize:vertical" rows="2" name="benefits" placeholder="..."></textarea>
											</td>
										</tr>
										<tr>
											<th><?=$lng['Classification']?></th>
											<td style="padding:0">
												<textarea data-autoresize style="resize:vertical" rows="2" name="classification" placeholder="..."></textarea>
											</td>
										</tr>
										<tr>
											<th><?=$lng['Remarks']?></th>
											<td colspan="3" style="padding:0">
												<textarea data-autoresize style="resize:vertical" rows="2" name="remarks" placeholder="..."></textarea>
											</td>
										</tr>
										<tr style="border:0">
											<td colspan="4"><b style="display:block; margin-bottom:3px"><?=$lng['Attachments']?></b>
												<div id="caAttach"></div>
												<div id="attachCareer" style="clear:both">
													<input style="margin:0 0 5px 0" class="attachBtn" name="attachment[]" type="file" />
												</div>
											</td>
										</tr>
									</tbody>
								</table>
								<button id="caBtn" class="btn btn-primary" style="position:absolute; top:5px; right:5px; display:none" type="submit"><i class="fa fa-save"></i>&nbsp;&nbsp;<?=$lng['Update']?> <?=$lng['Career']?></button>
							</form>
							</div>
						</td>
					</tr>
				</table>
			</div>
			
			<div class="tab-pane" id="tab_his_medical">
				<table style="width:100%; height:100%">
					<tr>
						<td style="width:50%; padding-right:15px; vertical-align:top">
							<div id="meTable" style="display:none">
								<table id="medical_table" class="dataTables nowrap hoverable selectable">
									<thead>
										<tr>
											<th><?=$lng['Date']?></th>
											<th data-sortable="false" style="width:80%"><?=$lng['Condition']?></th>
											<th data-sortable="false"><?=$lng['Certificate']?></th>
											<th data-visible="false">x</th>
										</tr>
								 </thead>
								</table>
								<button id="addMedical" class="btn btn-primary btn-xs" style="margin:8px 0 0 0" type="button"><i class="fa fa-plus fa-mr"></i><?=$lng['Add Medical']?></button>
							</div>
						</td>
						<td id="meColor" style="width:50%; background:rgba(200,255,200,0.1); padding:0px; vertical-align:top">
							<div style="overflow-y:auto; height:100%; border:1px solid #eee; padding:1px 5px 5px; position:relative">
								
								<table id="meRecord" class="basicTable">
									<thead>
										<tr style="cursor:pointer; background:transparent">
											<th><i style="width:8px" class="fa fa-caret-right fa-lg"></i>&nbsp; <?=$lng['Medical record']?></th>
										</tr>
									</thead>
								</table>
								
								<div id="recDiv" style="display:xnone; padding-bottom:5px; border-bottom:1px solid #ccc">
								<form id="recordForm" style="height:100%">
									<input name="emp_id" type="hidden" value="<?=$empID?>">
									<table id="medicalTable" class="basicTable nowrap inputs" style="width:100%; display:xnone">
										<tbody>
											<tr>
												<th><?=$lng['Medical contact']?></th>
												<td><input type="text" name="med_contact" placeholder="..." value="<?=$data['med_contact']?>"></td>
											</tr>
											<tr>
												<th><?=$lng['Phone']?></th>
												<td><input type="text" name="med_phone" placeholder="..." value="<?=$data['med_phone']?>"></td>
											</tr>
											<tr>
												<th><?=$lng['Smoker']?></th>
												<td colspan="3">
													<select name="med[smoker" style="width:100%">
														<!--<option selected disabled><? //=$lng['Select']?></option>-->
														<? foreach($yesno as $k=>$v){ ?>
															<option <? if($data['med_smoker'] == $k){echo 'selected';}?> value="<?=$k?>"><?=$v?></option>
														<? } ?>
													</select>
												</td>
											</tr>
											<tr>
												<th><?=$lng['Medical alert']?></th>
												<td colspan="3"><textarea data-autoresize style="resize:vertical" name="med_alert" rows="2" placeholder="..."><?=$data['med_alert']?></textarea></td>
											</tr>
											<tr>
												<th><?=$lng['Allergies']?></th>
												<td colspan="3"><textarea data-autoresize style="resize:vertical" name="med_allergies" rows="2" placeholder="..."><?=$data['med_allergies']?></textarea></td>
											</tr>
											<tr>
												<th><?=$lng['Disabilities']?></th>
												<td colspan="3"><textarea data-autoresize style="resize:vertical" name="med_disabilities" rows="2" placeholder="..."><?=$data['med_disabilities']?></textarea></td>
											</tr>
											<tr>
												<th><?=$lng['Medication']?></th>
												<td colspan="3"><textarea data-autoresize style="resize:vertical" name="med_medication" rows="2" placeholder="..."><?=$data['med_medication']?></textarea></td>
											</tr>
											<tr style="border:0">
												<td colspan="2" style="padding:4px 8px !important"><b style="display:block; margin-bottom:3px"><?=$lng['Attachments']?></b>
													<div id="meAttach">
													<? if($medical_attach){ foreach($medical_attach as $k=>$v){ ?>
														<div class="attachDiv">
															<a target="_blank" href="<?=ROOT.$cid.'/medical/'.$v?>" class="link"><?=$v?></a>
															<a data-key="<?=$k?>" class="icon delAttach"><i class="fa fa-trash"></i></a>
														</div>
													<? } } ?>
													</div>
													<div id="attachRecord" style="clear:both">
														<input style="margin:0 0 5px 0" class="attachBtn" name="attachment[]" type="file" />
													</div>
												</td>
											</tr>
										</tbody>
									</table>
								</form>
								</div>
								
								<table id="meHistory" class="basicTable">
									<thead>
										<tr style="cursor:default; background:transparent">
											<th colspan="4"><i style="width:8px" class="fa fa-caret-down fa-lg"></i>&nbsp; <?=$lng['Medical history']?> <span id="hiAction"></span></th>
										</tr>
									</thead>
								</table>
								<div id="hisDiv" style="display:none">
									<form id="historyForm">
										<input type="hidden" name="id" id="hiID" value="0">
										<input type="hidden" name="emp_id" value="<?=$empID?>">
										<table id="medHist" class="basicTable nowrap inputs" style="margin-top:5px">
											<tbody>
												<tr>
													<th><i class="man"></i><?=$lng['Date']?></th>
													<td><input readonly style="cursor:pointer" type="text" name="date_from" placeholder="..." class="datepick"></td>
													<th><?=$lng['Until']?></th>
													<td><input readonly style="cursor:pointer" type="text" name="date_until" placeholder="..." class="datepick"></td>
												</tr>
												<tr>
													<th><i class="man"></i><?=$lng['Condition']?></th>
													<td><input type="text" name="emp_condition" placeholder="..."></td>
												</tr>
												<tr>
													<th><?=$lng['Certificate']?></th>
													<td colspan="3" style="padding:2px 0 0 10px !important">
														<input style="margin:0; display:none" name="certificate" id="certificate" type="file" />
														<button id="certificateBtn" onClick="$('#certificate').click()" style="float:right" class="btn btn-default btn-xs" type="button"><?=$lng['Choose file']?></button>
														<a target="_blank" href="#" style="float:left; padding-top:2px" id="empCertificate"></a>
													</td>
												</tr>
												<tr>
													<th><?=$lng['Doctor']?></th>
													<td colspan="3"><input type="text" name="doctor" placeholder="..."></td>
												</tr>
												<tr>
													<th><?=$lng['Remarks']?></th>
													<td colspan="3"><textarea data-autoresize style="resize:vertical" rows="2" name="remarks" rows="2" placeholder="..."></textarea></td>
												</tr>
												<tr style="border:0">
													<td colspan="4" style="padding:4px 10px !important"><b style="display:block; margin-bottom:3px"><?=$lng['Attachments']?></b>
														<div id="hiAttach"></div>
														<div id="attachHistory" style="clear:both">
															<input style="margin:0 0 5px 0" class="attachBtn" name="attachment[]" type="file" />
														</div>
													</td>
												</tr>
											</tbody>
										</table>
									</form>
								</div>
								<!--<div style="position:absolute; top:5px; right:5px; padding:0 0 0 10px; background:#fff">-->
								<button id="meBtn" style="position:absolute; top:5px; right:5px;" class="btn btn-primary" type="button"><i class="fa fa-save fa-mr"></i> <?=$lng['Update']?> <?=$lng['Record']?></button><!--</div>-->
								<button id="hiBtn" class="btn btn-primary" style="position:absolute; top:5px; right:5px; display:none" type="button"><i class="fa fa-save fa-mr"></i> <?=$lng['Update']?> <?=$lng['History']?></button>
							</div>
						</td>
					</tr>
				</table>
			</div>
			
			<div class="tab-pane" id="tab_his_discipline">
				<table style="width:100%; height:100%">
					<tr>
						<td style="width:50%; padding-right:15px; vertical-align:top">
							<div id="diTable" style="display:none">
								<table id="discipline_table" class="dataTables nowrap hoverable selectable">
									<thead>
										<tr>
											<th><?=$lng['Date']?></th>
											<th data-sortable="false"><?=$lng['Type of warning']?></th>
											<th data-sortable="false" style="width:80%"><?=$lng['Type of violation']?></th>
											<th data-sortable="false"><?=$lng['Status']?></th>
											<th data-visible="false">x</th>
										</tr>
								 </thead>
								</table>
								<button id="addDiscipline" class="btn btn-primary btn-xs" style="margin:8px 0 0 0" type="button"><i class="fa fa-plus fa-mr"></i><?=$lng['Add Discipline']?></button>
							</div>
						</td>
						<td id="diColor" style="width:50%; background:rgba(200,255,200,0.1); padding:0px; vertical-align:top">
							<div style="overflow-y:auto; height:100%; border:1px solid #eee; padding:1px 5px 5px; position:relative">
							<form id="disciplineForm">
								<input type="hidden" name="id" id="diID" value="0">
								<input type="hidden" name="field" value="discipline">
								<input type="hidden" name="emp_id" value="<?=$empID?>">
								<table id="disciplineTable" class="basicTable nowrap" style="width:100%; display:none">
									<thead>
										<tr style="background:transparent">
											<th colspan="2"><?=$lng['Warning']?> <span id="diAction"></span></th>
										</tr>
									</thead>	
									<tbody>
										<tr>
										<th><i class="man"></i><?=$lng['Date']?></th>
											<td style="padding:0">
												<input readonly style="cursor:pointer" class="datepick"  type="text" name="date" placeholder="..." value="<? //=$birthdate?>">
											</td>
										</tr>
										<tr>
											<th><i class="man"></i><?=$lng['Type of warning']?></th>
											<td colspan="3" style="padding:0">
												<select name="warning">
													<option value="0" selected disabled><?=$lng['Select']?></option>
													<? foreach($warnings as $k=>$v){ ?>
														<option value="<?=$k?>"><?=$v?></option>
													<? } ?>
												</select>
											</td>
										</tr>
										<tr>
											<th><i class="man"></i><?=$lng['Type of violation']?></th>
											<td colspan="3" style="padding:0">
												<select name="violation">
													<option value="0" selected disabled><?=$lng['Select']?></option>
													<? foreach($violations as $k=>$v){ ?>
														<option value="<?=$k?>"><?=$v?></option>
													<? } ?>
												</select>
											</td>
										</tr>
										<tr>
											<th><?=$lng['Status']?></th>
											<td style="padding:0">
												<select name="status">
													<? foreach($oc_status as $k=>$v){ ?>
														<option value="<?=$k?>"><?=$v?></option>
													<? } ?>
												</select>
											</td>
										</tr>
										<tr>
											<td colspan="2"><b><?=$lng['Description of Infraction']?></b>
												<textarea data-autoresize name="infraction" style="padding:0 8px; resize:vertical" rows="2" placeholder="..."></textarea>
											</td>
										</tr>
										<tr>
										<tr>
											<td colspan="2" style="padding:3px 8px"><b><?=$lng['Damage caused']?> (<?=$lng['THB']?>) : </b>
												<input style="width:150px" type="text" class="numeric sel" name="damage" placeholder="..." value="">
											</td>
										</tr>
										<tr>
											<td colspan="2"><b><?=$lng['Plan for Improvement']?></b><br>
												<textarea data-autoresize name="improvement" style="padding:0 8px; resize:vertical" rows="2" placeholder="..."></textarea>
											</td>
										</tr>
										<tr>
											<td colspan="2"><b><?=$lng['Consequences of Further Infractions']?></b>
												<textarea data-autoresize name="consequences" style="padding:0 8px; resize:vertical" rows="2" placeholder="..."></textarea>
											</td>
										</tr>
										<tr>
											<td colspan="2"><b><?=$lng['Employee statement']?></b><br>
												<textarea data-autoresize name="employee" style="padding:0 8px; resize:vertical" rows="2" placeholder="..."></textarea>
											</td>
										</tr>
										<tr>
											<td colspan="2"><b><?=$lng['Employer statement']?></b><br>
												<textarea data-autoresize name="employer" style="padding:0 8px; resize:vertical" rows="2" placeholder="..."></textarea>
											</td>
										</tr>
										<tr>
											<td colspan="2"><b><?=$lng['Witness']?></b><br>
												<textarea data-autoresize name="witness" style="padding:0 8px; resize:vertical" rows="2" placeholder="..."></textarea>
											</td>
										</tr>
										<tr style="border:0">
											<td colspan="2"><b style="display:block; margin-bottom:3px"><?=$lng['Attachments']?></b>
												<div id="diAttach"></div>
												<div id="attachDiscipline" style="clear:both">
													<input style="margin:0 0 5px 0" class="attachBtn" name="attachment[]" type="file" />
												</div>
											</td>
										</tr>
									</tbody>
								</table>
								<button id="diBtn" class="btn btn-primary" style="position:absolute; top:5px; right:5px; display:none" type="submit"><i class="fa fa-save fa-mr"></i> <?=$lng['Update']?> <?=$lng['Discipline']?></button>
							</form>
							</div>
						</td>
					</tr>
				</table>
			</div>
			
		</div>
		
	</div>
		
	<? include('employee_new_edit_script.php')?>

	<script>
		
	$(document).ready(function() {
		
		var update = <?=json_encode($update)?>;
		var emp_id = <?=json_encode($_SESSION['rego']['empID'])?>;
	
	
	// LOG ///////////////////////////////////////////////////////////////////////////////
		var logTable = $('#logTable').DataTable({
			scrollY: false,
			scrollX: false,
			//scrollCollapse: false,
			//fixedColumns:   true,
			lengthChange: false,
			searching: true,
			ordering: true,
			paging: true,
			pageLength: 18,
			filter: true,
			info: false,
			<?=$dtable_lang?>
			processing: false,
			serverSide: true,
			order: [3, 'desc'],
			ajax: {
				url: ROOT+"employees/ajax/server_get_log.php",
				type: 'POST',
				"data": function(d){
					d.emp_id = emp_id;
				}
			},
			columnDefs: [
				  //{"targets": [1], "class": 'pad1' },
				  //{"targets": [3], "width": '80%' },
				  //{"targets": sCols, "visible": true },
			],
			initComplete : function( settings, json ) {
				$('#showTable').fadeIn(400);
				logTable.columns.adjust().draw();
			}
		});

	
	// CAREER FORM ///////////////////////////////////////////////////////////////////////////////
		var caTable = $('#career_table').DataTable({
			scrollY: false,
			scrollX: false,
			lengthChange: false,
			searching: false,
			ordering: false,
			paging: false,
			filter: false,
			info: false,
			<?=$dtable_lang?>
			processing: false,
			serverSide: true,
			//order: [0, 'desc'],
			ajax: {
				url: "ajax/server_get_career.php",
				type: 'POST',
				"data": function(d){
					d.emp_id = emp_id;
				}
			},
			columnDefs: [
				  //{"targets": [1], "class": 'pad1' },
				  //{"targets": [3], "width": '80%' },
				  //{"targets": sCols, "visible": true },
			],
			initComplete : function( settings, json ) {
				$('#caTable').fadeIn(200);
				caTable.columns.adjust().draw();
			}
		});
		$(document).on('click','#career_table tbody tr', function(){
			var id = caTable.row(this).data()[4];
			$.ajax({
				url: "ajax/get_employee_benefit.php",
				type: "POST", 
				data: {id: id, field: 'career'},
				dataType: 'json',
				success: function(data){
					//$("#dump").html(data); //return false;
					$('#caID').val(data.id);
					$('#careerForm input[name="position"]').val(data.position)
					$('#careerForm input[name="department"]').val(data.department)
					$('#careerForm input[name="start_date"]').val(data.start_date)
					$('#careerForm input[name="end_date"]').val(data.end_date)
					$('#careerForm input[name="salary"]').val(data.salary)
					$('#careerForm textarea[name="benefits"]').val(data.benefits)
					$('#careerForm textarea[name="classification"]').val(data.classification)
					$('#careerForm textarea[name="remarks"]').val(data.remarks)
					$('#caAttach').empty();
					$.each(data.attachment, function(i,val){
						$('#caAttach').append(
							'<div class="attachDiv">'+
								'<a target="_blank" href="<?=ROOT.$cid?>/career/'+val+'"class="link">'+val+'</a>'+
								'<a data-key="'+i+'" class="icon delAttach"><i class="fa fa-trash"></i></a>'+
							'</div>'
						)
					})
					$("#caColor").css('background', 'rgba(200,255,200,0.1)');
					$("#careerTable").show();
					$("#caBtn").show();
					$("#caAction").html('- Edit');
					autosize.destroy(document.querySelectorAll('textarea'));
					autosize(document.querySelectorAll('textarea'));
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
		$("#addCareer").on('click', function(e){
			$("#careerForm").trigger('reset');
			$('#caAttach').empty();
			$("#caID").val(0);
			$("#careerTable").show();
			$("#caBtn").show();
			$("#caAction").html('- New');
			$("#caColor").css('background', 'rgba(255,200,200,0.08)');
			$("table#career_table tr").removeClass("selected");
		})
		$("#careerForm").on('submit', function(e){
			e.preventDefault();
			var err = 0;		
			if($('#careerForm input[name="position"]').val() == ""){err = 1}
			if($('#careerForm input[name="start_date"]').val() == ""){err = 1}
			if(err){
				$("body").overhang({
					type: "error",
					message: '<i class="fa fa-exclamation-triangle"></i>&nbsp;&nbsp;<?=$lng['Please fill in required fields']?>',
					duration: 4,
				})
				return false;
			}
			$("#caBtn i").removeClass('fa-save').addClass('fa-repeat fa-spin');
			var formData = new FormData($('#careerForm')[0]);
			$.ajax({
				url: "ajax/update_benefits.php",
				type: "POST", 
				data: formData,
				dataType: 'json',
				cache: false,
				contentType: false,
				processData: false,
				success: function(data){
					//$("#dump").html(data); return false;
					if(data.result == 'success'){
						$("body").overhang({
							type: "success",
							message: '<i class="fa fa-check"></i>&nbsp;&nbsp;<?=$lng['Data updated successfully']?>',
							duration: 2,
						})
						$('#sAlert').fadeOut(200);
						caTable.ajax.reload(null, false);
						if(data.attachment != ''){
							$('#caAttach').empty();
							$('#attachCareer').empty();
							$('#attachCareer').append('<input style="margin:0 0 5px 0" class="attachBtn" name="attachment[]" type="file" />');
							$.each(data.attachment, function(i,val){
								$('#caAttach').append(
									'<div class="attachDiv">'+
										'<a target="_blank" href="<?=ROOT.$cid?>/career/'+val+'"class="link">'+val+'</a>'+
										'<a data-key="'+i+'" class="icon delAttach"><i class="fa fa-trash"></i></a>'+
									'</div>'
								)
							})
						}
					}else{
						$("body").overhang({
							type: "error",
							message: '<i class="fa fa-exclamation-triangle"></i>&nbsp;&nbsp;<?=$lng['Error']?> : '+data.result,
							duration: 4,
						})
					}
					setTimeout(function(){
						$("#caBtn i").removeClass('fa-repeat fa-spin').addClass('fa-save');
						$("#caBtn").removeClass('flash');
					},500);
				},
				error:function (xhr, ajaxOptions, thrownError){
					$("body").overhang({
						type: "error",
						message: '<i class="fa fa-exclamation-circle"></i>&nbsp;&nbsp;<?=$lng['Sorry but someting went wrong']?> <b><?=$lng['Error']?></b> : '+thrownError,
						duration: 4,
					})
					setTimeout(function(){
						$("#caBtn i").removeClass('fa-repeat fa-spin').addClass('fa-save');
						$("#caBtn").removeClass('flash');
					},500);
				}
			});
		})
		$(document).on('change', "#careerForm .attachBtn", function(e){
			readFileURL(this, '#attachCareer');
			$("#caBtn").addClass('flash');
			$('#sAlert').fadeIn(200);
		})
		$(document).on("click", "#careerForm .delAttach", function(e){
			var key = $(this).data('key')
			var app = $(this);
			$.ajax({
				url: "ajax/delete_benefit_attach.php",
				data: {id: $("#caID").val(), key: key, field: 'career'},
				success: function(result){
					//$('#dump').html(result);
					app.parent().remove();
				},
				error:function (xhr, ajaxOptions, thrownError){
					$("body").overhang({
						type: "error",
						message: '<i class="fa fa-exclamation-circle"></i>&nbsp;&nbsp;<?=$lng['Sorry but someting went wrong']?>&nbsp;&nbsp;<b><?=$lng['Error']?></b> : '+thrownError,
						duration: 4,
					})
				}
			});
		});
		$('#careerForm input, #careerForm textarea').on('keyup', function(e){
			$('#sAlert').fadeIn(200);
			$("#caBtn").addClass('flash');
		})
		$('#careerForm .datepick').datepicker({
			format: "dd-mm-yyyy",
			autoclose: true,
			inline: true,
			language: lang,//lang+'-th',
			todayHighlight: true,
		}).on('changeDate', function(ev){
			$('#sAlert').fadeIn(200);
			$("#caBtn").addClass('flash');
		});		


	// MEDICAL FORM //////////////////////////////////////////////////////////////////////////////
		var meTable = $('#medical_table').DataTable({
			scrollY: false,
			scrollX: false,
			lengthChange: false,
			searching: false,
			ordering: true,
			paging: false,
			filter: false,
			info: false,
			<?=$dtable_lang?>
			processing: false,
			serverSide: true,
			order: [0, 'desc'],
			ajax: {
				url: ROOT+"employees/ajax/server_get_medical.php",
				type: 'POST',
				"data": function(d){
					d.emp_id = emp_id;
				}
			},
			columnDefs: [
				  //{"targets": [1], "class": 'pad1' },
				  //{"targets": [3], "width": '80%' },
				  //{"targets": sCols, "visible": true },
			],
			initComplete : function( settings, json ) {
				$('#meTable').fadeIn(400);
				meTable.columns.adjust().draw();
			}
		});
		
		$(document).on('click','#medical_table tbody tr', function(){
			var id = meTable.row(this).data()[3];
			$.ajax({
				url: "ajax/get_employee_benefit.php",
				type: "POST", 
				data: {id: id, field: 'medical'},
				dataType: 'json',
				success: function(data){
					$('#hiAction').html('- Edit');
					$("#hiID").val(data.id);
					$('#historyForm input[name="date_from"]').val(data.date_from)
					$('#historyForm input[name="date_until"]').val(data.date_until)
					$('#historyForm input[name="emp_condition"]').val(data.emp_condition)
					$('#historyForm #empCertificate').html(data.certificate)
					$('#historyForm #empCertificate').attr('href', '<?=ROOT.$cid?>/medical/'+data.certificate)
					$('#historyForm input[name="doctor"]').val(data.doctor)
					$('#historyForm textarea[name="remarks"]').val(data.remarks)
					$('#hiAttach').empty();
					$.each(data.attachment, function(i,val){
						$('#hiAttach').append(
							'<div class="attachDiv">'+
								'<a target="_blank" href="<?=ROOT.$cid?>/medical/'+val+'"class="link">'+val+'</a>'+
								'<a data-key="'+i+'" class="icon delAttach"><i class="fa fa-trash"></i></a>'+
							'</div>'
						)
					})
					$("#meColor").css('background', 'rgba(200,255,200,0.1)');
					$('#meRecord thead tr th i').removeClass('fa-caret-right').addClass('fa-caret-down')
					$('#recDiv').slideUp(200);
					$('#meHistory thead tr th i').removeClass('fa-caret-down').addClass('fa-caret-right')
					$('#hisDiv').slideDown(200);
					$('#meBtn').hide()
					$('#hiBtn').show()
					autosize.destroy(document.querySelectorAll('textarea'));
					autosize(document.querySelectorAll('textarea'));
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
		$("#addMedical").on('click', function(e){
			$('#meRecord thead tr th i').removeClass('fa-caret-right').addClass('fa-caret-down')
			$('#recDiv').slideUp(200);
			$('#meHistory thead tr th i').removeClass('fa-caret-down').addClass('fa-caret-right')
			$('#hisDiv').slideDown(200);
			$("#historyForm").trigger('reset');
			$('#hiAttach').empty();
			$('#hiAction').html('- New');
			$('#empCertificate').html('');
			$('#empCertificate').attr('href', '#');
			$('#certificateBtn').html('Choose file');
			$("#hiID").val(0);
			$("#meColor").css('background', 'rgba(255,200,200,0.08)');
			$("table#medical_table tr").removeClass("selected");
			$('#meBtn').hide()
			$('#hiBtn').show()
		})
		$(document).on('change', "#certificate", function(e){
			readCertificateURL(this);
			$('#sAlert').fadeIn(200);
			$("#hiBtn").addClass('flash');
		})
		$(document).on('change', "#historyForm .attachBtn", function(e){
			readFileURL(this, '#attachHistory');
			$("#hiBtn").addClass('flash');
			$('#sAlert').fadeIn(200);
		})
		$(document).on("click", "#historyForm .delAttach", function(e){
			var key = $(this).data('key')
			var app = $(this);
			$.ajax({
				url: "ajax/delete_benefit_attach.php",
				data: {id: $("#hiID").val(), key: key, field: 'medical'},
				success: function(result){
					//$('#dump').html(result);
					app.parent().remove();
				},
				error:function (xhr, ajaxOptions, thrownError){
					$("body").overhang({
						type: "error",
						message: '<i class="fa fa-exclamation-circle"></i>&nbsp;&nbsp;<?=$lng['Sorry but someting went wrong']?>&nbsp;&nbsp;<b><?=$lng['Error']?></b> : '+thrownError,
						duration: 4,
					})
				}
			});
		});
		$("#hiBtn").on('click', function(e){
			$("#historyForm").submit();
		})
		$("#historyForm").on('submit', function(e){
			e.preventDefault();
			var err = 0;
			if($('#historyForm input[name="date_from"]').val() == ''){err = 1;}
			if($('#historyForm input[name="emp_condition"]').val() == ''){err = 1;}
			if(err){
				$("body").overhang({
					type: "error",
					message: '<i class="fa fa-exclamation-triangle"></i>&nbsp;&nbsp;<?=$lng['Please fill in required fields']?> : ',
					duration: 4,
				})
				return false;
			}
			$("#hiBtn i.mBut").removeClass('fa-save').addClass('fa-repeat fa-spin');
			var formData = new FormData(this);
			$.ajax({
				url: "ajax/update_medical_history.php",
				type: "POST", 
				data: formData,
				dataType: 'json',
				cache: false,
				contentType: false,
				processData: false,
				success: function(data){
					//$("#dump").html(data); return false;
					if(data.result == 'success'){
						$("body").overhang({
							type: "success",
							message: '<i class="fa fa-check"></i>&nbsp;&nbsp;<?=$lng['Data updated successfully']?>',
							duration: 2,
						})
						$('#sAlert').fadeOut(200);
						meTable.ajax.reload(null, false);
						if(data.certificate != ''){
							$("#certificateBtn").html('Choose file');
							$('#historyForm #empCertificate').html(data.certificate)
							$('#historyForm #empCertificate').attr('href', '<?=ROOT.$cid?>/medical/'+data.certificate)
						}
						if(data.attachment != ''){
							$('#hiAttach').empty();
							$('#attachHistory').empty();
							$('#attachHistory').append('<input style="margin:0 0 5px 0" class="attachBtn" name="attachment[]" type="file" />');
							$.each(data.attachment, function(i, val){
								$('#hiAttach').append(
									'<div class="attachDiv">'+
										'<a target="_blank" href="<?=ROOT.$cid?>/medical/'+val+'"class="link">'+val+'</a>'+
										'<a data-key="'+i+'" class="icon delAttach"><i class="fa fa-trash"></i></a>'+
									'</div>'
								)
							})
						}
					}else{
						$("body").overhang({
							type: "error",
							message: '<i class="fa fa-exclamation-triangle"></i>&nbsp;&nbsp;<?=$lng['Error']?> : '+data.result,
							duration: 4,
							closeConfirm: true
						})
					}
					setTimeout(function(){
						$("#hiBtn i.mBut").removeClass('fa-repeat fa-spin').addClass('fa-save');
						$("#hiBtn").removeClass('flash');
					},500);
					//meHistory == 0;
				},
				error:function (xhr, ajaxOptions, thrownError){
					$("body").overhang({
						type: "error",
						message: '<i class="fa fa-exclamation-circle"></i>&nbsp;&nbsp;<?=$lng['Sorry but someting went wrong']?> <b><?=$lng['Error']?></b> : '+thrownError,
						duration: 4,
					})
					setTimeout(function(){
						$("#hiBtn i.mBut").removeClass('fa-repeat fa-spin').addClass('fa-save');
						$("#hiBtn").removeClass('flash');
					},500);
				}
			});
		})
		
		
		
		$(document).on('click','#meRecord thead tr', function(){
			if($('#meRecord thead tr th i').hasClass('fa-caret-right')){
				$('#meRecord thead tr th i').removeClass('fa-caret-right').addClass('fa-caret-down');
			}else{
				$('#meRecord thead tr th i').removeClass('fa-caret-down').addClass('fa-caret-right');
				$('#hiBtn').hide()
				$('#meBtn').show()
				$("table#medical_table tr").removeClass("selected");
				$("#meColor").css('background', 'rgba(200,255,200,0.1)');
			}
			$('#recDiv').slideToggle(200);
			$('#hisDiv').slideUp(200);
		})
		$(document).on('change', "#recordForm .attachBtn", function(e){
			readFileURL(this, '#attachRecord');
			$("#meBtn").addClass('flash');
			$('#sAlert').fadeIn(200);
		})
		$(document).on("click", "#recordForm .delAttach", function(e){
			var key = $(this).data('key')
			var app = $(this);
			$.ajax({
				url: "ajax/delete_employee_med_attach.php",
				data: {id: emp_id, key: key},
				success: function(result){
					//$('#dump').html(result);
					app.parent().remove();
				},
				error:function (xhr, ajaxOptions, thrownError){
					$("body").overhang({
						type: "error",
						message: '<i class="fa fa-exclamation-circle"></i>&nbsp;&nbsp;<?=$lng['Sorry but someting went wrong']?>&nbsp;&nbsp;<b><?=$lng['Error']?></b> : '+thrownError,
						duration: 4,
					})
				}
			});
		});
		
		$("#meBtn").on('click', function(e){
			$("#recordForm").submit();
		})
		$("#recordForm").on('submit', function(e){
			e.preventDefault();
			$("#meBtn i.mBut").removeClass('fa-save').addClass('fa-repeat fa-spin');
			var formData = new FormData(this);
			$.ajax({
				url: "ajax/update_medical_record.php",
				type: "POST", 
				data: formData,
				dataType: 'json',
				cache: false,
				contentType: false,
				processData: false,
				success: function(data){
					//$("#dump").html(data); return false;
					if(data.result == 'success'){
						$("body").overhang({
							type: "success",
							message: '<i class="fa fa-check"></i>&nbsp;&nbsp;<?=$lng['Data updated successfully']?>',
							duration: 2,
						})
						$('#sAlert').fadeOut(200);
						if(data.attachment != ''){
							$('#meAttach').empty();
							$('#attachRecord').empty();
							$('#attachRecord').append('<input style="margin:0 0 5px 0" class="attachBtn" name="attachment[]" type="file" />');
							$.each(data.attachment, function(i, val){
								$('#meAttach').append(
									'<div class="attachDiv">'+
										'<a target="_blank" href="<?=ROOT.$cid?>/medical/'+val+'"class="link">'+val+'</a>'+
										'<a data-key="'+i+'" class="icon delAttach"><i class="fa fa-trash"></i></a>'+
									'</div>'
								)
							})
						}
					}else{
						$("body").overhang({
							type: "error",
							message: '<i class="fa fa-exclamation-triangle"></i>&nbsp;&nbsp;<?=$lng['Error']?> : '+data.result,
							duration: 4,
						})
					}
					setTimeout(function(){
						$("#meBtn i.mBut").removeClass('fa-repeat fa-spin').addClass('fa-save');
						$("#meBtn").removeClass('flash');
					},500);
				},
				error:function (xhr, ajaxOptions, thrownError){
					$(".submitbtn").removeClass('flash');
					$("body").overhang({
						type: "error",
						message: '<i class="fa fa-exclamation-circle"></i>&nbsp;&nbsp;<?=$lng['Sorry but someting went wrong']?> <b><?=$lng['Error']?></b> : '+thrownError,
						duration: 4,
					})
					setTimeout(function(){
						$("#meBtn i.mBut").removeClass('fa-repeat fa-spin').addClass('fa-save');
						$("#meBtn").removeClass('flash');
					},500);
				}
			});
		})
		
		$('#recordForm input, #recordForm textarea').on('keyup', function(e){
			$('#sAlert').fadeIn(200);
			$("#meBtn").addClass('flash');
		})
		$('#recordForm select').on('change', function(e){
			$('#sAlert').fadeIn(200);
			$("#meBtn").addClass('flash');
		})
		
		$('#hisroryForm input, #hisroryForm textarea').on('keyup', function(e){
			$('#sAlert').fadeIn(200);
			$("#hiBtn").addClass('flash');
		})
		$('#hisroryForm .datepick').datepicker({
			format: "dd-mm-yyyy",
			autoclose: true,
			inline: true,
			language: lang,//lang+'-th',
			todayHighlight: true,
		}).on('changeDate', function(ev){
			$('#sAlert').fadeIn(200);
			$("#hiBtn").addClass('flash');
		});
		
		
		
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	// DISCIPLINE FORM ///////////////////////////////////////////////////////////////////////////////
		var diTable = $('#discipline_table').DataTable({
			scrollY: false,
			scrollX: false,
			lengthChange: false,
			searching: false,
			ordering: false,
			paging: false,
			filter: false,
			info: false,
			<?=$dtable_lang?>
			processing: false,
			serverSide: true,
			//order: [0, 'desc'],
			ajax: {
				url: "ajax/server_get_discipline.php",
				type: 'POST',
				"data": function(d){
					d.emp_id = emp_id;
				}
			},
			columnDefs: [
				  //{"targets": [1], "class": 'pad1' },
				  //{"targets": [3], "width": '80%' },
				  //{"targets": sCols, "visible": true },
			],
			initComplete : function( settings, json ) {
				$('#diTable').fadeIn(200);
				diTable.columns.adjust().draw();
			}
		});
		$(document).on('click','#discipline_table tbody tr', function(){
			var id = diTable.row(this).data()[4];
			$.ajax({
				url: "ajax/get_employee_benefit.php",
				type: "POST", 
				data: {id: id, field: 'discipline'},
				dataType: 'json',
				success: function(data){
					//$("#dump").html(data); //return false;
					$('#diID').val(data.id);
					$('#disciplineForm input[name="date"]').val(data.date)
					$('#disciplineForm select[name="status"]').val(data.status)
					$('#disciplineForm select[name="warning"]').val(data.warning)
					$('#disciplineForm select[name="violation"]').val(data.violation)
					$('#disciplineForm textarea[name="infraction"]').val(data.infraction)
					$('#disciplineForm textarea[name="improvement"]').val(data.improvement)
					$('#disciplineForm textarea[name="consequences"]').val(data.consequences)
					$('#disciplineForm textarea[name="employee"]').val(data.employee)
					$('#disciplienForm textarea[name="employer"]').val(data.employer)
					$('#disciplineForm textarea[name="witness"]').val(data.witness)
					$('#diAttach').empty();
					$.each(data.attachment, function(i,val){
						$('#diAttach').append(
							'<div class="attachDiv">'+
								'<a target="_blank" href="<?=ROOT.$cid?>/discipline/'+val+'"class="link">'+val+'</a>'+
								'<a data-key="'+i+'" class="icon delAttach"><i class="fa fa-trash"></i></a>'+
							'</div>'
						)
					})
					$("#diColor").css('background', 'rgba(200,255,200,0.1)');
					$("#disciplineTable").show();
					$("#diBtn").show();
					$("#diAction").html('- Edit');
					autosize.destroy(document.querySelectorAll('textarea'));
					autosize(document.querySelectorAll('textarea'));
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
		$("#addDiscipline").on('click', function(e){
			$("#disciplineForm").trigger('reset');
			$('#diAttach').empty();
			$("#diID").val(0);
			$("#disciplineTable").show();
			$("#diBtn").show();
			$("#diAction").html('- New');
			$("#diColor").css('background', 'rgba(255,200,200,0.08)');
			$("table#discipline_table tr").removeClass("selected");
		})
		$("#disciplineForm").on('submit', function(e){
			e.preventDefault();
			var err = 0;		
			if($('#disciplineForm input[name="position"]').val() == ""){err = 1}
			if($('#disciplineForm input[name="start_date"]').val() == ""){err = 1}
			if(err){
				$("body").overhang({
					type: "error",
					message: '<i class="fa fa-exclamation-triangle"></i>&nbsp;&nbsp;<?=$lng['Please fill in required fields']?>',
					duration: 4,
				})
				return false;
			}
			$("#diBtn i").removeClass('fa-save').addClass('fa-repeat fa-spin');
			var formData = new FormData($('#disciplineForm')[0]);
			$.ajax({
				url: "ajax/update_benefits.php",
				type: "POST", 
				data: formData,
				dataType: 'json',
				cache: false,
				contentType: false,
				processData: false,
				success: function(data){
					//$("#dump").html(data); return false;
					if(data.result == 'success'){
						$("body").overhang({
							type: "success",
							message: '<i class="fa fa-check"></i>&nbsp;&nbsp;<?=$lng['Data updated successfully']?>',
							duration: 2,
						})
						$('#sAlert').fadeOut(200);
						diTable.ajax.reload(null, false);
						if(data.attach != ''){
							$('#diAttach').empty();
							$('#attachDiscipline').empty();
							$('#attachDiscipline').append('<input style="margin:0 0 5px 0" class="attachBtn" name="attachment[]" type="file" />');
							$.each(data.attachment, function(i,val){
								$('#diAttach').append(
									'<div class="attachDiv">'+
										'<a target="_blank" href="<?=ROOT.$cid?>/discipline/'+val+'"class="link">'+val+'</a>'+
										'<a data-key="'+i+'" class="icon delAttach"><i class="fa fa-trash"></i></a>'+
									'</div>'
								)
							})
						}
					}else{
						$("body").overhang({
							type: "error",
							message: '<i class="fa fa-exclamation-triangle"></i>&nbsp;&nbsp;<?=$lng['Error']?> : '+data.result,
							duration: 4,
						})
					}
					setTimeout(function(){
						$("#diBtn i").removeClass('fa-repeat fa-spin').addClass('fa-save');
						$("#diBtn").removeClass('flash');
					},500);
				},
				error:function (xhr, ajaxOptions, thrownError){
					$("body").overhang({
						type: "error",
						message: '<i class="fa fa-exclamation-circle"></i>&nbsp;&nbsp;<?=$lng['Sorry but someting went wrong']?> <b><?=$lng['Error']?></b> : '+thrownError,
						duration: 4,
					})
					setTimeout(function(){
						$("#diBtn i").removeClass('fa-repeat fa-spin').addClass('fa-save');
						$("#diBtn").removeClass('flash');
					},500);
				}
			});
		})
		$(document).on('change', "#disciplineForm .attachBtn", function(e){
			readFileURL(this, '#attachDiscipline');
			$("#diBtn").addClass('flash');
			$('#sAlert').fadeIn(200);
		})
		$(document).on("click", "#disciplineForm .delAttach", function(e){
			var key = $(this).data('key')
			var app = $(this);
			$.ajax({
				url: "ajax/delete_benefit_attach.php",
				data: {id: $("#diID").val(), key: key, field: 'discipline'},
				success: function(result){
					//$('#dump').html(result);
					app.parent().remove();
				},
				error:function (xhr, ajaxOptions, thrownError){
					$("body").overhang({
						type: "error",
						message: '<i class="fa fa-exclamation-circle"></i>&nbsp;&nbsp;<?=$lng['Sorry but someting went wrong']?>&nbsp;&nbsp;<b><?=$lng['Error']?></b> : '+thrownError,
						duration: 4,
					})
				}
			});
		});
		$('#disciplineForm input, #disciplineForm textarea').on('keyup', function(e){
			$('#sAlert').fadeIn(200);
			$("#diBtn").addClass('flash');
		})
		$('#disciplineForm select').on('change', function(e){
			$('#sAlert').fadeIn(200);
			$("#diBtn").addClass('flash');
		})
		$('#disciplineForm .datepick').datepicker({
			format: "dd-mm-yyyy",
			autoclose: true,
			inline: true,
			language: lang,//lang+'-th',
			todayHighlight: true,
		}).on('changeDate', function(ev){
			$('#sAlert').fadeIn(200);
			$("#diBtn").addClass('flash');
		});		
		
		
		var activeTabHis = localStorage.getItem('activeTabHis');
		if(activeTabHis){
			$('.nav-link[href="' + activeTabHis + '"]').tab('show');
		}else{
			$('.nav-link[href="#tab_his_log"]').tab('show');
		}
		$('a[data-toggle="tab"]').on('shown.bs.tab', function(e) {
			localStorage.setItem('activeTabHis', $(e.target).attr('href'));
			
			logTable.columns.adjust().draw();
			caTable.columns.adjust().draw();
			meTable.columns.adjust().draw();
			diTable.columns.adjust().draw();
		});

	})
		
	</script>

















