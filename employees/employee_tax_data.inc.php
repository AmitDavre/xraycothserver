
		<i class="man">*</i><b style="color:#b00"><?=$lng['Calculated by system from last payroll calculation']?></b>
		<table class="basicTable editTable" id="taxTable" border="0" style="margin-top:3px">
			<thead>
				<tr style="line-height:100%">
					<th class="tac tax-table"><?=$lng['Description']?></th>
					<th style="min-width:60px" class="tac"><?=$lng['Number']?></th>
					<th style="min-width:90px" class="tac"><?=$lng['Baht']?></th>
					<th class="hide-tax"><?=$lng['Information conditions']?></th>
				</tr>
			</thead>
			<tbody>
			<tr>
				<th><i class="man">*</i><?=$lng['Standard deduction']?></th>
				<td></td>
				<td><i><input tabindex="-1" style="color:#999;" readonly class="tar nofocus" type="text" name="tax_standard_deduction" id="standard_deduction" value="<?=$data['tax_standard_deduction']?>"></i></td>
				<td class="info" style="width:80%;color:#a00" id="info_standard_deduction"><?=$tax_info['standard_deduction']?></td>
			</tr>
			<tr>
				<th><i class="man">*</i><?=$lng['Personal care']?></th>
				<td></td>
				<td><i><input tabindex="-1" style="color:#999;" readonly class="tar nofocus" type="text" name="tax_personal_allowance" id="personal_allowance" placeholder="..." value="<?=$data['tax_personal_allowance']?>"><i></td>
				<td class="info pad410"></td>
			</tr>
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
				<th><?=$lng['Parents in law care']?></th>
				<td><input class="numeric sel tar" maxlength="1" type="text" name="tax_parents_inlaw" id="parents_inlaw_allow" placeholder="..." value="<?=$data['tax_parents_inlaw']?>"></td>
				<td><input tabindex="-1" readonly class="tar nofocus" type="text" name="tax_allow_parents_inlaw" id="parents_inlaw_allowance" placeholder="..." value="<?=$data['tax_allow_parents_inlaw']?>"></td>
				<td class="info" id="info_parents_inlaw_allow"><?=$tax_info['parents_allow']?></td>
			</tr>
			<tr>
				<th><?=$lng['Care disabled person']?></th>
				<td><input class="float21 sel tar" type="text" name="tax_disabled_person" id="disabled_allow" placeholder="..." value="<?=$data['tax_disabled_person']?>"></td>
				<td><input tabindex="-1" readonly class="tar nofocus" type="text" name="tax_allow_disabled_person" id="disabled_allowance" placeholder="..." value="<?=$data['tax_allow_disabled_person']?>"></td>
				<td class="info"><?=$tax_info['disabled_allow']?></td>
			</tr>
			
			<tr style="border-bottom:1px #ddd solid"><td colspan="4" style="height:15px"></td></tr>
			</tbody>
			<tbody style="border:1px #ddd solid">
			
			<tr>
				<th><?=$lng['Child care - biological']?></th>
				<td><input class="float21 sel tar" type="text" name="tax_child_bio" id="child_allow" placeholder="..." value="<?=$data['tax_child_bio']?>"></td>
				<td><input tabindex="-1" readonly class="tar nofocus" type="text" name="tax_allow_child_bio" id="child_allowance" placeholder="..." value="<?=$data['tax_allow_child_bio']?>"></td>
				<td class="info"><?=$tax_info['child_allow']?></td>
			</tr>
			
			<tr>
				<th><?=$lng['Child care - biological 2018/19/20']?></th>
				<td><input class="float21 sel tar" type="text" name="tax_child_bio_2018" id="child_allow_2018" placeholder="..." value="<?=$data['tax_child_bio_2018']?>"></td>
				<td><input tabindex="-1" readonly class="tar nofocus" type="text" name="tax_allow_child_bio_2018" id="child_allowance_2018" placeholder="..." value="<?=$data['tax_allow_child_bio_2018']?>"></td>
				<td class="info"><?=$tax_info['child_allow_2018']?></td>
			</tr>
			
			<tr>
				<th><?=$lng['Child care - adopted']?></th>
				<td><input class="float21 sel tar" type="text" name="tax_child_adopted" id="child_adopt_allow" placeholder="..." value="<?=$data['tax_child_adopted']?>"></td>
				<td><input tabindex="-1" readonly class="tar nofocus" type="text" name="tax_allow_child_adopted" id="child_adopt_allowance" placeholder="..." value="<?=$data['tax_allow_child_adopted']?>"></td>
				<td class="info" id="info_child_adopt_allow"><?=$tax_info['child_adopt_allow']?></td>
			</tr>
			
			<tr style="border-bottom:1px #ddd solid">
				<th><?=$lng['Child birth (Baby bonus)']?></th>
				<td></td>
				<td><input class="numeric sel tar" type="text" name="tax_allow_child_birth" id="child_birth_bonus" placeholder="..." value="<?=$data['tax_allow_child_birth']?>"></td>
				<td class="info" id="info_child_bonus"><?=$tax_info['child_birth_bonus']?></td>
			</tr>
			
			</tbody>
			
			<tr style="border-bottom:1px #ddd solid"><td colspan="4" style="height:15px"></td></tr>
			</tbody>
			<tbody style="border:1px #ddd solid">
			<tr>
				<th><?=$lng['Own health insurance']?></th>
				<td></td>
				<td><input class="numeric sel tar" id="own_health_insurance" type="text" name="tax_allow_own_health" placeholder="..." value="<?=$data['tax_allow_own_health']?>"></td>
				<td class="info" id="info_own_health_insurance"><?=$tax_info['own_health_insurance']?></td>
			</tr>
			<tr style="border-bottom:1px #ddd solid">
				<th><?=$lng['Own life insurance']?></th>
				<td></td>
				<td><input class="numeric sel tar" id="own_life_insurance" type="text" name="tax_allow_own_life_insurance" placeholder="..." value="<?=$data['tax_allow_own_life_insurance']?>"></td>
				<td class="info" id="info_own_life_insurance"><?=$tax_info['own_life_insurance']?></td>
			</tr>
			<tr style="border-bottom:1px #ddd solid">
				<th style="color:#900; font-weight:400"><?=$lng['Subtotal']?></th>
				<td></td>
				<td><input tabindex="-1" readonly class="tar nofocus" type="text" id="total_own_health_life" value="<?php //=$data['total_own_health_life']?>"></td>
				<td class="info" id="info_health_life_insurance"></td>
			</tr>
			</tbody>
			<tbody>
			<tr><td colspan="4" style="height:15px"></td></tr>
			
			<tr>
				<th><?=$lng['Health insurance parents']?></th>
				<td><!--<input class="float21 sel tar" type="text" name="tax_health_parents" id="health_insurance_par" placeholder="..." value="<? //=$data['tax_health_parents']?>">--></td>
				<td><input class="tar sel" type="text" name="tax_allow_health_parents" id="health_insurance_parent" placeholder="..." value="<?=$data['tax_allow_health_parents']?>"></td>
				<td class="info" id="info_health_insurance_parent"><?=$tax_info['health_insurance_par']?></td>
			</tr>
			<tr>
				<th><?=$lng['Life insurance spouse']?></th>
				<td></td>
				<td><input class="numeric sel tar" type="text" name="tax_allow_life_insurance_spouse" id="life_allow_insurance_spouse" placeholder="..." value="<?=$data['tax_allow_life_insurance_spouse']?>"></td>
				<td class="info" id="info_life_insurance_spouse"><?=$tax_info['life_insurance_spouse']?></td>
			</tr>
			
			<tr style="border-bottom:1px #ddd solid"><td colspan="4" style="height:15px"></td></tr>
			</tbody>
			<tbody style="border:1px #ddd solid">
			<tr>
				
				<th><?=$lng['Pension fund']?></th>
				<td></td>
				<td><input class="numeric sel tar" type="text" id="pension_fund_allowance" name="tax_allow_pension_fund" placeholder="..." value="<?=$data['tax_allow_pension_fund']?>"></td>
				<td class="info" id="info_pension_fund_allow"><?=$tax_info['pension_fund_allowance']?></td>
			</tr>
			
			<tr>
				<th><i class="man">*</i><?=$lng['Provident fund']?></th>
				<td></td>
				<td><i><input tabindex="-1" style="color:#999;" readonly class="tar nofocus" type="text" id="tax_allow_pvf" name="tax_allow_pvf" placeholder="..." value="<?=$data['tax_allow_pvf']?>"></i></td>
				<td class="info" id="info_provident_fund_allow"><?=$tax_info['provident_fund_allowance']?></td>
			</tr>
			
			<tr>
				<th><?=$lng['NSF']?></th>
				<td></td>
				<td><input class="numeric sel tar" type="text" id="nsf_allowance" name="tax_allow_nsf" placeholder="..." value="<?=$data['tax_allow_nsf']?>"></td>
				<td class="info" id="info_nsf_allow"><?=$tax_info['nsf_allowance']?></td>
			</tr>
			
			<tr style="border-bottom:1px #ddd solid">
				<th><?=$lng['RMF']?></th>
				<td></td>
				<td><input class="numeric sel tar" type="text" id="rmf_allowance" name="tax_allow_rmf" placeholder="..." value="<?=$data['tax_allow_rmf']?>"></td>
				<td class="info" id="info_rmf_allow"><?=$tax_info['rmf_allowance']?></td>
			</tr>
			
			<tr style="border-bottom:1px #ddd solid">
				<th style="color:#900; font-weight:400"><?=$lng['Subtotal']?></th>
				<td></td>
				<td><input tabindex="-1" readonly class="tar nofocus" type="text" id="subtotal2" value="<? //=$data['subtotal2']?>"></td>
				<td class="info" id="info_subtotal2"></td>
			</tr>
			</tbody>
			<tbody>
			
			<tr><td colspan="4" style="height:15px"></td></tr>
			
			<tr>
				<th><i class="man">*</i><?=$lng['Social Security Fund']?></th>
				<td></td>
				<td><i><input style="color:#999;" readonly class="tar nofocus" name="tax_allow_sso" id="tax_allow_sso" type="text" value="<?=$data['tax_allow_sso']?>"></i></td>
				<td class="info" id="info_ltf_deduction"><?=$tax_info['social_security_fund']?></td>
			</tr>
			
			<tr>
				<th><?=$lng['LTF']?></th>
				<td></td>
				<td><input class="numeric sel tar" type="text" id="ltf_deduction" name="tax_allow_ltf" placeholder="..." value="<?=$data['tax_allow_ltf']?>"></td>
				<td class="info" id="info_ltf_deduction"><?=$tax_info['ltf_allowance']?></td>
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
				<th><?=$lng['Donation flooding']?></th>
				<td></td>
				<td><input class="numeric sel tar" type="text" id="donation_flood" name="tax_allow_donation_flood" placeholder="..." value="<?=$data['tax_allow_donation_flood']?>"></td>
				<td class="info" id="info_donation_flood"><?=$tax_info['donation_flood']?></td>
			</tr>
			
			<tr>
				<th><?=$lng['Donation education']?></th>
				<td></td>
				<td><input class="numeric sel tar" type="text" id="donation_education" name="tax_allow_donation_education" placeholder="..." value="<?=$data['tax_allow_donation_education']?>"></td>
				<td class="info" id="info_donation_education"><?=$tax_info['donation_education']?></td>
			</tr>
			
			<tr>
				<th><?=$lng['Exemption disabled person <65 yrs']?></th>
				<td>
					<select class="calcTax" name="tax_exemp_disabled_under" id="exemp_disabled">
						<?php foreach($yesno as $k=>$v){
								echo '<option ';
								if(strtoupper($data['tax_exemp_disabled_under'])==$k){echo 'selected';}
								echo ' value="'.$k.'">'.$v.'</option>';
						} ?>
					</select>
				</td>
				<td><input tabindex="-1" readonly class="tar nofocus" type="text" name="tax_allow_exemp_disabled_under" id="exemp_disabled_under" placeholder="..." value="<?=$data['tax_allow_exemp_disabled_under']?>"></td>
				<td class="info"><?=$tax_info['exemp_disabled_under']?></td>
			</tr>
			
			<tr>
				<th><?=$lng['Exemption tax payer => 65yrs']?></th>
				<td>
					<select class="calcTax" name="tax_exemp_payer_older" id="exemp_payer">
						<?php foreach($yesno as $k=>$v){
								echo '<option ';
								if(strtoupper($data['tax_exemp_payer_older'])==$k){echo 'selected';}
								echo ' value="'.$k.'">'.$v.'</option>';
						} ?>
					</select>
				</td>
				<td><input tabindex="-1" readonly class="tar nofocus" type="text" name="tax_allow_exemp_payer_older" id="exemp_payer_older" placeholder="..." value="<?=$data['tax_allow_exemp_payer_older']?>"></td>
				<td class="info"><?=$tax_info['exemp_payer_older']?></td>
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
			
			<tr>
				<th><?=$lng['Domestic tour']?></th>
				<td></td>
				<td><input class="numeric sel tar" type="text" id="domestic_tour_allowance" name="tax_allow_domestic_tour" placeholder="..." value="<?=$data['tax_allow_domestic_tour']?>"></td>
				<td class="info" id="info_domestic_tour_allow"><?=$tax_info['domestic_tour_allowance']?></td>
			</tr>
			
			<tr>
				<th><?=$lng['Other allowance']?></th>
				<td></td>
				<td><input class="numeric sel tar" type="text" id="other_allowance" name="tax_allow_other" placeholder="..." value="<?=$data['tax_allow_other']?>"></td>
				<td class="info" id="info_other_allowance"><?=$tax_info['other_allowance']?></td>
			</tr>
			
			</tbody>
		</table>
		
		<div class="totals_scroll"><?=$lng['Total deductions']?> 
			<span id="total_deductions"><?=number_format($data['emp_tax_deductions'],2)?></span> THB
			<input type="hidden" id="total_tax_deductions" name="total_tax_deductions" value="" />
			<!--<input type="hidden" id="tax_deductions" value="" />-->
			<input type="hidden" id="emp_tax_deductions" name="emp_tax_deductions" value="<?=$data['emp_tax_deductions']?>" />
		</div>
		
