<?
	if(session_id()==''){session_start();}
	ob_start();
	include("../../dbconnect/db_connect.php");
	

	$sqlFteams = "SELECT * FROM rego_default_shiftplans WHERE id = '".$_REQUEST['getid']."'";

	if($resFteams = $dba->query($sqlFteams)){

		if($row = $resFteams->fetch_assoc()){
			$desc = $row['description'];
			$th_name = $row['th_name'];

			$array = array (
				'desc' => $desc,
				'th_name' => $th_name,
			);
		}
		else
		{	
			$array = '';
		}
	}

		echo json_encode($array);
	
?>
