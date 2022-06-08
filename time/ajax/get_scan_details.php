<?
	if(session_id()==''){session_start();}; ob_start();
	//$cid = $_SESSION['xhr']['cid'];
	include('../../dbconnect/db_connect.php');
	//include("../../files/functions.php");
	//$_REQUEST['id'] = 10007;
	//var_dump($_REQUEST);




	function getEmployeesEmpID($sid){
		$data = array();	
		
		global $cid;
		global $dbc;
		// echo $cid;
		$res = $dbc->query("SELECT * FROM ".$cid."_employees WHERE sid= '".$sid."' ");
		if($res->num_rows > 0){
			if($row = $res->fetch_assoc()){
				$data = $row['emp_id'];
			}
		}
		return $data;
	}	

	function getEmployeesEmpName($sid){
		$data = array();	
		
		global $cid;
		global $dbc;
		// echo $cid;
		$res = $dbc->query("SELECT * FROM ".$cid."_employees WHERE sid= '".$sid."' ");
		if($res->num_rows > 0){
			if($row = $res->fetch_assoc()){
				$data = $row['en_name'];
			}
		}
		return $data;
	}







	
	$body = '';
	$data = array();
	$sql = "SELECT filename,content,scansystem FROM ".$cid."_scanfiles WHERE id = '".$_REQUEST['id']."'";

	if($res = $dbc->query($sql)){
		if($row = $res->fetch_assoc()){
			$data = unserialize($row['content']);
			$filename = $row['filename'];
			$scansystemValue = $row['scansystem'];



		}
	}	




	$sql1 = "SELECT * FROM ".$cid."_scandata WHERE filename = '".$filename."' order by id ASC";
	if($res1 = $dbc->query($sql1)){

		$count =1;
		while($row1 = $res1->fetch_assoc())
		{
			// echo '<pre>';
			// print_r($row1);
			// echo '</pre>';
			

				$cnt = 1;
				$body .= '<tr><input type="hidden" id="hidden_scaniD_'.$count.'" name="hidden_scaniD_'.$count.'" value="'.$row1['id'].'" />';
				// STATUS 
				// check scan in is a valid time 


				if(!empty($row1['scan_in']))
				{
					if (preg_match("/^(?(?=\d{2})(?:2[0-3]|[01][0-9])|[0-9]):[0-5][0-9]$/", $row1['scan_in'])) 
					{
						$scanINtime = 'valid';
					}
					else
					{
						$scanINtime = 'notvalid';
					}
				}
				else
				{
					$scanINtime = '';
				}	

				// echo $scanINtime . '<br>';


				// check scan out time is valid 

				if(!empty($row1['scan_out']))
				{
					if (preg_match("/^(?(?=\d{2})(?:2[0-3]|[01][0-9])|[0-9]):[0-5][0-9]$/", $row1['scan_out'])) 
					{
						$scanOUTtime = 'valid';
					}
					else
					{
						$scanOUTtime = 'notvalid';
					}
				}
				else
				{
					$scanOUTtime = '';
				}








				// CHECK SCAN IN VALID 
				if(!empty($row1['scan_in']))
				{
					if (preg_match('~[0-9]+~', $row1['scan_in'])) 
					{
						$scanIN = $row1['scan_in'];
					}
					else
					{
						$scanIN = '';
					}
				}
				else
				{
					$scanIN = '';
				}					

				// CHECK SCAN OUT VALID 
				if(!empty($row1['scan_out']))
				{
					if (preg_match('~[0-9]+~', $row1['scan_out'])) 
					{
						$scanOUT = $row1['scan_out'];
					}
					else
					{
						$scanOUT = '';
					}
				}
				else
				{
					$scanOUT = '';
				}				



				$employee_value2 = getEmployeesEmpID($row1['scan_id']);
				if(!empty($employee_value2)){
					$empcheckmsg= $employee_value;
				}
				else
				{
					$empcheckmsg = 'empty';
				}



				if($row1['datescan'] == '1970-01-01' || $row1['scan_id'] == '' || ($scanIN == '' && $scanOUT == '') || $empcheckmsg == 'empty' || $scanINtime == 'notvalid' || $scanOUTtime == 'notvalid')
				{
					$body .= '<td><span class="InvalidSpan" style="color:red">INVALID</span></td>';
					$body .= '<td><label><input disabled="disabled" id="'.$row1['id'].'" type="checkbox" class="empty both_'.$count.' dbox checkbox notxt totalVal" onchange="allCheckbox(this,'.$count.')"><span style="z-index:0"></span></label></td>';

				}
				else
				{

						$sql2 = "SELECT * FROM ".$cid."_metascandata WHERE scandata_id = '".$row1['id']."' order by id ASC";
						if($res2 = $dbc->query($sql2))
						{
								
							if($row2 = $res2->fetch_assoc())
							{
								// if($row2['scandata_id'] != '')
								// {
									$body .= '<td><span class="existSpan" style="color:red">EXIST</span></td>';
									$body .= '<td><label><input disabled="disabled" id="'.$row1['id'].'" type="checkbox" class="exist dbox both_'.$count.' checkbox notxt totalVal" onchange="allCheckbox(this,'.$count.')"><span style="z-index:0"></span></label></td>';
								// }
								
						
							}

							else
							{
								$body .= '<td><span class="valid_span" style="color:green"><b>VALID</b></span></td>';
								$body .= '<td><label><input id="'.$row1['id'].'" type="checkbox" class="valid dbox both_'.$count.' checkbox notxt totalVal" onchange="allCheckbox(this,'.$count.')"><span style="z-index:0"></span></label></td>';
							}
						
						}


				}





				// DATESCAN
				if($row1['datescan'] == '1970-01-01')
				{
					$body .= '<td><span style="color:red">INVALID</span></td>';
				}
				else
				{
					$datescan  = date('d-m-Y',strtotime($row1['datescan']));
					$body .= '<td>'.$datescan.'</td>';
				}

				// SCAN ID 
				if($row1['scan_id'] == '')
				{
					$body .= '<td><span style="color:red">INVALID</span></td>';
				}
				else
				{
					$body .= '<td>'.$row1['scan_id'].'</td>';
				}


				if($row1['scan_id'] != '')
				{
					// if fsv2011 then check scan id exists or not 

					 // getEmployeesEmpID()

					if($scansystemValue == 'FSV2011')
					{

						$employee_value = getEmployeesEmpID($row1['scan_id']);
						if(!empty($employee_value)){
							$body .= '<td>'.$employee_value.'</td>';
						}
						else
						{
							$body .= '<td>-</td>';
						}
					}
					else
					{
						// EMPLOYEE ID 
						$body .= '<td>'.$row1['emp_id'].'</td>';
					}
					
				}
				else
				{
					// EMPLOYEE ID 
					$body .= '<td></td>';
				}
		

				// EMPLOYEE NAME 

					if($scansystemValue == 'FSV2011')
					{

						if($row1['scan_id'])
						{
							$employee_value_name = getEmployeesEmpName($row1['scan_id']);
						}
						if(!empty($employee_value_name)){
							$body .= '<td>'.$employee_value_name.'</td>';

						}
						else
						{
							$body .= '<td>-</td>';
						}
					}
					else
					{
						// EMPLOYEE ID 
						$body .= '<td>'.$row1['emp_name'].'</td>';

					}




			
				if(!empty($row1['scan_in']))
				{
					if (preg_match('([01]?[0-9]|2[0-3])', $row1['scan_in'])) {
					// contains number 
						$body .= '<td class="tac">'.$row1['scan_in'].'</td>';
					}
					else
					{
						$body .= '<td><span style="color:red">INVALID</span></td>';
					}
					
					
				}
				else
				{
					$body .= '<td class="tac">-</td>';
				}				

				if(!empty($row1['scan_out']))
				{
					if (preg_match('~[0-9]+~', $row1['scan_out'])) {
					// contains number 
						$body .= '<td class="tac">'.$row1['scan_out'].'</td>';
					}
					else
					{
						$body .= '<td><span style="color:red">INVALID</span></td>';
					}

				}
				else
				{
					$body .= '<td class="tac">-</td>';
				}
				
				while($cnt < 8){
					$body .= '<td class="tac">-</td>';
					$cnt++;
				}



				$body .= '</tr>';

			// }
				$count ++;
		}

	}
	
	// update the employee id if the scan id is present in database 




	// foreach($data as $key=>$val){
	// 	foreach($val['time'] as $k=>$v){
	// 		$time = explode('|', $v);
	// 		$body .= '<tr>';
	// 		$body .= '<td>'.$k.'</td>';
	// 		$body .= '<td><span style="color:red">INVALID</span></td>';
	// 		$body .= '<td>'.$val['id'].'</td>';
	// 		$body .= '<td>'.$val['name'].'</td>';
	// 		$cnt = 1;
	// 		foreach($time as $tk=>$tv){
	// 			if(!empty($tv)){
	// 				$body .= '<td class="tac">'.$tv.'</td>';
	// 			}else{
	// 				$body .= '<td class="tac">-</td>';
	// 			}
	// 			$cnt++;
	// 		}
	// 		while($cnt < 10){
	// 			$body .= '<td class="tac">-</td>';
	// 			$cnt++;
	// 		}
	// 		$body .= '</tr>';
	// 	}
	// }


	// die();
	


	//var_dump($data); exit;
	
	//ob_clean();
	// die();
	echo $body;



?>
