 
 <table id="deductionTable" class="basicTable inputs taxset vat" border="0">
		<thead>
			 <tr style="line-height:100%">
					<th><?=$lng['Description']?></th>
					<th class="tac" style="min-width:60px"><?=$lng['Max']?><br /><?=strtolower($lng['Input'])?></th>
					<th class="tac" style="min-width:80px"><?=$lng['Max']?><br /><?=strtolower($lng['Amount'])?></th>
					<th style="width:90%"><?=$lng['Information condition error messages']?></th>
			 </tr>
		</thead>
		<tbody>
		<tr>
			 <th><?=$lng['Standard deduction']?> (%)</th>
			 <td></td>
			 <td>
					<input type="text" class="numeric tar sel" name="tax_settings[stdeduct_per]" placeholder="__" value="<?=$tax_settings['stdeduct_per']?>">
			 </td>
			 <td class="info">
					<input class="in_info" type="text" name="tax_info_th[stdeduct_per]" placeholder="Thai info" value="<?=$tax_info_th['stdeduct_per']?>">
					<input class="in_info" type="text" name="tax_info_en[stdeduct_per]" placeholder="English info" value="<?=$tax_info_en['stdeduct_per']?>">
			 </td>
		</tr>
		<tr>
			 <th><?=$lng['Standard deduction']?><br><?=$lng['Max. amount']?></th>
			 <td></td>
			 <td>
					<input type="text" class="numeric tar sel" name="tax_settings[standard_deduction]" placeholder="__" value="<?=$tax_settings['standard_deduction']?>">
			 </td>
			 <td class="info">
					<input class="in_info" type="text" name="tax_info_th[standard_deduction]" placeholder="Thai info" value="<?=$tax_info_th['standard_deduction']?>">
					<input class="in_info" type="text" name="tax_info_en[standard_deduction]" placeholder="English info" value="<?=$tax_info_en['standard_deduction']?>">
					<input class="in_err" type="text" name="tax_err_th[standard_deduction]" placeholder="Thai error message" value="<?=$tax_err_th['standard_deduction']?>">
					<input class="in_err" type="text" name="tax_err_en[standard_deduction]" placeholder="English error message" value="<?=$tax_err_en['standard_deduction']?>">
			 </td>
		</tr>
		<tr>
			 <th><?=$lng['Personal care']?></th>
			 <td></td>
			 <td>
					<input type="text" class="numeric tar sel" name="tax_settings[personal_allowance]" placeholder="__" value="<?=$tax_settings['personal_allowance']?>">
			 </td>
			 <td class="info">
					<input class="in_info" type="text" name="tax_info_th[personal_allowance]" placeholder="Thai info" value="<?=$tax_info_th['personal_allowance']?>">
					<input class="in_info" type="text" name="tax_info_en[personal_allowance]" placeholder="English info" value="<?=$tax_info_en['personal_allowance']?>">
			 </td>
		</tr>
		<tr>
			 <th><?=$lng['Spouse care']?></th>
			 <td class="tac">Y/N</td>
			 <td>
					<input type="text" class="numeric tar sel" name="tax_settings[spouse_allowance]" placeholder="__" value="<?=$tax_settings['spouse_allowance']?>">
			 </td>
			 <td class="info">
					<input class="in_info" type="text" name="tax_info_th[spouse_allowance]" placeholder="Thai info" value="<?=$tax_info_th['spouse_allowance']?>">
					<input class="in_info" type="text" name="tax_info_en[spouse_allowance]" placeholder="English info" value="<?=$tax_info_en['spouse_allowance']?>">
			 </td>
		</tr>
		<tr>
			 <th><?=$lng['Parents care']?></th>
			 <td><input type="text" class="float21 tar sel" name="tax_settings[parents_allow]" placeholder="__" value="<?=$tax_settings['parents_allow']?>"></td>
			 <td>
					<input type="text" class="numeric tar sel" name="tax_settings[parents_allowance]" placeholder="__" value="<?=$tax_settings['parents_allowance']?>">
			 </td>
			 <td class="info">
					<input class="in_info" type="text" name="tax_info_th[parents_allow]" placeholder="Thai info" value="<?=$tax_info_th['parents_allow']?>">
					<input class="in_info" type="text" name="tax_info_en[parents_allow]" placeholder="English info" value="<?=$tax_info_en['parents_allow']?>">
					<input class="in_err" type="text" name="tax_err_th[parents_allow]" placeholder="Thai error message" value="<?=$tax_err_th['parents_allow']?>">
					<input class="in_err" type="text" name="tax_err_en[parents_allow]" placeholder="English error message" value="<?=$tax_err_en['parents_allow']?>">
			 </td>
		</tr>
		<tr>
			 <th><?=$lng['Parents in law care']?></th>
			 <td><input type="text" class="float21 tar sel" name="tax_settings[parents_inlaw_allow]" placeholder="__" value="<?=$tax_settings['parents_inlaw_allow']?>"></td>
			 <td>
					<input type="text" class="numeric tar sel" name="tax_settings[parents_inlaw_allowance]" placeholder="__" value="<?=$tax_settings['parents_inlaw_allowance']?>">
			 </td>
			 <td class="info">
					<input class="in_info" type="text" name="tax_info_th[parents_inlaw_allow]" placeholder="Thai info" value="<?=$tax_info_th['parents_inlaw_allow']?>">
					<input class="in_info" type="text" name="tax_info_en[parents_inlaw_allow]" placeholder="English info" value="<?=$tax_info_en['parents_inlaw_allow']?>">
					<input class="in_err" type="text" name="tax_err_th[parents_inlaw_allow]" placeholder="Thai error message" value="<?=$tax_err_th['parents_inlaw_allow']?>">
					<input class="in_err" type="text" name="tax_err_en[parents_inlaw_allow]" placeholder="English error message" value="<?=$tax_err_en['parents_inlaw_allow']?>">
			 </td>
		</tr>
		<tr>
			 <th><?=$lng['Care disabled person']?></th>
			 <td><input type="text" class="float21 tar sel" name="tax_settings[disabled_allow]" placeholder="__" value="<?=$tax_settings['disabled_allow']?>"></td>
			 <td>
					<input type="text" class="numeric tar sel" name="tax_settings[disabled_allowance]" placeholder="__" value="<?=$tax_settings['disabled_allowance']?>">
			 </td>
			 <td class="info">
					<input class="in_info" type="text" name="tax_info_th[disabled_allow]" placeholder="Thai info" value="<?=$tax_info_th['disabled_allow']?>">
					<input class="in_info" type="text" name="tax_info_en[disabled_allow]" placeholder="English info" value="<?=$tax_info_en['disabled_allow']?>">
					<input class="in_err" type="text" name="tax_err_th[disabled_allow]" placeholder="Thai error message" value="<?=$tax_err_th['disabled_allow']?>">
					<input class="in_err" type="text" name="tax_err_en[disabled_allow]" placeholder="English error message" value="<?=$tax_err_en['disabled_allow']?>">
			 </td>
		</tr>
		<tr>
			 <th><?=$lng['Child care - biological']?></th>
			 <td><input type="text" class="float21 tar sel" name="tax_settings[child_allow]" placeholder="__" value="<?=$tax_settings['child_allow']?>"></td>
			 <td>
					<input type="text" class="numeric tar sel" name="tax_settings[child_allowance]" placeholder="__" value="<?=$tax_settings['child_allowance']?>">
			 </td>
			 <td class="info">
					<input class="in_info" type="text" name="tax_info_th[child_allow]" placeholder="Thai info" value="<?=$tax_info_th['child_allow']?>">
					<input class="in_info" type="text" name="tax_info_en[child_allow]" placeholder="English info" value="<?=$tax_info_en['child_allow']?>">
			 </td>
		</tr>
		<tr>
			 <th><?=$lng['Child care - biological 2018/19/20']?></th>
			 <td><input type="text" class="float21 tar sel" name="tax_settings[child_allow_2018]" placeholder="__" value="<?=$tax_settings['child_allow_2018']?>"></td>
			 <td>
					<input type="text" class="numeric tar sel" name="tax_settings[child_allowance_2018]" placeholder="__" value="<?=$tax_settings['child_allowance_2018']?>">
			 </td>
			 <td class="info">
					<input class="in_info" type="text" name="tax_info_th[child_allow_2018]" placeholder="Thai info" value="<?=$tax_info_th['child_allow_2018']?>">
					<input class="in_info" type="text" name="tax_info_en[child_allow_2018]" placeholder="English info" value="<?=$tax_info_en['child_allow_2018']?>">
			 </td>
		</tr>
		<tr>
			 <th><?=$lng['Child care - adopted']?></th>
			 <td><input type="text" class="float21 tar sel" name="tax_settings[child_adopt_allow]" placeholder="__" value="<?=$tax_settings['child_adopt_allow']?>"></td>
			 <td>
					<input type="text" class="numeric tar sel" name="tax_settings[child_adopt_allowance]" placeholder="__" value="<?=$tax_settings['child_adopt_allowance']?>">
			 </td>
			 <td class="info">
					<input class="in_info" type="text" name="tax_info_th[child_adopt_allow]" placeholder="Thai info" value="<?=$tax_info_th['child_adopt_allow']?>">
					<input class="in_info" type="text" name="tax_info_en[child_adopt_allow]" placeholder="English info" value="<?=$tax_info_en['child_adopt_allow']?>">
					<input class="in_err" type="text" name="tax_err_th[child_adopt_allow]" placeholder="Thai error message" value="<?=$tax_err_th['child_adopt_allow']?>">
					<input class="in_err" type="text" name="tax_err_en[child_adopt_allow]" placeholder="English error message" value="<?=$tax_err_en['child_adopt_allow']?>">
			 </td>
		</tr>
		<tr>
			 <th><?=$lng['Child birth (Baby bonus)']?></th>
			 <td></td>
			 <td>
					<input type="text" class="numeric tar sel" name="tax_settings[child_birth_bonus]" placeholder="__" value="<?=$tax_settings['child_birth_bonus']?>">
			 </td>
			 <td class="info">
					<input class="in_info" type="text" name="tax_info_th[child_birth_bonus]" placeholder="Thai info" value="<?=$tax_info_th['child_birth_bonus']?>">
					<input class="in_info" type="text" name="tax_info_en[child_birth_bonus]" placeholder="English info" value="<?=$tax_info_en['child_birth_bonus']?>">
			 </td>
		</tr>
		<tr>
			 <th><?=$lng['Own health insurance']?></th>
			 <td></td>
			 <td>
					<input type="text" class="numeric tar sel" name="tax_settings[own_health_insurance]" placeholder="__" value="<?=$tax_settings['own_health_insurance']?>">
			 </td>
			 <td class="info">
					<input class="in_info" type="text" name="tax_info_th[own_health_insurance]" placeholder="Thai info" value="<?=$tax_info_th['own_health_insurance']?>">
					<input class="in_info" type="text" name="tax_info_en[own_health_insurance]" placeholder="English info" value="<?=$tax_info_en['own_health_insurance']?>">
					<input class="in_err" type="text" name="tax_err_th[own_health_insurance]" placeholder="Thai error message" value="<?=$tax_err_th['own_health_insurance']?>">
					<input class="in_err" type="text" name="tax_err_en[own_health_insurance]" placeholder="English error message" value="<?=$tax_err_en['own_health_insurance']?>">
			 </td>
		</tr>
		<tr>
			 <th><?=$lng['Own life insurance']?></th>
			 <td></td>
			 <td>
					<input type="text" class="numeric tar sel" name="tax_settings[own_life_insurance]" placeholder="__" value="<?=$tax_settings['own_life_insurance']?>">
			 </td>
			 <td class="info">
					<input class="in_info" type="text" name="tax_info_th[own_life_insurance]" placeholder="Thai info" value="<?=$tax_info_th['own_life_insurance']?>">
					<input class="in_info" type="text" name="tax_info_en[own_life_insurance]" placeholder="English info" value="<?=$tax_info_en['own_life_insurance']?>">
					<input class="in_err" type="text" name="tax_err_th[own_life_insurance]" placeholder="Thai error message" value="<?=$tax_err_th['own_life_insurance']?>">
					<input class="in_err" type="text" name="tax_err_en[own_life_insurance]" placeholder="English error message" value="<?=$tax_err_en['own_life_insurance']?>">
			 </td>
		</tr>
		<tr>
			 <th><?=$lng['Max sum above']?><br />Own health + Own life above</th>
			 <td></td>
			 <td>
					<input type="text" class="numeric tar sel" name="tax_settings[max_own_health_life]" placeholder="__" value="<?=$tax_settings['max_own_health_life']?>">
			 </td>
			 <td class="info">
					<input class="in_info" type="text" name="tax_info_th[max_own_health_life]" placeholder="Thai info" value="<?=$tax_info_th['max_own_health_life']?>">
					<input class="in_info" type="text" name="tax_info_en[max_own_health_life]" placeholder="English info" value="<?=$tax_info_en['max_own_health_life']?>">
					<input class="in_err" type="text" name="tax_err_th[max_own_health_life]" placeholder="Thai error message" value="<?=$tax_err_th['max_own_health_life']?>">
					<input class="in_err" type="text" name="tax_err_en[max_own_health_life]" placeholder="English error message" value="<?=$tax_err_en['max_own_health_life']?>">
			 </td>
		</tr>
		<tr>
			 <th><?=$lng['Health insurance parents']?></th>
			 <td><input type="text" class="float21 tar sel" name="tax_settings[health_insurance_par]" placeholder="__" value="<?=$tax_settings['health_insurance_par']?>"></td>
			 <td>
					<input type="text" class="numeric tar sel" name="tax_settings[health_insurance_parent]" placeholder="__" value="<?=$tax_settings['health_insurance_parent']?>">
			 </td>
			 <td class="info">
					<input class="in_info" type="text" name="tax_info_th[health_insurance_par]" placeholder="Thai info" value="<?=$tax_info_th['health_insurance_par']?>">
					<input class="in_info" type="text" name="tax_info_en[health_insurance_par]" placeholder="English info" value="<?=$tax_info_en['health_insurance_par']?>">
					<input class="in_err" type="text" name="tax_err_th[health_insurance_par]" placeholder="Thai error message" value="<?=$tax_err_th['health_insurance_par']?>">
					<input class="in_err" type="text" name="tax_err_en[health_insurance_par]" placeholder="English error message" value="<?=$tax_err_en['health_insurance_par']?>">
			 </td>
		</tr>
		<tr>
			 <th><?=$lng['Life insurance spouse']?></th>
			 <td></td>
			 <td>
					<input type="text" class="numeric tar sel" name="tax_settings[life_insurance_spouse]" placeholder="__" value="<?=$tax_settings['life_insurance_spouse']?>">
			 </td>
			 <td class="info">
					<input class="in_info" type="text" name="tax_info_th[life_insurance_spouse]" placeholder="Thai info" value="<?=$tax_info_th['life_insurance_spouse']?>">
					<input class="in_info" type="text" name="tax_info_en[life_insurance_spouse]" placeholder="English info" value="<?=$tax_info_en['life_insurance_spouse']?>">
					<input class="in_err" type="text" name="tax_err_th[life_insurance_spouse]" placeholder="Thai error message" value="<?=$tax_err_th['life_insurance_spouse']?>">
					<input class="in_err" type="text" name="tax_err_en[life_insurance_spouse]" placeholder="English error message" value="<?=$tax_err_en['life_insurance_spouse']?>">
			 </td>
		</tr>
		<tr>
			 <th><?=$lng['Pension fund']?></th>
			 <td></td>
			 <td>
					<input type="text" class="numeric tar sel" name="tax_settings[pension_fund_allowance]" placeholder="__" value="<?=$tax_settings['pension_fund_allowance']?>">
			 </td>
			 <td class="info">
					<input class="in_info" type="text" name="tax_info_th[pension_fund_allowance]" placeholder="Thai info" value="<?=$tax_info_th['pension_fund_allowance']?>">
					<input class="in_info" type="text" name="tax_info_en[pension_fund_allowance]" placeholder="English info" value="<?=$tax_info_en['pension_fund_allowance']?>">
					<input class="in_err" type="text" name="tax_err_th[pension_fund_allowance]" placeholder="Thai error message" value="<?=$tax_err_th['pension_fund_allowance']?>">
					<input class="in_err" type="text" name="tax_err_en[pension_fund_allowance]" placeholder="English error message" value="<?=$tax_err_en['pension_fund_allowance']?>">
			 </td>
		</tr>
		<tr>
			 <th><?=$lng['Provident fund']?></th>
			 <td></td>
			 <td>
					<input type="text" class="numeric tar sel" name="tax_settings[provident_fund_allowance]" placeholder="__" value="<?=$tax_settings['provident_fund_allowance']?>">
			 </td>
			 <td class="info">
					<input class="in_info" type="text" name="tax_info_th[provident_fund_allowance]" placeholder="Thai info" value="<?=$tax_info_th['provident_fund_allowance']?>">
					<input class="in_info" type="text" name="tax_info_en[provident_fund_allowance]" placeholder="English info" value="<?=$tax_info_en['provident_fund_allowance']?>">
					<input class="in_err" type="text" name="tax_err_th[provident_fund_allowance]" placeholder="Thai error message" value="<?=$tax_err_th['provident_fund_allowance']?>">
					<input class="in_err" type="text" name="tax_err_en[provident_fund_allowance]" placeholder="English error message" value="<?=$tax_err_en['provident_fund_allowance']?>">
			 </td>
		</tr>
		<tr>
			 <th><?=$lng['NSF']?></th>
			 <td></td>
			 <td>
					<input type="text" class="numeric tar sel" name="tax_settings[nsf_allowance]" placeholder="__" value="<?=$tax_settings['nsf_allowance']?>">
			 </td>
			 <td class="info">
					<input class="in_info" type="text" name="tax_info_th[nsf_allowance]" placeholder="Thai info" value="<?=$tax_info_th['nsf_allowance']?>">
					<input class="in_info" type="text" name="tax_info_en[nsf_allowance]" placeholder="English info" value="<?=$tax_info_en['nsf_allowance']?>">
					<input class="in_err" type="text" name="tax_err_th[nsf_allowance]" placeholder="Thai error message" value="<?=$tax_err_th['nsf_allowance']?>">
					<input class="in_err" type="text" name="tax_err_en[nsf_allowance]" placeholder="English error message" value="<?=$tax_err_en['nsf_allowance']?>">
			 </td>
		</tr>
		<tr>
			 <th><?=$lng['RMF']?></th>
			 <td></td>
			 <td>
					<input type="text" class="numeric tar sel" name="tax_settings[rmf_allowance]" placeholder="__" value="<?=$tax_settings['rmf_allowance']?>">
			 </td>
			 <td class="info">
					<input class="in_info" type="text" name="tax_info_th[rmf_allowance]" placeholder="Thai info" value="<?=$tax_info_th['rmf_allowance']?>">
					<input class="in_info" type="text" name="tax_info_en[rmf_allowance]" placeholder="English info" value="<?=$tax_info_en['rmf_allowance']?>">
					<input class="in_err" type="text" name="tax_err_th[rmf_allowance]" placeholder="Thai error message" value="<?=$tax_err_th['rmf_allowance']?>">
					<input class="in_err" type="text" name="tax_err_en[rmf_allowance]" placeholder="English error message" value="<?=$tax_err_en['rmf_allowance']?>">
			 </td>
		</tr>
		<tr>
			 <th><?=$lng['Max sum above']?></th>
			 <td></td>
			 <td>
					<input type="text" class="numeric tar sel" name="tax_settings[max_pension_provident_nsf_rmf]" placeholder="__" value="<?=$tax_settings['max_pension_provident_nsf_rmf']?>">
			 </td>
			 <td class="info">
					<input class="in_info" type="text" name="tax_info_th[max_pension_provident_nsf_rmf]" placeholder="Thai info" value="<?=$tax_info_th['max_pension_provident_nsf_rmf']?>">
					<input class="in_info" type="text" name="tax_info_en[max_pension_provident_nsf_rmf]" placeholder="English info" value="<?=$tax_info_en['max_pension_provident_nsf_rmf']?>">
					<input class="in_err" type="text" name="tax_err_th[max_pension_provident_nsf_rmf]" placeholder="Thai error message" value="<?=$tax_err_th['max_pension_provident_nsf_rmf']?>">
					<input class="in_err" type="text" name="tax_err_en[max_pension_provident_nsf_rmf]" placeholder="English error message" value="<?=$tax_err_en['max_pension_provident_nsf_rmf']?>">
			 </td>
		</tr>
		<tr>
			 <th><?=$lng['LTF']?></th>
			 <td></td>
			 <td>
					<input type="text" class="numeric tar sel" name="tax_settings[ltf_allowance]" placeholder="__" value="<?=$tax_settings['ltf_allowance']?>">
			 </td>
			 <td class="info">
					<input class="in_info" type="text" name="tax_info_th[ltf_allowance]" placeholder="Thai info" value="<?=$tax_info_th['ltf_allowance']?>">
					<input class="in_info" type="text" name="tax_info_en[ltf_allowance]" placeholder="English info" value="<?=$tax_info_en['ltf_allowance']?>">
					<input class="in_err" type="text" name="tax_err_th[ltf_allowance]" placeholder="Thai error message" value="<?=$tax_err_th['ltf_allowance']?>">
					<input class="in_err" type="text" name="tax_err_en[ltf_allowance]" placeholder="English error message" value="<?=$tax_err_en['ltf_allowance']?>">
			 </td>
		</tr>
		<tr>
			 <th><?=$lng['Home loan interest']?></th>
			 <td></td>
			 <td>
					<input type="text" class="numeric tar sel" name="tax_settings[home_loan_interest]" placeholder="__" value="<?=$tax_settings['home_loan_interest']?>">
			 </td>
			 <td class="info">
					<input class="in_info" type="text" name="tax_info_th[home_loan_interest]" placeholder="Thai info" value="<?=$tax_info_th['home_loan_interest']?>">
					<input class="in_info" type="text" name="tax_info_en[home_loan_interest]" placeholder="English info" value="<?=$tax_info_en['home_loan_interest']?>">
					<input class="in_err" type="text" name="tax_err_th[home_loan_interest]" placeholder="Thai error message" value="<?=$tax_err_th['home_loan_interest']?>">
					<input class="in_err" type="text" name="tax_err_en[home_loan_interest]" placeholder="English error message" value="<?=$tax_err_en['home_loan_interest']?>">
			 </td>
		</tr>
		<tr>
			 <th><?=$lng['Social Security Fund']?></th>
			 <td></td>
			 <td>
					<input type="text" class="numeric tar sel" name="tax_settings[social_security_fund]" placeholder="__" value="<?=$tax_settings['social_security_fund']?>">
			 </td>
			 <td class="info">
					<input class="in_info" type="text" name="tax_info_th[social_security_fund]" placeholder="Thai info" value="<?=$tax_info_th['social_security_fund']?>">
					<input class="in_info" type="text" name="tax_info_en[social_security_fund]" placeholder="English info" value="<?=$tax_info_en['social_security_fund']?>">
			 </td>
		</tr>
		<tr>
			 <th><?=$lng['Donation charity']?></th>
			 <td></td>
			 <td>
					<input type="text" class="numeric tar sel" name="tax_settings[donation_charity]" placeholder="__" value="<?=$tax_settings['donation_charity']?>">
			 </td>
			 <td class="info">
					<input class="in_info" type="text" name="tax_info_th[donation_charity]" placeholder="Thai info" value="<?=$tax_info_th['donation_charity']?>">
					<input class="in_info" type="text" name="tax_info_en[donation_charity]" placeholder="English info" value="<?=$tax_info_en['donation_charity']?>">
					<input class="in_err" type="text" name="tax_err_th[donation_charity]" placeholder="Thai error message" value="<?=$tax_err_th['donation_charity']?>">
					<input class="in_err" type="text" name="tax_err_en[donation_charity]" placeholder="English error message" value="<?=$tax_err_en['donation_charity']?>">
			 </td>
		</tr>
		<tr>
			 <th><?=$lng['Donation flooding']?></th>
			 <td></td>
			 <td>
					<input type="text" class="numeric tar sel" name="tax_settings[donation_flood]" placeholder="__" value="<?=$tax_settings['donation_flood']?>">
			 </td>
			 <td class="info">
					<input class="in_info" type="text" name="tax_info_th[donation_flood]" placeholder="Thai info" value="<?=$tax_info_th['donation_flood']?>">
					<input class="in_info" type="text" name="tax_info_en[donation_flood]" placeholder="English info" value="<?=$tax_info_en['donation_flood']?>">
					<input class="in_err" type="text" name="tax_err_th[donation_flood]" placeholder="Thai error message" value="<?=$tax_err_th['donation_flood']?>">
					<input class="in_err" type="text" name="tax_err_en[donation_flood]" placeholder="English error message" value="<?=$tax_err_en['donation_flood']?>">
			 </td>
		</tr>
		<tr>
			 <th><?=$lng['Donation education']?></th>
			 <td></td>
			 <td>
					<input type="text" class="numeric tar sel" name="tax_settings[donation_education]" placeholder="__" value="<?=$tax_settings['donation_education']?>">
			 </td>
			 <td class="info">
					<input class="in_info" type="text" name="tax_info_th[donation_education]" placeholder="Thai info" value="<?=$tax_info_th['donation_education']?>">
					<input class="in_info" type="text" name="tax_info_en[donation_education]" placeholder="English info" value="<?=$tax_info_en['donation_education']?>">
					<input class="in_err" type="text" name="tax_err_th[donation_education]" placeholder="Thai error message" value="<?=$tax_err_th['donation_education']?>">
					<input class="in_err" type="text" name="tax_err_en[donation_education]" placeholder="English error message" value="<?=$tax_err_en['donation_education']?>">
			 </td>
		</tr>
		<tr>
			 <th><?=$lng['Exemption disabled person <65 yrs']?></th>
			 <td class="tac">Y/N</td>
			 <td>
					<input type="text" class="numeric tar sel" name="tax_settings[exemp_disabled_under]" placeholder="__" value="<?=$tax_settings['exemp_disabled_under']?>">
			 </td>
			 <td class="info">
					<input class="in_info" type="text" name="tax_info_th[exemp_disabled_under]" placeholder="Thai info" value="<?=$tax_info_th['exemp_disabled_under']?>">
					<input class="in_info" type="text" name="tax_info_en[exemp_disabled_under]" placeholder="English info" value="<?=$tax_info_en['exemp_disabled_under']?>">
			 </td>
		</tr>
		<tr>
			 <th><?=$lng['Exemption tax payer => 65yrs']?></th>
			 <td class="tac">Y/N</td>
			 <td>
					<input type="text" class="numeric tar sel" name="tax_settings[exemp_payer_older]" placeholder="__" value="<?=$tax_settings['exemp_payer_older']?>">
			 </td>
			 <td class="info">
					<input class="in_info" type="text" name="tax_info_th[exemp_payer_older]" placeholder="Thai info" value="<?=$tax_info_th['exemp_payer_older']?>">
					<input class="in_info" type="text" name="tax_info_en[exemp_payer_older]" placeholder="English info" value="<?=$tax_info_en['exemp_payer_older']?>">
			 </td>
		</tr>
		<tr>
			 <th><?=$lng['First home buyer']?></th>
			 <td></td>
			 <td>
					<input type="text" class="numeric tar sel" name="tax_settings[first_home_allowance]" placeholder="__" value="<?=$tax_settings['first_home_allowance']?>">
			 </td>
			 <td class="info">
					<input class="in_info" type="text" name="tax_info_th[first_home_allowance]" placeholder="Thai info" value="<?=$tax_info_th['first_home_allowance']?>">
					<input class="in_info" type="text" name="tax_info_en[first_home_allowance]" placeholder="English info" value="<?=$tax_info_en['first_home_allowance']?>">
					<input class="in_err" type="text" name="tax_err_th[first_home_allowance]" placeholder="Thai error message" value="<?=$tax_err_th['first_home_allowance']?>">
					<input class="in_err" type="text" name="tax_err_en[first_home_allowance]" placeholder="English error message" value="<?=$tax_err_en['first_home_allowance']?>">
			 </td>
		</tr>
		<tr>
			 <th><?=$lng['Year-end shopping']?></th>
			 <td></td>
			 <td>
					<input type="text" class="numeric tar sel" name="tax_settings[year_end_shop_allowance]" placeholder="__" value="<?=$tax_settings['year_end_shop_allowance']?>">
			 </td>
			 <td class="info">
					<input class="in_info" type="text" name="tax_info_th[year_end_shop_allowance]" placeholder="Thai info" value="<?=$tax_info_th['year_end_shop_allowance']?>">
					<input class="in_info" type="text" name="tax_info_en[year_end_shop_allowance]" placeholder="English info" value="<?=$tax_info_en['year_end_shop_allowance']?>">
					<input class="in_err" type="text" name="tax_err_th[year_end_shop_allowance]" placeholder="Thai error message" value="<?=$tax_err_th['year_end_shop_allowance']?>">
					<input class="in_err" type="text" name="tax_err_en[year_end_shop_allowance]" placeholder="English error message" value="<?=$tax_err_en['year_end_shop_allowance']?>">
			 </td>
		</tr>
		<tr>
			 <th><?=$lng['Domestic tour']?></th>
			 <td></td>
			 <td>
					<input type="text" class="numeric tar sel" name="tax_settings[domestic_tour_allowance]" placeholder="__" value="<?=$tax_settings['domestic_tour_allowance']?>">
			 </td>
			 <td class="info">
					<input class="in_info" type="text" name="tax_info_th[domestic_tour_allowance]" placeholder="Thai info" value="<?=$tax_info_th['domestic_tour_allowance']?>">
					<input class="in_info" type="text" name="tax_info_en[domestic_tour_allowance]" placeholder="English info" value="<?=$tax_info_en['domestic_tour_allowance']?>">
					<input class="in_err" type="text" name="tax_err_th[domestic_tour_allowance]" placeholder="Thai error message" value="<?=$tax_err_th['domestic_tour_allowance']?>">
					<input class="in_err" type="text" name="tax_err_en[domestic_tour_allowance]" placeholder="English error message" value="<?=$tax_err_en['domestic_tour_allowance']?>">
			 </td>
		</tr>
		<tr>
			 <th><?=$lng['Other allowance']?></th>
			 <td></td>
			 <td>
					<input type="text" class="numeric tar sel" name="tax_settings[other_allowance]" placeholder="__" value="<?=$tax_settings['other_allowance']?>">
			 </td>
			 <td class="info">
					<input class="in_info" type="text" name="tax_info_th[other_allowance]" placeholder="Thai info" value="<?=$tax_info_th['other_allowance']?>">
					<input class="in_info" type="text" name="tax_info_en[other_allowance]" placeholder="English info" value="<?=$tax_info_en['other_allowance']?>">
			 </td>
		</tr>
		</tbody>
 </table>
