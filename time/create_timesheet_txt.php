<?php

	function date_range($first, $last, $step = '+1 day', $output_format = 'd-m-Y') {
		 $dates = array();
		 $current = strtotime($first);
		 $last = strtotime($last);
		 while( $current <= $last ) {
			  if(date('N', $current) < 6){
			  		$dates[$current] = date($output_format, $current);
			  }
			  $current = strtotime($step, $current);
		 }
		 return $dates;
	}
	$sdate = '30-04-2020';
	$edate = $sdate;//'04-05-2020';
	$dates = date_range($sdate, $edate, $step = '+1 day', $output_format = 'Y/m/d');
	//var_dump($dates);
	
	$nr = 2;
	foreach($dates as $key=>$val){
		for($i=1;$i<=10;$i++){
			$arr[] = array($nr,1,$i,'',0,1,$val.' 07:'.rand(48,59).':00');
			$nr ++;
		}
	}
	foreach($dates as $key=>$val){
		for($i=1;$i<=10;$i++){
			$arr[] = array($nr,1,$i,'',0,1,$val.' 12:0'.rand(1,9).':00');
			$nr ++;
		}
	}
	foreach($dates as $key=>$val){
		for($i=1;$i<=10;$i++){
			$arr[] = array($nr,1,$i,'',0,1,$val.' 12:'.rand(55,59).':00');
			$nr ++;
		}
	}
	foreach($dates as $key=>$val){
		for($i=1;$i<=10;$i++){
			$arr[] = array($nr,1,$i,'',0,1,$val.' 17:0'.rand(1,9).':00');
			$nr ++;
		}
	}
	//var_dump($arr); exit;
	
	
	
	
	$txt = "";
	$txt .= "No\tTMNo\tEnNo\tName\tINOUT\tMode\tDateTime\n";
	foreach($arr as $key=>$val){
		foreach($val as $k=>$v){
			$txt .= $v."\t";
		}
		$txt .= "\n";
	}
	//echo trim($txt);
	//exit;
	$filename = 'timesheet_'.$sdate.'.txt';
	header('Content-type: text/plain');
	header("Content-Disposition: attachment; filename=".$filename);
	
	ob_clean();
	echo trim($txt);
	file_put_contents($filename, $txt);

	exit;
