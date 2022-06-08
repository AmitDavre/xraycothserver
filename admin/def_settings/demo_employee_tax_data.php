
<style>
.basicTable tbody td {
	vertical-align: top !important;
	border-right:1px #eee solid;
}
table.basicTable.thwrap tbody th {
	white-space:normal !important;
}
.basicTable tbody td.info {
	white-space:normal;
	color:#999;
	font-style:normal;
	font-size:12px;
	vertical-align:baseline;
}
.basicTable tbody td.info span {
	color:#c00;
	font-weight:600;
}
.basicTable tbody td input.error {
	color:#fff;
	font-weight:600;
	background:#c00 !important; 
}
.totals_scroll {
	position:absolute; 
	top:70px; 
	right:40px; 
	display:block; 
	color:#b00; 
	font-weight:600; 
	font-size:14px; 
	padding:2px 10px; 
	background:#eee; 
}
</style>
		
		<table class="basicTable editTable thwrap" id="taxTable" border="0" style="margin-top:3px">
			<thead>
				<tr style="line-height:100%">
					<th class="tac" style="min-width:200px"><?=$lng['Description']?></th>
					<th style="min-width:60px" class="tac"><?=$lng['Number']?></th>
					<th style="min-width:100px" class="tac"><?=$lng['Baht']?></th>
					<th style="width:80%"><?=$lng['Information conditions']?></th>
				</tr>
			</thead>
			<tbody>
			<tr>
				<th style="width:5%"><?=$lng['Spouse care']?></th>
				<td>
					<select class="calcTax" name="tax_spouse" id="spouse_allow">
						<?php foreach($yesno as $k=>$v){
								echo '<option ';
								if(strtoupper($data['tax_spouse'])==$k){echo 'selected';}
								echo ' value="'.$k.'">'.$v.'</option>';
						} ?>
					</select>
				</td>
				<td><input tabindex="-1" readonly class="tar nofocus" type="text" name="tax_allow_spouse" id="spouse_allowance" placeholder="..." value="<?=$data['tax_allow_spouse']?>"></td>
				<td class="info pad410"><?=$tax_info['spouse_allowance']?></td>
			</tr>
			<tr>
				<th><?=$lng['Parents care']?></th>
				<td><input class="float21 sel tar" type="text" name="tax_parents" id="parents_allow" placeholder="..." value="<?=$data['tax_parents']?>"></td>
				<td><input tabindex="-1" readonly class="tar nofocus" type="text" name="tax_allow_parents" id="parents_allowance" placeholder="..." value="<?=$data['tax_allow_parents']?>"></td>
				<td class="info" id="info_parents_allow"><?=$tax_info['parents_allow']?></td>
			</tr>
			<tr>
				<th><?=$lng['Child care - biological']?></th>
				<td><input class="float21 sel tar" type="text" name="tax_child_bio" id="child_allow" placeholder="..." value="<?=$data['tax_child_bio']?>"></td>
				<td><input tabindex="-1" readonly class="tar nofocus" type="text" name="tax_allow_child_bio" id="child_allowance" placeholder="..." value="<?=$data['tax_allow_child_bio']?>"></td>
				<td class="info"><?=$tax_info['child_allow']?></td>
			</tr>
			
			<tr>
				<th><?=$lng['Child birth (Baby bonus)']?></th>
				<td></td>
				<td><input class="numeric sel tar" type="text" name="tax_allow_child_birth" id="child_birth_bonus" placeholder="..." value="<?=$data['tax_allow_child_birth']?>"></td>
				<td class="info" id="info_child_bonus"><?=$tax_info['child_birth_bonus']?></td>
			</tr>
			
			<tr>
				<th><?=$lng['Own health insurance']?></th>
				<td></td>
				<td><input class="numeric sel tar" id="own_health_insurance" type="text" name="tax_allow_own_health" placeholder="..." value="<?=$data['tax_allow_own_health']?>"></td>
				<td class="info" id="info_own_health_insurance"><?=$tax_info['own_health_insurance']?></td>
			</tr>
			<tr>
				<th><?=$lng['Home loan interest']?></th>
				<td></td>
				<td><input class="numeric sel tar" type="text" id="home_loan_interest" name="tax_allow_home_loan_interest" placeholder="..." value="<?=$data['tax_allow_home_loan_interest']?>"></td>
				<td class="info" id="info_home_loan_interest"><?=$tax_info['home_loan_interest']?></td>
			</tr>
			
			<tr>
				<th><?=$lng['Donation charity']?></th>
				<td></td>
				<td><input class="numeric sel tar" type="text" id="donation_charity" name="tax_allow_donation_charity" placeholder="..." value="<?=$data['tax_allow_donation_charity']?>"></td>
				<td class="info" id="info_donation_charity"><?=$tax_info['donation_charity']?></td>
			</tr>
			
			<tr>
				<th><?=$lng['Donation education']?></th>
				<td></td>
				<td><input class="numeric sel tar" type="text" id="donation_education" name="tax_allow_donation_education" placeholder="..." value="<?=$data['tax_allow_donation_education']?>"></td>
				<td class="info" id="info_donation_education"><?=$tax_info['donation_education']?></td>
			</tr>
			
			<tr>
				<th><?=$lng['First home buyer']?></th>
				<td></td>
				<td><input class="numeric sel tar" type="text" id="first_home_allowance" name="tax_allow_first_home" placeholder="..." value="<?=$data['tax_allow_first_home']?>"></td>
				<td class="info" id="info_first_home_allow"><?=$tax_info['first_home_allowance']?></td>
			</tr>
			
			<tr>
				<th><?=$lng['Year-end shopping']?></th>
				<td></td>
				<td><input class="numeric sel tar" type="text" id="year_end_shop_allowance" name="tax_allow_year_end_shopping" placeholder="..." value="<?=$data['tax_allow_year_end_shopping']?>"></td>
				<td class="info" id="info_year_end_shop_allow"><?=$tax_info['year_end_shop_allowance']?></td>
			</tr>
			
			<tr style="background:#eee">
				<th><?=$lng['Total deductions']?></th>
				<td></td>
				<td id="total_deductions" class="tar pad410" style="font-weight:600"><?=number_format($data['emp_tax_deductions'],2)?>
					<input type="hidden" id="emp_tax_deductions" name="emp_tax_deductions" value="<?=$data['emp_tax_deductions']?>" />
				</td>
				<td></td>
			</tr>
			
			</tbody>
		</table>
		
		
