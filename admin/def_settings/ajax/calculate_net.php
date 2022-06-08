<?
	
	if(session_id()==''){session_start();}
	ob_start();
	include("../../dbconnect/db_connect.php");
	
	$rules['from'] = $_REQUEST['from'];
	$rules['to'] = $_REQUEST['to'];
	$rules['percent'] = $_REQUEST['percent'];
	
	foreach($rules as $key=>$val){
		foreach($val as $k=>$v){
			if($k==0){
				$xrules['net_from'][$k] = 0;
				$xrules['net_to'][$k] = (int)$rules['to'][$k];
			}else{
				$sum = (int)$rules['to'][$k] - (int)$rules['to'][$k-1];
				$per = (100-(int)$rules['percent'][$k]);
				$xrules['net_to'][$k] = round((($sum) * ($per/100)) + $xrules['net_to'][$k-1]);
				$xrules['net_from'][$k] = $xrules['net_to'][$k-1]+1;
				if($k==7){$xrules['net_to'][$k] = 0;}
			}
		}
	}
	echo json_encode($xrules);
	//var_dump($xrules); exit;
	
