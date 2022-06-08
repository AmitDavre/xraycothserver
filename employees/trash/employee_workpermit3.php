<?php
	
	$data = array();
	
	if(isset($_GET['id'])){
		$sql = "SELECT * FROM ".$cid."_workpermit WHERE emp_id = '".$_GET['id']."'";
		if($res = $dbc->query($sql)){
			if($row = $res->fetch_assoc()){
				$data = $row;
				$data['selEmployee'] = $row['emp_id'].' - '.$row['name_'.$lang];
				
			}
		}else{
			echo mysqli_error($dbc);
		}
	}else{
		$data['selEmployee'] = '';
		$data['emp_id'] = '';
		$data['title'] = '';
		$data['name_en'] = '';
		$data['name_th'] = '';
		$data['nationality'] = '';
		$data['maritial'] = '';
		$data['blood_group'] = '';
		$data['birthdate'] = '';
		$data['address'] = '';
		$data['family'] = array();
		//$data['xxx'] = '';
	}
	//var_dump($data); exit;
	
	$emps = getEmployees($cid,0);
	$emp_array = getJsonIdsEmployees();
	//var_dump($data); exit;

?>

<link rel="stylesheet" href="<?=ROOT?>css/autocomplete.css?<?=time()?>">

<style>
	.pannel {
		position:absolute; 
		top:130px; 
		bottom:10px; 
		border:0px solid red;
		padding:15px;
		box-size:border-box;
		overflow:hidden;
	}
	.left_pannel {
		left:0; 
		width:205px;
		padding-right:5px;
	}
	.main_pannel {
		left:205px; 
		right:0;
		padding-left:5px;
	}
	b {
		font-weight:600;
	}
	input, select, textarea {
		background:transparent !important;
	}
	textarea{  
		box-sizing: border-box;
		resize: none;
		overflow:hidden;
	}
		.fileBtn {
			display:block;
			margin-top:5px;
		}
		.fileBtn [type="file"]{
			border:0;
			visibility:false;
			position:absolute;
			width:0px;
			height:0px;
		}
		.fileBtn label{
			background:#eee;
			background: linear-gradient(to bottom, #eee, #ddd);
			border-radius: 2px;
			border:1px #ccc solid;
			padding:1px 8px;
			line-height:18px;
			white-space:nowrap;
			color: #000;
			cursor: pointer;
			display: inline-block;
			xfont-family: 'Open Sans', sans-serif;
			font-size:13px;
			font-weight:400;
		}
		.fileBtn label:hover{
			background: linear-gradient(to bottom, #ddd, #eee);
		}
		.fileBtn p {
			padding:0 0 0 5px;
			margin:0;
			display:inline-block;
			xfont-family: Arial, Helvetica, sans-serif;
			font-size:13px;
		}
</style>

<div style="width:100%">
	
   <h2 style="position:relative">
		<i class="fa fa-table"></i>&nbsp; <?=$lng['Employee register']?>
		<span style="display:none; font-style:italic; color:#b00; padding-left:30px" id="sAlert"><i class="fa fa-exclamation-triangle fa-mr"></i><?=$lng['Data is not updated to last changes made']?></span>
	</h2>
	
	
	<div class="pannel left_pannel">
		<table border="0">
			<tr>
				<td valign="top" style="text-align:right; padding:0">
					<div style="padding:15px; border:1px #ccc solid; background:#f6f6f6; border-radius:3px;">
					<img id="emp_image" width="150" height="150" src="../images/profile_image.jpg" /> 
					</div>
				</td>
			</tr>
		</table>
		
	</div>
	
	<div class="pannel main_pannel">
			
		<ul class="nav nav-tabs" id="myTab">
			<li style="padding:2px 10px 0 0">
				<div class="searchFilter" style="margin-bottom:0px; width:250px; border:0px red solid">
					<input style="width:100%" class="sFilter" placeholder="<?=$lng['Employee filter']?> ..." type="text" id="selEmployee" value="<?=$data['selEmployee']?>" />
					<button id="clearSearchbox" type="button" class="clearFilter btn btn-default btn-sm"><i class="fa fa-times"></i></button>
				</div>
			</li>
			<li><a data-target="#tab_current" data-toggle="tab">Current<? //=$lng['xxx']?></a></li>
			<li class="active"><a data-target="#tab_personal" data-toggle="tab">Data expat<? //=$lng['xxx']?></a></li>
			<li><a data-target="#tab_expat" data-toggle="tab">Invitations<? //=$lng['xxx']?></a></li>
			<!--<li><a data-target="#tab_family" data-toggle="tab">Invitation family<? //=$lng['xxx']?></a></li>-->
			<li><a data-target="#tab_boi" data-toggle="tab">BOI<? //=$lng['xxx']?></a></li>
			<li><a data-target="#tab_visa" data-toggle="tab">Visa / 90 days<? //=$lng['xxx']?></a></li>
			<!--<li><a data-target="#tab_90days" data-toggle="tab">90 days<? //=$lng['xxx']?></a></li>-->
			<li><a data-target="#tab_permit" data-toggle="tab">Workpermit<? //=$lng['xxx']?></a></li>
			<li><a data-target="#tab_docs" data-toggle="tab">Documents<? //=$lng['xxx']?></a></li>
		</ul>
		<form id="permitForm" style="height:100%">
			<input type="hidden" name="emp_id" value="<?=$data['emp_id']?>">

			<div class="tab-content" style="padding:10px; border:1px #ccc solid; border-top:0; height:calc(100% - 25px)">
				
				<div style="position:absolute; right:16px; top:15px">
					<button id="xsubmitBtn" class="btn btn-primary btn-sm" style="margin:0; float:right" type="submit"><i class="fa fa-save"></i>&nbsp;&nbsp;<?=$lng['Update']?></button>
				</div>

				<div class="tab-pane" id="tab_current" style="overflow-y:auto; overflow-x:hidden; height:100%">
					Current
				</div>
			
				<div class="tab-pane active" id="tab_personal" style="overflow-y:auto; height:100%">
					<table style="width:100%; height:100%">
					<tr>
					<td style="width:44%; padding-right:15px; vertical-align:top; border-right:1px solid #ddd">					
						
						<table class="basicTable editTable" border="0" style="margin-bottom:5px">
							<tbody>
								<tr>
									<th><i class="man"></i><?=$lng['Title']?></th><td>
									<select name="title">
										<option value="0" selected disabled><?=$lng['Select']?></option>
										<? foreach($title as $k=>$v){ ?>
											<option <? if($data['title'] == $k){echo 'selected';}?> value="<?=$k?>"><?=$v?></option>
										<? } ?>
									</select>
									</td>
								</tr>
								<tr>
									<th style="width:5%"><i class="man"></i>Full name English<? //=$lng['Employee ID']?></th>
									<td><input type="text" name="name_en" placeholder="..." value="<?=$data['name_en']?>"></td>
								</tr>
								<tr>
									<th style="width:5%"><i class="man"></i>Full name Thai<? //=$lng['Employee ID']?></th>
									<td><input type="text" name="name_th" placeholder="..." value="<?=$data['name_th']?>"></td>
								</tr>
								<tr>
									<th style="width:5%"><i class="man"></i><?=$lng['Nationality']?></th>
									<td><input type="text" name="nationality" placeholder="..." value="<?=$data['nationality']?>"></td>
								</tr>
								<tr>
									<th style="width:5%"><i class="man"></i>Date of birth<? //=$lng['Employee ID']?></th>
									<td>
										<input class="date_year" style="width:90px; cursor:pointer" readonly type="text" name="birthdate" placeholder="..." value="<?=$data['birthdate']?>">
										<b>Thai :</b><input style="width:90px" readonly type="text" name="" placeholder="..." value="25-05-2494<? //=$data['emp_id']?>"><b><?=$lng['Age']?> :</b> <span id="emp_age">69</span>
									</td>
								</tr>
								<tr>
									<th style="width:5%"><i class="man"></i>Blood group<? //=$lng['Employee ID']?></th>
									<td><input type="text" name="blood_group" placeholder="..." value="<?=$data['blood_group']?>"></td>
								</tr>
								<tr>
									<th style="width:5%"><i class="man"></i>Full resident address<br>(outside Thailand)<? //=$lng['Employee ID']?></th>
									<td><textarea rows="3" name="address" placeholder="..."><?=$data['address']?></textarea></td>
								</tr>
								<tr>
									<th style="width:5%"><i class="man"></i><?=$lng['Maritial status']?></th>
									<td><input type="text" name="maritial" placeholder="..." value="<?=$data['maritial']?>"></td>
								</tr>
							</tbody>
						</table>

						<table class="basicTable editTable" border="0">
							<thead>
								<tr style="border-bottom:1px #ccc solid; line-height:100%">
									<th colspan="5">Family members</th>
								</tr>
								<tr style="border-bottom:1px #ccc solid; line-height:100%">
									<th><?=$lng['Name']?> English</th>
									<th><?=$lng['Name']?> Thai</th>
									<th><?=$lng['Birthdate']?></th>
									<th>Live in Thailand<? //=$lng['Name']?></th>
									<th>Relationship<? //=$lng['Name']?></th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td><input type="text" name="family[0][en]" placeholder="..." value="<? //=$data['emp_id']?>"></td>
									<td><input type="text" name="family[0][th]" placeholder="..." value="<? //=$data['emp_id']?>"></td>
									<td><input type="text" name="family[0][birthdate]" placeholder="..." value="<? //=$data['emp_id']?>"></td>
									<td>									
										<select name="family[0][live]">
											<option selected value="0"><?=$lng['Select']?></option>
											<option value="N"><?=$lng['No']?></option>
											<option value="Y"><?=$lng['Yes']?></option>
										</select>
									</td>
									<td>									
										<select name="family[0][relation]">
											<option selected value="0"><?=$lng['Select']?></option>
											<option value="w">Spouse<? //=$lng['No']?></option>
											<option value="s">Sun<? //=$lng['Yes']?></option>
											<option value="d">Daughter<? //=$lng['Yes']?></option>
										</select>
									</td>
								</tr>
							</tbody>
						</table>
						<button class="btn btn-primary btn-xs" style="margin-top:5px" type="button"><i class="fa fa-plus"></i>&nbsp; Add line<? //=$lng['xxx']?></button>
					
					</td>
					<td style="width:56%; padding-left:15px; vertical-align:top">
					
						<table class="basicTable editTable" border="0" style="margin-bottom:0px">
							<tbody>
								<tr>
									<th style="width:5%"><i class="man"></i>Expat position<? //=$lng['Employee ID']?></th>
									<td><input type="text" name="" placeholder="..." value="<? //=$data['emp_id']?>"></td>
								</tr>
								<tr>
									<th style="width:5%"><i class="man"></i>Job description English<? //=$lng['Employee ID']?></th>
									<td><textarea rows="3" name="" placeholder="..."><? //=$data['emp_id']?></textarea></td>
								</tr>
								<tr>
									<th style="width:5%"><i class="man"></i>Job description Thai<? //=$lng['Employee ID']?></th>
									<td><textarea rows="3" name="" placeholder="..."><? //=$data['emp_id']?></textarea></td>
								</tr>
							</tbody>
						</table>
						
						<table class="basicTable" border="0">
							<tbody>
								<tr>
									<th class="tal" style="width:95%">Copy passport with Non-immigration "B" page</th>
									<td class="tac"><a><i class="fa fa-upload fa-lg"></i></a></td>
									<td class="tac"><i style="color:#ccc" class="fa fa-download fa-lg"></i></td>
								</tr>
								<tr>
									<th class="tal">Doctor medical certificate within 1 month</th>
									<td class="tac"><a><i class="fa fa-upload fa-lg"></i></a></td>
									<td class="tac"><i style="color:#ccc" class="fa fa-download fa-lg"></i></td>
								</tr>
								<tr>
									<th class="tal">Detailed job description</th>
									<td class="tac"><a><i class="fa fa-upload fa-lg"></i></a></td>
									<td class="tac"><i style="color:#ccc" class="fa fa-download fa-lg"></i></td>
								</tr>
							</tbody>
						</table>
						<button class="btn btn-primary btn-xs" style="margin:5px 0 8px 0" type="button"><i class="fa fa-plus"></i>&nbsp; Add attachment<? //=$lng['xxx']?></button>
						
						<table class="basicTable editTable" border="0">
							<thead>
								<tr style="border-bottom:1px #ccc solid; line-height:100%">
									<th colspan="6">Education and Experiance</th>
								</tr>
								<tr style="border-bottom:1px #ccc solid; line-height:100%">
									<th><?=$lng['Date']?></th>
									<th><?=$lng['Title']?></th>
									<th><?=$lng['Description']?></th>
									<th class="tac"><i data-toggle="tooltip" title="Certificate" class="fa fa-download fa-lg"></i><? //=$lng['Certificate']?></th>
									<th class="tac"><i data-toggle="tooltip" title="Translation" class="fa fa-download fa-lg"></i><? //=$lng['Name']?></th>
									<th class="tac"><i data-toggle="tooltip" title="Edit/View" class="fa fa-edit fa-lg"></i></th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td><input type="text" name="" placeholder="..." value="<? //=$data['emp_id']?>"></td>
									<td><input type="text" name="" placeholder="..." value="<? //=$data['emp_id']?>"></td>
									<td><input type="text" name="" placeholder="..." value="<? //=$data['emp_id']?>"></td>
									<td class="tac"><a><i class="fa fa-download fa-lg"></i></a></td>
									<td class="tac"><a><i class="fa fa-download fa-lg"></i></a></td>
									<td class="tac"><a><i class="fa fa-edit fa-lg"></i></a></td>
								</tr>
							</tbody>
						</table>
						<button class="btn btn-primary btn-xs" style="margin-top:5px" type="button"><i class="fa fa-plus"></i>&nbsp; Add line<? //=$lng['xxx']?></button>
					
						<div id="dump"></div>
					
					</td>
					</tr>
					</table>
				</div>
			
				<div class="tab-pane" id="tab_expat" style="overflow-y:auto; overflow-x:hidden; height:100%">
					<table style="width:100%; height:100%">
						<tr>
							<td style="width:50%; padding-right:15px; vertical-align:top; border-right:1px solid #ddd">					
								Expat
							</td>
							<td style="width:50%; padding-left:15px; vertical-align:top">
								Family
							</td>
						</tr>
					</table>
				</div>
			
				<!--<div class="tab-pane" id="tab_family" style="overflow-y:auto; overflow-x:hidden; height:100%">
					Family
				</div>-->
			
				<div class="tab-pane" id="tab_boi" style="overflow-y:auto; height:100%">
					BOI
				</div>
			
				<div class="tab-pane" id="tab_visa" style="overflow-y:auto; overflow-x:hidden; height:100%">
					<table style="width:100%; height:100%">
						<tr>
							<td style="width:50%; padding-right:15px; vertical-align:top; border-right:1px solid #ddd">					
								
								<table class="basicTable" border="0">
									<thead>
										<tr>
											<th colspan="3">Immigration VISA</th>
										</tr>
									</thead>
									<tbody>
										<tr>
											<th class="tal" style="width:95%; white-space:normal">Final approval of  VISA application</th>
											<td class="tac"><a><i class="fa fa-upload fa-lg"></i></a></td>
											<td class="tac"><a><i class="fa fa-download fa-lg"></i></a></td>
										</tr>
									</tbody>
								</table>
								
								<table class="basicTable editTable" border="0" style="margin-bottom:0px">
									<tbody>
										<tr>
											<th style="width:5%"><i class="man"></i>Reference Number<? //=$lng['Employee ID']?></th>
											<td><input type="text" name="" placeholder="..." value="<? //=$data['emp_id']?>"></td>
										</tr>
										<tr>
											<th style="width:5%"><i class="man"></i>Issue date<? //=$lng['Employee ID']?></th>
											<td><input type="text" name="" placeholder="..." value="<? //=$data['emp_id']?>"></td>
										</tr>
										<tr>
											<th style="width:5%"><i class="man"></i>Expiry date<? //=$lng['Employee ID']?></th>
											<td><input type="text" name="" placeholder="..." value="<? //=$data['emp_id']?>"></td>
										</tr>
										<tr>
											<th style="width:5%"><i class="man"></i>Contact details <? //=$lng['Employee ID']?></th>
											<td><textarea rows="3" name="" placeholder="..."><? //=$data['emp_id']?></textarea></td>
										</tr>
									</tbody>
								</table>
								
								<table class="basicTable" border="0">
									<tbody>
										<tr>
											<th class="tal" style="width:95%; white-space:normal">Prepared application</th>
											<td class="tac"><a><i class="fa fa-upload fa-lg"></i></a></td>
											<td class="tac"><a><i class="fa fa-download fa-lg"></i></a></td>
										</tr>
										<tr>
											<th class="tal" style="width:95%; white-space:normal">Company letter (if no BOI permission letter)</th>
											<td class="tac"><a><i class="fa fa-upload fa-lg"></i></a></td>
											<td class="tac"><a><i class="fa fa-download fa-lg"></i></a></td>
										</tr>
										<tr>
											<th class="tal" style="width:95%; white-space:normal">Copy of registration address to immigration with (TM.30) by house owner or yellow house book for foreigner</th>
											<td class="tac"><a><i class="fa fa-upload fa-lg"></i></a></td>
											<td class="tac"><a><i class="fa fa-download fa-lg"></i></a></td>
										</tr>
										<tr>
											<th class="tal" style="white-space:normal">Copy passport with expat signature sign true copy on all pages (fist page + visa page + arrival stamp page and TM.6)</th>
											<td class="tac"><a><i class="fa fa-upload fa-lg"></i></a></td>
											<td class="tac"><a><i class="fa fa-download fa-lg"></i></a></td>
										</tr>
										<tr>
											<th class="tal" style="white-space:normal">Signed Sor Tor Mor 2 form</th>
											<td class="tac"><a><i class="fa fa-upload fa-lg"></i></a></td>
											<td class="tac"><a><i class="fa fa-download fa-lg"></i></a></td>
										</tr>
										<tr>
											<th class="tal" style="white-space:normal">Signed condition of stay in Thailand form</th>
											<td class="tac"><a><i class="fa fa-upload fa-lg"></i></a></td>
											<td class="tac"><a><i class="fa fa-download fa-lg"></i></a></td>
										</tr>
									</tbody>
								</table>
								<button class="btn btn-primary btn-xs" style="margin:5px 0 8px 0" type="button"><i class="fa fa-plus"></i>&nbsp; Add attachment<? //=$lng['xxx']?></button>
							
							</td>
							<td style="width:50%; padding-left:15px; vertical-align:top">
								
								<table class="basicTable" border="0">
									<thead>
										<tr>
											<th colspan="3">Immigration 90 Days</th>
										</tr>
									</thead>
									<tbody>
										<tr>
											<th class="tal" style="width:95%; white-space:normal">Extension of Notification 90 days</th>
											<td class="tac"><a><i class="fa fa-upload fa-lg"></i></a></td>
											<td class="tac"><a><i class="fa fa-download fa-lg"></i></a></td>
										</tr>
									</tbody>
								</table>

								<table class="basicTable editTable" border="0" style="margin-bottom:0px">
									<tbody>
										<tr>
											<th style="width:5%"><i class="man"></i>Reference Number<? //=$lng['Employee ID']?></th>
											<td><input type="text" name="" placeholder="..." value="<? //=$data['emp_id']?>"></td>
										</tr>
										<tr>
											<th style="width:5%"><i class="man"></i>Arrival date/extension date<? //=$lng['Employee ID']?></th>
											<td><input type="text" name="" placeholder="..." value="<? //=$data['emp_id']?>"></td>
										</tr>
										<tr>
											<th style="width:5%"><i class="man"></i>Expiry date<? //=$lng['Employee ID']?></th>
											<td><input type="text" name="" placeholder="..." value="<? //=$data['emp_id']?>"></td>
										</tr>
										<tr>
											<th style="width:5%"><i class="man"></i>Contact details <? //=$lng['Employee ID']?></th>
											<td><textarea rows="3" name="" placeholder="..."><? //=$data['emp_id']?></textarea></td>
										</tr>
									</tbody>
								</table>

								<table class="basicTable" border="0">
									<tbody>
										<tr>
											<th class="tal" style="width:95%; white-space:normal">TM47. form</th>
											<td class="tac"><a><i class="fa fa-upload fa-lg"></i></a></td>
											<td class="tac"><a><i class="fa fa-download fa-lg"></i></a></td>
										</tr>
										<tr>
											<th class="tal" style="width:95%; white-space:normal">Passport with last arrival stamp and last departure card (TM.6)</th>
											<td class="tac"><a><i class="fa fa-upload fa-lg"></i></a></td>
											<td class="tac"><a><i class="fa fa-download fa-lg"></i></a></td>
										</tr>
										<tr>
											<th class="tal" style="width:95%; white-space:normal">Immigration resident address in Thailand to immigration with (TM.30) by house owner</th>
											<td class="tac"><a><i class="fa fa-upload fa-lg"></i></a></td>
											<td class="tac"><a><i class="fa fa-download fa-lg"></i></a></td>
										</tr>
									</tbody>
								</table>
								
							</td>
						</tr>
					</table>
				</div>
			
				<!--<div class="tab-pane" id="tab_90days" style="overflow-y:auto; overflow-x:hidden; height:100%">
					90 days
				</div>-->
			
				<div class="tab-pane" id="tab_permit" style="overflow-y:auto; overflow-x:hidden; height:100%">
					Workpermit
				</div>
			
				<div class="tab-pane" id="tab_docs" style="overflow-y:auto; overflow-x:hidden; height:100%">
					Empty documents
				</div>
			
			</div>
		</form>
		
	</div>
	
</div>

	<script type="text/javascript" src="../js/jquery.autocomplete.js"></script>

	<script type="text/javascript">
		
		$(document).ready(function() {

			var employees = <?=json_encode($emp_array)?>;
			var emps = <?=json_encode($emps)?>;
			var emp_id;
			
			$("#permitForm").on('submit', function(e){ 
				e.preventDefault();
				/*var err = false;
				$('#requestMsg').html('').hide();
				if($('#leave_type').val() == null){err = true;}
				if($('#startdate').val() == ""){err = true;}
				if($('#enddate').val() == ""){err = true;}
				//err = false;
				if(err == true){
					$("body").overhang({
						type: "warn",
						message: '<i class="fa fa-exclamation-triangle"></i>&nbsp;&nbsp;Please fill in required fields',
						duration: 4,
						closeConfirm: true
					})
				}*/

				var data = new FormData(this);
				//alert(data)
				
				$.ajax({
					url: ROOT+"employees/ajax/update_workpermit.php",
					type: "POST", 
					data: data,
					cache: false,
					processData:false,
					contentType: false,
					success: function(result){
						$('#dump').html(result); return false;
						
						if(result == 'success'){
							/*if($('#update').val() == 0){
								$('#requestMsg').html('<i class="fa fa-check"></i>&nbsp;&nbsp;Request send successfuly').fadeIn(300);
							}else{
								$('#requestMsg').html('<i class="fa fa-check"></i>&nbsp;&nbsp;Updated successfuly').fadeIn(300);
							}
							$("#requestBut i").removeClass('fa-refresh fa-spin').addClass('fa-feed');
							$("#approveBut i").removeClass('fa-refresh fa-spin').addClass('fa-thumbs-o-up');
							$("#updateBut i").removeClass('fa-refresh fa-spin').addClass('fa-save');
							$("#requestBut").prop('disabled', true);
							$("#approveBut").prop('disabled', true);
							$("#subButton").prop('disabled', true);
							setTimeout(function(){$("#modalAddLeave").modal('toggle');},2000);*/
						}else{
							/*$("#requestBut i").removeClass('fa-refresh fa-spin').addClass('fa-feed');
							$("#approveBut i").removeClass('fa-refresh fa-spin').addClass('fa-thumbs-o-up');
							$('#requestMsg').html('<i class="fa fa-exclamation-triangle"></i>&nbsp;&nbsp;' + result).fadeIn(300);*/
						}
					},
					error:function (xhr, ajaxOptions, thrownError){
						alert(thrownError);
					}
				});
			});
			
			
			$('#selEmployee').devbridgeAutocomplete({
				 lookup: employees,
				 triggerSelectOnValidInput: false,
				 onSelect: function (suggestion) {
					emp_id = suggestion.data;
					window.location.href="index.php?mn=103&id="+emp_id;
					return false;
					//$('input[name="emp_id"]').val(suggestion.data);
					//$("#emp_image").attr('src', ROOT+emps[emp_id]['image']);
					//alert(emps[emp_id]['image'])
					$.ajax({
						url: ROOT+"employees/ajax/get_workpermit.php",
						data: {emp_id: suggestion.data},
						dataType: 'json',
						success:function(data){
							//$("#dump").html(data);
							$('select[name="title"]').val(data.title);
							$('input[name="name_en"]').val(data.name_en);
							$('input[name="name_th"]').val(data.name_th);
							$('input[name="nationality"]').val(data.nationality);
							$('input[name="maritial"]').val(data.maritial);
							$('input[name="blood_group"]').val(data.blood_group);
							$('input[name="birthdate"]').val(data.birthdate);
							$('textarea[name="address"]').val(data.address);
							
							
							//alert(result);
							//$("#modalLeaveDetails").modal('toggle');
						},
						error:function (xhr, ajaxOptions, thrownError){
							alert('<?=$lng['Sorry but someting went wrong']?> ' + thrownError);
						}
					});
					//$('input[name="emp_id"]').val(suggestion.data);
					//$('input[name="name"]').val(suggestion.name);
					//$('input[name="phone"]').val(suggestion.phone);
				 	//$("#emp_img").prop('src', '../'+suggestion.image);
				 	//$("#addLeave").prop('disabled', false);
				 }
			});	
			$(document).on("click", "#clearSearchbox", function(e) {
				emp_id = '';
				$("#selEmployee").val('');
				$("#emp_image").attr('src', ROOT+'images/profile_image.jpg');
			})
			
			
			$('a[data-toggle="tab"]').on('shown.bs.tab', function(e) {
				//dtable.columns.adjust().draw();
				//alert($(e.target).data('target'))
				if($(e.target).data('target') == '#tab_request'){
				}
				if($(e.target).data('target') == '#tab_applied'){
				}
			});

		})
	</script>