<script>
		function calculateDeductions(){
			//alert('calculateDeductions')
			var total = 0;
			//total += (isNaN(parseInt($('#standard_deduction').val())) ? 0 : parseInt($('#standard_deduction').val()));
			//total += (isNaN(parseInt($('#personal_allowance').val())) ? 0 : parseInt($('#personal_allowance').val()));
			total += (isNaN(parseInt($('#spouse_allowance').val())) ? 0 : parseInt($('#spouse_allowance').val()));
			//alert(total)
			total += (isNaN(parseInt($('#parents_allowance').val())) ? 0 : parseInt($('#parents_allowance').val()));
			//alert(total)
			total += (isNaN(parseInt($('#parents_inlaw_allowance').val())) ? 0 : parseInt($('#parents_inlaw_allowance').val()));
			total += (isNaN(parseInt($('#disabled_allowance').val())) ? 0 : parseInt($('#disabled_allowance').val()));
			total += (isNaN(parseInt($('#child_allowance').val())) ? 0 : parseInt($('#child_allowance').val()));
			total += (isNaN(parseInt($('#child_allowance_2018').val())) ? 0 : parseInt($('#child_allowance_2018').val()));
			total += (isNaN(parseInt($('#child_adopt_allowance').val())) ? 0 : parseInt($('#child_adopt_allowance').val()));
			total += (isNaN(parseInt($('#child_birth_bonus').val())) ? 0 : parseInt($('#child_birth_bonus').val()));
			total += (isNaN(parseInt($('#total_own_health_life').val())) ? 0 : parseInt($('#total_own_health_life').val()));
			//alert(total)
			total += (isNaN(parseInt($('#health_insurance_parent').val())) ? 0 : parseInt($('#health_insurance_parent').val()));
			total += (isNaN(parseInt($('#life_allow_insurance_spouse').val())) ? 0 : parseInt($('#life_allow_insurance_spouse').val()));
			total += (isNaN(parseInt($('#donation_charity').val())) ? 0 : parseInt($('#donation_charity').val()));
			total += (isNaN(parseInt($('#donation_flood').val())) ? 0 : parseInt($('#donation_flood').val()));
			total += (isNaN(parseInt($('#donation_education').val())) ? 0 : parseInt($('#donation_education').val()));
			
			total += (isNaN(parseInt($('#first_home_allowance').val())) ? 0 : parseInt($('#first_home_allowance').val()));
			total += (isNaN(parseInt($('#year_end_shop_allowance').val())) ? 0 : parseInt($('#year_end_shop_allowance').val()));
			total += (isNaN(parseInt($('#domestic_tour_allowance').val())) ? 0 : parseInt($('#domestic_tour_allowance').val()));
			
			total += (isNaN(parseInt($('#exemp_disabled_under').val())) ? 0 : parseInt($('#exemp_disabled_under').val()));
			total += (isNaN(parseInt($('#exemp_payer_older').val())) ? 0 : parseInt($('#exemp_payer_older').val()));
			
			total += (isNaN(parseInt($('#ltf_deduction').val())) ? 0 : parseInt($('#ltf_deduction').val()));
			total += (isNaN(parseInt($('#home_loan_interest').val())) ? 0 : parseInt($('#home_loan_interest').val()));
			
			total += (isNaN(parseInt($('#pension_fund_allowance').val())) ? 0 : parseInt($('#pension_fund_allowance').val()));
			total += (isNaN(parseInt($('#nsf_allowance').val())) ? 0 : parseInt($('#nsf_allowance').val()));
			total += (isNaN(parseInt($('#rmf_allowance').val())) ? 0 : parseInt($('#rmf_allowance').val()));

			var sub2 = 0;
			sub2 += parseInt($('#pension_fund_allowance').val());
			sub2 += parseInt($('#tax_allow_pvf').val());
			sub2 += parseInt($('#nsf_allowance').val());
			sub2 += parseInt($('#rmf_allowance').val());
			$('#subtotal2').val(sub2)
			
			total += (isNaN(parseInt($('#other_allowance').val())) ? 0 : parseInt($('#other_allowance').val()));

			//alert(total)
			$('#emp_tax_deductions').val(total)
			total += (isNaN(parseInt($('#standard_deduction').val())) ? 0 : parseInt($('#standard_deduction').val()));
			total += (isNaN(parseInt($('#personal_allowance').val())) ? 0 : parseInt($('#personal_allowance').val()));
			total += (isNaN(parseInt($('#tax_allow_pvf').val())) ? 0 : parseInt($('#tax_allow_pvf').val()));
			total += (isNaN(parseInt($('#tax_allow_sso').val())) ? 0 : parseInt($('#tax_allow_sso').val()));
			$('#total_deductions').html(total.format(2))
			//$('#tax_deductions').val(total)
			//alert(total)
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
			if(val > tax_settings.parents_allow){
				val = tax_settings.parents_allow
				$('#parents_allow').val(val)
				$('#info_parents_allow').html('<span>'+tax_err.parents_allow+'</span>')
				setTimeout(function(){$('#info_parents_allow').html(tax_info.parents_allow)},timeout);
			}
			$('#parents_allowance').val(val * tax_settings.parents_allowance)
			calculateDeductions()
		})
		$('#parents_inlaw_allow').on('change', function(){
			var val = this.value
			if(val > tax_settings.parents_inlaw_allow){
				val = tax_settings.parents_inlaw_allow
				$('#parents_inlaw_allow').val(val)
				$('#info_parents_inlaw_allow').html('<span>'+tax_err.parents_allow+'</span>')
				setTimeout(function(){$('#info_parents_inlaw_allow').html(tax_info.parents_allow)},timeout);
			}
			$('#parents_inlaw_allowance').val(val * tax_settings.parents_inlaw_allowance)
			calculateDeductions()
		})
		$('#disabled_allow').on('change', function(){
			var val = this.value
			if(val > tax_settings.disabled_allow){
				val = tax_settings.disabled_allow
				$('#disabled_allow').val(val)
				$('#info_disabled_allow').html('<span>'+tax_err.disabled_allow+'</span>')
				setTimeout(function(){$('#info_disabled_allow').html(tax_info.disabled_allow)},timeout);
			}
			$('#disabled_allowance').val(val * tax_settings.disabled_allowance)
			calculateDeductions()
		})
		$('#child_allow, #child_allow_2018').on('change', function(){
			var ch1 = $('#child_allow').val()
			var ch2 = $('#child_allow_2018').val()
			$('#child_allowance').val(ch1 * tax_settings.child_allowance)
			$('#child_allowance_2018').val(ch2 * tax_settings.child_allowance_2018)
			if((ch1+ch2) >= tax_settings.child_adopt_allow){
				$('#child_adopt_allow').val(0).prop('disabled', true)
				$('#child_adopt_allowance').val(0)
			}else{
				$('#child_adopt_allow').prop('disabled', false)
			}
			calculateDeductions()
		})
		
		$('#child_adopt_allow').on('change', function(){
			var adopt = parseFloat(this.value)
			var child = parseFloat($('#child_allow').val()) +  parseFloat($('#child_allow_2018').val())
			var maxx = parseInt(tax_settings.child_adopt_allow) - child
			if(adopt > maxx){
				adopt = maxx; $(this).val(adopt)
				$('#info_child_adopt_allow').html('<span>'+tax_err.child_adopt_allow+'</span>')
				setTimeout(function(){$('#info_child_adopt_allow').html(tax_info.child_adopt_allow)},timeout);
			}
			$('#child_adopt_allowance').val(adopt * tax_settings.child_adopt_allowance)
			calculateDeductions()
		})
		
		$('#child_birth_bonus').on('change', function(){
			if(this.value > parseInt(tax_settings.child_birth_bonus)){$('#child_birth_bonus').val(tax_settings.child_birth_bonus)}
			calculateDeductions()
		})
		$('#own_health_insurance, #own_life_insurance').on('change', function(){
			var health = parseInt($('#own_health_insurance').val())
			var life = parseInt($('#own_life_insurance').val())
			if(health > parseInt(tax_settings.own_health_insurance)){
				health = parseInt(tax_settings.own_health_insurance)
				$('#info_own_health_insurance').html('<span>'+tax_err.own_health_insurance+'</span>')
				setTimeout(function(){$('#info_own_health_insurance').html(tax_info.own_health_insurance)},timeout);
			}
			if(life > tax_settings.own_life_insurance){
				life = parseInt(tax_settings.own_life_insurance)
				$('#info_own_life_insurance').html('<span>'+tax_err.own_life_insurance+'</span>')
				setTimeout(function(){$('#info_own_life_insurance').html(tax_info.own_life_insurance)},timeout);
			}
			if(isNaN(health)){health = 0}
			if(isNaN(life)){life = 0}
			$('#own_health_insurance').val(health)
			$('#own_life_insurance').val(life)
			$('#total_own_health_life').val(health + life)
			$('#total_own_health_life').trigger('change')
			calculateDeductions()
		})
		$('#own_health_insurance').trigger('change')
		
		$('#total_own_health_life').on('change', function(){
			if(parseInt(this.value) > parseInt(tax_settings.max_own_health_life)){
				$('#info_health_life_insurance').html('<span>'+tax_err.max_own_health_life+'</span>')
				$('#total_own_health_life').addClass('error')
			}else{
				$('#info_health_life_insurance').html('<i style="color:#090" class="fa fa-check fa-lg"></i>')
				$('#total_own_health_life').removeClass('error')
			}
			calculateDeductions()
		})
		$('#total_own_health_life').trigger('change')
		
		$('#health_insurance_parent').on('change', function(){
			var val = parseFloat(this.value)
			/*if(val > parseInt(tax_settings.health_insurance_par)){
				val = tax_settings.health_insurance_par
				$('#health_insurance_parent').val(val)
				$('#info_health_insurance_parent').html('<span>'+tax_err.health_insurance_par+'</span>')
				setTimeout(function(){$('#info_health_insurance_parent').html(tax_info.health_insurance_par)},timeout);
			}*/
			$('#health_insurance_parent').val(val)
			//alert()
			calculateDeductions()
		})
		$('#life_allow_insurance_spouse').on('change', function(){
			if(this.value > parseInt(tax_settings.life_insurance_spouse)){
				$('#life_allow_insurance_spouse').val(tax_settings.life_insurance_spouse)
				$('#info_life_insurance_spouse').html('<span>'+tax_err.life_insurance_spouse+'</span>')
				setTimeout(function(){$('#info_life_insurance_spouse').html(tax_info.life_insurance_spouse)},timeout);
			}
			calculateDeductions()
		})
		$('#pension_fund_allowance, #tax_allow_pvf, #nsf_allowance, #rmf_allowance').on('change', function(){
			var pension = parseInt($('#pension_fund_allowance').val())
			var provident = parseInt($('#tax_allow_pvf').val())
			var nsf = parseInt($('#nsf_allowance').val())
			var rmf = parseInt($('#rmf_allowance').val())
			if(pension > parseInt(tax_settings.pension_fund_allowance)){
				pension = parseInt(tax_settings.pension_fund_allowance)
				$('#info_pension_fund_allow').html('<span>'+tax_err.pension_fund_allowance+'</span>')
				setTimeout(function(){$('#info_pension_fund_allow').html(tax_info.pension_fund_allowance)},timeout);
			}
			if(parseInt(provident) > parseInt(tax_settings.provident_fund_allowance)){
				//alert(provident +' - '+parseInt(tax_settings.provident_fund_allowance))
				provident = parseInt(tax_settings.provident_fund_allowance)
				$('#info_provident_fund_allow').html('<span>'+tax_err.tax_allow_pvf+'</span>')
				setTimeout(function(){$('#info_provident_fund_allow').html(tax_info.tax_allow_pvf)},timeout);
			}
			if(nsf > parseInt(tax_settings.nsf_allowance)){
				nsf = parseInt(tax_settings.nsf_allowance)
				$('#info_nsf_allow').html('<span>'+tax_err.nsf_allowance+'</span>')
				setTimeout(function(){$('#info_nsf_allow').html(tax_info.nsf_allowance)},timeout);
			}
			if(rmf > parseInt(tax_settings.rmf_allowance)){
				rmf = parseInt(tax_settings.rmf_allowance)
				$('#info_rmf_allow').html('<span>'+tax_err.rmf_allowance+'</span>')
				setTimeout(function(){$('#info_rmf_allow').html(tax_info.rmf_allowance)},timeout);
			}
			if(isNaN(pension)){pension = 0}
			if(isNaN(provident)){provident = 0}
			if(isNaN(nsf)){nsf = 0}
			if(isNaN(rmf)){rmf = 0}
			$('#pension_fund_allowance').val(pension)
			$('#tax_allow_pvf').val(provident)
			$('#nsf_allowance').val(nsf)
			$('#rmf_allowance').val(rmf)
			
			$('#subtotal2').val(pension + provident + nsf + rmf)
			$('#subtotal2').trigger('change')
			calculateDeductions()
		})
		$('#pension_fund_allowance').trigger('change')
		
		$('#subtotal2').on('change', function(){
			if(parseInt(this.value) > parseInt(tax_settings.max_pension_provident_nsf_rmf)){
				$('#info_subtotal2').html('<span>'+tax_err.ltf_allowance+'</span>')
				$('#subtotal2').addClass('error')
			}else{
				$('#info_subtotal2').html('<i style="color:#090" class="fa fa-check fa-lg"></i>')
				$('#subtotal2').removeClass('error')
			}
			calculateDeductions()
		})
		$('#subtotal2').trigger('change')

		$('#ltf_deduction').on('change', function(){
			if(this.value > parseInt(tax_settings.ltf_allowance)){
				$('#ltf_deduction').val(tax_settings.ltf_allowance)
				$('#info_ltf_deduction').html('<span>'+tax_err.ltf_allowance+'</span>')
				setTimeout(function(){$('#info_ltf_deduction').html(tax_info.ltf_allowance)},timeout);
			}
			calculateDeductions()
		})
		$('#home_loan_interest').on('change', function(){
			if(this.value > parseInt(tax_settings.home_loan_interest)){
				$('#home_loan_interest').val(tax_settings.home_loan_interest)
				$('#info_home_loan_interest').html('<span>'+tax_err.home_loan_interest+'</span>')
				setTimeout(function(){$('#info_home_loan_interest').html(tax_info.home_loan_interest)},timeout);
			}
			calculateDeductions()
		})
		$('#donation_charity').on('change', function(){
			calculateDeductions()
		})
		$('#donation_education').on('change', function(){
			calculateDeductions()
		})
		$('#donation_flood').on('change', function(){
			calculateDeductions()
		})
		$('#exemp_disabled').on('change', function(){
			if($(this).val()=='Y'){
				$('#exemp_disabled_under').val(tax_settings.exemp_disabled_under)
			}else{
				$('#exemp_disabled_under').val(0)
			}
			calculateDeductions()
		})
		$('#exemp_payer').on('change', function(){
			if($(this).val()=='Y'){
				$('#exemp_payer_older').val(tax_settings.exemp_payer_older)
			}else{
				$('#exemp_payer_older').val(0)
			}
			calculateDeductions()
		})
		$('#first_home_allowance').on('change', function(){
			if(this.value > parseInt(tax_settings.first_home_allowance)){
				$('#first_home_allowance').val(tax_settings.first_home_allowance)
				$('#info_first_home_allow').html('<span>'+tax_err.first_home_allow+'</span>')
				setTimeout(function(){$('#info_first_home_allow').html(tax_info.first_home_allow)},timeout);
			}
			calculateDeductions()
		})
		$('#year_end_shop_allowance').on('change', function(){
			if(this.value > parseInt(tax_settings.year_end_shop_allowance)){
				$('#year_end_shop_allowance').val(tax_settings.year_end_shop_allowance)
				$('#info_year_end_shop_allow').html('<span>'+tax_err.year_end_shop_allow+'</span>')
				setTimeout(function(){$('#info_year_end_shop_allow').html(tax_info.year_end_shop_allow)},timeout);
			}
			calculateDeductions()
		})
		$('#domestic_tour_allowance').on('change', function(){
			if(this.value > parseInt(tax_settings.domestic_tour_allowance)){
				$('#domestic_tour_allowance').val(tax_settings.domestic_tour_allowance)
				$('#info_domestic_tour_allow').html('<span>'+tax_err.domestic_tour_allow+'</span>')
				setTimeout(function(){$('#info_domestic_tour_allow').html(tax_info.domestic_tour_allow)},timeout);
			}
			calculateDeductions()
		})
		$('#other_allowance').on('change', function(){
			calculateDeductions()
		})
		
  	});

</script>		
		


		
		
		
		
		
		
		
		
		
		
		
		
		
