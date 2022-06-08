<?
	if(session_id()==''){session_start(); ob_start();}
	//$cid = $_SESSION['xhr']['cid'];
	include("../../dbconnect/db_connect.php");
	include(DIR.'files/arrays_'.$_SESSION['xhr']['lang'].'.php');
	//$_REQUEST['emp_id'] = '10003';
	$lng = getLangVariables($_SESSION['xhr']['lang']);
	
	$emp_leave = getEntitledLeaveEmployee($cid, $_REQUEST['emp_id']);
	//var_dump($emp_leave); //exit;
	
	
	$sel_types = array();
	foreach($leave_types as $k=>$v){
		if($v['activ'] == 1){
			$sel_types[$k] = $v;
		}
	}
	//var_dump($sel_types);
	$emp_types = array();
	foreach($sel_types as $k=>$v){
		if($v['emp_request'] == 1){
			$emp_types[$k] = $v;
		}
	}
	//var_dump($emp_types);
	
	
	foreach($emp_types as $k=>$v){
		$balance[$k] = array('th'=>$v['th'], 'en'=>$v['en'], 'maxdays'=>$emp_leave[$k]['max'], 'maxpaid'=>$emp_leave[$k]['pay'], 'pending'=>0, 'used'=>0);
	}
	//var_dump($balance); exit;
	
	$balance = getUsedLeaveEmployee($cid, $_REQUEST['emp_id'], $balance);
	//var_dump($balance); exit;
	
	//ob_clean();
	//var_dump($_REQUEST['id']); exit;
	//$_REQUEST['id'] = '10093_LW';
	
	//var_dump($balance); 
		
		
	$table = '	
				<table class="basicTable" border="0">
					<thead>
						<th>'.$lng['Code'].'</th>
						<th style="width:70%">'.$lng['Leave type'].'</th>
						<th class="tac">'.$lng['Entitled'].'</th>
						<th class="tac">'.$lng['Taken'].'</th>
						<th class="tac">'.$lng['Pending'].'</th>
						<th class="tac">'.$lng['Balance'].'</th>
					</thead>
					<tbody>';
					
					foreach($balance as $k=>$v){
					
					$bal = $v['maxdays'] - $v['used'] - $v['pending'];
					$table .= '<tr>
							<td>'.$k.'</td>
							<td>'.$v[$_SESSION['xhr']['lang']].'</td>
							<td class="tac">'.round($v['maxdays'],1).'</td>
							<td class="tac ';
							if($v['used'] != '0'){$table .= 'strong';}
							$table .= '">'.round($v['used'],1).'</td>
							<td class="tac ';
							if($v['pending'] != '0'){$table .= 'strong';}
							$table .= '">'.round($v['pending'],1).'</td>
							<td class="tac"><b style="';
							if($bal<1){$table .= 'color:#c00';}else{$table .= 'color:#393';}
					$table .= '">'.round($bal,1).'</b></td>
						</tr>';
					}
				
	$table .= '</tbody></table>';
		
		
	ob_clean();	
	echo $table;	
		
	exit;
		
	ob_clean();
	echo json_encode($days); exit;
	
?>
