<?
	if(session_id()==''){session_start();} 
	ob_start();
	include('../../../dbconnect/db_connect.php');
	//var_dump($_REQUEST); exit;

	$sqlChk = "SELECT * FROM ".$cid."_document_textblocks WHERE id = '".$_REQUEST['id']."'";
	$res = $dbc->query($sqlChk);
	if($res->num_rows > 0){

		$sql11 = "UPDATE ".$cid."_document_textblocks SET `name`='".$_REQUEST['name']."', `text`= '".$_REQUEST['text']."' WHERE id= '".$_REQUEST['id']."' ";
	}else{

		$sql11 = "INSERT INTO ".$cid."_document_textblocks (`name`, `text`, `status`) VALUES ('".$_REQUEST['name']."', '".$_REQUEST['text']."', '0')";
	}

	ob_clean();
	if($dbc->query($sql11)){
		echo 'success';
	}else{
		echo mysqli_error($dbc);
	}