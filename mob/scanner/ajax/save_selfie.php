<?

	if(session_id()==''){session_start();}
	ob_start();
	include('../../../dbconnect/db_connect.php');

	
	$dir = DIR.$cid.'/time/selfies/';
  if(!file_exists($dir)){
   	mkdir($dir);
	}
	
	$img = $_REQUEST['image'];
	$img = str_replace('data:image/png;base64,', '', $img);
	$img = str_replace(' ', '+', $img);
	$fileData = base64_decode($img);
	//saving
	$fileName = date('d-m-Y_His').'.png';
	if(file_put_contents($dir.$fileName, $fileData)){
		$sql = "UPDATE ".$cid."_attendance SET ".$_REQUEST['img']." =  '".$fileName."' WHERE id = '".$_REQUEST['id']."'";

		// update metascandata table 
			$sql1 = "UPDATE ".$cid."_metascandata SET picture =  '".$fileName."' WHERE id = '".$_REQUEST['metaid']."'";
			$dbc->query($sql1);


		if($dbc->query($sql)){
			ob_clean();
			echo 'success';
		}else{
			ob_clean();
			echo mysqli_error($dbc);
		}



	}else{
		ob_clean();
		echo 'error';
	}















