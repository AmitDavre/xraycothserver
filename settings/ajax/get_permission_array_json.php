<?

	if(session_id()==''){session_start(); }
	ob_start();
	include('../../dbconnect/db_connect.php');
	//include(DIR.'files/functions.php');
	//var_dump($branches); exit;
	
	if($_REQUEST['type'] == 'entities'){
		foreach($entities as $k=>$v){
			$array[] = array('key'=>$k, 'value'=>$v[$lang]);
		}
	}
	
	if($_REQUEST['type'] == 'branches'){
		foreach($branches as $k=>$v){
			$array[] = array('key'=>$k, 'value'=>$v[$lang]);
		}
	}
	
	if($_REQUEST['type'] == 'divisions'){
		foreach($divisions as $k=>$v){
			$array[] = array('key'=>$k, 'value'=>$v[$lang]);
		}
	}
	
	if($_REQUEST['type'] == 'departments'){
		foreach($departments as $k=>$v){
			$array[] = array('key'=>$k, 'value'=>$v[$lang]);
		}
	}
	
	if($_REQUEST['type'] == 'teams'){
		foreach($teams as $k=>$v){
			$array[] = array('key'=>$k, 'value'=>$v[$lang]);
		}
	}
	
	ob_clean();
	echo json_encode($array);;

