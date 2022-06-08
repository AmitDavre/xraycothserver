<?
	if(session_id()==''){session_start();}
	ob_start();
	include("../../dbconnect/db_connect.php");
	var_dump($_REQUEST); //exit;
	var_dump($_FILES); exit;

	$path = 'admin/billing/documents/';
	if(!file_exists(DIR.$path)){
		mkdir(DIR.$path, 0777, true);
	}
	$filename = '';
	if(!empty($_FILES['payslip']['tmp_name'])){
		$ext = strtolower(pathinfo($_FILES['payslip']['name'], PATHINFO_EXTENSION));		
		$filename = $path.'payslip_'.$_REQUEST['inv_number'].'.'.$ext;
		if(move_uploaded_file($_FILES['payslip']['tmp_name'], DIR.$filename)){
			$filename = ROOT.$filename;
		}
	}
	//var_dump($filename); exit;
	
	$sql = "UPDATE rego_invoices SET payslip = '".$filename."' WHERE inv = '".$_REQUEST['inv_number']."'";
	//echo $sql; //exit;
	if($dbx->query($sql)){
		echo 'success';
	}else{
		echo mysqli_error($dbx);
	}
	exit;
	
?>














