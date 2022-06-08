<?
	if(session_id()==''){session_start(); ob_start();}
	include("../../dbconnect/db_connect.php");
	
	$emp_id = $_POST['id'];
	$sql1 = "SELECT * FROM ".$cid."_employees WHERE emp_id = '".$emp_id."'";
	if($res1 = $dbc->query($sql1)){
		while($row1 = $res1->fetch_assoc())
		{
			$team_name = $row1['team_name'];
		}
	}




	$leaveData =  array();
	$sql2 = "SELECT * FROM ".$cid."_leaves_data WHERE emp_id = '".$emp_id."'";
	if($res2 = $dbc->query($sql2)){


		while($row2 = $res2->fetch_assoc())
		{
			$row3[] = $row2;

			$leaveDate = $row2['date'];
			$empName = $row2['name'];
			$status = $row2['status'];

			if($status == 'OA')
			{ 
				$sOA = 'selected="selected"';
				$selDetect = 'selDetect';
			}
			else
			{ 
				$sOA = '';
				$selDetect = '';

			}
			if($status == 'OR')
			{ 
				$sOR = 'selected="selected"';
			}
			else
			{ 
				$sOR = '';
			}


			$select = '<select id="actionSelect" > 
				<option>Select</option>
				<option class="'.$selDetect.'" value="OA" '.$sOA.'>Approve</option>
				<option class="'.$selDetect.'" value="OR" '.$sOR.'>Rejected</option>
			</select>';
			// $leaveDates[] = $row2['date'];
			// $string_version = implode(',', $leaveDates);

			// get emp id of employee who has the same leave request date 

			// $sql3 = "SELECT * FROM ".$cid."_offday_data WHERE date = '".$row2['date']."'";
			// if($res3 = $dbc->query($sql3))
			// {
			// 	$row3 = $res3->fetch_assoc() ;
				
			
				
			// }

			$leaveData[] = ['requested_date' => $leaveDate , 'employee_name' => $empName, 'team_name' => $team_name, 'emp_id' => $emp_id, 'select' => $select,'status' =>$status];
		}
	}


	// foreach ($row3 as $key3 => $value3) {

	// 	$sql4 = "SELECT * FROM ".$cid."_offday_data WHERE date = '".$value3['date']."'";
	// 	if($res4 = $dbc->query($sql4))
	// 	{
	// 		while($row4 = $res4->fetch_assoc())
	// 		{
	// 			// if($row4['date'])
	// 			// $row5[] =  $row4;

	// 			$row4Date = $row4['date'];

	// 			if($row4Date in_array(needle, haystack))
	// 			{
	// 				echo '<pre>';
	// 				print_r($row4);
	// 				echo '</pre>';
	// 			}

				

	// 		}
	// 	}
	// }


	


	


	// die();


	ob_clean();
	echo json_encode($leaveData);
	exit;
	
	
	
	
	

















