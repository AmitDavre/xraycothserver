<?php

	if(session_id()==''){session_start();}
	ob_start();
	include("../db_connect.php");
	$yesno = array('Y'=>'Yes','N'=>'No');
	$ptype = array('card'=>'Credit card', 'internet'=>'Internet banking', 'transfer'=>'Bank transfer');
	//$_REQUEST['payment_type'] = 'transfer';

	$_REQUEST['password'] = hash('sha256', $_REQUEST['pass1']);
	$_REQUEST['date'] = date('Y-m-d H:i:s');
	$_REQUEST['status'] = 'acc';
	$_REQUEST['period_start'] = date('d-m-Y');
	if($_REQUEST['version'] == '0'){
		$_REQUEST['period_end'] = date('d-m-Y', strtotime('+1 months', strtotime($_REQUEST['period_start'])));
	}else{
		$_REQUEST['period_end'] = date('d-m-Y', strtotime('+12 months', strtotime($_REQUEST['period_start'])));
	}
	$_REQUEST['price_year'] = $_REQUEST['price'] - $_REQUEST['discount'];
	$_REQUEST['remarks'] = 'From website';
	$_REQUEST['employees'] = $_REQUEST['version'];
	
	unset($_REQUEST['token'], $_REQUEST['pass1'], $_REQUEST['pass2']);
	
	//var_dump($_REQUEST); exit;
	
	$sql = "INSERT INTO rego_waiting_customers (";
	foreach($_REQUEST as $k=>$v){
		$sql .= $k.", "; 
	}
	$sql = substr($sql,0,-2).") VALUES (";
	foreach($_REQUEST as $k=>$v){
		$sql .= "'".$dbx->real_escape_string($v)."', "; 
	}
	$sql = substr($sql,0,-2).")";
	//echo $sql;
	
	
	if($res = $dbx->query($sql)){
		
		$body = "<html>
					<head>
					<style type='text/css'>
						body, th, td {font-family:Calibri,Verdana; white-space:nowrap; font-size:16px; padding:1px 3px} 
					</style>
					<meta http-equiv='Content-Type' content='text/html; charset=utf-8' />
					</head>
					<body>
					<table border='0'>
						<tr>
							<th align='right' style='width:5%'>".$lng['First name']." : </th>
							<td style='width:95%'>".$_REQUEST['firstname']."</td>
						</tr><tr>
							<th align='right'>".$lng['Last name']." : </th>
							<td>".$_REQUEST['lastname']."</td>
						</tr><tr>
							<th align='right'>".$lng['Company']." : </th>
							<td>".$_REQUEST['company']."</td>
						</tr><tr>
							<th align='right'>".$lng['Phone']." : </th>
							<td>".$_REQUEST['phone']."</td>
						</tr><tr>
							<th align='right'>".$lng['email']." : </th>
							<td><a href='mailto:".$_REQUEST['email']."'>".$_REQUEST['email']."</a></td>
						</tr><tr>
							<th align='right'>".$lng['Address']." : </th>
							<td>".$_REQUEST['address']."</td>
						</tr><tr>
							<th align='right'>".$lng['Sub district']." : </th>
							<td>".$_REQUEST['subdistrict']."</td>
						</tr><tr>
							<th align='right'>".$lng['District']." : </th>
							<td>".$_REQUEST['district']."</td>
						</tr><tr>
							<th align='right'>".$lng['Province']." : </th>
							<td>".$_REQUEST['province']."</td>
						</tr><tr>
							<th align='right'>".$lng['Postal code']." : </th>
							<td>".$_REQUEST['postcode']."</td>
						</tr><tr>
							<th align='right'>".$lng['Tax ID no.']." : </th>
							<td>".$_REQUEST['tax_id']."</td>
						</tr><tr>
							<th align='right'>".$lng['Certificate']." : </th>
							<td>".$yesno[$_REQUEST['certificate']]."</td>
						</tr><tr>
							<th align='right'>".$lng['Subscription']." : </th>
							<td>REGO ".$_REQUEST['version']."</td>
						</tr><tr>
							<th align='right'>".$lng['Amount']." : </th>
							<td>".number_format($_REQUEST['net'],2).' '.$lng['Baht']."</td>
						</tr><tr>
							<th align='right'>".$lng['Date']." : </th>
							<td>".date('d-m-Y @ H:i:s', strtotime($_REQUEST['date']))."</td>
						</tr><tr>
							<th align='right'>Payment type : </th>
							<td>".$ptype[$_REQUEST['payment_type']]."</td>
						
					</table>
					</body>
					</html>";
		
		require('../PHPMailer/PHPMailerAutoload.php');
		
		$mail = new PHPMailer;
		$mail->CharSet = "UTF-8";
		$mail->From = $_REQUEST['email'];
		$mail->FromName = $_REQUEST['firstname'].' '.$_REQUEST['lastname'];
		$mail->addAddress('admin@regohr.com', 'REGO HR Admin');
		//$mail->addAddress('willy@xrayict.com', 'REGO HR Admin');
		//$mail->addReplyTo($_REQUEST['email'], $_REQUEST['name']);
		$mail->isHTML(true);                                  
		$mail->Subject = 'New customer from RegoThailand.com';
		$mail->Body = $body;
		$mail->WordWrap = 100;   
		
		if(!$mail->send()) {
			 //echo $mail->ErrorInfo;
		}else{
			 //echo 'success';
		}
		
		ob_clean();                           
		echo 'success';
	}else{
		echo mysqli_error($dbx);
	}
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