<script>
		function calculateDeductions(){
			var total = 0;
			total += (isNaN(parseInt($('#spouse_allowance').val())) ? 0 : parseInt($('#spouse_allowance').val()));
			total += (isNaN(parseInt($('#parents_allowance').val())) ? 0 : parseInt($('#parents_allowance').val()));
			total += (isNaN(parseInt($('#child_allowance').val())) ? 0 : parseInt($('#child_allowance').val()));
			total += (isNaN(parseInt($('#child_birth_bonus').val())) ? 0 : parseInt($('#child_birth_bonus').val()));
			total += (isNaN(parseInt($('#own_health_insurance').val())) ? 0 : parseInt($('#own_health_insurance').val()));
			total += (isNaN(parseInt($('#home_loan_interest').val())) ? 0 : parseInt($('#home_loan_interest').val()));
			total += (isNaN(parseInt($('#donation_charity').val())) ? 0 : parseInt($('#donation_charity').val()));
			total += (isNaN(parseInt($('#donation_education').val())) ? 0 : parseInt($('#donation_education').val()));
			total += (isNaN(parseInt($('#first_home_allowance').val())) ? 0 : parseInt($('#first_home_allowance').val()));
			total += (isNaN(parseInt($('#year_end_shop_allowance').val())) ? 0 : parseInt($('#year_end_shop_allowance').val()));

			$('#total_deductions').html(total.format(2));
			$('#emp_tax_deductions').val(total);
		}
	$(document).ready(function() {
		//alert('isNaN')	
		
		var tax_settings = <?=json_encode($tax_settings)?>;
		var tax_info = <?=json_encode($tax_info)?>;
		var tax_err = <?=json_encode($tax_err)?>;
		var timeout = 6000;
		var basic_salary = <?=json_encode($data['base_salary'])?>;
		
		calculateDeductions()
		
		$('#spouse_allow').on('change', function(){
			if($(this).val()=='Y'){
				$('#spouse_allowance').val(60000)
			}else{
				$('#spouse_allowance').val(0)
			}
			calculateDeductions()
		})
		$('#parents_allow').on('change', function(){
			var val = this.value
			$('#parents_allowance').val(val * tax_settings.parents_allowance)
			calculateDeductions()
		})
		$('#child_allow').on('change', function(){
			var ch1 = $('#child_allow').val()
			$('#child_allowance').val(ch1 * tax_settings.child_allowance)
			calculateDeductions()
		})
		
		$('#child_birth_bonus').on('change', function(){
			calculateDeductions()
		})
		
		$('#own_health_insurance').on('change', function(){
			calculateDeductions()
		})
		
		$('#home_loan_interest').on('change', function(){
			calculateDeductions()
		})
		$('#donation_charity').on('change', function(){
			calculateDeductions()
		})
		$('#donation_education').on('change', function(){
			calculateDeductions()
		})
		$('#first_home_allowance').on('change', function(){
			calculateDeductions()
		})
		$('#year_end_shop_allowance').on('change', function(){
			calculateDeductions()
		})
		
  });

</script>		
		


		
		
		
		
		
		
		
		
		
		
		
		
		
