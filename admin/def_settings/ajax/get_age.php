<?php
	if(session_id()==''){session_start();}
	ob_start();
	
	$bday = new DateTime($_REQUEST['date']);
	$today = new DateTime('now');
	$diff = $today->diff($bday);
	if($_SESSION['RGadmin']['lang'] == 'en'){
		$age = '';
		if($diff->y > 0){
			if($diff->y == 1){$age .= $diff->y.' year, ';}else{$age .= $diff->y.' years, ';}
		}
		if($diff->m > 0){
			if($diff->m == 1){$age .= $diff->m.' month, ';}else{$age .= $diff->m.' months, ';}
		}
		$age .= $diff->d;
		if($diff->d == 1){$age .= ' day';}else{$age .= ' days';}
	}else{
		$age = $diff->y.' ปี, '.$diff->m;
		$age .= ' เดือน, ';
		$age .= $diff->d;
		$age .= ' วัน';
	}
	echo $age;
?>