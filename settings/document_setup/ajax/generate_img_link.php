<?
	if(session_id()==''){session_start();}
	ob_start();
	include('../../../dbconnect/db_connect.php');

	$uploadmap = '../../../'.$cid.'/commcenter/genlink/';
	if (!file_exists($uploadmap)) {
		mkdir($uploadmap, 0755, true);
	}

	$target_file = $uploadmap . basename($_FILES["genfile"]["name"]);
	if(move_uploaded_file($_FILES["genfile"]["tmp_name"], $target_file)) {

		$filelink = ROOT.$cid.'/commcenter/genlink/'.basename($_FILES["genfile"]["name"]);
		ob_clean();
		echo $filelink;

	}else{

		ob_clean();
		echo 'error';
	}


?>