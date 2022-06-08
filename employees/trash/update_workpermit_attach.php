<?php

	if(session_id()==''){session_start();}
	ob_start();
	include("../../dbconnect/db_connect.php");
	
	//var_dump($_FILES); //exit;
	//var_dump($_REQUEST); //exit;

	if(empty($_REQUEST['emp_id'])){echo 'empty'; exit;}

	$uploadmap = DIR.$cid.'/employees/workpermit/';
	if (!file_exists($uploadmap)) {
		mkdir($uploadmap, 0755, true);
	}
	
	if(!empty($_FILES['attach']['tmp_name'])){
		$extension = pathinfo($_FILES['attach']['name'], PATHINFO_EXTENSION);		
		$filename = $_FILES['attach']['name'];
		$file = $uploadmap. $_FILES['attach']['name'];
		$baseName = pathinfo($_FILES['attach']['name'], PATHINFO_FILENAME );
		$counter = 1;				
		while(file_exists($file)) {
			 $filename = $baseName.'('.$counter.').'.$extension;
			 $file = $uploadmap.$baseName.'('.$counter.').'.$extension;
			 $counter++;
		};
		if(move_uploaded_file($_FILES['attach']['tmp_name'], $file)){
			$filename = ROOT.$cid.'/employees/workpermit/'.$filename;
			$sql = "UPDATE ".$cid."_workpermit SET ".$_REQUEST['field']." = '".mysqli_real_escape_string($dbc,$filename)."' WHERE emp_id = '".$_REQUEST['emp_id']."'";
			
			//ob_clean();
			if($dbc->query($sql)){
				//updateEmployeesForPayroll($cid);
				echo $filename;
			}else{
				echo mysqli_error($dbc);
			}
		}
	}

	
