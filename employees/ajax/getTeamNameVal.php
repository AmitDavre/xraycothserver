<?
	if(session_id()==''){session_start();}
	ob_start();
	include("../../dbconnect/db_connect.php");
	
	$cid = $_POST['cid'];
	$cur_year = $_POST['cur_year'];

	$sql1 = "SELECT * FROM ".$cid."_teams WHERE id = '".$_POST['teamVal']."'";
	if($res1 = $dbc->query($sql1)){
		if($row1 = $res1->fetch_assoc()){
			$desc1 =  $row1['code'];
			$trimmedVal = preg_replace('/\s+/', '', $desc1);
			 $newTeamval = strtolower($trimmedVal);

		}
	}
	


	$sqlFteams = "SELECT * FROM ".$cid."_shiftplans_".$cur_year." WHERE id = '".$newTeamval."'";




	if($resFteams = $dbc->query($sqlFteams)){

		if($row = $resFteams->fetch_assoc()){

			$desc =  $row['code'];
	
		}
		else
		{	
			$desc =  '';
		}
	}

	// ob_clean();
	echo json_encode($desc);
	// exit;
	
?>
