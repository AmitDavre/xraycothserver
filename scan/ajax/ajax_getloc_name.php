<?php

	if(session_id()==''){session_start();}
	ob_start();
	include('../db_connect.php');
	include(DIR.'files/functions.php');
	//var_dump($_REQUEST); exit;

	function array_flatten($array) { 
	  if (!is_array($array)) { 
	    return false; 
	  } 
	  $result = array(); 
	  
	  foreach ($array as $key => $value) { 

	    if (is_array($value)) { 
	      $result = array_merge($result, array_flatten($value)); 
	    } else { 
	      $result = array_merge($result, array($key => $value));
	    } 
	  } 

	  $resultsss = array_combine(range(1, count($result)), array_values($result));
	  return $resultsss; 
	}

	$selcomp =  $_REQUEST['selcomp'];
	$selbrancch =  $_REQUEST['bchid'];
	$locid =  $_REQUEST['locid'];


	if($locid == '1')
	{
		$locname= 'loc1';
	}
	else if($locid == '2')
	{
		$locname= 'loc2';
	}	
	else if($locid == '3')
	{
		$locname= 'loc3';
	}	
	else if($locid == '4')
	{
		$locname= 'loc4';
	}	
	else if($locid == '5')
	{
		$locname= 'loc5';
	}



			$my_dbcname = $prefix.$selcomp;
			$dbc = new mysqli($my_database,$my_username,$my_password,$my_dbcname);
			if($dbc->connect_error) {
				echo '<p style="width:900px; margin:0 auto; margin-top:20px;" class="box_err">Error: ('.$dbc->connect_errno.') '.$dbc->connect_error.'<br>Please try again later or report this error to <a href="mailto:admin@regohr.com">admin@regohr.com</a></p>';
			}else{
				mysqli_set_charset($dbc,"utf8");
			}


			if($resbb = $dbc->query("SELECT * FROM ".$selcomp."_branches_data WHERE ref = '".$selbrancch."'")){
				if($xrowbb = $resbb->fetch_assoc()){

					$locavalue = json_decode($xrowbb[$locname]);

				}

			}


		
		echo json_encode($locavalue);




