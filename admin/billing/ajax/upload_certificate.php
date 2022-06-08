<?

	//if(session_id()==''){session_start();}
	//ob_start();
	include("../../dbconnect/db_connect.php");
	//var_dump($_REQUEST); //exit;
	//var_dump($_FILES); exit;
	
	$dir = DIR.'admin/uploads/certificates/';
	$root = AROOT.'uploads/certificates/';
	
	if(!empty($_FILES['certificate']['tmp_name'])){
		$filename = $_FILES['certificate']['name'];	
		$baseName = pathinfo($filename, PATHINFO_FILENAME );
		$extension = pathinfo($filename, PATHINFO_EXTENSION );
		$counter = 1;				
		while(file_exists($dir.$filename)) {
			 $filename = $baseName.'('.$counter.').'.$extension;
			 $counter++;
		};
		if(move_uploaded_file($_FILES['certificate']['tmp_name'], $dir.$filename)){
			$dba->query("UPDATE rego_invoices SET pdf_certificate = '".$dba->real_escape_string($root.$filename)."' WHERE id = '".$_REQUEST['id']."'");
			echo 'success';
		}else{
			echo mysqli_error($dba);
		}
	}
