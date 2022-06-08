
<?php

	if(session_id()==''){session_start();}
	ob_start();
	mb_internal_encoding('UTF-8');
	//mb_internal_encoding('TIS-620');

/*$order = [0 => 'UTF-8', 1 => 'ASCII'];
$encoding = mb_detect_encoding('เนเธ—เธขเนเธฅเธเธ”เน', $order, true);

var_dump($order);
// array(2) { [0]=>string(5) "UTF-8", [1]=> string(5) "ASCII" }
var_dump($encoding);
// string(5) "UTF-8"

$order = mb_detect_order();
$encoding = mb_detect_encoding('เนเธ—เธขเนเธฅเธเธ”เน', $order, true);

var_dump($order);
// array(2) { [0]=>string(5) "ASCII", [1]=> string(5) "UTF-8" }
var_dump($encoding);
// string(5) "UTF-8"

exit;	
*/	
	function utf8_to_tis620($string) {
		$str = $string;
		$res = "";
		for ($i = 0; $i < strlen($str); $i++) {
			if (ord($str[$i]) == 224) {
			  $unicode = ord($str[$i+2]) & 0x3F;
			  $unicode |= (ord($str[$i+1]) & 0x3F) << 6;
			  $unicode |= (ord($str[$i]) & 0x0F) << 12;
			  $res .= chr($unicode-0x0E00+0xA0);
			  $i += 2;
			} else {
			  $res .= $str[$i];
			}
		}
		return $res;
	}
	
	$str = 'ดกหฟพร  english text';
	//$str = "This is the Euro symbol '€'.";

	echo 'Original: €'.'<br>';
 	echo 'TRANSLIT: '.iconv("UTF-8", "ISO-8859-1//TRANSLIT", '€').'<br>';
  	echo 'IGNORE: '.iconv("UTF-8", "ISO-8859-1//IGNORE", '€').'<br>';
  	echo 'function: '.utf8_to_tis620($str).'<br><br>';
	echo 'Convert : '.mb_convert_encoding($str, "auto").'<br>';
	//setlocale(LC_ALL, "th_TH"); //set your own locale
	
	echo 'Original text : '.$str.'<br>';
	echo 'Original encoding : '.mb_detect_encoding($str).'<br>';
	echo 'Convert to UTF-8 : '.iconv(mb_detect_encoding($str), "UTF-8", $str).'<br>';
	echo 'Convert to TIS-620 : '.iconv(mb_detect_encoding($str), "TIS-620", $str).'<br>';
	echo 'Convert to UTF-16 : '.iconv(mb_detect_encoding($str), "UTF-16", $str).'<br>';
	echo 'Convert to ISO-8859-11 : '.iconv(mb_detect_encoding($str), "ISO-8859-11//PLAIN", $str).'<br>';
	echo 'Convert to WINDOWS-1252 : '.iconv(mb_detect_encoding($str), "WINDOWS-1252", $str).'<br>';
	

	exit;
	
	include('../../../dbconnect/db_connect.php');
	include(DIR.'payroll/inc/payroll_functions.php');
	include(DIR.'payroll/inc/tax_modulle.php');
	//global $compinfo;
//1	2-11/10     12-17 	18-23   24-27 	      28-72	        73-76 	77-82			83-97					98-111			112-123	 	 124-135 
//1   2000181082  00000  040759   0559   company name28-72   0500   000103   000000317518800  00000014288000  000007144000  000007144000

//10000000000000000 310562 562 Your company name Thai                      050000000300000000450000000000000450000000000225000000000225000

//1000000 310562 0562             050000000300000000450000000000000450000000000225000000000225000
//1000000 301160 1160             050000010100000012778557800000012778800000006389400000006389400

