<?

	if(session_id()==''){session_start();}
	include('../dbconnect/db_connect.php');
	//var_dump($_REQUEST); //exit;
	//var_dump($teams);
		
	/*$entity = $_SESSION['rego']['selpr_entities'];
	if($_REQUEST['access'] == 'entities' && isset($_REQUEST['values'])){
		$entity = $_REQUEST['values'];
		foreach($teams as $k=>$v){
			if($v['entity'] == $entity){
				$branch[$v['branch']] = $v['branch'];
				$division[$v['division']] = $v['division'];
				$department[$v['department']] = $v['department'];
				$team[$k] = $k;
			}
		}
		//$_SESSION['rego']['pr_branches'] = implode(',', $branch);
		//$_SESSION['rego']['pr_divisions'] = implode(',', $division);
		//$_SESSION['rego']['pr_departments'] = implode(',', $department);
		//$_SESSION['rego']['pr_teams'] = implode(',', $team);
		$_SESSION['rego']['selpr_entities'] = $entity;
		$_SESSION['rego']['selpr_branches'] = implode(',', $branch);
		$_SESSION['rego']['selpr_divisions'] = implode(',', $division);
		$_SESSION['rego']['selpr_departments'] = implode(',', $department);
		$_SESSION['rego']['selpr_teams'] = implode(',', $team);
	}

	if($_REQUEST['access'] == 'divisions' && isset($_REQUEST['values'])){
		$_SESSION['rego']['selpr_divisions'] = implode(',', $_REQUEST['values']);
	}
	if($_REQUEST['access'] == 'departments' && isset($_REQUEST['values'])){
		$_SESSION['rego']['selpr_departments'] = implode(',', $_REQUEST['values']);
	}
	if($_REQUEST['access'] == 'branches' && isset($_REQUEST['values'])){
		$_SESSION['rego']['selpr_branches'] = implode(',', $_REQUEST['values']);
	}
	if($_REQUEST['access'] == 'teams' && isset($_REQUEST['values'])){
		$_SESSION['rego']['selpr_teams'] = implode(',', $_REQUEST['values']);
	}
	if($_REQUEST['access'] == 'gov_entities' && isset($_REQUEST['values'])){
		$_SESSION['rego']['gov_entity'] = $_REQUEST['values'];
	}
	if($_REQUEST['access'] == 'gov_branches' && isset($_REQUEST['values'])){
		$_SESSION['rego']['gov_branch'] = $_REQUEST['values'];
	}

	echo '<pre>';
	print_r($_SESSION);
	echo '</pre>';*/

	$teamAcc = explode(',', $_SESSION['rego']['mn_teams']); 


	$entity = array();
	$branch = array();
	$division = array();
	$department = array();
	$team = array();

	if($_REQUEST['access'] == 'entities' && isset($_REQUEST['values'])){
		foreach($_REQUEST['values'] as $key=>$val){
			foreach($teams as $k=>$v){

				if(in_array($k, $teamAcc)){
					if($v['entity'] == $val){
						$branch[] = $v['branch'];
						$division[] = $v['division'];
						$department[] = $v['department'];
						$team[$k] = $k;
					}
				}
			}
		}
		$entity = $_REQUEST['values'];
	}
	
	if($_REQUEST['access'] == 'divisions' && isset($_REQUEST['values'])){
		foreach($_REQUEST['values'] as $key=>$val){
			foreach($teams as $k=>$v){

				if(in_array($k, $teamAcc)){
					if($v['division'] == $val){
						$entity[] = $v['entity'];
						$branch[] = $v['branch'];
						$department[] = $v['department'];
						$team[$k] = $k;
					}
				}
			}
		}
		$division = $_REQUEST['values'];
	}
	
	if($_REQUEST['access'] == 'departments' && isset($_REQUEST['values'])){
		foreach($_REQUEST['values'] as $key=>$val){
			foreach($teams as $k=>$v){

				if(in_array($k, $teamAcc)){
					if($v['department'] == $val){
						$entity[] = $v['entity'];
						$branch[] = $v['branch'];
						$division[] = $v['division'];
						$team[$k] = $k;
					}
				}
			}
		}
		$department = $_REQUEST['values'];
	}
	
	if($_REQUEST['access'] == 'branches' && isset($_REQUEST['values'])){
		foreach($_REQUEST['values'] as $key=>$val){
			foreach($teams as $k=>$v){

				if(in_array($k, $teamAcc)){
					if($v['branch'] == $val){
						$entity[] = $v['entity'];
						$division[] = $v['division'];
						$department[] = $v['department'];
						$team[$k] = $k;
					}
				}
			}
		}
		$branch = $_REQUEST['values'];
	}
	
	if($_REQUEST['access'] == 'teams' && isset($_REQUEST['values'])){
		/*foreach($_REQUEST['values'] as $k=>$v){
			$entity[] = $entities[$teams[$v]['entity']]['id'];
			$branch[] = $branches[$teams[$v]['branch']]['id'];
			$division[] = $divisions[$teams[$v]['division']]['id'];
			$department[] = $departments[$teams[$v]['department']]['id'];
		}*/

		foreach($_REQUEST['values'] as $key=>$val){
			foreach($teams as $k=>$v){

				//if(in_array($k, $teamAcc)){ echo '1<br>';
					if($k == $val){
						$entity[] = $v['entity'];
						$branch[] = $v['branch'];
						$division[] = $v['division'];
						$department[] = $v['department'];
						//$groups[] = $v['groups'];
						//$team[$k] = $k;
					}
				//}
			}
		}
		$team = $_REQUEST['values'];
	}
	
	//var_dump($entity);
	//var_dump($branch);
	//var_dump($division);
	//var_dump($department);
	//var_dump($team);

	$_SESSION['rego']['pr_entities'] = $_SESSION['rego']['mn_entities'];
	$_SESSION['rego']['pr_branches'] = $_SESSION['rego']['mn_branches'];
	$_SESSION['rego']['pr_divisions'] = $_SESSION['rego']['mn_divisions'];
	$_SESSION['rego']['pr_departments'] = $_SESSION['rego']['mn_departments'];
	$_SESSION['rego']['pr_teams'] = $_SESSION['rego']['mn_teams'];
	
	$_SESSION['rego']['selpr_entities'] = implode(',', array_unique($entity));
	$_SESSION['rego']['selpr_branches'] = implode(',', array_unique($branch));
	$_SESSION['rego']['selpr_divisions'] = implode(',', array_unique($division));
	$_SESSION['rego']['selpr_departments'] = implode(',', array_unique($department));
	$_SESSION['rego']['selpr_teams'] = implode(',', array_unique($team));
	












