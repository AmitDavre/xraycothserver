<?php
	
	if(session_id()==''){session_start();}
	ob_start();
	mb_internal_encoding('UTF-8');
	
	/*function utf8_strlen($s) {
		$c = strlen($s); $l = 0;
		for ($i = 0; $i < $c; ++$i) if ((ord($s[$i]) & 0xC0) != 0x80) ++$l;
		return $l;
	}
	
	$str = 'บริษัท เอ็กซ์เรย์ ไอซีที แอนด์ คอนซัลติ้ง จำกัด';
	var_dump(utf8_strlen($str));
	
	
	
	
	
	
	exit;*/
	/*var_dump('21234567891011003ศักดา               แก้มอิ่ม           00000001312000000000065600                           ');
	var_dump('21234567891011003ศักดา               แก้มอิ่ม           00000001312000000000065600                           ');
	var_dump('21213141516171003สดใส                  จันทร์แจ่ม     00000001348000000000067400                           ');
	exit;*/
	
	include('../../../dbconnect/db_connect.php');
	include(DIR.'payroll/inc/payroll_functions.php');
	include(DIR.'payroll/inc/tax_modulle.php');
	//global $compinfo;
//1	2-11/10     12-17 	18-23   24-27 	      28-72	        73-76 	77-82			83-97					98-111			112-123	 	 124-135 
//1   2000181082  00000  040759   0559   company name28-72   0500   000103   000000317518800  00000014288000  000007144000  000007144000

//10000000000000000 310562 562 Your company name Thai                      050000000300000000450000000000000450000000000225000000000225000

//1000000 310562 0562             050000000300000000450000000000000450000000000225000000000225000
//1000000 301160 1160             050000010100000012778557800000012778800000006389400000006389400

//10000000310562562บริษัท เอ็มเอชจี(ไทยแลนด์)จำกัด             050000000300000000450000000000000450000000000225000000000225000
//10000003011601160บริษัท เอ็มเอชจี(ไทยแลนด์)จำกัด             050000010100000012778557800000012778800000006389400000006389400
//บริษัท เอ็มเอชจี(ไทยแลนด์)จำกัด             


	$title = array(1=>'003',2=>'004',3=>'005');
	$pd = explode('-',$_SESSION['rego']['paydate']);
	//var_dump($pd);
	$d = sprintf("%02d",$pd[0]);
	$m = sprintf("%02d",$pd[1]);
	$y = substr(($pd[2]+543),-2);
	$paydate = $d.$m.$y;
	//var_dump($paydate);
	
	$txt = '';
	$period = $_SESSION['rego']['curr_month'].substr($_SESSION['rego']['year_th'],-2);
	//$pr_settings = unserialize($compinfo['pr_settings']);
	$sso_rate = sprintf("%04d",number_format((float)$pr_settings['sso_rate'], 2, '', ''));
	
	if(strlen($compinfo['th_compname']) > 45){
		$compname = substr($compinfo['th_compname'],0,45);
	}else{
		$compname = $compinfo['th_compname'].str_repeat(' ', (45 - strlen($compinfo['th_compname'])));
	}
	//var_dump($compname);
	//var_dump(mb_strlen($compname));
	
	//exit;
	//$tmp = 'บริษัท เอ็กซ์เรย์ ไอซีที แอนด์ คอนซัลติ้ง จำกัด';
	//$compname = substr($tmp,0,45);
	$compname = $compname.str_repeat(' ', (45 - strlen($compname)));
	
	$sso_act_max = getSSOactMax($cid);
	
	//var_dump('Your company name Thai                      ');
	//var_dump($compname);
	//var_dump(strlen($compinfo['th_compname'])); //exit;
	