//10000000310562562เธเธฃเธดเธฉเธฑเธ— เน€เธญเนเธกเน€เธญเธเธเธต(เนเธ—เธขเนเธฅเธเธ”เน)เธเธณเธเธฑเธ”             050000000300000000450000000000000450000000000225000000000225000
//10000003011601160เธเธฃเธดเธฉเธฑเธ— เน€เธญเนเธกเน€เธญเธเธเธต(เนเธ—เธขเนเธฅเธเธ”เน)เธเธณเธเธฑเธ”             050000010100000012778557800000012778800000006389400000006389400
//เธเธฃเธดเธฉเธฑเธ— เน€เธญเนเธกเน€เธญเธเธเธต(เนเธ—เธขเนเธฅเธเธ”เน)เธเธณเธเธฑเธ”             


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
	
	$compinfo['th_compname'] = 'เธเธฃเธดเธฉเธฑเธ— เน€เธญเนเธเธเนเน€เธฃเธขเน เนเธญเธเธตเธ—เธต เนเธญเธเธ”เน เธเธญเธเธเธฑเธฅเธ•เธดเนเธ เธเธณเธเธฑเธ”';
	if(mb_strlen($compinfo['th_compname']) > 45){
		$compname = mb_substr($compinfo['th_compname'],0,45);
	}else{
		$compname = $compinfo['th_compname'].str_repeat(' ', (45 - mb_strlen($compinfo['th_compname'])));
	}
	//var_dump($compname);
	//var_dump(mb_strlen($compname));
	//exit;

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
				//$data[$row['emp_id']][] = $empinfo['firstname'];
				//$data[$row['emp_id']][] = $empinfo['lastname'];
				
				$fn = mb_strlen($empinfo['firstname'], 'UTF-8');
				$ln = mb_strlen($empinfo['lastname'], 'UTF-8');
				$data[$row['emp_id']][] = mb_substr($empinfo['firstname'],0,30, 'UTF-8').str_repeat(' ', 30-$fn);
				$data[$row['emp_id']][] = mb_substr($empinfo['lastname'],0,35, 'UTF-8').str_repeat(' ', 35-$ln);
				
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
					
			//var_dump(mb_strlen($txt));
			//var_dump($txt);
			//var_dump($data);var_dump($tot_wages);var_dump(sprintf('%018d', 1234567));
			foreach($data as $k=>$v){
				$fn = mb_strlen($v[2]);
				$ln = mb_strlen($v[3]);
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
	$data = $txt;
	//$data = iconv(mb_detect_encoding($txt), "TIS-620", $txt);
	//$data = mb_convert_encoding ($txt, 'UTF-8', 'UTF-8');
	
	//$data = mb_convert_encoding($txt, 'Windows-1252', 'UTF-8');
	//$data = mb_convert_encoding($txt, 'Windows-1252', 'UTF-8');
	//$data = mb_convert_encoding($txt, 'UTF-8', 'ISO-8859-5');
	//$data = mb_convert_encoding($txt, 'UTF-8', 'ISO-8859-1');
	//var_dump(mb_check_encoding($txt, 'UTF-8'));
	
	echo 'Original : '.$txt.'<br><br>';
	//echo 'Encoded : '.$data.'<br><br>';
	echo 'TRANSLIT : ', iconv(mb_detect_encoding($txt), "Windows-874//ignore", $txt).'<br><br>';
	echo 'UTF-8  : '.iconv("UTF-8", "TIS620", $txt).'<br><br>';
	//echo 'WINDOWS-1252  : '.iconv(mb_detect_encoding($txt), "CP1252", $txt).'<br><br>';
	
	exit;
	$data = iconv( mb_detect_encoding($txt), 'tis620', $txt );
	//$data = iconv("UTF-8", "ISO-8859-1", $txt);
	//$data = mb_convert_encoding($txt, 'ANSI', 'auto');
	
	$dir = DIR.$_SESSION['rego']['cid'].'/documents/';
	$root = ROOT.$_SESSION['rego']['cid'].'/documents/';
	$filename = strtoupper($_SESSION['rego']['cid']).' SSO textfile '.$_SESSION['rego']['year_th'].' x'.$_SESSION['rego']['curr_month'].'.txt';
	$doc = 'SSO upload textfile';
	
	header('Content-type: text/plain;');
	header("Content-Disposition: attachment; filename=".$filename);
	
	ob_clean();
	echo trim($data);
	
	file_put_contents($dir.$filename, $data);
	
	//exec('iconv -f utf8 -t tis620 -o' $dir.$filename $dir.$filename.'x' );
	//iconv -t CP1252 -f UTF-8 "input_file.txt" > "encoded_output_file.txt"

	//$fp = fopen($dir.$filename, 'wb');
	//fwrite($fp,$txt);
	//fclose($fp);	
	
	//include('../print/save_to_documents.php');

	exit;

?>









