<?
	
	$price_schedule = array();
	$price_activities = array();
	$sql = "SELECT standard FROM rego_company_settings";
	if($res = $dba->query($sql)){
		if($row = $res->fetch_assoc()){
			$standard = unserialize($row['standard']);
		}
	}else{
		echo 'Error : '.mysqli_error($dba);
	}
	//var_dump($standard); exit;
	
?>

<style>
	.basicTable thead th {
		min-width:100px;
	}
</style>
	
	<h2><i class="fa fa-table"></i>&nbsp;&nbsp;<?=$lng['REGO Standards']?> <span style="float:right; display:none; font-style:italic; color:#b00" id="sAlert"><?=$lng['Data is not updated to last changes made']?></span></h2>
	<div class="main">
		
				
		<form id="standardForm">
			<table class="basicTable tac" style="margin-top:0px; width:100%; table-layout:auto">
				<thead>
					<tr>
						<th class="tal"><?=$lng['Module']?></th>
						<th class="tal"><?=$lng['Section']?></th>
						<th><?=$lng['FREE TRIAL']?></th>
						<th><?=strtoupper($client_prefix)?> 10</th>
						<th><?=strtoupper($client_prefix)?> 20</th>
						<th><?=strtoupper($client_prefix)?> 50</th>
						<th><?=strtoupper($client_prefix)?> 100</th>
						<th><?=$lng['STANDARD']?></th>
						<th><?=$lng['PROFESSIONAL']?></th>
						<th><?=$lng['ELITE']?></th>
						<th style="width:90%"></th>
					</tr>
				</thead>
				<tbody>
				
				<!--EMPLOYEE REGISTER //////////////////////////////////////////////////////////////////// --> 
					<tr>
						<th class="tal" style="font-weight:600; vertical-align:baseline; width:1px" rowspan="4"><?=$lng['Employee register']?></th>
						<th class="tal"><?=$lng['Other benefits']?></th>
						<td>
							<input name="rego[0][other_benefits]" type="hidden" value="0"  />
							<label><input <? if($standard[0]['other_benefits']){echo 'checked';}?> type="checkbox" name="rego[0][other_benefits]" value="1" class="checkbox nomargin"></label>
						</td>
						<td>
							<input name="rego[10][other_benefits]" type="hidden" value="0"  />
							<label><input <? if($standard[10]['other_benefits']){echo 'checked';}?> type="checkbox" name="rego[10][other_benefits]" value="1" class="checkbox nomargin"></label>
						</td>
						<td>
							<input name="rego[20][other_benefits]" type="hidden" value="0"  />
							<label><input <? if($standard[20]['other_benefits']){echo 'checked';}?> type="checkbox" name="rego[20][other_benefits]" value="1" class="checkbox nomargin"></label>
						</td>
						<td>
							<input name="rego[50][other_benefits]" type="hidden" value="0"  />
							<label><input <? if($standard[50]['other_benefits']){echo 'checked';}?> type="checkbox" name="rego[50][other_benefits]" value="1" class="checkbox nomargin"></label>
						</td>
						<td>
							<input name="rego[100][other_benefits]" type="hidden" value="0"  />
							<label><input <? if($standard[100]['other_benefits']){echo 'checked';}?> type="checkbox" name="rego[100][other_benefits]" value="1" class="checkbox nomargin"></label>
						</td>
						<td>
							<input name="rego[200][other_benefits]" type="hidden" value="0"  />
							<label><input <? if($standard[200]['other_benefits']){echo 'checked';}?> type="checkbox" name="rego[200][other_benefits]" value="1" class="checkbox nomargin"></label>
						</td>
						<td>
							<input name="rego[300][other_benefits]" type="hidden" value="0"  />
							<label><input <? if($standard[300]['other_benefits']){echo 'checked';}?> type="checkbox" name="rego[300][other_benefits]" value="1" class="checkbox nomargin"></label>
						</td>
						<td>
							<input name="rego[400][other_benefits]" type="hidden" value="0"  />
							<label><input <? if($standard[400]['other_benefits']){echo 'checked';}?> type="checkbox" name="rego[400][other_benefits]" value="1" class="checkbox nomargin"></label>
						</td>
						<td></td>
					</tr>
					<tr>
						<th class="tal"><?=$lng['Historical records']?></th>
						<td>
							<input name="rego[0][historical]" type="hidden" value="0"  />
							<label><input <? if($standard[0]['historical']){echo 'checked';}?> type="checkbox" name="rego[0][historical]" value="1" class="checkbox nomargin"></label>
						</td>
						<td>
							<input name="rego[10][historical]" type="hidden" value="0"  />
							<label><input <? if($standard[10]['historical']){echo 'checked';}?> type="checkbox" name="rego[10][historical]" value="1" class="checkbox nomargin"></label>
						</td>
						<td>
							<input name="rego[20][historical]" type="hidden" value="0"  />
							<label><input <? if($standard[20]['historical']){echo 'checked';}?> type="checkbox" name="rego[20][historical]" value="1" class="checkbox nomargin"></label>
						</td>
						<td>
							<input name="rego[50][historical]" type="hidden" value="0"  />
							<label><input <? if($standard[50]['historical']){echo 'checked';}?> type="checkbox" name="rego[50][historical]" value="1" class="checkbox nomargin"></label>
						</td>
						<td>
							<input name="rego[100][historical]" type="hidden" value="0"  />
							<label><input <? if($standard[100]['historical']){echo 'checked';}?> type="checkbox" name="rego[100][historical]" value="1" class="checkbox nomargin"></label>
						</td>
						<td>
							<input name="rego[200][historical]" type="hidden" value="0"  />
							<label><input <? if($standard[200]['historical']){echo 'checked';}?> type="checkbox" name="rego[200][historical]" value="1" class="checkbox nomargin"></label>
						</td>
						<td>
							<input name="rego[300][historical]" type="hidden" value="0"  />
							<label><input <? if($standard[300]['historical']){echo 'checked';}?> type="checkbox" name="rego[300][historical]" value="1" class="checkbox nomargin"></label>
						</td>
						<td>
							<input name="rego[400][historical]" type="hidden" value="0"  />
							<label><input <? if($standard[400]['historical']){echo 'checked';}?> type="checkbox" name="rego[400][historical]" value="1" class="checkbox nomargin"></label>
						</td>
						<td></td>
					</tr>
					<tr>
						<th class="tal"><?=$lng['Workpermit']?></th>
						<td>
							<input name="rego[0][workpermit]" type="hidden" value="0"  />
							<label><input <? if($standard[0]['workpermit']){echo 'checked';}?> type="checkbox" name="rego[0][workpermit]" value="1" class="checkbox nomargin"></label>
						</td>
						<td>
							<input name="rego[10][workpermit]" type="hidden" value="0"  />
							<label><input <? if($standard[10]['workpermit']){echo 'checked';}?> type="checkbox" name="rego[10][workpermit]" value="1" class="checkbox nomargin"></label>
						</td>
						<td>
							<input name="rego[20][workpermit]" type="hidden" value="0"  />
							<label><input <? if($standard[20]['workpermit']){echo 'checked';}?> type="checkbox" name="rego[20][workpermit]" value="1" class="checkbox nomargin"></label>
						</td>
						<td>
							<input name="rego[50][workpermit]" type="hidden" value="0"  />
							<label><input <? if($standard[50]['workpermit']){echo 'checked';}?> type="checkbox" name="rego[50][workpermit]" value="1" class="checkbox nomargin"></label>
						</td>
						<td>
							<input name="rego[100][workpermit]" type="hidden" value="0"  />
							<label><input <? if($standard[100]['workpermit']){echo 'checked';}?> type="checkbox" name="rego[100][workpermit]" value="1" class="checkbox nomargin"></label>
						</td>
						<td>
							<input name="rego[200][workpermit]" type="hidden" value="0"  />
							<label><input <? if($standard[200]['workpermit']){echo 'checked';}?> type="checkbox" name="rego[200][workpermit]" value="1" class="checkbox nomargin"></label>
						</td>
						<td>
							<input name="rego[300][workpermit]" type="hidden" value="0"  />
							<label><input <? if($standard[300]['workpermit']){echo 'checked';}?> type="checkbox" name="rego[300][workpermit]" value="1" class="checkbox nomargin"></label>
						</td>
						<td>
							<input name="rego[400][workpermit]" type="hidden" value="0"  />
							<label><input <? if($standard[400]['workpermit']){echo 'checked';}?> type="checkbox" name="rego[400][workpermit]" value="1" class="checkbox nomargin"></label>
						</td>
						<td></td>
					</tr>
					<tr>
						<th class="tal"><?=$lng['Tax simulation']?></th>
						<td>
							<input name="rego[0][tax_simulation]" type="hidden" value="0"  />
							<label><input <? if($standard[0]['tax_simulation']){echo 'checked';}?> type="checkbox" name="rego[0][tax_simulation]" value="1" class="checkbox nomargin"></label>
						</td>
						<td>
							<input name="rego[10][tax_simulation]" type="hidden" value="0"  />
							<label><input <? if($standard[10]['tax_simulation']){echo 'checked';}?> type="checkbox" name="rego[10][tax_simulation]" value="1" class="checkbox nomargin"></label>
						</td>
						<td>
							<input name="rego[20][tax_simulation]" type="hidden" value="0"  />
							<label><input <? if($standard[20]['tax_simulation']){echo 'checked';}?> type="checkbox" name="rego[20][tax_simulation]" value="1" class="checkbox nomargin"></label>
						</td>
						<td>
							<input name="rego[50][tax_simulation]" type="hidden" value="0"  />
							<label><input <? if($standard[50]['tax_simulation']){echo 'checked';}?> type="checkbox" name="rego[50][tax_simulation]" value="1" class="checkbox nomargin"></label>
						</td>
						<td>
							<input name="rego[100][tax_simulation]" type="hidden" value="0"  />
							<label><input <? if($standard[100]['tax_simulation']){echo 'checked';}?> type="checkbox" name="rego[100][tax_simulation]" value="1" class="checkbox nomargin"></label>
						</td>
						<td>
							<input name="rego[200][tax_simulation]" type="hidden" value="0"  />
							<label><input <? if($standard[200]['tax_simulation']){echo 'checked';}?> type="checkbox" name="rego[200][tax_simulation]" value="1" class="checkbox nomargin"></label>
						</td>
						<td>
							<input name="rego[300][tax_simulation]" type="hidden" value="0"  />
							<label><input <? if($standard[300]['tax_simulation']){echo 'checked';}?> type="checkbox" name="rego[300][tax_simulation]" value="1" class="checkbox nomargin"></label>
						</td>
						<td>
							<input name="rego[400][tax_simulation]" type="hidden" value="0"  />
							<label><input <? if($standard[400]['tax_simulation']){echo 'checked';}?> type="checkbox" name="rego[400][tax_simulation]" value="1" class="checkbox nomargin"></label>
						</td>
						<td></td>
					</tr>

				<!--PAYROLL MODULE //////////////////////////////////////////////////////////////////// --> 
					<tr style="border-top:2px solid #ddd">
						<th class="tal" style="font-weight:600; vertical-align:baseline; width:1px" rowspan="2"><?=$lng['Payroll module']?></th>
						<th class="tal"><?=$lng['Benefits calculator']?></th>
						<td>
							<input name="rego[0][pr_benefits]" type="hidden" value="0"  />
							<label><input <? if($standard[0]['pr_benefits']){echo 'checked';}?> type="checkbox" name="rego[0][pr_benefits]" value="1" class="checkbox nomargin"></label>
						</td>
						<td>
							<input name="rego[10][pr_benefits]" type="hidden" value="0"  />
							<label><input <? if($standard[10]['pr_benefits']){echo 'checked';}?> type="checkbox" name="rego[10][pr_benefits]" value="1" class="checkbox nomargin"></label>
						</td>
						<td>
							<input name="rego[20][pr_benefits]" type="hidden" value="0"  />
							<label><input <? if($standard[20]['pr_benefits']){echo 'checked';}?> type="checkbox" name="rego[20][pr_benefits]" value="1" class="checkbox nomargin"></label>
						</td>
						<td>
							<input name="rego[50][pr_benefits]" type="hidden" value="0"  />
							<label><input <? if($standard[50]['pr_benefits']){echo 'checked';}?> type="checkbox" name="rego[50][pr_benefits]" value="1" class="checkbox nomargin"></label>
						</td>
						<td>
							<input name="rego[100][pr_benefits]" type="hidden" value="0"  />
							<label><input <? if($standard[100]['pr_benefits']){echo 'checked';}?> type="checkbox" name="rego[100][pr_benefits]" value="1" class="checkbox nomargin"></label>
						</td>
						<td>
							<input name="rego[200][pr_benefits]" type="hidden" value="0"  />
							<label><input <? if($standard[200]['pr_benefits']){echo 'checked';}?> type="checkbox" name="rego[200][pr_benefits]" value="1" class="checkbox nomargin"></label>
						</td>
						<td>
							<input name="rego[300][pr_benefits]" type="hidden" value="0"  />
							<label><input <? if($standard[300]['pr_benefits']){echo 'checked';}?> type="checkbox" name="rego[300][pr_benefits]" value="1" class="checkbox nomargin"></label>
						</td>
						<td>
							<input name="rego[400][pr_benefits]" type="hidden" value="0"  />
							<label><input <? if($standard[400]['pr_benefits']){echo 'checked';}?> type="checkbox" name="rego[400][pr_benefits]" value="1" class="checkbox nomargin"></label>
						</td>
						<td></td>
					</tr>
					<tr>
						<th class="tal"><?=$lng['Individual calculator']?></th>
						<td>
							<input name="rego[0][pr_individual]" type="hidden" value="0"  />
							<label><input <? if($standard[0]['pr_individual']){echo 'checked';}?> type="checkbox" name="rego[0][pr_individual]" value="1" class="checkbox nomargin"></label>
						</td>
						<td>
							<input name="rego[10][pr_individual]" type="hidden" value="0"  />
							<label><input <? if($standard[10]['pr_individual']){echo 'checked';}?> type="checkbox" name="rego[10][pr_individual]" value="1" class="checkbox nomargin"></label>
						</td>
						<td>
							<input name="rego[20][pr_individual]" type="hidden" value="0"  />
							<label><input <? if($standard[20]['pr_individual']){echo 'checked';}?> type="checkbox" name="rego[20][pr_individual]" value="1" class="checkbox nomargin"></label>
						</td>
						<td>
							<input name="rego[50][pr_individual]" type="hidden" value="0"  />
							<label><input <? if($standard[50]['pr_individual']){echo 'checked';}?> type="checkbox" name="rego[50][pr_individual]" value="1" class="checkbox nomargin"></label>
						</td>
						<td>
							<input name="rego[100][pr_individual]" type="hidden" value="0"  />
							<label><input <? if($standard[100]['pr_individual']){echo 'checked';}?> type="checkbox" name="rego[100][pr_individual]" value="1" class="checkbox nomargin"></label>
						</td>
						<td>
							<input name="rego[200][pr_individual]" type="hidden" value="0"  />
							<label><input <? if($standard[200]['pr_individual']){echo 'checked';}?> type="checkbox" name="rego[200][pr_individual]" value="1" class="checkbox nomargin"></label>
						</td>
						<td>
							<input name="rego[300][pr_individual]" type="hidden" value="0"  />
							<label><input <? if($standard[300]['pr_individual']){echo 'checked';}?> type="checkbox" name="rego[300][pr_individual]" value="1" class="checkbox nomargin"></label>
						</td>
						<td>
							<input name="rego[400][pr_individual]" type="hidden" value="0"  />
							<label><input <? if($standard[400]['pr_individual']){echo 'checked';}?> type="checkbox" name="rego[400][pr_individual]" value="1" class="checkbox nomargin"></label>
						</td>
						<td></td>
					</tr>

				<!--SETTINGS //////////////////////////////////////////////////////////////////// --> 
					<tr style="border-top:2px solid #ddd">
						<th class="tal" style="font-weight:600; vertical-align:baseline; width:1px" rowspan="7"><?=$lng['Settings']?></th>
						<th class="tal"><?=$lng['Add entity']?></th>
						<td>
							<input name="rego[0][add_entity]" type="hidden" value="0"  />
							<label><input <? if($standard[0]['add_entity']){echo 'checked';}?> type="checkbox" name="rego[0][add_entity]" value="1" class="checkbox nomargin"></label>
						</td>
						<td>
							<input name="rego[10][add_entity]" type="hidden" value="0"  />
							<label><input <? if($standard[10]['add_entity']){echo 'checked';}?> type="checkbox" name="rego[10][add_entity]" value="1" class="checkbox nomargin"></label>
						</td>
						<td>
							<input name="rego[20][add_entity]" type="hidden" value="0"  />
							<label><input <? if($standard[20]['add_entity']){echo 'checked';}?> type="checkbox" name="rego[20][add_entity]" value="1" class="checkbox nomargin"></label>
						</td>
						<td>
							<input name="rego[50][add_entity]" type="hidden" value="0"  />
							<label><input <? if($standard[50]['add_entity']){echo 'checked';}?> type="checkbox" name="rego[50][add_entity]" value="1" class="checkbox nomargin"></label>
						</td>
						<td>
							<input name="rego[100][add_entity]" type="hidden" value="0"  />
							<label><input <? if($standard[100]['add_entity']){echo 'checked';}?> type="checkbox" name="rego[100][add_entity]" value="1" class="checkbox nomargin"></label>
						</td>
						<td>
							<input name="rego[200][add_entity]" type="hidden" value="0"  />
							<label><input <? if($standard[200]['add_entity']){echo 'checked';}?> type="checkbox" name="rego[200][add_entity]" value="1" class="checkbox nomargin"></label>
						</td>
						<td>
							<input name="rego[300][add_entity]" type="hidden" value="0"  />
							<label><input <? if($standard[300]['add_entity']){echo 'checked';}?> type="checkbox" name="rego[300][add_entity]" value="1" class="checkbox nomargin"></label>
						</td>
						<td>
							<input name="rego[400][add_entity]" type="hidden" value="0"  />
							<label><input <? if($standard[400]['add_entity']){echo 'checked';}?> type="checkbox" name="rego[400][add_entity]" value="1" class="checkbox nomargin"></label>
						</td>
						<td></td>
					</tr>
					<tr>
						<th class="tal"><?=$lng['Add branch']?></th>
						<td>
							<input name="rego[0][add_branch]" type="hidden" value="0"  />
							<label><input <? if($standard[0]['add_branch']){echo 'checked';}?> type="checkbox" name="rego[0][add_branch]" value="1" class="checkbox nomargin"></label>
						</td>
						<td>
							<input name="rego[10][add_branch]" type="hidden" value="0"  />
							<label><input <? if($standard[10]['add_branch']){echo 'checked';}?> type="checkbox" name="rego[10][add_branch]" value="1" class="checkbox nomargin"></label>
						</td>
						<td>
							<input name="rego[20][add_branch]" type="hidden" value="0"  />
							<label><input <? if($standard[20]['add_branch']){echo 'checked';}?> type="checkbox" name="rego[20][add_branch]" value="1" class="checkbox nomargin"></label>
						</td>
						<td>
							<input name="rego[50][add_branch]" type="hidden" value="0"  />
							<label><input <? if($standard[50]['add_branch']){echo 'checked';}?> type="checkbox" name="rego[50][add_branch]" value="1" class="checkbox nomargin"></label>
						</td>
						<td>
							<input name="rego[100][add_branch]" type="hidden" value="0"  />
							<label><input <? if($standard[100]['add_branch']){echo 'checked';}?> type="checkbox" name="rego[100][add_branch]" value="1" class="checkbox nomargin"></label>
						</td>
						<td>
							<input name="rego[200][add_branch]" type="hidden" value="0"  />
							<label><input <? if($standard[200]['add_branch']){echo 'checked';}?> type="checkbox" name="rego[200][add_branch]" value="1" class="checkbox nomargin"></label>
						</td>
						<td>
							<input name="rego[300][add_branch]" type="hidden" value="0"  />
							<label><input <? if($standard[300]['add_branch']){echo 'checked';}?> type="checkbox" name="rego[300][add_branch]" value="1" class="checkbox nomargin"></label>
						</td>
						<td>
							<input name="rego[400][add_branch]" type="hidden" value="0"  />
							<label><input <? if($standard[400]['add_branch']){echo 'checked';}?> type="checkbox" name="rego[400][add_branch]" value="1" class="checkbox nomargin"></label>
						</td>
						<td></td>
					</tr>
					<tr>
						<th class="tal"><?=$lng['Departments']?></th>
						<td>
							<input name="rego[0][set_departments]" type="hidden" value="0"  />
							<label><input <? if($standard[0]['set_departments']){echo 'checked';}?> type="checkbox" name="rego[0][set_departments]" value="1" class="checkbox nomargin"></label>
						</td>
						<td>
							<input name="rego[10][set_departments]" type="hidden" value="0"  />
							<label><input <? if($standard[10]['set_departments']){echo 'checked';}?> type="checkbox" name="rego[10][set_departments]" value="1" class="checkbox nomargin"></label>
						</td>
						<td>
							<input name="rego[20][set_departments]" type="hidden" value="0"  />
							<label><input <? if($standard[20]['set_departments']){echo 'checked';}?> type="checkbox" name="rego[20][set_departments]" value="1" class="checkbox nomargin"></label>
						</td>
						<td>
							<input name="rego[50][set_departments]" type="hidden" value="0"  />
							<label><input <? if($standard[50]['set_departments']){echo 'checked';}?> type="checkbox" name="rego[50][set_departments]" value="1" class="checkbox nomargin"></label>
						</td>
						<td>
							<input name="rego[100][set_departments]" type="hidden" value="0"  />
							<label><input <? if($standard[100]['set_departments']){echo 'checked';}?> type="checkbox" name="rego[100][set_departments]" value="1" class="checkbox nomargin"></label>
						</td>
						<td>
							<input name="rego[200][set_departments]" type="hidden" value="0"  />
							<label><input <? if($standard[200]['set_departments']){echo 'checked';}?> type="checkbox" name="rego[200][set_departments]" value="1" class="checkbox nomargin"></label>
						</td>
						<td>
							<input name="rego[300][set_departments]" type="hidden" value="0"  />
							<label><input <? if($standard[300]['set_departments']){echo 'checked';}?> type="checkbox" name="rego[300][set_departments]" value="1" class="checkbox nomargin"></label>
						</td>
						<td>
							<input name="rego[400][set_departments]" type="hidden" value="0"  />
							<label><input <? if($standard[400]['set_departments']){echo 'checked';}?> type="checkbox" name="rego[400][set_departments]" value="1" class="checkbox nomargin"></label>
						</td>
						<td></td>
					</tr>
					<tr>
						<th class="tal"><?=$lng['Divisions']?></th>
						<td>
							<input name="rego[0][set_divisions]" type="hidden" value="0"  />
							<label><input <? if($standard[0]['set_divisions']){echo 'checked';}?> type="checkbox" name="rego[0][set_divisions]" value="1" class="checkbox nomargin"></label>
						</td>
						<td>
							<input name="rego[10][set_divisions]" type="hidden" value="0"  />
							<label><input <? if($standard[10]['set_divisions']){echo 'checked';}?> type="checkbox" name="rego[10][set_divisions]" value="1" class="checkbox nomargin"></label>
						</td>
						<td>
							<input name="rego[20][set_divisions]" type="hidden" value="0"  />
							<label><input <? if($standard[20]['set_divisions']){echo 'checked';}?> type="checkbox" name="rego[20][set_divisions]" value="1" class="checkbox nomargin"></label>
						</td>
						<td>
							<input name="rego[50][set_divisions]" type="hidden" value="0"  />
							<label><input <? if($standard[50]['set_divisions']){echo 'checked';}?> type="checkbox" name="rego[50][set_divisions]" value="1" class="checkbox nomargin"></label>
						</td>
						<td>
							<input name="rego[100][set_divisions]" type="hidden" value="0"  />
							<label><input <? if($standard[100]['set_divisions']){echo 'checked';}?> type="checkbox" name="rego[100][set_divisions]" value="1" class="checkbox nomargin"></label>
						</td>
						<td>
							<input name="rego[200][set_divisions]" type="hidden" value="0"  />
							<label><input <? if($standard[200]['set_divisions']){echo 'checked';}?> type="checkbox" name="rego[200][set_divisions]" value="1" class="checkbox nomargin"></label>
						</td>
						<td>
							<input name="rego[300][set_divisions]" type="hidden" value="0"  />
							<label><input <? if($standard[300]['set_divisions']){echo 'checked';}?> type="checkbox" name="rego[300][set_divisions]" value="1" class="checkbox nomargin"></label>
						</td>
						<td>
							<input name="rego[400][set_divisions]" type="hidden" value="0"  />
							<label><input <? if($standard[400]['set_divisions']){echo 'checked';}?> type="checkbox" name="rego[400][set_divisions]" value="1" class="checkbox nomargin"></label>
						</td>
						<td></td>
					</tr>
					<tr>
						<th class="tal"><?=$lng['System users allocate']?></th>
						<td>
							<input name="rego[0][set_allocate]" type="hidden" value="0"  />
							<label><input <? if($standard[0]['set_allocate']){echo 'checked';}?> type="checkbox" name="rego[0][set_allocate]" value="1" class="checkbox nomargin"></label>
						</td>
						<td>
							<input name="rego[10][set_allocate]" type="hidden" value="0"  />
							<label><input <? if($standard[10]['set_allocate']){echo 'checked';}?> type="checkbox" name="rego[10][set_allocate]" value="1" class="checkbox nomargin"></label>
						</td>
						<td>
							<input name="rego[20][set_allocate]" type="hidden" value="0"  />
							<label><input <? if($standard[20]['set_allocate']){echo 'checked';}?> type="checkbox" name="rego[20][set_allocate]" value="1" class="checkbox nomargin"></label>
						</td>
						<td>
							<input name="rego[50][set_allocate]" type="hidden" value="0"  />
							<label><input <? if($standard[50]['set_allocate']){echo 'checked';}?> type="checkbox" name="rego[50][set_allocate]" value="1" class="checkbox nomargin"></label>
						</td>
						<td>
							<input name="rego[100][set_allocate]" type="hidden" value="0"  />
							<label><input <? if($standard[100]['set_allocate']){echo 'checked';}?> type="checkbox" name="rego[100][set_allocate]" value="1" class="checkbox nomargin"></label>
						</td>
						<td>
							<input name="rego[200][set_allocate]" type="hidden" value="0"  />
							<label><input <? if($standard[200]['set_allocate']){echo 'checked';}?> type="checkbox" name="rego[200][set_allocate]" value="1" class="checkbox nomargin"></label>
						</td>
						<td>
							<input name="rego[300][set_allocate]" type="hidden" value="0"  />
							<label><input <? if($standard[300]['set_allocate']){echo 'checked';}?> type="checkbox" name="rego[300][set_allocate]" value="1" class="checkbox nomargin"></label>
						</td>
						<td>
							<input name="rego[400][set_allocate]" type="hidden" value="0"  />
							<label><input <? if($standard[400]['set_allocate']){echo 'checked';}?> type="checkbox" name="rego[400][set_allocate]" value="1" class="checkbox nomargin"></label>
						</td>
						<td></td>
					</tr>
					<tr>
						<th class="tal"><?=$lng['System users permissions']?></th>
						<td>
							<input name="rego[0][set_permissions]" type="hidden" value="0"  />
							<label><input <? if($standard[0]['set_permissions']){echo 'checked';}?> type="checkbox" name="rego[0][set_permissions]" value="1" class="checkbox nomargin"></label>
						</td>
						<td>
							<input name="rego[10][set_permissions]" type="hidden" value="0"  />
							<label><input <? if($standard[10]['set_permissions']){echo 'checked';}?> type="checkbox" name="rego[10][set_permissions]" value="1" class="checkbox nomargin"></label>
						</td>
						<td>
							<input name="rego[20][set_permissions]" type="hidden" value="0"  />
							<label><input <? if($standard[20]['set_permissions']){echo 'checked';}?> type="checkbox" name="rego[20][set_permissions]" value="1" class="checkbox nomargin"></label>
						</td>
						<td>
							<input name="rego[50][set_permissions]" type="hidden" value="0"  />
							<label><input <? if($standard[50]['set_permissions']){echo 'checked';}?> type="checkbox" name="rego[50][set_permissions]" value="1" class="checkbox nomargin"></label>
						</td>
						<td>
							<input name="rego[100][set_permissions]" type="hidden" value="0"  />
							<label><input <? if($standard[100]['set_permissions']){echo 'checked';}?> type="checkbox" name="rego[100][set_permissions]" value="1" class="checkbox nomargin"></label>
						</td>
						<td>
							<input name="rego[200][set_permissions]" type="hidden" value="0"  />
							<label><input <? if($standard[200]['set_permissions']){echo 'checked';}?> type="checkbox" name="rego[200][set_permissions]" value="1" class="checkbox nomargin"></label>
						</td>
						<td>
							<input name="rego[300][set_permissions]" type="hidden" value="0"  />
							<label><input <? if($standard[300]['set_permissions']){echo 'checked';}?> type="checkbox" name="rego[300][set_permissions]" value="1" class="checkbox nomargin"></label>
						</td>
						<td>
							<input name="rego[400][set_permissions]" type="hidden" value="0"  />
							<label><input <? if($standard[400]['set_permissions']){echo 'checked';}?> type="checkbox" name="rego[400][set_permissions]" value="1" class="checkbox nomargin"></label>
						</td>
						<td></td>
					</tr>
					<tr>
						<th class="tal"><?=$lng['Employee defaults']?></th>
						<td>
							<input name="rego[0][set_employee]" type="hidden" value="0"  />
							<label><input <? if($standard[0]['set_employee']){echo 'checked';}?> type="checkbox" name="rego[0][set_employee]" value="1" class="checkbox nomargin"></label>
						</td>
						<td>
							<input name="rego[10][set_employee]" type="hidden" value="0"  />
							<label><input <? if($standard[10]['set_employee']){echo 'checked';}?> type="checkbox" name="rego[10][set_employee]" value="1" class="checkbox nomargin"></label>
						</td>
						<td>
							<input name="rego[20][set_employee]" type="hidden" value="0"  />
							<label><input <? if($standard[20]['set_employee']){echo 'checked';}?> type="checkbox" name="rego[20][set_employee]" value="1" class="checkbox nomargin"></label>
						</td>
						<td>
							<input name="rego[50][set_employee]" type="hidden" value="0"  />
							<label><input <? if($standard[50]['set_employee']){echo 'checked';}?> type="checkbox" name="rego[50][set_employee]" value="1" class="checkbox nomargin"></label>
						</td>
						<td>
							<input name="rego[100][set_employee]" type="hidden" value="0"  />
							<label><input <? if($standard[100]['set_employee']){echo 'checked';}?> type="checkbox" name="rego[100][set_employee]" value="1" class="checkbox nomargin"></label>
						</td>
						<td>
							<input name="rego[200][set_employee]" type="hidden" value="0"  />
							<label><input <? if($standard[200]['set_employee']){echo 'checked';}?> type="checkbox" name="rego[200][set_employee]" value="1" class="checkbox nomargin"></label>
						</td>
						<td>
							<input name="rego[300][set_employee]" type="hidden" value="0"  />
							<label><input <? if($standard[300]['set_employee']){echo 'checked';}?> type="checkbox" name="rego[300][set_employee]" value="1" class="checkbox nomargin"></label>
						</td>
						<td>
							<input name="rego[400][set_employee]" type="hidden" value="0"  />
							<label><input <? if($standard[400]['set_employee']){echo 'checked';}?> type="checkbox" name="rego[400][set_employee]" value="1" class="checkbox nomargin"></label>
						</td>
						<td></td>
					</tr>
