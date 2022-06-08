<?php
	
	function langDate($date, $lang){
		if($lang == 'en'){return $date;}
		return date('d-m-', strtotime($date)).(date('Y', strtotime($date))+543);
	}

	function getWordsFromAmount($number, $lang){
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

	function getJsonCustoners($cid, $lang){
		global $dba;
		$data = array();
		$sql = "SELECT clientID, ".$lang."_compname, ".$lang."_billing, wht, email FROM rego_customers WHERE status = 1 ORDER BY clientID ASC";
		if($res = $dba->query($sql)){
			if($res->num_rows > 0){
				while($row = $res->fetch_assoc()){
					$data[] = array('data'=>$row[$lang.'_compname'], 'value'=>strtoupper($row['clientID']).' - '.$row[$lang.'_compname'],'id'=>$row['clientID'], 'address'=>$row[$lang.'_billing'], 'wht'=>$row['wht'], 'email'=>$row['email']);
				}
			}
		}
		return $data;
		//return mysqli_error($dbc);
	}

	function getJsonInvoices($lang){
		global $dba;
		$data = array();
		$sql = "SELECT * FROM rego_invoices ORDER BY id DESC";
		if($res = $dba->query($sql)){
			if($res->num_rows > 0){
				while($row = $res->fetch_assoc()){
					$data[] = array('data'=>$row['inv'], 'value'=>$row['inv'].' :: '.$row['inv_date'],'id'=>$row['id']);
				}
			}
		}
		return $data;
		//return mysqli_error($dbc);
	}

	function getXrayUsers(){
		global $dba;
		global $cid;
		$data = array();
		$sql = "SELECT * FROM rego_users WHERE user_id != '5a6effb9c34ab' AND status = '1'";
		if($res = $dba->query($sql)){
			while($row = $res->fetch_assoc()){
				$data[$row['user_id']]['name'] = $row['name'];
				$data[$row['user_id']]['email'] = $row['email'];
				$data[$row['user_id']]['img'] = $row['img'];
			}
		}
		return $data;
	}
	
	function checkHolidaysDB($year){
		global $dba;
		$holidays = false;
		$sql = "SELECT id, year FROM rego_default_holidays WHERE year = $year GROUP BY year";
		if($res = $dba->query($sql)){
			if($res->num_rows > 0){
				$holidays = true;
			}
		}
		return $holidays;
	}

	function getSupportTickets(){
		global $dba;
		$data['new'] = 0;
		$data['open'] = 0;
		$sql = "SELECT * FROM rego_support_desk";
		if($res = $dba->query($sql)){
			while($row = $res->fetch_assoc()){
				if($row['status'] == 0){
					$data['open'] ++;
				}
				if($row['alert'] == 0){
					$data['new'] ++;
				}
			}
		}
		return $data;
	}

	function getEmailTemplate($field){
		global $dba;
		$data['sub'] = '';
		$data['body'] = '';
		$sql = "SELECT * FROM rego_default_email_templates WHERE name = '".$field."'";
		if($res = $dba->query($sql)){
			if($row = $res->fetch_assoc()){
				$data['sub'] =  $row['subject_'.$_SESSION['RGadmin']['lang']];
				$data['body'] = $row['body_'.$_SESSION['RGadmin']['lang']];
			}
		}
		return $data;
	}

	function getRealUserIp(){
		switch(true){
			case (!empty($_SERVER['HTTP_X_REAL_IP'])) : return $_SERVER['HTTP_X_REAL_IP'];
			case (!empty($_SERVER['HTTP_CLIENT_IP'])) : return $_SERVER['HTTP_CLIENT_IP'];
			case (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) : return $_SERVER['HTTP_X_FORWARDED_FOR'];
			default : return $_SERVER['REMOTE_ADDR'];
		}
	}

	function getLangVariables($lng){
		global $dbx;
		$data = array();
		if($res = $dbx->query("SELECT * FROM xray_application_language")){
			while($row = $res->fetch_object()){
				if($lng == 'en'){
					$data[$row->code] = $row->en;
				}else{
					$data[$row->code] = $row->th;
				}
			}
		}
		return $data;
	}

	function getAdminAppMetadata($field, $lng){
		global $dba;
		$sql = "SELECT ".$field." FROM rego_def_system_settings";
		if($res = $dba->query($sql)){
			$row = $res->fetch_object();
			$data = unserialize($row->$field);
			if($lng != 'all'){
				$data = $data[$lng];
			}
			return $data;
		}else{
			return array();
		}
	}

	function xxxgetClients(){
		global $dba;
		$data = array();
		$res = $dba->query("SELECT * FROM xhr_clients WHERE status = '1' ORDER BY clientID ASC");
		if($res->num_rows > 0){
			while($row = $res->fetch_object()){
				$data[$row->clientID] = array('en'=>$row->en_compname,'th'=>$row->th_compname,'logo'=>$row->logofile);
			}
		}
		return $data;
	}
	
	function getCustomers(){
		global $dba;
		$data = array();
		$res = $dba->query("SELECT * FROM rego_customers WHERE status = '1' ORDER BY clientID ASC");
		if($res->num_rows > 0){
			while($row = $res->fetch_object()){
				$data[$row->clientID] = array('en'=>$row->en_compname,'th'=>$row->th_compname);
			}
		}
		return $data;
	}

	function getShiftplanList($cid){
		global $dba;
		$data = array();
		$sql = "SELECT * FROM rego_default_shiftplans";
		if($res = $dba->query($sql)){
			while($row = $res->fetch_object()){
				$data[$row->code] = $row->description;
			}
		}
		return $data;
	}
	
	function hoursRange($lower = 0, $upper = 86400, $step = 1800, $format = 'h:i a'){
		 $times = array();
		 foreach(range($lower, $upper, $step) as $increment){
			  $increment = gmdate('H:i', $increment);
			  list($hour, $minutes) = explode(':', $increment);
			  $date = new DateTime($hour . ':' . $minutes);
			  $times[(string)$increment] = $date->format($format);
		 }
		 return $times;
	}	
	
	function getDefaultTimeSettings(){
		global $dba;
		$row = array();
		if($res = $dba->query("SELECT * FROM rego_def_leave_time_settings")){
			$row = $res->fetch_assoc();
		}
		return $row;
	}
	
	function getDefaultShiftplan(){
		global $dba;
		$data = array();
		$sql = "SELECT shiftplan FROM rego_default_leave_time_settings";
		if($res = $dba->query($sql)){
			if($row = $res->fetch_object()){
				$data = unserialize($row->shiftplan);
			}
		}
		return $data;
	}

	function generateStrongPassword($length = 8, $add_dashes = false, $available_sets = 'luds'){
		$sets = array();
		if(strpos($available_sets, 'l') !== false)
			$sets[] = 'abcdefghjkmnpqrstuvwxyz';
		if(strpos($available_sets, 'u') !== false)
			$sets[] = 'ABCDEFGHJKMNPQRSTUVWXYZ';
		if(strpos($available_sets, 'd') !== false)
			$sets[] = '23456789';
		if(strpos($available_sets, 's') !== false)
			//$sets[] = '!@#$%&*?';
			$sets[] = '@#';
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

	function getNewCustomerID(){
		global $dba;
		$res = $dba->query("SELECT clientID FROM rego_customers ORDER BY id DESC LIMIT 1");
		if($row = $res->fetch_object()){
			$newid = ((int)substr($row->clientID, 4))+1;
		}else{
			$newid = '1000';
		}
		return 'rego'.sprintf("%05d",$newid);
	}
	
	function getFirstCustomerID(){
		global $dba;
		global $client_prefix;
		$id = 1000;
		if($res = $dba->query("SELECT clientID FROM rego_customers")){
			if($res->num_rows > 0){
				while($row = $res->fetch_object()){
					//$ids[] = (int)substr($row->clientID, 4);
					$ids[] = (int)filter_var($row->clientID, FILTER_SANITIZE_NUMBER_INT);
				}
				sort($ids);
				//unset($ids[3],$ids[4]);
				if(!in_array($id, $ids)){
					$id = 1000;
				}else{
					for($id=reset($ids); in_array($id, $ids); $id++);
				}
			}
		}
		//return $id;
		return $client_prefix.sprintf("%04d",$id);
	}

	function getEmpCount($cid){
		global $prefix;
		global $my_database;
		global $my_password;
		global $my_username;
		$count = 0;
		
		$my_dbcname = $prefix.$cid;	
		$dbc = new mysqli($my_database,$my_username,$my_password,$my_dbcname);
		mysqli_set_charset($dbc,"utf8");
		
		$dbname = $cid."_employees";
		$sql = "SELECT emp_status FROM $dbname WHERE emp_status = '1' OR emp_status = '4'";
		if($res = $dbc->query($sql)){
			$count = $res->num_rows;
		}
		return $count;
		//return mysqli_error($dbc);
	}

	function getEmpLogCount($clients){
		global $prefix;
		global $my_database;
		global $my_password;
		global $my_username;
		$count = array();
		
		foreach($clients as $key=>$val){
			$my_dbcname = $prefix.$key;	
			$dbc = new mysqli($my_database,$my_username,$my_password,$my_dbcname);
			mysqli_set_charset($dbc,"utf8");
			$dbname = $key."_employees";
			$sql = "SELECT emp_status, allow_login FROM $dbname WHERE emp_status = '1' AND allow_login = 'Y'";
			if($res = $dbc->query($sql)){
				$count[$key] = $res->num_rows;
			}
		}
		return $count;
		//return mysqli_error($dbc);
	}

	function everdayConsentCheckTerms($terms_consent_date, $days,$email,$terms_consent_change,$limitDay,$dropdownValue)
	{
		global $dba;


		$terms_consent_date_value = $terms_consent_date; // m-d-Y format

		$formatDate = DateTime::createFromFormat("m-d-Y H:i:s" , $terms_consent_date_value);
		$formatDate->format('Y-m-d H:i:s');

		$hours = $days; // hours amount (integer) you want to add
		$modified = (clone $formatDate)->add(new DateInterval("PT{$hours}H")); // use clone to avoid modification of $now object
		$dateWith24Hours = $modified->format('Y-m-d H:i:s'); 

		$formatDate1 = DateTime::createFromFormat("m-d-Y H:i:s" , $terms_consent_date_value);
		$formattedDate1=  $formatDate1->format('Y-m-d H:i:s');

		$currentDate = date('Y-m-d H:i:s');

		$datetime1 = new DateTime($currentDate);
		$datetime2 = new DateTime($dateWith24Hours);

		$interval = $datetime1->diff($datetime2);
		$days2 = $interval->days ;

		if($dropdownValue == '1')
		{
			// if($days2 >= $limitDay)
			// {
				// reset the consent for terms 
				$dba->query("UPDATE rego_users SET terms_consent = '0'  ,  terms_consent_change = '1'  WHERE username = '".$email."'");
			// }
		}
		else if($dropdownValue == '2')
		{
			if($days2 >= $limitDay)
			{
				// reset the consent for terms 
				$dba->query("UPDATE rego_users SET terms_consent = '0'  ,  terms_consent_change = '0' WHERE username = '".$email."'");
			}
			else if($terms_consent_change == '1')
			{
				$dba->query("UPDATE rego_users SET terms_consent = '0'  ,  terms_consent_change = '0' WHERE username = '".$email."'");
			}
		}		
		else if($dropdownValue == '3')
		{
			if($days2 >= $limitDay)
			{
				// reset the consent for terms 
				$dba->query("UPDATE rego_users SET terms_consent = '0'  ,  terms_consent_change = '0' WHERE username = '".$email."'");

			}
			else if($terms_consent_change == '1')
			{
				$dba->query("UPDATE rego_users SET terms_consent = '0'  ,  terms_consent_change = '0' WHERE username = '".$email."'");
			}
		}		
		else if($dropdownValue == '4')
		{
			if($days2 >= $limitDay)
			{
				// reset the consent for terms 
				$dba->query("UPDATE rego_users SET terms_consent = '0'  ,  terms_consent_change = '0' WHERE username = '".$email."'");
			}
			else if($terms_consent_change == '1')
			{
				$dba->query("UPDATE rego_users SET terms_consent = '0'  ,  terms_consent_change = '0' WHERE username = '".$email."'");
			}
		}		
		else if($dropdownValue == '5')
		{
			// reset the consent for terms 
			if($terms_consent_change == '1')
			{
				$dba->query("UPDATE rego_users SET terms_consent = '0'  ,  terms_consent_change = '0' WHERE username = '".$email."'");
			}
		}
	}	

	function everdayConsentCheckPrivacy($terms_consent_date, $days,$email,$terms_consent_change,$limitDay,$dropdownValue)
	{
		global $dba;
		

		$terms_consent_date_value = $terms_consent_date; // m-d-Y format

		$formatDate = DateTime::createFromFormat("m-d-Y H:i:s" , $terms_consent_date_value);
		$formatDate->format('Y-m-d H:i:s');

		$hours = $days; // hours amount (integer) you want to add
		$modified = (clone $formatDate)->add(new DateInterval("PT{$hours}H")); // use clone to avoid modification of $now object
		$dateWith24Hours = $modified->format('Y-m-d H:i:s'); 

		$formatDate1 = DateTime::createFromFormat("m-d-Y H:i:s" , $terms_consent_date_value);
		$formattedDate1=  $formatDate1->format('Y-m-d H:i:s');

		$currentDate = date('Y-m-d H:i:s');

		$datetime1 = new DateTime($currentDate);
		$datetime2 = new DateTime($dateWith24Hours);

		$interval = $datetime1->diff($datetime2);
		$days2 = $interval->days ;

		if($dropdownValue == '1')
		{
			// if($days2 >= $limitDay)
			// {
				// reset the consent for terms 
				$dba->query("UPDATE rego_users SET privacy_consent = '0' , privacy_consent_change = '1'  WHERE username = '".$email."'");
			// }
		}
		else if($dropdownValue == '2')
		{
			if($days2 >= $limitDay)
			{
				// reset the consent for terms 
				$dba->query("UPDATE rego_users SET privacy_consent = '0'  ,  privacy_consent_change = '0' WHERE username = '".$email."'");
			}
			else if($terms_consent_change == '1')
			{
				$dba->query("UPDATE rego_users SET privacy_consent = '0'  ,  privacy_consent_change = '0' WHERE username = '".$email."'");
			}
		}		
		else if($dropdownValue == '3')
		{
			if($days2 >= $limitDay)
			{
				// reset the consent for terms 
				$dba->query("UPDATE rego_users SET privacy_consent = '0'  ,  privacy_consent_change = '0' WHERE username = '".$email."'");
			}
			else if($terms_consent_change == '1')
			{
				$dba->query("UPDATE rego_users SET privacy_consent = '0'  ,  privacy_consent_change = '0' WHERE username = '".$email."'");
			}
		}		
		else if($dropdownValue == '4')
		{
			if($days2 >= $limitDay)
			{
				// reset the consent for terms 
				$dba->query("UPDATE rego_users SET privacy_consent = '0'  ,  privacy_consent_change = '0' WHERE username = '".$email."'");			
			}
			else if($terms_consent_change == '1')
			{
				$dba->query("UPDATE rego_users SET privacy_consent = '0'  ,  privacy_consent_change = '0' WHERE username = '".$email."'");
			}
		}		
		else if($dropdownValue == '5')
		{
			// reset the consent for terms 
			if($terms_consent_change == '1')
			{
				$dba->query("UPDATE rego_users SET privacy_consent = '0'  ,  privacy_consent_change = '0' WHERE username = '".$email."'");
			}
		}
	}	

	function everdayConsentCheckCookie($terms_consent_date, $days,$email,$terms_consent_change,$limitDay,$dropdownValue)
	{
		global $dba;
		

		$terms_consent_date_value = $terms_consent_date; // m-d-Y format

		$formatDate = DateTime::createFromFormat("m-d-Y H:i:s" , $terms_consent_date_value);
		$formatDate->format('Y-m-d H:i:s');

		$hours = $days; // hours amount (integer) you want to add
		$modified = (clone $formatDate)->add(new DateInterval("PT{$hours}H")); // use clone to avoid modification of $now object
		$dateWith24Hours = $modified->format('Y-m-d H:i:s'); 

		$formatDate1 = DateTime::createFromFormat("m-d-Y H:i:s" , $terms_consent_date_value);
		$formattedDate1=  $formatDate1->format('Y-m-d H:i:s');

		$currentDate = date('Y-m-d H:i:s');

		$datetime1 = new DateTime($currentDate);
		$datetime2 = new DateTime($dateWith24Hours);

		$interval = $datetime1->diff($datetime2);
		$days2 = $interval->days ;

		if($dropdownValue == '1')
		{
			// if($days2 >= $limitDay)
			// {
				// reset the consent for terms 
				$dba->query("UPDATE rego_users SET cookie_consent = '0' , cookie_consent_change = '1' WHERE username = '".$email."'");
			// }
		}
		else if($dropdownValue == '2')
		{
			if($days2 >= $limitDay)
			{
				// reset the consent for terms 
				$dba->query("UPDATE rego_users SET cookie_consent = '0'  ,  cookie_consent_change = '0' WHERE username = '".$email."'");
			}
			else if($terms_consent_change == '1')
			{
				$dba->query("UPDATE rego_users SET cookie_consent = '0'  ,  cookie_consent_change = '0' WHERE username = '".$email."'");
			}
		}		
		else if($dropdownValue == '3')
		{
			if($days2 >= $limitDay)
			{
				// reset the consent for terms 
				$dba->query("UPDATE rego_users SET cookie_consent = '0'  ,  cookie_consent_change = '0' WHERE username = '".$email."'");
			}
			else if($terms_consent_change == '1')
			{
				$dba->query("UPDATE rego_users SET cookie_consent = '0'  ,  cookie_consent_change = '0' WHERE username = '".$email."'");
			}
		}		
		else if($dropdownValue == '4')
		{
			if($days2 >= $limitDay)
			{
				// reset the consent for terms 
				$dba->query("UPDATE rego_users SET cookie_consent = '0'  ,  cookie_consent_change = '0' WHERE username = '".$email."'");	
			}
			else if($terms_consent_change == '1')
			{
				$dba->query("UPDATE rego_users SET cookie_consent = '0'  ,  cookie_consent_change = '0' WHERE username = '".$email."'");
			}
		}		
		else if($dropdownValue == '5')
		{
			// reset the consent for terms 
			if($terms_consent_change == '1')
			{
				$dba->query("UPDATE rego_users SET cookie_consent = '0'  ,  cookie_consent_change = '0' WHERE username = '".$email."'");
			}
		}
	}
 






























