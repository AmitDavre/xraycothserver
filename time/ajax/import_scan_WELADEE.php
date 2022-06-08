<?

	if(session_id()==''){session_start();}
	ob_start();
	//$cid = $_SESSION['xhr']['cid'];
	include('../../dbconnect/db_connect.php');
	include(DIR.'files/functions.php');
	include('../functions.php');
	//var_dump($_FILES); exit;

	$period = $_SESSION['rego']['curr_month'].'/'.$_SESSION['rego']['cur_year'];
	$time_settings = getTimeSettings();
	//$fixed_break = $time_settings['fixed_break'];
	$scans = $time_settings['scans'];
	$employees = getEmployeesBySID($cid);
	
	//var_dump($scans); exit;
	
	$dir = DIR.$cid.'/uploads/';
	$filename = '?';
	if(!empty($_FILES)) {
		if(strpos($_FILES['timesheet']['type'], 'ms-excel') == false && strpos($_FILES['timesheet']['type'], 'spreadsheetml') == false){
			ob_clean();
			echo 'wrong';
			exit;
		}
		$tempFile = $_FILES['timesheet']['tmp_name'];
		$filename = $_FILES['timesheet']['name'];
		$inputFileName =  $dir.$_FILES['timesheet']['name'];
		move_uploaded_file($tempFile,$inputFileName);
	}
	//$inputFileName = '../timesheet/weladee/attendance-07-2020.xls';

	$sheetData = array();
	//$inputFileName = $targetFile; 
	
	require_once '../../PhpSpreadsheet/vendor/autoload.php';
	use PhpOffice\PhpSpreadsheet\IOFactory;
	
	$inputFileType = IOFactory::identify($inputFileName);
	$reader = IOFactory::createReader($inputFileType);
	$spreadsheet = $reader->load($inputFileName);
	
	$sheetData = $spreadsheet->getActiveSheet()->toArray('', false, false, false);
	//$objPHPExcel = PHPExcel_IOFactory::load($inputFileName);
	// 1. Value returned in the array entry if a cell doesn't exist
	// 2. Should formulas be calculated ?
	// 3. Should formatting be applied to cell values ?
	// 4. False - Return a simple array of rows and columns indexed by number counting from zero 
	// 4. True - Return rows and columns indexed by their actual row and column IDs

	//var_dump($sheetData[1]);
	unset($sheetData[0]);


	$sql2 = "SELECT filename from  ".$cid."_scanfiles WHERE filename= '".$filename."'";
	$res2 = $dbc->query($sql2);

	if ($res2->num_rows > 0) 
	{
		if($_POST['hidden1Value'] == '1')
		{
			// Delete and insert 

			$deleteSql1 = "DELETE FROM ".$cid."_scanfiles WHERE filename = '".$filename."'";
			$deleteSql2 = "DELETE FROM ".$cid."_scandata WHERE filename = '".$filename."'";
			$deleteSql3 =  "DELETE FROM ".$cid."_metascandata WHERE filename = '".$filename."'";
			$res3 = $dbc->query($deleteSql1);
			$res4 = $dbc->query($deleteSql2);
			$res5 = $dbc->query($deleteSql3);


				// Insert new data 
			$data = array();
			foreach($sheetData as $k=>$v){
				
				$data[$v[6]]['id'] = $employees[$v[6]]['emp_id'];
				$data[$v[6]]['name'] = $employees[$v[6]][$lang.'_name'];
				
				$in = explode(' ', $v[0]);
				$in2 = explode(' ', $v[1]);

				$din = date('d-m-Y', strtotime(str_replace('/','-',$in[0])));
				$din1 = date('Y-m-d', strtotime(str_replace('/','-',$in[0])));
				$din2 = date('Y-m-d', strtotime(str_replace('/','-',$in2[0])));



				$out[1] = '';
				if(!empty($v[1]) && $v[1] != '-')
				{
					$out = explode(' ', $v[1]);
				}

				if(!isset($data[$v[6]]['time'][$din]))
				{
					$data[$v[6]]['time'][$din] = $in[1];
					$data[$v[6]]['time'][$din] .= '|'.$out[1];
				}


				// fetching employee name from sheet
				if($v[5] != '' && $v[5] != 'Name')
				{
					$emp_name = $v[5];
				}



				$scan_in 	= $in[1];
				$scan_out   = $out[1];
				// $datescan	= date('Y-m-d');
		

				

				if (preg_match('~[0-9]+~', $scan_out)) {
					// contains number 
				    $numCheck = '1';
				}
				else
				{
					// no number 
					$numCheck = '0';
				}


				if($scan_in == '' || $scan_out == '' || $numCheck == '0')
				{
					$status = '0';
					$scaninVal = 'No';
					$scanOutVal = 'No';

				}
				else
				{
					$status = '1';
					$scaninVal = $scan_in;
					$scanOutVal = $scan_out;
				}
			
				if($v[6] == 'Total late')
				{
					$s_idVal= '';
				}
				else
				{
					$s_idVal = $v[6];
				}


				// get scan id on the basis of emp id from sheet 

				$sql7 = "SELECT emp_id from  ".$cid."_employees WHERE sid= '".$s_idVal."'";
				$res7 = $dbc->query($sql7);

				if ($res7->num_rows > 0) 
				{
					if($row7 = $res7->fetch_assoc())
					{
						$sql1 = "INSERT INTO ".$cid."_scandata (datescan, emp_name,scan_in, scan_out,filename, status,scan_id,emp_id,datescanout) VALUES ";
						$sql1 .= "('".$dbc->real_escape_string($din1)."', ";
						$sql1 .= "'".$dbc->real_escape_string($emp_name)."', ";
						$sql1 .= "'".$dbc->real_escape_string($scaninVal)."', ";
						$sql1 .= "'".$dbc->real_escape_string($scanOutVal)."', ";
						$sql1 .= "'".$dbc->real_escape_string($filename)."', ";
						$sql1 .= "'".$dbc->real_escape_string($status)."', ";
						$sql1 .= "'".$dbc->real_escape_string($s_idVal)."', ";
						$sql1 .= "'".$dbc->real_escape_string($row7['emp_id'])."', ";
						$sql1 .= "'".$dbc->real_escape_string($din2)."')";

						$res1 = $dbc->query($sql1);

					}
				}
				else
				{
					$sql1 = "INSERT INTO ".$cid."_scandata (datescan, emp_name,scan_in, scan_out,filename, status,emp_id,datescanout) VALUES ";
					$sql1 .= "('".$dbc->real_escape_string($din1)."', ";
					$sql1 .= "'".$dbc->real_escape_string($emp_name)."', ";
					$sql1 .= "'".$dbc->real_escape_string($scaninVal)."', ";
					$sql1 .= "'".$dbc->real_escape_string($scanOutVal)."', ";
					$sql1 .= "'".$dbc->real_escape_string($filename)."', ";
					$sql1 .= "'".$dbc->real_escape_string($status)."', ";
					$sql1 .= "'".$dbc->real_escape_string($emp_idVal)."', ";
					$sql1 .= "'".$dbc->real_escape_string($din2)."')";

					$res1 = $dbc->query($sql1);
				}
			



			}

			// die();


			$in_out = 'Yes';
			$scan_system = $_POST['scan_system'];

			$sql = "INSERT INTO ".$cid."_scanfiles (date, period, content, filename, import, status,in_out,scansystem) VALUES ";
				$sql .= "('".$dbc->real_escape_string(date('Y-m-d'))."', ";
				$sql .= "'".$dbc->real_escape_string($period)."', ";
				$sql .= "'".$dbc->real_escape_string(serialize($data))."', ";
				$sql .= "'".$dbc->real_escape_string($filename)."', ";
				$sql .= "'".$dbc->real_escape_string(1)."', ";
				$sql .= "'".$dbc->real_escape_string(0)."', ";
				$sql .= "'".$dbc->real_escape_string($in_out)."', ";
				$sql .= "'".$dbc->real_escape_string($scan_system)."')";
			
			ob_clean();
			if($res = $dbc->query($sql)){
				echo 'newInsert';
			}else{
				echo mysqli_error($dbc);
			}
			exit;
		}
		else
		{
			echo 'duplicate';
		}
	} 
	else
	{
		// Insert new data 
		$data = array();
		foreach($sheetData as $k=>$v){


			// echo '<pre>';
			// print_r($v);
			// echo '</pre>';
			
			$data[$v[6]]['id'] = $employees[$v[6]]['emp_id'];
			$data[$v[6]]['name'] = $employees[$v[6]][$lang.'_name'];
			
			$in = explode(' ', $v[0]);
			$in2 = explode(' ', $v[1]);

			$din = date('d-m-Y', strtotime(str_replace('/','-',$in[0])));

			$out[1] = '';
			if(!empty($v[1]) && $v[1] != '-')
			{
				$out = explode(' ', $v[1]);
			}

			if(!isset($data[$v[6]]['time'][$din]))
			{
				$data[$v[6]]['time'][$din] = $in[1];
				$data[$v[6]]['time'][$din] .= '|'.$out[1];
			}

			$din1 = date('Y-m-d', strtotime(str_replace('/','-',$in[0])));
			$din2 = date('Y-m-d', strtotime(str_replace('/','-',$in2[0])));


			// fetching employee name from sheet
			if($v[5] != '' && $v[5] != 'Name')
			{
				$emp_name = $v[5];
			}

			$scan_in 	= $in[1];
			$scan_out   = $out[1];

			// $status = '1';

				if (preg_match('~[0-9]+~', $scan_out)) {
					// contains number 
				    $numCheck = '1';
				}
				else
				{
					// no number 
					$numCheck = '0';
				}

		

				if($scan_in == '' || $scan_out == '' || $numCheck == '0')
				{
					$status = '0';
					$scaninVal = 'No';
					$scanOutVal = 'No';

				}
				else
				{
					$status = '1';
					$scaninVal = $scan_in;
					$scanOutVal = $scan_out;
				}
			

				if($v[6] == 'Total late' || $v[6] == 'Code')
				{
					$s_idVal= '';
				}
				else
				{
					$s_idVal = $v[6];
				}

		
			// get scan id on the basis of emp id from sheet 

			$sql6 = "SELECT emp_id from  ".$cid."_employees WHERE sid= '".$s_idVal."'";
			$res6 = $dbc->query($sql6);

			if ($res6->num_rows > 0) 
			{
				if($row6 = $res6->fetch_assoc())
				{

					$sql1 = "INSERT INTO ".$cid."_scandata (datescan, emp_name,scan_in, scan_out,filename, status,scan_id,emp_id,datescanout) VALUES ";
					$sql1 .= "('".$dbc->real_escape_string($din1)."', ";
					$sql1 .= "'".$dbc->real_escape_string($emp_name)."', ";
					$sql1 .= "'".$dbc->real_escape_string($scaninVal)."', ";
					$sql1 .= "'".$dbc->real_escape_string($scanOutVal)."', ";
					$sql1 .= "'".$dbc->real_escape_string($filename)."', ";
					$sql1 .= "'".$dbc->real_escape_string($status)."', ";
					$sql1 .= "'".$dbc->real_escape_string($s_idVal)."', ";
					$sql1 .= "'".$dbc->real_escape_string($row6['emp_id'])."', ";
					$sql1 .= "'".$dbc->real_escape_string($din2)."')";

					$res1 = $dbc->query($sql1);	

				}
			}
			else
			{
				$sql1 = "INSERT INTO ".$cid."_scandata (datescan, emp_name,scan_in, scan_out,filename, status,emp_id,datescanout) VALUES ";
				$sql1 .= "('".$dbc->real_escape_string($din1)."', ";
				$sql1 .= "'".$dbc->real_escape_string($emp_name)."', ";
				$sql1 .= "'".$dbc->real_escape_string($scaninVal)."', ";
				$sql1 .= "'".$dbc->real_escape_string($scanOutVal)."', ";
				$sql1 .= "'".$dbc->real_escape_string($filename)."', ";
				$sql1 .= "'".$dbc->real_escape_string($status)."', ";
				$sql1 .= "'".$dbc->real_escape_string($s_idVal)."', ";
				$sql1 .= "'".$dbc->real_escape_string($din2)."')";

				$res1 = $dbc->query($sql1);	

			}

		

		}

		// echo '<pre>';
		// print_r($data);
		// echo '</pre>';
		// die();

		$in_out = 'Yes';
		$scan_system = $_POST['scan_system'];

		$sql = "INSERT INTO ".$cid."_scanfiles (date, period, content, filename, import, status,in_out,scansystem) VALUES ";
			$sql .= "('".$dbc->real_escape_string(date('Y-m-d'))."', ";
			$sql .= "'".$dbc->real_escape_string($period)."', ";
			$sql .= "'".$dbc->real_escape_string(serialize($data))."', ";
			$sql .= "'".$dbc->real_escape_string($filename)."', ";
			$sql .= "'".$dbc->real_escape_string(1)."', ";
			$sql .= "'".$dbc->real_escape_string(0)."', ";
			$sql .= "'".$dbc->real_escape_string($in_out)."', ";
			$sql .= "'".$dbc->real_escape_string($scan_system)."')";
		
		ob_clean();
		if($res = $dbc->query($sql)){
			echo 'success';
		}else{
			echo mysqli_error($dbc);
		}
		exit;

	}



		
