<?
	function get_column_names($con, $table) {
	  $sql = 'DESCRIBE '.$table;
	  $result = mysqli_query($con, $sql);
	
	  $rows = array();
	  while($row = mysqli_fetch_assoc($result)) {
		 $rows[$row['Field']] = '';
	  }
	
	  return $rows;
	}
	
	$emps = getEmployees($cid,0);
	$emp_id = key($emps);
	//var_dump($emps);
	
	$fix_allow = array();
	$fix = getFixAllowNames();
	//var_dump($fix);
	
	$fix_deduct = array();
	$dfix = getFixDeductNames();
	//var_dump($dfix);
	
	$emp_array = '[';
	foreach($emps as $k=>$v){
		$emp_array .= "{data:'".$k."',value:'".$k.' - '.$v[$lang.'_name']."'},";
	}
	$emp_array = substr($emp_array,0,-1);
	$emp_array .= ']';
	
	
	//var_dump($_SESSION['rego']['report_id']);
	
	$employee = '';
	if(isset($_SESSION['rego']['report_id'])){
		$employee = $_SESSION['rego']['report_id'].' - '.$emps[$_SESSION['rego']['report_id']][$lang.'_name'];
	}
	
	$data = array();
	$total_deductions = 0;
	$id = false;
	if(isset($_SESSION['rego']['report_id'])){
		$id = true;
		$sql = "SELECT * FROM ".$cid."_employees WHERE emp_id = '".$_SESSION['rego']['report_id']."'";
		if($res = $dbc->query($sql)){
			if($row = $res->fetch_assoc()){
				$data = $row;
				$total_deductions = $data['emp_tax_deductions'] + $data['tax_standard_deduction'] + $data['tax_personal_allowance'] + $data['tax_allow_pvf'] + $data['tax_allow_sso'];
			}
		}
		$data['emergency_contacts'] = unserialize($data['emergency_contacts']);
		//if(empty($data['emergency_contacts'][1]['name'])){$data['emergency_contacts'] = array();}
		if(empty($data['image'])){$data['image'] = 'images/profile_image.jpg';}
		for($i=1;$i<=10;$i++){
			if($data['fix_allow_'.$i] > 0){
					$fix_allow[$fix[$i]] = $data['fix_allow_'.$i];
			}
		}
		for($i=1;$i<=5;$i++){
			if($data['fix_deduct_'.$i] > 0){
					$fix_deduct[$dfix[$i]] = $data['fix_deduct_'.$i];
			}
		}
	}else{
		$data['emergency_contacts'] = array();
		$data['image'] = 'images/profile_image.jpg';
	}
	$gender[''] = '';
	$maritial[''] = '';
	$religion[''] = '';
	$military_status[''] = '';
	
	//var_dump($positions);
	//var_dump($data['emergency_contacts']); //exit;
?>	
<style>
.A4form {
	width:100%;
	xmargin:10px 10px 10px 15px;
	background:#fff; 
	padding:20px 30px 20px 30px; 
	box-shadow:0 0 10px rgba(0,0,0,0.4); 
	position:relative;
	min-height:500px;
}
table.reportTable {
	width:100%;
	border-collapse:collapse;
	font-size:13px;
	margin-bottom:10px;
}
table.reportTable b {
	font-weight:600;
	color:#039;
}
table.reportTable thead th {
	padding:4px 8px;
	font-weight:600;
	text-align:center;
	font-size:13px;
}
table.reportTable thead th.head {
	padding:4px 8px;
	font-weight:600;
	text-align:center;
	font-size:16px;
}
table.reportTable tbody th {
	padding:2px 8px;
	white-space:nowrap;
	font-weight:600;
	border:1px solid #eee;
	border-left:0;
}
table.reportTable tbody td {
	padding:2px 8px;
	border:1px solid #eee;
	white-space:nowrap;
}
table.reportTable tbody td.bold {
	font-weight:600;
}
table.reportTable tbody td:first-child {
	border-left:0;
}
table.reportTable tbody td:last-child {
	border-right:0;
}
table.reportTable tbody th.H1 {
	background:#eee;
	color:#900;
	padding:2px 8px;
	font-weight:600;
	border:1px solid #fff;
	border-bottom:1px solid #bbb;
}
table.reportTable tbody th.H1:last-child {
	xborder-right:0;
}


	xtable.reportTable tbody tr {
		border-bottom:1px solid #eee;
	}
	xtable.reportTable tbody th, 
	xtable.reportTable tbody td {
		padding:3px 8px;
	}
	table.reportTable tbody th {
		text-align:right;
	}
