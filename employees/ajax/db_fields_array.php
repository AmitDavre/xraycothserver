<?
	if(session_id()==''){session_start();}
	ob_start();

/*	include("../../dbconnect/db_connect.php");
	include(DIR.'files/functions.php');
	$lng = getLangVariables('en');
	
	$olddata = array();
	$sql = "SELECT * FROM ".$_SESSION['xhr']['emp_dbase']." WHERE emp_id = 'ST200-001'";
	if($res = $dbc->query($sql)){
		if($row = $res->fetch_assoc()){
			$olddata = $row;
		}
	}
	unset($olddata['emp_card'], $olddata['lim_email'], $olddata['lim_id'], $olddata['hod_email'], $olddata['hod_id']);
	unset($olddata['leave_group'], $olddata['dleave'], $olddata['dilligence_status'], $olddata['yearbonus']);
	unset($olddata['former_salary_rate'], $olddata['income_current_year'], $olddata['tax_paid_current_year'], $olddata['ssf_current_year_employee']);
	unset($olddata['pvf_current_year_employee'], $olddata['ssf_current_year_employer'], $olddata['pvf_current_year_employer'], $olddata['pvf_prior_years_employee']);
	unset($olddata['pvf_prior_years_employer'], $olddata['contribute'], $olddata['image'], $olddata['att_idcard']);
	unset($olddata['att_housebook'], $olddata['att_bankbook'], $olddata['attach1'], $olddata['attach2']);
	unset($olddata['access_start'], $olddata['access_end'], $olddata['log_status'], $olddata['emergency_contacts']);
	unset($olddata['emp_tax_deductions'], $olddata['total_tax_deductions'], $olddata['th_name'], $olddata['att_contract']);
	unset($olddata['tax_id'], $olddata['tax_standard_deduction'], $olddata['tax_personal_allowance'], $olddata['tax_allow_pvf']);
	unset($olddata['tax_allow_sso'], $olddata['username'], $olddata['password'], $olddata['gpf_rate'], $olddata['calc_gpf']);
	unset($olddata['pr_calculation'], $olddata['allow_login'], $olddata['print_payslip'], $olddata['idcard_nr'], $olddata['pr_status']);
*/	
	//var_dump($olddata);

	$emp_db['emp_id'] = $lng['Employee ID'];
	$emp_db['sid'] = 'Scan ID';
	$emp_db['title'] = $lng['Title'];
	$emp_db['firstname'] = $lng['First name'];
	$emp_db['lastname'] = $lng['Last name'];
	$emp_db['en_name'] = $lng['Name in English'];
	$emp_db['birthdate'] = $lng['Birthdate'];
	$emp_db['nationality'] = $lng['Nationality'];
	$emp_db['gender'] = $lng['Gender'];
	$emp_db['maritial'] = $lng['Maritial status'];
	$emp_db['religion'] = $lng['Religion'];
	$emp_db['military_status'] = $lng['Military status'];
	$emp_db['height'] = $lng['Height'];
	$emp_db['weight'] = $lng['Weight'];
	$emp_db['blood_group'] = $lng['Blood group'];

	$emp_db['address1'] = $lng['Address line 1'];
	$emp_db['address2'] = $lng['Address line 2'];
	$emp_db['sub_district'] = $lng['Sub district'];
	$emp_db['district'] = $lng['District'];
	$emp_db['province'] = $lng['Province'];
	$emp_db['postnr'] = $lng['Postal code'];
	$emp_db['country'] = $lng['Country'];
	$emp_db['personal_phone'] = $lng['Personal phone'];
	$emp_db['work_phone'] = $lng['Work phone'];
	$emp_db['personal_email'] = $lng['Personal email'];
	$emp_db['work_email'] = $lng['Work email'];

	$emp_db['dept_code'] = $lng['Department'];
	$emp_db['department'] = $lng['Department'];
	$emp_db['branch_code'] = $lng['Branch'];
	$emp_db['branch'] = $lng['Branch'];
	$emp_db['group_code'] = $lng['Group'];
	$emp_db['group_name'] = $lng['Group'];
	$emp_db['lim_name'] = $lng['Line manager'];
	$emp_db['hod_name'] = $lng['Head of department'];
	$emp_db['emp_group'] = $lng['Employee group'];
	$emp_db['position_code'] = $lng['Position'];
	$emp_db['position'] = $lng['Position'];
	$emp_db['emp_type'] = $lng['Employee type'];
	$emp_db['shiftplan'] = $lng['Shift team'];
	$emp_db['joining_date'] = $lng['Joining date'];
	$emp_db['probation_date'] = $lng['Probation due date'];
	$emp_db['resign_date'] = $lng['Resign date'];
	$emp_db['resign_reason'] = $lng['Resign reason'];
	//$emp_db[] = $lng['Service years'];
	$emp_db['emp_status'] = $lng['Employee status'];
	$emp_db['drvlicense_nr'] = $lng['Driving license No.'];
	$emp_db['drvlicense_exp'] = $lng['License expiry date'];
	$emp_db['time_registration'] = $lng['Time registration'];
	
	$emp_db['idcard_nr'] = $lng['ID Card Tax ID'];
	$emp_db['idcard_exp'] = $lng['ID card expiry date'];
	$emp_db['issued'] = $lng['Place issued'];
	$emp_db['bank_code'] = $lng['Bank code'];
	$emp_db['bank_name'] = $lng['Bank name'];
	$emp_db['bank_account'] = $lng['Bank account no.'];
	$emp_db['bank_account_name'] = $lng['Bank account name'];
	$emp_db['bank_transfer'] = $lng['Automatic bank tranfer'];
	$emp_db['frequency_pay'] = $lng['Payment frequency'];
	$emp_db['pay_type'] = $lng['Payment type'];
	$emp_db['pvf_nr'] = $lng['Provident fund no.'];
	$emp_db['pvf_reg_date'] = $lng['PVF registration date'];

	$emp_db['base_salary'] = $lng['Basic salary'];
	$emp_db['eff_date'] = 'Effective date';
	for($i=1; $i<=15; $i++){
		$emp_db['fix_allow_'.$i] = 'Fix allow '.$i;
	}
	/*foreach($fix_allow as $k=>$v){
		$emp_db['fix_allow_'.$k] = $v[$lang].' (Fix. '.$lng['allowance'].')';
	}*/
	$emp_db['var_allow_dilligence'] = 'Dilligence';
	$emp_db['var_allow_shift'] = 'Shift';
	$emp_db['var_allow_transport'] = 'Transport';
	$emp_db['var_allow_meal'] = 'Meal';
	$emp_db['var_allow_phone'] = 'Phone';
	$emp_db['bonus_payinmonth'] = $lng['Bonus paid in month'];
	$emp_db['bonus_months'] = $lng['Year bonus (months)'];
	$emp_db['bonus_cash'] = $lng['Year bonus cash'];
	//$emp_db[''] = $lng['Total year bonus'];
	$emp_db['pvf_rate_employee'] = $lng['PVF rate employee'];
	$emp_db['pvf_rate_employer'] = $lng['PVF rate employer'];
	$emp_db['calc_sso'] = $lng['Calculate SSF'];
	$emp_db['calc_pvf'] = $lng['Calculate PVF'];
	$emp_db['calc_tax'] = $lng['Calculate Tax'];
	$emp_db['modify_tax'] = $lng['Modify Tax amount'];
	$emp_db['calc_method'] = $lng['Tax calculation method'];
	$emp_db['annual_leave'] = 'Annual leave (days)';

	//$emp_db['tax_standard_deduction'] = 'xx';
	//$emp_db['tax_personal_allowance'] = 'xxx';
	$emp_db['tax_spouse'] = $lng['Spouse care'];
	$emp_db['tax_allow_spouse'] = $lng['allowance'];
	$emp_db['tax_parents'] = $lng['Parents care'];
	$emp_db['tax_allow_parents'] = $lng['allowance'];
	$emp_db['tax_parents_inlaw'] = $lng['Parents in law care'];
	$emp_db['tax_allow_parents_inlaw'] = $lng['allowance'];
	$emp_db['tax_child_bio'] = $lng['Child care - biological'];
	$emp_db['tax_allow_child_bio'] = $lng['allowance'];
	$emp_db['tax_child_bio_2018'] = $lng['Child care - biological 2018/19/20'];
	$emp_db['tax_allow_child_bio_2018'] = $lng['allowance'];
	$emp_db['tax_child_adopted'] = $lng['Child care - adopted'];
	$emp_db['tax_allow_child_adopted'] = $lng['allowance'];
	$emp_db['tax_allow_child_birth'] = $lng['Child birth (Baby bonus)'];
	$emp_db['tax_disabled_person'] = $lng['Care disabled person'];
	$emp_db['tax_allow_disabled_person'] = $lng['allowance'];
	$emp_db['tax_allow_home_loan_interest'] = $lng['Home loan interest'];	
	$emp_db['tax_allow_first_home'] = $lng['First home buyer'];
	$emp_db['tax_allow_donation_charity'] = $lng['Donation charity'];
	$emp_db['tax_allow_donation_education'] = $lng['Donation education'];	
	$emp_db['tax_allow_donation_flood'] = $lng['Donation flooding'];
	$emp_db['tax_allow_own_health'] = $lng['Own health insurance'];
	$emp_db['tax_allow_health_parents'] = $lng['Health insurance parents'];
	$emp_db['tax_allow_own_life_insurance'] = $lng['Own life insurance'];
	$emp_db['tax_allow_life_insurance_spouse'] = $lng['Life insurance spouse'];
	$emp_db['tax_allow_pension_fund'] = $lng['Pension fund'];	
	$emp_db['tax_allow_rmf'] = $lng['RMF'];
	$emp_db['tax_allow_ltf'] = $lng['LTF'];
	$emp_db['tax_exemp_disabled_under'] = $lng['Exemption disabled person <65 yrs'];
	$emp_db['tax_allow_exemp_disabled_under'] = $lng['allowance'].' (THB)';	
	$emp_db['tax_exemp_payer_older'] = $lng['Exemption tax payer => 65yrs'];	
	$emp_db['tax_allow_exemp_payer_older'] = $lng['allowance'].' (THB)';	
	$emp_db['tax_allow_domestic_tour'] = $lng['Domestic tour'];
	$emp_db['tax_allow_year_end_shopping'] = $lng['Year-end shopping'];
	$emp_db['tax_allow_other'] = $lng['Other allowance'];
	$emp_db['tax_health_parents'] = $lng['Health insurance parents'];
	$emp_db['tax_allow_nsf'] = $lng['NSF'];
	
	$emp_db['att_idcard'] = $lng['ID card'];
	$emp_db['att_housebook'] = $lng['Housebook'];
	$emp_db['att_bankbook'] = $lng['Bankbook'];
	$emp_db['att_contract'] = $lng['Contract'];
	$emp_db['attach1'] = $lng['Additional file'];
	$emp_db['attach2'] = $lng['Additional file'];
	
	//var_dump($emp_db);
	
	/*$history = array(); 
	foreach($olddata as $k=>$v){
		$history[$k] = $emp_db[$k];
	}
	var_dump($history);*/
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		