<?
	if(session_id()==''){session_start();}
	ob_start();
	include("../../dbconnect/db_connect.php");
	include(DIR.'files/functions.php');
	include(DIR.'leave/functions.php');
	//$leave_types = getLeaveTypes($cid);
	$lcolors = array(0=>'unplanned', 1=>'planned');
	$leave_types = getSelLeaveTypes($cid);
	
	$status_colors = array('RQ'=>'#3498db','CA'=>'#F27935','AP'=>'#2abb67','RJ'=>'#e74c3c','TA'=>'#34495e');
	/*$leave_colors = array(
		'bg-color-blue',
		'bg-color-blueLight',
		'bg-color-blueDark',
		'bg-color-green',
		'bg-color-greenLight',
		'bg-color-greenDark',
		'bg-color-red',
		'bg-color-yellow',
		'bg-color-orange',
		'bg-color-orangeDark',
		'bg-color-pink',
		'bg-color-pinkDark',
		'bg-color-purple',
		'bg-color-darken',
		'bg-color-lighten',
		'bg-color-white',
		'bg-color-grayDark',
		'bg-color-magenta',
		'bg-color-teal',
		'bg-color-redLight');*/
	$nr=0;
	/*foreach($leave_types as $k=>$v){
		$leave_types[$k]['class'] = $leave_colors[$nr]; $nr++;
	}*/
	$abc = array(1=>'A',2=>'B',3=>'C',4=>'D',5=>'E',6=>'F',7=>'G',8=>'H',9=>'I');
	//var_dump($leave_types);
	//var_dump($_REQUEST);	exit;
	//$_REQUEST['emp_id'] = 'ST200-001';
	
	$json = array();
	
	$where = '';
	if(!empty($_REQUEST['emp_id'])){
		$where = " WHERE emp_id = '".$_REQUEST['rego']['emp_id']."'";
	}
	$res = $dbc->query("SELECT * FROM ".$cid."_leaves_data".$where);
	
	if(!mysqli_error($dbc)){
		while($row = $res->fetch_assoc()){
			$half = ''; $day = 'Full day'; 
			if($row['days'] < 1){
				//var_dump($row['hours']);
				$hrs = round(($row['hours']/8)*10);
				//var_dump($hrs);
				$half = $abc[$hrs].'hrs'; 
				//var_dump($half);
				$day = $row['hours'].' hours';
			}
			if($row['days'] == 0.5){
				$day = 'Half day';
				$half = 'half';
			}
			$json[] = array(
				'id'=>$row['id'],
				'name'=>$leave_types[$row['leave_type']][$lang],
				'startdate'=>$row['date'],
				'enddate'=>$row['date'],
				'starttime'=>'',
				'endtime'=>'',
				'color'=>$status_colors[$row['status']],
				'url'=>'');
		}
	}
	//$json = array();
	$hd = getHolidays($_SESSION['rego']['cur_year']);
	foreach($hd as $k=>$v){
		$json[] = array(
			'id'=>$k,
			'name'=>$v[$_SESSION['rego']['lang']],
			'startdate'=>date('Y-m-d', strtotime($v['cdate'])),
			'enddate'=>date('Y-m-d', strtotime($v['cdate'])),
			'starttime'=>'',
			'endtime'=>'',
			'color'=>'purple',
			'url'=>'');
	}
	//var_dump($hd);	exit;

	
	
	$result['monthly'] = $json;
	//var_dump($json); exit;
	ob_clean();
 	echo json_encode($result);
	exit;

?>