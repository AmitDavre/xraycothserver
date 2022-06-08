<?
	
	if(session_id()==''){session_start();}
	ob_start();
	var_dump($_REQUEST); exit;
	
	$msg = '';
		
	//$hour_rate = ($basic_salary/$workdays/$dayhours);
	$hour_rate = ($_REQUEST['hour_rate']);
	$hour_rate1 = ($_REQUEST['salary1']/30/8);
	$hour_rate2 = ($_REQUEST['salary2']/30/8);

	$ot1_1 = round(($hour_rate1 * $_REQUEST['ot1_1']),2);
	$ot1_2 = round(($hour_rate2 * $_REQUEST['ot1_2']),2);
	$tot1 = $ot1_1+$ot1_2;
	
	$ot15_1 = round((($hour_rate1*1.5) * $_REQUEST['ot15_1']),2);
	$ot15_2 = round((($hour_rate2*1.5) * $_REQUEST['ot15_2']),2);
	$tot15 = $ot15_1+$ot15_2;

	$ot2_1 = round((($hour_rate1*2) * $_REQUEST['ot2_1']),2);
	$ot2_2 = round((($hour_rate2*2) * $_REQUEST['ot2_2']),2);
	$tot2 = $ot2_1+$ot2_2;
	
	$ot3_1 = round((($hour_rate1*3) * $_REQUEST['ot3_1']),2);
	$ot3_2 = round((($hour_rate2*3) * $_REQUEST['ot3_2']),2);
	$tot3 = $ot3_1+$ot3_2;
	
	$absence = $_REQUEST['absence'] * $hour_rate;
	$leave_wop = $_REQUEST['leave_wop'] * $hour_rate;
	$late_early = $_REQUEST['late_early'] * $hour_rate;
	$tot_absence = $absence + $leave_wop + $late_early;
	
	$data = array();
		$rdata['save'] = $_REQUEST['save'];
		$rdata['rate2'] = $hour_rate2;
		
		$rdata['tot1'] = $tot1;
		$rdata['tot1n'] = $tot1;
		$rdata['tot15'] = $tot15;
		$rdata['tot15n'] = $tot15;
		$rdata['tot2'] = $tot2;
		$rdata['tot2n'] = $tot2;
		$rdata['tot3'] = $tot3;
		$rdata['tot3n'] = $tot3;
		$rdata['ootn'] = $_REQUEST['other_ot'];
		$rdata['tot_ot'] = $tot1 + $tot15 + $tot2 + $tot3 + $_REQUEST['other_ot'];
		$rdata['tot_absence'] = $tot_absence;
		$rdata['tot_other_income'] = $_REQUEST['other_income'] + $_REQUEST['severance'] + $_REQUEST['remaining_salary'] + $_REQUEST['notice_payment'] + $_REQUEST['paid_leave'];
	
	//var_dump($rdata); exit;
	
	include('calculate_payroll.php');
	
	$rdata['tot_deduct'] = $tot_absence + $_REQUEST['uniform'] + $_REQUEST['deduct_3'] + $_REQUEST['pvf'] + $_REQUEST['sso'];
	//exit;
	
	//var_dump($_REQUEST); exit;
	//var_dump($rdata); exit;
	
	ob_clean();
	echo json_encode($rdata);
	exit;
		
