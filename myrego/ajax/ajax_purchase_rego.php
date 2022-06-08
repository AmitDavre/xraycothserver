<?

	if(session_id()==''){session_start();}
	ob_start();
	
	include("../../dbconnect/db_connect.php");
	include(DIR.'files/functions.php');
	include(DIR.'files/arrays_'.$lang.'.php');
	//var_dump($_REQUEST); exit;
	
	if($_REQUEST['upgrade_period'] == 'fullyear'){
		$new_start = date('d-m-Y');
		$new_end = date('d-m-Y', strtotime(date('d-m-Y', strtotime($new_start)).'+ 1 year'));
		$price = $_REQUEST['new_price'];
		$discount = $_REQUEST['discount'];
		$price_rem_period = $_REQUEST['prev_price'] / 365 * $_REQUEST['days_left'];
		$price_upgrade = $price - $price_rem_period - $discount;
	}else if($_REQUEST['upgrade_period'] == 'remaining'){
		$new_start = $_REQUEST['prev_start'];
		$new_end = $_REQUEST['prev_end'];
		$price = $_REQUEST['new_price'] / 365 * $_REQUEST['days_left'];
		$discount = $_REQUEST['discount'] / 365 * $_REQUEST['days_left'];
		$price_rem_period = $_REQUEST['prev_price'] / 365 * $_REQUEST['days_left'];
		$price_upgrade = $price - $price_rem_period - $discount;
	}else{
		$new_start = $_REQUEST['prev_end'];
		$new_end = date('d-m-Y', strtotime(date('d-m-Y', strtotime($new_start)).'+ 1 year'));
		$price = $_REQUEST['new_price'];
		$discount = $_REQUEST['discount'];
		$price_rem_period = 0;
		$price_upgrade = $price - $discount;
	}
	
	$price_year = round($_REQUEST['new_price'],2);
	$price_period = round($price,2);
	$price_rem_period = round($price_rem_period,2);
	$price_amount = $price_period - $price_rem_period;
	$price_upgrade = round($price_upgrade,2);
	$price_discount = round($discount,2);
	$vat = round(($price_upgrade * $_REQUEST['per']/100),2);
	$price_total = round(($price_upgrade + $vat),2);
	$price_due = $price_total;
	
	if($_REQUEST['certificate'] == 'Y'){
		$price_wht = round(($price_upgrade * $_REQUEST['wht_per']/100),2);
		$price_net = $price_total - $price_wht;
		//$text_net = getWordsFromAmount($price_net, $lang);
		$price_due = $price_net;
	}else{
		$price_wht = '0.00';
		$price_net = '0.00';
		//$text_net = '';
	}
	
	for($i=1;$i<=9;$i++){
		$body[$i]['description'] = '';
		$body[$i]['quantity'] = '';
		$body[$i]['unit'] = '';
		$body[$i]['per'] = '';
		$body[$i]['amount'] = '';
	}
	$body[1]['description'] = $lng['Service fee for access to the RegoHR.com platform'];
	$body[1]['quantity'] = 1;
	$body[1]['unit'] = number_format($price_amount,2);
	$body[1]['per'] = $_REQUEST['per'];
	$body[1]['amount'] = number_format($price_amount,2);
	$body[2]['description'] = $lng['Subscription'].' : '.$version[$_REQUEST['new_version']];
	$body[3]['description'] = $lng['Max employees'].' : '.$_REQUEST['max_employees'];
	$body[4]['description'] = $lng['Period'].' : '.$lng['From'].' '.$new_start.' '.$lng['Until'].' '.$new_end;
	//var_dump($body);
	
	$data['cid'] = $_REQUEST['cid'];
	$data['clientID'] = $rego;
	$data['period_start'] = $new_start;
	$data['period_end'] = $new_end;
	$data['company'] = $_REQUEST['company'];
	$data['address'] = $_REQUEST['address'];
	$data['version'] = $_REQUEST['new_version'];
	$data['max_employees'] = $_REQUEST['max_employees'];
	$data['upgrade_period'] = $_REQUEST['upgrade_period'];
	$data['price_year'] = $price_year;
	$data['price_period'] = $price_period;
	$data['price_amount'] = $price_amount;
	$data['price_discount'] = $price_discount;
	$data['price_remain'] = $price_rem_period;
	$data['price_upgrade'] = $price_upgrade;
	$data['body'] = serialize($body);
	$data['price_sub'] = $price_amount;
	$data['vat_per'] = $_REQUEST['per'];
	$data['price_vat'] = $vat;
	$data['price_total'] = $price_total;
	$data['wht'] = $_REQUEST['wht'];
	$data['wht_per'] = $_REQUEST['wht_per'];
	$data['price_wht'] = $price_wht;
	$data['price_net'] = $price_net;
	$data['price_due'] = $price_due;
	$data['inv_date'] = $_REQUEST['inv_date'];
	$data['inv_due'] = $_REQUEST['inv_due'];
	$data['inv_number'] = $_REQUEST['inv_number'];
	$data['status'] = 0;
	//var_dump($data); exit;
	
	if(isset($_REQUEST['confirm'])){
		$template = array();
		$res = $dbx->query("SELECT * FROM rego_documents WHERE id = 1");
		if($row = $res->fetch_assoc()){
			$template = $row;
		}	
		//var_dump($template); exit;
		$nr = 1;
		$res = $dbx->query("SELECT * FROM rego_invoices ORDER BY id DESC LIMIT 1");
		if($row = $res->fetch_assoc()){
			$nr = $row['id']+1;
		}
		$inv_number = $template['inv_prefix'].date($template['inv_date']).'-'.sprintf('%04d', $nr);
		$inv_date = date('d-m-Y');
		$inv_due = date('d-m-Y', strtotime($template['due'], strtotime($inv_date)));
		
		$sql = "INSERT INTO rego_invoices (inv, cid, clientID, customer, address, subscription, inv_date, inv_due, body, discount, subtotal, vat, total, wht_percent, wht_amount, net_amount, status) VALUES (";
		$sql .= "'".mysqli_real_escape_string($dbx, $inv_number)."',";
		$sql .= "'".mysqli_real_escape_string($dbx, $cid)."',";
		$sql .= "'".mysqli_real_escape_string($dbx, $rego)."',";
		$sql .= "'".mysqli_real_escape_string($dbx, $_REQUEST['company'])."',";
		$sql .= "'".mysqli_real_escape_string($dbx, $_REQUEST['address'])."',";
		$sql .= "'".mysqli_real_escape_string($dbx, $_REQUEST['new_version'])."',";
		$sql .= "'".mysqli_real_escape_string($dbx, $inv_date)."',";
		$sql .= "'".mysqli_real_escape_string($dbx, $inv_due)."',";
		$sql .= "'".mysqli_real_escape_string($dbx, serialize($body))."',";
		$sql .= "'".mysqli_real_escape_string($dbx, $price_discount)."',";
		$sql .= "'".mysqli_real_escape_string($dbx, $price_upgrade)."',";
		$sql .= "'".mysqli_real_escape_string($dbx, $vat)."',";
		$sql .= "'".mysqli_real_escape_string($dbx, $price_total)."',";
		$sql .= "'".mysqli_real_escape_string($dbx, $_REQUEST['wht_per'])."',";
		$sql .= "'".mysqli_real_escape_string($dbx, $price_wht)."',";
		$sql .= "'".mysqli_real_escape_string($dbx, $price_net)."',";
		$sql .= "'".mysqli_real_escape_string($dbx, 1)."')";
		//var_dump($sql); exit;

		if($dbx->query($sql)){
			echo 'success';
		}else{
			echo mysqli_error($dbx);
			//var_dump(mysqli_error($dbc));
		}
		/*$sql = "INSERT INTO rego_purchase_draft (";
		foreach($data as $k=>$v){
			$sql .= $k.",";
		}
		$sql = substr($sql, 0, -1).") VALUES (";
		foreach($data as $k=>$v){
			$sql .= "'".mysqli_real_escape_string($dbx, $v)."',";
		}
		$sql = substr($sql, 0, -1).") ON DUPLICATE KEY UPDATE ";
		foreach($data as $k=>$v){
			$sql .= $k." = VALUES(".$k."),"; 
		}
		$sql = substr($sql, 0, -1);	
		//var_dump($sql); exit;
		
		if($dbx->query($sql)){
			echo 'success';
		}else{
			echo mysqli_error($dbx);
			//var_dump(mysqli_error($dbc));
		}*/
	}
	//exit;
	//var_dump($data); exit;
	//var_dump($_REQUEST); exit;
	ob_clean();
	echo json_encode($data); exit; 
