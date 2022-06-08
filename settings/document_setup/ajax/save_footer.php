<?
	if(session_id()==''){session_start();}
	ob_start();
	include("../../../dbconnect/db_connect.php");

	// echo '<pre>';
	// print_r($_REQUEST);
	// echo '</pre>';

	if($_REQUEST['id'] != 'n'){

		$sql = "UPDATE ".$cid."_footer_templates SET `phone`= '".$_REQUEST['phone']."', `fax`= '".$_REQUEST['fax']."', `email`= '".$_REQUEST['email']."', `website`= '".$_REQUEST['website']."', `vat`= '".$_REQUEST['vat']."', `bank1`= '".$_REQUEST['bank1']."', `acc1`= '".$_REQUEST['acc1']."', `bic1`= '".$_REQUEST['bic1']."', `bank2`= '".$_REQUEST['bank2']."', `acc2`= '".$_REQUEST['acc2']."', `bic2`= '".$_REQUEST['bic2']."', `note`= '".$_REQUEST['note']."' WHERE id='".$_REQUEST['id']."'";
	}else{

		$sql = "INSERT INTO ".$cid."_footer_templates (`phone`, `fax`, `email`, `website`, `vat`, `bank1`, `acc1`, `bic1`, `bank2`, `acc2`, `bic2`, `note`) VALUES ('".$_REQUEST['phone']."', '".$_REQUEST['fax']."', '".$_REQUEST['email']."', '".$_REQUEST['website']."', '".$_REQUEST['vat']."', '".$_REQUEST['bank1']."', '".$_REQUEST['acc1']."', '".$_REQUEST['bic1']."', '".$_REQUEST['bank2']."', '".$_REQUEST['acc2']."', '".$_REQUEST['bic2']."', '".$_REQUEST['note']."')";
	}

	//echo $sql; exit;

	ob_clean();
	if($dbc->query($sql)){
		//ob_clean();
		echo 'success';
	}else{
		//ob_clean();
		echo mysqli_error($dbc);
	}
?>














