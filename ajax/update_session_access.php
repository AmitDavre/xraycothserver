<?

	if(session_id()==''){session_start();}
	include('../dbconnect/db_connect.php');
	//var_dump($_REQUEST); //exit;

	//unset($_SESSION['rego']['mn_'.$_REQUEST['access']]);
	
	//var_dump($teams);
		
	$entity = array();
	$branch = array();
	$division = array();
	$department = array();
	$team = array();

	if($_REQUEST['access'] == 'entities' && isset($_REQUEST['values'])){
		foreach($_REQUEST['values'] as $key=>$val){
			foreach($teams as $k=>$v){
				if($v['entity'] == $val){
					$branch[] = $v['branch'];
					$division[] = $v['division'];
					$department[] = $v['department'];
					$team[$k] = $k;
				}
			}
		}
		$entity = $_REQUEST['values'];
	}
	
	if($_REQUEST['access'] == 'divisions' && isset($_REQUEST['values'])){
		foreach($_REQUEST['values'] as $key=>$val){
			foreach($teams as $k=>$v){
				if($v['division'] == $val){
					$entity[] = $v['entity'];
					$branch[] = $v['branch'];
					$department[] = $v['department'];
					$team[$k] = $k;
				}
			}
		}
		$division = $_REQUEST['values'];
	}
	
	if($_REQUEST['access'] == 'departments' && isset($_REQUEST['values'])){
		foreach($_REQUEST['values'] as $key=>$val){
			foreach($teams as $k=>$v){
				if($v['department'] == $val){
					$entity[] = $v['entity'];
					$branch[] = $v['branch'];
					$division[] = $v['division'];
					$team[$k] = $k;
				}
			}
		}
		$department = $_REQUEST['values'];
	}
	
	if($_REQUEST['access'] == 'branches' && isset($_REQUEST['values'])){
		foreach($_REQUEST['values'] as $key=>$val){
			foreach($teams as $k=>$v){
				if($v['branch'] == $val){
					$entity[] = $v['entity'];
					$division[] = $v['division'];
					$department[] = $v['department'];
					$team[$k] = $k;
				}
			}
		}
		$branch = $_REQUEST['values'];
	}
	
	if($_REQUEST['access'] == 'teams' && isset($_REQUEST['values'])){
		foreach($_REQUEST['values'] as $k=>$v){
			$entity[] = $entities[$teams[$v]['entity']]['id'];
			$branch[] = $branches[$teams[$v]['branch']]['id'];
			$division[] = $divisions[$teams[$v]['division']]['id'];
			$department[] = $departments[$teams[$v]['department']]['id'];
		}
		$team = $_REQUEST['values'];
	}
	
	//var_dump($entity);
	//var_dump($branch);
	//var_dump($division);
	//var_dump($department);
	//var_dump($team);
	
	$_SESSION['rego']['sel_entities'] = implode(',', $entity);
	$_SESSION['rego']['sel_branches'] = implode(',', $branch);
	$_SESSION['rego']['sel_divisions'] = implode(',', $division);
	$_SESSION['rego']['sel_departments'] = implode(',', $department);
	$_SESSION['rego']['sel_teams'] = implode(',', $team);
	


