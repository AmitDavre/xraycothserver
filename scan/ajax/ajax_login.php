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
	
	if(empty($_REQUEST['username']) || empty($_REQUEST['password'])){
		ob_clean(); 
		echo json_encode('empty'); 
		exit;
	}

	$username = strtolower(preg_replace('/\s+/', '', $_REQUEST['username']));
	$password = hash('sha256', preg_replace('/\s+/', '', $_REQUEST['password'])); 

	$sql = "SELECT * FROM rego_all_users WHERE LOWER(username) = '".$username."' AND password = '".$password."'";
	if($res = $dbx->query($sql)){
		if($res->num_rows > 0){
			$row = $res->fetch_assoc();

			if($row['sys_status'] != 1){
				ob_clean(); 
				echo json_encode('suspended');
				exit;
			}else if($row['type'] != 'sys'){
				ob_clean(); 
				echo json_encode('type');
				exit;
			}

			$my_dbcname = $prefix.$row['last'];
			$dbc = new mysqli($my_database,$my_username,$my_password,$my_dbcname);
			if($dbc->connect_error) {
				echo '<p style="width:900px; margin:0 auto; margin-top:20px;" class="box_err">Error: ('.$dbc->connect_errno.') '.$dbc->connect_error.'<br>Please try again later or report this error to <a href="mailto:admin@regohr.com">admin@regohr.com</a></p>';
			}else{
				mysqli_set_charset($dbc,"utf8");
			}

			if($resbb = $dbc->query("SELECT * FROM ".$row['last']."_branches")){
				$xrowbb = $resbb->fetch_assoc();
				$_branchesID = $xrowbb['id'];
 				$series = range(1,100);

				$resasd = "'" . implode ( "', '", $series ) . "'";



				if($resbbdd = $dbc->query("SELECT * FROM ".$row['last']."_branches_data WHERE `ref` IN(".$resasd.") ")){
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

			
			foreach ($AllLocations as $key => $value) {
				
				if($value->latitude !=''){
					$validLocations[] = array(
												'name' => $value->name,
												'code' => $value->code,
										        'qr' => $value->qr,
										        'latitude' => $value->latitude,
										        'longitude' => $value->longitude,
										        'perimeter' => $value->perimeter,

											);
				}
			}

			$Latestlocation = array_combine(range(1, count($validLocations)), array_values($validLocations));

			//echo '<pre>';
			//print_r($AllLocations);
			//print_r($Latestlocation);
			//echo '</pre>';

			

			$scan = '';
			//$locations = array();
			if($res = $dbc->query("SELECT scan_system FROM ".$row['last']."_leave_time_settings")){
				$xrow = $res->fetch_assoc();
				$scan = $xrow['scan_system'];
				// $locations = unserialize($xrow['scan_locations']);
			}
			if($scan != 'REGO'){
				ob_clean(); 
				echo json_encode('scan');
				exit;
			}			
			
			//$_SESSION['tmp']['cid'] = $row['cid'];
			$_SESSION['tmp']['cid'] = $row['last'];
			$cookie = array('user'=>$username, 'pass'=>$_REQUEST['password']);
			// setcookie('scan', serialize($cookie), time()+31556926 ,'/');
			
			$locs = array();
			foreach($Latestlocation as $k=>$v){
				if(!empty($v['latitude'])){
					$locs[] = array($v['name'], $v['latitude'], $v['longitude'], $v['perimeter']);
				}
			}
			echo json_encode($locs);
			//var_dump($locs); 
			exit;
			
			//$scan = '';
			$perimeter = 0;
			if($res = $dbc->query("SELECT perimeter FROM ".$row['last']."_leave_time_settings")){
				$xrow = $res->fetch_assoc();
				//$scan = $xrow['scan_system'];
				$perimeter = $xrow['perimeter'];
			}
			
			
			$latitude = 0;
			$longitude = 0;
			if($res = $dbc->query("SELECT latitude, longitude FROM ".$row['last']."_settings")){
				$xrow = $res->fetch_assoc();
				$latitude = $xrow['latitude'];
				$longitude = $xrow['longitude'];
			}
			$array['lat'] = $latitude;
			$array['lon'] = $longitude;
			$array['per'] = $perimeter;
			//var_dump($array); exit;
			//echo json_encode('success'); exit;
			
			$_SESSION['tmp']['cid'] = $row['last'];
			$cookie = array('user'=>$username, 'pass'=>$_REQUEST['password']);
			// setcookie('scan', serialize($cookie), time()+31556926 ,'/');
			
			ob_clean();
			echo json_encode($array); exit;
		}else{
			echo json_encode('wrong');
		}
	}else{
		echo json_encode('wrong');
	}








