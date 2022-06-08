<?

	if(session_id()==''){session_start();}
	include('../../dbconnect/db_connect.php');
	//var_dump($_REQUEST); //exit;

	//unset($_SESSION['rego']['mn_'.$_REQUEST['access']]);
	
	//var_dump($teams);
	
	$data = array();
	$entity = array();
	$branch = array();
	$division = array();
	$department = array();
	$team = array();

	if($_REQUEST['access'] == 'entities' && isset($_REQUEST['values'])){
		foreach($_REQUEST['values'] as $key=>$val){
			foreach($teams as $k=>$v){
				if($v['entity'] == $val){
					$data[$val]['branch'][$v['branch']] = $v['branch'];
					$data[$val]['division'][$v['division']] = $v['division'];
					$data[$val]['department'][$v['department']] = $v['department'];
					$data[$val]['team'][$k] = $k;
					
					$entity[$v['entity']] = $v['entity'];
					$branch[$v['branch']] = $v['branch'];
					$division[$v['division']] = $v['division'];
					$department[$v['department']] = $v['department'];
					$team[$k] = $k;
				}
			}
		}
	}
	//var_dump($data); exit;
	
	if($_REQUEST['access'] == 'branches' && isset($_REQUEST['values'])){
		foreach($_REQUEST['values'] as $key=>$val){
			foreach($teams as $k=>$v){
				if($v['branch'] == $val){
					$data[$v['entity']]['branch'][$v['branch']] = $v['branch'];
					$data[$v['entity']]['division'][$v['division']] = $v['division'];
					$data[$v['entity']]['department'][$v['department']] = $v['department'];
					$data[$v['entity']]['team'][$k] = $k;
					
					$entity[$v['entity']] = $v['entity'];
					$branch[$v['branch']] = $v['branch'];
					$division[$v['division']] = $v['division'];
					$department[$v['department']] = $v['department'];
					$team[$k] = $k;
				}
			}
		}
		$branch = $_REQUEST['values'];
	}
	
	if($_REQUEST['access'] == 'divisions' && isset($_REQUEST['values'])){
		foreach($_REQUEST['values'] as $key=>$val){
			foreach($teams as $k=>$v){
				if($v['division'] == $val){
					$data[$v['entity']]['branch'][$v['branch']] = $v['branch'];
					$data[$v['entity']]['division'][$v['division']] = $v['division'];
					$data[$v['entity']]['department'][$v['department']] = $v['department'];
					$data[$v['entity']]['team'][$k] = $k;
				
					$entity[$v['entity']] = $v['entity'];
					$branch[$v['branch']] = $v['branch'];
					$division[$v['division']] = $v['division'];
					$department[$v['department']] = $v['department'];
					$team[$k] = $k;
				}
			}
		}
		
	}
	
	if($_REQUEST['access'] == 'departments' && isset($_REQUEST['values'])){
		foreach($_REQUEST['values'] as $key=>$val){
			foreach($teams as $k=>$v){
				if($v['department'] == $val){
					$data[$v['entity']]['branch'][$v['branch']] = $v['branch'];
					$data[$v['entity']]['division'][$v['division']] = $v['division'];
					$data[$v['entity']]['department'][$v['department']] = $v['department'];
					$data[$v['entity']]['team'][$k] = $k;
				
					$entity[$v['entity']] = $v['entity'];
					$branch[$v['branch']] = $v['branch'];
					$division[$v['division']] = $v['division'];
					$department[$v['department']] = $v['department'];
					$team[$k] = $k;
				}
			}
		}
	}
	
	if($_REQUEST['access'] == 'teams' && isset($_REQUEST['values'])){
		foreach($_REQUEST['values'] as $key=>$val){
			foreach($teams as $k=>$v){
				if($k == $val){
					$data[$v['entity']]['branch'][$v['branch']] = $v['branch'];
					$data[$v['entity']]['division'][$v['division']] = $v['division'];
					$data[$v['entity']]['department'][$v['department']] = $v['department'];
					$data[$v['entity']]['team'][$k] = $k;
				
					$entity[$v['entity']] = $v['entity'];
					$branch[$v['branch']] = $v['branch'];
					$division[$v['division']] = $v['division'];
					$department[$v['department']] = $v['department'];
					$team[$k] = $k;
				}
			}
		}
		//$team = $_REQUEST['values'];
	}
	
	$tableRow = '';
	if($data){
		foreach($data as $key=>$val){
			$tableRow .= '
						<tr>
							<td></td>
							<td class="vat">'.$entities[$key][$lang].'</td>
							<td class="vat">';
							foreach($val['branch'] as $k=>$v){
								$tableRow .= $branches[$v][$lang].'<br>';
							}
			$tableRow .= '				
							</td>
							<td class="vat">';
							foreach($val['division'] as $k=>$v){
								$tableRow .= $divisions[$v][$lang].'<br>';
							}
			$tableRow .= '				
							</td>
							<td class="vat">';
							foreach($val['department'] as $k=>$v){
								$tableRow .= $departments[$v][$lang].'<br>';
							}
			$tableRow .= '				
							</td>
							<td class="vat">';
							foreach($val['team'] as $k=>$v){
								$tableRow .= $teams[$v][$lang].'<br>';
							}
			$tableRow .= '				
							</td>
							<td></td>
						</tr>';
		}
	}
	
	$result['entity'] = $entity;
	$result['branch'] = $branch;
	$result['division'] = $division;
	$result['department'] = $department;
	$result['team'] = $team;
	$result['tableRow'] = $tableRow;
	//var_dump($result); exit;
	
	echo json_encode($result);
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	/*var_dump($entity);
	var_dump($branch);
	var_dump($division);
	var_dump($department);
	var_dump($team);*/
	


