<?
	if(session_id()==''){session_start(); ob_start();}
	include("../../dbconnect/db_connect.php");
	
	//var_dump($_FILES); //exit;
	//var_dump($_REQUEST); //exit;
	
	$dir = DIR.'admin/uploads/';
	foreach($_FILES['slide']['tmp_name'] as $k=>$v){
		//var_dump($k);
		if(!empty($v)){
			$ext = pathinfo($_FILES['slide']['name'][$k], PATHINFO_EXTENSION);		
			$filename = $dir.'slide'.$k.'.'.$ext;
			$dbfile = 'uploads/slide'.$k.'.'.$ext;
			if(move_uploaded_file($v,$filename)){
				//$dba->query("UPDATE rego_company_settings SET complogo = '".$dbfile."'");
				var_dump($filename); 
				$_REQUEST['slider'][$k] = $dbfile;
			}
		}
	}
	//$_REQUEST['slider'] = array_values($_REQUEST['slider']);
	
	
	//var_dump($_REQUEST); //exit;
	//$_REQUEST['promo_slider'] = '';

	$sql = "UPDATE rego_company_settings SET 
			promo = '".$dba->real_escape_string($_REQUEST['promo'])."', 
			th_promo = '".$dba->real_escape_string($_REQUEST['th_promo'])."', 
			en_promo = '".$dba->real_escape_string($_REQUEST['en_promo'])."', 
			promo_slider = '".$dba->real_escape_string(serialize($_REQUEST['slider']))."'";
	//echo $sql;
	
	
	ob_clean();	
	if($dba->query($sql)){
		echo 'success';
	}else{
		echo mysqli_error($dba);
	}