</style>

	<h2><i class="fa fa-file-pdf-o"></i>&nbsp; <?=$lng['Employee record']?></h2>		
	<div class="main" style="padding-top:15px; top:130px">
		<div style="padding:0 0 0 20px" id="dump"></div>
			
		<div style="width:300px; position:fixed">
			<div class="searchFilter" style="width:300px; margin-bottom:5px">
				<input style="width:100%; font-size:13px; line-height:27px" class="sFilter" placeholder="<?=$lng['Employee']?> ... <?=$lng['Type for hints']?> ..." type="text" id="selectEmployee" value="<?=$employee?>" />
			</div>
			<button <? if(!$id){echo 'disabled';}?> onClick="window.open('print/print_employee_record.php','_blank')" style="margin:0 0 5px; width:100%; text-align:left" type="button" class="btn btn-primary"><i class="fa fa-file-pdf-o"></i>&nbsp; <?=$lng['Print']?></button>
		
		</div>
		
		<div style="padding:0 0 10px 20px; margin-left:300px">
		
			<div class="A4form" style="width:900px; padding:30px 30px 30px; overflow-y:auto">
				<table class="reportTable" border="0" style="margin:0">
					<thead>
						<tr>
							<th style="padding-left:0" class="head tal">
								<?=$lng['Employee']?> <? if($id){ echo $data['emp_id'].' '.$title[$data['title']].' '.$data[$lang.'_name'];}?>
							</th>
						</tr>
					</thead>
				</table>
				
				<table class="reportTable" border="0">
					<tbody>
						<tr>
							<th class="H1 tal" colspan="3"><?=$lng['Personal information']?></th>
						</tr>
						<tr>
							<td><b><?=$lng['First name']?> :</b> <? if($id){echo $data['firstname'];}?></td>
							<td><b><?=$lng['Last name']?> :</b> <? if($id){echo $data['lastname'];}?></td>
							<td rowspan="8" style="width:1px"><img height="165px" src="<?=ROOT.$data['image']?>" /></td>
						</tr>
						<tr>
							<td><b><?=$lng['Name in English']?> :</b> <? if($id){echo $data['en_name'];}?></td>
							<td><b><?=$lng['Nationality']?> :</b> <? if($id){echo $data['nationality'];}?></td>
						</tr>
						<tr>
							<td><b><?=$lng['Birthdate']?> :</b> <? if($id){echo $data['birthdate'];}?></td>
							<td><b><?=$lng['Age']?> :</b> <? if($id){echo getAge($data['birthdate']);}?></td>
						</tr>
						<tr>
							
							<td><b><?=$lng['Gender']?> :</b> <? if($id){echo $gender[$data['gender']];}?></td>
							<td><b><?=$lng['Maritial status']?> :</b> <? if($id){echo $maritial[$data['maritial']];}?></td>
						</tr>
						<tr>
							<td><b><?=$lng['Religion']?> :</b> <? if($id){echo $religion[$data['religion']];}?></td>
							<td><b><?=$lng['Military status']?> :</b> <? if($id){echo $military_status[$data['military_status']];}?></td>
						</tr>
						<tr>
							<td><b><?=$lng['Driving license No.']?> :</b> <? if($id){echo $data['drvlicense_nr'];}?></td>
							<td><b><?=$lng['License expiry date']?> :</b> <? if($id){echo $data['drvlicense_exp'];}?></td>
						</tr>
						<tr>
							<td><b><?=$lng['ID card']?> :</b> <? if($id){echo $data['idcard_nr'];}?></td>
							<td><b><?=$lng['ID card expiry date']?> :</b> <? if($id){echo $data['idcard_exp'];}?></td>
						</tr>
						<tr>
							<td><b><?=$lng['Place issued']?> :</b> <? if($id){echo $data['issued'];}?></td>
							<td><b><?=$lng['Tax ID no.']?> :</b> <? if($id){echo $data['tax_id'];}?></td>
						</tr>
					</tbody>
				</table>
		
				<table class="reportTable" border="0" style="table-layout:fixed">
					<tbody>
						<tr>
							<th class="H1 tal" colspan="3"><?=$lng['Contact information']?></th>
						</tr>
						<tr>
							<td colspan="2"><b><?=$lng['Address']?> :</b> <? if($id){echo $data['reg_address'];}?></td>
							<td><b><?=$lng['Province']?> :</b> <? if($id){echo $data['province'];}?></td>
						</tr>
						<tr>
							<td><b><?=$lng['Sub district']?> :</b> <? if($id){echo $data['sub_district'];}?></td>
							<td><b><?=$lng['District']?> :</b> <? if($id){echo $data['district'];}?></td>
							<td><b><?=$lng['Postal code']?> :</b> <? if($id){echo $data['postnr'];}?></td>
						</tr>
						<tr>
							<td><b><?=$lng['Phone']?> :</b> <? if($id){echo $data['personal_phone'];}?></td>
							<td><b><?=$lng['email']?> :</b> <? if($id){echo $data['personal_email'];}?></td>
							<td><b><?=$lng['Country']?> :</b> <? if($id){echo $data['country'];}?></td>
						</tr>
						<tr>
							<td colspan="3"><b><?=$lng['Current address']?> :</b> <? if($id){echo $data['cur_address'];}?></td>
						</tr>
					</tbody>
				</table>
		
				<table class="reportTable" border="0">
					<tbody>
						<tr>
							<th class="H1 tal"><?=$lng['Emergency contact information']?></th>
						</tr>
						<tr>
							<td style="padding:0">
								<? if(!$data['emergency_contacts']){echo '<div style="padding:2px 10px">'.$lng['No data available'].'</div>';}else{?>
								<table class="basicTable" border="0" <? if(empty($data['emergency_contacts'])){echo 'style="display:none"';}?>>
									<thead>
									<tr style="line-height:60%">
										<th><?=$lng['Name']?></th>
										<th><?=$lng['Relationship']?></th>
										<th><?=$lng['Mobile phone']?></th>
										<th><?=$lng['Work phone']?></th>
									<tr>
									</thead>
									<tbody>
									<? foreach($data['emergency_contacts'] as $k=>$v){ if(!empty($v['name'])){ ?>
									<tr>
										<td><?=$v['name']?></td>
										<td><?=$v['relation']?></td>
										<td><?=$v['mobile']?></td>
										<td><?=$v['work']?></td>
									</tr>
									<? }} ?>
									</tbody>
								</table>
								<? } ?>
							</td>
						</tr>
					</tbody>
				</table>
		
				<table class="reportTable" border="0" style="table-layout:fixed">
					<tbody>
						<tr>
							<th class="H1 tal" colspan="3"><?=$lng['Work information']?></th>
						</tr>
						<tr>
							<td><b><?=$lng['Position']?> :</b> <? if($id){echo $positions[$data['position']][$lang];}?></td>
							<td><b><?=$lng['Employee status']?> :</b> <? if($id){echo $emp_status[$data['emp_status']];}?></td>
							<td><b><?=$lng['Employee type']?> :</b> <? if($id){echo $emp_type[$data['emp_type']];}?></td>
						</tr>
						<tr>
							<td><b><?=$lng['Joining date']?> :</b> <? if($id){echo date('d-m-Y', strtotime($data['joining_date']));}?></td>
							<td><b><?=$lng['Probation due date']?> :</b> <? if($id){echo $data['probation_date'];}?></td>
							<td><b><?=$lng['Service years']?> :</b> <? if($id){echo getAge($data['joining_date']);}?></td>
						</tr>
						<tr>
							<td><b><?=$lng['Resign date']?> :</b> <? if($id && !empty($data['resign_date'])){echo date('d-m-Y', strtotime($data['resign_date']));}?></td>
							<td colspan="2"><b><?=$lng['Employee platform']?> :</b> <? //if($id){echo $noyes01[$data['allow_login']];}?></td>
						</tr>
						<tr>
						</tr>
						<tr>
							<td colspan="3"><b><?=$lng['Resign reason']?> :</b> <? if($id){echo $data['resign_reason'];}?></td>
						</tr>
					</tbody>
				</table>
				
				<table class="reportTable" border="0" style="table-layout:fixed">
					<tbody>
						<tr>
							<th class="H1 tal" colspan="3"><?=$lng['Financial information']?></th>
						</tr>
						<tr>
							<td><b><?=$lng['Basic salary']?> :</b> <? if($id){echo number_format($data['base_salary'],2);}?></td>
							<td><b><?=$lng['Day rate']?> :</b> <? if($id){echo number_format($data['day_rate'],2);}?></td>
							<td><b><?=$lng['Hour rate']?> :</b> <? if($id){echo number_format($data['hour_rate'],2);}?></td>
						</tr>
						<tr>
							<td><b><?=$lng['Contract type']?> :</b> <? if($id){echo $contract_type[$data['contract_type']];}?></td>
							<td><b><?=$lng['Calculation base']?> :</b> <? if($id){echo $calc_base[$data['calc_base']];}?></td>
							<td><b><?=$lng['Base OT rate']?> :</b> <? if($id){echo $base_ot_rate[$data['base_ot_rate']];}?></td>
						</tr>
						<tr>
							<td><b><?=$lng['Calculate PVF']?> :</b> <? if($id){echo $noyes01[$data['calc_pvf']];}?></td>
							<td><b><?=$lng['PVF rate employee']?> :</b> <? if($id){echo $data['pvf_rate_emp'];}?> %</td>
							<td><b><?=$lng['PVF rate employer']?> :</b> <? if($id){echo $data['pvf_rate_com'];}?> %</td>
						</tr>
						<tr>
							<td><b><?=$lng['Calculate PSF']?> :</b> <? if($id){echo $noyes01[$data['calc_pvf']];}?></td>
							<td><b><?=$lng['PSF rate employee']?> :</b> <? if($id){echo $data['psf_rate_emp'];}?> %</td>
							<td><b><?=$lng['PSF rate employer']?> :</b> <? if($id){echo $data['psf_rate_com'];}?> %</td>
						</tr>
						<tr>
							<td><b><?=$lng['Calculate SSO']?> :</b> <? if($id){echo $noyes01[$data['calc_sso']];}?></td>
							<td><b><?=$lng['Calculate Tax']?> :</b> <? if($id){echo $calcTax[$data['calc_tax']];}?></td>
							<td><b><?=$lng['Modify Tax amount']?> :</b> <? if($id){echo $data['modify_tax'];}?></td>
						</tr>
					</tbody>
				</table>
				
				<table border="0" style="width:100%">
					<tr>
						<td style="width:50%; vertical-align:top; padding-right:5px">
							<table class="reportTable" border="0" style="">
								<tbody>
									<tr>
										<th class="H1 tal"><?=$lng['Fixed allowances']?></th>
									</tr>
									<? if($fix_allow){ foreach($fix_allow as $k=>$v){ ?>
									<tr>
										<td><b><?=$k?> : </b><?=number_format($v,2)?></td>
									</tr>
									<? }}else{ ?>
									<tr>
										<td><?=$lng['No data available']?></td>
									</tr>
									<? } ?>
								</tbody>
							</table>
						</td>
						<td style="vertical-align:top; padding-left:5px">
							<table class="reportTable" border="0">
								<tbody>
									<tr>
										<th class="H1 tal"><?=$lng['Fixed deductions']?></th>
									</tr>
									<? if($fix_deduct){ foreach($fix_deduct as $k=>$v){ ?>
									<tr>
										<td><b><?=$k?> : </b><?=number_format($v,2)?></td>
									</tr>
									<? }}else{ ?>
									<tr>
										<td><?=$lng['No data available']?></td>
									</tr>
									<? } ?>
								</tbody>
							</table>
						</td>
					</tr>
				</table>

				<table class="reportTable" border="0" style="table-layout:fixed">
					<tbody>
						<tr>
							<th class="H1 tal" colspan="3"><?=$lng['Tax deductions']?><span style="float:right"><?=$lng['Total tax deductions']?> : <? if($id){echo number_format($total_deductions,2);}?></span></th>
						</tr>
						<tr>
							<td><b><?=$lng['Standard deduction']?> :</b> <? if($id){echo number_format($data['tax_standard_deduction'],2);}?></td>
							<td><b><?=$lng['Personal care']?> :</b> <? if($id){echo number_format($data['tax_personal_allowance'],2);}?></td>
							<td><b><?=$lng['Spouse care']?> :</b> <? if($id){echo number_format($data['tax_allow_spouse'],2);}?></td>
						</tr>
						<tr>
							<td><b><?=$lng['Parents care']?> :</b> <? if($id){echo number_format($data['tax_allow_parents'],2);}?></td>
							<td><b><?=$lng['Parents in law care']?> :</b> <? if($id){echo number_format($data['tax_allow_parents_inlaw'],2);}?></td>
							<td><b><?=$lng['Care disabled person']?> :</b> <? if($id){echo number_format($data['tax_allow_disabled_person'],2);}?></td>
						</tr>
						<tr>
							<td><b><?=$lng['Child care - biological']?> :</b> <? if($id){echo number_format($data['tax_allow_child_bio'],2);}?></td>
							<td><b><?=$lng['Child care - biological 2018/19/20']?> :</b> <? if($id){echo number_format($data['tax_allow_child_bio_2018'],2);}?></td>
							<td><b><?=$lng['Child care - adopted']?> :</b> <? if($id){echo number_format($data['tax_allow_child_adopted'],2);}?></td>
						</tr>
						<tr>
							<td><b><?=$lng['Child birth (Baby bonus)']?> :</b> <? if($id){echo number_format($data['tax_allow_child_birth'],2);}?></td>
							<td><b><?=$lng['Own health insurance']?> :</b> <? if($id){echo number_format($data['tax_allow_own_health'],2);}?></td>
							<td><b><?=$lng['Own life insurance']?> :</b> <? if($id){echo number_format($data['tax_allow_own_life_insurance'],2);}?></td>
						</tr>
						<tr>
							<td><b><?=$lng['Health insurance parents']?> :</b> <? if($id){echo number_format($data['tax_allow_health_parents'],2);}?></td>
							<td><b><?=$lng['Life insurance spouse']?> :</b> <? if($id){echo number_format($data['tax_allow_life_insurance_spouse'],2);}?></td>
							<td><b><?=$lng['Pension fund']?> :</b> <? if($id){echo number_format($data['tax_allow_pension_fund'],2);}?></td>
						</tr>
						<tr>
							<td><b><?=$lng['Provident fund']?> :</b> <? if($id){echo number_format($data['tax_allow_pvf'],2);}?></td>
							<td><b><?=$lng['NSF allowance']?> :</b> <? if($id){echo number_format($data['tax_allow_nsf'],2);}?></td>
							<td><b><?=$lng['RMF allowance']?> :</b> <? if($id){echo number_format($data['tax_allow_rmf'],2);}?></td>
						</tr>
						<tr>
							<td><b><?=$lng['Social Security Fund']?> :</b> <? if($id){echo number_format($data['tax_allow_sso'],2);}?></td>
							<td><b><?=$lng['LTF amount']?> :</b> <? if($id){echo number_format($data['tax_allow_ltf'],2);}?></td>
							<td><b><?=$lng['Home loan interest allowance']?> :</b> <? if($id){echo number_format($data['tax_allow_home_loan_interest'],2);}?></td>
							
						</tr>
						<tr>
							<td><b><?=$lng['Donation charity']?> :</b> <? if($id){echo number_format($data['tax_allow_donation_charity'],2);}?></td>
							<td><b><?=$lng['Donation flooding']?> :</b> <? if($id){echo number_format($data['tax_allow_donation_flood'],2);}?></td>
							<td><b><?=$lng['Donation education']?> :</b> <? if($id){echo number_format($data['tax_allow_donation_education'],2);}?></td>
						</tr>
						<tr>
							<td><b><?=$lng['Exemption disabled person <65 yrs']?> :</b> <? if($id){echo number_format($data['tax_allow_exemp_disabled_under'],2);}?></td>
							<td><b><?=$lng['Exemption tax payer => 65yrs']?> :</b> <? if($id){echo number_format($data['tax_allow_exemp_payer_older'],2);}?></td>
							<td><b><?=$lng['First home buyers allowance']?> :</b> <? if($id){echo number_format($data['tax_allow_first_home'],2);}?></td>
						</tr>
						<tr>
							<td><b><?=$lng['Year-end shopping allowance']?> :</b> <? if($id){echo number_format($data['tax_allow_year_end_shopping'],2);}?></td>
							<td><b><?=$lng['Domestic tour allowance']?> :</b> <? if($id){echo number_format($data['tax_allow_domestic_tour'],2);}?></td>
							<td><b><?=$lng['Other allowance']?> :</b> <? if($id){echo number_format($data['tax_allow_other'],2);}?></td>
						</tr>
						<tr>
							<td colspan="3"><b><?=$lng['Tax calculation method']?> :</b> <? if($id){if($data['calc_method'] == 'def' || $data['calc_method'] == 'cam'){ echo $lng['Calculate in Advance Method'];}else{echo $lng['Accumulative Calculation Method'];}}?></td>
						</tr>
					</tbody>
				</table>
			</div>
			
		</div>
		<!--<table border="0" style="width:100%; height:100%; display:none">
			<tr>
				
				<td style="width:300px; vertical-align:top; padding-right:20px">
					<div class="searchFilter" style="width:300px; margin-bottom:5px">
						<input style="width:100%; font-size:13px; line-height:27px" class="sFilter" placeholder="<?=$lng['Employee']?> ... <?=$lng['Type for hints']?> ..." type="text" id="selectEmployee" value="<?=$employee?>" />
					</div>
					<button <? if(!$id){echo 'disabled';}?> onClick="window.open('reports/print/print_employee_record.php','_blank')" style="margin:0 0 5px; width:100%; text-align:left" type="button" class="btn btn-primary btn-sm"><i class="fa fa-file-pdf-o"></i>&nbsp; <?=$lng['Print']?></button>

				</td>
				<td style="vertical-align:top">
		
					<div class="A4form" style="width:900px;xheight:1260px; padding:30px 30px 30px;">
					
					</div>
					
				</td>
			</tr>
		</table>
-->
	</div>
					
	<script type="text/javascript">
	
	$(document).ready(function() {
		
		var employees = <?=$emp_array?>;
		//var emps = <?//=json_encode($emps)?>;
		$('#selectEmployee').devbridgeAutocomplete({
			 lookup: employees,
			 onSelect: function (suggestion) {
			 	//$("#emp_id").val(suggestion.data);
				//alert(suggestion.data);
				$.ajax({
					url: "ajax/select_employee.php",
					data: {id: suggestion.data},
					success: function(response) {
						//$('#dump').html(response);
						location.reload();
					},
					error: function (xhr, ajaxOptions, thrownError) {
						alert(thrownError);
					}
				});
			 }
		});	
		

	});
	
	</script>

						
						


















