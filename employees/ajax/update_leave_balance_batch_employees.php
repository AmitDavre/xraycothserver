<?php

	if(session_id()==''){session_start();}
	ob_start();
	include("../../dbconnect/db_connect.php");

	// get all active employees 

	// insert the entry 

	$sql5111 = "SELECT * FROM ".$cid."_employees WHERE emp_status = '1'";

		if($res5111 = $dbc->query($sql5111)){
			while($row5111 = $res5111->fetch_assoc()){


				$nextYearLeaveForwardBalance = $row5111['annual_leave']; // get employees default leave balance from employee register 
				$searlizedOtherFields = array();
				$searlizedOtherFields['leaveForwardorNot'] = '1';
				$newsearlizeArrayOther = serialize($searlizedOtherFields);

				$selectedValue = array (

					'annual_leave' => $nextYearLeaveForwardBalance,
					'year' => $cur_year,
					'emP_id' => $row5111['emp_id'],
					'other_fields' => $newsearlizeArrayOther,

				);


				// check if same employee id and same year already exists 


				$sql511 = "SELECT * FROM ".$cid."_employee_per_year_records WHERE emp_id = '".$row5111['emp_id']."' AND year = '".$cur_year."'";

				if($res511 = $dbc->query($sql511)){
					if($row511 = $res511->fetch_assoc()){
						$requestRowid = $row511['id'];

						$update11 = $dbc->query("UPDATE ".$cid."_employee_per_year_records SET annual_leave = '".$row5111['annual_leave']."' , year = '".$cur_year."', other_fields = '".$newsearlizeArrayOther."' WHERE id = '".$requestRowid."'");

					}
					else
					{
						$sql111 = "INSERT INTO ".$cid."_employee_per_year_records (";
						foreach($selectedValue as $k=>$v){
							$sql111 .= $k.', ';
						}
						$sql111 = substr($sql111,0,-2);
						$sql111 .= ") VALUES ("; 
						foreach($selectedValue as $k=>$v){
							$sql111 .= "'".mysqli_real_escape_string($dbc,$v)."', ";
						}
						$sql111 = substr($sql111,0,-2).')';

						$dbc->query($sql111);

					}
				}





				
			}
		}

		echo 'success';


?>