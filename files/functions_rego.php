<?php
	
	/*function getRegoPayrollSettings(){
		global $dbx;
		$data = array();
		$res = $dbx->query("SELECT * FROM rego_default_payroll_settings");
		if($row = $res->fetch_assoc()){
			$data['days_month'] = $row['days_month'];
			$data['hours_day'] = $row['hours_day'];
			$data['sso_rate'] = $row['sso_rate'];
			$data['sso_min'] = $row['sso_min'];
			$data['sso_max'] = $row['sso_max'];
			$data['sso_min_wage'] = $row['sso_min_wage'];
			$data['sso_max_wage'] = $row['sso_max_wage'];
			$data['fix_allow'] = $row['fix_allow'];
			$data['var_allow'] = $row['var_allow'];
			$data['tax_settings'] = unserialize($row['tax_settings']);
			$data['taxrules'] = unserialize($row['taxrules']);
			$data['tax_info_th'] = unserialize($row['tax_info_th']);
			$data['tax_info_en'] = unserialize($row['tax_info_en']);
			$data['tax_err_th'] = unserialize($row['tax_err_th']);
			$data['tax_err_en'] = unserialize($row['tax_err_en']);
			//$data = $row;
		}
		return $data;
	}*/
	
	/*	function getWordsFromAmount($number, $lang){
		if($lang == 'en'){
			$f = new NumberFormatter("en", NumberFormatter::SPELLOUT);
			return '*** '.ucfirst($f->format($number)).' Baht ***';
		}else{
			//$number = $number;
			if((int)$number == 0){return '*** ศูนย์บาท ***'; exit;}
			$digit=array('ศูนย์','หนึ่ง','สอง','สาม','สี่','ห้า','หก','เจ็ด','แปด','เก้า','สิบ','สิบเอ็ด');
			$digit2=array('','สิบ','ร้อย','พัน','หมื่น','แสน','ล้าน');
			//$digit=array('null','nung ','song ','sam ','see ','ha ','hok ','tjet ','paet ','kaow ','sip ','et ');
			//$digit2=array('','sip ','roi ','pan ','meung ','sean ','lan ');
			if(strpos($number, '.') !== false){
				$explode_number = explode(".",$number);
				$num0=$explode_number[0]; // เลขจำนวนเต็ม
				$num1=$explode_number[1]; // หลักทศนิยม
			}else{
				$num0=$number; // เลขจำนวนเต็ม
				$num1=''; // หลักทศนิยม
			}
			$bathtext1 = '';// เลขจำนวนเต็ม
			$didit2_chk=strlen($num0)-1;
			for($i=0;$i<=strlen($num0)-1;$i++){
				$cut_input_number=substr($num0,$i,1);
				if($cut_input_number==0){ // ถ้าเลข 0 ไม่ต้องใส่ค่าอะไร
					//$bathtext1.=''."".$digit2[$didit2_chk]; 
				}elseif($cut_input_number==2 && $didit2_chk==1){ // ถ้าเลข 2 อยู่หลักสิบ
					$bathtext1.='ยี่'."".$digit2[$didit2_chk]; 
				}elseif($cut_input_number==1 && $didit2_chk==1){ // ถ้าเลข 1 อยู่หลักสิบ
					//$bathtext1.= ''."".$digit2[$didit2_chk]; 
				}elseif($cut_input_number==1 && $didit2_chk==0){ // ถ้าเลข 1 อยู่หลักหน่วย
					if(substr($num0,$i-1,1)==0){
						$bathtext1.= 'หนึ่ง'."".$digit2[$didit2_chk];
					}else{
						$bathtext1.= 'เอ็ด'."".$digit2[$didit2_chk];
					} 
				}else{
					$bathtext1.= $digit[$cut_input_number]."".$digit2[$didit2_chk];
				}
				$didit2_chk=$didit2_chk-1;
			}
			$bathtext1.='บาท ';
			//$bathtext1.='Bath ';
			// เลขทศนิยม
			$didit2_chk=strlen($num1)-1;
			$satang = false;
			for($i=0;$i<=strlen($num1)-1;$i++){
				$cut_input_number=substr($num1,$i,1);
				if($cut_input_number==0){ // ถ้าเลข 0 ไม่ต้องใส่ค่าอะไร
				
				}elseif($cut_input_number==2 && $didit2_chk==1){ // ถ้าเลข 2 อยู่หลักสิบ
					$bathtext1.='ยี่'."".$digit2[$didit2_chk]; 
					//$bathtext1.='yee'."".$digit2[$didit2_chk]; 
					$satang = true;
				}elseif($cut_input_number==1 && $didit2_chk==1){ // ถ้าเลข 1 อยู่หลักสิบ
					$bathtext1.= ''."".$digit2[$didit2_chk];
					$satang = true;
				}elseif($cut_input_number==1 && $didit2_chk==0){ // ถ้าเลข 1 อยู่หลักหน่วย
					if(substr($num1,$i-1,1)==0){
					$bathtext1.= 'หนึ่ง'."".$digit2[$didit2_chk];
					$satang = true;
					//$bathtext1.= 'neung '."".$digit2[$didit2_chk];
				}else{
					$bathtext1.= 'เอ็ด'."".$digit2[$didit2_chk];
					//$bathtext1.= 'et '."".$digit2[$didit2_chk];
				} 
				$satang = true;
			}else{
				$bathtext1.= $digit[$cut_input_number]."".$digit2[$didit2_chk];
				$satang = true;
			}
			$didit2_chk=$didit2_chk-1;
			}
			if($satang){$bathtext1 .='สตางค์';}else{$bathtext1 .= 'ถ้วน';}
			return '*** '.$bathtext1.' ***';
		}
	}
*/
	
	function calculateEmpTotalTaxDeductions($id, $std_deduct, $tax_pvf, $tax_sso){
		global $dbc;
		$total_deductions = 0;
		$emp_tax_deductions = 0;
		
		$res = $dbc->query("SELECT * FROM ".$_SESSION['rego']['cid']."_employees WHERE emp_id = '".$id."'");
		if($tax_deduct = $res->fetch_assoc()){
			$total_deductions += $tax_deduct['tax_personal_allowance'];
			$total_deductions += $tax_deduct['tax_allow_spouse'];
			$total_deductions += $tax_deduct['tax_allow_parents'];
			$total_deductions += $tax_deduct['tax_allow_parents_inlaw'];
			$total_deductions += $tax_deduct['tax_allow_child_bio_2018'];
			$total_deductions += $tax_deduct['tax_allow_child_adopted'];
			$total_deductions += $tax_deduct['tax_allow_child_bio'];
			$total_deductions += $tax_deduct['tax_allow_child_birth'];
			$total_deductions += $tax_deduct['tax_allow_disabled_person'];
			$total_deductions += $tax_deduct['tax_allow_home_loan_interest'];
			$total_deductions += $tax_deduct['tax_allow_first_home'];
			$total_deductions += $tax_deduct['tax_allow_donation_charity'];
			$total_deductions += $tax_deduct['tax_allow_donation_education'];
			$total_deductions += $tax_deduct['tax_allow_donation_flood'];
			$total_deductions += $tax_deduct['tax_allow_own_health'];
			$total_deductions += $tax_deduct['tax_allow_health_parents'];
			$total_deductions += $tax_deduct['tax_allow_own_life_insurance'];
			$total_deductions += $tax_deduct['tax_allow_life_insurance_spouse'];
			$total_deductions += $tax_deduct['tax_allow_pension_fund'];
			$total_deductions += $tax_deduct['tax_allow_rmf'];
			$total_deductions += $tax_deduct['tax_allow_ltf'];
			$total_deductions += $tax_deduct['tax_allow_exemp_disabled_under'];
			$total_deductions += $tax_deduct['tax_allow_exemp_payer_older'];
			$total_deductions += $tax_deduct['tax_allow_domestic_tour'];
			$total_deductions += $tax_deduct['tax_allow_year_end_shopping'];
			$total_deductions += $tax_deduct['tax_allow_other'];
			$total_deductions += $tax_deduct['tax_allow_nsf'];
			
			$emp_tax_deductions = $total_deductions;
			$emp_tax_deductions += $tax_deduct['tax_standard_deduction'];
			$emp_tax_deductions += $tax_deduct['tax_allow_pvf'];
			$emp_tax_deductions += $tax_deduct['tax_allow_sso'];
		}
		$dbc->query("UPDATE ".$_SESSION['rego']['emp_dbase']." SET 
			tax_standard_deduction = '".$std_deduct."', 
			tax_allow_pvf = '".$tax_pvf."', 
			tax_allow_sso = '".$tax_sso."', 
			total_tax_deductions = '".$total_deductions."', 
			emp_tax_deductions = '".$emp_tax_deductions."' 
			WHERE emp_id = '".$id."'");
	}
	
	function getNewEmployeeID(){
		global $dbc;
		global $compinfo;
		$id[1] = 1;
		//$res = $dbc->query("SELECT * FROM ".$_SESSION['rego']['emp_dbase']." ORDER BY emp_id DESC LIMIT 1"); 
		$res = $dbc->query("SELECT SUBSTRING_INDEX(emp_id, '-', -1) as id FROM ".$_SESSION['rego']['emp_dbase']." ORDER BY SUBSTRING_INDEX(emp_id, '-', -1) DESC LIMIT 1"); 
		if($row = $res->fetch_assoc()){
			$id = $row['id']+1;
		}
		if(empty($compinfo['abreviation'])){$compinfo['abreviation'] = 'EMP';}
		return $compinfo['abreviation'].'-'.sprintf('%03d', $id);
	}
	
	/*function getFormdate($cid){
		global $dbc;
		global $months;
		$thm = array(1=>"มกราคม", 2=>"กุมภาพันธ์", 3=>"มีนาคม", 4=>"เมษายน", 5=>"พฤษภาคม", 6=>"มิถุนายน", 7=>"กรกฏาคม", 8=>"สิงหาคม", 9=>"กันยายน", 10=>"ตุลาคม", 11=>"พฤษจิกายน", 12=>"ธันวาคม");
		$month = $_SESSION['rego']['cur_year'].'_'.$_SESSION['rego']['cur_month'];
		$formdate = '';
		$sql = "SELECT formdate FROM ".$cid."_payroll_months WHERE month = '".$month."'";
		if($res = $dbc->query($sql)){
			if($row = $res->fetch_assoc()){
				$formdate = $row['formdate'];
			}
		}
		if(empty($formdate)){
			$formdate = date('t-'.$_SESSION['rego']['cur_month'].'-Y');
		}
		$_SESSION['rego']['formdate']['endate'] = date('d-m-Y', strtotime($formdate));
		$_SESSION['rego']['formdate']['thdate'] = date('d-m-', strtotime($formdate)).(date('Y', strtotime($formdate))+543);
		$_SESSION['rego']['formdate']['d'] = date('d', strtotime($formdate));
		$_SESSION['rego']['formdate']['m'] = $months[date('n', strtotime($formdate))];
		$_SESSION['rego']['formdate']['thm'] = $thm[date('n', strtotime($formdate))];
		$_SESSION['rego']['formdate']['eny'] = date('Y', strtotime($formdate));
		$_SESSION['rego']['formdate']['thy'] = date('Y', strtotime($formdate))+543;
		return $formdate;
	}*/

	/*function getPaydate($cid){
		global $dbc;
		$paydate = '';
		$sql = "SELECT paydate FROM ".$cid."_payroll_months WHERE month = '".$_SESSION['rego']['cur_year'].'_'.$_SESSION['rego']['cur_month']."'";
		if($res = $dbc->query($sql)){
			if($row = $res->fetch_assoc()){
				$paydate = $row['paydate'];
			}
		}
		if(empty($paydate)){
			$tmp = date('01-'.$_SESSION['rego']['curr_month'].'-Y');
			$paydate = date('t-m-Y', strtotime($tmp));
		}
		return $paydate;
	}*/

	/*function getHistoryLock($cid){
		global $dbc;
		$lock = 1;
		$sql = "SELECT history FROM ".$cid."_settings";
		if($res = $dbc->query($sql)){
			$row = $res->fetch_assoc();
			$lock = $row['history'];
		}
		return $lock;
	}*/

	/*function getPayrollPeriods($lang){
		global $dbc;
		global $months;
		global $pr_settings;
		$date = str_replace('/','-',$pr_settings['pr_startdate']);
		$date = str_replace('/','-',$pr_settings['pr_startdate']);
		$start_year = (int)date('Y',strtotime($date));
		$month = (int)date('m', strtotime($date));
		if($start_year == $_SESSION['rego']['cur_year']){
			$start_month = (int)date('m',strtotime($date));
		}elseif($start_year < $_SESSION['rego']['cur_year']){
			$start_month = 1;
		}else{
			$start_month = date('m');
		}
		
		//$period['to_unlock'] = array(); 
		$period['to_lock'] = array(); 
		$period['period'] = array(); 
		$nr = $start_month;
		$new = true;
		
		if($res = $dbc->query("SELECT month, paid FROM ".$_SESSION['rego']['payroll_dbase']." WHERE MONTH >= $start_month and paid != 'H' GROUP by month ORDER by LENGTH(month) ASC, month ASC")){
			while($row = $res->fetch_assoc()){
				if($row['paid'] == 'Y'){
					$icon = '<i class="fa fa-ban"></i>&nbsp;';
					//$period['to_unlock'][(int)$row['month']] = $months[(int)$row['month']].' '.$_SESSION['rego']['year_'.$lang];
				}else{
					$icon = '<i class="fa fa-pencil-square-o"></i>';
					$period['to_lock'][(int)$row['month']] = '<i class="fa fa-lock"></i> '.$months[(int)$row['month']].' '.$_SESSION['rego']['year_'.$lang];
					$new = false;
				}
				$period['period'][(int)$row['month']] = $icon.' '.$months[(int)$row['month']].' '.$_SESSION['rego']['year_'.$lang];
				$nr = $row['month'];
				
			}
			$period['unlock'][$nr] = $months[$nr].' '.$_SESSION['rego']['year_'.$lang];
			if(isset($period['to_lock'][$nr])){$period['unlock'] = array();}
			
			if(!empty($period['period'])){
				$m = $nr;
				if($m < 12){$m ++;}
				if($new){
					$period['period'][$m] = '<i class="fa fa-plus-circle"></i>&nbsp; '.$months[$m].' '.$_SESSION['rego']['year_'.$lang];
				}
			}else{
				$period['period'][$start_month] = '<i class="fa fa-pencil-square-o"></i>&nbsp; '.$months[$start_month].' '.$_SESSION['rego']['year_'.$lang];
			}
			
		}
		return $period;
	}*/

	/*function getCustomers($access){
		global $dbx;
		$data = array();
		$tmp = explode(',', $access);
		if(count($tmp) > 1){
			$ids = "";
			foreach($tmp as $v){$ids .= "'".$v."',";}
			$ids = substr($ids,0,-1);
			$sql = "SELECT * FROM rego_customers WHERE clientID IN($ids)";
			if($res = $dbx->query($sql)){
				while($row = $res->fetch_assoc()){
					$data[$row['clientID']] = $row[$_SESSION['rego']['lang'].'_compname'];
				}
			}
		}
		return $data;
	}*/
	
	
	function getHelpfile($page){
		global $dbx;
		$data = array();
		$res = $dbx->query("SELECT * FROM rego_helpfiles WHERE page = '".$page."'");
		if($row = $res->fetch_assoc()){
			$data = $row[$_SESSION['rego']['lang'].'_content'];
		}
		return $data;
	}	
	
	function getWelcomeFiles(){
		global $dbx;
		$data = array();
		$res = $dbx->query("SELECT * FROM rego_welcomefiles ORDER BY page ASC");
		while($row = $res->fetch_assoc()){
			$data[$row['page']]['title'] = $row[$_SESSION['rego']['lang'].'_title'];
			$data[$row['page']]['content'] = $row[$_SESSION['rego']['lang'].'_content'];
		}
		return $data;
	}	
	
	function taxFomGross($income, $deductions){
		global $pr_settings;
		$taxable = $income - $deductions;
		$rules = unserialize($pr_settings['taxrules']);	
		foreach($rules as $k=>$v){
			$from[$k] = $v['from'];
			$to[$k] = $v['to'];
			$per[$k] = 	$v['percent'];
			$net[$k] = 0;
		}
		$last = max(array_keys($net));
		$pgross = 0;
		foreach($to as $k=>$v){
			if($k == 0){
				if($taxable > $v){
					$gross[$k] = (int)$v;
				}else{
					$gross[$k] = $taxable;
				}
				$pgross += $gross[$k];
			}else if($k == $last){
				if($taxable > $from[$k]){
					$gross[$k] = $taxable - $pgross;
				}else{
					$gross[$k] = 0;
				}
			}else{
				if($taxable < $from[$k]){
					$gross[$k] = 0;
				}else if($taxable > $v){
					$gross[$k] = $v - $pgross;
				}else{
					$gross[$k] = $taxable - $pgross;
				}
				$pgross += $gross[$k];
			}
		}
		$tax_year = 0;
		foreach($gross as $k=>$v){
			$tax[$k] = $gross[$k] * ($per[$k]/100);
			$tax_year += $tax[$k];
		}
		$data['gross'] = $gross;
		$data['percent'] = $per;
		$data['tax'] = $tax;
		
		$data['income'] = $income;
		$data['taxable'] = $taxable;
		$data['tax_year'] = $tax_year;
		$data['tot_gross'] = array_sum($gross);
		$data['percent_tax'] = ($tax_year / $data['tot_gross']) * 100;
		
		$data['net_year'] = $income - $tax_year;
		$data['gross_month'] = $income / 12;
		$data['tax_month'] = $tax_year / 12;
		$data['net_month'] = $data['net_year'] / 12;

		return $data;
	}
	
	function taxFomNet($income, $deductions){
		global $pr_settings;
		$taxable = $income - $deductions;
		$rules = unserialize($pr_settings['taxrules']);	
		foreach($rules as $k=>$v){
			$from[$k] = $v['net_from'];
			$to[$k] = $v['net_to'];
			$per[$k] = 	$v['percent'];
			$net[$k] = 0;
		}
		$last = max(array_keys($net));
		$pnet = 0;
		foreach($to as $k=>$v){
			if($k == 0){
				if($taxable > $v){
					$net[$k] = (int)$v;
				}else{
					$net[$k] = $taxable;
				}
				$pnet += $net[$k];
			}else if($k == $last){
				if($taxable > $from[$k]){
					$net[$k] = $taxable - $pnet;
				}else{
					$net[$k] = 0;
				}
			}else{
				if($taxable < $from[$k]){
					$net[$k] = 0;
				}else if($taxable > $v){
					$net[$k] = $v-$pnet;
				}else{
					$net[$k] = $taxable - $pnet;
				}
				$pnet += $net[$k];
			}
		}
		
		$gross_year = 0;
		foreach($net as $k=>$v){
			$rate = 100 - $per[$k];
			$gross[$k] = $net[$k] / ($rate/100);
			$gross_year += $gross[$k];
		}
		
		$tax_year = 0;
		foreach($gross as $k=>$v){
			$tax[$k] = $gross[$k] * ($per[$k]/100);
			$tax_year += $tax[$k];
		}
		
		$data['gross'] = $gross;
		$data['percent'] = $per;
		$data['tax'] = $tax;
		
		$data['income'] = $income;
		$data['taxable'] = $taxable;
		$data['tax_year'] = $tax_year;
		$data['tot_gross'] = array_sum($gross) + $deductions;
		$data['percent_tax'] = ($tax_year / array_sum($gross)) * 100;
		
		$data['net_year'] = $data['tot_gross'] - $deductions;
		$data['gross_month'] = $data['tot_gross'] / 12;
		$data['tax_month'] = $tax_year / 12;
		$data['net_month'] = $data['gross_month'] - $data['tax_month'];
		
		return $data;
	}
	
	function getLockedMonth($month){
		global $dbc;
		$locked = false;
		if($res = $dbc->query("SELECT paid FROM ".$_SESSION['rego']['payroll_dbase']." WHERE month = '".$month."'")){
			while($row = $res->fetch_assoc()){
				if($row['paid'] == 'Y'){
					$locked = true;
				}
			}
		}
		return $locked;
	}

	function getPrPeriods($lang){
		global $dbc;
		global $months;
		global $pr_settings;
		$date = str_replace('/','-',$pr_settings['pr_startdate']);
		$start_year = (int)date('Y',strtotime($date));
		$month = (int)date('m', strtotime($date));
		if($start_year == $_SESSION['rego']['cur_year']){
			$start_month = (int)date('m',strtotime($date));
		}elseif($start_year < $_SESSION['rego']['cur_year']){
			$start_month = 1;
		}else{
			$start_month = date('m');
		}
		
		$period['to_unlock'] = array(); 
		$period['to_lock'] = array(); 
		$period['period'] = array(); 
		$nr = 0;
		$new = true;
		
		if($res = $dbc->query("SELECT month, paid FROM ".$_SESSION['rego']['payroll_dbase']." GROUP by month ORDER by LENGTH(month) ASC, month ASC")){
			while($row = $res->fetch_assoc()){
				if($row['paid'] !== 'C'){
					$icon = '<i class="fa fa-ban"></i>&nbsp;';
					$period['to_unlock'][(int)$row['month']] = $months[(int)$row['month']].' '.$_SESSION['rego']['year_'.$lang];
				}else{
					$icon = '<i class="fa fa-pencil-square-o"></i>';
					$period['to_lock'][(int)$row['month']] = '<i class="fa fa-lock"></i> '.$months[(int)$row['month']].' '.$_SESSION['rego']['year_'.$lang];
					$new = false;
				}
				$period['period'][(int)$row['month']] = $icon.' '.$months[(int)$row['month']].' '.$_SESSION['rego']['year_'.$lang];
				$nr = $row['month'];
				
			}
			if(!empty($period['period'])){
				$m = (int)$nr;
				if($m < 12){$m ++;}
				if($new){
					$period['period'][$m] = '<i class="fa fa-plus-circle"></i>&nbsp; '.$months[$m].' '.$_SESSION['rego']['year_'.$lang];
				}
			}
		}
		
		if(empty($period['period'])){
			$period['period'][$start_month] = '<i class="fa fa-plus-circle"></i>&nbsp; '.$months[$start_month].' '.$_SESSION['rego']['year_'.$lang];
		}

//$data['period'] = $period;

		/*if(empty($period['to_lock']) && $_SESSION['rego']['cur_month'] < 12){
			if($nr < 12){$nr++;}
			$period['period'][$nr] = '<i class="fa fa-plus-circle"></i>&nbsp; '.$months[$nr].' '.$_SESSION['rego']['year_'.$lang];
		}*/
			
//$data['period'] = $period;
		
		/*if(empty($period['to_lock'])){	
			if(!$period['period']){
				if($start_year < (int)$_SESSION['rego']['cur_year']){
					$d = 1;
					//$period['period'][$d] = '<i class="fa fa-plus-circle"></i>&nbsp; '.$months[$d].' '.$_SESSION['rego']['year_'.$lang];
				}else{
					//$d = (int)date('m',strtotime($joiningdate));
					//$period['period'][$d] = 'xxx '.$comp_start_month.'<i class="fa fa-plus-circle"></i>&nbsp; '.$months[$d].' '.$_SESSION['rego']['year_'.$lang];
					for($i=$start_month;$i<=$cur_month;$i++){
						//$period['period'][$i] = '<i class="fa fa-plus-circle"></i>&nbsp; '.$months[$i].' '.$_SESSION['rego']['year_'.$lang];
					}
				}
			}else{
				if($nr<12){$nr++;}
				//$period['period'][$nr] = '<i class="fa fa-plus-circle"></i>&nbsp; '.$months[$nr].' '.$_SESSION['rego']['year_'.$lang];
			}
		}*/

		//return $data;
		return $period;
	}
	
	
	function getEmployeeColumns(){
		global $dbc;
		$cols = array();
		$sql = "SELECT * FROM ".$_SESSION['rego']['cid']."_users WHERE user_id = '".$_SESSION['rego']['emp_id']."'";
		if($res = $dbc->query($sql)){
			if($row = $res->fetch_assoc()){
				$cols = unserialize($row['emp_cols']);
			}
		}
		if(empty($cols)){$cols = array(4,5,6,7,8,12,13,14,15);}
		return $cols;
	}
	
	
	function sendMail($subject='Subject', $body='Message', $mailto='info@root.com'){
		require 'PHPMailer/PHPMailerAutoload.php';
		$data = array();
		
		//$bericht = nl2br($_REQUEST['message']);
		
		$mail = new PHPMailer;
		$mail->CharSet = "UTF-8";
		$mail->setFrom('willy@xrayict.com', 'myName');//$mailto;
		$mail->addAddress('willy@xrayict.com');
		//$mail->addReplyTo($from, $from_name);
		//$mail->AddBCC("willy@xrayict.com");
		//$mail->AddCC("ben_heidi@live.be");
		$mail->isHTML(true);                                  
		$mail->Subject = $subject;
		$mail->Body = $body;
		//$mail->WordWrap = 50;   
		
		//ob_clean();                           
		if(!$mail->send()) {
			 $data[1] = 'No connection with the mailserver, please try again later.';
			 $data[2] = '<br>Mailer Error: ' . $mail->ErrorInfo;
		} else {
			 $data[3] = 'Mail send successfuly.';
		}



		return $data;
	}
	
	function getEmployeeTaxdata($id){
		global $pr_settings;
		global $dbc;
		global $title;
		global $short_months;
		$historic_data = false;
		$his_salary = 0;
		$his_pvf = 0;
		$his_sso = 0;
		$his_tax = 0;
		$his_fix_allow = 0;
		$his_irregular = 0;
		//$his_tax3 = 0;

		$sql = "SELECT * FROM ".$_SESSION['rego']['cid']."_historic_data_".$_SESSION['rego']['cur_year']." WHERE emp_id = '".$id."'";
		if($res = $dbc->query($sql)){
			while($row = $res->fetch_assoc()){
				if($row['basic_salary'] == 0){break;}
				$his_salary = $row['basic_salary'];
				$his_pvf = $row['pvf'];
				$his_sso = $row['sso'];
				$his_tax = $row['tax'];
				$his_fix_allow = $row['fix_allow'];
				$his_irregular = $row['var_income'];
				//$his_tax3 = $row['tax3'];
				//$historic_data['months'] = $row['months'];
				$historic_data = true;
			}
		}
		//return $historic_data; exit;
		
		$prev = array();
		$tot_salary = $his_salary;
		$tot_pvf = $his_pvf;
		$tot_sso = $his_sso;
		$tot_tax = $his_tax;
		$tot_fix_allow = $his_fix_allow;
		$tot_irregular = $his_irregular;
		//$tot_tax3 = $his_tax3;
		
		//$title = getAppMetadata('title', $_SESSION['rego']['lang']);
		$emp_dbase = $_SESSION['rego']['emp_dbase'];
		$pr_dbase = $_SESSION['rego']['payroll_dbase'];
		$empinfo = getEmpinfo($emp_dbase, $id);
		$basic_salary = $empinfo['base_salary'];
		$bim = $empinfo['bonus_payinmonth'];
		$year_bonus = $empinfo['yearbonus'];
		//$year_bonus = $bom * $basic_salary;
		$fix_allow = 0;
		for($i=1;$i<=15;$i++){
			$fix_allow += $empinfo['fix_allow_'.$i];
		}
		$sso_rate = $pr_settings['sso_rate']/100;
		$max_sso = $pr_settings['sso_max'];
		/*if($_SESSION['rego']['cur_month'] <= 2 && $_SESSION['rego']['cur_year'] == 2020){
			$sso_rate = 0.05;
			$max_sso = 750;
			$min_sso = 83;
		}*/
		$pvf_rate = $empinfo['pvf_rate_employee']/100;
		$calc_method = ($empinfo['calc_method'] == '' || $empinfo['calc_method'] == 'def' ? 'cam' : $empinfo['calc_method']);
		$employee_deductions = $empinfo['total_tax_deductions'];
		
		$data = array();
		
		$res = $dbc->query("SELECT * FROM $pr_dbase WHERE emp_id = '".$id."' ORDER by month ASC");
		while($row = $res->fetch_assoc()){
			$prev[(int)$row['month']] = array(
				'date'=>$short_months[(int)$row['month']].' '.substr($_SESSION['rego']['cur_year'],2),
				'paid'=>$row['paid'],
				'salary'=>(float)$row['salary'],
				'allow'=>(float)$row['total_fix_allow'] + (float)$row['total_var_allow'],
				'ot'=>(float)$row['total_otb'],
				'bonus'=>(float)$row['bonus'],
				'other_income'=>(float)$row['other_income']+(float)$row['severance'],
				'pvf'=>(float)$row['pvf_employee'],
				'sso'=>(float)$row['social'],
				'tax'=>(float)$row['tax'],
				'deduct'=>(float)$row['tot_deduct'],
				'deductions'=>(float)$row['tot_deductions'],
				'gross'=>(float)$row['gross_income'],
				'net'=>(float)$row['net_income'],
				'class'=>''
			);
			//$basic_salary += $row['salary'];
			$tot_salary += $row['salary'];
			$tot_pvf += $row['pvf_employee'];
			$tot_sso += $row['social'];
			$tot_tax += $row['tax'];
	
			$tot_fix_allow += $row['total_fix_allow'];
			//$fix_allow = $row['total_fix_allow'];
			$tot_irregular += ($row['total_var_allow']+$row['total_otb']+$row['other_income']+$row['severance']);
			//$tot_tax3 += $row['tax3'];
		}
		
		reset($prev);
		if(key($prev) > 1){ 
			for($n=1;$n<key($prev);$n++){// Empty leading months
			$data[$n] = array(
				'date'=>$short_months[$n].' '.substr($_SESSION['rego']['cur_year'],2),
				'paid'=>'-',
				'salary'=>'-',
				'allow'=>'-',
				'ot'=>'-',
				'bonus'=>'-',
				'other_income'=>'-',
				'pvf'=>'-',
				'sso'=>'-',
				'tax'=>'-',
				'deduct'=>'-',
				'deductions'=>'-',
				'gross'=>'-',
				'net'=>'-',
				'class'=>'');
			} 
		}
	
		if($historic_data){
			$data[$n-1] = array(
				'date'=>$short_months[$n-1].' '.substr($_SESSION['rego']['cur_year'],2),
				'paid'=>'H',
				'salary'=>$his_salary,
				'allow'=>$his_fix_allow,
				'ot'=>'-',
				'bonus'=>'-',
				'other_income'=>$his_irregular,
				'pvf'=>$his_pvf,
				'sso'=>$his_sso,
				'tax'=>$his_tax,
				'deduct'=>'-',
				'deductions'=>$his_pvf+$his_sso+$his_tax,
				'gross'=>$his_salary+$his_fix_allow+$his_irregular,
				'net'=>($his_salary+$his_fix_allow+$his_irregular)-($his_pvf+$his_sso+$his_tax),
				'class'=>'ired');
		}
		
		$data += $prev;
		//return $data; exit;
		
		end($prev);
		$prev_months = key($prev);
		$rem_months = 12 - $prev_months;
		
		$sso = $basic_salary * ($sso_rate);
		$sso = ($sso > $max_sso ? (float)$max_sso : $sso);
		$tax_sso = ($sso * $rem_months) + $tot_sso;
		$pvf = $basic_salary * ($pvf_rate);
		$tax_pvf = ($pvf * $rem_months) + $tot_pvf;
			
		if($empinfo['calc_sso'] == 'N'){
			$sso = 0;
			$tax_sso = 0;
		}
		if($empinfo['calc_pvf'] == 'N'){
			$pvf = 0;
			$tax_pvfo = 0;
		}
		
		if($calc_method == 'cam'){ // CAM ------------------------------------------------------------------
			for($i=$prev_months+1;$i<=12;$i++){
				
				//var_dump($i);
				$tot_salary += $basic_salary;
				$tot_fix_allow += $fix_allow;
				
				if($i == 12){
					$year1 = $tot_salary + $tot_fix_allow;
				}else{
					$year1 = ($basic_salary + $fix_allow) * 12;
				}
				//var_dump($year1);
				
				$year2 = $year1 + $tot_irregular;
				
				$bonus = 0;
				if($bim == $i){$year2 += $year_bonus; $bonus = $year_bonus;}
				//var_dump($year2);
	
				$taxable = calculateTax3($year1, $tax_sso, $tax_pvf, $employee_deductions);
				$tax1 = $taxable['year_tax'];
				//var_dump($tax1);
				
				$taxable = calculateTax3($year2, $tax_sso, $tax_pvf, $employee_deductions);
				$tax2 = $taxable['year_tax'];
				//var_dump($tax2);
				
				/*if($i == 12){
					$tax3 = $tot_tax;
				}else{
					$tax3 = $tax2 - $tax1 - $tot_tax3;
				}
				$tot_tax3 += $tax3;*/
				//var_dump($tax3);
				
				if($i == 12){
					$tax_this_month = $tax2;// - $tax3;
				}else{
					$tax_this_month = ($tax1/12);// + $tax3;
				}
				$tax_this_month = ($tax_this_month < 0 ? 0 : $tax_this_month);
				$tot_tax += $tax_this_month;
				//var_dump($tax_this_month);
				
				$gross = $basic_salary + $fix_allow;
				$deduct = $sso + $pvf + $tax_this_month;
				$net = $gross - $deduct;
				
				$data[$i] = array(
					'date'=>$short_months[$i].' '.substr($_SESSION['rego']['cur_year'],2),
					'paid'=>'N',
					'salary'=>$basic_salary,
					'allow'=>$fix_allow,
					'ot'=>0,
					'bonus'=>$bonus,
					'other_income'=>0,
					'pvf'=>$pvf,
					'sso'=>$sso,
					'tax'=>$tax_this_month,
					'deduct'=>0,
					'deductions'=>$deduct,
					'gross'=>$gross,
					'net'=>$net,
					'class'=>'igrey'
				);
				//var_dump('xxx');
			}
		}
		
		if($calc_method == 'acm'){ // ACM ----------------------------------------------------------------
			
			/*for($i=$prev_months+1;$i<=12;$i++){
				if($i == 1){
					$year1 = ($basic_salary + $fix_allow) * 12;
				}else{
					$year1 = $tot_salary + $tot_fix_allow;
					$year1 += ($basic_salary + $fix_allow) * $rem_months;
				}
				$bonus = 0;
				if($bim == $i){$bonus = $year_bonus;}
				$taxable = calculateTax3($year1, $tax_sso, $tax_pvf, $employee_deductions);
				$tax1 = $taxable['year_tax'];
				$tax2 = $tax1;
			
				if($i == 12){
					$tax3 = $tot_tax;
				}else{
					$tax3 = $tax2 - $tax1;// - $tot_tax3;
				}
				$tot_tax3 += $tax3;
				//var_dump($tax3);
				
				if($i == 12){
					$tax_this_month = $tax2 - $tax3;
				}else{
					$tax_this_month = $tax1 - $tot_tax;
					$tax_this_month += $tot_tax3;
					$tax_this_month = $tax_this_month/(12-($i-1));
					//$tax_this_month += $tax3;
				}
				
				$tax_this_month = ($tax_this_month < 0 ? 0 : $tax_this_month);
				$tot_tax += $tax_this_month;
			
				$gross = $basic_salary + $fix_allow + $bonus;
				$deduct = $sso + $pvf + $tax_this_month;
				$net = $gross - $deduct;
				
				$data[$i] = array(
					'date'=>$short_months[$i].' '.substr($_SESSION['rego']['cur_year'],2),
					'paid'=>'N',
					'salary'=>$basic_salary,
					'allow'=>$fix_allow,
					'ot'=>0,
					'bonus'=>$bonus,
					'other_income'=>0,
					'pvf'=>$pvf,
					'sso'=>$sso,
					'tax'=>$tax_this_month,
					'deduct'=>0,
					'deductions'=>$deduct,
					'gross'=>$gross,
					'net'=>$net,
					'class'=>'igrey'
				);
			
			
			
			}*/
			
			for($i=$prev_months+1;$i<=12;$i++){
				
				if($i == 1){
					$year1 = ($basic_salary + $fix_allow) * 12;
				}else{
					$year1 = $tot_salary + $tot_fix_allow;
					$year1 += ($basic_salary + $fix_allow) * $rem_months;
				}
				$year1 += $year_bonus;
				//$year1 += $tot_irregular;
				//var_dump($year1);
	
				$year2 = $year1;// + $tot_irregular;
				//var_dump($year2);
				
				$bonus = 0;
				if($bim == $i){$bonus = $year_bonus;}

				$taxable = calculateTax3($year1, $tax_sso, $tax_pvf, $employee_deductions);
				$tax1 = $taxable['year_tax'];
				//$tax1 = $year1 * 0.10;
				
				/*if($i == 12){
					$tax3 = $tot_tax3;
				}else{
					$tax3 = 0;// - $tot_tax3;
				}*/
				//var_dump($tot_tax);
				if($i == 12){
					$tax_this_month = $tax1 - $tot_tax;// + $tot_tax3;
				}else{
					$tax_this_month = $tax1 - $tot_tax;
					//$tax_this_month += $tot_tax3;
					$tax_this_month = $tax_this_month/(12-($i-1));
				}
				//$tot_tax3 += $tax3;
				
				
				//$tax_this_month += $empinfo['modify_tax'];
				$tax_this_month = ($tax_this_month < 0 ? 0 : $tax_this_month);
				$tot_tax += $tax_this_month;
				
				$gross = $basic_salary + $fix_allow + $bonus;
				$deduct = $sso + $pvf + $tax_this_month;
				$net = $gross - $deduct;
				
				$data[$i] = array(
					'date'=>$short_months[$i].' '.substr($_SESSION['rego']['cur_year'],2),
					'paid'=>'N',
					'salary'=>$basic_salary,
					'allow'=>$fix_allow,
					'ot'=>0,
					'bonus'=>$bonus,
					'other_income'=>0,
					'pvf'=>$pvf,
					'sso'=>$sso,
					'tax'=>$tax_this_month,
					'deduct'=>0,
					'deductions'=>$deduct,
					'gross'=>$gross,
					'net'=>$net,
					'class'=>'igrey'
				);
			}
		}
		
		$tot_salary = 0;
		$tot_ot = 0;
		$tot_allow = 0;
		$tot_bonus = 0;
		$tot_other_income = 0;
		$tot_gross = 0;
		$tot_pvf = 0;
		$tot_sso = 0;
		$tot_tax = 0;
		$tot_other_deduct = 0;
		$tot_deductions = 0;
		$tot_net = 0;
		
		foreach($data as $k=>$v){ 
			$tot_salary += $v['salary'];
			$tot_ot += $v['ot'];
			$tot_allow += $v['allow'];
			$tot_bonus += $v['bonus'];
			$tot_other_income += $v['other_income'];
			$tot_gross += $v['gross'];
			$tot_pvf += $v['pvf'];
			$tot_sso += $v['sso'];
			$tot_tax += $v['tax'];
			$tot_other_deduct += $v['deduct'];
			$tot_deductions += $v['deductions'];
			$tot_net += $v['net'];
		}	
		
		$data[13] = array(
			'date'=>'Totals',
			'paid'=>'',
			'salary'=>$tot_salary,
			'allow'=>$tot_allow,
			'ot'=>$tot_ot,
			'bonus'=>$tot_bonus,
			'other_income'=>$tot_other_income,
			'pvf'=>$tot_pvf,
			'sso'=>$tot_sso,
			'tax'=>$tot_tax,
			'deduct'=>$tot_other_deduct,
			'deductions'=>$tot_deductions,
			'gross'=>$tot_gross,
			'net'=>$tot_net,
			'class'=>''
		);
		
		$xdata['calc_method'] = $calc_method;
		$xdata['emp_name'] = $id.' : '.$title[$empinfo['title']].' '.$empinfo[$_SESSION['rego']['lang'].'_name'];
		$xdata['data'] = $data;
		
		$xdata['year1'] = $year1;
		$xdata['year2'] = $year2;
		$xdata['tax_sso'] = $tax_sso;
		$xdata['tax_pvf'] = $tax_pvf;
		$xdata['employee_deductions'] = $employee_deductions;
		$xdata['taxable'] = $taxable;
		
		calculateTax3($year1, $tax_sso, $tax_pvf, $employee_deductions);
		return $xdata;
		
	}
	
	function countEmployees(){
		global $dbc;
		$emps = 0;
		$sql = "SELECT emp_id FROM ".$_SESSION['rego']['emp_dbase'];
		if($res = $dbc->query($sql)){
			$emps = $res->num_rows;
		}
		return $emps;
	}

	function getEmpinfo($db_employee, $id){
		global $dbc;
		//global $db_employee;
		//return $db_employee; exit;
		$data = array();	
		$sql = "(SELECT * FROM $db_employee WHERE emp_id = '".$id."')";
		if($res = $dbc->query($sql)){
			if($row = $res->fetch_assoc()){
				$data = $row;
			}
		}
		return $data;
	}

	function setEmployeeHistory($cid){
		global $dbc;
		//global $departments;
		//global $positions;
		//global $departments;// = getDepartmentNames($cid, $_SESSION['rego']['lang']);
		$data = array();
		$sql = "SELECT * FROM ".$cid."_employees ORDER BY emp_id ASC";
		$res = $dbc->query($sql);
		while($row = $res->fetch_assoc()){
			$data[$row['emp_id']]['salary'] = $row['base_salary'];
			$data[$row['emp_id']]['eff_date'] = $row['eff_date'];
			
			/*$data[$row['emp_id']]['branch'] = $row['branch_code'];
			$data[$row['emp_id']]['group'] = $row['group_code'];
			$data[$row['emp_id']]['department'] = $row['dept_code'];
			$data[$row['emp_id']]['position'] = $row['position_code'];
			$data[$row['emp_id']]['emp_group'] = $row['emp_group'];*/
		}
		if($data){
			foreach($data as $k=>$v){
				if($v['salary'] != '' && $v['eff_date'] != ''){
					
					$sql = "INSERT INTO `".$cid."_emp_history` (id, emp_id, salary, effdate) VALUES (
						'".$dbc->real_escape_string($k.'_sal_'.strtotime($v['eff_date']))."',
						'".$dbc->real_escape_string($k)."',
						'".$dbc->real_escape_string($v['salary'])."', 
						'".$dbc->real_escape_string($v['eff_date'])."') 
						ON DUPLICATE KEY UPDATE salary = VALUES(salary)";
						$dbc->query($sql);
					/*$dbc->query("INSERT INTO `".$cid."_emp_history` (id, emp_id, salary, effdate) VALUES (
						'".$dbc->real_escape_string($k.'sal'.$v['salary'])."',
						'".$dbc->real_escape_string($k)."',
						'".$dbc->real_escape_string($v['salary'])."', 
						'".$dbc->real_escape_string($v['eff_date'])."')");*/
				}
				/*if($v['department'] != 0){
					$dbc->query("INSERT INTO `".$cid."_emp_history` (id, emp_id, department) VALUES (
						'".$dbc->real_escape_string($k.'dep'.$v['department'])."',
						'".$dbc->real_escape_string($k)."',
						'".$dbc->real_escape_string($v['department'])."')");
				}
				if($v['position'] != 0){
					$dbc->query("INSERT INTO `".$cid."_emp_history` (id, emp_id, position) VALUES (
						'".$dbc->real_escape_string($k.'pos'.$v['position'])."',
						'".$dbc->real_escape_string($k)."',
						'".$dbc->real_escape_string($v['position'])."')");
				}
				if($v['branch'] != ''){
					$dbc->query("INSERT INTO `".$cid."_emp_history` (id, emp_id, branch) VALUES (
						'".$dbc->real_escape_string($k.'bra'.$v['branch'])."',
						'".$dbc->real_escape_string($k)."',
						'".$dbc->real_escape_string($v['branch'])."')");
				}
				if($v['group'] != 0){
					$dbc->query("INSERT INTO `".$cid."_emp_history` (id, emp_id, groep) VALUES (
						'".$dbc->real_escape_string($k.'gro'.$v['group'])."',
						'".$dbc->real_escape_string($k)."',
						'".$dbc->real_escape_string($v['group'])."')");
				}
				if($v['emp_group'] != ''){
					$dbc->query("INSERT INTO `".$cid."_emp_history` (id, emp_id, emp_group) VALUES (
						'".$dbc->real_escape_string($k.'egr'.$v['emp_group'])."',
						'".$dbc->real_escape_string($k)."',
						'".$dbc->real_escape_string($v['emp_group'])."')");
				}*/
			}
		}
		return $data;
	}
	
	function setEmployeeDefaults($ids){
		global $dbc;
		global $pr_settings;
		global $sys_settings;
		$tax_settings = unserialize($pr_settings['tax_settings']);
		$calc_method = $pr_settings['tax_calc_method'];

		$emp_defaults = unserialize($sys_settings['emp_defaults']);
		$em_contacts = array();
		$pass = substr(ucfirst($_SESSION['rego']['cid']),0,3)."@".substr($_SESSION['rego']['cid'],3);
		$password = hash('sha256', $pass);
		$sql = "UPDATE ".$_SESSION['rego']['cid']."_employees SET 
			emp_type = IF(emp_type = '', '".$sys_settings['emp_type']."', emp_type), 
			emp_status = IF(emp_status = '', '".$sys_settings['emp_status']."', emp_status), 
			position = IF(position = '', '1', position), 
			startdate = IF(startdate = '', '".date('d-m-Y')."', startdate), 
			print_payslip = IF(print_payslip = '', '".$sys_settings['print_payslip']."', print_payslip), 
			calc_method = IF(calc_method = '', '".$pr_settings['tax_calc_method']."', calc_method), 
			
			tax_standard_deduction = IF(tax_standard_deduction = '', '".$tax_settings['standard_deduction']."', tax_standard_deduction), 
			tax_personal_allowance = IF(tax_personal_allowance = '', '".$tax_settings['personal_allowance']."', tax_personal_allowance), 
			
			tax_spouse = IF(tax_spouse = '', 'N', tax_spouse), 
			tax_exemp_disabled_under = IF(tax_exemp_disabled_under = '', 'N', tax_exemp_disabled_under), 
			tax_exemp_payer_older = IF(tax_exemp_payer_older = '', 'N', tax_exemp_payer_older), 
			
			calc_sso = IF(calc_sso = '', '".$sys_settings['calc_sso']."', calc_sso), 
			calc_pvf = IF(calc_pvf = '', '".$sys_settings['pvf_applied']."', calc_pvf), 
			calc_tax = IF(calc_tax = '', '".$sys_settings['calc_tax']."', calc_tax), 
			username = IF(username = '', emp_id, username),  
			allow_login = IF(allow_login = '', '".$sys_settings['allow_login']."', allow_login)";
			
			//password = IF(password = '', '".$password."', password),  
			//dleave = IF(dleave = '', '".serialize($dleave)."', dleave), 
			//leave_group = IF(leave_group = '', '0', leave_group), 
			//pr_status = IF(pr_status = '', '0', pr_status), 
			//pr_status = IF(idcard_nr = '?????????????', '0', pr_status), 
			//idcard_nr = IF(idcard_nr = '', '?????????????', idcard_nr), 
			//$username = '@'.$_SESSION['rego']['cid'];
			//username = IF(username = '', CONCAT(emp_id,'@".$_SESSION['rego']['cid']."'), username),  
			
			// leave group management or staf ///////////////////////////////////////////  OK
			// show only selected leaves hide tab in employee register //////////////////  OK
			// defauld periods leave & time
			// standard setting in admin
			// set all codes in second sheet excel  /////////////////////////////////////  OK
			// id card only 13 numbers comment, script add - after every section ////////  OK
			// set date format in exported excel file and other formats ?
			// if PVF = no in comp settings set NO, same other settings /////////////////  OK
			
			// also with add new employee
			// xhr0100 change to demo  //////////////////////////////////////////////////  OK
			// users admin
			// show all modules, if ... disabled ////////////////////////////////////////  OK
			
			// check for incomplete employees (id card nr) ... or remove incomplete filter ?
		
		$dbc->query($sql);
		//$data['err'] = mysqli_error($dbc);
		//$data['sql'] = $sql;
		//return $data;
		
		$cdata = array();
		$sql = "SELECT * FROM ".$_SESSION['rego']['cid']."_employees"; 
		if($res = $dbc->query($sql)){
			while($row = $res->fetch_assoc()){
				if($row['base_salary'] > 0){
					$cdata[$row['emp_id']]['pvf'] = 0;
					$cdata[$row['emp_id']]['sso'] = 0;
					//if($row['tax_allow_pvf'] == 0 && $row['calc_pvf'] == 'Y'){
						$pvf = $row['base_salary'] * ($row['pvf_rate_employee'] / 100);
						$pvf = $pvf * 12;
						$cdata[$row['emp_id']]['pvf'] = $pvf;
					//}
					//if($row['tax_allow_sso'] == 0 && $row['calc_sso'] == 'Y'){
						$sso = $row['base_salary'] * 0.5;
						$sso = $sso * 12;
						$sso = ($sso > 9000 ? 9000 : $sso);
						$cdata[$row['emp_id']]['sso'] = $sso;
					//}
				}
			}
		}
		
		
		foreach($cdata as $k=>$v){
			$dbc->query("UPDATE ".$_SESSION['rego']['cid']."_employees SET
				tax_allow_pvf = '".$v['pvf']."',
				tax_allow_sso = '".$v['sso']."' 
				WHERE emp_id = '".$k."'");
		}
		//return $cdata;
	}
	
	function writeToSessions($cid, $array, $field){
		global $dbc;
		$dbname = $cid."_sessions";
		$sql = "UPDATE $dbname SET $field = '".$dbc->real_escape_string(serialize($array))."'";
		//return $sql;
        if($dbc->query($sql)){
			return 'ok';
        }else{
			return 'error : '.mysqli_error($dbc);
		}
    }	
	
	function getVariableAllowances(){
		global $dbc;
		$allow = array();
		if($res = $dbc->query("SELECT var_allow FROM ".$_SESSION['rego']['cid']."_leave_time_settings")){
			$row = $res->fetch_assoc();
			$tmp = unserialize($row['var_allow']);
			foreach($tmp as $k=>$v){
				if($v == 1){$allow[$k] = $v;}
			}
		}
		return $allow;
	}
	
	function getLogtime($cid){
		global $dbc;
		$logtime = 900;
		if($res = $dbc->query("SELECT logtime FROM ".$cid."_system_settings")){
			if($row = $res->fetch_object()){
				$logtime = $row->logtime;
			}
		}
		return $logtime;
	}

	function getPeriods($months){
		$periods = array();
		if((int)$_SESSION['rego']['cur_year'] < (int)date('Y')){
			foreach($months as $k=>$v){
				$icon = '<i class="fa fa-circle-o"></i>&nbsp; ';
				if($k == $_SESSION['rego']['cur_month']){$icon = '<i class="fa fa-edit"></i>&nbsp; ';}
				$periods[$k] = $icon.$v;
			}
		}else{
			foreach($months as $k=>$v){
				$icon = '<i class="fa fa-circle-o"></i>&nbsp; ';
				if($k == $_SESSION['rego']['cur_month']){$icon = '<i class="fa fa-edit"></i>&nbsp; ';}
				if($k <= (date('m')+1)){
					$periods[$k] = $icon.$v;
				}
			}
		}
		return $periods;
	}

	function checkSetupData($cid){
		global $dbc;
		global $lng;
		$msg = '';
		$check = '';
		$msg .= checkCompanySettings($cid);
		//$msg .= checkSystemSettings($cid);
		$msg .= checkPayrollSettings($cid);
		if(!empty($msg)){$check = '<i class="fa fa-arrow-circle-right"></i>&nbsp; <b>'.$lng['System settings'].'</b><br>'.$msg.'<div style="height:5px"></div>';}
		
		return $check;
	}
	function checkCompanySettings($cid){
		global $dbc;
		global $lng;
		$msg = '';
		$caret = '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <i class="fa fa-caret-right"></i>&nbsp ';
		if($res = $dbc->query("SELECT * FROM ".$cid."_company_settings")){
			if($row = $res->fetch_assoc()){
				$pos = unserialize($row['positions']);
				//if(empty($row['en_compname'])){$msg .= $caret.$lng['Company name in English'].'<br>';}
				if(empty($row['th_compname'])){$msg .= $caret.$lng['Company name in Thai'].'<br>';}
				if(empty($row['tax_id'])){$msg .= $caret.$lng['Company Tax ID'].'<br>';}
				if(empty($row['branch'])){$msg .= $caret.$lng['Branch'].'<br>';}
				//if(empty($row->ssf_idnr)){$msg .= $caret.'Social Security Fund number<br>';}
				if(empty($row['th_address'])){$msg .= $caret.$lng['Company address'].'<br>';}
				//if(empty($row['bank_name'])){$msg .= $caret.$lng['Bank name'].'<br>';}
				//if(empty($row['bank_account'])){$msg .= $caret.$lng['Bank account no.'].'<br>';}
				//if(empty($row['en_address'])){$msg .= $caret.'English address<br>';}
				//if(empty($pos)){$msg .= $caret.'Create at least one Position<br>';}
			}
		}
		return $msg;
	}
	function checkSystemSettings($cid){
		global $dbc;
		global $lng;
		$msg = '';
		$caret = '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <i class="fa fa-caret-right"></i>&nbsp ';
		if($res = $dbc->query("SELECT * FROM ".$cid."_system_settings")){
			if($row = $res->fetch_object()){
				if(empty($row->time_zone)){$msg .= $caret.'Time zone<br>';}
				if(empty($row->from_email)){$msg .= $caret.'From email<br>';}
				if(empty($row->reply_email)){$msg .= $caret.'Reply email<br>';}
				//if(empty($row->emp_id_prefix) && empty($row->emp_id_sequence)){$msg .= $caret.'Employee ID format<br>';}
			}
		}
		if(!empty($msg)){$msg = '<i class="fa fa-arrow-circle-right"></i>&nbsp; <b>System settings</b><br>'.$msg.'<div style="height:5px"></div>';}
		return $msg;
	}
	function checkPayrollSettings($cid){
		global $dbc;
		global $lng;
		$msg = '';
		$caret = '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <i class="fa fa-caret-right"></i>&nbsp ';
		if($res = $dbc->query("SELECT * FROM ".$cid."_settings")){
			if($row = $res->fetch_assoc()){
				$addr = unserialize($row['th_addr_detail']);
				if(empty($row['pr_startdate'])){$msg .= $caret.$lng['Payroll startdate'].'<br>';}
				if(empty($addr['number'])){$msg .=  $caret.$lng['Address details'].' - '.$lng['Number'].'<br>';}
				if(empty($addr['moo'])){$msg .= $caret.$lng['Address details'].' - '.$lng['Moo'].'<br>';}
				//if(empty($addr['lane'])){$msg .= $caret.'Thai address detail - Lane<br>';}
				if(empty($addr['subdistrict'])){$msg .= $caret.$lng['Address details'].' - '.$lng['Sub district'].'<br>';}
				if(empty($addr['district'])){$msg .= $caret.$lng['Address details'].' - '.$lng['District'].'<br>';}
				if(empty($addr['province'])){$msg .= $caret.$lng['Address details'].' - '.$lng['Province'].'<br>';}
				if(empty($addr['postal'])){$msg .= $caret.$lng['Address details'].' - '.$lng['Postal code'].'<br>';}
				//if(empty($row['tax_idnr'])){$msg .= $caret.$lng['Company Tax ID'].'<br>';}
				//if(empty($row['sso_idnr'])){$msg .= $caret.$lng['Social Security Fund'].'<br>';}
			}
		}
		return $msg;
	}

	/*function xxxgetRealUserIp(){
		switch(true){
			case (!empty($_SERVER['HTTP_X_REAL_IP'])) : return $_SERVER['HTTP_X_REAL_IP'];
			case (!empty($_SERVER['HTTP_CLIENT_IP'])) : return $_SERVER['HTTP_CLIENT_IP'];
			case (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) : return $_SERVER['HTTP_X_FORWARDED_FOR'];
			default : return $_SERVER['REMOTE_ADDR'];
		}
	}*/

	function getYears(){
		global $lang;
		global $cid;
		global $dbc;
		$data = array();
		if($res = $dbc->query("SELECT years FROM ".$cid."_sys_settings")){
			if($row = $res->fetch_assoc()){
				$tmp = $row['years'];
				$years = explode(',', $tmp);
				foreach($years as $v){
					if($lang == 'en'){
						$data[$v] = (int)$v;
					}else{
						$data[$v] = (int)$v+543;
					}
				}
			}
		}
		return $data;
	}

	function checkEmpRegister($cid){
		global $dbc;
		$empty = true;
		if($res = $dbc->query("SELECT emp_id FROM ".$cid."_employees")){
			if($row = $res->fetch_object()){
				$empty = false;
			}
		}
		return $empty;
	}

	function getFixAllowNames(){
		global $pr_settings;
		$allow = unserialize($pr_settings['fix_allow']);
		$data = array();
		foreach($allow as $k=>$v){
			$data[$k] = $v[$_SESSION['rego']['lang']];
		}
		return $data;
	}	
	function getVarAllowNames(){
		global $pr_settings;
		$allow = unserialize($pr_settings['var_allow']);
		$data = array();
		foreach($allow as $k=>$v){
			$data[$k] = $v[$_SESSION['rego']['lang']];
		}
		return $data;
	}	

	function getAllowances($pr_settings){
		$allow = unserialize($pr_settings['allowances']);
		$data = array();
		foreach($allow as $k=>$v){
			if($v['type'] == 'F' || $v['type'] == 'B'){
				$data['fixed'][$k] = $v;
			}
			if($v['type'] == 'V' || $v['type'] == 'B'){
				$data['var'][$k] = $v;
			}
			$data['en'][$k] = $v['en'];
			$data['th'][$k] = $v['th'];
		}
		return $data;
	}	
	
	function getFixAllowances(){
		global $pr_settings;
		$tmp = unserialize($pr_settings['fix_allow']);
		$data = array();
		foreach($tmp as $k=>$v){
			if($v['apply'] == 1){
				$data[$k] = $v;
			}
		}
		return $data;
	}	
	
	function getVarAllowances(){
		global $pr_settings;
		$tmp = unserialize($pr_settings['var_allow']);
		$data = array();
		foreach($tmp as $k=>$v){
			if($v['apply'] == 1){
				$data[$k] = $v;
			}
		}
		return $data;
	}	

	function getUsedFixAllow($lang){
		global $pr_settings;
		$allow = unserialize($pr_settings['fix_allow']);
		$data = array();
		foreach($allow as $k=>$v){
			if($v['apply'] == 1){
				$data[$k] = $v[$lang];
			}
		}
		return $data;
	}	
	
	function getUsedVarAllow($lang){
		global $pr_settings;
		$allow = unserialize($pr_settings['var_allow']);
		$data = array();
		foreach($allow as $k=>$v){
			if($v['apply'] == 1){
				$data[$k] = $v[$lang];
			}
		}
		return $data;
	}	
	
/*	function xgetVarAllowances($pr_settings){
		$allow = unserialize($pr_settings['allowances']);
		$data = array();
		foreach($allow as $k=>$v){
			if($v['type'] == 'V' || $v['type'] == 'B'){
				$data[$k] = $v;
			}
		}
		return $data;
	}	

	function getAllowNames($pr_settings, $lang){
		$allow = unserialize($pr_settings['allowances']);
		$data = array();
		foreach($allow as $k=>$v){
			$data[$k] = $v[$lang];
		}
		return $data;
	}	
*/

	function getEmployeeStatus($cid){
		global $dbc;
		$data = array('incomplete'=>0,1=>0,2=>0,3=>0,4=>0,5=>0,6=>0,7=>0,8=>0,9=>0);
		$sql = "SELECT emp_id, title, emp_status, idcard_nr, base_salary, startdate, en_name, firstname, lastname FROM ".$cid."_employees";
		if($res = $dbc->query($sql)){
			while($row = $res->fetch_assoc()){
				if($row['emp_status'] == '' || 
					//$row['idcard_nr'] == '?????????????' || 
					//$row['idcard_nr'] == '' || 
					//$row['title'] == '' ||
					$row['base_salary'] == 0 || 
					$row['startdate'] == '' || 
					$row['firstname'] == '' || 
					$row['lastname'] == ''){
					$data[6] += 1;
				}
				$data[$row['emp_status']] += 1;
			}
		}
		return $data;
	}

	function getMissingEmployeesForHistoricData($cid, $month, $ids){
		global $dbc;
		
		$existing_emps = array();
		$where = '';
		if($month != 'all'){
			$where = ' AND month = '.$month;
		}
		$sql = "SELECT emp_id, month FROM ".$_SESSION['rego']['payroll_dbase']." WHERE paid = 'Y'".$where." ORDER BY month, emp_id ASC";
		if($res = $dbc->query($sql)){
			while($row = $res->fetch_assoc()){
				$existing_emps[$row['month']][$row['emp_id']] = $row['emp_id'];
			}
		}
		
		$available_emps = array();
		$sql = "SELECT emp_id, en_name, th_name FROM ".$cid."_employees WHERE pr_status = '0' AND emp_status = '1' AND emp_type != '4' AND emp_type != '5' ORDER BY emp_id ASC";
		if($res = $dbc->query($sql)){
			while($row = $res->fetch_assoc()){
				$available_emps[$row['emp_id']] = $row[$_SESSION['rego']['lang'].'_name'];
			}
		}
		$missing_emps = array();
		if($existing_emps){
			foreach($existing_emps as $key=>$val){
				foreach($available_emps as $k=>$v){
					if(!isset($val[$k]) && in_array($k, $ids)){
						$missing_emps[$key][$k] = $v;
					}
				}
			}
		}else{
			//$missing_emps = $available_emps;
		}
		ksort($missing_emps);
		return $missing_emps;
	}
	
	function getMissingEmployeesFromPayroll($cid, $month){
		global $dbc;
		$existing_emps = array();
		$sql = "SELECT emp_id FROM ".$_SESSION['rego']['payroll_dbase']." WHERE month = '".$month."'";
		if($res = $dbc->query($sql)){
			while($row = $res->fetch_assoc()){
				$existing_emps[] = $row['emp_id'];
			}
		}
		//var_dump($existing_emps);
		$missing_emps = array();
		$sql = "SELECT emp_id, en_name, th_name FROM ".$cid."_employees WHERE pr_calculation = 'Y' AND pr_status = '0' AND emp_status = '1' AND emp_type < 4 ORDER BY emp_id ASC";
		if($res = $dbc->query($sql)){
			while($row = $res->fetch_assoc()){
				if(!in_array($row['emp_id'], $existing_emps)){
					$missing_emps[$row['emp_id']] = $row[$_SESSION['rego']['lang'].'_name'];
				}
			}
		}
		return $missing_emps;
	}
	
	function getEmployees($cid){
		global $dbc;
		//global $departments;
		global $positions;
		//$positions[0] = '';
		//$departments = getDepartmentNames($cid, $_SESSION['rego']['lang']);
		$data = array();
		$res = $dbc->query("SELECT * FROM ".$cid."_employees ORDER BY emp_id ASC");
		//$res = $dbc->query("SELECT * FROM ".$cid."_employees ORDER BY emp_id ASC");
		if($res->num_rows > 0){
			while($row = $res->fetch_assoc()){
				$data[$row['emp_id']]['emp_id'] = $row['emp_id'];
				$data[$row['emp_id']]['title'] = $row['title'];
				$data[$row['emp_id']]['firstname'] = $row['firstname'];
				$data[$row['emp_id']]['lastname'] = $row['lastname'];
				$data[$row['emp_id']]['th_name'] = $row['th_name'];
				$data[$row['emp_id']]['en_name'] = $row['en_name'];
				if($row['position'] > 0){
					$data[$row['emp_id']]['position'] = $positions[$row['position']];
				}else{
					$data[$row['emp_id']]['position'] = '';
				}
				$data[$row['emp_id']]['phone'] = $row['personal_phone'];
				$data[$row['emp_id']]['email'] = $row['personal_email'];
				$data[$row['emp_id']]['idcard_nr'] = $row['idcard_nr'];
				$data[$row['emp_id']]['emp_status'] = $row['emp_status'];
				if(!empty($row['image'])){
					$data[$row['emp_id']]['image'] = $row['image'];
				}else{
					$data[$row['emp_id']]['image'] = 'images/profile_image.jpg';
				}
				$data[$row['emp_id']]['bank'] = $row['bank_code'];
				$data[$row['emp_id']]['account'] = $row['bank_account'];
			}
		}
		return $data;
	}

	function getEmployeeNameId($cid){
		global $dbc;
		$data = array();
		$res = $dbc->query("SELECT emp_id, en_name FROM ".$cid."_employees ORDER BY emp_id ASC");
		if($res->num_rows > 0){
			while($row = $res->fetch_object()){
				$data[$row->emp_id] = $row->en_name;
			}
		}
		ksort($data);
		return $data;
	}
	
	function getVarAllowEmployee($id){
		//global $cid;
		global $dbc;
		$data = array();
		if($res = $dbc->query("SELECT var_allow_dilligence, var_allow_shift, var_allow_transport, var_allow_meal, var_allow_phone, dilligence_status FROM ".$_SESSION['rego']['cid']."_employees WHERE emp_id = '".$id."'")){
			if($row = $res->fetch_assoc()){
				$data['dilligence'] = $row['var_allow_dilligence'];
				$data['shift'] = $row['var_allow_shift'];
				$data['transport'] = $row['var_allow_transport'];
				$data['meal'] = $row['var_allow_meal'];
				$data['phone'] = $row['var_allow_phone'];
				$data['dilligence_status'] = $row['dilligence_status'];
			}
		}else{
			return mysqli_error($dbc);
		}
		return $data;
	}
	
	function getEmployeesByBank($cid, $id, $bank, $filter){
		global $dbc;
		if($filter == 'other'){
			$where = " AND bank_code <> '".$bank."'";
		}else if($filter == 'all'){
			$where = "";
		}else{
			$where = " AND bank_code = '".$bank."'";
		}
		$data = array();
		$sql = "SELECT emp_id, en_name, th_name, title, bank_code, bank_account, bank_branch, bank_account_name FROM ".$cid."_employees WHERE emp_id = '".$id."' AND bank_transfer = 'Y' AND pay_type = '1' AND bank_code != '' AND bank_account != ''".$where;
		if($res = $dbc->query($sql)){
			if($row = $res->fetch_assoc()){
				$data = $row;
			}
		}
		return $data;
	}

	function getEmployeeInfo($cid, $id){
		global $dbc;
		$data = array();
		$sql = "SELECT * FROM ".$cid."_employees WHERE emp_id = '".$id."'";
		if($res = $dbc->query($sql)){
			if($row = $res->fetch_assoc()){
				$data = $row;
			}
		}
		return $data;
	}

	function getEmployeesNameId($cid){
		global $dbc;
		$data = array();
		$sql = "SELECT emp_id, th_name, en_name FROM ".$cid."_employees ORDER BY emp_id ASC";
		if($res = $dbc->query($sql)){
			while($row = $res->fetch_assoc()){
				$data[$row['emp_id']]['en_name'] = $row['en_name'];
				$data[$row['emp_id']]['th_name'] = $row['th_name'];
				$data[$row['emp_id']]['en_id_name'] = $row['emp_id'].' - '.$row['en_name'];
				$data[$row['emp_id']]['th_id_name'] = $row['emp_id'].' - '.$row['th_name'];
			}
		}
		return $data;
	}

	function checkEmployeesForPayroll($cid){
		global $dbc;
		global $lng;
		$msg = '';
		$caret = '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <i class="fa fa-caret-right"></i>&nbsp ';
		//if($res = $dbc->query("SELECT emp_id, title, first_name, last_name, base_salary, idcard_nr, startdate FROM ".$cid."_employees WHERE pr_calculation = 'Y' AND emp_status = '1'")){
		$sql = "SELECT emp_id, title, firstname, en_name, th_name, lastname, base_salary, startdate, emp_status FROM ".$cid."_employees WHERE emp_status = '1' ORDER BY emp_id ASC";
		if($res = $dbc->query($sql)){
			if($res->num_rows > 0){
				while($row = $res->fetch_assoc()){
					
					$missing = array();
					$name = $row[$_SESSION['rego']['lang'].'_name'];
					if($_SESSION['rego']['lang'] == 'en'){
						if(empty($name)){$name = 'no Name';}
					}else{
						if(empty($name)){$name = 'ไม่มีชื่อ';}
					}
					/*if(empty($row['title'])){
						$missing[$row['emp_id']][] = $lng['Title'];
					}*/
					if(empty($row['firstname'])){
						$missing[$row['emp_id']][] = $lng['First name'];
					}
					if(empty($row['lastname'])){
						$missing[$row['emp_id']][] = $lng['Last name'];
					}
					if(empty($row['startdate'])){
						$missing[$row['emp_id']][] = $lng['Joining date'];
					}
					if(empty($row['base_salary'])){
						$missing[$row['emp_id']][] = $lng['Basic salary'];
					}
					if($row['emp_status'] != 1 ){
						$missing[$row['emp_id']][] = $lng['Employee status'];
					}
					
					if($missing){
						$msg .= '<b>&nbsp; <i class="fa fa-arrow-circle-right"></i>&nbsp; '.$row['emp_id'].' - <a href="index.php?H&mn=102&id='.$row['emp_id'].'#personal">'.$name.'</a></b><br>';
						foreach($missing[$row['emp_id']] as $k=>$v){
							//if($k > 0){
								$msg .= $caret.$v.'<br>';
							//}
						}
					}
				}
			}else{
				return '<b><i class="fa fa-exclamation-circle"></i>&nbsp;&nbsp;'.$lng['Employee register is empty'].'</b>';
			}
		}
		return $msg;
	}

	function checkDataForGovForms(){
		global $dbc;
		global $lng;
		$missing = array();
		$msg = '';
		$caret = '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <i class="fa fa-caret-right"></i>&nbsp ';
		if($res = $dbc->query("SELECT * FROM ".$_SESSION['rego']['cid']."_company_settings")){
			if($row = $res->fetch_assoc()){
				if(empty($row[$_SESSION['rego']['lang'].'_compname'])){
					if($_SESSION['rego']['lang'] == 'en'){
						$missing[] = $caret.$lng['Company name in English'].'<br>';
					}else{
						$missing[] = $caret.$lng['Company name in Thai'].'<br>';
					}
				}
				if(empty($row['tax_id'])){
					$missing[] = $caret.$lng['Company Tax ID'].'<br>';
				}
				if(empty($row['branch'])){
					$missing[] = $caret.$lng['Branch'].'<br>';
				}
			}
		}
		if($res = $dbc->query("SELECT * FROM ".$_SESSION['rego']['cid']."_settings")){
			if($row = $res->fetch_assoc()){
				$addr = unserialize($row[$_SESSION['rego']['lang'].'_addr_detail']);
				if(empty($addr['number'])){$missing[] = $caret.$lng['Address details'].' - '.$lng['Number'].'<br>';}
				if(empty($addr['moo'])){$missing[] = $caret.$lng['Address details'].' - '.$lng['Moo'].'<br>';}
				//if(empty($addr['lane'])){$msg .= $caret.'Thai address detail - Lane<br>';}
				if(empty($addr['subdistrict'])){$missing[] = $caret.$lng['Address details'].' - '.$lng['Sub district'].'<br>';}
				if(empty($addr['district'])){$missing[] = $caret.$lng['Address details'].' - '.$lng['District'].'<br>';}
				if(empty($addr['province'])){$missing[] = $caret.$lng['Address details'].' - '.$lng['Province'].'<br>';}
				if(empty($addr['postal'])){$missing[] = $caret.$lng['Address details'].' - '.$lng['Postal code'].'<br>';}
				if(empty($row['sso_idnr'])){$missing[] = $caret.$lng['Social Security Fund'].' No.<br>';}
				//if(!empty($row['pr_startdate'])){$missing[] = $caret.$lng['Payroll startdate'].'<br>';}
			}
		}
		if($missing){
			$msg .= '<b>&nbsp; <i class="fa fa-arrow-circle-right"></i>&nbsp; <a href="index.php?mn=640">System '.$lng['Settings'].'</a></b><br>';
			foreach($missing as $v){
				$msg .= $v;
			}
		}
		
		$sql = "SELECT emp_id, title, firstname, lastname, th_name, en_name, base_salary, tax_id, startdate FROM ".$_SESSION['rego']['emp_dbase']." WHERE emp_status = '1' ORDER BY emp_id ASC";
		if($res = $dbc->query($sql)){
			if($res->num_rows > 0){
				while($row = $res->fetch_assoc()){
					$missing = array();
					$name = $row[$_SESSION['rego']['lang'].'_name'];
					if($_SESSION['rego']['lang'] == 'en'){
						if(empty($name)){$name = 'no Name';}
					}else{
						if(empty($name)){$name = 'ไม่มีชื่อ';}
					}
					if(empty($row['title'])){
						$missing[$row['emp_id']][] = $lng['Title'];
					}
					if(empty($row['firstname'])){
						$missing[$row['emp_id']][] = $lng['First name'];
					}
					if(empty($row['lastname'])){
						$missing[$row['emp_id']][] = $lng['Last name'];
					}
					if(empty($row['tax_id'])){
						$missing[$row['emp_id']][] = $lng['Tax ID no.'];
					}
					
					if($missing){
						$msg .= '<b>&nbsp; <i class="fa fa-arrow-circle-right"></i>&nbsp; '.$row['emp_id'].' - <a href="index.php?H&mn=102&id='.$row['emp_id'].'#personal">'.$name.'</a></b><br>';
						foreach($missing[$row['emp_id']] as $k=>$v){
							//if($k > 0){
								$msg .= $caret.$v.'<br>';
							//}
						}
					}
				}
			}else{
				$msg .= '<b>&nbsp; <i class="fa fa-arrow-circle-right"></i>&nbsp;&nbsp;<a href="index.php?mn=101">'.$lng['Employee register is empty'].'</a></b>';
			}
		}
		return $msg; exit;
	}

	function updateEmployeesForPayroll($cid){
		global $dbc;
		$pr_stat = array();
		//if($res = $dbc->query("SELECT emp_id, title, first_name, last_name, base_salary, idcard_nr, startdate FROM ".$cid."_employees WHERE pr_calculation = 'Y' AND emp_status = '1'")){
		$sql = "SELECT emp_id, title, firstname, lastname, th_name, base_salary, idcard_nr, startdate, emp_status FROM ".$cid."_employees";
		if($res = $dbc->query($sql)){
			if($res->num_rows > 0){
				while($row = $res->fetch_assoc()){
					if($row['emp_status'] == '' || 
						$row['title'] == '' || 
						$row['firstname'] == '' || 
						$row['lastname'] == '' || 
						//$row['idcard_nr'] == '' || 
						$row['base_salary'] == 0 || 
						$row['startdate'] == ''){
						$pr_stat[$row['emp_id']] = 1;
					}else{
						$pr_stat[$row['emp_id']] = 0;
					}
				}
			}
		}
		if($pr_stat){
			foreach($pr_stat as $k=>$v){
				$dbc->query("UPDATE ".$cid."_employees SET pr_status = '".$v."' WHERE emp_id = '".$k."'");
			}
		}
	}

	function getNameFromNumber($num) {
		$numeric = $num % 26;
		$letter = chr(65 + $numeric);
		$num2 = intval($num / 26);
		if ($num2 > 0) {
		return getNameFromNumber($num2 - 1) . $letter;
		} else {
		return $letter;
		}
	}	

	function checkUsers($cid){
		global $dbc;
		$empty = true;
		if($res = $dbc->query("SELECT user_id FROM ".$cid."_users WHERE user_id != '100'")){
			if($row = $res->fetch_object()){
				$empty = false;
			}
		}
		return $empty;
	}

	function getJsonUserEmployees($cid, $lang){
		global $dbc;
		$data = array();
		$sql = "SELECT emp_id, ".$lang."_name FROM ".$cid."_employees WHERE emp_status = 1 ORDER BY emp_id ASC";
		if($res = $dbc->query($sql)){
			if($res->num_rows > 0){
				while($row = $res->fetch_assoc()){
					$data[] = array('data'=>$row['emp_id'], 'value'=>$row[$lang.'_name']);
				}
			}
		}
		return $data;
		//return mysqli_error($dbc);
	}

	/*function getJsonEmployeeIds($cid, $lang, $departments, $emp_group){
		global $dbc;
		$data = '';//array();
		$depts = '';
		foreach($departments as $k=>$v){
			$depts .= $k.',';
		}
		$depts = substr($depts,0,-1);
		$depts = '900';
		$where = '';
		//$depts = implode(',', $departments);
		if($emp_group != 'x'){$where = "AND emp_group = '".$emp_group."'";}
		$res = $dbc->query("SELECT emp_id, ".$lang."_name FROM ".$cid."_employees WHERE dept_code IN (".$depts.") ".$where." ORDER BY emp_id ASC");
		if($res->num_rows > 0){
			while($row = $res->fetch_assoc()){
				$data .= $row['emp_id'].',';
			}
		}
		return $data;
	}*/

	function getUserEmployeesIds($cid, $departments){
		global $dbc;
		$data = '';
		$depts = '';
		foreach($departments as $k=>$v){
			$depts .= $k.',';
		}
		$depts = substr($depts,0,-1);
		$sql = "SELECT emp_id FROM ".$cid."_employees WHERE dept_code IN (".$depts.") ORDER BY emp_id ASC";
		if($res = $dbc->query($sql)){
			while($row = $res->fetch_assoc()){
				$data .= $row['emp_id'].',';
			}
		}
		$data = substr($data,0,-1);
		return $data;
	}

	function getYTDworkedDays($cid){
		global $dbc;
		/*$hol = array();
		$hd = getHolidays($cid);
		foreach($hd as $k=>$v){
			$hol[] = strtotime($v['cdate']);
		}*/
		
		$data = array();
		$xdata = array();
		$days = array();
		$today = strtotime(date('Y-m-d'));
		$sql = "SELECT * FROM ".$cid."_monthly_shiftplan";
		if($res = $dbc->query($sql)){
			while($row = $res->fetch_assoc()){
				$data[] = $row;
			}
		}
		foreach($data as $k=>$v){
			$end = cal_days_in_month(CAL_GREGORIAN,$v['month'],$_SESSION['rego']['cur_year']);
			for($i=1; $i<=$end; $i++){
				if($v['D'.$i] != 'OFF'){
					$xdata[$v['month']][$i] = strtotime($i.'-'.$v['month'].'-'.$_SESSION['rego']['cur_year']);
				}
			}
		}
		
		$xdata = array_map('array_values', $xdata);
		
		foreach($xdata as $key=>$val){
			foreach($val as $k=>$v){
				if($v > $today){
					$days[$key] = $k;
					break;
				}
			}
		}

		for($i=count($days);$i<=12;$i++){
			$days[$i] = 0;
		}
		return $days;
	}

	function getAge($date){	
		$bday = new DateTime($date);
		$today = new DateTime('now');
		$diff = $today->diff($bday);
		$age = '';
		
		if($_SESSION['rego']['lang'] == 'en'){
			if($diff->y > 0){
				$age .= $diff->y;
				if($diff->y == 1){$age .= ' year, ';}else{$age .= ' years, ';}
				//if($diff->m == 1){$age .= $diff->m.' month, ';}else{$age .= $diff->m.' months, ';}
				//if($diff->d == 1){$age .= $diff->d.' day';}else{$age .= $diff->d.' days';}
			}
			if($diff->m > 0){
				$age .= $diff->m;
				if($diff->m == 1){$age .= ' month, ';}else{$age .= ' months, ';}
			}
			if($diff->d > 0){
				$age .= $diff->d;
				if($diff->d == 1){$age .= ' day';}else{$age .= ' days';}
			}
		}else{
			if($diff->y > 0){
				$age .= $diff->y.' ปี ';
				//$age .= $diff->m.' เดือน ';
				//$age .= $diff->d.' วัน';
			}
			if($diff->m > 0){
				$age .= $diff->m.' เดือน ';
			}
			if($diff->d > 0){
				$age .= $diff->d.' วัน';
			}
		}
		return $age;
	}	

	function date_range($first, $last, $step = '+1 day', $output_format = 'd-m-Y') {
		 $dates = array();
		 $current = strtotime($first);
		 $last = strtotime($last);
		 while( $current <= $last ) {
			  //$dates[date('n',$current)][date('d-m-Y',$current)] = array(substr(date('l',$current),0,2), date($output_format, $current), date('w',$current));
			  $dates[date('n',$current)][$current] = date($output_format, $current);
			  $current = strtotime($step, $current);
		 }
		 return $dates;
	}

	function dateRange($first, $last, $step = '+1 day', $output_format = 'd-m-Y') {
		 $dates = array();
		 $current = strtotime($first);
		 $last = strtotime($last);
		 while( $current <= $last ) {
			  //$dates[date('n',$current)][date('d-m-Y',$current)] = array(substr(date('l',$current),0,2), date($output_format, $current), date('w',$current));
			  $dates[] = date($output_format, $current);
			  $current = strtotime($step, $current);
		 }
		 return $dates;
	}

	function isValidDate($date, $format = 'H:i') {
		 $d = DateTime::createFromFormat($format, $date);
		 return $d && $d->format($format) == $date;
	}

	function decimalHours($time){
		if(!empty($time)){
			$hms = explode(":", $time);
			return ($hms[0] + ($hms[1]/60));// + ($hms[2]/3600));
		}
	}
	
	/*function getEmailTemplate($template, $dbx, $field){
		$data['sub'] = '';
		$data['body'] = '';
		$sql = "SELECT * FROM ".$template." WHERE name = '".$field."'";
		if($res = $dbx->query($sql)){
			if($row = $res->fetch_object()){
				$data['sub'] = $row->subject;
				$data['body'] = $row->body;
			}
		}
		return $data;
	}*/
	
	function array_filter_recursive($input){
		 foreach ($input as &$value){
			  if (is_array($value)){
					$value = array_filter_recursive($value);
			  }
		 }
		 return array_filter($input);
	}

	function generateStrongPassword($length = 9, $add_dashes = false, $available_sets = 'luds'){
		$sets = array();
		if(strpos($available_sets, 'l') !== false)
			$sets[] = 'abcdefghjkmnpqrstuvwxyz';
		if(strpos($available_sets, 'u') !== false)
			$sets[] = 'ABCDEFGHJKMNPQRSTUVWXYZ';
		if(strpos($available_sets, 'd') !== false)
			$sets[] = '23456789';
		if(strpos($available_sets, 's') !== false)
			$sets[] = '!@#$%&*?';
		$all = '';
		$password = '';
		foreach($sets as $set)
		{
			$password .= $set[array_rand(str_split($set))];
			$all .= $set;
		}
		$all = str_split($all);
		for($i = 0; $i < $length - count($sets); $i++)
			$password .= $all[array_rand($all)];
		$password = str_shuffle($password);
		if(!$add_dashes)
			return $password;
		$dash_len = floor(sqrt($length));
		$dash_str = '';
		while(strlen($password) > $dash_len)
		{
			$dash_str .= substr($password, 0, $dash_len) . '-';
			$password = substr($password, $dash_len);
		}
		$dash_str .= $password;
		return $dash_str;
	}

	function getRealUserIp(){
		switch(true){
			case (!empty($_SERVER['HTTP_X_REAL_IP'])) : return $_SERVER['HTTP_X_REAL_IP'];
			case (!empty($_SERVER['HTTP_CLIENT_IP'])) : return $_SERVER['HTTP_CLIENT_IP'];
			case (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) : return $_SERVER['HTTP_X_FORWARDED_FOR'];
			default : return $_SERVER['REMOTE_ADDR'];
		}
	}
	
	function writeToLogfile($cid, $field, $message){
		global $dbc;
		$ua = getBrowser();
		$sql = "INSERT INTO ".$cid."_logdata (user_id, user_name, user_ip, user_img, server_ip, platform, browser, uri, $field) VALUES (
			'".$dbc->real_escape_string($_SESSION['rego']['id'])."', 
			'".$dbc->real_escape_string($_SESSION['rego']['name'])."', 
			'".$dbc->real_escape_string($_SERVER['REMOTE_ADDR'])."', 
			'".$dbc->real_escape_string($_SESSION['rego']['img'])."', 
			'".$dbc->real_escape_string($_SERVER['SERVER_ADDR'])."', 
			'".$dbc->real_escape_string($ua['platform'])."', 
			'".$dbc->real_escape_string($ua['name'].' '.$ua['version'])."', 
			'".$dbc->real_escape_string($_SERVER['REQUEST_URI'])."', 
			'".$dbc->real_escape_string($message)."')";
		//return $sql;
        if($dbc->query($sql)){
			return 'ok';
        }else{
			return 'error : '.mysqli_error($dbc);
		}
    }	
	
	function getBrowser() { 
		$u_agent = $_SERVER['HTTP_USER_AGENT']; 
		$bname = 'Unknown';
		$platform = 'Unknown';
		$version= "";
	
		//First get the platform?
		if (preg_match('/linux/i', $u_agent)) {
			$platform = 'linux';
		}elseif (preg_match('/macintosh|mac os x/i', $u_agent)) {
			$platform = 'mac';
		}elseif (preg_match('/windows|win32/i', $u_agent)) {
			$platform = 'windows';
		}
		
		// Next get the name of the useragent yes seperately and for good reason
		if(preg_match('/MSIE/i',$u_agent) && !preg_match('/Opera/i',$u_agent)) { 
			$bname = 'Internet Explorer'; 
			$ub = "MSIE"; 
		}elseif(preg_match('/Firefox/i',$u_agent)){ 
			$bname = 'Mozilla Firefox'; 
			$ub = "Firefox"; 
		}elseif(preg_match('/Chrome/i',$u_agent)){ 
			$bname = 'Google Chrome'; 
			$ub = "Chrome"; 
		}elseif(preg_match('/Safari/i',$u_agent)){ 
			$bname = 'Apple Safari'; 
			$ub = "Safari"; 
		}elseif(preg_match('/Opera/i',$u_agent)){ 
			$bname = 'Opera'; 
			$ub = "Opera"; 
		}elseif(preg_match('/Netscape/i',$u_agent)){ 
			$bname = 'Netscape'; 
			$ub = "Netscape"; 
		} 
		
		// finally get the correct version number
		$known = array('Version', $ub, 'other');
		$pattern = '#(?<browser>' . join('|', $known) .
		')[/ ]+(?<version>[0-9.|a-zA-Z.]*)#';
		if (!preg_match_all($pattern, $u_agent, $matches)) {
			// we have no matching number just continue
		}
		
		// see how many we have
		$i = count($matches['browser']);
		if ($i != 1) {
			//we will have two since we are not using 'other' argument yet
			//see if version is before or after the name
			if (strripos($u_agent,"Version") < strripos($u_agent,$ub)){
				$version= $matches['version'][0];
			}else {
				$version= $matches['version'][1];
			}
		}else {
			$version= $matches['version'][0];
		}
		
		// check if we have a number
		if ($version==null || $version=="") {$version="?";}
		
		return array(
			'userAgent' => $u_agent,
			'name'      => $bname,
			'version'   => $version,
			'platform'  => $platform,
			'pattern'    => $pattern
		);
	} 

?>