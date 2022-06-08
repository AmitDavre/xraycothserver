<?
	
	//$_SESSION['rego']['sys_users']['access'] = 1;
	//$_SESSION['rego']['sys_users']['edit'] = 1;
	
	/*if($_SESSION['rego']['sys_users']['access'] == 0){
		echo '<div class="msg_nopermit">You have no permission<br>to enter this page</div>'; 
		exit;
	}*/
	//$pr_settings = getPayrollSettings();
	//var_dump($pr_settings);
	
	/*$br['all'] = 'All branches';
	$branch = array();//unserialize($compinfo['branches']);
	if(!$branch){$branch[$lang] = array();}
	$branches = $br + $branch[$lang];
	//asort($branches);
	//var_dump($branches);
	$gr['all'] = 'All groups';
	$group = array();//unserialize($compinfo['groups']);
	if(!$group){$group[$lang] = array();}
	$groups = $gr + $group[$lang];
	
	$departments = array();
	$emp_group = 's';
	$employees = getEmployees($cid);
	if(!$employees){
		$employees = array();
		$emps = array();
		$emp_array = '[]';
	}else{
		$emps = getEmployees($cid);
		$emp_array = getJsonUserEmployees($cid, $lang, $departments, $emp_group);
	}*/
	
	$employees = getJsonUserEmployees($cid, $lang);
	$emps = getEmployees($cid);
	$password = generateStrongPassword(8, false);//randomPassword();

	//var_dump($employees); //exit;
	//$employees = array();
	//var_dump($emp_array);

//$admin = true;
//$disabled = '';

	$users = array();
	$sql = "SELECT * FROM ".$cid."_users WHERE type = 'sys' OR type = 'app' ORDER by type DESC, name ASC";
	if($res = $dbc->query($sql)){
		while($row = $res->fetch_assoc()){ 
			$users[] = $row;
			$copy_from[] = array('data'=>$row['id'], 'value'=>$row['name']);
		}
	}else{
		echo mysqli_error($dbc);
	}
	if(!$users){$users = array();}
	if(!$copy_from){$copy_from = array();}
	//var_dump($users); exit;
	
?>

<style>
	.disabled {
		cursor:not-allowed;
	}
	.disabled i {
		color:#bbb;
	}
	table.basicTable.tac th {
		text-align:center;
		min-width:75px;
	}
	table.basicTable.tac td {
		text-align:center;
	}
