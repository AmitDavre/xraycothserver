<?php

	if(session_id()==''){session_start();} 
	ob_start();
	
	include('../../../dbconnect/db_connect.php');
	include('../../../scan/db_connect.php');

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



	$emp_id_common = $_SESSION['rego']['username'];
	
	$locations = array();
	$scanloc = array();
	if($res = $dbc->query("SELECT scan_locations FROM ".$cid."_leave_time_settings")){
		$row = $res->fetch_assoc();
		$locations = unserialize($row['scan_locations']);
	}


	// foreach($locations as $k=>$v){
	// 	if(!empty($v['name']) && !empty($v['code']) && !empty($v['perimeter']) && !empty($v['latitude']) && !empty($v['longitude'])){
	// 		$scanloc['qr'] = array($v['name'],$v['perimeter'],$v['latitude'],$v['longitude'],$v['code'],);

	// 	}
	// }


		$sql1 = "SELECT * FROM rego_all_users WHERE username = '".$emp_id_common."'";
	if($res1 = $dbx->query($sql1)){


		if($res1->num_rows > 0){
			$row1 = $res1->fetch_assoc();


			 $my_dbcname = $prefix.$row1['last'];

	


			if($resbb = $dbc->query("SELECT * FROM ".$row1['last']."_branches")){

				
				$xrowbb = $resbb->fetch_assoc();

				$_branchesID = $xrowbb['id'];


				if($resbbdd = $dbc->query("SELECT * FROM ".$row1['last']."_branches_data WHERE `ref` IN('1','2','3','4','5') ")){
					while($xrowbbd = $resbbdd->fetch_assoc()){
						$myarray[] = array(

											"1"=> json_decode($xrowbbd['loc1']),
											"2"=> json_decode($xrowbbd['loc2']),
											"3"=> json_decode($xrowbbd['loc3']),
											"4"=> json_decode($xrowbbd['loc4']),
											"5"=> json_decode($xrowbbd['loc5']),

										);
					}
				}
			}


			$AllLocations = array_flatten($myarray);

			$validLocations = array();
			foreach ($AllLocations as $key => $value) {
				
				if($value->latitude !=''){
					$scanloc[$value->code] = array($value->name,$value->perimeter,$value->latitude,$value->longitude);
				}
			}

			// $Latestlocation = array_combine(range(1, count($validLocations)), array_values($validLocations));

			

		
		}else{
			echo json_encode('wrong1');
		}
	}else{
		echo json_encode('wrong2');
	}


	// echo '<pre>';
	// print_r($scanloc);
	// echo '</pre>';
	//var_dump($scanloc); exit;
	//var_dump($clock); exit;

	echo json_encode($scanloc);
	

?>