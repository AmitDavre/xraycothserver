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

					//$data[$val]['branch'][$v['branch']] = $v['branch'];
					//$data[$val][$v['branch']]['division'][$v['division']] = $v['division'];
					$data[$val][$v['branch']][$v['division']]['department'][$v['department']] = $v['department'];
					$data[$val][$v['branch']][$v['division']]['team'][$k] = $k;
					
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

					//$data[$v['entity']]['branch'][$v['branch']] = $v['branch'];
					//$data[$v['entity']][$v['branch']]['division'][$v['division']] = $v['division'];
					$data[$v['entity']][$v['branch']][$v['division']]['department'][$v['department']] = $v['department'];
					$data[$v['entity']][$v['branch']][$v['division']]['team'][$k] = $k;
					
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

					//$data[$v['entity']]['branch'][$v['branch']] = $v['branch'];
					//$data[$v['entity']][$v['branch']]['division'][$v['division']] = $v['division'];
					$data[$v['entity']][$v['branch']][$v['division']]['department'][$v['department']] = $v['department'];
					$data[$v['entity']][$v['branch']][$v['division']]['team'][$k] = $k;
				
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

					//$data[$v['entity']]['branch'][$v['branch']] = $v['branch'];
					//$data[$v['entity']][$v['branch']]['division'][$v['division']] = $v['division'];
					$data[$v['entity']][$v['branch']][$v['division']]['department'][$v['department']] = $v['department'];
					$data[$v['entity']][$v['branch']][$v['division']]['team'][$k] = $k;
				
					$entity[$v['entity']] = $v['entity'];
					$branch[$v['branch']] = $v['branch'];
					$division[$v['division']] = $v['division'];
					$department[$v['department']] = $v['department'];
					
					$team[$k] = $k;
				}
			}
		}
	}

	/*if($_REQUEST['access'] == 'groups' && isset($_REQUEST['values'])){
		foreach($_REQUEST['values'] as $key=>$val){
			foreach($teams as $k=>$v){
				if($v['groups'] == $val){
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
	}*/
	
	if($_REQUEST['access'] == 'teams' && isset($_REQUEST['values'])){
		foreach($_REQUEST['values'] as $key=>$val){
			foreach($teams as $k=>$v){
				if($k == $val){

					//$data[$v['entity']]['branch'][$v['branch']] = $v['branch'];
					//$data[$v['entity']][$v['branch']]['division'][$v['division']] = $v['division'];
					$data[$v['entity']][$v['branch']][$v['division']]['department'][$v['department']] = $v['department'];
					$data[$v['entity']][$v['branch']][$v['division']]['team'][$k] = $k;
				
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


	// echo '<pre>';
	// print_r($data);
	// echo '</pre>';
	// exit;
	

	$tableRow = '';
	if($data){
		$countE = 0;
		$countB = 0;
		foreach($data as $key=>$datas){

			foreach($datas as $key1=>$val){

				foreach($val as $kdd=>$vdd){

					$countB++;
					if($countB == 1){$BranchName = $branches[$key1][$lang]; }else{$BranchName = '';}
					$countE++;
					if($countE == 1){$entitiesName = $entities[$key][$lang]; }else{$entitiesName = '';}

					$tableRow .= '
								<tr>
									
									<td class="vat">'.$entitiesName.'</td>
									<td class="vat">';
									
									$tableRow .= $BranchName.'<br>';
								
					$tableRow .= '				
									</td>
									<td class="vat">';
									
									$tableRow .= $divisions[$kdd][$lang].'<br>';
									
					$tableRow .= '				
									</td>
									<td class="vat">';
									foreach($vdd['department'] as $k=>$v){
										$tableRow .= $departments[$v][$lang].'<br>';
									}
					$tableRow .= '				
									</td>
									<td class="vat teamsAct">';
									foreach($vdd['team'] as $k=>$v){
										$tableRow .= $teams[$v]['code'].'<br>';
									}
					
					$tableRow .= '				
									</td>
								</tr>';
				}
				$countB=0;
				
			}
			$countE=0;
		}
		
	}
	
	/*$tableRow = '';

	if($data){
		foreach($data as $key=>$val){
			$tableRow .= '
						<tr>
							
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
							<td class="vat teamsAct">';
							foreach($val['team'] as $k=>$v){
								$tableRow .= $teams[$v]['code'].'<br>';
							}
			
			$tableRow .= '				
							</td>
						</tr>';
		}
	}*/
	
	$result['entity'] = $entity;
	$result['branch'] = $branch;
	$result['division'] = $division;
	$result['department'] = $department;
	
	$result['team'] = $team;
	$result['tableRow'] = $tableRow;
	//var_dump($result); exit;	

	if(!empty($result))
	{
		$_SESSION['session_store'] = $result;
	}
	else
	{
		$_SESSION['session_store'] = '';
	}


	echo json_encode($result);


