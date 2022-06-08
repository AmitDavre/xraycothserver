<?

	if(session_id()==''){session_start();}
	include('../../dbconnect/db_connect.php');
	//var_dump($_REQUEST); //exit;
	
	$tableRow = '
				<tr>
					<td></td>
					<td class="vat">All Entities</td>
					<td class="vat">All Branches</td>
					<td class="vat">All Divisions</td>
					<td class="vat">All Departments</td>
					<td class="vat">All Teams</td>
					<td></td>
				</tr>';
	
	$result['entity'] = '1,2,3,4,5,6,7,8,910,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30';
	$result['branch'] = '1,2,3,4,5,6,7,8,910,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30';
	$result['division'] = '1,2,3,4,5,6,7,8,910,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30';
	$result['department'] = '1,2,3,4,5,6,7,8,910,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30';
	$result['team'] = '1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31,32,33,34,35,36,37,38,39,40,41,42,43,44,45,46,47,48,49,50';
	$result['tableRow'] = $tableRow;
	
	//var_dump($result); exit;
	
	echo json_encode($result);
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	/*var_dump($entity);
	var_dump($branch);
	var_dump($division);
	var_dump($department);
	var_dump($team);*/
	


