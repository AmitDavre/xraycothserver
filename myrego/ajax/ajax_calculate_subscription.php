<?

	if(session_id()==''){session_start();}
	ob_start();
	
	include("../../dbconnect/db_connect.php");
	include(DIR.'files/functions.php');
	include(DIR.'files/arrays_'.$lang.'.php');
	
	$sql = "SELECT price_schedule FROM rego_company_settings";
	if($res = $dbx->query($sql)){
		if($row = $res->fetch_assoc()){
			$price_table = unserialize($row['price_schedule']);
		}
	}
	//var_dump($price_table); //exit;
	//var_dump($_REQUEST); exit;
	
	$ver = $_REQUEST['new_version'];

	$period_start = $_REQUEST['period_start'];
	$period_end = $_REQUEST['period_end'];
	$price = $price_table[$ver]['price_year'];
	$discount = $price_table[$ver]['discount'];
	$price_rem_period = 0;
	$price_upgrade = $price - $discount;
	
	$price_year = round($price_table[$ver]['price_year'],2);
	$price_period = round($price,2);
	$price_rem_period = round($price_rem_period,2);
	$price_amount = $price_period - $price_rem_period;
	$price_upgrade = round($price_upgrade,2);
	$price_discount = round($discount,2);
	$vat = round(($price_upgrade * 0.07),2);
	$price_total = round(($price_upgrade + $vat),2);
	$price_net = $price_total;
	$price_wht = 0;
	
	if($_REQUEST['certificate'] == 'Y'){
		$price_wht = round(($price_upgrade * 0.03),2);
		$price_net -= $price_wht;
	}
	
	$data['version'] = (int)$ver;
	$data['max_employees'] = $price_table[$ver]['max_employees'];
	$data['upgrade_period'] = '';
	$data['period_start'] = $period_start;
	$data['period_end'] = $period_end;
	$data['price_year'] = number_format($price_year,2);
	$data['price_period'] = number_format($price_period,2);
	$data['price_remain'] = number_format($price_rem_period,2);
	$data['price_discount'] = number_format($price_discount,2);
	$data['price_sub'] = number_format($price_amount,2);
	$data['price_vat'] = number_format($vat,2);
	$data['price_wht'] = number_format($price_wht,2);
	$data['price_topay'] = number_format($price_net,2);
	$data['price_net'] = $price_net * 100;
	
	unset($_SESSION['rego']['upgrade']);
	$_SESSION['rego']['new'] = $data;
	
	//var_dump($data); exit;
	//var_dump($_REQUEST); exit;
	ob_clean();
	echo json_encode($data); exit; 
