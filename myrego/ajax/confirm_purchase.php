<?

	if(session_id()==''){session_start();}
	ob_start();
	include("../../dbconnect/db_connect.php");
	var_dump($_REQUEST); exit;
	
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
	//var_dump($inv_number);
	//var_dump($inv_date);
	//var_dump($inv_due);
	
	$draft = array();
	if($res = $dbx->query("SELECT * FROM rego_purchase_draft WHERE cid = '".$cid."'")){
		if($row = $res->fetch_assoc()){
			$data = $row;
			$data['inv_number'] = $inv_number;
			$data['inv_date'] = $inv_date;
			$data['inv_due'] = $inv_due;
		}
	}
	
	$sql = "UPDATE rego_purchase_draft SET 
		status = 1,
		inv_number = '".mysqli_real_escape_string($dbx, $data['inv_number'])."',
		inv_date = '".mysqli_real_escape_string($dbx, $data['inv_date'])."',
		inv_due = '".mysqli_real_escape_string($dbx, $data['inv_due'])."' 
		WHERE cid = '".$cid."'";
	if($dbx->query($sql)){
		//ob_clean(); 
		echo 'success';
	}else{
		//ob_clean(); 
		echo mysqli_error($dbx);
	}
	//var_dump($data); //exit;

	$sql = "INSERT INTO rego_invoices (inv, clientID, customer, address, subscription, inv_date, inv_due, body, sub, vat, total, wht_percent, wht) VALUES (";
		$sql .= "'".mysqli_real_escape_string($dbx, $data['inv_number'])."',";
		$sql .= "'".mysqli_real_escape_string($dbx, $cid)."',";
		$sql .= "'".mysqli_real_escape_string($dbx, $data['company'])."',";
		$sql .= "'".mysqli_real_escape_string($dbx, $data['address'])."',";
		$sql .= "'".mysqli_real_escape_string($dbx, $data['version'])."',";
		$sql .= "'".mysqli_real_escape_string($dbx, $data['inv_date'])."',";
		$sql .= "'".mysqli_real_escape_string($dbx, $data['inv_due'])."',";
		$sql .= "'".mysqli_real_escape_string($dbx, $data['body'])."',";
		$sql .= "'".mysqli_real_escape_string($dbx, $data['price_sub'])."',";
		$sql .= "'".mysqli_real_escape_string($dbx, $data['price_vat'])."',";
		$sql .= "'".mysqli_real_escape_string($dbx, $data['price_total'])."',";
		$sql .= "'".mysqli_real_escape_string($dbx, $data['wht_per'])."',";
		$sql .= "'".mysqli_real_escape_string($dbx, $data['price_wht'])."')";
		//$sql .= "'".mysqli_real_escape_string($dbx, 'Credit card')."',";
		//$sql .= "'".mysqli_real_escape_string($dbx, date('d-m-Y'))."')";
	
	//var_dump($sql); exit;
	
	if($dbx->query($sql)){
		echo 'success';
	}else{
		echo mysqli_error($dbx);
		//var_dump(mysqli_error($dbc));
	}










