<?php
		
	ini_set('xdebug.var_display_max_depth', -1);
	ini_set('xdebug.var_display_max_children', -1);
	ini_set('xdebug.var_display_max_data', -1);

	$net = array();
	
	$val = 150000;
	$add = 1052.63;
	for($i=151000; $i<293000; $i+=1000){
		$val += $add;
		//var_dump($val);
		$net[$i] = round($val);
	}
	
	$val = 300555.56;
	$net[293000] = round($val);
	$add = 1111.11;
	for($i=294000; $i<473000; $i+=1000){
		$val += $add;
		//var_dump($val);
		$net[$i] = round($val);
	}
	
	$val = 500588.24;
	$net[473000] = round($val);
	$add = 1176.47;
	for($i=474000; $i<686000; $i+=1000){
		$val += $add;
		//var_dump($val);
		$net[$i] = round($val);
	}
	
	$val = 751250;
	$net[686000] = round($val);
	$add = 1250;
	for($i=687000; $i<886000; $i+=1000){
		$val += $add;
		//var_dump($val);
		$net[$i] = round($val);
	}
	
	$val = 1001333.33;
	$net[886000] = round($val);
	$add = 1333.333;
	for($i=887000; $i<1635000; $i+=1000){
		$val += $add;
		//var_dump($val);
		$net[$i] = round($val);
	}
	
	$val = 2000000;
	$net[1635000] = round($val);
	$add = 1428.5715;
	for($i=1635000; $i<3735000; $i+=1000){
		$val += $add;
		//var_dump($val);
		$net[$i] = round($val);
	}
	
	$val = 5000000;
	$net[3735000] = round($val);
	$add = 1537.246;
	for($i=3735000; $i<10000000; $i+=1000){
		$val += $add;
		//var_dump($val);
		$net[$i] = round($val);
	}
	
	var_dump($net); exit;
