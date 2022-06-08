<?php

		///// UPDATE DAY RATE AND HOUR RATE AND TOTAL EMPLOYEE TAXDEDUCTIONS ////////////////////////////// CREATE FUNCTION
		$fix_allow = array();
		if($res = $dbc->query("SELECT * FROM ".$cid."_settings")){
			$row = $res->fetch_assoc();
			$tmp = $row['fix_allow'];
			$fix_allow = unserialize($tmp);
		}
		//echo '<pre>';
		//var_dump($fix_allow); //exit;
		//echo '</pre>';
		
		$workdays = ($pr_settings['days_month'] == 0 ? 30 : $pr_settings['days_month']);
		$dayhours = ($pr_settings['hours_day'] == 0 ? 8 : $pr_settings['hours_day']);
		
		$emps = array();
		if($res = $dbc->query("SELECT * FROM ".$cid."_employees")){
			while($nrow = $res->fetch_assoc()){
				$wage = $nrow['base_salary'];
				foreach($fix_allow as $k=>$v){
					if($v['rate'] == 'Y'){
						$wage += $nrow['fix_allow_'.$k];
					}
				}
				if(strtolower($nrow['wage_type']) == 'day'){
					$emps[$nrow['emp_id']]['day_rate'] = $wage;
					$emps[$nrow['emp_id']]['hour_rate'] = $wage/$dayhours;
				}else{
					$emps[$nrow['emp_id']]['day_rate'] = $wage/$workdays;
					$emps[$nrow['emp_id']]['hour_rate'] = $wage/$workdays/$dayhours;
				}
				$total_deductions = 0;
				$total_deductions += $nrow['tax_allow_spouse'];
				$total_deductions += $nrow['tax_allow_parents'];
				$total_deductions += $nrow['tax_allow_parents_inlaw'];
				$total_deductions += $nrow['tax_allow_child_bio'];
				$total_deductions += $nrow['tax_allow_child_bio_2018'];
				$total_deductions += $nrow['tax_allow_child_adopted'];
				$total_deductions += $nrow['tax_allow_child_birth'];
				$total_deductions += $nrow['tax_allow_disabled_person'];
				$total_deductions += $nrow['tax_allow_home_loan_interest'];
				$total_deductions += $nrow['tax_allow_first_home'];
				$total_deductions += $nrow['tax_allow_donation_charity'];
				$total_deductions += $nrow['tax_allow_donation_education'];
				$total_deductions += $nrow['tax_allow_donation_flood'];
				$total_deductions += $nrow['tax_allow_own_health'];
				$total_deductions += $nrow['tax_allow_health_parents'];
				$total_deductions += $nrow['tax_allow_own_life_insurance'];
				$total_deductions += $nrow['tax_allow_life_insurance_spouse'];
				$total_deductions += $nrow['tax_allow_pension_fund'];
				$total_deductions += $nrow['tax_allow_rmf'];
				$total_deductions += $nrow['tax_allow_ltf'];
				$total_deductions += $nrow['tax_allow_nsf'];
				$total_deductions += $nrow['tax_allow_exemp_disabled_under'];
				$total_deductions += $nrow['tax_allow_exemp_payer_older'];
				$total_deductions += $nrow['tax_allow_domestic_tour'];
				$total_deductions += $nrow['tax_allow_year_end_shopping'];
				$total_deductions += $nrow['tax_allow_other'];
				$emps[$nrow['emp_id']]['emp_tax_deductions'] = $total_deductions;
			}
		}
		if($emps){ 
			foreach($emps as $k=>$v){
				$dbc->query("UPDATE ".$cid."_employees SET 
					day_rate = '".$v['day_rate']."', 
					hour_rate = '".$v['hour_rate']."', 
					emp_tax_deductions = '".$v['emp_tax_deductions']."' 
					WHERE emp_id = '".$k."'");
			}
		}
		//echo '<pre>';
		//var_dump($emps); exit;
		//echo '</pre>';
