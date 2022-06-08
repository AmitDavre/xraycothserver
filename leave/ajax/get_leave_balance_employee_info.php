<?
	if(session_id()==''){session_start(); ob_start();}
	include("../../dbconnect/db_connect.php");
	include(DIR."files/functions.php");
	include(DIR."leave/functions.php");
	//$_REQUEST['emp_id'] = 'DEMO-001';


	$leave_time_settings = getLeaveTimeSettings();
	$leave_types = unserialize($leave_time_settings['leave_types']);





	foreach($leave_types as $k=>$v){

		// if(($v['activ'] == 1) || ($v['emp_request'] == 1)  || ($v['emp_request'] == 0 && $v['bab_request'] == 'after'))
		// if($v['activ'] == 1)
		// {
			$balance[$k] = array('activ' => $v['activ'],'th'=>$v['th'], 'en'=>$v['en'], 'maxdays'=>$v['max'][$_SESSION['rego']['emp_group']], 'maxpaid'=>$v['pay'][$_SESSION['rego']['emp_group']], 'pending'=>0, 'used'=>0);

		// }

	}
	$ALemp = getALemployee($cid, $_REQUEST['emp_id']);
	$balance['AL']['maxdays'] = $ALemp;
	$balance = getUsedLeaveEmployee($cid, $_REQUEST['emp_id'], $balance);
	//var_dump($ALemp); exit;	






	$maxdaysAL= $balance['AL']['maxdays'];
	$maxpaidAL= $balance['AL']['maxpaid'];
	$pendingAL= $balance['AL']['pending'];
	$usedAL	  = $balance['AL']['used'];

	$maxdaysAU= $balance['AU']['maxdays'];
	$maxpaidAU= $balance['AU']['maxpaid'];
	$pendingAU= $balance['AU']['pending'];
	$usedAU	  = $balance['AU']['used'];




	$balance['AU']['maxdays']=  $maxdaysAL;
	$balance['AU']['maxpaid']= $maxpaidAL;
	$balance['AU']['pending']= $pendingAU + $pendingAL;
	$balance['AU']['used']  =  $usedAU + $usedAL;	
	$balance['AL']['maxdays']= $maxdaysAL;
	$balance['AL']['maxpaid']= $maxpaidAL;
	$balance['AL']['pending']= $pendingAU + $pendingAL;
	$balance['AL']['used']  =  $usedAU + $usedAL;	





	$maxdaysSL= $balance['SL']['maxdays'];
	$maxpaidSL= $balance['SL']['maxpaid'];
	$pendingSL= $balance['SL']['pending'];
	$usedSL	  = $balance['SL']['used'];

	$maxdaysSN= $balance['SN']['maxdays'];
	$maxpaidSN= $balance['SN']['maxpaid'];
	$pendingSN= $balance['SN']['pending'];
	$usedSN	  = $balance['SN']['used'];	

	$totSickpending		= $pendingSL + $pendingSN;
	$totSickused	 	= $usedSL    + $usedSN;


	$balance['SN']['maxdays']= $maxdaysSL;
	$balance['SN']['maxpaid']= $maxpaidSL;
	$balance['SN']['pending']= $totSickpending;
	$balance['SN']['used']  =  $totSickused;

	$balance['SL']['maxdays']= $maxdaysSL;
	$balance['SL']['maxpaid']= $maxpaidSL;
	$balance['SL']['pending']= $totSickpending;
	$balance['SL']['used']  =  $totSickused;


	// UNSET AU AND SN AND COMBINE TEXT IN ONE LINE 

	// GET AU AND SN TEXTS AND ADD THEM TO AL AND SL RESPECTIVELY

	$auEngText = $balance['AU']['en']; // AU
	$auThaiText = $balance['AU']['th']; // AU

	$alEngText = $balance['AL']['en']; // AL
	$alThaiText = $balance['AL']['th']; // AL

	$snEngText = $balance['SN']['en']; // SN
	$snThaiText = $balance['SN']['th']; // SN

	$slEngText = $balance['SL']['en']; // SL
	$slThaiText = $balance['SL']['th']; // SL

	$balance['AL']['en'] = $alEngText .' + '.$auEngText;
	$balance['AL']['th'] = $alThaiText .' + '.$auThaiText;

	$balance['SL']['en'] = $slEngText .' + '.$snEngText;
	$balance['SL']['th'] = $slThaiText .' + '.$snThaiText;

	unset($balance['AU']);
	unset($balance['SN']);



	foreach ($balance as $key_100 => $value_100) 
	{
		if($value_100['activ'] == '1')
		{
			$balances[$key_100] =$value_100;
		}		
		
	}









			foreach($balances as $k=>$v){

				if($k == 'AL')
				{
					$newkey = 'AL + AU' ;
				}
				else if($k == 'SL')
				{
					$newkey = 'SL + SN' ;
				}
				else
				{
					$newkey = $k;
				}
				$bal = $v['maxdays'] - $v['used'] - $v['pending'];
				$balss = round($v['maxdays'],2) - (number_format($v['used'],2) + number_format($v['pending'],2) );

				$userformat= number_format($v['used'],2) ;

				if($userformat == '0.00')
				{
					$usedFormatVal = '0';
				}
				else
				{
					$str_arr_used = explode('.',$userformat);
					$usedBefore = $str_arr_used[0]; // value before decimal  
					$usedAfter  = $str_arr_used[1]; // value after decimal 

					if($usedAfter)
					{
						if($usedAfter == '00' || $usedAfter == '000')
						{
							$usedFormatVal = $usedBefore; // value without zero 
						}
						else
						{
							$usedFormatVal = $userformat; // value with zero 
						}
					}

				}			

				$pendformat= number_format($v['pending'],2) ;

				if($pendformat == '0.00')
				{
					$pendFormatVal = '0';
				}
				else
				{	

					$str_arr_pen = explode('.',$pendformat);
					$penBefore = $str_arr_pen[0]; // value before decimal  
					$penAfter  = $str_arr_pen[1]; // value after decimal 

					if($penAfter)
					{
						if($penAfter == '00' || $penAfter == '000')
						{
							$pendFormatVal = $penBefore; // value without zero 
						}
						else
						{
							$pendFormatVal = $pendformat; // value with zero 
						}
					}

				}

				// $table .= '<tr>
				// 		<td>'.$v[$_SESSION['rego']['lang']].' ('.$newkey.')</td>
				// 		<td class="tac">'.round($v['maxdays'],1).'</td>
				// 		<td class="tac ';
				// 		if($v['used'] != '0'){$table .= 'strong';}
				// 		$table .= '">'.$usedFormatVal.'</td>
				// 		<td class="tac ';
				// 		if($v['pending'] != '0'){$table .= 'strong';}
				// 		$table .= '">'.$pendFormatVal.'</td>
				// 		<td class="tac"><b style="';
				// 		if($balss<1)
				// 		{
				// 			$table .= 'color:#c00';
				// 		}
				// 		else
				// 		{
				// 			$table .= 'color:#393';
				// 		}
				// $table .= '">'.$balss.'</b></td>
				// 	</tr>';

				if($newkey == 'AL + AU')
				{
					echo $balss;
				}
			}
			
				

	
		
?>
