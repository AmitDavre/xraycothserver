<?
	if(session_id()==''){session_start();}
	ob_start();
	include("../../dbconnect/db_connect.php");

	// 1 = yes 
	// 2 = no 
	$selectedCondition = $_REQUEST['selectedCondition'];
	$shiftplanid = $_REQUEST['koopId'];


	$previousyearvalue = $cur_year -1;

	if($selectedCondition == '1')
	{
		// get old data and run shift cycle 	

		$sql = "SELECT * FROM ".$cid."_shiftplans_".$previousyearvalue. " WHERE id= '".$shiftplanid."'";
		if($res = $dbc->query($sql)){
				if($row = $res->fetch_assoc()){
					$ss_data = unserialize($row['ss_data']);
			}
		}
	}

	$dateeee= $ss_data['startdate'];

	$breakdate = explode('/', $dateeee);

	$brkyear = $breakdate['2']+1;
	$brkmonth= $breakdate['1'];
	$brkday= $breakdate['0'];


	$newdatevalue = $brkday.'/'.$brkmonth.'/'.$brkyear ;

	$ss_data['startdate'] = $newdatevalue;


	// echo '<pre>';
	// print_r($ss_data);
	// echo '</pre>';


	 $sql1 = "UPDATE ".$cid."_shiftplans_".$cur_year. " SET ss_data = '".$dbc->real_escape_string(serialize($ss_data))."'  WHERE id ='".$shiftplanid."' ";


	
	ob_clean();	
	if($dbc->query($sql1)){
		echo 'success';
	}else{
		echo mysqli_error($dbc);
	}
	exit;
	
?>
