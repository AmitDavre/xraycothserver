<?php

	if(session_id()==''){session_start();} 
	ob_start();
	var_dump($_FILES); //exit;
	var_dump($_REQUEST); //exit;
		
	include("../dbconnect/db_connect.php");
	include(DIR."files/functions.php");
	
	$targetDir = DIR.$_SESSION['rego']['cid']."/archive/";
	if (!file_exists($targetDir)) {
		mkdir($targetDir, 0755, true);
	}
	$dbDir = ROOT.$_SESSION['rego']['cid']."/archive/";
	
	//var_dump($targetDir); //exit;
	//var_dump($dbDir); //exit;

	if(!empty($_FILES)){
		
		$filename = $_FILES['file']['name'];
		$fileSize = $_FILES['file']['size'];	
		$baseName = pathinfo($filename, PATHINFO_FILENAME );
		$extension = pathinfo($filename, PATHINFO_EXTENSION );
		$counter = 1;				
		while(file_exists($targetDir.$filename)) {
			 $filename = $baseName.' ('.$counter.').'.$extension;
			 $counter++;
		};
		//var_dump($filename); //exit;
		$targetFile = $targetDir.$filename;	
		$dbFile = $dbDir.$filename;	
		//var_dump($dbFile); //exit;
		//var_dump($targetFile); exit;

		if(move_uploaded_file($_FILES['file']['tmp_name'], $targetFile)){
			
			$dbc->query("INSERT INTO ".$_SESSION['rego']['cid']."_documents (filename, name, month, year, size, type, date, user_name, link) VALUES(
				'".$dbc->real_escape_string($filename)."',
				'".$dbc->real_escape_string($filename)."',
				'".$dbc->real_escape_string($_REQUEST['month'])."',
				'".$dbc->real_escape_string($_SESSION['rego']['cur_year'])."',
				'".$dbc->real_escape_string(round(($fileSize/1024),2))."',
				'".$dbc->real_escape_string(strtolower($extension))."',
				'".date("d-m-Y H:i")."', 
				'".$dbc->real_escape_string($_SESSION['rego']['name'])."',
				'".$dbc->real_escape_string($dbDir.$filename)."')");
			ob_clean();
			echo mysqli_error($dbc);
		}
	}
	
	
	
	
	
	
	
	
	
