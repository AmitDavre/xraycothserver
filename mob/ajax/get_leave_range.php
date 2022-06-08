<?
	if(session_id()==''){session_start();}
	ob_start();
	include('../../dbconnect/db_connect.php');
	include(DIR.'files/functions.php');
	include(DIR.'files/arrays_'.$lang.'.php');
	
	$_REQUEST['startDate'] = substr($_REQUEST['startDate'], -10);
	$_REQUEST['endDate'] = substr($_REQUEST['endDate'], -10);
	$range = dateRange($_REQUEST['startDate'], $_REQUEST['endDate'], $step = '+1 day', $output_format = 'd-m-Y');
	
	$table = '<table class="table-bordered text-center" style="background:#fff; table-layout:fixed; margin:0; width:100%"><tbody>';
	foreach($range as $k=>$v){
		$d = date('N', strtotime($v));
		$table .= '
			<tr>
				<td class="tac">
					'.$xsdays[$d].date(' d-m-Y', strtotime($v)).'
					<input name="date['.$k.']" type="hidden" value="'.date('Y-m-d', strtotime($v)).'" />
				</td>
				<td class="pad1">
					<button data-id="'.$k.'" class="dayType btn btn-info btn-block" type="button"><span class="day'.$k.'">'.$lng['Full day'].'</span></button>
					<input name="day['.$k.']" id="mday'.$k.'" type="hidden" value="full" />
				</td>
			</tr>';
	}	
	$table .= '</tbody></table>';
	
	ob_clean();
	echo $table; exit;
