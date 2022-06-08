<?
	
	if(session_id()==''){session_start();}
	ob_start();
	// $cid = $_SESSION['rego']['cid'];

	include('../../dbconnect/db_connect.php');


	//include(DIR.'files/functions.php');
	include(DIR.'time/functions.php');
	
	$shiftplans = getFullShiftplan();
	$shiftplans['OFF'] = 'OFF';
	$shiftplans['PUB'] = 'PUB';
	$shiftplans[''] = '';
	//var_dump($shiftplans); //exit;


	// echo '<pre>';
	// print_r($shiftplans);
	// echo '</pre>';

	// die();
	
//$_REQUEST['monthfilter'] = 11;
//$_REQUEST['teamfilter'] = 'all';


	// echo $_REQUEST['selBoxTeamVal'];
	// die();



	$ym = $_SESSION['rego']['cur_year'].'-'.$_REQUEST['monthfilter'].'-';
	$now = strtotime($ym.date('d'));
	$today = strtotime(date('Y-m-d', strtotime("+1 day")));
	$cols = date('t', strtotime($ym.'-01'));
	
	$sql_details = array(
		'user' => $my_username,
		'pass' => $my_password,
		'db'   => $my_dbcname,
		'host' => $my_database
	);
	
	$table = $cid.'_monthly_shiftplans_'.$cur_year;//'shr0100_employees';
	$primaryKey = 'id';
	
	$where = " month = '".$_REQUEST['monthfilter']."'";	

	// get not active users id in array 

	$empArray = array();
	
	$sql1 = "SELECT * FROM ".$cid."_employees WHERE emp_status = '1'";
	if($res1 = $dbc->query($sql1)){
		while($row1 = $res1->fetch_assoc())
				{
			
			$empArray[] =$row1['emp_id'];
		}
	}


	// $stringVal =  implode(',', $empArray);
	$stringVal = "'".implode("','", $empArray)."'";

	$where .= " AND emp_id in (".$stringVal.")";
	
	$teams = '';
	$tmp = getShifTeams();

	// echo '<pre>';
	// print_r($tmp);
	// echo '</pre>';


	if($_SESSION['rego']['teams'] != 'all'){ // NOT ADMIN //////////////////////
		$team = explode(',',$_SESSION['rego']['teams']);
		foreach($team as $k=>$v){
			$teams .= "'$v',";
		}
		$teams = substr($teams,0,-1);
	}


	// if($_REQUEST['teamfilter'] != 'all'){
	// 	$teams = "'".$_REQUEST['teamfilter']."'";
	// 	$where .= " AND shiftteam_name in(".$teams.")";

	// }	
	// else
	// {
	// 	$where .= " AND shiftteam_name NOT IN ('noteam')";
	// }	



	$teamString = $_REQUEST['selBoxTeamVal'];

	$explodeS =	explode(',', $teamString);

	$sessionTeamAR = array();
	foreach ($explodeS as $key => $sessionTeam) {

		$zname_clean = preg_replace('/\s*/', '', $sessionTeam);
		// convert the string to all lowercase
		$sessionTeamAR[] = strtolower($zname_clean);
	}



	$str = "'".implode("','", $sessionTeamAR)."'";
    // echo $str;

    // die();

	if($_REQUEST['selBoxTeamVal'] != ''){
		$where .= " AND shiftteam_name in(".$str.")";

	}	
	else
	{
		$where .= " AND shiftteam_name ='emptyvalue' ";
	}


	//echo $where; //exit;
	
	$nr=0;
	$columns[] = array( 'db' => 'emp_id', 'dt' => $nr, 'formatter' => function($d, $row ){return '<span class="emp_id">'.$d.'</span>'; }); $nr++; // display emp id

	// echo $d;

	$columns[] = array( 

		'db' => 'emp_id', 
		'dt' => $nr, 
		'formatter' => function($d, $row )
		{

		$cid = $_SESSION['rego']['cid'];
		include('../../dbconnect/db_connect.php');

		// $sql = "SELECT * FROM ".$cid."_employees WHERE emp_id = '".$d."'";
		// if($res = $dbc->query($sql)){
		// 	if($row = $res->fetch_assoc()){

		// 		$emp_name = $row['team_name'] ;
		// 	}
		// }

		$emPiD =  $d.'-'.$_REQUEST['monthfilter']; 

		$sql5 = "SELECT * FROM ".$cid."_monthly_shiftplans_".$cur_year." WHERE id = '".$emPiD."'";

		// echo $sql5;
		// die();
		if($res5 = $dbc->query($sql5)){
			if($row5 = $res5->fetch_assoc()){

				$emp_name = strtoupper($row5['shiftteam_name']) ;
			}
		}


		return $emp_name;

	}); 
	$nr++; 


	$columns[] = array( 'db' => $_SESSION['rego']['lang'].'_name', 'dt' => $nr); $nr++;

	// WRK
		$columns[] = array( 

		'db' => 'emp_id', 
		'dt' => $nr, 
		'formatter' => function($d, $row )
		{

		$cid = $_SESSION['rego']['cid'];
		$cur_year = $_SESSION['rego']['cur_year'];
		include('../../dbconnect/db_connect.php');
		$emPiD =  $d.'-'.$_REQUEST['monthfilter']; 

		$sql5 = "SELECT * FROM ".$cid."_monthly_shiftplans_".$cur_year." WHERE id = '".$emPiD."'";

		// echo $sql5;
		// die();
		if($res5 = $dbc->query($sql5)){
			if($row5 = $res5->fetch_assoc()){

				$wkd = $row5['wkd'] ;
			}
		}

		return $wkd;

	}); 
	$nr++;

	// OFF
			$columns[] = array( 

			'db' => 'emp_id', 
			'dt' => $nr, 
			'formatter' => function($d, $row )
			{

			$cid = $_SESSION['rego']['cid'];
			$cur_year = $_SESSION['rego']['cur_year'];
			include('../../dbconnect/db_connect.php');
			$emPiD =  $d.'-'.$_REQUEST['monthfilter']; 

			$sql5 = "SELECT * FROM ".$cid."_monthly_shiftplans_".$cur_year." WHERE id = '".$emPiD."'";

			// echo $sql5;
			// die();
			if($res5 = $dbc->query($sql5)){
				if($row5 = $res5->fetch_assoc()){

					$off = $row5['off'] ;
				}
			}

			return $off;

		}); 
		$nr++;
	
	// PUB
		$columns[] = array( 

		'db' => 'emp_id', 
		'dt' => $nr, 
		'formatter' => function($d, $row )
		{

		$cid = $_SESSION['rego']['cid'];
		$cur_year = $_SESSION['rego']['cur_year'];
		include('../../dbconnect/db_connect.php');
		$emPiD =  $d.'-'.$_REQUEST['monthfilter']; 

		$sql5 = "SELECT * FROM ".$cid."_monthly_shiftplans_".$cur_year." WHERE id = '".$emPiD."'";

		// echo $sql5;
		// die();
		if($res5 = $dbc->query($sql5)){
			if($row5 = $res5->fetch_assoc()){

				$pub = $row5['pub'] ;
			}
		}

		return $pub;

	}); 
	$nr++;		

	// PEN
		$columns[] = array( 

		'db' => 'emp_id', 
		'dt' => $nr, 
		'formatter' => function($d, $row )
		{

		$cid = $_SESSION['rego']['cid'];
		$cur_year = $_SESSION['rego']['cur_year'];
		include('../../dbconnect/db_connect.php');
		$emPiD =  $d.'-'.$_REQUEST['monthfilter']; 




		$sql6123 = "SELECT * FROM ".$cid."_monthly_shiftplans_".$cur_year." WHERE id = '".$emPiD."'";
		if($res6123 = $dbc->query($sql6123)){
			while($row6123= $res6123->fetch_assoc()){

				$getRowwkd  = $row6123['wkd']; //18
				$getPub  = $row6123['pub']; //1
				$getOff  = $row6123['off']; //4
				$getVod  = $row6123['vod']; //8 
			}
		}		

	

		// $sql8 = "SELECT * FROM ".$cid."_leaves_data WHERE emp_id = '".$d."' AND status = 'RQ' ";
		// if($res8 = $dbc->query($sql8)){
		// 	while($row8 = $res8->fetch_assoc()){

		// 		$leaveStart = $row8['date'];
		// 	}
		// }		

		// $sql7 = "SELECT * FROM ".$cid."_employees WHERE emp_id = '".$d."'";
		// if($res7 = $dbc->query($sql7)){
		// 	while($row7 = $res7->fetch_assoc()){

		// 		$teamName = $row7['team_name'];
		// 		$EmpName = $row7['en_name'];
		// 	}
		// }

		// // get month from date  
		// if($leaveStart != '')
		// {
		// 	$leaveMonth = date('F', strtotime($leaveStart));
		// }
		// else
		// {
		// 	$leaveMonth = 'test';
		// }


		//  $monthNum = $_REQUEST['monthfilter'];
		//  $monthName = date("F", mktime(0, 0, 0, $monthNum, 10));

		
		 

		 
		//  if($leaveMonth == $monthName)
		//  {
		// 	$pen = '<span style="display:none;" id="leave_request">yes</span><span data-id="'.$d.'"  style="color:red;" class="yes '.$d.'">PEN</span>';
		//  }
		//  else
		//  {
		// 	$pen = '<span style="display:none;" id="leave_request">no</span><span style="color:#fff;" class="no"></span>' ;
		//  }

		 $pens =$getVod;
		return $pens;

	}); 
	$nr++;	

	// die();


	$columns[] = array( 'db' => 'emp_id', 'dt' => $nr, 'formatter' => function($d, $row ){

	$d2 = "'".$d."'" ;

	// return '<span class="emp_idd"  > <a  class="delEmployee" title="Delete"><i class="fa fa-trash fa-lg deleteicon" onclick="deleteEmp(this,'.$d2.');"></i></a></span>'; }); $nr++; // display emp id
			if($_SESSION['rego']['time_planning']['del']) {

				return '<span class="emp_idd"  > <a style="padding:0 5px" tabIndex="-1" data-id="'.$d.'" class="delEmployee"><i class="fa fa-trash fa-lg"></i></a></span>'; 

			}
			else
			{
				return '<span class="emp_idd"  > <a style="padding:0 5px" tabIndex="-1" data-id="" class="delEmployee"><i class="fa fa-trash fa-lg"></i></a></span>'; 
			}

		}); $nr++; 

	
	for($i=1;$i<=$cols;$i++){
		$columns[] = array( 'db' => 'D'.$i, 'dt' => $nr, 'formatter' => function($d, $row )use($shiftplans, $today, $ym, $nr, $cols){


			// echo '<pre>';
			// print_r($d);
			// echo '</pre>';

			if($d == 'HD'){
				return '<div style="background:#8B40CB; padding:3px"><i style="color:#eef" class="fa fa-rebel fa-lg"></i></div>';
			}else{
				$plan = '';
				$bg = '#666';
				$color = '#fff';
				if($d != 'OFF' && isset($shiftplans[$d]['f1']) && isset($shiftplans[$d]['u2'])){
					$plan = $shiftplans[$d]['f1'].' - '.$shiftplans[$d]['u2'];
					//$plan = $d;
					if(isset($shiftplans[$d]['bg'])){
						$bg = $shiftplans[$d]['bg'];
						$color = '#000';
					}else{
						$bg = 'transparent';
						$color = '#000';
					}
				}				
				if($d == 'PUB'){
					$bg = '#000';
					$color = '#fff';
				}				

				/*if(strtotime($ym.($nr-1)) < $today){	
					return '<div data-toggle="tooltip" data-placement="left" title="'.$plan.'" style="text-align:center; padding:3px; background:transparent; color:#999">'.$d.'</div>';
				}else{*/
					if($d == '')
					{
						$addTraingleclass = '';
						$addselectCss = 'addselectCss';
						$bg= '#c71010';
					}
					else
					{
						$addTraingleclass  = '';
						$addselectCss  = '';
						$bg = $bg;


					}
					$str = '<select data-col="D'.($nr-7).'" class=" '.$addselectCss.' dbselect" data-toggle="tooltip" data-placement="left" title="'.$plan.'" style="width:53px;text-overflow: ellipsis; padding-top:3px !important; padding-bottom:3px !important; background:'.$bg.'; color:'.$color.'; text-align-last:center; margin:1px; font-weight:600">';
						foreach($shiftplans as $k=>$v){
							/*$str .= '<option ';
							if($d == $k){$str .= 'selected';}
							// $str .= ' >'.$k.'</option>';
							$str .= ' >'.$k.' - '.$v['name'].'</option>';*/

							if($d == $k)
							{	
								$kk =substr($k,0,3);
								$val = 'selected'; 

								if($d == '' )
								{
									$str .= '<option value="'.$k.'" '.$val.'>No Plan</option>';
								}
								else
								{
									$str .= '<option value="'.$k.'" '.$val.'>'.$kk.'</option>';
								}

							}
							else
							{
								$val = ''; 
								if($v['name'] == 'O' || $v['name'] == 'P')
								{
									$nameVar= '';
								}
								else
								{
									$nameVar= ' - '.$v['name'];
								}
								$opt = $k.$nameVar;
								
								$str .= '<option value="'.$k.'" '.$val.'>'.$opt.'</option>';
							}

							
						}
					$str .= '</select>';
				//}	
			}	
			return $str;
		}); $nr++;



	}


require(DIR.'ajax/ssp.class.php' );
/*var_dump(
	SSP::simple( $_POST, $sql_details, $table, $primaryKey, $columns, $where)
);*/ //exit;


// echo $where ;
// echo '<pre>';
// print_r($_POST);
// echo '</pre>';
// die();
ob_clean();
echo json_encode(
	SSP::simple( $_POST, $sql_details, $table, $primaryKey, $columns, $where)
);






