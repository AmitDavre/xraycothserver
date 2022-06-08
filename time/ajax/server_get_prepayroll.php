<?
	if(session_id()==''){session_start();}
	ob_start();
	include('../../dbconnect/db_connect.php');
	include(DIR.'files/functions.php');
	include(DIR.'leave/functions.php');
	include(DIR.'time/functions.php');
	
	$var_allow = getUsedVarAllow($lang);
	$compensations = getCompensations();
	foreach($var_allow as $k=>$v){
		$allow_cols[$k] = 0;
	}
	
	$sql = "SELECT * FROM ".$cid."_prepayroll WHERE month = '".$cur_month."' AND status = 0 ORDER BY emp_id ASC";
	if($res = $dbc->query($sql)){
		while($row = $res->fetch_assoc()){
			for($i=1;$i<=10;$i++){
				if($row['var_allow_'.$i] > 0){$allow_cols[$i] = 1;}
			}
		}
	}
	
	//var_dump($allow_cols); //exit;
	
	$sql_details = array(
		'user' => $my_username,
		'pass' => $my_password,
		'db'   => $my_dbcname,
		'host' => $my_database
	);
	
	$table = $cid."_prepayroll";
	$primaryKey = 'id';
	
	$where = "";
	/*if($_REQUEST['valid'] != ''){
		$where .= "status = '".$_REQUEST['valid']."'";
	}
	if(!empty($_REQUEST['period'])){
		if($where != ''){$where .= ' AND ';}
		$where .= "period = '".$_REQUEST['period']."'";
	}*/

	$nr=0;
	
	//$columns[] = array( 'db' => 'date', 'dt' => $nr, 'formatter' => function($d, $row ){return date('d-m-Y', strtotime($d));}); $nr++;
	
	$columns[] = array( 'db' => 'emp_id', 'dt' => $nr); $nr++;
	
	$columns[] = array( 'db' => $lang.'_name', 'dt' => $nr); $nr++;
	
	$columns[] = array( 'db' => 'planned_days', 'dt' => $nr); $nr++;
	
	$columns[] = array( 'db' => 'normal_days', 'dt' => $nr); $nr++;
	
	$columns[] = array( 'db' => 'paid_days', 'dt' => $nr); $nr++;
	
	$columns[] = array( 'db' => 'ot1h', 'dt' => $nr, 'formatter' => function($d, $row ){
		return '<input type="text" name="<?=$val['id']?>[ot1h]" value="'.round($val['ot1h'],2).'">';
	); $nr++;
	
	$columns[] = array( 'db' => 'ot15h', 'dt' => $nr); $nr++;
	
	$columns[] = array( 'db' => 'ot2h', 'dt' => $nr); $nr++;
	
	$columns[] = array( 'db' => 'ot3h', 'dt' => $nr); $nr++;
	
	$columns[] = array( 'db' => 'absence', 'dt' => $nr); $nr++;
	
	$columns[] = array( 'db' => 'late_early_unpaid', 'dt' => $nr); $nr++;
	
	$columns[] = array( 'db' => 'personal', 'dt' => $nr); $nr++; // paid leave
	
	$columns[] = array( 'db' => 'public', 'dt' => $nr); $nr++; // paid holidays
	
	foreach($compensations as $k=>$v){
		$columns[] = array( 'db' => 'comp'.$k, 'dt' => $nr); $nr++;
	}
	foreach($var_allow as $k=>$v){
		if($allow_cols[$k] == 1){
			$columns[] = array( 'db' => 'var_allow_'.$k, 'dt' => $nr); $nr++;
		}
	}
	
	
	/*$columns[] = array( 'db' => 'import', 'dt' => $nr, 'formatter' => function($d, $row ){
		if($d == 0){
			return '<span style="color:#a00; font-weight:normal"><i class="fa fa-minus fa-lg"></i></span>';
		}else{
			return '<span style="color:green; font-weight:normal"><i class="fa fa-check fa-lg"></i></span>';
		}
	}); $nr++;
	
	$columns[] = array( 'db' => 'status', 'dt' => $nr, 'formatter' => function($d, $row ){
		if($d == 0){
			return '<span style="color:#a00; font-weight:normal"><i class="fa fa-minus fa-lg"></i></span>';
		}else{
			return '<span style="color:green; font-weight:normal"><i class="fa fa-check fa-lg"></i></span>';
		}
	}); $nr++;*/
	
	/*$columns[] = array( 'db' => 'id', 'dt' => $nr, 'formatter' => function($d, $row ){
		return '<a data-id="'.$d.'" class="viewContent"><i class="fa fa-eye fa-lg"></i></a>';
	}); $nr++;
	
	$columns[] = array( 'db' => 'id', 'dt' => $nr, 'formatter' => function($d, $row ){return '';}); $nr++;
	
	$columns[] = array( 'db' => 'filename', 'dt' => $nr, 'formatter' => function($d, $row )use($dir){
		return '<a download href="'.$dir.$d.'" class="downloadFile"><i class="fa fa-download fa-lg"></i></a>';
	}); $nr++;

	$columns[] = array( 'db' => 'id', 'dt' => $nr, 'formatter' => function($d, $row )use($dir){
		return '<a data-id="'.$d.'" class="deleteFile"><i class="fa fa-trash fa-lg"></i></a>';
	}); $nr++;*/

	require(DIR.'ajax/ssp.class.php' );
	
	//var_dump(SSP::simple( $_POST, $sql_details, $table, $primaryKey, $columns, $where)); exit;
	
	ob_clean();
	echo json_encode(
		SSP::simple( $_POST, $sql_details, $table, $primaryKey, $columns, $where)
	);

?>