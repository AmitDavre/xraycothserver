<?php
	
	if(!$_SESSION['rego']['employee_benefit']['view']){ 
		echo '<div class="msg_nopermit">You have no access to this page</div>'; exit;
	}

	$update = 1;
	if(isset($_SESSION['rego']['empID'])){ // EDIT EMPLOYEE ////////////////////////////////////////////////
		$empID = $_SESSION['rego']['empID'];
		$res = $dbc->query("SELECT emp_id, image, th_name, en_name, personal_phone, personal_email, joining_date FROM ".$cid."_employees WHERE emp_id = '".$empID."'");
		$data = $res->fetch_assoc();
		if(empty($data['image'])){$data['image'] = 'images/profile_image.jpg';}
	}
	//var_dump($data); exit;
	
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
		<span><i class="fa fa-users fa-mr"></i> <?=$lng['Other benefits']?>&nbsp; <i class="fa fa-arrow-circle-right"></i> </span>
		<span><?=$data['emp_id']?> : <?=$data[$lang.'_name']?></span>
		<span style="display:none; font-style:italic; color:#b00; padding-left:30px" id="sAlert"><i class="fa fa-exclamation-triangle fa-mr"></i><?=$lng['Data is not updated to last changes made']?></span>
	</h2>
	
	<div style="padding:0 0 0 20px" id="dump"></div>
	
	<? include('employee_image_inc.php')?>
	
	<div class="pannel main_pannel employee-profile">
			
		<ul class="nav nav-tabs">
			<li class="nav-item"><a class="nav-link" href="#tab_ben_assets" data-toggle="tab"><?=$lng['Assets']?></a></li>
			<li class="nav-item"><a class="nav-link" href="#tab_ben_equipment" data-toggle="tab"><?=$lng['Equipment']?></a></li>
			<li class="nav-item"><a class="nav-link" href="#tab_ben_privileges" data-toggle="tab"><?=$lng['Privileges']?></a></li>
			<li class="nav-item"><a class="nav-link" href="#tab_ben_training" data-toggle="tab"><?=$lng['Training']?></a></li>
			<li class="nav-item"><a class="nav-link" href="#tab_ben_events" data-toggle="tab"><?=$lng['Events']?></a></li>
		</ul>

		<div class="tab-content" style="height:calc(100% - 30px); padding:10px">
			
			<div class="tab-pane" id="tab_ben_assets">
				<table style="width:100%; height:100%">
					<tr>
						<td style="width:50%; padding-right:15px; vertical-align:top">
							<div id="asTable" style="display:none">
								<table id="assets_table" class="dataTables nowrap hoverable selectable">
									<thead>
										<tr>
											<th data-sortable="false"><?=$lng['Asset']?></th>
											<th data-sortable="false" style="width:80%"><?=$lng['Description']?></th>
											<th data-sortable="false"><?=$lng['Assign date']?></th>
											<th data-sortable="false"><?=$lng['Return date']?></th>
											<th data-visible="false">x</th>
										</tr>
								 </thead>
								</table>
								<button id="addAsset" class="btn btn-primary btn-xs" style="margin:8px 0 0 0" type="button"><i class="fa fa-plus fa-mr"></i><?=$lng['Add Asset']?></button>
							</div>
						</td>
						<td id="asColor" style="width:50%; background:rgba(200,255,200,0.1); padding:0px; vertical-align:top">
							<div style="overflow-y:auto; height:100%; border:1px solid #eee; padding:1px 5px 5px; position:relative">
							<form id="assetForm">
								<input type="hidden" name="id" id="asID" value="0">
								<input type="hidden" name="field" value="assets">
								<input type="hidden" name="emp_id" value="<?=$empID?>">
								<table id="assetTable" class="basicTable nowrap" style="width:100%; display:none">
									<thead>
										<tr style="background:transparent">
											<th colspan="4">Asset <span id="asAction"></span></th>
										</tr>
									</thead>	
									<tbody>
										<tr>
											<th><i class="man"></i> <?=$lng['Asset']?></th>
											<td style="padding:0">
												<input type="text" name="asset" placeholder="...">
											</td>
										</tr>
										<tr>
											<th><?=$lng['Description']?></th>
											<td colspan="3" style="padding:0">
												<input type="text" name="description" placeholder="...">
											</td>
										</tr>
										<tr>
											<th><?=$lng['Reference']?></th>
											<td colspan="3" style="padding:0">
												<input type="text" name="reference" placeholder="...">
											</td>
										</tr>
										<tr>
											<th><i class="man"></i> <?=$lng['Assign date']?></th>
											<td style="padding:0">
												<input readonly style="cursor:pointer" class="datepick"  type="text" name="assign_date" placeholder="...">
											</td>
										</tr>
										<tr>
											<th><?=$lng['Return date']?></th>
											<td style="padding:0">
												<input readonly style="cursor:pointer" class="datepick"  type="text" name="return_date" placeholder="...">
											</td>
										</tr>
										<tr>
											<th><?=$lng['Purchase value']?></th>
											<td colspan="3" style="padding:0">
												<input type="text" name="value" placeholder="...">
											</td>
										</tr>
										<tr>
											<th><?=$lng['Cost / year']?></th>
											<td colspan="3" style="padding:0">
												<input class="sel numeric" type="text" name="cost" placeholder="...">
											</td>
										</tr>
										<tr>
											<th><?=$lng['Paid by']?></th>
											<td colspan="3" style="padding:0">
												<select name="paidby">
													<option value="0"><?=$lng['Company']?></option>
													<option value="1"><?=$lng['Employee']?></option>
												</select>
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
												<div id="asAttach"></div>
												<div id="attachAsset" style="clear:both">
													<input style="margin:0 0 5px 0" class="attachBtn" name="attachment[]" type="file" />
												</div>
											</td>
										</tr>
									</tbody>
								</table>
								<button id="asBtn" class="btn btn-primary" style="position:absolute; top:5px; right:5px; display:none" type="submit"><i class="fa fa-save"></i>&nbsp;&nbsp;<?=$lng['Update']?> <?=$lng['Assets']?></button>
							</form>
							</div>
						</td>
					</tr>
				</table>
			</div>
			
			<div class="tab-pane" id="tab_ben_equipment">
				<table style="width:100%; height:100%">
					<tr>
						<td style="width:50%; padding-right:15px; vertical-align:top">
							<div id="eqTable" style="display:none">
								<table id="equip_table" class="dataTables nowrap hoverable selectable">
									<thead>
										<tr>
											<th data-sortable="false"><?=$lng['Equipment']?></th>
											<th data-sortable="false" style="width:80%"><?=$lng['Description']?></th>
											<th data-sortable="false"><?=$lng['Assign date']?></th>
											<th data-sortable="false"><?=$lng['Return date']?></th>
											<th data-visible="false">x</th>
										</tr>
								 </thead>
								</table>
								<button id="addEquip" class="btn btn-primary btn-xs" style="margin:8px 0 0 0" type="button"><i class="fa fa-plus fa-mr"></i><?=$lng['Add Equipment']?></button>
							</div>
						</td>
						<td id="eqColor" style="width:50%; background:rgba(200,255,200,0.1); padding:0px; vertical-align:top">
							<div style="overflow-y:auto; height:100%; border:1px solid #eee; padding:1px 5px 5px; position:relative">
							<form id="equipForm">
								<input type="hidden" name="id" id="eqID" value="0">
								<input type="hidden" name="field" value="equipment">
								<input type="hidden" name="emp_id" value="<?=$empID?>">
								<table id="equipTable" class="basicTable nowrap" style="width:100%; display:none">
									<thead>
										<tr style="background:transparent">
											<th colspan="4"><?=$lng['Equipment']?> <span id="eqAction"></span></th>
										</tr>
									</thead>	
									<tbody>
										<tr>
											<th><i class="man"></i> <?=$lng['Equipment']?></th>
											<td style="padding:0">
												<input type="text" name="asset" placeholder="...">
											</td>
										</tr>
										<tr>
											<th><?=$lng['Description']?></th>
											<td colspan="3" style="padding:0">
												<input type="text" name="description" placeholder="...">
											</td>
										</tr>
										<tr>
											<th><?=$lng['Reference']?></th>
											<td colspan="3" style="padding:0">
												<input type="text" name="reference" placeholder="...">
											</td>
										</tr>
										<tr>
											<th><i class="man"></i> <?=$lng['Assign date']?></th>
											<td style="padding:0">
												<input readonly style="cursor:pointer" class="datepick"  type="text" name="assign_date" placeholder="...">
											</td>
										</tr>
										<tr>
											<th><?=$lng['Return date']?></th>
											<td style="padding:0">
												<input readonly style="cursor:pointer" class="datepick"  type="text" name="return_date" placeholder="...">
											</td>
										</tr>
										<tr>
											<th><?=$lng['Cost / year']?></th>
											<td colspan="3" style="padding:0">
												<input type="text" name="cost" placeholder="...">
											</td>
										</tr>
										<tr>
											<th><?=$lng['Paid by']?></th>
											<td colspan="3" style="padding:0">
												<select name="paidby">
													<option value="0"><?=$lng['Company']?></option>
													<option value="1"><?=$lng['Employee']?></option>
												</select>
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
												<div id="eqAttach"></div>
												<div id="attachEquip" style="clear:both">
													<input style="margin:0 0 5px 0" class="attachBtn" name="attachment[]" type="file" />
												</div>
											</td>
										</tr>
									</tbody>
								</table>
								<button id="eqBtn" class="btn btn-primary" style="position:absolute; top:5px; right:5px; display:none" type="submit"><i class="fa fa-save"></i>&nbsp;&nbsp;<?=$lng['Update']?> <?=$lng['Equipment']?></button>
							</form>
							</div>
						</td>
					</tr>
				</table>
			</div>

			<div class="tab-pane" id="tab_ben_privileges">
				<table style="width:100%; height:100%">
					<tr>
						<td style="width:50%; padding-right:15px; vertical-align:top">
            	<div id="prTable" style="display:none">
								<table id="privilege_table" class="dataTables nowrap hoverable selectable">
               		<thead>
               			<tr>
											<th data-sortable="false"><?=$lng['Date']?></th>
											<th data-sortable="false" style="width:80%"><?=$lng['Privilege']?></th>
											<th data-sortable="false"><?=$lng['Cost']?></th>
											<th data-sortable="false"><?=$lng['Completed']?></th>
											<th data-visible="false"></th>
										</tr>
               		</thead>
            		</table>
								<button id="addPrivilege" class="btn btn-primary btn-xs" style="margin:8px 0 0 0" type="button"><i class="fa fa-plus fa-mr"></i><?=$lng['Add Privilege']?></button>
							</div>
						</td>
						<td id="prColor" style="width:50%; background:rgba(200,255,200,0.1); padding:0px; vertical-align:top">
							<div style="overflow-y:auto; height:100%; border:1px solid #eee; padding:1px 5px 5px; position:relative">
								<form id="privilegeForm">
									<input type="hidden" name="id" id="prID" value="0">
									<input type="hidden" name="field" value="privileges">
									<input type="hidden" name="emp_id" value="<?=$empID?>">
									<table id="privilegeTable" class="basicTable nowrap" style="width:100%; display:none">
										<thead>
											<tr style="background:transparent">
												<th colspan="4"><?=$lng['Privilege']?> <span id="prAction"></span></th>
											</tr>
										</thead>	
										<tbody>
											<tr>
												<th><i class="man"></i> <?=$lng['Date']?></th>
												<td style="padding:0">
													<input readonly style="cursor:pointer" class="datepick"  type="text" name="date" placeholder="...">
												</td>
											</tr>
											<tr>
												<th><i class="man"></i> <?=$lng['Privilege']?></th>
												<td colspan="3" style="padding:0">
													<input type="text" name="privilege" placeholder="...">
												</td>
											</tr>
											<tr>
												<th><?=$lng['Hours']?></th>
												<td style="padding:0">
													<input type="text" name="hours" placeholder="...">
												</td>
											</tr>
											<tr>
												<th><?=$lng['Cost']?></th>
												<td style="padding:0">
													<input type="text" name="cost" class="sel numeric" placeholder="...">
												</td>
											</tr>
											<tr>
												<th><?=$lng['Certification']?></th>
												<td colspan="3" style="padding:0">
													<input type="text" name="certification" placeholder="...">
												</td>
											</tr>
											<tr>
												<th><?=$lng['Completed']?></th>
												<td colspan="3" style="padding:0">
													<input readonly style="cursor:pointer" class="datepick"  type="text" name="completed" placeholder="...">
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
												<div id="prAttach"></div>
												<div id="attachPrivilege" style="clear:both">
													<input style="margin:0 0 5px 0" class="attachBtn" name="attachment[]" type="file" />
												</div>
												</td>
											</tr>
										</tbody>
									</table>
									<button id="prBtn" class="btn btn-primary" style="position:absolute; top:5px; right:5px; display:none" type="submit"><i class="fa fa-save"></i>&nbsp;&nbsp;<?=$lng['Update']?> <?=$lng['Privileges']?></button>
								</form>
							</div>
						</td>
					</tr>
				</table>
			</div>
			
			<div class="tab-pane" id="tab_ben_training">
				<table style="width:100%; height:100%">
					<tr>
						<td style="width:50%; padding-right:15px; vertical-align:top">
            	<div id="trTable" style="display:none">
								<table id="training_table" class="dataTables nowrap hoverable selectable">
               		<thead>
               			<tr>
											<th data-sortable="false"><?=$lng['Date']?></th>
											<th data-sortable="false" style="width:80%"><?=$lng['Training']?></th>
											<th data-sortable="false"><?=$lng['Cost']?></th>
											<th data-sortable="false"><?=$lng['Completed']?></th>
											<th data-visible="false"></th>
										</tr>
               		</thead>
            		</table>
								<button id="addTraining" class="btn btn-primary btn-xs" style="margin:8px 0 0 0" type="button"><i class="fa fa-plus fa-mr"></i><?=$lng['Add Training']?></button>
							</div>
						</td>
						<td id="trColor" style="width:50%; background:rgba(200,255,200,0.1); padding:0px; vertical-align:top">
							<div style="overflow-y:auto; height:100%; border:1px solid #eee; padding:1px 5px 5px; position:relative">
								<form id="trainingForm">
									<input type="hidden" name="id" id="trID" value="0">
									<input type="hidden" name="field" value="training">
									<input type="hidden" name="emp_id" value="<?=$empID?>">
									<table id="trainingTable" class="basicTable nowrap" style="width:100%; display:none">
										<thead>
											<tr style="background:transparent">
												<th colspan="4"><?=$lng['Training']?> <span id="trAction"></span></th>
											</tr>
										</thead>	
										<tbody>
											<tr>
												<th><i class="man"></i> <?=$lng['Date']?></th>
												<td style="padding:0">
													<input readonly style="cursor:pointer" class="datepick"  type="text" name="date" placeholder="...">
												</td>
											</tr>
											<tr>
												<th><i class="man"></i> <?=$lng['Training']?></th>
												<td colspan="3" style="padding:0">
													<input type="text" name="training" placeholder="...">
												</td>
											</tr>
											<tr>
												<th><?=$lng['Hours']?></th>
												<td style="padding:0">
													<input type="text" name="hours" placeholder="...">
												</td>
											</tr>
											<tr>
												<th><?=$lng['Cost']?></th>
												<td style="padding:0">
													<input type="text" name="cost" class="sel numeric" placeholder="...">
												</td>
											</tr>
											<tr>
												<th><?=$lng['Certification']?></th>
												<td colspan="3" style="padding:0">
													<input type="text" name="certification" placeholder="...">
												</td>
											</tr>
											<tr>
												<th><?=$lng['Completed']?></th>
												<td colspan="3" style="padding:0">
													<input readonly style="cursor:pointer" class="datepick"  type="text" name="completed" placeholder="...">
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
													<div id="trAttach"></div>
													<div id="attachTraining" style="clear:both">
														<input style="margin:0 0 5px 0" class="attachBtn" name="attachment[]" type="file" />
													</div>
												</td>
											</tr>
										</tbody>
									</table>
									<button id="trBtn" class="btn btn-primary" style="position:absolute; top:5px; right:5px; display:none" type="submit"><i class="fa fa-save"></i>&nbsp;&nbsp;<?=$lng['Update']?> <?=$lng['Training']?></button>
								</form>
							</div>
						</td>
					</tr>
				</table>
			</div>
			
			<div class="tab-pane" id="tab_ben_events">
				<table style="width:100%; height:100%">
					<tr>
						<td style="width:50%; padding-right:15px; vertical-align:top">
            	<div id="evTable" style="display:none">
								<table id="events_table" class="dataTables nowrap hoverable selectable">
               		<thead>
               			<tr>
											<th data-sortable="false"><?=$lng['Date']?></th>
											<th data-sortable="false" style="width:80%"><?=$lng['Event']?></th>
											<th data-sortable="false"><?=$lng['Cost']?></th>
											<th data-sortable="false"><?=$lng['Completed']?></th>
											<th data-visible="false"></th>
										</tr>
               		</thead>
            		</table>
								<button id="addEvent" class="btn btn-primary btn-xs" style="margin:8px 0 0 0" type="button"><i class="fa fa-plus fa-mr"></i><?=$lng['Add Event']?></button>
							</div>
						</td>
						<td id="evColor" style="width:50%; background:rgba(200,255,200,0.1); padding:0px; vertical-align:top">
							<div style="overflow-y:auto; height:100%; border:1px solid #eee; padding:1px 5px 5px; position:relative">
								<form id="eventsForm">
									<input type="hidden" name="id" id="evID" value="0">
									<input type="hidden" name="field" value="events">
									<input type="hidden" name="emp_id" value="<?=$empID?>">
									<table id="eventsTable" class="basicTable nowrap" style="width:100%; display:none">
										<thead>
											<tr style="background:transparent">
												<th colspan="4"><?=$lng['Event']?> <span id="evAction"></span></th>
											</tr>
										</thead>	
										<tbody>
											<tr>
												<th><i class="man"></i> <?=$lng['Date']?></th>
												<td style="padding:0">
													<input readonly style="cursor:pointer" class="datepick"  type="text" name="date" placeholder="...">
												</td>
											</tr>
											<tr>
												<th><i class="man"></i> <?=$lng['Event']?></th>
												<td colspan="3" style="padding:0">
													<input type="text" name="event" placeholder="...">
												</td>
											</tr>
											<tr>
												<th><?=$lng['Hours']?></th>
												<td style="padding:0">
													<input type="text" name="hours" placeholder="...">
												</td>
											</tr>
											<tr>
												<th><?=$lng['Cost']?></th>
												<td style="padding:0">
													<input type="text" name="cost" class="sel numeric" placeholder="...">
												</td>
											</tr>
											<tr>
												<th><?=$lng['Certification']?></th>
												<td colspan="3" style="padding:0">
													<input type="text" name="certification" placeholder="...">
												</td>
											</tr>
											<tr>
												<th><?=$lng['Completed']?></th>
												<td colspan="3" style="padding:0">
													<input readonly style="cursor:pointer" class="datepick"  type="text" name="completed" placeholder="...">
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
													<div id="evAttach"></div>
													<div id="attachEvents" style="clear:both">
														<input style="margin:0 0 5px 0" class="attachBtn" name="attachment[]" type="file" />
													</div>
												</td>
											</tr>
										</tbody>
									</table>
									<button id="evBtn" class="btn btn-primary" style="position:absolute; top:5px; right:5px; display:none" type="submit"><i class="fa fa-save"></i>&nbsp;&nbsp;<?=$lng['Update']?> <?=$lng['Events']?></button>
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
		
		var emp_id = <?=json_encode($_SESSION['rego']['empID'])?>;
	
	// ASSETS FORM ///////////////////////////////////////////////////////////////////////////////
		var asTable = $('#assets_table').DataTable({
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
				url: "ajax/server_get_assets.php",
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
				$('#asTable').fadeIn(200);
				asTable.columns.adjust().draw();
			}
		});
		$(document).on('click','#assets_table tbody tr', function(){
			var id = asTable.row(this).data()[4];
			$.ajax({
				url: "ajax/get_employee_benefit.php",
				type: "POST", 
				data: {id: id, field: 'assets'},
				dataType: 'json',
				success: function(data){
					//$("#dump").html(data); //return false;
					$('#asID').val(data.id);
					$('#assetForm input[name="asset"]').val(data.asset)
					$('#assetForm input[name="description"]').val(data.description)
					$('#assetForm input[name="reference"]').val(data.reference)
					$('#assetForm input[name="assign_date"]').val(data.assign_date)
					$('#assetForm input[name="return_date"]').val(data.return_date)
					$('#assetForm input[name="value"]').val(data.value)
					$('#assetForm input[name="cost"]').val(data.cost)
					$('#assetForm select[name="paidby"]').val(data.paidby)
					$('#assetForm textarea[name="remarks"]').val(data.remarks)
					$('#asAttach').empty();
					$.each(data.attachment, function(i,val){
						$('#asAttach').append(
							'<div class="attachDiv">'+
								'<a target="_blank" href="<?=ROOT.$cid?>/assets/'+val+'"class="link">'+val+'</a>'+
								'<a data-key="'+i+'" class="icon delAttach"><i class="fa fa-trash"></i></a>'+
							'</div>'
						)
					})
					$("#asColor").css('background', 'rgba(200,255,200,0.1)');
					$("#assetTable").show();
					$("#asBtn").show();
					$("#asAction").html('- Edit');
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
		$("#addAsset").on('click', function(e){
			$("#assetForm").trigger('reset');
			$('#asAttach').empty();
			$("#asID").val(0);
			$("#assetTable").show();
			$("#asBtn").show();
			$("#asAction").html('- New');
			$("#asColor").css('background', 'rgba(255,200,200,0.08)');
			$("table#assets_table tr").removeClass("selected");
		})
		$("#assetForm").on('submit', function(e){
			e.preventDefault();
			var err = 0;		
			if($('#assetForm input[name="asset"]').val() == ""){err = 1}
			if($('#assetForm input[name="assign_date"]').val() == ""){err = 1}
			if(err){
				$("body").overhang({
					type: "error",
					message: '<i class="fa fa-exclamation-triangle"></i>&nbsp;&nbsp;<?=$lng['Please fill in required fields']?>',
					duration: 4,
				})
				return false;
			}
			$("#asBtn i").removeClass('fa-save').addClass('fa-repeat fa-spin');
			var formData = new FormData($('#assetForm')[0]);
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
						asTable.ajax.reload(null, false);
						if(data.attach != ''){
							$('#asAttach').empty();
							$('#attachAsset').empty();
							$('#attachAsset').append('<input style="margin:0 0 5px 0" class="attachBtn" name="attachment[]" type="file" />');
							$.each(data.attachment, function(i,val){
								$('#asAttach').append(
									'<div class="attachDiv">'+
										'<a target="_blank" href="<?=ROOT.$cid?>/assets/'+val+'"class="link">'+val+'</a>'+
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
						$("#asBtn i").removeClass('fa-repeat fa-spin').addClass('fa-save');
						$("#asBtn").removeClass('flash');
					},500);
				},
				error:function (xhr, ajaxOptions, thrownError){
					$("body").overhang({
						type: "error",
						message: '<i class="fa fa-exclamation-circle"></i>&nbsp;&nbsp;<?=$lng['Sorry but someting went wrong']?> <b><?=$lng['Error']?></b> : '+thrownError,
						duration: 4,
					})
					setTimeout(function(){
						$("#asBtn i").removeClass('fa-repeat fa-spin').addClass('fa-save');
						$("#asBtn").removeClass('flash');
					},500);
				}
			});
		})
		$(document).on('change', "#assetForm .attachBtn", function(e){
			readFileURL(this, '#attachAsset');
			$("#asBtn").addClass('flash');
			$('#sAlert').fadeIn(200);
		})
		$(document).on("click", "#assetForm .delAttach", function(e){
			var key = $(this).data('key')
			var app = $(this);
			$.ajax({
				url: "ajax/delete_benefit_attach.php",
				data: {id: $("#asID").val(), key: key, field: 'assets'},
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
		$('#assetForm input, #assetForm textarea').on('keyup', function(e){
			$('#sAlert').fadeIn(200);
			$("#asBtn").addClass('flash');
		})
		$('#assetForm select').on('change', function(e){
			$('#sAlert').fadeIn(200);
			$("#asBtn").addClass('flash');
		})
		$('#assetForm .datepick').datepicker({
			format: "dd-mm-yyyy",
			autoclose: true,
			inline: true,
			language: lang,//lang+'-th',
			todayHighlight: true,
		}).on('changeDate', function(ev){
			$('#sAlert').fadeIn(200);
			$("#asBtn").addClass('flash');
		});		
	
	
	
	// EQUIPMENT FORM ///////////////////////////////////////////////////////////////////////////////
		var eqTable = $('#equip_table').DataTable({
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
				url: "ajax/server_get_equipment.php",
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
				$('#eqTable').fadeIn(200);
				eqTable.columns.adjust().draw();
			}
		});
		$(document).on('click','#equip_table tbody tr', function(){
			var id = eqTable.row(this).data()[4];
			$.ajax({
				url: "ajax/get_employee_benefit.php",
				type: "POST", 
				data: {id: id, field: 'equipment'},
				dataType: 'json',
				success: function(data){
					$('#eqID').val(data.id);
					$('#equipForm input[name="asset"]').val(data.asset)
					$('#equipForm input[name="description"]').val(data.description)
					$('#equipForm input[name="reference"]').val(data.reference)
					$('#equipForm input[name="assign_date"]').val(data.assign_date)
					$('#equipForm input[name="return_date"]').val(data.return_date)
					$('#equipForm input[name="cost"]').val(data.cost)
					$('#equipForm select[name="paidby"]').val(data.paidby)
					$('#equipForm textarea[name="remarks"]').val(data.remarks)
					$('#eqAttach').empty();
					$.each(data.attachment, function(i,val){
						$('#eqAttach').append(
							'<div class="attachDiv">'+
								'<a target="_blank" href="<?=ROOT.$cid?>/equipment/'+val+'"class="link">'+val+'</a>'+
								'<a data-key="'+i+'" class="icon delAttach"><i class="fa fa-trash"></i></a>'+
							'</div>'
						)
					})
					$("#eqColor").css('background', 'rgba(200,255,200,0.1)');
					$("#equipTable").show();
					$("#eqBtn").show();
					$("#eqAction").html('- Edit');
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
		$("#addEquip").on('click', function(e){
			$("#equipForm").trigger('reset');
			$('#eqAttach').empty();
			$("#eqID").val(0);
			$("#equipTable").show();
			$("#eqBtn").show();
			$("#eqAction").html('- New');
			$("#eqColor").css('background', 'rgba(255,200,200,0.08)');
			$("table#equip_table tr").removeClass("selected");
		})
		
		$("#equipForm").on('submit', function(e){
			e.preventDefault();
			var err = 0;		
			if($('#equipForm input[name="asset"]').val() == ""){err = 1}
			if($('#equipForm input[name="assign_date"]').val() == ""){err = 1}
			if(err){
				$("body").overhang({
					type: "error",
					message: '<i class="fa fa-exclamation-triangle"></i>&nbsp;&nbsp;<?=$lng['Please fill in required fields']?>',
					duration: 4,
				})
				return false;
			}
			$("#eqBtn i").removeClass('fa-save').addClass('fa-repeat fa-spin');
			var formData = new FormData($('#equipForm')[0]);
			$.ajax({
				url: "ajax/update_benefits.php",
				type: "POST", 
				data: formData,
				dataType: 'json',
				cache: false,
				contentType: false,
				processData: false,
				success: function(data){
					//$("#dump").html(result); return false;
					if(data.result == 'success'){
						$("body").overhang({
							type: "success",
							message: '<i class="fa fa-check"></i>&nbsp;&nbsp;<?=$lng['Data updated successfully']?>',
							duration: 2,
						})
						$('#sAlert').fadeOut(200);
						eqTable.ajax.reload(null, false);
						if(data.attach != ''){
							$('#eqAsset').empty();
							$('#attachAsset').empty();
							$('#equipAsset').append('<input style="margin:0 0 5px 0" class="attachBtn" name="attachment[]" type="file" />');
							$.each(data.attachment, function(i,val){
								$('#eqAttach').append(
									'<div class="attachDiv">'+
										'<a target="_blank" href="<?=ROOT.$cid?>/equipment/'+val+'"class="link">'+val+'</a>'+
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
						$("#eqBtn i").removeClass('fa-repeat fa-spin').addClass('fa-save');
						$("#eqBtn").removeClass('flash');
					},500);
				},
				error:function (xhr, ajaxOptions, thrownError){
					$("body").overhang({
						type: "error",
						message: '<i class="fa fa-exclamation-circle"></i>&nbsp;&nbsp;<?=$lng['Sorry but someting went wrong']?> <b><?=$lng['Error']?></b> : '+thrownError,
						duration: 4,
					})
					setTimeout(function(){
						$("#eqBtn i").removeClass('fa-repeat fa-spin').addClass('fa-save');
						$("#eqBtn").removeClass('flash');
					},500);
				}
			});
		})
		$(document).on('change', "#equipForm .attachBtn", function(e){
			readFileURL(this, '#attachEquip');
			$("#eqBtn").addClass('flash');
			$('#sAlert').fadeIn(200);
		})
		$(document).on("click", "#equipForm .delAttach", function(e){
			var key = $(this).data('key')
			var app = $(this);
			$.ajax({
				url: "ajax/delete_benefit_attach.php",
				data: {id: $("#eqID").val(), key: key, field: 'equipment'},
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
		$('#equipForm input, #equipForm textarea').on('keyup', function(e){
			$('#sAlert').fadeIn(200);
			$("#eqBtn").addClass('flash');
		})
		$('#equipForm select').on('change', function(e){
			$('#sAlert').fadeIn(200);
			$("#eqBtn").addClass('flash');
		})
		$('#equipForm .datepick').datepicker({
			format: "dd-mm-yyyy",
			autoclose: true,
			inline: true,
			language: lang,//lang+'-th',
			todayHighlight: true,
		}).on('changeDate', function(ev){
			$('#sAlert').fadeIn(200);
			$("#eqBtn").addClass('flash');
		});		


	
	// PRIVILEGE FORM ///////////////////////////////////////////////////////////////////////////////
		var prTable = $('#privilege_table').DataTable({
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
				url: "ajax/server_get_privilege.php",
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
				$('#prTable').fadeIn(200);
				prTable.columns.adjust().draw();
			}
		});
		$(document).on('click','#privilege_table tbody tr', function(){
			var id = prTable.row(this).data()[4];
			$.ajax({
				url: "ajax/get_employee_benefit.php",
				type: "POST", 
				data: {id: id, field: 'privileges'},
				dataType: 'json',
				success: function(data){
					$("#prID").val(data.id);
					$('#privilegeForm input[name="date"]').val(data.date)
					$('#privilegeForm input[name="privilege"]').val(data.privilege)
					$('#privilegeForm input[name="hours"]').val(data.hours)
					$('#privilegeForm input[name="cost"]').val(data.cost)
					$('#privilegeForm input[name="certification"]').val(data.certification)
					$('#privilegegForm input[name="completed"]').val(data.completed)
					$('#privilegeForm textarea[name="remarks"]').val(data.remarks)
					$('#prAttach').empty();
					$.each(data.attachment, function(i,val){
						$('#prAttach').append(
							'<div class="attachDiv">'+
								'<a target="_blank" href="<?=ROOT.$cid?>/privileges/'+val+'"class="link">'+val+'</a>'+
								'<a data-key="'+i+'" class="icon delAttach"><i class="fa fa-trash"></i></a>'+
							'</div>'
						)
					})
					$("#prColor").css('background', 'rgba(200,255,200,0.1)');
					$("#privilegeTable").show();
					$("#prBtn").show();
					$("#prAction").html('- Edit');
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
		$("#addPrivilege").on('click', function(e){
			$("#privilegeForm").trigger('reset');
			$('#prAttach').empty();
			$("#prID").val(0);
			$("#privilegeTable").show();
			$("#prBtn").show();
			$("#prAction").html('- New');
			$("#prColor").css('background', 'rgba(255,200,200,0.08)');
			$("table#privilege_table tr").removeClass("selected");
		})
		
		$("#privilegeForm").on('submit', function(e){
			e.preventDefault();
			var err = 0;		
			if($('#privilegeForm input[name="date"]').val() == ""){err = 1}
			if($('#privilegeForm input[name="privilege"]').val() == ""){err = 1}
			if(err){
				$("body").overhang({
					type: "error",
					message: '<i class="fa fa-exclamation-triangle"></i>&nbsp;&nbsp;<?=$lng['Please fill in required fields']?>',
					duration: 4,
				})
				return false;
			}
			$("#prBtn i").removeClass('fa-save').addClass('fa-repeat fa-spin');
			var formData = new FormData($('#privilegeForm')[0]);
			$.ajax({
				url: "ajax/update_benefits.php",
				type: "POST", 
				data: formData,
				dataType: 'json',
				cache: false,
				contentType: false,
				processData: false,
				success: function(data){
					//$("#dump").html(result); return false;
					if(data.result == 'success'){
						$("body").overhang({
							type: "success",
							message: '<i class="fa fa-check"></i>&nbsp;&nbsp;<?=$lng['Data updated successfully']?>',
							duration: 2,
						})
						$('#sAlert').fadeOut(200);
						eqTable.ajax.reload(null, false);
						if(data.attach != ''){
							$('#prAttach').empty();
							$('#privilegeAsset').empty();
							$('#privilegeAsset').append('<input style="margin:0 0 5px 0" class="attachBtn" name="attachment[]" type="file" />');
							$.each(data.attachment, function(i,val){
								$('#prAttach').append(
									'<div class="attachDiv">'+
										'<a target="_blank" href="<?=ROOT.$cid?>/privileges/'+val+'"class="link">'+val+'</a>'+
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
						$("#prBtn i").removeClass('fa-repeat fa-spin').addClass('fa-save');
						$("#prBtn").removeClass('flash');
					},500);
				},
				error:function (xhr, ajaxOptions, thrownError){
					$("body").overhang({
						type: "error",
						message: '<i class="fa fa-exclamation-circle"></i>&nbsp;&nbsp;<?=$lng['Sorry but someting went wrong']?> <b><?=$lng['Error']?></b> : '+thrownError,
						duration: 4,
					})
					setTimeout(function(){
						$("#prBtn i").removeClass('fa-repeat fa-spin').addClass('fa-save');
						$("#prBtn").removeClass('flash');
					},500);
				}
			});
		})
		$(document).on('change', "#privilegeForm .attachBtn", function(e){
			readFileURL(this, '#attachPrivilege');
			$("#prBtn").addClass('flash');
			$('#sAlert').fadeIn(200);
		})
		$(document).on("click", "#privilegeForm .delAttach", function(e){
			var key = $(this).data('key')
			var app = $(this);
			$.ajax({
				url: "ajax/delete_benefit_attach.php",
				data: {id: $("#prID").val(), key: key, field: 'privileges'},
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
		$('#privilegeForm input, #privilegeForm textarea').on('keyup', function(e){
			$('#sAlert').fadeIn(200);
			$("#prBtn").addClass('flash');
		})
		$('#privilegeForm .datepick').datepicker({
			format: "dd-mm-yyyy",
			autoclose: true,
			inline: true,
			language: lang,//lang+'-th',
			todayHighlight: true,
		}).on('changeDate', function(ev){
			$('#sAlert').fadeIn(200);
			$("#prBtn").addClass('flash');
		});		
	
	
	
	// TRAINING FORM ///////////////////////////////////////////////////////////////////////////////
		var trTable = $('#training_table').DataTable({
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
				url: "ajax/server_get_training.php",
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
				$('#trTable').fadeIn(200);
				trTable.columns.adjust().draw();
			}
		});
		$(document).on('click','#training_table tbody tr', function(){
			var id = trTable.row(this).data()[4];
			$.ajax({
				url: "ajax/get_employee_benefit.php",
				type: "POST", 
				data: {id: id, field: 'training'},
				dataType: 'json',
				success: function(data){
					$("#trID").val(data.id);
					$('#trainingForm input[name="date"]').val(data.date)
					$('#trainingForm input[name="training"]').val(data.training)
					$('#trainingForm input[name="hours"]').val(data.hours)
					$('#trainingForm input[name="cost"]').val(data.cost)
					$('#trainingForm input[name="certification"]').val(data.certification)
					$('#trainingForm input[name="completed"]').val(data.completed)
					$('#trainingForm textarea[name="remarks"]').val(data.remarks)
					$('#trAttach').empty();
					$.each(data.attachment, function(i,val){
						$('#trAttach').append(
							'<div class="attachDiv">'+
								'<a target="_blank" href="<?=ROOT.$cid?>/training/'+val+'"class="link">'+val+'</a>'+
								'<a data-key="'+i+'" class="icon delAttach"><i class="fa fa-trash"></i></a>'+
							'</div>'
						)
					})
					$("#trColor").css('background', 'rgba(200,255,200,0.1)');
					$("#trainingTable").show();
					$("#trBtn").show();
					$("#trAction").html('- Edit');
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
		$("#addTraining").on('click', function(e){
			$("#trainingForm").trigger('reset');
			$('#trAttach').empty();
			$("#trID").val(0);
			$("#trainingTable").show();
			$("#trBtn").show();
			$("#trAction").html('- New');
			$("#trColor").css('background', 'rgba(255,200,200,0.08)');
			$("table#training_table tr").removeClass("selected");
		})
		$("#trainingForm").on('submit', function(e){
			e.preventDefault();
			var err = 0;		
			if($('#trainingForm input[name="asset"]').val() == ""){err = 1}
			if($('#trainingForm input[name="date"]').val() == ""){err = 1}
			if(err){
				$("body").overhang({
					type: "error",
					message: '<i class="fa fa-exclamation-triangle"></i>&nbsp;&nbsp;<?=$lng['Please fill in required fields']?>',
					duration: 4,
				})
				return false;
			}
			$("#trBtn i").removeClass('fa-save').addClass('fa-repeat fa-spin');
			var formData = new FormData($('#trainingForm')[0]);
			$.ajax({
				url: "ajax/update_benefits.php",
				type: "POST", 
				data: formData,
				dataType: 'json',
				cache: false,
				contentType: false,
				processData: false,
				success: function(data){
					//$("#dump").html(result); return false;
					if(data.result == 'success'){
						$("body").overhang({
							type: "success",
							message: '<i class="fa fa-check"></i>&nbsp;&nbsp;<?=$lng['Data updated successfully']?>',
							duration: 2,
						})
						$('#sAlert').fadeOut(200);
						evTable.ajax.reload(null, false);
						if(data.attach != ''){
							$('#trAttach').empty();
							$('#trainingAsset').empty();
							$('#trainingAsset').append('<input style="margin:0 0 5px 0" class="attachBtn" name="attachment[]" type="file" />');
							$.each(data.attachment, function(i,val){
								$('#trAttach').append(
									'<div class="attachDiv">'+
										'<a target="_blank" href="<?=ROOT.$cid?>/training/'+val+'"class="link">'+val+'</a>'+
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
						$("#trBtn i").removeClass('fa-repeat fa-spin').addClass('fa-save');
						$("#trBtn").removeClass('flash');
					},500);
				},
				error:function (xhr, ajaxOptions, thrownError){
					$("body").overhang({
						type: "error",
						message: '<i class="fa fa-exclamation-circle"></i>&nbsp;&nbsp;<?=$lng['Sorry but someting went wrong']?> <b><?=$lng['Error']?></b> : '+thrownError,
						duration: 4,
					})
					setTimeout(function(){
						$("#trBtn i").removeClass('fa-repeat fa-spin').addClass('fa-save');
						$("#trBtn").removeClass('flash');
					},500);
				}
			});
		})
		$(document).on('change', "#trainingForm .attachBtn", function(e){
			readFileURL(this, '#attachTraining');
			$("#trBtn").addClass('flash');
			$('#sAlert').fadeIn(200);
		})
		$(document).on("click", "#trainingForm .delAttach", function(e){
			var key = $(this).data('key')
			var app = $(this);
			$.ajax({
				url: "ajax/delete_benefit_attach.php",
				data: {id: $("#trID").val(), key: key, field: 'training'},
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
		$('#trainingForm input, #trainingForm textarea').on('keyup', function(e){
			$('#sAlert').fadeIn(200);
			$("#trBtn").addClass('flash');
		})
		$('#trainingForm .datepick').datepicker({
			format: "dd-mm-yyyy",
			autoclose: true,
			inline: true,
			language: lang,//lang+'-th',
			todayHighlight: true,
		}).on('changeDate', function(ev){
			$('#sAlert').fadeIn(200);
			$("#trBtn").addClass('flash');
		});		


	// EVENTS FORM ///////////////////////////////////////////////////////////////////////////////
		var evTable = $('#events_table').DataTable({
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
				url: "ajax/server_get_events.php",
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
				$('#evTable').fadeIn(200);
				evTable.columns.adjust().draw();
			}
		});
		$(document).on('click','#events_table tbody tr', function(){
			var id = evTable.row(this).data()[4];
			$.ajax({
				url: "ajax/get_employee_benefit.php",
				type: "POST", 
				data: {id: id, field: 'events'},
				dataType: 'json',
				success: function(data){
					$("#evID").val(data.id);
					$('#eventsForm input[name="date"]').val(data.date)
					$('#eventsForm input[name="event"]').val(data.event)
					$('#eventsForm input[name="hours"]').val(data.hours)
					$('#eventsForm input[name="cost"]').val(data.cost)
					$('#eventsForm input[name="certification"]').val(data.certification)
					$('#eventsForm input[name="completed"]').val(data.completed)
					$('#eventsForm textarea[name="remarks"]').val(data.remarks)
					$('#evAttach').empty();
					$.each(data.attachment, function(i,val){
						$('#evAttach').append(
							'<div class="attachDiv">'+
								'<a target="_blank" href="<?=ROOT.$cid?>/events/'+val+'"class="link">'+val+'</a>'+
								'<a data-key="'+i+'" class="icon delAttach"><i class="fa fa-trash"></i></a>'+
							'</div>'
						)
					})
					$("#evColor").css('background', 'rgba(200,255,200,0.1)');
					$("#eventsTable").show();
					$("#evBtn").show();
					$("#evAction").html('- Edit');
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
		$("#addEvent").on('click', function(e){
			$("#eventsForm").trigger('reset');
			$('#evAttach').empty();
			$("#evID").val(0);
			$("#eventsTable").show();
			$("#evBtn").show();
			$("#evAction").html('- New');
			$("#evColor").css('background', 'rgba(255,200,200,0.08)');
			$("table#events_table tr").removeClass("selected");
		})
		
		$("#eventsForm").on('submit', function(e){
			e.preventDefault();
			var err = 0;		
			if($('#eventsForm input[name="date"]').val() == ""){err = 1}
			if($('#eventsForm input[name="event"]').val() == ""){err = 1}
			if(err){
				$("body").overhang({
					type: "error",
					message: '<i class="fa fa-exclamation-triangle"></i>&nbsp;&nbsp;<?=$lng['Please fill in required fields']?>',
					duration: 4,
				})
				return false;
			}
			$("#evBtn i").removeClass('fa-save').addClass('fa-repeat fa-spin');
			var formData = new FormData($('#eventsForm')[0]);
			$.ajax({
				url: "ajax/update_benefits.php",
				type: "POST", 
				data: formData,
				dataType: 'json',
				cache: false,
				contentType: false,
				processData: false,
				success: function(data){
					//$("#dump").html(result); return false;
					if(data.result == 'success'){
						$("body").overhang({
							type: "success",
							message: '<i class="fa fa-check"></i>&nbsp;&nbsp;<?=$lng['Data updated successfully']?>',
							duration: 2,
						})
						$('#sAlert').fadeOut(200);
						evTable.ajax.reload(null, false);
						if(data.attach != ''){
							$('#evAttach').empty();
							$('#eventsAsset').empty();
							$('#eventsAsset').append('<input style="margin:0 0 5px 0" class="attachBtn" name="attachment[]" type="file" />');
							$.each(data.attachment, function(i,val){
								$('#evAttach').append(
									'<div class="attachDiv">'+
										'<a target="_blank" href="<?=ROOT.$cid?>/events/'+val+'"class="link">'+val+'</a>'+
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
						$("#evBtn i").removeClass('fa-repeat fa-spin').addClass('fa-save');
						$("#evBtn").removeClass('flash');
					},500);
				},
				error:function (xhr, ajaxOptions, thrownError){
					$("body").overhang({
						type: "error",
						message: '<i class="fa fa-exclamation-circle"></i>&nbsp;&nbsp;<?=$lng['Sorry but someting went wrong']?> <b><?=$lng['Error']?></b> : '+thrownError,
						duration: 4,
					})
					setTimeout(function(){
						$("#evBtn i").removeClass('fa-repeat fa-spin').addClass('fa-save');
						$("#evBtn").removeClass('flash');
					},500);
				}
			});
		})
		$(document).on('change', "#eventsForm .attachBtn", function(e){
			readFileURL(this, '#attachEvents');
			$("#evBtn").addClass('flash');
			$('#sAlert').fadeIn(200);
		})
		$(document).on("click", "#eventsForm .delAttach", function(e){
			var key = $(this).data('key')
			var app = $(this);
			$.ajax({
				url: "ajax/delete_benefit_attach.php",
				data: {id: $("#evID").val(), key: key, field: 'events'},
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
		$('#eventsForm input, #eventsForm textarea').on('keyup', function(e){
			$('#sAlert').fadeIn(200);
			$("#evBtn").addClass('flash');
		})
		$('#eventsForm .datepick').datepicker({
			format: "dd-mm-yyyy",
			autoclose: true,
			inline: true,
			language: lang,//lang+'-th',
			todayHighlight: true,
		}).on('changeDate', function(ev){
			$('#sAlert').fadeIn(200);
			$("#evBtn").addClass('flash');
		});		
	
	
	
	
	
	
	
		
		
		
		
		
		
		
		
		
		
		
		var activeTabBen = localStorage.getItem('activeTabBen');
		if(activeTabBen){
			$('.nav-link[href="' + activeTabBen + '"]').tab('show');
		}else{
			$('.nav-link[href="#tab_ben_assets"]').tab('show');
		}
		$('a[data-toggle="tab"]').on('shown.bs.tab', function(e) {
			localStorage.setItem('activeTabBen', $(e.target).attr('href'));
			asTable.columns.adjust().draw();
			eqTable.columns.adjust().draw();
			trTable.columns.adjust().draw();
			prTable.columns.adjust().draw();
			evTable.columns.adjust().draw();
		});

	})
		
	</script>
		

