<!--					<tr>
						<th class="tal">Payroll settings</th>
						<td>
							<input name="rego[0][set_payroll]" type="hidden" value="0"  />
							<label><input <? if($standard[0]['set_payroll']){echo 'checked';}?> type="checkbox" name="rego[0][set_payroll]" value="1" class="checkbox nomargin"></label>
						</td>
						<td>
							<input name="rego[10][set_payroll]" type="hidden" value="0"  />
							<label><input <? if($standard[10]['set_payroll']){echo 'checked';}?> type="checkbox" name="rego[10][set_payroll]" value="1" class="checkbox nomargin"></label>
						</td>
						<td>
							<input name="rego[20][set_payroll]" type="hidden" value="0"  />
							<label><input <? if($standard[20]['set_payroll']){echo 'checked';}?> type="checkbox" name="rego[20][set_payroll]" value="1" class="checkbox nomargin"></label>
						</td>
						<td>
							<input name="rego[50][set_payroll]" type="hidden" value="0"  />
							<label><input <? if($standard[50]['set_payroll']){echo 'checked';}?> type="checkbox" name="rego[50][set_payroll]" value="1" class="checkbox nomargin"></label>
						</td>
						<td>
							<input name="rego[100][set_payroll]" type="hidden" value="0"  />
							<label><input <? if($standard[100]['set_payroll']){echo 'checked';}?> type="checkbox" name="rego[100][set_payroll]" value="1" class="checkbox nomargin"></label>
						</td>
						<td>
							<input name="rego[200][set_payroll]" type="hidden" value="0"  />
							<label><input <? if($standard[200]['set_payroll']){echo 'checked';}?> type="checkbox" name="rego[200][set_payroll]" value="1" class="checkbox nomargin"></label>
						</td>
						<td>
							<input name="rego[300][set_payroll]" type="hidden" value="0"  />
							<label><input <? if($standard[300]['set_payroll']){echo 'checked';}?> type="checkbox" name="rego[300][set_payroll]" value="1" class="checkbox nomargin"></label>
						</td>
						<td>
							<input name="rego[400][set_payroll]" type="hidden" value="0"  />
							<label><input <? if($standard[400]['set_payroll']){echo 'checked';}?> type="checkbox" name="rego[400][set_payroll]" value="1" class="checkbox nomargin"></label>
						</td>
						<td></td>
					</tr>