</style>
	<style>
		.SumoSelect{
			width: 99% !important;
			min-width: 200px !important;
			padding: 4px 0 0 10px !important;
			border:0 !important;
		}
		.SumoSelect > .CaptionCont {
			background:transparent !important;
			font-weight:600;
		}
	</style>

	<link rel="stylesheet" href="../assets/css/croppie_users.css?<?=time()?>" />
	<link rel="stylesheet" href="../selectize/dist/css/selectize2.css?<?=time()?>" />

	<h2><i class="fa fa-user"></i>&nbsp; <?=$lng['System users']?></h2>
	
	
	<div class="main" style="display:none">
		<div style="padding:0 0 0 20px" id="dump"></div>
			 
		<table border="1" class="basicTable pad010">
			<thead>
				<tr>
					<th class="tac" style="width:1px; padding:0 5px"><i class="fa fa-image fa-lg"></i></th>
					<th class="tac" style="width:1px"><i data-toggle="tooltip" data-placement="right" title="Set permissions" class="fa fa-cog fa-lg"></i></th>
					<th><?=$lng['ID']?></th> 
					<th><?=$lng['Name']?></th> 
					<th><?=$lng['Username']?></th> 
					<!--<th><?=$lng['Phone']?></th> -->
					<th><?=$lng['Type']?></th> 
					<th><?=$lng['Status']?></th>
					<th style="width:80%"></th>
					<? //if($_SESSION['rego']['sys_users']['del'] == 1){?>
					<th style="width:1px"><i class="fa fa-trash fa-lg"></i></th>
					<? //} ?>
				</tr>
			</thead>
			<tbody>
			<? if($users){ 
				foreach($users as $k=>$v){ $pid = $cid.'_'.$v['username'];?>
				<tr>
					<td class="tac" style="padding:0 !important;">
						<center><img style="height:28px; width:28px; margin:2px; cursor:default" class="img-tooltip" data-id="<?=$v['id']?>" title="<img src=../<?=$v['img'].'?'.time()?> />" data-toggle="tooltip" data-placement="right" src="../<?=$v['img'].'?'.time()?>" /></center>
					</td>
					<td class="tac"><a class="permissions<? //if($_SESSION['rego']['sys_users']['edit'] == 1){echo 'permissions';}else{echo 'disabled';}?>" data-id="<?=$v['id']?>"><i class="fa fa-cog fa-lg"></i></a></td>
					<td><?=$v['emp_id']?></td>
					<td><?=$v['name']?></td>
					<td><?=$v['username']?></td>
					<!--<td><?=$v['phone']?></td>-->
					<td style="padding:0 !important">
						<select data-id="<?=$v['id']?>" class="userType" <? //if($_SESSION['rego']['sys_users']['edit'] == 0){echo 'disabled';}?> name="type" style="min-width:100%; width:auto">
							<option <? if($v['type'] == 'sys'){echo 'selected';}?> value="sys"><?=$user_type['sys']?></option>
							<option <? if($v['type'] == 'app'){echo 'selected';}?> value="app"><?=$user_type['app']?></option>
						</select>
					</td>
					<td style="padding:0 !important">
						<select data-id="<?=$v['id']?>" class="userStatus" <? //if($_SESSION['rego']['sys_users']['edit'] == 0){echo 'disabled';}?> name="status" style="min-width:100%; width:auto">
							<? foreach($def_status as $ks=>$vs){ ?>
							<option <? if($ks == $v['status']){echo 'selected';}?> value="<?=$ks?>"><?=$vs?></option>
							<? } ?>
						</select>
					</td>
					<td></td>
					<? //if($_SESSION['rego']['sys_users']['del'] == 1){?>
					<td><a class="delUser" data-id="<?=$v['id']?>" data-ref="<?=$v['ref']?>"><i class="fa fa-trash fa-lg"></i></a></th>
					<? //} ?>
				</tr>
			<? }
			}else{ ?>
				<tr>
					<td colspan="12" style="font-size:14px;color:#000;text-align:center;padding:6px 10px !important; background:#ff6; font-weight:600">
						<?=$lng['No data available in Database']?>
					</td>
				</tr>
			<? } ?>  
			</tbody>
		</table>
		
		<button <? //if($_SESSION['rego']['sys_users']['add'] == 0){echo 'disabled';} ?> style="margin-top:10px;" type="button" class="btn btn-primary" id="add_user"><i class="fa fa-plus"></i>&nbsp; <?=$lng['Add new user']?></button>
		<div class="clear" style="height:15px"></div>

    <form id="permissionForm" style="position:relative">
    	<fieldset disabled>
			<input id="user_id" name="id" type="hidden" value=""  />
			<table class="basicTable" style="margin-top:0px; width:100%; table-layout:auto">
				<thead>
					<tr>
						<th class="tal" colspan="11" style="padding:4px; vertical-align: bottom !important; font-size:18px">
							<table border="0" style="width:100%;">
								<tr>
									<td style="padding:1px 0 0 1px">
										<img id="permImg" style="height:60px; display:block;" src="../images/profile_image.jpg" />
									</td>
									<td style="vertical-align:bottom; padding-bottom:5px; min-width:100px">
										<? //=$lng['Set permissions for']?><span style="color:#a00" id="sysUser"></span>
									</td>
									<td style="vertical-align:bottom">
										<label><input id="selAll" disabled type="checkbox" class="checkbox style-0"><span> Select all</span></label>
									</td>
									<td style="vertical-align:bottom">
										Copy permissions from : <input style="background:transparent; width:250px; border-bottom:1px solid #ccc; font-size:16px; padding:0; margin-left:5px" placeholder="<?=$lng['Type for hints']?> ..." id="copy_from" type="text" />
									</td>
									<td style="width:90%; padding:0 5px 7px 20px; vertical-align:bottom;">
										<span id="accessMsg"></span>
									</td>
									<td style="padding-bottom:8px; vertical-align:bottom">
										<button type="submit" class="btn btn-primary" id="save_settings"><i class="fa fa-save"></i>&nbsp; <?=$lng['Update permissions']?></button>
									</td>
								</tr>
							</table>
						</th> 
					</tr>
				</thead>
			</table>
			<div style="height:5px"></div>
			
			<table id="usersAccess" class="basicTable" style="margin-top:0px; width:100%; table-layout:auto">
				<thead>
					<tr style="line-height:100%; background:#0099CC; color:#fff">
						<th style="color:#fff; padding-right:50px">Employee group</th>
						<th style="color:#fff">Entities</th>
						<th style="color:#fff">Branches</th>
						<th style="color:#fff">Departments</th>
						<th style="color:#fff">Teams</th>
						<th style="width:80%"></th>
					</tr>
				</thead>
				<tbody>
					<tr style="background:#f9f9f9">
						<td style="padding:0">
							<select name="emp_group" style="width:100%; min-width:auto; background:transparent">
							<? foreach($emp_group as $k=>$v){ ?>
								<option value="<?=$k?>"><?=$v?></option>
							<? } ?>
							</select>
						</td>
						<td style="padding:0">
							<select name="entities" multiple="multiple" id="userEntities">
							<? foreach($entities as $k=>$v){ ?>
								<option value="<?=$k?>"><?=$v[$lang]?></option>
							<? } ?>
							</select>
							<input type="hidden" name="divisions">					
							<input type="hidden" name="access_selection">					
						</td>
						<td style="padding:0">
							<select name="branches" multiple="multiple" id="userBranches">
							<? foreach($branches as $k=>$v){ ?>
								<option value="<?=$k?>"><?=$v[$lang]?></option>
							<? } ?>
							</select>						
						</td>
						<td style="padding:0">
							<select name="departments" multiple="multiple" id="userDepartments">
							<? foreach($departments as $k=>$v){ ?>
								<option value="<?=$k?>"><?=$v[$lang]?></option>
							<? } ?>
							</select>						
						</td>
						<td style="padding:0">
							<select name="teams" multiple="multiple" id="userTeams">
							<? foreach($teams as $k=>$v){ ?>
								<option value="<?=$k?>"><?=$v[$lang]?></option>
							<? } ?>
							</select>						
						</td>
						<td></td>
					</tr>
				</tbody>
				<tbody id="accessBody">
					
					<!--<tr>
						<td style="font-weight:600"><i class="fa fa-check-square fa-lg" style="margin-right:8px; color:#0066FF"></i>Employee group</td>
						<td colspan="2" style="padding:0">
							<select data-id="<?=$pid?>" data-field="emp_group" class="update_empgroup" name="emp_group" style="width:100%">
							<? foreach($emp_group as $ke=>$ve){ ?>
								<option <? if($ke == $v['emp_group']){echo 'selected';}?> value="<?=$ke?>"><?=$ve?></option>
							<? } ?>
							</select>
						</td>
						<td colspan="18" style="width:80%"></td>
					</tr>-->
				</tbody>
				
				<!--<thead>
					<tr style="line-height:100%; background:#f6f6f6; border-bottom:1px solid #ddd">
						<th style="min-width:180px">Entities</th>
						<th style="min-width:180px">Branches</th>
						<th style="min-width:180px">Departments</th>
						<th style="min-width:180px">Teams</th>
						<th style="width:80%"></th>
					</tr>
				</thead>-->
				<!--<tbody>
					<tr>
						<td>x</td>
						<td style="vertical-align:top; white-space:normal">
							x
						</td>
						<td style="vertical-align:top; white-space:normal">
							x
						</td>
						<td style="vertical-align:top; white-space:normal">
							x
						</td>
						<td></td>
					</tr>
					<tr>
						<td>x</td>
						<td style="vertical-align:top; white-space:normal">
							x
						</td>
						<td style="vertical-align:top; white-space:normal">
							x
						</td>
						<td style="vertical-align:top; white-space:normal">
							x
						</td>
						<td></td>
					</tr>
					<tr>
						<td>x</td>
						<td style="vertical-align:top; white-space:normal">
							x
						</td>
						<td style="vertical-align:top; white-space:normal">
							x
						</td>
						<td style="vertical-align:top; white-space:normal">
							x
						</td>
						<td></td>
					</tr>
				</tbody>-->
			</table>
							<div style="height:10px"></div>
	
			<table class="basicTable" style="margin-top:0px; width:100%; table-layout:auto">
				<thead>
					<tr style="line-height:100%; background:#393; border-bottom:1px solid #ddd">
						<th style="color:#fff" class="tal">Module</th>
						<th style="color:#fff" class="tal">Section</th>
						<th style="color:#fff" colspan="20" style="width:90%">Permissions</th>
					</tr>
				</thead>
				<tbody>
				
				<!--EMPLOYEE REGISTER //////////////////////////////////////////////////////////////////// --> 
					<tr>
						<th class="tal" style="font-weight:600; vertical-align:baseline; width:1px" rowspan="6">
							<input name="employee[access]" type="hidden" value="0"  />
							<label><input disabled type="checkbox" name="employee[access]" value="1" class="cMod aEmp checkbox"><span> <?=$lng['Employee register']?></span></label><br>
							<label><input disabled type="checkbox" class="allEmp cBox checkbox"><span> Select all</span></label>
						</th>
						<th class="tal">Employee list</th>
						<td class="tal">
							<input name="employee[add]" type="hidden" value="0"  />
							<label><input disabled type="checkbox" name="employee[add]" value="1" class="cEmp cBox checkbox"><span>Add new</span></label>
						</td>
						<td>
							<input name="employee[import]" type="hidden" value="0"  />
							<label><input disabled type="checkbox" name="employee[import]" value="1" class="cEmp cBox checkbox"><span>Import</span></label>
						</td>
						<td>
							<input name="employee[export]" type="hidden" value="0"  />
							<label><input disabled type="checkbox" name="employee[export]" value="1" class="cEmp cBox checkbox"><span>Export</span></label>
						</td>
						<td>
							<input name="employee[del]" type="hidden" value="0"  />
							<label><input disabled type="checkbox" name="employee[del]" value="1" class="cEmp cBox checkbox"><span>Delete</span></label>
						</td>
						<td>
						</td>
						<td></td><td></td><td></td><td style="width:80%"></td>
						<td>
							<input name="employee[report]" type="hidden" value="0"  />
							<label><input disabled type="checkbox" name="employee[report]" value="1" class="cEmp cBox checkbox"><span>Report</span></label>
						</td>
						<td>
							<input name="employee[archive]" type="hidden" value="0"  />
							<label><input disabled type="checkbox" name="employee[archive]" value="1" class="cEmp cBox checkbox"><span>Archive</span></label>
						</td>
						<td>
							<input name="employee[settings]" type="hidden" value="0"  />
							<label><input disabled type="checkbox" name="employee[settings]" value="1" class="cEmp cBox checkbox"><span>Settings</span></label>
						</td>
					</tr>
					<tr>
						<th class="tal">Employee Info</th>
						<td>
							<input name="employee_info[view]" type="hidden" value="0"  />
							<label><input disabled type="checkbox" name="employee_info[view]" value="1" class="cEmp cBox checkbox"><span>View</span></label>
						</td>
						<td>
							<input name="employee_info[edit]" type="hidden" value="0"  />
							<label><input disabled type="checkbox" name="employee_info[edit]" value="1" class="cEmp cBox checkbox"><span>Edit</span></label>
						</td>
						<td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td>
					</tr>
					<tr>
						<th class="tal">Financial Info</th>
						<td>
							<input name="employee_finance[view]" type="hidden" value="0"  />
							<label><input disabled type="checkbox" name="employee_finance[view]" value="1" class="cEmp cBox checkbox"><span>View</span></label>
						</td>
						<td>
							<input name="employee_finance[edit]" type="hidden" value="0"  />
							<label><input disabled type="checkbox" name="employee_finance[edit]" value="1" class="cEmp cBox checkbox"><span>Edit</span></label>
						</td>
						<td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td>
					</tr>
					<tr>
						<th class="tal">Other benefits</th>
						<td>
							<input name="employee_benefit[view]" type="hidden" value="0"  />
							<label><input disabled type="checkbox" name="employee_benefit[view]" value="1" class="cEmp cBox checkbox"><span>View</span></label>
						</td>
						<td>
							<input name="employee_benefit[edit]" type="hidden" value="0"  />
							<label><input disabled type="checkbox" name="employee_benefit[edit]" value="1" class="cEmp cBox checkbox"><span>Edit</span></label>
						</td>
						<td>
							<input name="employee_benefit[add]" type="hidden" value="0"  />
							<label><input disabled type="checkbox" name="employee_benefit[add]" value="1" class="cEmp cBox checkbox"><span>Add</span></label>
						</td>
						<td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td>
					</tr>
					<tr>
						<th class="tal">Historical records</th>
						<td>
							<input name="employee_history[view]" type="hidden" value="0"  />
							<label><input disabled type="checkbox" name="employee_history[view]" value="1" class="cEmp cBox checkbox"><span>View</span></label>
						</td>
						<td>
							<input name="employee_history[edit]" type="hidden" value="0"  />
							<label><input disabled type="checkbox" name="employee_history[edit]" value="1" class="cEmp cBox checkbox"><span>Edit</span></label>
						</td>
						<td>
							<input name="employee_history[add]" type="hidden" value="0"  />
							<label><input disabled type="checkbox" name="employee_history[add]" value="1" class="cEmp cBox checkbox"><span>Add</span></label>
						</td>
						<td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td>
					</tr>
					<tr>
						<th class="tal">Work permit</th>
						<td>
							<input name="employee_permit[view]" type="hidden" value="0"  />
							<label><input disabled type="checkbox" name="employee_permit[view]" value="1" class="cEmp cBox checkbox"><span>View</span></label>
						</td>
						<td>
							<input name="employee_permit[edit]" type="hidden" value="0"  />
							<label><input disabled type="checkbox" name="employee_permit[edit]" value="1" class="cEmp cBox checkbox"><span>Edit</span></label>
						</td>
						<td>
							<input name="employee_permit[add]" type="hidden" value="0"  />
							<label><input disabled type="checkbox" name="employee_permit[add]" value="1" class="cEmp cBox checkbox"><span>Add</span></label>
						</td>
						<td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td>
					</tr>

				<!--LEAVE MODULE //////////////////////////////////////////////////////////////////// --> 
					<tr style="border-top:2px solid #ddd">
						<th class="tal" style="font-weight:600; vertical-align:baseline; width:1px" rowspan="3">
							<input name="leave[access]" type="hidden" value="0"  />
							<label><input disabled type="checkbox" name="leave[access]" value="1" class="cMod aLea checkbox"><span> Leave module<? //=$lng['Employee register']?></span></label><br>
							<label><input disabled type="checkbox" class="allLea cBox checkbox"><span> Select all</span></label>
						</th>
						<th class="tal">Leave application</th>
						<td>
							<input name="leave_application[view]" type="hidden" value="0"  />
							<label><input disabled type="checkbox" name="leave_application[view]" value="1" class="cLea cBox checkbox"><span>View</span></label>
						</td>
						<td>
							<input name="leave_application[edit]" type="hidden" value="0"  />
							<label><input disabled type="checkbox" name="leave_application[edit]" value="1" class="cLea cBox checkbox"><span>Edit</span></label>
						</td>
						<td>
							<input name="leave_application[del]" type="hidden" value="0"  />
							<label><input disabled type="checkbox" name="leave_application[del]" value="1" class="cLea cBox checkbox"><span>Delete</span></label>
						</td>
						<td>
							<input name="leave_application[request]" type="hidden" value="0"  />
							<label><input disabled type="checkbox" name="leave_application[request]" value="1" class="cLea cBox checkbox"><span>Request</span></label>
						</td>
						<td>
							<input name="leave_application[review]" type="hidden" value="0"  />
							<label><input disabled type="checkbox" name="leave_application[review]" value="1" class="cLea cBox checkbox"><span>Review</span></label>
						</td>
						<td>
							<input name="leave_application[approve]" type="hidden" value="0"  />
							<label><input disabled type="checkbox" name="leave_application[approve]" value="1" class="cLea cBox checkbox"><span>Approve</span></label>
						</td>
						<td></td><td></td><td style="width:80%"></td>
						<td>
							<input name="leave[report]" type="hidden" value="0"  />
							<label><input disabled type="checkbox" name="leave[report]" value="1" class="cLea cBox checkbox"><span>Report</span></label>
						</td>
						<td>
							<input name="leave[archive]" type="hidden" value="0"  />
							<label><input disabled type="checkbox" name="leave[archive]" value="1" class="cLea cBox checkbox"><span>Archive</span></label>
						</td>
						<td>
							<input name="leave[settings]" type="hidden" value="0"  />
							<label><input disabled type="checkbox" name="leave[settings]" value="1" class="cLea cBox checkbox"><span>Settings</span></label>
						</td>
					</tr>
					<tr>
						<th class="tal">Leave calendar</th>
						<td>
							<input name="leave_calendar[view]" type="hidden" value="0"  />
							<label><input disabled type="checkbox" name="leave_calendar[view]" value="1" class="cLea cBox checkbox"><span>View</span></label>
						</td>
						<td>
							<input name="leave_calendar[edit]" type="hidden" value="0"  />
							<label><input disabled type="checkbox" name="leave_calendar[edit]" value="1" class="cLea cBox checkbox"><span>Edit</span></label>
						</td>
						<td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td>
					</tr>
					<tr>
						<th class="tal">Approve for payroll</th>
						<td>
							<input name="leave_approve[view]" type="hidden" value="0"  />
							<label><input disabled type="checkbox" name="leave_approve[view]" value="1" class="cLea cBox checkbox"><span>View</span></label>
						</td>
						<td>
							<input name="leave_approve[edit]" type="hidden" value="0"  />
							<label><input disabled type="checkbox" name="leave_approve[edit]" value="1" class="cLea cBox checkbox"><span>Edit</span></label>
						</td>
						<td>
							<input name="leave_approve[del]" type="hidden" value="0"  />
							<label><input disabled type="checkbox" name="leave_approve[del]" value="1" class="cLea cBox checkbox"><span>Delete</span></label>
						</td>
						<td>
							<input name="leave_approve[request]" type="hidden" value="0"  />
							<label><input disabled type="checkbox" name="leave_approve[request]" value="1" class="cLea cBox checkbox"><span>Request</span></label>
						</td>
						<td>
							<input name="leave_approve[review]" type="hidden" value="0"  />
							<label><input disabled type="checkbox" name="leave_approve[review]" value="1" class="cLea cBox checkbox"><span>Review</span></label>
						</td>
						<td>
							<input name="leave_approve[approve]" type="hidden" value="0"  />
							<label><input disabled type="checkbox" name="leave_approve[approve]" value="1" class="cLea cBox checkbox"><span>Approve</span></label>
						</td>
						<td>
							<input name="leave_approve[lock]" type="hidden" value="0"  />
							<label><input disabled type="checkbox" name="leave_approve[lock]" value="1" class="cLea cBox checkbox"><span>Lock</span></label>
						</td>
						<td></td><td></td><td></td><td></td><td>
					</tr>

				<!--TIME MODULE //////////////////////////////////////////////////////////////////// --> 
					<tr style="border-top:2px solid #ddd">
						<th class="tal" style="font-weight:600; vertical-align:baseline; width:1px" rowspan="5">
							<input name="time[access]" type="hidden" value="0"  />
							<label><input disabled type="checkbox" name="time[access]" value="1" class="cMod aTim checkbox"><span> Time module<? //=$lng['Employee register']?></span></label><br>
							<label><input disabled type="checkbox" class="allTim cBox checkbox"><span> Select all</span></label>
						</th>
						<th class="tal">Import timesheet</th>
						<td>
							<input name="time_import[view]" type="hidden" value="0"  />
							<label><input disabled type="checkbox" name="time_import[view]" value="1" class="cTim cBox checkbox"><span>View</span></label>
						</td>
						<td>
							<input name="time_import[edit]" type="hidden" value="0"  />
							<label><input disabled type="checkbox" name="time_import[edit]" value="1" class="cTim cBox checkbox"><span>Edit</span></label>
						</td>
						<td>
							<input name="time_import[edit]" type="hidden" value="0"  />
							<label><input disabled type="checkbox" name="time_import[edit]" value="1" class="cTim cBox checkbox"><span>Delete</span></label>
						</td>
						<td>
							<input name="time_import[edit]" type="hidden" value="0"  />
							<label><input disabled type="checkbox" name="time_import[edit]" value="1" class="cTim cBox checkbox"><span>Approve</span></label>
						</td>
						<td>
						</td>
						<td>
						</td>
						<td></td><td></td><td style="width:80%"></td>
						<td>
							<input name="time[report]" type="hidden" value="0"  />
							<label><input disabled type="checkbox" name="time[report]" value="1" class="cTim cBox checkbox"><span>Report</span></label>
						</td>
						<td>
							<input name="time[archive]" type="hidden" value="0"  />
							<label><input disabled type="checkbox" name="time[archive]" value="1" class="cTim cBox checkbox"><span>Archive</span></label>
						</td>
						<td>
							<input name="time[settings]" type="hidden" value="0"  />
							<label><input disabled type="checkbox" name="time[settings]" value="1" class="cTim cBox checkbox"><span>Settings</span></label>
						</td>
					</tr>
					<tr>
						<th class="tal">Time attendance</th>
						<td>
							<input name="time_attendance[view]" type="hidden" value="0"  />
							<label><input disabled type="checkbox" name="time_attendance[view]" value="1" class="cTim cBox checkbox"><span>View</span></label>
						</td>
						<td>
							<input name="time_attendance[edit]" type="hidden" value="0"  />
							<label><input disabled type="checkbox" name="time_attendance[edit]" value="1" class="cTim cBox checkbox"><span>Edit</span></label>
						</td>
						<td>
							<input name="time_attendance[del]" type="hidden" value="0"  />
							<label><input disabled type="checkbox" name="time_attendance[del]" value="1" class="cTim cBox checkbox"><span>Delete</span></label>
						</td>
						<td>
							<input name="time_attendance[approve]" type="hidden" value="0"  />
							<label><input disabled type="checkbox" name="time_attendance[approve]" value="1" class="cTim cBox checkbox"><span>Approve</span></label>
						</td>
						<td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td>
					</tr>
					<tr>
						<th class="tal">Monthly attendance</th>
						<td>
							<input name="time_monthly[view]" type="hidden" value="0"  />
							<label><input disabled type="checkbox" name="time_monthly[view]" value="1" class="cTim cBox checkbox"><span>View</span></label>
						</td>
						<td>
							<input name="time_monthly[edit]" type="hidden" value="0"  />
							<label><input disabled type="checkbox" name="time_monthly[edit]" value="1" class="cTim cBox checkbox"><span>Edit</span></label>
						</td>
						<td>
							<input name="time_monthly[review]" type="hidden" value="0"  />
							<label><input disabled type="checkbox" name="time_monthly[review]" value="1" class="cTim cBox checkbox"><span>Review</span></label>
						</td>
						<td>
							<input name="time_monthly[approve]" type="hidden" value="0"  />
							<label><input disabled type="checkbox" name="time_monthly[approve]" value="1" class="cTim cBox checkbox"><span>Approve</span></label>
						</td>
						<td>
							<input name="time_monthly[lock]" type="hidden" value="0"  />
							<label><input disabled type="checkbox" name="time_monthly[lock]" value="1" class="cTim cBox checkbox"><span>Lock</span></label>
						</td>
						<td></td><td></td><td></td><td></td><td></td><td></td><td></td>
					</tr>
					<tr>
						<th class="tal">Monthly planning</th>
						<td>
							<input name="time_planning[view]" type="hidden" value="0"  />
							<label><input disabled type="checkbox" name="time_planning[view]" value="1" class="cTim cBox checkbox"><span>View</span></label>
						</td>
						<td>
							<input name="time_planning[edit]" type="hidden" value="0"  />
							<label><input disabled type="checkbox" name="time_planning[edit]" value="1" class="cTim cBox checkbox"><span>Edit</span></label>
						</td>
						<td>
							<input name="time_planning[del]" type="hidden" value="0"  />
							<label><input disabled type="checkbox" name="time_planning[del]" value="1" class="cTim cBox checkbox"><span>Delete</span></label>
						</td>
						<td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td>
					</tr>
					<tr>
						<th class="tal">Shift requests</th>
						<td>
							<input name="time_shift[view]" type="hidden" value="0"  />
							<label><input disabled type="checkbox" name="time_shift[view]" value="1" class="cTim cBox checkbox"><span>View</span></label>
						</td>
						<td>
							<input name="time_shift[edit]" type="hidden" value="0"  />
							<label><input disabled type="checkbox" name="time_shift[edit]" value="1" class="cTim cBox checkbox"><span>Edit</span></label>
						</td>
						<td>
							<input name="time_shift[del]" type="hidden" value="0"  />
							<label><input disabled type="checkbox" name="time_shift[del]" value="1" class="cTim cBox checkbox"><span>Delete</span></label>
						</td>
						<td>
							<input name="time_shift[approve]" type="hidden" value="0"  />
							<label><input disabled type="checkbox" name="time_shift[approve]" value="1" class="cTim cBox checkbox"><span>Approve</span></label>
						</td>
						<td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td>
					</tr>

				
				<!--PAYROLL MODULE //////////////////////////////////////////////////////////////////// --> 
					<tr style="border-top:2px solid #ddd">
						<th class="tal" style="font-weight:600; vertical-align:baseline; width:1px" rowspan="7">
							<input name="payroll[access]" type="hidden" value="0"  />
							<label><input disabled type="checkbox" name="payroll[access]" value="1" class="cMod aPay checkbox"><span> Payroll module<? //=$lng['Employee register']?></span></label><br>
							<label><input disabled type="checkbox" class="allPay cBox checkbox"><span> Select all</span></label>
						</th>
						<th class="tal">Monthly attendance</th>
						<td>
							<input name="payroll_attendance[view]" type="hidden" value="0"  />
							<label><input disabled type="checkbox" name="payroll_attendance[view]" value="1" class="cPay cBox checkbox"><span>View</span></label>
						</td>
						<td>
							<input name="payroll_attendance[edit]" type="hidden" value="0"  />
							<label><input disabled type="checkbox" name="payroll_attendance[edit]" value="1" class="cPay cBox checkbox"><span>Edit</span></label>
						</td>
						<td>
						</td>
						<td>
						</td>
						<td>
						</td>
						<td>
						</td>
						<td></td><td></td><td style="width:80%"></td>
						<td>
							<input name="payroll[report]" type="hidden" value="0"  />
							<label><input disabled type="checkbox" name="payroll[report]" value="1" class="cPay cBox checkbox"><span>Report</span></label>
						</td>
						<td>
							<input name="payroll[archive]" type="hidden" value="0"  />
							<label><input disabled type="checkbox" name="payroll[archive]" value="1" class="cPay cBox checkbox"><span>Archive</span></label>
						</td>
						<td>
							<input name="payroll[settings]" type="hidden" value="0"  />
							<label><input disabled type="checkbox" name="payroll[settings]" value="1" class="cPay cBox checkbox"><span>Settings</span></label>
						</td>
					</tr>
					<tr>
						<th class="tal">Payroll results</th>
						<td>
							<input name="payroll_result[view]" type="hidden" value="0"  />
							<label><input disabled type="checkbox" name="payroll_result[view]" value="1" class="cPay cBox checkbox"><span>View</span></label>
						</td>
						<td>
							<input name="payroll_result[edit]" type="hidden" value="0"  />
							<label><input disabled type="checkbox" name="payroll_result[edit]" value="1" class="cPay cBox checkbox"><span>Edit</span></label>
						</td>
						<td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td>
					</tr>
					<tr>
						<th class="tal">Government forms</th>
						<td>
							<input name="payroll_forms[view]" type="hidden" value="0"  />
							<label><input disabled type="checkbox" name="payroll_forms[view]" value="1" class="cPay cBox checkbox"><span>View</span></label>
						</td>
						<td>
							<input name="payroll_forms[edit]" type="hidden" value="0"  />
							<label><input disabled type="checkbox" name="payroll_forms[edit]" value="1" class="cPay cBox checkbox"><span>Edit</span></label>
						</td>
						<td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td>
					</tr>
					<tr>
						<th class="tal">Export center</th>
						<td>
							<input name="payroll_export[view]" type="hidden" value="0"  />
							<label><input disabled type="checkbox" name="payroll_export[view]" value="1" class="cPay cBox checkbox"><span>View</span></label>
						</td>
						<td>
							<input name="payroll_export[edit]" type="hidden" value="0"  />
							<label><input disabled type="checkbox" name="payroll_export[edit]" value="1" class="cPay cBox checkbox"><span>Edit</span></label>
						</td>
						<td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td>
					</tr>
					<tr>
						<th class="tal">Variable Benefits</th>
						<td>
							<input name="payroll_benefits[view]" type="hidden" value="0"  />
							<label><input disabled type="checkbox" name="payroll_benefits[view]" value="1" class="cPay cBox checkbox"><span>View</span></label>
						</td>
						<td>
							<input name="payroll_benefits[edit]" type="hidden" value="0"  />
							<label><input disabled type="checkbox" name="payroll_benefits[edit]" value="1" class="cPay cBox checkbox"><span>Edit</span></label>
						</td>
						<td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td>
					</tr>
					<tr>
						<th class="tal">Calculations</th>
						<td>
							<input name="payroll_calculations[view]" type="hidden" value="0"  />
							<label><input disabled type="checkbox" name="payroll_calculations[view]" value="1" class="cPay cBox checkbox"><span>View</span></label>
						</td>
						<td>
							<input name="payroll_calculations[edit]" type="hidden" value="0"  />
							<label><input disabled type="checkbox" name="payroll_calculations[edit]" value="1" class="cPay cBox checkbox"><span>Edit</span></label>
						</td>
						<td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td>
					</tr>
					<tr>
						<th class="tal">Historical data</th>
						<td>
							<input name="payroll_historical[view]" type="hidden" value="0"  />
							<label><input disabled type="checkbox" name="payroll_historical[view]" value="1" class="cPay cBox checkbox"><span>View</span></label>
						</td>
						<td>
							<input name="payroll_historical[edit]" type="hidden" value="0"  />
							<label><input disabled type="checkbox" name="payroll_historical[edit]" value="1" class="cPay cBox checkbox"><span>Edit</span></label>
						</td>
						<td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td>
					</tr>

				<!--BENEFITS & EXPENCES //////////////////////////////////////////////////////////////////// --> 
					<tr style="border-top:2px solid #ddd">
						<th class="tal" style="font-weight:600; vertical-align:baseline; width:1px">
							<input name="expences[access]" type="hidden" value="0"  />
							<label><input disabled type="checkbox" name="expences[access]" value="1" class="cMod aBen checkbox"><span> Benefits & Expences<? //=$lng['Employee register']?></span></label>
						</th>
						<th class="tal">
							<!--<label><input disabled type="checkbox" value="1" class="cBen cBox checkbox"><span>Select all<? //=$lng['View']?></span></label>-->
						</th>
						<td>
							<input name="expences[view]" type="hidden" value="0"  />
							<label><input disabled type="checkbox" name="expences[view]" value="1" class="cBen cBox checkbox"><span>View</span></label>
						</td>
						<td>
							<input name="expences[edit]" type="hidden" value="0"  />
							<label><input disabled type="checkbox" name="expences[edit]" value="1" class="cBen cBox checkbox"><span>Edit</span></label>
						</td>
						<td>
						</td>
						<td></td><td></td><td></td><td></td><td></td><td style="width:80%"></td>
						<td>
							<input name="expences[report]" type="hidden" value="0"  />
							<label><input disabled type="checkbox" name="expences[report]" value="1" class="cBen cBox checkbox"><span>Report</span></label>
						</td>
						<td>
							<input name="expences[archive]" type="hidden" value="0"  />
							<label><input disabled type="checkbox" name="expences[archive]" value="1" class="cBen cBox checkbox"><span>Archive</span></label>
						</td>
						<td>
							<input name="expences[settings]" type="hidden" value="0"  />
							<label><input disabled type="checkbox" name="expences[settings]" value="1" class="cBen cBox checkbox"><span>Settings</span></label>
						</td>
					</tr>

				<!--PROJECTS //////////////////////////////////////////////////////////////////// --> 
					<tr style="border-top:2px solid #ddd">
						<th class="tal" style="font-weight:600; vertical-align:baseline; width:1px">
							<input name="project[access]" type="hidden" value="0"  />
							<label><input disabled type="checkbox" name="project[access]" value="1" class="cMod aPro checkbox"><span> Projects<? //=$lng['Employee register']?></span></label>
						</th>
						<th class="tal">
							<!--<label><input disabled type="checkbox" class="cPro cBox checkbox"><span>Select all<? //=$lng['View']?></span></label>-->
						</th>
						<td>
							<input name="project[view]" type="hidden" value="0"  />
							<label><input disabled type="checkbox" name="project[view]" value="1" class="cPro cBox checkbox"><span>View</span></label>
						</td>
						<td>
							<input name="project[edit]" type="hidden" value="0"  />
							<label><input disabled type="checkbox" name="project[edit]" value="1" class="cPro cBox checkbox"><span>Edit</span></label>
						</td>
						<td>
						</td>
						<td></td><td></td><td></td><td></td><td></td><td style="width:80%"></td>
						<td>
							<input name="project[report]" type="hidden" value="0"  />
							<label><input disabled type="checkbox" name="project[report]" value="1" class="cPro cBox checkbox"><span>Report</span></label>
						</td>
						<td>
							<input name="project[archive]" type="hidden" value="0"  />
							<label><input disabled type="checkbox" name="project[archive]" value="1" class="cPro cBox checkbox"><span>Archive</span></label>
						</td>
						<td>
							<input name="project[settings]" type="hidden" value="0"  />
							<label><input disabled type="checkbox" name="project[settings]" value="1" class="cPro cBox checkbox"><span>Settings</span></label>
						</td>
					</tr>

				<!--SETTINGS //////////////////////////////////////////////////////////////////// --> 
					<tr style="border-top:2px solid #ddd">
						<th class="tal" style="font-weight:600; vertical-align:baseline; width:1px">
							<input name="settings[access]" type="hidden" value="0"  />
							<label><input disabled type="checkbox" name="settings[access]" value="1" class="cMod aSet checkbox"><span> Settings<? //=$lng['Employee register']?></span></label>
						</th>
						<th class="tal">
							<!--<label><input disabled type="checkbox" class="cSet cBox checkbox"><span>Select all<? //=$lng['View']?></span></label>-->
						</th>
						<td>
							<input name="settings[view]" type="hidden" value="0"  />
							<label><input disabled type="checkbox" name="settings[view]" value="1" class="cSet cBox checkbox"><span>View</span></label>
						</td>
						<td>
							<input name="settings[edit]" type="hidden" value="0"  />
							<label><input disabled type="checkbox" name="settings[edit]" value="1" class="cSet cBox checkbox"><span>Edit</span></label>
						</td>
						<td>
						</td>
						<td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td>
					</tr>

				<!--SUPPORT DESK //////////////////////////////////////////////////////////////////// --> 
					<tr style="border-top:2px solid #ddd">
						<th class="tal" style="font-weight:600; vertical-align:baseline; width:1px">
							<input name="support[access]" type="hidden" value="0"  />
							<label><input disabled type="checkbox" name="support[access]" value="1" class="cMod aSup checkbox"><span> Support desk<? //=$lng['Employee register']?></span></label>
						</th>
						<th class="tal">
							<!--<label><input disabled type="checkbox" class="cSup cBox checkbox"><span>Select all<? //=$lng['View']?></span></label>-->
						</th>
						<td>
							<input name="support[view]" type="hidden" value="0"  />
							<label><input disabled type="checkbox" name="support[view]" value="1" class="cSup cBox checkbox"><span>View</span></label>
						</td>
						<td>
							<input name="support[edit]" type="hidden" value="0"  />
							<label><input disabled type="checkbox" name="support[edit]" value="1" class="cSup cBox checkbox"><span>Edit</span></label>
						</td>
						<td>
						</td>
						<td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td>
					</tr>
               
					
               
        </tbody>
			</table>	
      </fieldset>
		</form>
    <div style="height:50px"></div> 
	</div>
	
	<!-- Modal Add User -->
	<div class="modal fade" id="modalUser" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false">
		<div class="modal-dialog" role="document" style="max-width:700px">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title"><i class="fa fa-user"></i>&nbsp;&nbsp;<?=$lng['Add new user']?></h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body" style="padding-bottom:12px">
					<span style="font-weight:600; color:#cc0000; display:none; display:block; margin:-5px 0 5px 0" id="userMess"></span>
					<form id="userForm">
						<!--<input type="hidden" name="firstname" id="firstname" />-->
						<input type="hidden" name="name" id="name" />
						<input type="hidden" name="emp_id" id="emp_id" />
						<input type="hidden" name="img" id="img" value="images/profile_image.jpg" />
						<table border="0" style="width:100%">
            	<tr>
              	<td class="vat" style="padding:0 15px 0 0; border-right:1px #eee solid; width:80%">
									<table class="basicTable inputs" border="0">
										<tbody>
										<tr>
											<th><i class="man"></i><?=$lng['Name']?></th>
											<td><input placeholder="<?=$lng['Type for hints']?> ..." id="system_user_name" type="text" /></td>
										</tr>
										<tr>
											<th><i class="man"></i><?=$lng['email']?></th>
											<td><input onKeyUp="$('#username').val(this.value);" onChange="$('#username').val(this.value);" id="email" type="text" value="" /></td>
										</tr>
										<tr>
											<th><i class="man"></i><?=$lng['Username']?></th>
											<td><input readonly class="nofocus" name="username" id="username" type="text" value="" /></td>
										</tr>
										<tr>
											<th><i class="man"></i><?=$lng['Password']?></th>
											<td><input autocomplete="off" name="password" id="password" type="text" value="<?=$password?>" /></td>
										</tr>
										<tr>
											<th><?=$lng['Phone']?></th>
											<td><input name="phone" type="text" value="" /></td>
										</tr>
										<tr>
											<th><i class="man"></i><?=$lng['Type']?></th>
											<td>
												<select name="type" id="type" style="width:100%">
													<option value="sys"><?=$user_type['sys']?></option>
													<option value="app"><?=$user_type['app']?></option>
												</select>
											</td>
										</tr>
										<tr>
											<th><i class="man"></i><?=$lng['Status']?></th>
											<td colspan="2">
												<select name="status" id="status" style="width:100%">
													<? foreach($def_status as $k=>$v){ ?>
														<option value="<?=$k?>"><?=$v?></option>
													<? } ?>
												</select>
											</td>
										</tr>
										</tbody>
									</table>
								</td>
                <td class="vat" style="width:1%; padding:5px 0 0 15px;">
									<div id="upload-demo" style="margin:0 0 5px;"></div>
									<input style="height:0; visibility:hidden" id="selectUserImg" type="file" name="user_img" />
									<? //if($admin){ ?>
									<button onclick="$('#selectUserImg').click();" type="button" id="userBut" style="width:100%; margin:0" class="btn btn-primary btn-xs"><i class="fa fa-user"></i>&nbsp;&nbsp;<?=$lng['Select picture']?></button>
									<? //} ?>
								</td>
         			</tr>
						</table>
					
						<div class="clear" style="height:15px"></div>
						<button class="btn btn-primary btn-fl" type="button" id="saveUser" ><i class="fa fa-save"></i>&nbsp; <?=$lng['Save']?></button>
						<button class="btn btn-primary btn-fr" type="button" data-dismiss="modal"><i class="fa fa-times"></i>&nbsp; <?=$lng['Cancel']?></button>
						<div class="clear"></div>
					</form>
					</div>
			  </div>
		 </div>
	</div>

	<!-- PAGE RELATED PLUGIN(S) -->
	<script src="../assets/js/croppie.js?<?=time()?>"></script>
	<script src="../assets/js/jquery.autocomplete.js"></script>
	<!--<script src="../assets/js/jquery.sumoselect.min.js"></script>-->
	<script src="../selectize/dist/js/standalone/selectize.js"></script>

	<script>

	$(document).ready(function() {
	
		var changeSumo = 1;
		
		function updateAccess(access, values){
			changeSumo = 0;
			$.ajax({
				url: "ajax/update_user_access.php",
				data: {access: access, values: values},
				dataType: 'json',
				success: function(result){
					//$('#dump').html(result); //return false;
					$('#userEntities')[0].sumo.unSelectAll();
					$.each(result.entity, function(v){
						$('#userEntities')[0].sumo.selectItem(v);
					})
					$('#userBranches')[0].sumo.unSelectAll();
					$.each(result.branch, function(v){
						$('#userBranches')[0].sumo.selectItem(v);
					})
					$('#userDepartments')[0].sumo.unSelectAll();
					$.each(result.department, function(v){
						$('#userDepartments')[0].sumo.selectItem(v);
					})
					$('#userTeams')[0].sumo.unSelectAll();
					$.each(result.team, function(v){
						$('#userTeams')[0].sumo.selectItem(v);
					})
					changeSumo = 1;
					$('input[name="divisions"]').val(result.division);
					$('input[name="access_selection"]').val(result.tableRow);
					$('#usersAccess tbody#accessBody').html('');
					$('#usersAccess tbody#accessBody').html(result.tableRow); //return false;
					//window.location.reload();
				}
			});
		}
		
		$('#userEntities').SumoSelect({
			okCancelInMulti:true, 
			selectAll:true,
			//triggerChangeCombined: true,
			csvDispCount:1,
			outputAsCSV : true,
			showTitle : false,
			placeholder: 'Select entities<? //=$lng['Select departments']?>',
			captionFormat: 'Entities ({0})',
			captionFormatAllSelected: 'All entities ({0})<? //=$lng['All departments']?>',
		});
		$('#userEntities').on('change',function(e){
			if(changeSumo){updateAccess('entities', $(this).val());}; 
		});

		$('#userBranches').SumoSelect({
			okCancelInMulti:true, 
			selectAll:true,
			//triggerChangeCombined: true,
			csvDispCount:1,
			outputAsCSV : true,
			showTitle : false,
			placeholder: 'Select branches<? //=$lng['Select departments']?>',
			captionFormat: 'Branches ({0})',
			captionFormatAllSelected: 'All branches ({0})<? //=$lng['All departments']?>',
		});
		$('#userBranches').on('change',function(e){
			if(changeSumo){updateAccess('branches', $(this).val());}; 
		});

		$('#userDepartments').SumoSelect({
			okCancelInMulti:true, 
			selectAll:true,
			//triggerChangeCombined: true,
			csvDispCount:1,
			outputAsCSV : true,
			showTitle : false,
			placeholder: 'Select departments<? //=$lng['Select departments']?>',
			captionFormat: 'Departments ({0})',
			captionFormatAllSelected: 'All departments ({0})<? //=$lng['All departments']?>',
		});
		$('#userDepartments').on('change',function(e){
			if(changeSumo){updateAccess('departments', $(this).val());}; 
		});

		$('#userTeams').SumoSelect({
			okCancelInMulti:true, 
			selectAll:true,
			//triggerChangeCombined: true,
			csvDispCount:1,
			outputAsCSV : true,
			showTitle : false,
			placeholder: 'Select teams<? //=$lng['Select departments']?>',
			captionFormat: 'Teams ({0})',
			captionFormatAllSelected: 'All teams ({0})<? //=$lng['All departments']?>',
		});
		$('#userTeams').on('change',function(e){
			if(changeSumo){updateAccess('teams', $(this).val());}; 
		});
		$('#userEntities')[0].sumo.disable();
		$('#userBranches')[0].sumo.disable();
		$('#userDepartments')[0].sumo.disable();
		$('#userTeams')[0].sumo.disable();
		
		$('#userEntities')[0].sumo.unSelectAll();
		$('#userBranches')[0].sumo.unSelectAll();
		$('#userDepartments')[0].sumo.unSelectAll();
		$('#userTeams')[0].sumo.unSelectAll();

		//var update = 0;
		var employees = <?=json_encode($employees)?>;
		var copy_from = <?=json_encode($copy_from)?>;
		var emps = <?=json_encode($emps)?>;
		//var id;
		
		// devbridgeAutocomplete ---------------------------------------------------------------------------------
		$('#system_user_name').on('change', function(){
			//alert($(this).val())
			$("#name").val($(this).val());
		})
		
		$('#system_user_name').devbridgeAutocomplete({
			 lookup: employees,
			 minChars: 0,
			 onSelect: function (suggestion) {
				$("#emp_id").val(emps[suggestion.data]['emp_id']);
				$("#phone").val(emps[suggestion.data]['phone']);
				$("#email").val(emps[suggestion.data]['email']);
				$("#username").val(emps[suggestion.data]['email']);
				//$("#firstname").val(emps[suggestion.data]['firstname']);
				$("#name").val(emps[suggestion.data]['<?=$lang?>_name']);
				$("#img").val(emps[suggestion.data]['image']);
				$('#upload-demo').css('background-image', 'url(../'+emps[suggestion.data]['image']+')');
			 }
		});	
		
		$('#copy_from').devbridgeAutocomplete({
			lookup: copy_from,
			minChars: 0,
			onSelect: function (suggestion) {
				getPermissionData(suggestion.data, false)
			}
		})/*.focus(function () {
        $(this).devbridgeAutocomplete('search', $(this).val())
    });*/	
		
		var $uploadCrop;
		var maxSize = 2000;
		//var minHeight = 150;
		//var minWidth = 150;
		$uploadCrop = $('#upload-demo').croppie({
			viewport: {
				width: 150,
				height: 150,
				type: 'square'
			},
			boundary: {
				width: 180,
				height: 180
			},
			mouseWheelZoom: true,
			showZoom: true
		});
		
		$('#selectUserImg').on('change', function () { 
			$("#userBut i").removeClass('fa-user').addClass('fa-repeat fa-spin');
			readFile(this);
		});
		
		function readFile(input) {
			if(input.files && input.files[0]) {
				var file = input.files[0];
				var reader = new FileReader();
				reader.onload = function (e) {
					var img = new Image;
					var t = file.type.split('/')[1];
					var n = file.name;
					var s = ~~(file.size/1024);
					var msg = "";
					if(s > maxSize){msg = '<?=$lng['Filesize is to bigg']?> ('+(s/1024).format(2)+' Mb) - Max. '+(maxSize/1000)+' Mb';}
					if(t != 'jpeg' && t != 'png' && t != 'jpg'){msg = "<?=$lng['Please use only ... files']?>";}
					if(msg!=''){
						$("body").overhang({
							type: "error",
							message: '<i class="fa fa-exclamation-triangle"></i>&nbsp;&nbsp;<?=$lng['Error']?> : '+msg,
							duration: 4,
						})
						$("p#selpic i").removeClass('fa-repeat fa-spin').addClass('fa-user');
						$("#upload").val('');
						return false;
					}
					img.src = e.target.result;
					img.onload = function() {
						$("#userBut i").removeClass('fa-repeat fa-spin').addClass('fa-user');
					};
					//$('#klik').html('');
					//alert(e.target.result)
					$('#orig').attr('src', e.target.result);
					$uploadCrop.croppie('bind', {
						url: e.target.result
					});
					$('.upload-demo').addClass('ready');
					$("#message").fadeOut();
				}
				reader.readAsDataURL(input.files[0]);
			}else{
				swal("Sorry - you're browser doesn't support the FileReader API");
			}
		};
	
		function getPermissionData(id, copy){
			if(copy){
				$("#user_id").val(id);
				$('#copy_from').val('');
			}
			$('input[type="checkbox"]').prop('checked',false);
			//$('input[type="checkbox"]').prop('disabled',true);
			$.ajax({
				url: "ajax/get_permission_data.php",
				data: {id: id},
				dataType: 'json',
				success:function(data){
					//var data = jQuery.parseJSON(result);
					//alert(data.branches);
					if(copy){
						$("#sysUser").html(data.name);
						$("#permImg").prop('src', '../'+data.img);
					}
					//$('#dump').html(data); //return false;
					
					$('#save_settings').css('display','block')
					$('#save_settings').prop('disabled',false)
					$('#permissionForm fieldset').prop('disabled',false)
					$('input[type="checkbox"]').prop('disabled',false);
					$('input[type="checkbox"]').prop('checked',false);
					
					$.each(data.permissions, function (key, val) {
						$.each(val, function (k, v) {
							if(v == 1){
								$('input[name="'+key+'['+k+']"]').prop('checked',true)
							}
						});
					});
					changeSumo = 0;
					$('#userEntities')[0].sumo.enable();
					$('#userBranches')[0].sumo.enable();
					$('#userDepartments')[0].sumo.enable();
					$('#userTeams')[0].sumo.enable();
					
					$.each((data.entities).split(','), function(v){
						$('#userEntities')[0].sumo.selectItem(v);
					})
					$.each((data.branches).split(','), function(v){
						$('#userBranches')[0].sumo.selectItem(v);
					})
					$.each((data.departments).split(','), function(v){
						$('#userDepartments')[0].sumo.selectItem(v);
					})
					$.each((data.teams).split(','), function(v){
						//$('#userTeams')[0].sumo.selectItem(v);
						alert(v)
					})
					$('input[name="divisions"]').val(data.divisions);
					$('#usersAccess tbody#accessBody').html(data.access_selection);

					changeSumo = 1;
				
					$('select[name="emp_group"]').val(data.emp_group);
					
					$('input.cMod').each(function(){
						if(!$(this).is(':checked')){
							$(this).trigger('change')
						}
					});
				},
				error:function (xhr, ajaxOptions, thrownError){
					$("body").overhang({
						type: "error",
						message: '<i class="fa fa-exclamation-circle"></i>&nbsp;&nbsp;<?=$lng['Sorry but someting went wrong']?> <b><?=$lng['Error']?></b> : '+thrownError,
						duration: 4,
					})
				}
			});
		}	
			
		$(document).on("change", ".userType", function(e){
			$.ajax({
				url: "ajax/update_user_type_status.php",
				data: {id: $(this).data('id'), value: $(this).val(), field: 'type'},
				success: function(result){
					//$('#dump').html(result); return false;
				}
			});
		});
		$(document).on("change", ".userStatus", function(e){
			$.ajax({
				url: "ajax/update_user_type_status.php",
				data: {id: $(this).data('id'), value: $(this).val(), field: 'status'},
				success: function(result){
					//$('#dump').html(result); return false;
				}
			});
		});

			// SHOW PERMISSION FORM -------------------------------------------------------------------------------------------
			$(document).on("click", ".permissions", function(e){
				e.preventDefault();
				getPermissionData($(this).data('id'), true);
			});
			$(document).on("click", "#selAll", function(e){
				$('input[type="checkbox"]').prop('checked', this.checked);
				if(this.checked){
					$('.cBox').prop('disabled', false);
				}else{
					$('.cBox').prop('disabled', true);
				}
			})
			
			$(document).on("change", ".aEmp", function(e){
				if(!$(this).is(':checked')){
					$('.cEmp').prop('checked', false);
					$('.cEmp').prop('disabled', true);
					$('.allEmp').prop('disabled', true);
				}else{
					$('.cEmp').prop('disabled', false);
					$('.allEmp').prop('disabled', false);
				}
			});
			$(document).on("change", ".allEmp", function(e){
				if(!$(this).is(':checked')){
					$('.cEmp').prop('checked', false);
				}else{
					$('.cEmp').prop('checked', true);
				}
			});
			
			$(document).on("change", ".aLea", function(e){
				if(!$(this).is(':checked')){
					$('.cLea').prop('checked', false);
					$('.cLea').prop('disabled', true);
					$('.allLea').prop('disabled', true);
				}else{
					$('.cLea').prop('disabled', false);
					$('.allLea').prop('disabled', false);
				}
			});
			$(document).on("change", ".allLea", function(e){
				if(!$(this).is(':checked')){
					$('.cLea').prop('checked', false);
				}else{
					$('.cLea').prop('checked', true);
				}
			});
			
			$(document).on("change", ".aTim", function(e){
				if(!$(this).is(':checked')){
					$('.cTim').prop('checked', false);
					$('.cTim').prop('disabled', true);
					$('.allTim').prop('disabled', true);
				}else{
					$('.cTim').prop('disabled', false);
					$('.allTim').prop('disabled', false);
				}
			});
			$(document).on("change", ".allTim", function(e){
				if(!$(this).is(':checked')){
					$('.cTim').prop('checked', false);
				}else{
					$('.cTim').prop('checked', true);
				}
			});
			
			$(document).on("change", ".aPay", function(e){
				if(!$(this).is(':checked')){
					$('.cPay').prop('checked', false);
					$('.cPay').prop('disabled', true);
					$('.allPay').prop('disabled', true);
				}else{
					$('.cPay').prop('disabled', false);
					$('.allPay').prop('disabled', false);
				}
			});
			$(document).on("change", ".allPay", function(e){
				if(!$(this).is(':checked')){
					$('.cPay').prop('checked', false);
				}else{
					$('.cPay').prop('checked', true);
				}
			});

			$(document).on("change", ".aBen", function(e){
				if(!$(this).is(':checked')){
					$('.cBen').prop('checked', false);
					$('.cBen').prop('disabled', true);
				}else{
					$('.cBen').prop('disabled', false);
				}
			});
			$(document).on("change", ".aPro", function(e){
				if(!$(this).is(':checked')){
					$('.cPro').prop('checked', false);
					$('.cPro').prop('disabled', true);
				}else{
					$('.cPro').prop('disabled', false);
				}
			});
			$(document).on("change", ".aSet", function(e){
				if(!$(this).is(':checked')){
					$('.cSet').prop('checked', false);
					$('.cSet').prop('disabled', true);
				}else{
					$('.cSet').prop('disabled', false);
				}
			});
			$(document).on("change", ".aSup", function(e){
				if(!$(this).is(':checked')){
					$('.cSup').prop('checked', false);
					$('.cSup').prop('disabled', true);
				}else{
					$('.cSup').prop('disabled', false);
				}
			});
			/*$(document).on("change", ".aUse", function(e){
				if(!$(this).is(':checked')){
					$('.cUse').prop('checked', false);
					$('.cUse').prop('disabled', true);
				}else{
					$('.cUse').prop('disabled', false);
				}
			});
			$(document).on("change", ".aIex", function(e){
				if(!$(this).is(':checked')){
					$('.cIex').prop('checked', false);
					$('.cIex').prop('disabled', true);
				}else{
					$('.cIex').prop('disabled', false);
				}
			});
			$(document).on("change", ".aRep", function(e){
				if(!$(this).is(':checked')){
					$('.cRep').prop('checked', false);
					$('.cRep').prop('disabled', true);
				}else{
					$('.cRep').prop('disabled', false);
				}
			});
			$(document).on("change", ".aCcp", function(e){
				if(!$(this).is(':checked')){
					$('.cCcp').prop('checked', false);
					$('.cCcp').prop('disabled', true);
				}else{
					$('.cCcp').prop('disabled', false);
				}
			});
			
			$(document).on("change", ".aLes", function(e){
				if(!$(this).is(':checked')){
					$('.cLes').prop('checked', false);
					$('.cLes').prop('disabled', true);
				}else{
					$('.cLes').prop('disabled', false);
				}
			});
			$(document).on("change", ".aTis", function(e){
				if(!$(this).is(':checked')){
					$('.cTis').prop('checked', false);
					$('.cTis').prop('disabled', true);
				}else{
					$('.cTis').prop('disabled', false);
				}
			});
			$(document).on("change", ".aPrs", function(e){
				if(!$(this).is(':checked')){
					$('.cPrs').prop('checked', false);
					$('.cPrs').prop('disabled', true);
				}else{
					$('.cPrs').prop('disabled', false);
				}
			});
			$(document).on("change", ".asUse", function(e){
				if(!$(this).is(':checked')){
					$('.csUse').prop('checked', false);
					$('.csUse').prop('disabled', true);
				}else{
					$('.csUse').prop('disabled', false);
				}
			});
			$(document).on("change", ".acUse", function(e){
				if(!$(this).is(':checked')){
					$('.ccUse').prop('checked', false);
					$('.ccUse').prop('disabled', true);
				}else{
					$('.ccUse').prop('disabled', false);
				}
			});
			$(document).on("change", ".aeUse", function(e){
				if(!$(this).is(':checked')){
					$('.ecUse').prop('checked', false);
					$('.ecUse').prop('disabled', true);
				}else{
					$('.ecUse').prop('disabled', false);
				}
			});*/
			
			// SUBMIT PERMISSION FORM ---------------------------------------------------------------------------------------
			$(document).on('submit','#permissionForm', function(e){
				e.preventDefault();
				var data = $(this).serialize();
				//data += '&id=' + id;
				//data += '&user_name='+emp_info[user_id];
				//alert(data);
				$.ajax({
					url: "ajax/save_user_permissions.php",
					type: 'POST',
					data: data,
					//dataType: 'json',
					success: function(result){
					//$('#dump').html(result); return false;
						if(result == 'success'){
							$("body").overhang({
								type: "success",
								message: '<i class="fa fa-check"></i>&nbsp;&nbsp;<?=$lng['Permissions updated successfuly']?>',
								duration: 2,
							})
						}else{
							$("body").overhang({
								type: "warn",
								message: '<i class="fa fa-exclamation-triangle"></i>&nbsp;&nbsp;<?=$lng['Error']?> : '+result,
								duration: 4,
							})
						}
					},
					error:function (xhr, ajaxOptions, thrownError){
						$("body").overhang({
							type: "error",
							message: '<i class="fa fa-exclamation-circle"></i>&nbsp;&nbsp;<?=$lng['Sorry but someting went wrong']?> <b><?=$lng['Error']?></b> : '+thrownError,
							duration: 4,
						})
					}
				});
			});
			
			
			// DELETE USER ----------------------------------------------------------------------------------------------------- DELETE USER
			$('.delUser').confirmation({
				container: 'body',
				rootSelector: '.delUser',
				singleton: true,
				animated: 'fade',
				placement: 'left',
				popout: true,
				html: true,
				title: '<?=$lng['Are you sure']?>',
				//btnOkIcon: '',
				//btnCancelIcon: '',
				btnOkLabel: '<?=$lng['Delete']?>',
				btnCancelLabel: '<?=$lng['Cancel']?>',
				onConfirm: function() { 
					$.ajax({
						url: "ajax/delete_user.php",
						data:{id: $(this).data('id'), ref: $(this).data('ref')},
						success: function(result){
							//$('#dump').html(result);
							if(result == 'last'){
								$("body").overhang({
									type: "error",
									message: '<i class="fa fa-exclamation-triangle"></i>&nbsp;&nbsp;You can not delete the last system user',
									duration: 4,
								})
								return false;
							}
							location.reload();
						},
						error:function (xhr, ajaxOptions, thrownError){
							$("body").overhang({
								type: "error",
								message: '<i class="fa fa-exclamation-triangle"></i>&nbsp;&nbsp;<?=$lng['Sorry but someting went wrong']?> <b><?=$lng['Error']?></b> : '+thrownError,
								duration: 4,
							})
						}
					});
				}
			});
			
		
		// SAVE USER check_admin_username ------------------------------------------------------------------------------------ SAVE USER
		$('#saveUser').on('click', function () { 
			var username = $('#username').val();
			$.ajax({
				url: "ajax/check_username_exist.php",
				data: {username: username},
				success:function(result){
					//$('#dump2').html(result);
					if(result == 'exist'){
						$("body").overhang({
							type: "error",
							message: '<i class="fa fa-exclamation-triangle"></i>&nbsp;&nbsp;<?=$lng['This username exist already']?>',
							duration: 4,
						})
					}
					if(result == 'success'){
						//$('#userMess').html('').hide();
						$('#userForm').submit();						
					}
				},
				error:function (xhr, ajaxOptions, thrownError){
					$("body").overhang({
						type: "error",
						message: '<i class="fa fa-exclamation-circle"></i>&nbsp;&nbsp;<?=$lng['Sorry but someting went wrong']?> <b><?=$lng['Error']?></b> : '+thrownError,
						duration: 4,
					})
				}
			});
		});
		
		// SUBMIT USER FORM ------------------------------------------------------------------------------------------- SUBMIT USER FORM
		$(document).on('submit','#userForm', function(e){
			e.preventDefault();
			//if(alien == 1){$("#user_name").val($("#system_user_name").val());}
			if(checkPassword($('#password').val()) != ''){
				//$('#userMess').html('<div class="msg_alert"><b>Weak password !</b><br>'+checkPassword($('#password').val())+'</div>').fadeIn(); return false;
			}
			var err = true;
			if($('user_id').val()==''){err = false};
			if($('#username').val()==''){err = false};
			if($('#password').val()==''){err = false};
			//if($('#system_user_name').val()==''){err = false};
			//if($('#phone').val()==''){err = false};
			if($('#email').val()==''){err = false};
			//var err = true;
			if(err==false){
				$("body").overhang({
					type: "error",
					message: '<i class="fa fa-exclamation-triangle"></i>&nbsp;&nbsp;<?=$lng['Please fill in required fields']?>',
					duration: 4,
				})
				return false
			}
			if($('#password').val().length < 8){
				$("body").overhang({
					type: "error",
					message: '<i class="fa fa-exclamation-triangle"></i>&nbsp;&nbsp;<?=$lng['Password to short min 8 characters']?>',
					duration: 4,
				})
				return false
			};

			var data = new FormData($(this)[0]);
			var file = $('#selectUserImg')[0].files[0];
			if(!file){
				$.ajax({
					url: "ajax/save_user_data.php",
					type: 'POST',
					data: data,
					//async: false,
					cache: false,
					contentType: false,
					processData: false,
					success: function(result){
						//alert(result);
						//getPermissionData(result, false)
						//$('#dump').html(result);
						//$('#permissionForm').submit();
						location.reload();
						//setTimeout(function(){$('#modalUser').modal('toggle')},500);
						//setTimeout(function(){location.reload();},1000);
					},
					error:function (xhr, ajaxOptions, thrownError){
						$("body").overhang({
							type: "error",
							message: '<i class="fa fa-exclamation-circle"></i>&nbsp;&nbsp;<?=$lng['Sorry but someting went wrong']?> <b><?=$lng['Error']?></b> : '+thrownError,
							duration: 4,
						})
					}
				});
				return false;
			}
			var reader = new FileReader();
			reader.readAsDataURL(file);
			reader.onload = function (e) {
				var img = new Image;
				img.src = e.target.result;
				img.onload = function() {
				$uploadCrop.croppie('result', {
					type: 'canvas',
					size: 'viewport'
				}).then(function (resp) {
					data.append('img_data', resp); 
					$.ajax({
						url: ROOT+"settings/ajax/save_user_data.php",
						type: 'POST',
						data: data,
						async: false,
						cache: false,
						contentType: false,
						processData: false,
						success: function(result){
							//alert(result);
							//$('#dump').html(result);
							location.reload();
							//setTimeout(function(){$('#modalUser').modal('toggle')},500);
							//setTimeout(function(){location.reload();},1000);
						},
						error:function (xhr, ajaxOptions, thrownError){
							alert(thrownError);
						}
					});
				});
				};
			}
		});
		
		// ADD USER ----------------------------------------------------------------------------------------------------------- ADD USER
		$(document).on("click", "#add_user", function(){
			$("#modalUser").modal('toggle');
		})
		$('#modalUser').on('hidden.bs.modal', function () {
			$(this).find('form').trigger('reset');
			$('#upload-demo').css('background-image', 'url(../images/profile_image.jpg');
		});

		$('.main').fadeIn(200);
	
	})
		
	</script>






















