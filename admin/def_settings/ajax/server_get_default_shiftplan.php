<?
	if(session_id()==''){session_start(); ob_start();}
	include('../../dbconnect/db_connect.php');
	
	//$_REQUEST['code'] = 'Day Team';
	$class = array(1=>'fc-nonwork', 2=>'fc-holiday', 3=>'fc-leave', 4=>'fc-payday');
	
	$data = array();
	$res = $dba->query("SELECT * FROM rego_calendar");
	if(!mysqli_error($dba)){
		while($row = $res->fetch_assoc()){
			$data[strtotime($row['start'])] = array('type'=>$row['type'], 'title'=>$row[$lang.'_title'], 'class'=>$class[$row['type']]);
		}
	}
	//var_dump($data);
	
	$json = array();
	$splan = array();
	$res = $dba->query("SELECT dates FROM rego_default_shiftplans WHERE code = '".$_REQUEST['code']."'");
	if(!mysqli_error($dba)){
		if($row = $res->fetch_assoc()){
			$splan = unserialize($row['dates']);
		}
	}else{
		echo mysqli_error($dba);
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

?>