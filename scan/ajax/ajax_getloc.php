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
	$selbrancch =  $_REQUEST['selbrancch'];


			$my_dbcname = $prefix.$selcomp;
			$dbc = new mysqli($my_database,$my_username,$my_password,$my_dbcname);
			if($dbc->connect_error) {
				echo '<p style="width:900px; margin:0 auto; margin-top:20px;" class="box_err">Error: ('.$dbc->connect_errno.') '.$dbc->connect_error.'<br>Please try again later or report this error to <a href="mailto:admin@regohr.com">admin@regohr.com</a></p>';
			}else{
				mysqli_set_charset($dbc,"utf8");
			}


			if($resbb = $dbc->query("SELECT * FROM ".$selcomp."_branches_data WHERE ref = '".$selbrancch."'")){
				if($xrowbb = $resbb->fetch_assoc()){


				$myarray[] = array(

						"1"=> json_decode($xrowbb['loc1']),
						"2"=> json_decode($xrowbb['loc2']),
						"3"=> json_decode($xrowbb['loc3']),
						"4"=> json_decode($xrowbb['loc4']),
						"5"=> json_decode($xrowbb['loc5']),

					);
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


			$scan = '';
			//$locations = array();
			if($res = $dbc->query("SELECT scan_system FROM ".$selcomp."_leave_time_settings")){
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
			$_SESSION['tmp']['cid'] = $selcomp;
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
			if($res = $dbc->query("SELECT perimeter FROM ".$selcomp."_leave_time_settings")){
				$xrow = $res->fetch_assoc();
				//$scan = $xrow['scan_system'];
				$perimeter = $xrow['perimeter'];
			}
			
			
			$latitude = 0;
			$longitude = 0;
			if($res = $dbc->query("SELECT latitude, longitude FROM ".$selcomp."_settings")){
				$xrow = $res->fetch_assoc();
				$latitude = $xrow['latitude'];
				$longitude = $xrow['longitude'];
			}
			$array['lat'] = $latitude;
			$array['lon'] = $longitude;
			$array['per'] = $perimeter;
			//var_dump($array); exit;
			//echo json_encode('success'); exit;
			
			$_SESSION['tmp']['cid'] = $selcomp;
			$cookie = array('user'=>$username, 'pass'=>$_REQUEST['password']);
			// setcookie('scan', serialize($cookie), time()+31556936 ,'/');
			
			ob_clean();
			echo json_encode($array); exit;