-->
				<!--LEAVE MODULE //////////////////////////////////////////////////////////////////// --> 
					<tr style="border-top:2px solid #ddd">
						<th class="tal" style="font-weight:600; vertical-align:baseline; width:1px"><?=$lng['Leave module']?></th>
						<th class="tal"><?=$lng['All']?></th>
						<td>
							<input name="rego[0][leave]" type="hidden" value="0"  />
							<label><input <? if($standard[0]['leave']){echo 'checked';}?> type="checkbox" name="rego[0][leave]" value="1" class="checkbox nomargin"></label>
						</td>
						<td>
							<input name="rego[10][leave]" type="hidden" value="0"  />
							<label><input <? if($standard[10]['leave']){echo 'checked';}?> type="checkbox" name="rego[10][leave]" value="1" class="checkbox nomargin"></label>
						</td>
						<td>
							<input name="rego[20][leave]" type="hidden" value="0"  />
							<label><input <? if($standard[20]['leave']){echo 'checked';}?> type="checkbox" name="rego[20][leave]" value="1" class="checkbox nomargin"></label>
						</td>
						<td>
							<input name="rego[50][leave]" type="hidden" value="0"  />
							<label><input <? if($standard[50]['leave']){echo 'checked';}?> type="checkbox" name="rego[50][leave]" value="1" class="checkbox nomargin"></label>
						</td>
						<td>
							<input name="rego[100][leave]" type="hidden" value="0"  />
							<label><input <? if($standard[100]['leave']){echo 'checked';}?> type="checkbox" name="rego[100][leave]" value="1" class="checkbox nomargin"></label>
						</td>
						<td>
							<input name="rego[200][leave]" type="hidden" value="0"  />
							<label><input <? if($standard[200]['leave']){echo 'checked';}?> type="checkbox" name="rego[200][leave]" value="1" class="checkbox nomargin"></label>
						</td>
						<td>
							<input name="rego[300][leave]" type="hidden" value="0"  />
							<label><input <? if($standard[300]['leave']){echo 'checked';}?> type="checkbox" name="rego[300][leave]" value="1" class="checkbox nomargin"></label>
						</td>
						<td>
							<input name="rego[400][leave]" type="hidden" value="0"  />
							<label><input <? if($standard[400]['leave']){echo 'checked';}?> type="checkbox" name="rego[400][leave]" value="1" class="checkbox nomargin"></label>
						</td>
						<td></td>
					</tr>

				<!--TIME MODULE //////////////////////////////////////////////////////////////////// --> 
					<tr style="border-top:2px solid #ddd">
						<th class="tal" style="font-weight:600; vertical-align:baseline; width:1px"><?=$lng['Time module']?></th>
						<th class="tal"><?=$lng['All']?></th>
						<td>
							<input name="rego[0][time]" type="hidden" value="0"  />
							<label><input <? if($standard[0]['time']){echo 'checked';}?> type="checkbox" name="rego[0][time]" value="1" class="checkbox nomargin"></label>
						</td>
						<td>
							<input name="rego[10][time]" type="hidden" value="0"  />
							<label><input <? if($standard[10]['time']){echo 'checked';}?> type="checkbox" name="rego[10][time]" value="1" class="checkbox nomargin"></label>
						</td>
						<td>
							<input name="rego[20][time]" type="hidden" value="0"  />
							<label><input <? if($standard[20]['time']){echo 'checked';}?> type="checkbox" name="rego[20][time]" value="1" class="checkbox nomargin"></label>
						</td>
						<td>
							<input name="rego[50][time]" type="hidden" value="0"  />
							<label><input <? if($standard[50]['time']){echo 'checked';}?> type="checkbox" name="rego[50][time]" value="1" class="checkbox nomargin"></label>
						</td>
						<td>
							<input name="rego[100][time]" type="hidden" value="0"  />
							<label><input <? if($standard[100]['time']){echo 'checked';}?> type="checkbox" name="rego[100][time]" value="1" class="checkbox nomargin"></label>
						</td>
						<td>
							<input name="rego[200][time]" type="hidden" value="0"  />
							<label><input <? if($standard[200]['time']){echo 'checked';}?> type="checkbox" name="rego[200][time]" value="1" class="checkbox nomargin"></label>
						</td>
						<td>
							<input name="rego[300][time]" type="hidden" value="0"  />
							<label><input <? if($standard[300]['time']){echo 'checked';}?> type="checkbox" name="rego[300][time]" value="1" class="checkbox nomargin"></label>
						</td>
						<td>
							<input name="rego[400][time]" type="hidden" value="0"  />
							<label><input <? if($standard[400]['time']){echo 'checked';}?> type="checkbox" name="rego[400][time]" value="1" class="checkbox nomargin"></label>
						</td>
						<td></td>
					</tr>

				<!--BENEFITS & EXPENSES //////////////////////////////////////////////////////////////////// --> 
					<tr style="border-top:2px solid #ddd">
						<th class="tal" style="font-weight:600; vertical-align:baseline; width:1px"><?=$lng['Benefits & Expences']?></th>
						<th class="tal"><?=$lng['All']?></th>
						<td>
							<input name="rego[0][expenses]" type="hidden" value="0"  />
							<label><input <? if($standard[0]['expenses']){echo 'checked';}?> type="checkbox" name="rego[0][expenses]" value="1" class="checkbox nomargin"></label>
						</td>
						<td>
							<input name="rego[10][expenses]" type="hidden" value="0"  />
							<label><input <? if($standard[10]['expenses']){echo 'checked';}?> type="checkbox" name="rego[10][expenses]" value="1" class="checkbox nomargin"></label>
						</td>
						<td>
							<input name="rego[20][expenses]" type="hidden" value="0"  />
							<label><input <? if($standard[20]['expenses']){echo 'checked';}?> type="checkbox" name="rego[20][expenses]" value="1" class="checkbox nomargin"></label>
						</td>
						<td>
							<input name="rego[50][expenses]" type="hidden" value="0"  />
							<label><input <? if($standard[50]['expenses']){echo 'checked';}?> type="checkbox" name="rego[50][expenses]" value="1" class="checkbox nomargin"></label>
						</td>
						<td>
							<input name="rego[100][expenses]" type="hidden" value="0"  />
							<label><input <? if($standard[100]['expenses']){echo 'checked';}?> type="checkbox" name="rego[100][expenses]" value="1" class="checkbox nomargin"></label>
						</td>
						<td>
							<input name="rego[200][expenses]" type="hidden" value="0"  />
							<label><input <? if($standard[200]['expenses']){echo 'checked';}?> type="checkbox" name="rego[200][expenses]" value="1" class="checkbox nomargin"></label>
						</td>
						<td>
							<input name="rego[300][expenses]" type="hidden" value="0"  />
							<label><input <? if($standard[300]['expenses']){echo 'checked';}?> type="checkbox" name="rego[300][expenses]" value="1" class="checkbox nomargin"></label>
						</td>
						<td>
							<input name="rego[400][expenses]" type="hidden" value="0"  />
							<label><input <? if($standard[400]['expenses']){echo 'checked';}?> type="checkbox" name="rego[400][expenses]" value="1" class="checkbox nomargin"></label>
						</td>
						<td></td>
					</tr>

				<!--PROJECTS //////////////////////////////////////////////////////////////////// --> 
					<tr style="border-top:2px solid #ddd">
						<th class="tal" style="font-weight:600; vertical-align:baseline; width:1px"><?=$lng['Projects']?></th>
						<th class="tal"><?=$lng['All']?></th>
						<td>
							<input name="rego[0][projects]" type="hidden" value="0"  />
							<label><input <? if($standard[0]['projects']){echo 'checked';}?> type="checkbox" name="rego[0][projects]" value="1" class="checkbox nomargin"></label>
						</td>
						<td>
							<input name="rego[10][projects]" type="hidden" value="0"  />
							<label><input <? if($standard[10]['projects']){echo 'checked';}?> type="checkbox" name="rego[10][projects]" value="1" class="checkbox nomargin"></label>
						</td>
						<td>
							<input name="rego[20][projects]" type="hidden" value="0"  />
							<label><input <? if($standard[20]['projects']){echo 'checked';}?> type="checkbox" name="rego[20][projects]" value="1" class="checkbox nomargin"></label>
						</td>
						<td>
							<input name="rego[50][projects]" type="hidden" value="0"  />
							<label><input <? if($standard[50]['projects']){echo 'checked';}?> type="checkbox" name="rego[50][projects]" value="1" class="checkbox nomargin"></label>
						</td>
						<td>
							<input name="rego[100][projects]" type="hidden" value="0"  />
							<label><input <? if($standard[100]['projects']){echo 'checked';}?> type="checkbox" name="rego[100][projects]" value="1" class="checkbox nomargin"></label>
						</td>
						<td>
							<input name="rego[200][projects]" type="hidden" value="0"  />
							<label><input <? if($standard[200]['projects']){echo 'checked';}?> type="checkbox" name="rego[200][projects]" value="1" class="checkbox nomargin"></label>
						</td>
						<td>
							<input name="rego[300][projects]" type="hidden" value="0"  />
							<label><input <? if($standard[300]['projects']){echo 'checked';}?> type="checkbox" name="rego[300][projects]" value="1" class="checkbox nomargin"></label>
						</td>
						<td>
							<input name="rego[400][projects]" type="hidden" value="0"  />
							<label><input <? if($standard[400]['projects']){echo 'checked';}?> type="checkbox" name="rego[400][projects]" value="1" class="checkbox nomargin"></label>
						</td>
						<td></td>
					</tr>

				<!--MAX EMPLOYEES //////////////////////////////////////////////////////////////////// --> 
					<tr style="border-top:2px solid #ddd">
						<th class="tal" style="font-weight:600; vertical-align:baseline; width:1px"><?=$lng['Max employees']?></th>
						<th class="tal"></th>
						<td class="nopad"><input class="numeric sel tac" name="rego[0][max_employees]" type="text" value="<?=$standard[0]['max_employees']?>"  /></td>
						<td class="nopad"><input class="numeric sel tac" name="rego[10][max_employees]" type="text" value="<?=$standard[10]['max_employees']?>"  /></td>
						<td class="nopad"><input class="numeric sel tac" name="rego[20][max_employees]" type="text" value="<?=$standard[20]['max_employees']?>"  /></td>
						<td class="nopad"><input class="numeric sel tac" name="rego[50][max_employees]" type="text" value="<?=$standard[50]['max_employees']?>"  /></td>
						<td class="nopad"><input class="numeric sel tac" name="rego[100][max_employees]" type="text" value="<?=$standard[100]['max_employees']?>"  /></td>
						<td class="nopad"><input class="numeric sel tac" name="rego[200][max_employees]" type="text" value="<?=$standard[200]['max_employees']?>"  /></td>
						<td class="nopad"><input class="numeric sel tac" name="rego[300][max_employees]" type="text" value="<?=$standard[300]['max_employees']?>"  /></td>
						<td class="nopad"><input class="numeric sel tac" name="rego[400][max_employees]" type="text" value="<?=$standard[400]['max_employees']?>"  /></td>
						<td></td>
					</tr>

				<!--MOBILE ACCESS //////////////////////////////////////////////////////////////////// --> 
					<tr style="border-top:2px solid #ddd">
						<th class="tal" style="font-weight:600; vertical-align:baseline; width:1px"><?=$lng['Mobile access']?></th>
						<th class="tal"></th>
						<td class="nopad tac">
							<select name="rego[0][mobile]" style="width:auto">
								<? foreach($noyes01 as $k=>$v){ ?>
								<option <? if($standard[0]['mobile'] == $k){echo 'selected';}?> value="<?=$k?>"><?=$v?></option>
								<? } ?>
							</select>
						</td>
						<td class="nopad tac">
							<select name="rego[10][mobile]" style="width:auto">
								<? foreach($noyes01 as $k=>$v){ ?>
								<option <? if($standard[10]['mobile'] == $k){echo 'selected';}?> value="<?=$k?>"><?=$v?></option>
								<? } ?>
							</select>
						</td>
						<td class="nopad tac">
							<select name="rego[20][mobile]" style="width:auto">
								<? foreach($noyes01 as $k=>$v){ ?>
								<option <? if($standard[20]['mobile'] == $k){echo 'selected';}?> value="<?=$k?>"><?=$v?></option>
								<? } ?>
							</select>
						</td>
						<td class="nopad tac">
							<select name="rego[50][mobile]" style="width:auto">
								<? foreach($noyes01 as $k=>$v){ ?>
								<option <? if($standard[50]['mobile'] == $k){echo 'selected';}?> value="<?=$k?>"><?=$v?></option>
								<? } ?>
							</select>
						</td>
						<td class="nopad tac">
							<select name="rego[100][mobile]" style="width:auto">
								<? foreach($noyes01 as $k=>$v){ ?>
								<option <? if($standard[100]['mobile'] == $k){echo 'selected';}?> value="<?=$k?>"><?=$v?></option>
								<? } ?>
							</select>
						</td>
						<td class="nopad tac">
							<select name="rego[200][mobile]" style="width:auto">
								<? foreach($noyes01 as $k=>$v){ ?>
								<option <? if($standard[200]['mobile'] == $k){echo 'selected';}?> value="<?=$k?>"><?=$v?></option>
								<? } ?>
							</select>
						</td>
						<td class="nopad tac">
							<select name="rego[300][mobile]" style="width:auto">
								<? foreach($noyes01 as $k=>$v){ ?>
								<option <? if($standard[300]['mobile'] == $k){echo 'selected';}?> value="<?=$k?>"><?=$v?></option>
								<? } ?>
							</select>
						</td>
						<td class="nopad tac">
							<select name="rego[400][mobile]" style="width:auto">
								<? foreach($noyes01 as $k=>$v){ ?>
								<option <? if($standard[400]['mobile'] == $k){echo 'selected';}?> value="<?=$k?>"><?=$v?></option>
								<? } ?>
							</select>
						</td>
						<td></td>
					</tr>

				</tbody>
			</table>	
			
			<div style="height:10px"></div>
			<button id="submitbtn" class="btn btn-primary btn-fl" type="submit"><i class="fa fa-save fa-mr"></i> <?=$lng['Update']?></button>
			<div style="clear:both"></div>
			<div style="padding:0 0 0 20px" id="dump2"></div>
		</form>



   </div>
   
