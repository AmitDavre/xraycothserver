<?
	
	if(session_id()==''){session_start();}
	ob_start();
	include('../../dbconnect/db_connect.php');
	include(DIR.'files/functions.php');
	include(DIR.'leave/functions.php');
	include(DIR.'files/arrays_'.$lang.'.php');

	$getHoliDates = getAllHoliDates($_SESSION['rego']['cur_year']);

	// echo '<pre>';
	// print_r($getHoliDates);
	// echo '</pre>';
	//exit;

	$_REQUEST['startDate'] = substr($_REQUEST['startDate'], -10);
	$_REQUEST['endDate'] = substr($_REQUEST['endDate'], -10);
	$range = dateRange($_REQUEST['startDate'], $_REQUEST['endDate'], $step = '+1 day', $output_format = 'd-m-Y');
	
	$table = '<table class="detailTable" border="0"><tbody>';

	$countKey = 0;
	foreach($range as $k=>$v){

		$dt1 = strtotime($v);
        $dt2 = date("l", $dt1);
        $dt3 = strtolower($dt2);
	    if(($dt3 == "saturday" ) || ($dt3 == "sunday"))
		{
			//weekend leaves...
		}
		else
		{
			if(in_array($v, $getHoliDates)){
				//public holiday...
			}else{
			
				$d = date('N', strtotime($v));
				$table .= '
					<tr>
						<td class="tar">
							'.$xsdays[$d].date(' d-m-Y', strtotime($v)).'
							<input name="date['.$countKey.']" type="hidden" value="'.date('Y-m-d', strtotime($v)).'" />
						</td>
						<td style="padding:4px 10px !important">
							<button data-id="'.$countKey.'" class="dayType tal btn btn-outline-secondary btn-xxs" type="button"><span class="day'.$countKey.'">'.$lng['Full day'].'</span></button>
							<input name="day['.$countKey.']" id="mday'.$countKey.'" type="hidden" value="full" />
						</td>
						<td style="width:80%">&nbsp;</td>
					</tr>';

				$countKey++;
			}
		}
	}	
	$table .= '</tbody></table>';
	
	ob_clean();
	echo $table; exit;
