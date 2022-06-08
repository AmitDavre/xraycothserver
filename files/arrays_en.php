<?php
	
	//$access = array('m'=>'Management','s'=>'Staff','x'=>'Management & Staff');
	$yesno = array('N'=>'No','Y'=>'Yes');
	$noyes = array('Y'=>'Yes','N'=>'No');
	$noyes01 = array(0=>'No',1=>'Yes');
	
	$months = array(1=>"January", 2=>"February", 3=>"March", 4=>"April", 5=>"May", 6=>"June", 7=>"July", 8=>"August", 9=>"September", 10=>"October", 11=>"November", 12=>"December");
	$short_months = array(1=>"Jan", 2=>"Feb", 3=>"Mar", 4=>"Apr", 5=>"May", 6=>"Jun", 7=>"Jul", 8=>"Aug", 9=>"Sep", 10=>"Oct", 11=>"Nov", 12=>"Dec");
	$weekdays = array(1=>"Monday", 2=>"Tuesday", 3=>"Wednesday", 4=>"Thursday", 5=>"Friday", 6=>"Saterday", 7=>"Sunday");
	$user_status = array(1=>'Active',2=>'Postponed');
	//$permissions = array(1=>'None',2=>'Member',3=>'Author',4=>'Administrator',5=>'Developer');
	$language = array('en'=>'English','th'=>'Thai');
	$title = array(1=>'Mr.',2=>'Ms.',3=>'Mrs.');
	// from admin
	//$levels = array(1=>'Level 1 - Review & Approve',2=>'Level 2 - Review only',3=>'Level 3 - Read only',);
	//$client_access = array('s'=>'Staff only','m'=>'Management & Staff','a'=>'Attendance only');
	//$onsuccess = array(2=>'Go to new client account and finish registration',1=>'Send email to new client',3=>'Do nothing');
	$def_status = array(1=>'Active',0=>'Suspended');
	$client_status = array(1=>'Active', 0=>'On hold', 2=>'Processing', 3=>'Remove');
	$oc_status = array(0=>'Open',1=>'Closed');
	
	$dtable_lang = 'language: {"decimal":"", "emptyTable":"No data available in table","info":"Showing _START_ to _END_ of _TOTAL_ entries","infoEmpty":"Showing 0 to 0 of 0 entries","infoFiltered":   "(filtered from _MAX_ total entries)","infoPostFix":"","thousands":",","lengthMenu":"Show _MENU_ entries","loadingRecords": "Loading ...","processing":"Processing ...","search":"Search:","zeroRecords":"No matching records found","paginate": {"first":"First","last":"Last","next":"Next","previous":"Previous"}},';
	
	//$roles = array(1=>'Employee',2=>'Team leader',3=>'Manager',4=>'Group leader',5=>'Senior manager',6=>'Administrator',7=>'Director CEO');
	//$permissions = array(1=>'No data',2=>'My data',3=>'Subordinates data',4=>'All data');
	$fields = array(1=>'Setup',2=>'Personal',3=>'Contact',4=>'Financial',5=>'Benefits',6=>'Tax',7=>'Documents');
	//$emp_type = array(1=>'Permanent', 2=>'Temporary', 3=>'Trainee', 4=>'Contractor', 5=>'Interim');
	//$xhr_emp_group = array('x'=>'Management & Staff','m'=>'Management only','s'=>'Staff only');
	//$emp_group = array('s'=>'Staff','m'=>'Management');

	//$areas = array(1=>'Branch',2=>'Group',3=>'Department',4=>'Team');
	//$leaders = array(1=>'Director',2=>'Senior manager',3=>'Group leader',4=>'Manager',5=>'Team leader');
	//$blood_types = array(1=>'O +','O -','A +','A -','B +','B -','AB +','AB -',"Don't know");
	$days = array(1=>'Monday','Tuesday','Wednesday','Thursday','Friday','Saterday','Sunday');
	$sdays = array(1=>'Mon','Tue','Wed','Thu','Fri','Sat','Sun');
	$xsdays = array(1=>'Mon','Tue','Wed','Thu','Fri','Sat','Sun');
	$day = array(0=>'days',1=>'day',2=>'days',3=>'days',4=>'days',5=>'days',6=>'days',7=>'days',8=>'days',9=>'days');

	$shift_teams = array(1=>'Shift team 1', 2=>'Shift team 2', 3=>'Shift team 3');
	
	$mod_colors = array('leave'=>'#1aaca3', 'time'=>'#f39c11', 'payroll'=>'#3598dc', 'settings'=>'#e84c3d', 'employee'=>'#2bb962', 'report'=>'#a56cbc', 'approve'=>'#e77e22', 'ccplatform'=>'#2a80b9', '9'=>'#f2c40f', '0'=>'#bec3c7');
	
	//$leave_status = array('RQ'=>'Requested','CA'=>'Cancelled','AP'=>'Approved','RJ'=>'Rejected','PE'=>'Pending','TA'=>'Taken');
	
	//$cert = array('H'=>'No','Y'=>'Yes','N'=>'No','NA'=>'N/A');
	
	//$payroll_status = array('AP'=>'<b style="color:green"><i class="fa fa-thumbs-up"></i>&nbsp; Approved</b>','RJ'=>'<b style="color:#a00"><i class="fa fa-thumbs-down"></i>&nbsp; Rejected</b>','RV'=>'<b style="color:green"><i class="fa fa-thumbs-up"></i>&nbsp; Reviewed</b>');
	
	$emp_group = array('s'=>'Staff', 'm'=>'Management', 'all'=>'Both');
	$emp_groep = array('s'=>'Staff', 'm'=>'Management');
	//$emp_status = array(1=>'Active', 2=>'Resigned', 3=>'Terminated', 4=>'', 5=>'', 6=>'', 7=>'', 8=>'', 1=>'', );
	//$pay_frequency = array(12=>'Once a month', 24=>'Twice a month', 26=>'Every 2 weeks', 52=>'Every week');
	$pay_frequency = array(12=>'Once a month', 24=>'Twice a month');
	$pay_type = array('cash'=>'Cash', 'cheque'=>'Cheque');
	$maritial = array(1=>'Single', 2=>'Married', 3=>'Divorced', 5=>'Separated', 6=>'Co-habitation', 7=>'Widowed');
	$military_status = array(1=>'Exempted',2=>'Pending',3=>'Active duty',4=>'Completed');
	$gender = array(1=>'Male', 2=>'Female');
	$religion = array(1=>'Buddhist', 2=>'Muslim', 3=>'Hindu', 4=>'Christian', 5=>'Other');
	$emp_type = array(1=>'Permanent', 2=>'Temporary', 3=>'Trainee', 4=>'Contractor (not included in payroll)', 5=>'Interim (not included in payroll)');
	$emp_status = array(1=>'Active', 2=>'Resigned', 3=>'Terminated', 0=>'Canceled', 7=>'in-Complete');
	//$xxx = array(1=>'', 2=>'', 3=>'', 4=>'', 5=>'', 6=>'', 7=>'');
	
	$emp_pr_status = array(0=>'Complete', 1=>'inComplete');
	//$scan_status = array(0=>'-', 1=>'Validated');
	
	//$overtime_at = array('any'=>'Anytime', 'before'=>'Before work', 'after'=>'After work', 'weekend'=>'Weekend', 'holiday'=>'Holiday');
	//$overtime_type = array('paid'=>'Paid OT', 'comp'=>'Compensate leave');
	
	//$pr_schedule = array(12=>'Monthly (12)', 24=>'Semi-monthly (24)', 26=>'Bi-weekly (26)', 52=>'Weekly (52)');
	
	$sd_prior[0] = array('Low', 'green');
	$sd_prior[1] = array('Medium', '#f60');
	$sd_prior[2] = array('High', '#b00');
	
	$sd_status[0] = array('Open', 'green');
	$sd_status[1] = array('Closed', '#b00');
	
	$sd_type = array('gen'=>'General', 'con'=>'Confidential', 'bug'=>'Bug report');
	
	$version = array(0=>'REGO Free trial', 10=>'REGO 10', 20=>'REGO 20', 50=>'REGO 50', 100=>'REGO 100', 200=>'REGO Standard', 300=>'REGO Professional', 400=>'REGO Elite');
	$user_type = array('sys'=>'System', 'app'=>'Approver', 'emp'=>'Employee', 'sub'=>'Subscriber');
	
	$paid_by = array('credit'=>'Creditcard', 'transf'=>'Bank transfer', 'cheque'=>'Cheque');
	$inv_status = array(0=>'Processing', 1=>'Confirmed', 2=>'Paid', 3=>'Completed');
	
	$sheet_txt = 'This sheet is protected. This file can be used to upload data to the attendance module.'.PHP_EOL.'Please note that you can use formulas to assemble your data.'.PHP_EOL.'BUT you do need to replace the formulas by values for having the data uploaded correctly.'.PHP_EOL.'You can not copy a full row';
	
	$ptype = array('card'=>'Credit card', 'internet'=>'Internet banking', 'transfer'=>'Bank transfer');
	
	$scan_system = array(
		0=>"No scan", 
		"REGO"=>"REGO Direct Time Registration (Qr code, selfie)", 
		"REGOXLS"=>"REGO Time Import (Excel file)", 
		"AGL"=>"AGL Scan (Tab separated textfile)", 
		"WELADEE"=>"Weladee Scan (Excel file)");
	
	$payroll_status = array(
		'AP'=>'<b style="color:green"><i class="fa fa-thumbs-up"></i>&nbsp; Approved</b>',
		'RJ'=>'<b style="color:#a00"><i class="fa fa-thumbs-down"></i>&nbsp; Rejected</b>',
		'RV'=>'<b style="color:green"><i class="fa fa-thumbs-up"></i>&nbsp; Reviewed</b>');

	$leave_status = array('RQ'=>'Requested','CA'=>'Cancelled','AP'=>'Approved','RJ'=>'Rejected','PE'=>'Pending','TA'=>'Taken');
	
	$access_approve = array('all'=>'All modules','att'=>'Leave & Time','non'=>'None');

	$pensionfund = array('non'=>'None','pvf'=>'Provident fund','psf'=>'Pension fund');

	$pr_status = array(0=>'Processing', 1=>'Approved', 2=>'Rejected', 4=>'Locked');

	$bab_request = array('before'=>'Before', 'after'=>'After', 'both'=>'Both');
	$min_request = array('half'=>'Half day', 'hrs'=>'Hours');

	$agent_status = array(0=>'On hold', 1=>'Active', 2=>'Departed', 3=>'Removed');

	$overtime = array('ot1'=>'OT 1','ot15'=>'OT 1.5','ot2'=>'OT 2','ot3'=>'OT 3');

	$warnings = array(1=>'Verbal warning', 2=>'First warning', 3=>'Second warning', 4=>'Final warning', 5=>'Incident registration');
	$violations = array(1=>'Attendance', 2=>'Carelessness', 3=>'Violation of Safety Rules', 4=>'Disobedience', 5=>'Tardiness', 6=>'Work Quality', 7=>'Rudeness to Customers/Coworkers', 8=>'Violation of Company Policies', 9=>'Quality incident', 10=>'Safety incident', 11=>'Other');
	
	$calcTax = array(0=>'no Tax', 1=>'PND1 40(1)', 2=>'PND1 40(2)', 3=>'PND3');

	$contract_type = array('month'=>'Monthly wage', 'day'=>'Daily wage');

	$calc_base = array('gross'=>'Gross amount', 'net'=>'Net amount');
	
	$base_ot_rate = array('cal'=>'Calculated', 'fix'=>'Fixed');

	$Announcementtype 	= array(1=>'Notification',2=>'Announcement',3=>'Event');
	$AnnouncementMode  	= array(1=>'Email',2=>'Notification Box',3=>'Both');
	$AnnouncementCategory   	= array(1=>'Public',2=>'Private');
	$Topersons   	= array(1=>'All employees', 3=>'Staff', 4=>'Management', 2=>'Specific Group');
	$CCStatus   	= array(1=>'Draft', 2=>'Approved', 3=>'Send', 4=>'Rejected');
	$renewalOptions   	= array(1=>'Every login', 2=>'Every month and if a change happens', 3=>'Every 3 months and if a change happens', 4=>'Every 6 months and if a change happens', 5=> 'Only if a change happens');