//var_dump($_SESSION['rego']['paydate']); var_dump($paydate); var_dump($period); exit;

	$tot_wages = 0; $tot_sso = 0;
	$sql = "SELECT * FROM ".$_SESSION['rego']['payroll_dbase']." WHERE month = '".$_SESSION['rego']['cur_month']."' AND social > 0";
	if($res = $dbc->query($sql)){
		if($res->num_rows > 0){
			while($row = $res->fetch_assoc()){
				$empinfo = prGetEmployeeInfo($row['emp_id']);
				$fix_allow = 0; 
				for($i=1;$i<=10;$i++){
					$fix_allow += $row['fix_allow_'.$i];
				}
				$data[$row['emp_id']][] = $empinfo['idcard_nr'];
				$data[$row['emp_id']][] = $title[$empinfo['title']];
				
				$first = substr($empinfo['firstname'],0,30);
				$last = substr($empinfo['lastname'],0,35);
				$fn = strlen($first);
				$ln = strlen($last);
				$data[$row['emp_id']][] = $first.str_repeat(' ', 30-$fn);
				$data[$row['emp_id']][] = $last.str_repeat(' ', 35-$ln);
				
				$basicsalary = $row['salary'] + $fix_allow;
				if($sso_act_max == 'act'){
					$basic_salary = $basicsalary;
				}else{
					$basic_salary = ($basicsalary > $pr_settings['sso_max_wage'] ? $pr_settings['sso_max_wage'] : $basicsalary);
				}
				$basic_salary = ($basicsalary < $pr_settings['sso_min_wage'] ? $pr_settings['sso_min_wage'] : $basicsalary);
				$data[$row['emp_id']][] = number_format($basic_salary,2,'','');
				$data[$row['emp_id']][] = number_format($row['social'],2,'','');
				$tot_wages += $basic_salary; 
				$tot_sso += $row['social'];
			}
			$total_sso = $tot_sso*2;
			$tot_wages = number_format($tot_wages,2,'','');
			$tot_sso = number_format($tot_sso,2,'','');
			$total_sso = number_format($total_sso,2,'','');
			//$branch = sprintf("%06d",$_SESSION['branch']);
			$branch = sprintf("%06d",$compinfo['branch']);
		
			/*var_dump(str_replace('-','',$pr_settings['sso_idnr']));
			var_dump($branch);
			var_dump($paydate);
			var_dump($period);
			
			var_dump(mb_strlen($compname));
			var_dump($compname);
			var_dump($sso_rate);
			var_dump(sprintf("%06d",count($data)));
			var_dump(sprintf("%015d",$tot_wages));
			var_dump(sprintf("%014d",$total_sso));
			var_dump(sprintf("%012d",$tot_sso));
			var_dump(sprintf("%012d",$tot_sso));*/
			
			$txt 	= '1'
					.str_replace('-','',$pr_settings['sso_idnr'])
					.$branch
					.$paydate
					.$period
					.$compname
					.$sso_rate
					.sprintf("%06d",count($data))
					.sprintf("%015d",$tot_wages)
					.sprintf("%014d",$total_sso)
					.sprintf("%012d",$tot_sso)
					.sprintf("%012d",$tot_sso)
					."\n";
					
			//var_dump($compname);
			//var_dump(mb_strlen($compname));
			//var_dump(mb_strlen($txt));
			//var_dump($txt); //exit;
			
			//var_dump($data);var_dump($tot_wages);var_dump(sprintf('%018d', 1234567));
			foreach($data as $k=>$v){
				//$fn = strlen($v[2]);
				//$ln = strlen($v[3]);
				$txt 	.= '2'
						.str_replace('-','',$v[0])
						.$v[1]
						.$v[2]
						.$v[3]
						.sprintf("%014d",$v[4])
						.sprintf("%012d",$v[5])
						.str_repeat(' ', 27)
						."\n";
			}
		}
	}
	//var_dump($data);
	//exit;
	
	$data = $txt;
	//$data = mb_convert_encoding($txt, 'UTF-8', 'OLD-ENCODING');
	//$data = iconv("UTF-8","",$txt);
	
	$dir = DIR.$_SESSION['rego']['cid'].'/documents/';
	$root = ROOT.$_SESSION['rego']['cid'].'/documents/';
	$filename = strtoupper($_SESSION['rego']['cid']).' SSO textfile '.$_SESSION['rego']['year_th'].' '.$_SESSION['rego']['curr_month'].'.txt';
	$doc = 'SSO upload textfile';
	
	//header('Content-Encoding: UTF-8;');
	header('Content-type: text/plain; charset=ansi;');
	//header('Content-Transfer-Encoding: binary'); 
	header("Content-Disposition: attachment; filename=".$filename);
	//header("Pragma: no-cache"); 
	//header("Expires: 0");
	
	//header("Content-Type: text/plain; charset=utf-8");
	//header("Content-type: text/plain");
	//header("Content-Disposition: attachment; filename=".$filename);
	
	ob_clean();
	echo trim($data);
	//file_put_contents($dir.$filename, "\xEF\xBB\xBF".  $data); 
	file_put_contents($dir.$filename, $data);
	exit;
	
	/*$output = fopen($dir . '.tmp', 'w');
	stream_filter_append($txt, 'convert.iconv.OLDENCODING/UTF-8');
	stream_copy_to_stream($input, $output);
	fclose($output);
	unlink($path);
	rename($path . '.tmp', $path);*/	
	
	
	
	
	
	
	
	
	$fp = fopen($dir.$filename, 'wb');
	//fwrite($fp, pack("CCC",0xef,0xbb,0xbf));
	fwrite($fp,$data);
	fclose($fp);	
	
	//include('../print/save_to_documents.php');

	exit;











