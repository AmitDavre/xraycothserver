<?php
	
	if(!$_SESSION['rego']['employee_permit']['view']){ 
		echo '<div class="msg_nopermit">You have no access to this page</div>'; exit;
	}

	if(isset($_SESSION['rego']['empID'])){ // EDIT EMPLOYEE ////////////////////////////////////////////////
		$empID = $_SESSION['rego']['empID'];
		$res = $dbc->query("SELECT * FROM ".$cid."_employees WHERE emp_id = '".$empID."'");
		$data = $res->fetch_assoc();
		if(empty($data['image'])){$data['image'] = 'images/profile_image.jpg';}
		$update = 1;
	}
	//var_dump($data); exit;
	
	$xdata = array();
	if(isset($_SESSION['rego']['empID'])){
		$sql = "SELECT * FROM ".$cid."_workpermit WHERE emp_id = '".$_SESSION['rego']['empID']."'";
		if($res = $dbc->query($sql)){
			if($row = $res->fetch_assoc()){
				$xdata = $row;
				$xdata['selEmployee'] = $row['emp_id'].' - '.$row['name_'.$lang];
				
			}
		}else{
			echo mysqli_error($dbc);
		}
	}else{
		$xdata['selEmployee'] = '';
		$xdata['emp_id'] = '';
		$xdata['title'] = '';
		$xdata['name_en'] = '';
		$xdata['name_th'] = '';
		$xdata['nationality'] = '';
		$xdata['maritial'] = '';
		$xdata['blood_group'] = '';
		$xdata['birthdate'] = '';
		$xdata['address'] = '';
		$xdata['family'] = array();
		//$data['xxx'] = '';
	}
	//var_dump($data); exit;
	
	$emps = getEmployees($cid,0);
	$emp_array = getJsonIdsEmployees();
	//var_dump($data); exit;
	
	
	
?>

   <h2 style="position:relative">
		<span><i class="fa fa-users fa-mr"></i> <?=$lng['Workpermit']?>&nbsp; <i class="fa fa-arrow-circle-right"></i> </span>
		<span><?=$data['emp_id']?> : <?=$data[$lang.'_name']?></span>
		<span style="display:none; font-style:italic; color:#b00; padding-left:30px" id="sAlert"><i class="fa fa-exclamation-triangle fa-mr"></i><?=$lng['Data is not updated to last changes made']?></span>
	</h2>
	
	<div style="padding:0 0 0 20px" id="dump"></div>
	
	<? include('employee_image_inc.php')?>
	
	<div class="pannel main_pannel employee-profile">
			
		<ul class="nav nav-tabs">
			<li class="nav-item"><a class="nav-link" href="#tab_current" data-toggle="tab">Current<? //=$lng['Assets']?></a></li>
			<li class="nav-item"><a class="nav-link" href="#tab_expat" data-toggle="tab">Data expat<? //=$lng['Benefits']?></a></li>
			<li class="nav-item"><a class="nav-link" href="#tab_invitations" data-toggle="tab">Invitations<? //=$lng['Tax']?></a></li>
			<li class="nav-item"><a class="nav-link" href="#tab_boi" data-toggle="tab">BOI<? //=$lng['Training']?></a></li>
			<li class="nav-item"><a class="nav-link" href="#tab_visa" data-toggle="tab">Visa / 90 days<? //=$lng['Training']?></a></li>
			<li class="nav-item"><a class="nav-link" href="#tab_workpermit" data-toggle="tab">Workpermit<? //=$lng['Training']?></a></li>
			<li class="nav-item"><a class="nav-link" href="#tab_documents" data-toggle="tab">Documents<? //=$lng['Training']?></a></li>
		</ul>

		<div class="tab-content" style="height:calc(100% - 30px)">
			<button id="submitBtn" class="btn btn-primary" type="button"><i class="fa fa-save"></i>&nbsp;&nbsp;<?=$lng['Update']?></button>
			
			<div class="tab-pane" id="tab_current">
				Current
			</div>
			
			<div class="tab-pane" id="tab_expat">
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
											<option <? //if($xdata['title'] == $k){echo 'selected';}?> value="<?=$k?>"><?=$v?></option>
										<? } ?>
									</select>
									</td>
								</tr>
								<tr>
									<th style="width:5%"><i class="man"></i>Full name English<? //=$lng['Employee ID']?></th>
									<td><input type="text" name="name_en" placeholder="..." value="<? //=$xdata['name_en']?>"></td>
								</tr>
								<tr>
									<th style="width:5%"><i class="man"></i>Full name Thai<? //=$lng['Employee ID']?></th>
									<td><input type="text" name="name_th" placeholder="..." value="<? //=$xdata['name_th']?>"></td>
								</tr>
								<tr>
									<th style="width:5%"><i class="man"></i><?=$lng['Nationality']?></th>
									<td><input type="text" name="nationality" placeholder="..." value="<? //=$xdata['nationality']?>"></td>
								</tr>
								<tr>
									<th style="width:5%"><i class="man"></i>Date of birth<? //=$lng['Employee ID']?></th>
									<td>
										<input class="date_year" style="width:90px; cursor:pointer" readonly type="text" name="birthdate" placeholder="..." value="<? //=$xdata['birthdate']?>">
										<b>Thai :</b><input style="width:90px" readonly type="text" name="" placeholder="..." value="25-05-2494<? //=$data['emp_id']?>"><b><?=$lng['Age']?> :</b> <span id="xemp_age">69</span>
									</td>
								</tr>
								<tr>
									<th style="width:5%"><i class="man"></i>Blood group<? //=$lng['Employee ID']?></th>
									<td><input type="text" name="blood_group" placeholder="..." value="<? //=$xdata['blood_group']?>"></td>
								</tr>
								<tr>
									<th style="width:5%"><i class="man"></i>Full resident address<br>(outside Thailand)<? //=$lng['Employee ID']?></th>
									<td><textarea rows="3" name="address" placeholder="..."><? //=$xdata['address']?></textarea></td>
								</tr>
								<tr>
									<th style="width:5%"><i class="man"></i><?=$lng['Maritial status']?></th>
									<td><input type="text" name="maritial" placeholder="..." value="<? //=$xdata['maritial']?>"></td>
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
			
			<div class="tab-pane" id="tab_invitations">
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
			
			<div class="tab-pane" id="tab_boi">
				BOI
			</div>
			
			<div class="tab-pane" id="tab_visa">
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
			
			<div class="tab-pane" id="tab_workpermit">
				Workpermit
			</div>
			
			<div class="tab-pane" id="tab_documents">
				Empty documents
			</div>
			
		</div>
		
	</div>
		
	<? include('employee_new_edit_script.php')?>

	<script>
		
	$(document).ready(function() {
		
		var activeTabPer = localStorage.getItem('activeTabPer');
		if(activeTabPer){
			$('.nav-link[href="' + activeTabPer + '"]').tab('show');
		}else{
			$('.nav-link[href="#tab_current"]').tab('show');
		}
		$('a[data-toggle="tab"]').on('shown.bs.tab', function(e) {
			localStorage.setItem('activeTabPer', $(e.target).attr('href'));
		});

	})
		
	</script>

















