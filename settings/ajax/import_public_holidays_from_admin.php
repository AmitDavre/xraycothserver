<?php
	if(session_id()==''){session_start();}
	ob_start();
	//var_dump($_REQUEST);
	//var_dump($_FILES);
	//exit;
	include('../../dbconnect/db_connect.php');
	// include(DIR.'files/functions.php');
		
	
	// SELECT * FROM `rego_default_holidays`
	$my_dbaname = $prefix.'admin';
	$dba = new mysqli($my_database,$my_username,$my_password,$my_dbaname);
	mysqli_set_charset($dba,"utf8");
	if($dba->connect_error) {
		echo'<p style="width:900px; margin:0 auto; margin-top:20px;" class="box_err">Error: ('.$dba->connect_errno.') '.$dba->connect_error.'<br>Please try again later or report this error to <a href="mailto:admin@regohr.com">admin@regohr.com</a></p>';
	}

	$sql102 = "SELECT * FROM rego_default_holidays ";


	if($res102 = $dba->query($sql102)){
		if($res102->num_rows > 0){
			while($row102 = $res102->fetch_assoc())
				{
					$holidays[] =$row102;
					
				}
		}
	}


	foreach ($holidays as $key => $value) 
	{
		$sql = "SELECT * FROM ".$cid."_holidays WHERE date = '".$value['date']."'";
		if($res = $dbc->query($sql))
		{
			if($row = $res->fetch_assoc())
			{
			}
			else
			{
				$sql2 = "INSERT INTO ".$cid."_holidays (year, date,th,en,cdate) VALUES ";
				$sql2 .= "('".$dbc->real_escape_string($value['year'])."', ";
				$sql2 .= "'".$dbc->real_escape_string($value['date'])."', ";
				$sql2 .= "'".$dbc->real_escape_string($value['th'])."', ";
				$sql2 .= "'".$dbc->real_escape_string($value['en'])."', ";
				$sql2 .= "'".$dbc->real_escape_string($value['cdate'])."')";

				$res2 = $dbc->query($sql2);


			}
		}

	}
	echo 'success';


	exit;

	



	


?>
















