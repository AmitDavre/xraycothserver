<?
	if(session_id()==''){session_start(); ob_start();}
	//$cid = $_SESSION['xhr']['cid'];
	include('../../dbconnect/db_connect.php');
	include('../../files/functions.php');
	include(DIR.'files/arrays_'.$_SESSION['xhr']['lang'].'.php');

	$lng = getLangVariables($_SESSION['xhr']['lang']);
	//var_dump($leave_types); exit;
	
	// apply pending and taken leave
	/*$data = array();
	$sql = "SELECT id, end FROM ".$cid."_leaves_".$_SESSION['xhr']['cur_year']." WHERE end < CURDATE()";
	if($res = $dbc->query($sql)){
		while($row = $res->fetch_assoc()){
			$data[$row['id']] = $row['end'];
		}
	}
	if($data){ foreach($data as $k=>$v){
		$sql = "UPDATE ".$cid."_leaves_".$_SESSION['xhr']['cur_year']." SET status = 'TA' WHERE id = '".$k."'"; 
		$dbc->query($sql);
	} }*/
	//var_dump($data); exit;
	
	
	$leave_status = array('RQ'=>$lng['Requested'],'CA'=>$lng['Cancelled'],'AP'=>$lng['Approved'],'RJ'=>$lng['Rejected'],'PE'=>$lng['Pending'],'TA'=>'Taken');
	$cert = array('H'=>$lng['Yes'],'A'=>$lng['Yes'],'N'=>$lng['No'],'NA'=>'N/A');
	
	//$leave_types = getLeaveTypes($cid);
	$employees = getEmployees($cid);
	//var_dump($employees); exit;

	$sql_details = array(
		'user' => $my_username,
		'pass' => $my_password,
		'db'   => $my_dbcname,
		'host' => $my_database
	);
	
	$where = "emp_id = '".$_REQUEST['emp_id']."'";        
	if(!empty($_REQUEST['sFilter'])){
		$where .= " AND ".$_REQUEST['sFilter'];
	}

/*
 * DataTables example server-side processing script.
 *
 * Please note that this script is intentionally extremely simply to show how
 * server-side processing can be implemented, and probably shouldn't be used as
 * the basis for a large complex system. It is suitable for simple use cases as
 * for learning.
 *
 * See http://datatables.net/usage/server-side for full details on the server-
 * side processing requirements of DataTables.
 *
 * @license MIT - http://datatables.net/license_mit
 */

/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * Easy set variables
 */
// DB table to use
$table = $cid.'_leaves';//$_SESSION['payroll']['emp_dbase'];//'shr0100_employees';

// Table's primary key
$primaryKey = 'emp_id';

// Array of database columns which should be read and sent back to DataTables.
// The db parameter represents the column name in the database, while the dt
// parameter represents the DataTables column identifier. In this case simple
// indexes


	$nr=0;
	
	$columns[] = array( 'db' => 'type', 'dt' => $nr, 'formatter' => function($d, $row )use($leave_types){
		$tmp = explode('|', $d);
		$types = '';
		foreach($tmp as $s){
			if(empty($s)){
				$types .= 'no Leave<br>';
			}else{
				$types .= $leave_types[$s][$_SESSION['xhr']['lang']].'<br>';
			}
		}
		return $types;
	}); $nr++;
	
	$columns[] = array( 'db' => 'start', 'dt' => $nr, 'formatter' => function($d, $row ){return date('d-m-Y', strtotime($d));}); $nr++;
	$columns[] = array( 'db' => 'end', 'dt' => $nr, 'formatter' => function($d, $row ){return date('d-m-Y', strtotime($d));}); $nr++;
	$columns[] = array( 'db' => 'days', 'dt' => $nr, 'formatter' => function($d, $row ){return '<span style="display:block;text-align:right">'.number_format($d,2).'</span>';}); $nr++;
	$columns[] = array( 'db' => 'status', 'dt' => $nr, 'formatter' => function($d, $row ){global $leave_status; return '<span class="col'.$d.'">'.$leave_status[$d].'</span>';}); $nr++;
	$columns[] = array( 'db' => 'created', 'dt' => $nr); $nr++;
	$columns[] = array( 'db' => 'updated', 'dt' => $nr); $nr++;
	$columns[] = array( 'db' => 'updated_by_name', 'dt' => $nr); $nr++;
	$columns[] = array( 'db' => 'comment', 'dt' => $nr, 'formatter' => function($d, $row ){
		//if(strlen($d)>15){return substr($d,0,15).' ... <i data-toggle="tooltip" title="'.$d.'" class="fa fa-comments-o fa-lg"></i>&nbsp;&nbsp;';}else{return $d;}
		return $d;
	}); $nr++;
	
	//$columns[] = array( 'db' => 'status', 'dt' => $nr ); $nr++;
// SQL server connection information
//require('config.php');

/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * If you just want to use the basic configuration for DataTables with PHP
 * server-side, there is no need to edit below this line.
 */


require('../../ajax/ssp.class.php' );

//$joinQuery = "FROM $table";
//var_dump(SSP::simple( $_GET, $sql_details, $table, $primaryKey, $columns, $joinQuery, $extraWhere ));exit;
ob_clean();
echo json_encode(
	SSP::simple( $_GET, $sql_details, $table, $primaryKey, $columns, $where)
);

?>