<script>

	$(document).ready(function() {
	
		$("#standardForm").submit(function(e){ 
			e.preventDefault();
			$("#submitbtn i").removeClass('fa-save').addClass('fa-refresh fa-spin');
			var data = $(this).serialize();
			$.ajax({
				type: 'POST',
				url: AROOT+"ajax/update_standards.php",
				data: data,
				success: function(result){
					//$("#dump2").html(result); return false
					if(result == 'success'){
						$("body").overhang({
							type: "success",
							message: '<i class="fa fa-check"></i>&nbsp;&nbsp;Data updated successfuly',
							duration: 2,
						})
					}else{
						$("body").overhang({
							type: "warn",
							message: '<i class="fa fa-exclamation-triangle"></i>&nbsp;&nbsp;<?=$lng['Error']?> : '+result,
							duration: 4,
						})
					}
					setTimeout(function(){
						$("#submitbtn i").removeClass('fa-refresh fa-spin').addClass('fa-save');
						$("#submitbtn").removeClass('flash');
						$("#sAlert").fadeOut(200);
					},300);
				},
				error:function (xhr, ajaxOptions, thrownError){
					$("body").overhang({
						type: "error",
						message: '<i class="fa fa-exclamation-triangle"></i>&nbsp;&nbsp;<?=$lng['Sorry but someting went wrong']?> <b><?=$lng['Error']?></b> : '+thrownError,
						duration: 4,
					})
				}
			});
		});
		
		setTimeout(function(){
			$('body').on('change', '#standardForm input', function (e) {
				$("#submitbtn").addClass('flash');
				$("#sAlert").fadeIn(200);
			});	
		},500);

	});

</script>
						




























