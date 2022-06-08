<?

	if(session_id()==''){session_start();}
	ob_start();
	include('../../dbconnect/db_connect.php');
	//var_dump($_REQUEST); exit;
	
	foreach($_REQUEST as $key=>$val){
		$sql = "UPDATE ".$cid."_prepayroll SET "; 
		foreach($val as $k=>$v){
			$sql .= $k." = '".round($v,2)."', ";
		}
		$sql = substr($sql,0,-2);
		$sql .= " WHERE id = '".$key."'";
		//echo '<br>'.$sql; //exit;
		
		if(!$dbc->query($sql)){
			ob_clean(); 
			echo mysqli_error($dbc); 
			exit;
		}
	}
	ob_clean(); 
	echo 'success';
