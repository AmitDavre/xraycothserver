<?php
	
	unset($_SESSION['rego']['empID']);
	$fix_allow = getFixAllowances();
	$fix_deduct = getUsedFixDeduct($lang);

	$personal = array();
		$personal['emp_id'] = $lng['Employee ID'];
		$personal['sid'] = $lng['Scan ID'];
		$personal['title'] =  $lng['Title'];
		$personal['firstname'] = $lng['First name'];
		$personal['lastname'] = $lng['Last name'];
		$personal['en_name'] = $lng['Name in English'];
		$personal['birthdate'] = $lng['Birthdate'];
		$personal['nationality'] = $lng['Nationality'];
		$personal['gender'] = $lng['Gender'];
		$personal['maritial'] = $lng['Maritial status'];
		$personal['religion'] = $lng['Religion'];
		$personal['military_status'] = $lng['Military status'];
		$personal['height'] = $lng['Height'];
		$personal['weight'] = $lng['Weight'];
		$personal['bloodtype'] = $lng['Blood type'];
		$personal['drvlicense_nr'] = $lng['Driving license No.'];
		$personal['drvlicense_exp'] = $lng['License expiry date'];
		$personal['idcard_nr'] = $lng['ID card'];
		$personal['idcard_exp'] = $lng['ID card expiry date'];
		$personal['tax_id'] = $lng['Tax ID no.'];
		
	$contact = array();	
		$contact['reg_address'] = $lng['Registered address'];
		$contact['sub_district'] = $lng['Sub district'];
		$contact['district'] = $lng['District'];
		$contact['province'] = $lng['Province'];
		$contact['postnr'] = $lng['Postal code'];
		$contact['country'] = $lng['Country'];
		$contact['cur_address'] = $lng['Current address'];
		$contact['personal_phone'] = $lng['Personal phone'];
		$contact['personal_email'] = $lng['Personal email'];
		$contact['work_phone'] = $lng['Work phone'];
		$contact['work_email'] = $lng['Work email'];
	
	$workdata = array();
		$workdata['joining_date'] = $lng['Joining date'];
		$workdata['probation_date'] = $lng['Probation due date'];
		$workdata['team'] = 'Team';//$lng['Team'];
		$workdata['emp_group'] = $lng['Employee group'];
		$workdata['emp_type'] = $lng['Employee type'];
		$workdata['resign_date'] = $lng['Resign date'];
		$workdata['resign_reason'] = $lng['Resign reason'];
		$workdata['emp_status'] = $lng['Employee status'];
		$workdata['account_code'] = $lng['Accounting code'];
		$workdata['position'] = $lng['Position'];
		$workdata['head_branch'] = $lng['Head of branch'];
		$workdata['head_department'] = $lng['Head of department'];
		$workdata['head_division'] = $lng['Head of division'];
		$workdata['team_supervisor'] = $lng['Team supervisor'];
		$workdata['date_position'] = $lng['Date start Position'];
		$workdata['shift_team'] = $lng['Shiftteam'];
		$workdata['time_reg'] = $lng['Time registration'];
		$workdata['selfie'] = $lng['Take selfie'];
		$workdata['annual_leave'] = $lng['Annual leave'];

	$financial = array();
		$financial['bank_code'] = $lng['Bank code'];
		$financial['bank_branch'] = $lng['Bank branch'];
		$financial['bank_account'] = $lng['Bank account no.'];
		$financial['bank_account_name'] = $lng['Bank account name'];
		//$financial['bank_transfer'] = $lng['Automatic bank tranfer'];
		$financial['pay_type'] = $lng['Payment type'];
		$financial['calc_psf'] = $lng['Pension fund'];
		$financial['psf_rate_emp'] = $lng['PSF rate employee'];
		$financial['psf_rate_com'] = $lng['PSF rate employer'];
		$financial['psf_prev_years_emp'] = $lng['PSF previous years employee'];
		$financial['psf_prev_years_com'] = $lng['PSF previous years employer'];
		$financial['calc_pvf'] = $lng['Provident fund'];
		$financial['pvf_rate_emp'] = $lng['PVF rate employee'];
		$financial['pvf_rate_com'] = $lng['PVF rate employer'];
		$financial['pvf_prev_years_emp'] = $lng['PVF previous years employee'];
		$financial['psf_prev_years_com'] = $lng['PVF previous years employer'];
		$financial['calc_method'] = $lng['Tax calculation method'];
		$financial['calc_tax'] = $lng['Calculate Tax'];
		$financial['modify_tax'] = $lng['Modify Tax amount'];
		$financial['calc_sso'] = $lng['Calculate SSO'];
		$financial['sso_by'] = $lng['SSO paid by'];
		
	$benefits = array();
		$benefits['contract_type'] = $lng['Contract type'];
		$benefits['calc_base'] = $lng['Calculation base'];
		$benefits['base_salary'] = $lng['Basic salary'];
		$benefits['base_ot_rate'] = $lng['Base OT rate'];
		$benefits['ot_rate'] = $lng['OT rate'];
		$benefits['gov_house_banking'] = $lng['Government house banking'];
		$benefits['savings'] = $lng['Savings'];
		$benefits['legal_execution'] = $lng['Legal execution deduction'];
		$benefits['kor_yor_sor'] = $lng['Kor.Yor.Sor (Student loan)'];
		foreach($fix_allow as $k=>$v){
			$benefits['fix_allow_'.$k] = $v[$lang].' ('.$lng['Fix allow'].')';
		}
		foreach($fix_deduct as $k=>$v){
			$benefits['fix_deduct_'.$k] = $v.' ('.$lng['Fix deduct'].')';
		}

	$taxdata = array();
		$taxdata['tax_spouse'] = $lng['Spouse care'];
		$taxdata['tax_parents'] = $lng['Parents care'];
		$taxdata['tax_parents_inlaw'] = $lng['Parents in law care'];
		$taxdata['tax_child_bio'] = $lng['Child care - biological'];
		$taxdata['tax_child_bio_2018'] = $lng['Child care - biological 2018/19/20'];
		$taxdata['tax_child_adopted'] = $lng['Child care - adopted'];
		$taxdata['tax_allow_child_birth'] = $lng['Child birth (Baby bonus)'];
		$taxdata['tax_disabled_person'] = $lng['Care disabled person'];
		$taxdata['tax_allow_home_loan_interest'] = $lng['Home loan interest'];	
		$taxdata['tax_allow_first_home'] = $lng['First home buyer'];
		$taxdata['tax_allow_donation_charity'] = $lng['Donation charity'];
		$taxdata['tax_allow_donation_education'] = $lng['Donation education'];	
		$taxdata['tax_allow_donation_flood'] = $lng['Donation flooding'];	
		$taxdata['tax_allow_own_health'] = $lng['Own health insurance'];	
		$taxdata['tax_allow_health_parents'] = $lng['Health insurance parents'];	
		$taxdata['tax_allow_own_life_insurance'] = $lng['Own life insurance'];	
		$taxdata['tax_allow_life_insurance_spouse'] = $lng['Life insurance spouse'];
		$taxdata['tax_allow_pension_fund'] = $lng['Pension fund'];	
		$taxdata['tax_allow_nsf'] = $lng['NSF'];	
		$taxdata['tax_allow_rmf'] = $lng['RMF'];	
		$taxdata['tax_allow_ltf'] = $lng['LTF'];	
		$taxdata['tax_exemp_disabled_under'] = $lng['Exemption disabled person <65 yrs'];
		$taxdata['tax_exemp_payer_older'] = $lng['Exemption tax payer => 65yrs'];	
		$taxdata['tax_allow_domestic_tour'] = $lng['Domestic tour'];
		$taxdata['tax_allow_year_end_shopping'] = $lng['Year-end shopping'];	
		$taxdata['tax_allow_other'] = $lng['Other allowance'];	

	/*$end_contract = array();
		$end_contract['notice_date'] = $lng['Notice date'];
		$end_contract['resign_reason'] = $lng['Resign reason'];
		$end_contract['resign_date'] = $lng['Resign date'];
		$end_contract['other_income'] = $lng['Other income'];
		$end_contract['severance'] = $lng['Severance'];
		$end_contract['remaining_salary'] = $lng['Remaining salary'];
		$end_contract['notice_payment'] = $lng['Notice payment'];
		$end_contract['paid_leave'] = $lng['Paid leave'];
		$end_contract['remarks'] = $lng['Remarks'];*/
	
	$export_fields = unserialize($sys_settings['emp_export_fields']);
	//var_dump($sys_settings); exit;
	
	$fix_allow = getFixAllowances($pr_settings);
	//var_dump($fix_allow);
	$sCols = getEmployeeColumns();
	//var_dump($sCols);
	//$emp_status[7] = $lng['in-Complete'];
	$empStatusCount = getEmployeeStatusTeam($cid,$_SESSION['rego']['sel_teams']);
	//var_dump($empStatusCount); exit;
	foreach($emp_status as $k=>$v){
		$emp_status[$k] = $v.' ('.$empStatusCount[$k].')';
	}
	//var_dump($emp_status);
	// echo '<pre>';
	// print_r($_SESSION);
	// echo '</pre>';
	// die();
