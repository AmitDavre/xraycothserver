<?php
	
	function dateHours($number){
		if(!empty($number)){
			$number = number_format($number,20);
			$tmp = explode(".", $number);
			$deci = '0.'.$tmp[1];
			$deci = $deci*60;
			$deci = number_format(((float)$deci),2);
			return ($tmp[0].':'.sprintf("%02d",$deci));
		}else{
			return '-';
		}
	}
	//var_dump('xxx : '.dateHours('7.0833333333333')); exit;
	
	
	
	
	$f1 = strtotime('14:00');
	$u2 = strtotime('22:00');
	//var_dump('f1 : '.$f1);
	//var_dump('u2 : '.$u2);

	$break = 1800; // 1 hour
	
	$scan1 = strtotime('14:00');
	$scan2 = strtotime('21:35');
	//var_dump('scan1 : '.$scan1);
	//var_dump('scan2 : '.$scan2);
	
	$late = 0;
	$early = 0;
	if($scan1 > $f1){$late = ($scan1-$f1)/60;}
	if($scan2 < $u2){$early = ($u2-$scan2)/60;}
	//var_dump('late : '.$late.' min');
	//var_dump('early : '.$early.' min');
	
	$before = 0;
	$after = 0;
	
	$plan = ($u2-$f1-$break)/60/60;
	$actual = 0;
	$paid = 0;
	
	$actual += ((float)($scan2-$scan1-$break)/60/60);
	var_dump($actual);
	var_dump(number_format($actual,20));
	//var_dump('plan hrs : '.dateHours($plan));
	var_dump('actual hrs : '.dateHours($actual));
	//var_dump('actual Dhrs : '.$actual);
	
	if($late == 0 && $early == 0){
		$paid += $plan;
		$before = ($f1-$scan1)/60/60;
		$after = ($scan2-$u2)/60/60;
	}else{
		if($late == 0){
			$t1 = $f1;
			$before = ($f1-$scan1)/60/60;
		}else{
			$t1 = $scan1;
		}
		if($early == 0){
			$t2 = $u2;
			$after = ($scan2-$u2)/60/60;
		}else{
			$t2 = $scan2;
		}
		$paid += ($t2-$t1-$break)/60/60;
	}
	//var_dump('paid hrs : '.dateHours($paid));
	//var_dump('paid Dhrs : '.$paid);
	//var_dump('before : '.dateHours($before));
	//var_dump('after : '.dateHours($after));
	

















































				
				
