<?

	if(session_id()==''){session_start(); ob_start();}
	//$cid = $_SESSION['rego']['cid'];
	include("../../dbconnect/db_connect.php");
	include(DIR.'files/functions.php');
	include(DIR.'leave/functions.php');
	include(DIR.'files/arrays_'.$_SESSION['rego']['lang'].'.php');
	
	//$_REQUEST['emp_id'] = 'DEMO-001';
	//var_dump($_REQUEST);

	updateLeaveDatabase($cid);
	
	$leave_settings = getLeaveTimeSettings();
	if($leave_settings['workingdays'] == 5){$weekdays = array(6,7);}
	if($leave_settings['workingdays'] == 6){$weekdays = array(6);}
	if($leave_settings['workingdays'] == 7){$weekdays = array();}

	//var_dump(countWorkingdaysMonth($_SESSION['rego']['cur_year'], 3, $weekdays)); // 23

	$leave_types = getSelLeaveTypes($cid);
	$emp_info = getEmployeeInfo($cid, $_REQUEST['emp_id']);
	//var_dump($leave_settings);
	
	foreach($leave_types as $k=>$v){
		$leave_types[$k]['pending'] = 0;
		$leave_types[$k]['used'] = 0;
		$balance[$k] = array('th'=>$v['th'], 'en'=>$v['en'], 'maxdays'=>$v['max'], 'pending'=>0, 'used'=>0);
	}
	$ALemp = getALemployee($cid, $_REQUEST['emp_id']);
	$balance['AL']['maxdays'] = $ALemp;
	$balance = getUsedLeaveEmployee($cid, $_REQUEST['emp_id'], $balance);
	//var_dump($balance); exit;
	//var_dump($leave_types); exit;
	
	$yr = $_SESSION['rego']['cur_year'];
	if($yr == date('Y')){
		$mth = date('n');
	}else{
		$mth = 12;
	}
	$holidays = getHoliDates($yr);

	foreach($months as $mo=>$m){
		$wd = '-';
		if($mo < $mth){
			$wd = getWorkingDays(date($yr.'-'.$mo.'-01'), date('Y-'.$mo.'-t'), $holidays, $weekdays);
			//$wd = countWorkingdaysMonth($_SESSION['rego']['cur_year'], $mo, $weekdays);
		}else if($mo == $mth){
			$wd = getWorkingDays(date($yr.'-'.$mth.'-01'), date($yr.'-'.$mth.'-d'), $holidays, $weekdays);
		}
		$summary[$mo] = array(
			'month'=>$m, 
			'm'=>$mo, 
			'workdays'=>$wd, 
			'attendance'=>0, 
			'att_tot'=>0, 
			'total'=>0);
			foreach($leave_types as $k=>$v){
				$summary[$mo][$k] = 0;
			}
	}
	//var_dump($summary[3]); //exit;	
	
	$sum_totals = array(
		'month'=>'Totals', 
		'workdays'=>0, 
		'attendance'=>0, 
		'att_tot'=>0, 
		'total'=>0);
		foreach($leave_types as $k=>$v){
			$sum_totals[$k] = 0;
		}
		//var_dump($sum_totals); exit;	

	$sum = array();	
	$res = $dbc->query("SELECT * FROM ".$cid."_leaves_data WHERE emp_id = '".$_REQUEST['emp_id']."' AND status = 'TA'"); 
	while($row = $res->fetch_assoc()){
		//$sum[][date('n',strtotime($row['date']))][$row['leave_type']] = $row['days'];
		$summary[date('n',strtotime($row['date']))][$row['leave_type']] += $row['days'];
		if($leave_types[$row['leave_type']]['attendance'] == 1){
			$summary[date('n',strtotime($row['date']))]['att_tot'] += $row['days'];
			$summary[date('n',strtotime($row['date']))]['total'] += $row['days'];
		}
	}
	//var_dump($summary); //exit;	
	
	foreach($summary as $k=>$v){
		if($v['workdays'] > 0 ){
			$att = 100 - ($v['att_tot'] / $v['workdays'] * 100);
			$summary[$k]['attendance'] = round($att,2);
			//var_dump($att);
			//$graph_totals['attendance'][] = number_format($att,2);
		}else{
			$att = 0;
			//$graph_totals['attendance'][] = 100;
		}
		//$graph_totals['planned'][] = $v['planned'];
		$graph_totals['months'][] = $months[$k];

		$sum_totals['workdays'] += $v['workdays'];
		$sum_totals['attendance'] += $att;
		$sum_totals['total'] += $v['att_tot'];
		//$sum_totals['planned'] += $v['planned'];
		foreach($leave_types as $s=>$ss){
			$sum_totals[$s] += $v[$s];
		}
	}
	//var_dump($sum_totals['attendance']); //exit;	
	//var_dump($summary); exit;	
	//var_dump($sum_totals['attendance']); exit;	

	$sum_totals['attendance'] = $sum_totals['attendance']/$mth;
	
	$table = '<table class="mytable sum table-striped table-bordered" style="width:auto" border="1">
						<thead>
							<tr>
								<th class="tar">'.$lng['Month'].'</th>
								<th class="tac">'.$lng['Days'].'</th>
								<th class="red">'.$lng['Att'].' %</th>
								<th>'.$lng['Total'].'</th>';
								foreach($leave_types as $k=>$v){
									$col = ''; if($v['attendance'] == 1){$col = 'red';}
									$table .= '<th class="tac '.$col.'" style="min-width:45px" data-toggle="tooltip" title="'.$v[$lang].'">'.$k.'</th>';
								}
			$table .=	'</tr>
						</thead>
						<tbody>';
						foreach($summary as $k=>$v){
							$table .= '
								<tr>
								<td>'.$v['month'].'</td>
								<td class="tac">'.$v['workdays'].'</td>
								<td>';
								if($v['attendance'] == 0){
									$table .= '-&nbsp;';
								}else{
									$table .= number_format($v['attendance'],2);
								}
								$table .= '</td>
								<td>';
								if($v['total'] == 0){
									$table .= '-';
								}else{
									$table .= number_format($v['total'],2);
								}
								$table .= '</td>';
								foreach($leave_types as $s=>$t){
									$st = '-'; if((float)$v[$s] > 0){$st = number_format($v[$s],2);}
									$table .= '<td>'.$st.'</td>';
								}
							$table .= '</tr>';
						}
			$table .=	'</tr>
						</tbody>
						<tfoot>';
							$table .= '
								<tr>
								<td>'.$sum_totals['month'].'</td>
								<td class="tac">'.$sum_totals['workdays'].'</td>
								<td>'.number_format($sum_totals['attendance'],2).'</td>
								<td>'.number_format($sum_totals['total'],2).'</td>';
								foreach($leave_types as $s=>$t){
									$st = '-';
									if($sum_totals[$s] > 0){$st = number_format($sum_totals[$s],2);}
									$table .= '<td>'.$st.'</td>';
								}
							$table .= '</tr>';
		$table .=  '</tfoot>
					</table>';
	
	//echo $table; exit;

	if($_REQUEST['res'] == 'table'){	
		ob_clean();	
		echo $table;
		exit;
	}else{
		ob_clean();	
		echo json_encode($graph_totals); 
		exit;
	}











