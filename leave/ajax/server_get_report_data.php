<?
	if(session_id()==''){session_start(); ob_start();}
	//$cid = $_SESSION['xhr']['cid'];
	include('../../dbconnect/db_connect.php');
	include(DIR.'files/functions.php');
	
	//$leave_status = array('RQ'=>'Pending','CA'=>'Cancelled','AP'=>'Approved','RJ'=>'Rejected');
	$leave_settings = getLeaveTimeSettings();
	$leave_types = unserialize($leave_settings['leave_types']);
	//var_dump($groups); exit;
	//$groups[3] = 'Group 3';
	
	$groups[''] = '';
	$departments[''] = '';
	//$positions[''] = '';
	//var_dump($groups); exit; 
	
	$sel_types = array();
	foreach($leave_types as $k=>$v){
		if($v['activ'] == 1){
			$sel_types[$k] = $v;
		}
	}
	//var_dump($sel_types['AL']);
	$mdays = '';//getMonthlyDays();
	$tot_days = 20;//array_sum($mdays);
	//var_dump($mdays); 
	
	if($res = $dbc->query("SELECT * FROM ".$cid."_employees ORDER BY emp_id ASC")){
		while($row = $res->fetch_assoc()){
			$emps[$row['emp_id']]['name'] = $row[$lang.'_name'];
			$emps[$row['emp_id']]['entity'] = $row['entity'];
			$emps[$row['emp_id']]['branch'] = $row['branch'];
			$emps[$row['emp_id']]['division'] = $row['division'];
			$emps[$row['emp_id']]['department'] = $row['department'];
			$emps[$row['emp_id']]['team'] = $row['team'];
			$emps[$row['emp_id']]['emp_group'] = $row['emp_group'];
		}
	}
	//var_dump($emps); exit;
	if(!$emps){
		ob_clean();
		echo json_encode(array());
		exit;
	}
	
	foreach($emps as $key=>$val){
		$data[$key]['emp_id'] = $key;
		$data[$key]['name'] = $val['name'];
		$data[$key]['entity'] = $val['entity'];
		$data[$key]['branch'] = $val['branch'];
		$data[$key]['division'] = $val['division'];
		$data[$key]['department'] = $val['department'];
		$data[$key]['team'] = $val['team'];
		$data[$key]['emp_group'] = $val['emp_group'];
		$data[$key]['attendance'] = 0;
		$data[$key]['att_tot'] = 0;
		$data[$key]['planned'] = 0;
		$data[$key]['unplanned'] = 0;
		$data[$key]['unpl_per'] = 0;
		foreach($sel_types as $k=>$v){
			$data[$key][$k] = 0;
		}
	}
	//var_dump($data); exit;
	
	$where = '';
	if(!empty($_REQUEST['from']) && !empty($_REQUEST['until'])){
		$where = " WHERE date between '".date('Y-m-d',strtotime($_REQUEST['from']))."' AND '".date('Y-m-d',strtotime($_REQUEST['until']))."'";
	}
	//$ddata = array();
	$dbname = $cid.'_leaves_data';
	$res = $dbc->query("SELECT * FROM $dbname $where ORDER BY date ASC");
		while($row = $res->fetch_assoc()){
			//$ddata[] = $row;
			if($sel_types[$row['leave_type']]['attendance'] == 1){
				$data[$row['emp_id']]['att_tot'] += (float)$row['days'];
			}
			if($row['planned'] == 1){
				$data[$row['emp_id']]['planned'] += (float)$row['days'];
			}else{
				$data[$row['emp_id']]['unplanned'] += (float)$row['days'];
			}
			$data[$row['emp_id']][$row['leave_type']] += (float)$row['days'];
		}
	
	foreach($data as $k=>$v){
		$att = 100 - (($v['att_tot'] / $tot_days) * 100);
		$data[$k]['attendance'] = number_format($att,2);
		$p = $v['planned'];
		$u = $v['unplanned'];
		$t = $p + $u;
		if($u>0){
			$per = (float)($u/$t)*100;
			$data[$k]['unpl_per'] = number_format($per,2);
		}
		unset($data[$k]['att_tot']);
	}
	//var_dump($data); exit;
	
	if(!$data){
		ob_clean();
		echo json_encode(array());
		exit;
	}
	
	$dbname = $cid.'_temp_leave_data';
	$res = $dbc->query("SELECT * FROM $dbname");    
	$table_fields = mysqli_num_fields($res);
	$leave_count = count($sel_types) + 9;
	var_dump($leave_count);
	var_dump($table_fields);
	
	if($leave_count != $table_fields){
		$dbc->query("DROP TABLE IF EXISTS `$dbname`");
	}

	if(!$dbc->query("DESCRIBE `$dbname`")) {
		$sql = "CREATE TABLE IF NOT EXISTS `$dbname` (
			  `emp_id` varchar(10) COLLATE utf8_bin NOT NULL,
			  `name` varchar(50) COLLATE utf8_bin NOT NULL,
			  `entity` int(10) NOT NULL,
			  `branch` int(10) NOT NULL,
			  `division` int(10) NOT NULL,
			  `department` int(10) NOT NULL,
			  `team` int(10) NOT NULL,
			  `emp_group` varchar(10) COLLATE utf8_bin NOT NULL,
			  `attendance` FLOAT COLLATE utf8_bin NOT NULL,
			  `planned` FLOAT COLLATE utf8_bin NOT NULL,
			  `unplanned` FLOAT COLLATE utf8_bin NOT NULL,
			  `unpl_per` FLOAT COLLATE utf8_bin NOT NULL,";
				foreach($sel_types as $k=>$v){
					$sql .= "`".$k."` FLOAT COLLATE utf8_bin NOT NULL,";
				}
		$sql .= " PRIMARY KEY (`emp_id`),
					UNIQUE KEY `emp_id` (`emp_id`)
					) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin" ;
		
		if(!$dbc->query($sql)){
			echo 'Error 2 : '.mysqli_error($dbc);
		}else{
			echo 'Created successfuly';
		}
	}
	//var_dump($data); exit;

	$dbc->query("TRUNCATE TABLE $dbname");
				
	$sql = "INSERT INTO $dbname (";
	foreach($data[key($data)] as $k=>$v){
		$field[] = $k;
	}
	//var_dump($field); exit;
	foreach($field as $v){
		$sql .= $v.',';
	}
	$sql = substr($sql,0,-1);
	$sql .= ") VALUES ("; 
	foreach($data as $key=>$val){
		foreach($val as $v){
			$sql .= "'".$dbc->real_escape_string($v)."',";
		}
		$sql = substr($sql,0,-1);
		$sql .='),(';
	}
	$sql = substr($sql,0,-2);
	//echo $sql; exit;
	
	if(!$dbc->query($sql)){
		echo 'Error 3: '.mysqli_error($dbc);
	}else{
		echo 'Success';
	}
	//exit;
	// DB table to use
	$table = $dbname;
	
	// Table's primary key
	$primaryKey = 'emp_id';
	
	$sql_details = array(
		'user' => $my_username,
		'pass' => $my_password,
		'db'   => $my_dbcname,
		'host' => $my_database
	);
	
	$where = '';
	if(isset($_GET['where'])){$where = $_GET['where'];}
	
	$nr=0;
	
	$columns[] = array( 'db' => 'emp_id',    'dt' => $nr ); $nr++;
	
	$columns[] = array( 'db' => 'name', 'dt' => $nr ); $nr++;
	
	/*$columns[] = array( 'db' => 'entity',    'dt' => $nr, 'formatter' => function($d, $row )use($entities){return '<span style="display:none">'.$d.'</span>'.$entities[$d]['en'];}); $nr++;
	
	$columns[] = array( 'db' => 'branch',    'dt' => $nr, 'formatter' => function($d, $row )use($branches){return '<span style="display:none">'.$d.'</span>'.$branches[$d]['en'];}); $nr++;
	
	$columns[] = array( 'db' => 'division',    'dt' => $nr, 'formatter' => function($d, $row )use($divisions){return '<span style="display:none">'.$d.'</span>'.$divisions[$d]['en'];}); $nr++;
	
	$columns[] = array( 'db' => 'department',    'dt' => $nr, 'formatter' => function($d, $row )use($departments){return '<span style="display:none">'.$d.'</span>'.$departments[$d]['en'];}); $nr++;
	
	$columns[] = array( 'db' => 'team',    'dt' => $nr, 'formatter' => function($d, $row )use($teams){return '<span style="display:none">'.$d.'</span>'.$teams[$d]['en'];}); $nr++;
	
	$columns[] = array( 'db' => 'emp_group',    'dt' => $nr, 'formatter' => function($d, $row ){return $d;}); $nr++;*/
	
	$columns[] = array( 'db' => 'attendance',    'dt' => $nr, 'formatter' => function($d, $row ){
		if($d < 98){return '<span style="color:#c00">'.$d.'</span>';}else{return number_format($d,2);}
	}); $nr++;
	
	$columns[] = array( 'db' => 'planned',    'dt' => $nr, 'formatter' => function($d, $row ){return $d;}); $nr++;
	
	$columns[] = array( 'db' => 'unplanned',    'dt' => $nr, 'formatter' => function($d, $row) {return $d;}); $nr++;
	
	$columns[] = array( 'db' => 'unpl_per',    'dt' => $nr, 'formatter' => function($d, $row ){return $d;}); $nr++;
	
	foreach($sel_types as $k=>$v){
		$columns[] = array( 'db' => $k, 'dt' => $nr, 'formatter' => function($d, $row ){return round($d,1);}); $nr++;
	}

// SQL server connection information
//require('config.php');

require('../../ajax/ssp.class.php' );

$joinQuery = "FROM $table";
$where = $where;        
//var_dump(SSP::simple( $_GET, $sql_details, $table, $primaryKey, $columns, $where ));exit;
ob_clean();
echo json_encode(
	SSP::simple( $_GET, $sql_details, $table, $primaryKey, $columns, $where)
);

?>