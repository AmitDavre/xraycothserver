<?

	if(session_id()==''){session_start();}
	ob_start();
	include('../../dbconnect/db_connect.php');
	
	//$_REQUEST['code'] = 'Day Team';
	$class = array(1=>'fc-nonwork', 2=>'fc-holiday', 3=>'fc-leave', 4=>'fc-payday');
	
	$data = array();
	$res = $dbc->query("SELECT * FROM rego_calendar");
	if(!mysqli_error($dbc)){
		while($row = $res->fetch_assoc()){
			$data[strtotime($row['start'])] = array('type'=>$row['type'], 'title'=>$row[$lang.'_title'], 'class'=>$class[$row['type']]);
		}
	}
	//var_dump($data);
	
	$json = array();
	$splan = array();
	$res = $dbc->query("SELECT dates FROM ".$cid."_shiftplans_".$cur_year." WHERE code = '".$_REQUEST['code']."'");
	if(!mysqli_error($dbc)){
		if($row = $res->fetch_assoc()){
			$splan = unserialize($row['dates']);
		}
	}else{
		echo mysqli_error($dbc);
	}
	//$splan = $plan['days'];
	//var_dump($splan);
	$n=0;
	foreach($splan as $k=>$v){
		$s = date('Y-m-d',$k);
		$t = '-';
		if($v['from1'] != ''){ $t = $v['from1'].' - '.$v['until2'];}
		$json[$n] = array(
			'start'=>$s,
			'title'=>$t);
		if(isset($data[$k])){
			$json[$n]['descr'] = $data[$k]['title'];
			$json[$n]['type'] = $data[$k]['type'];
			$json[$n]['class'] = $data[$k]['class'];
		}
		$n++;
	}
	//var_dump($json); exit;
	ob_clean();
 	echo json_encode($json);
	exit;
	
	/*$data = array();
	$res = $dbc->query("SELECT * FROM ".$_REQUEST['cid']."_calendar WHERE year = '".$_SESSION['xhr']['cur_year']."'");
	if(!mysqli_error($dbc)){
		while($row = $res->fetch_assoc()){
			$data[strtotime($row['start'])] = array('type'=>$row['type'], 'title'=>$row[$_SESSION['xhr']['lang'].'_title'], 'class'=>$class[$row['type']]);
		}
	}*/
	//var_dump($data);
	