?>
	<style>
		.customLength {
			background:red;
			width:50px;
		}

		#imphelp {
	    	position: fixed;
	    	width: 900px;
	    	right: auto;
	    	top: 118px;
		    bottom: auto;
		    background: #fff;
		    z-index: 9999;
		    box-shadow: 0 0 5px rgb(0 0 0 / 20%) !important;
		    padding: 10px 10px 10px 0px;
		}
	</style>
	
	<h2><i class="fa fa-users fa-mr"></i> <?=$lng['Employee register']?></h2>
	
	<div class="main employee-list">
		
		<div style="padding:0 0 0 20px" id="dump"></div>

		<form id="import" name="import" enctype="multipart/form-data" style="visibility:hidden; height:0; margin:0; padding:0">
			<input style="visibility:hidden" id="import_employees" type="file" name="file" />
		</form>
		<div id="showTable" style="display:none; overflow:hidden">
			
			<table style="width:100%; margin-bottom:8px">
				<tr>
					<td>
						<div class="searchFilter" style="margin:0">
							<input placeholder="<?=$lng['Filter']?>" id="searchFilter" class="sFilter" type="text" />
							<button id="clearSearchbox" type="button" class="clearFilter btn btn-default btn-sm"><i class="fa fa-times"></i></button>
						</div>
					</td>
					<td style="padding-left:5px">
						<select id="statFilter" class="button" style="margin:0">
							<option selected value=""><?=$lng['All employees']?></option>
							<? foreach($emp_status as $k=>$v){
									echo '<option';
									if($k == 1){echo ' selected';}
									echo ' value="'.$k.'">'.$v.'</option>';
								} ?>
						</select>
					</td>
					<td style="padding-left:5px">
						<select id="pageLength" class="button">
							<option selected value=""><?=$lng['Rows / page']?></option>
							<option value="10">10 <?=$lng['Rows / page']?></option>
							<option value="15">15 <?=$lng['Rows / page']?></option>
							<option value="20">20 <?=$lng['Rows / page']?></option>
							<option value="30">30 <?=$lng['Rows / page']?></option>
							<option value="40">40 <?=$lng['Rows / page']?></option>
							<option value="50">50 <?=$lng['Rows / page']?></option>
						</select>
					</td>
					<td style="width:80%"></td>
						
					<td style="padding-left:5px">
						<button <? if(!$_SESSION['rego']['employee']['add']){echo 'disabled';}?> id="addEmployee" type="button" class="btn btn-primary"><i class="fa fa-plus fa-mr"></i> <?=$lng['Add employee']?></button>
					</td>
					<td style="padding-left:5px">
						<button <? if(!$_SESSION['rego']['employee']['import']){echo 'disabled';}?> id="impemp" onclick="$('#import_employees').click()" type="button" class="btn btn-primary"><i class="fa fa-download"></i>&nbsp; <?=$lng['Import employees']?></button>	
					</td>
					<td style="padding-left:5px">
						<button <? if(!$_SESSION['rego']['employee']['export']){echo 'disabled';}?> id="exportEmployees" type="button" class="btn btn-primary"><i class="fa fa-upload"></i>&nbsp; <?=$lng['Export employees']?></button>
					</td>
			<!-- 		<td style="padding-left:5px">
						<button id="updateEmployee" type="button" class="btn btn-primary"><i class="fa fa-refresh fa-mr"></i> <?=$lng['Batch Update Annual Leave Balance']?></button>
					</td> -->
				</tr>
			</table>

			<table id="datatable" class="dataTable nowrap" style="width:100%">
				<thead>
				<tr>
					<th class="tac" style="min-width:50px"><?=$lng['ID']?></th>
					<th data-sortable="false" style="width:1px; text-align:center !important"><i class="fa fa-user fa-lg"></i></th>
					<th style="width:90%"><?=$lng['Name']?></th>
					<th data-sortable="false"><?=$lng['Entity']?></th>
					<th data-sortable="false"><?=$lng['Branch']?></th>
					<th data-sortable="false"><?=$lng['Division']?></th>
					<th data-sortable="false"><?=$lng['Department']?></th>
					<th data-sortable="false"><?=$lng['Team']?></th>
					<th data-sortable="false"><?=$lng['Position']?></th>
					<th data-sortable="false"><?=$lng['Joining date']?></th>
					<th data-sortable="false"><?=$lng['Personal phone']?></th>
					<th data-sortable="false"><?=$lng['Personal email']?></th>
					<!--<th data-sortable="false"><?=$lng['Payroll status']?></th>-->
					<th data-sortable="false"><?=$lng['Status']?></th>
				</tr>
				</thead>
				<tbody>
				
				</tbody>
			</table>
		</div>
		
	</div>
	
	<!-- Modal modalExportFields -->
	<div class="modal fade" id="modalExportFields" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title"><i class="fa fa-list-ul"></i>&nbsp; <?=$lng['Select fields']?></h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<!-- <span aria-hidden="true">&times;</span> -->
					</button>
					<button type="button" class="btn btn-fr" style="color: blue;" data-toggle="collapse" data-target="#imphelp">
						<i class="fa fa-question-circle fa-lg" style="font-size: 22px;"></i>
					</button>
				</div>
				<div class="modal-body modal-tabs" style="padding:10px 0">

					<div class="collapse" id="imphelp">
						<div class="row mb-2">
							<div class="col-md-1">
								<i data-toggle="collapse" data-target="#imphelp" class="fa fa-arrow-circle-right fa-lg" style="color: blue;font-size: 22px;cursor: pointer;float: right;"></i>
							</div>
					
							<div class="col-md-11" style="overflow-y: auto;">
						    	<?=getHelpfile(103)?>
							</div>
						</div>
					</div>
					
					<form id="exportForm">
						<ul class="nav nav-tabs">
							<li class="nav-item"><a class="nav-link active" href="#tab_personal" data-toggle="tab"><?=$lng['Personal data']?></a></li>
							<li class="nav-item"><a class="nav-link" href="#tab_contact" data-toggle="tab"><?=$lng['Contact']?></a></li>
							<li class="nav-item"><a class="nav-link" href="#tab_work" data-toggle="tab"><?=$lng['Work data']?></a></li>
							<li class="nav-item"><a class="nav-link" href="#tab_financial" data-toggle="tab"><?=$lng['Financial']?></a></li>
							<li class="nav-item"><a class="nav-link" href="#tab_benifits" data-toggle="tab"><?=$lng['Benefits']?></a></li>
							<li class="nav-item"><a class="nav-link" href="#tab_taxdata" data-toggle="tab"><?=$lng['Tax']?></a></li>
							<!--<li class="nav-item"><a class="nav-link" href="#tab_contract" data-toggle="tab"><?=$lng['End contract']?></a></li>-->
							<select name="field[empType]" style="height:30px; padding:0 8px; position:absolute; right:20px">
								<option selected value="all"><?=$lng['All employees']?></option>
								<? foreach($emp_status as $k=>$v){
										echo '<option';
										//if($k == 1){echo ' selected';}
										echo ' value="'.$k.'">'.$v.'</option>';
									} ?>
							</select>
							
						</ul>
						
						<div class="tab-content xtab-flex" style="padding:15px 20px 10px">
							
							<div class="tab-pane show active" id="tab_personal">
								<? foreach($personal as $k=>$v){ ?>
									<div class="sel-field">
									<? if($k == 'emp_id'){ ?>
										<label><input disabled checked type="checkbox" name="field[<?=$k?>]" value="<?=$v?>" class="per checkbox style-0"><span> <?=$v?></span></label>
										<input type="hidden" name="field[<?=$k?>]" value="<?=$v?>" />
									<? }else{ ?>
										<label><input <? if(isset($export_fields[$k])){ echo 'checked';} ?> type="checkbox" name="field[<?=$k?>]" value="<?=$v?>" class="per checkbox style-0"><span> <?=$v?></span></label>
									<? } ?>
									</div>
								<? } ?>
									<div class="sel-field" style="clear:both">
										<label><input type="checkbox" data-id="per" class="exCheckall checkbox"><b><span style="color:#b00"> <?=$lng['Check all']?></span></b></label>
									</div>
							</div>
							
							<div class="tab-pane" id="tab_contact">
								<? foreach($contact as $k=>$v){ ?>
									<div class="sel-field">
										<label><input <? if(isset($export_fields[$k])){ echo 'checked';} ?> type="checkbox" name="field[<?=$k?>]" value="<?=$v?>" class="con checkbox style-0"><span> <?=$v?></span></label>
									</div>
								<? } ?>
								<div class="sel-field" style="clear:both">
									<label><input type="checkbox" data-id="con" class="exCheckall checkbox"><b><span style="color:#b00"> <?=$lng['Check all']?></span></b></label>
								</div>
							</div>
							
							<div class="tab-pane" id="tab_work">
								<? foreach($workdata as $k=>$v){ ?>
									<div class="sel-field">
										<label><input <? if(isset($export_fields[$k])){ echo 'checked';} ?> type="checkbox" name="field[<?=$k?>]" value="<?=$v?>" class="wor checkbox style-0"><span> <?=$v?></span></label>
									</div>
								<? } ?>
								<div class="sel-field" style="clear:both">
									<label><input type="checkbox" data-id="wor" class="exCheckall checkbox"><b><span style="color:#b00"> <?=$lng['Check all']?></span></b></label>
								</div>
							</div>

							<div class="tab-pane" id="tab_financial">
								<? foreach($financial as $k=>$v){ ?>
									<div class="sel-field">
										<label><input <? if(isset($export_fields[$k])){ echo 'checked';} ?> type="checkbox" name="field[<?=$k?>]" value="<?=$v?>" class="fin checkbox style-0"><span> <?=$v?></span></label>
									</div>
								<? } ?>
								<div class="sel-field" style="clear:both">
									<label><input type="checkbox" data-id="fin" class="exCheckall checkbox"><b><span style="color:#b00"> <?=$lng['Check all']?></span></b></label>
								</div>
							</div>

							<div class="tab-pane" id="tab_benifits">
								<? foreach($benefits as $k=>$v){ ?>
									<div class="sel-field">
										<label><input <? if(isset($export_fields[$k])){ echo 'checked';} ?> type="checkbox" name="field[<?=$k?>]" value="<?=$v?>" class="ben checkbox style-0"><span> <?=$v?></span></label>
									</div>
								<? } ?>
								<div class="sel-field" style="clear:both">
									<label><input type="checkbox" data-id="ben" class="exCheckall checkbox"><b><span style="color:#b00"> <?=$lng['Check all']?></span></b></label>
								</div>
							</div>
							
							<div class="tab-pane" id="tab_taxdata">
								<? foreach($taxdata as $k=>$v){ ?>
									<div class="sel-field">
										<label><input <? if(isset($export_fields[$k])){ echo 'checked';} ?> type="checkbox" name="field[<?=$k?>]" value="<?=$v?>" class="tax checkbox style-0"><span> <?=$v?></span></label>
									</div>
								<? } ?>
								<div class="sel-field" style="clear:both">
									<label><input type="checkbox" data-id="tax" class="exCheckall checkbox"><b><span style="color:#b00"> <?=$lng['Check all']?></span></b></label>
								</div>
							</div>
							
							<!--<div class="tab-pane" id="tab_contract">
								<? foreach($end_contract as $k=>$v){ ?>
									<div class="sel-field">
										<label><input <? if(isset($export_fields[$k])){ echo 'checked';} ?> type="checkbox" name="field[<?=$k?>]" value="<?=$v?>" class="end checkbox style-0"><span> <?=$v?></span></label>
									</div>
								<? } ?>
								<div class="sel-field" style="clear:both">
									<label><input type="checkbox" data-id="end" class="exCheckall checkbox"><b><span style="color:#b00"> <?=$lng['Check all']?></span></b></label>
								</div>
							</div>-->
							
						</div>
						
						<div style="padding:0 20px 10px 20px;">
							<button type="submit" onclick="$('#action').val('empty');" class="btn btn-primary btn-fl"><i class="fa fa-upload"></i>&nbsp; <?=$lng['Export empty file']?></button>
							<button type="submit" onclick="$('#action').val('register');" class="btn btn-primary btn-fl"><i class="fa fa-upload"></i>&nbsp; <?=$lng['Export employee register']?></button>
							<button type="button" data-dismiss="modal" class="btn btn-primary btn-fr"><i class="fa fa-times"></i>&nbsp; <?=$lng['Cancel']?></button>
							<div style="clear:both"></div>
						</div>
						<input type="hidden" id="action" value="empty" />
					</form>
				</div>
			</div>
		</div>
	</div>	
	
	<div class="openHelp"><i class="fa fa-question-circle fa-lg"></i></div>
	<div id="help">
		<div class="closeHelp"><i class="fa fa-arrow-circle-right"></i></div>
		<div class="innerHelp">
			<? //include('../docs/employee_register_'.$lang.'.htm');?>
			<?=$helpfile?>
		</div>
	</div>

	<script type="text/javascript">
		
		var height = window.innerHeight-303;
		var headerCount = 1;
		//var scrY = heights;//true;
		
		$(document).ready(function() {
			var cid = <?=json_encode($_SESSION['rego']['cid'])?>;
			var year = <?=json_encode($_SESSION['rego']['cur_year'])?>;
			var month = <?=json_encode(sprintf('%02d', $_SESSION['rego']['cur_month']))?>;
			var dbname = <?=json_encode($_SESSION['rego']['cid'].'_employees')?>;
			var sCols = <?=json_encode($sCols)?>;
			var rows = Math.floor(height/29.64);

			//$('#modalExportFields').modal('toggle');
			
			$(document).on('click', '#addEmployee', function(e) {
				var id = 0;
				$.ajax({
					url: "ajax/session_employee_id.php",
					data: {id: id},
					success: function(result){
						//$('#dump').html(result); return false;
						window.location.href="index.php?mn=1021";
					}
				});
			})
			
			$(document).on('click', '.hover-bold', function(e) {
				var id = $(this).closest('tr').find('td:eq(0)').text();
				$.ajax({
					url: "ajax/session_employee_id.php",
					data: {id: id},
					success: function(result){
						//$('#dump').html(result); return false;
						window.location.href="index.php?mn=1021";
					}
				});
			})
			
			$('.exCheckall').on('click', function(){
				var id = $(this).data('id');
				$('#exportForm input.'+id).not(':disabled').prop('checked', $(this).is(':checked'));
			});
			
			var dtable = $('#datatable').DataTable({
				scrollY:        false,
				scrollX:        true,
				scrollCollapse: false,
				fixedColumns:   false,
				lengthChange:  false,
				searching: true,
				ordering: true,
				order: [0, 'asc'],
				paging: true,
				pagingType: 'full_numbers',
				pageLength: rows,
				filter: true,
				info: true,
				<?=$dtable_lang?>
				processing: false,
				serverSide: true,
				//autoWidth: true,
				ajax: {
					url: "ajax/server_get_employees.php",
					type: 'POST',
					"data": function(d){
						d.status = $('#statFilter').val();
					}
				},
				columnDefs: [
					{"targets": [1], "class": 'pad1' },
					//{"targets": [0,2], "class": 'hover-bold' },
					//{"targets": [3], "width": '80%' },
					//{"targets": sCols, "visible": true },
				],
				createdRow: function (row, data, dataIndex) {
			         row.children[0].classList.add("hover-bold");
			         row.children[2].classList.add("hover-bold");
	     		},
				initComplete : function( settings, json ) {
					$('#showTable').fadeIn(200);
					dtable.columns.adjust().draw();
				}
			});
			setTimeout(function(){
				$("#statFilter").trigger('change');
			},50);
			$("#searchFilter").keyup(function() {
				var s = $(this).val();
				dtable.search(s).draw();
			});
			$(document).on("click", "#clearSearchbox", function(e) {
				$('#searchFilter').val('');
				dtable.search('').draw();
			})
			$(document).on("change", "#statFilter", function(e) {
				dtable.ajax.reload(null, false);
			})
			$(document).on("change", "#pageLength", function(e) {
				if(this.value > 0){
					dtable.page.len( this.value ).draw();
				}
			})

			
			
			
			
			$(document).on("change", "#import_employees", function(e){
				e.preventDefault();
				var id = cid;//.replace('x',acc);
				var ff = $(this).val().toLowerCase();
				ff = ff.replace(/.*[\/\\]/, '');
				var ext =  ff.split('.').pop();
				f = ff.substr(0, ff.lastIndexOf('.'));
				var r = f.split('_');
				//alert(ff+'-'+r+'-'+ext)
				if(!(ext == 'xls' || ext == 'xlsx')){
					$("body").overhang({
						type: "warn",
						message: '<i class="fa fa-exclamation-triangle"></i>&nbsp;&nbsp;<?=$lng['Please use Excel files only .xls or .xlsx']?>',
						duration: 8,
						closeConfirm: true
					})
					return false;
				}
				if(r.length !== 2){
					$("body").overhang({
						type: "warn",
						message: '<i class="fa fa-exclamation-triangle"></i>&nbsp;&nbsp;<?=$lng['Wrong file format ! Please use']?> [ '+id+'_employees.xls ]',
						duration: 8,
						closeConfirm: true
					})
					return false;
				}else{
					var s1 = r[0]; // cid
					var s2 = r[1]; // Filename
					if(s1 !== id){
						$("body").overhang({
							type: "warn",
							message: '<i class="fa fa-exclamation-triangle"></i>&nbsp;&nbsp;<?=$lng['File ID does not match selected client']?> ('+s1+') ! <?=$lng['Please use']?> [ '+id+'_employees.xls ]',
							duration: 8,
							closeConfirm: true
						})
						return false;
					}
				}
				$("form#import").submit();
			});
			
			$(document).on("submit", "form#import", function(e){
				e.preventDefault();
				$("body").overhang({
					type: "warn",
					message: '<i class="fa fa-exclamation-triangle"></i>&nbsp;&nbsp;<?=$lng['One moment please importing employees']?>&nbsp;&nbsp;<i class="fa fa-refresh fa-spin"></i>',
					closeConfirm: "true",
					//duration: 10,
				})
				$('#impemp i').removeClass('fa-download').addClass('fa-refresh fa-spin');
				//return false;
				var file = $("#import_employees")[0].files[0];
				var data = new FormData($(this)[0]);
				data.append('dbname', dbname);
				data.append('cid', cid);
				setTimeout(function(){
					$.ajax({
						url: "ajax/import_employees.php",
						type: 'POST',
						data: data,
						async: false,
						cache: false,
						contentType: false,
						processData: false,
						success: function(result){
							//$("#dump").html(result); return false;
							//alert(result)
							$('#import_employees').val('');
							setTimeout(function(){
								$(".overhang").slideUp(200); 
								$('#impemp i').removeClass('fa-refresh fa-spin').addClass('fa-download');
							}, 800);
							setTimeout(function(){
								if($.trim(result) == 'success'){
									$("body").overhang({
										type: "success",
										message: '<i class="fa fa-check"></i>&nbsp;&nbsp;<?=$lng['Data imported successfuly. Please wait for page reload']?> . . .',
										duration: 1,
									})
									setTimeout(function(){location.reload();}, 1000);
								}else{
									$("body").overhang({
										type: "warn",
										message: '<i class="fa fa-exclamation-triangle"></i>&nbsp;&nbsp;'+result,
										closeConfirm: "true",
										duration: 5,
									})
								}
							}, 1000);
						},
						error:function (xhr, ajaxOptions, thrownError){
							$("body").overhang({
								type: "error",
								message: '<i class="fa fa-exclamation-triangle"></i>&nbsp;&nbsp;<?=$lng['Sorry but someting went wrong']?> <b><?=$lng['Error']?></b> : '+thrownError,
								duration: 8,
								closeConfirm: "true",
							})
							$('#impemp i').removeClass('fa-refresh fa-spin').addClass('fa-download');
						}
					});
				},300);
			});
			
			$(document).on("click", "#exportEmployees", function(e){
				$("#modalExportFields").modal('toggle');
			})
			$('#exportForm').on("submit", function(e) {
				e.preventDefault();
				var data = $(this).serialize();
				$.ajax({
					url: "ajax/update_employee_export_fields.php",
					data: data,
					type: 'POST',
					success: function(result){
						//$('#dump').html(result); return false;
						window.location.href = 'ajax/export_employee_register_excel.php?'+$('#action').val();
						$("#modalExportFields").modal('toggle');
					},
					error:function (xhr, ajaxOptions, thrownError){
						$("body").overhang({
							type: "error",
							message: '<i class="fa fa-exclamation-triangle"></i>&nbsp;&nbsp;<?=$lng['Sorry but someting went wrong']?> <b><?=$lng['Error']?></b> : '+thrownError,
							duration: 4,
						})
					}
				});
			})

			$(document).on('click', '#updateEmployee', function(e) {

				$.ajax({
					url: "ajax/update_leave_balance_batch_employees.php",
					success: function(result){

						$("body").overhang({
							type: "success",
							message: '<i class="fa fa-check"></i>&nbsp;&nbsp;<?=$lng['Batch Update Success. Please wait for page reload']?> . . .',
							duration: 1,
						})
						setTimeout(function(){location.reload();}, 1000);

					}
				});
			})

			
		})
	
	</script>























