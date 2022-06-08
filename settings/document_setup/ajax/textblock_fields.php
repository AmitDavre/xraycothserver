<?
	if(session_id()==''){session_start();} 
	ob_start();
	include('../../../dbconnect/db_connect.php');
	//var_dump($_REQUEST); exit;

	// $sqlChk = "SELECT * FROM ".$cid."_textblock_fields WHERE name = '".$_REQUEST['mval']."'";
	// $res = $dbc->query($sqlChk);
	// if($res->num_rows > 0){

	// 	$sql11 = "UPDATE ".$cid."_textblock_fields SET `name`='".$_REQUEST['mval']."', `txtblock_id`='".$_REQUEST['tbid']."' WHERE name='".$_REQUEST['mval']."' ";
	// }else{

		$sql11 = "INSERT INTO ".$cid."_textblock_fields (`name`,`txtblock_id`) VALUES ('".$_REQUEST['mval']."','".$_REQUEST['tbid']."')";
	//}

	ob_clean();
	if($dbc->query($sql11)){
		echo 'success';
	}else{
		echo mysqli_error($dbc);
	}