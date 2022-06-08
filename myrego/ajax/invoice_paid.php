<?

	if(session_id()==''){session_start();}
	ob_start();
	include("../../dbconnect/db_connect.php");
	
	$sql = "UPDATE rego_purchase_draft SET status = 2 WHERE clientID = '".$cid."'";
	if($dbx->query($sql)){
		ob_clean(); echo 'success';
	}else{
		ob_clean(); echo mysqli_error($dbx);
	}

	$draft = array();
	if($res = $dbx->query("SELECT * FROM rego_purchase_draft WHERE clientID = '".$cid."'")){
		if($row = $res->fetch_assoc()){
			$data = $row;
			//$draft['text_total'] = getWordsFromAmount($draft['price_total'], $lang);
			//$draft['text_net'] = getWordsFromAmount($draft['price_net'], $lang);
		}
	}
	var_dump($data);

	$sql = "INSERT INTO rego_invoices (inv, clientID, subscription, inv_date, inv_due, body, sub, vat, total, wht_percent, wht, paid_by, pay_date) VALUES (";
		$sql .= "'".mysqli_real_escape_string($dbx, $data['inv_number'])."',";
		$sql .= "'".mysqli_real_escape_string($dbx, $cid)."',";
		$sql .= "'".mysqli_real_escape_string($dbx, $data['version'])."',";
		$sql .= "'".mysqli_real_escape_string($dbx, $data['inv_date'])."',";
		$sql .= "'".mysqli_real_escape_string($dbx, $data['inv_due'])."',";
		$sql .= "'".mysqli_real_escape_string($dbx, serialize(array()))."',";
		$sql .= "'".mysqli_real_escape_string($dbx, $data['price_upgrade'])."',";
		$sql .= "'".mysqli_real_escape_string($dbx, $data['price_vat'])."',";
		$sql .= "'".mysqli_real_escape_string($dbx, $data['price_total'])."',";
		$sql .= "'".mysqli_real_escape_string($dbx, $data['wht_percent'])."',";
		$sql .= "'".mysqli_real_escape_string($dbx, $data['price_wht'])."',";
		$sql .= "'".mysqli_real_escape_string($dbx, 'Credit card')."',";
		$sql .= "'".mysqli_real_escape_string($dbx, date('d-m-Y'))."')";
	
	
	
	//var_dump($sql); exit;
	
	if($dbx->query($sql)){
		echo 'success';
	}else{
		echo mysqli_error($dbx);
		//var_dump(mysqli_error($dbc));
	}
