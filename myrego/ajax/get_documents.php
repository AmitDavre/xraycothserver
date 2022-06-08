<?

	if(session_id()==''){session_start();}
	ob_start();
	include("../../dbconnect/db_connect.php");
	//var_dump($_REQUEST); exit;
	
	$data = array();
	if($res = $dbx->query("SELECT pdf_invoice, pdf_receipt, payslip, pdf_certificate FROM rego_invoices WHERE inv = '".$_REQUEST['inv']."'")){
		if($row = $res->fetch_assoc()){
			$data['invoice'] = $row['pdf_invoice'];
			$data['receipt'] = $row['pdf_receipt'];
			$data['payslip'] = $row['payslip'];
			$data['certificate'] = $row['pdf_certificate'];
			//var_dump($data); exit;
			echo json_encode($data);
		}
	}else{
		echo mysqli_error($dbx);
	}
	//var_dump($data); exit;
	//ob_clean(); 
	/*if($dbx->query($sql)){
		echo json_encode($data);
	}else{
		echo mysqli_error($dbx);
	}*/
