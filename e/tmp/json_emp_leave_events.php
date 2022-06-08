<?
	if(session_id()==''){session_start(); ob_start();}
	//$cid = $_SESSION['xhr']['cid'];
	include("../../dbconnect/db_connect.php");
	include(DIR.'files/arrays_'.$_SESSION['xhr']['lang'].'.php');
	//$lng = getLangVariables($lang);
	//$leave_types = getLeaveTypes($cid);
	
	$leave_colors = array(
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
		'bg-color-redLight');
	$nr=0;
	foreach($leave_types as $k=>$v){
		$leave_types[$k]['class'] = $leave_colors[$nr]; $nr++;
	}
	//var_dump($leave_types);
	//var_dump($_REQUEST);	exit;
	
	$json = array();
	
	$where = '';
	if(!empty($_REQUEST['id'])){
		$where = " WHERE emp_id = '".$_REQUEST['id']."'";
	}
	$res = $dbc->query("SELECT * FROM ".$cid."_leaves".$where);
	
	if(!mysqli_error($dbc)){
		while($row = $res->fetch_assoc()){
			$tmp = explode('|', $row['type']);
			$title = '';
			if(count($tmp) > 1){
				$title = $leave_types[$tmp[0]][$_SESSION['xhr']['lang']].' - '.$leave_types[$tmp[1]][$_SESSION['xhr']['lang']];
				$clName = 'bg-color-blue txt-color-white';
			}else{
				$title = $leave_types[$tmp[0]][$_SESSION['xhr']['lang']];
				$clName = $leave_types[$tmp[0]]['class'].' txt-color-white';
			}
			$json[] = array(
				'id'=>$row['id'],
				'type'=>'rem3',
				'dept'=>$row['dept_code'],
				'start'=>date('Y-m-d',strtotime($row['start'])),
				'end'=>date('Y-m-d',strtotime($row['end'].'+1 day')),
				'title'=>$title,
				'description'=>'',
				'icon'=>'fa-times',
				'className'=>$clName);
		}
	}
	var_dump($json); //exit;
	ob_clean();
 	echo json_encode($json);
	exit;

?>