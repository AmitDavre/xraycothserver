<style>
	table.codeTable {
		border-collapse:collapse;
		font-family: Courier New, Verdana;
	}
	table.codeTable td {
		color:#069;
		white-space:nowrap;
	}
	span.txt {
		font-family: Courier New, Verdana;
		color:#a00;
		padding-bottom:10px;
		display:block;
	}
</style>

<div class="A4form" style="width:960px">
<table style="width:100%; margin-bottom:15px;" border="0"><tr>
	<td style="font-size:20px; font-weight:600"><?=$lng['SSO text file']?></td>
	<td>
		<button type="button" class="btn btn-primary btn-fr" onclick="window.location.href='<?=ROOT?>payroll/export/download/download_sso_textfile.php';"><i class="fa fa-download"></i>&nbsp; <?=$lng['Download']?></button>
	</td>
</tr></table>

<?php
	
	if(session_id()==''){session_start();}
	ob_start();
	mb_internal_encoding('UTF-8');
	
	function getMBStrSplit($string, $split_length = 1){
		mb_internal_encoding('UTF-8');
		mb_regex_encoding('UTF-8'); 
		$split_length = ($split_length <= 0) ? 1 : $split_length;
		$mb_strlen = mb_strlen($string, 'utf-8');
		$array = array();
		$i = 0; 
		while($i < $mb_strlen){
			$array[] = mb_substr($string, $i, $split_length);
			$i = $i+$split_length;
		}
		return $array;
	}
	function getStrLenTH($string){
		$array = getMBStrSplit($string);
		$count = 0;
		foreach($array as $value){
			$ascii = ord(iconv("UTF-8", "TIS-620", $value));
			if(!($ascii == 209 || ($ascii >= 212 && $ascii <= 218) || ($ascii >= 231 && $ascii <= 238))){$count += 1;}
		}
		return $count;
	}
	
	$compname = $edata[$lang.'_compname']. str_repeat('&nbsp;', 45-getStrLenTH($edata[$lang.'_compname']));
	$title = array(1=>'003',2=>'004',3=>'005');
	$pd = explode('-',$_SESSION['rego']['paydate']);
	$d = sprintf("%02d",$pd[0]);
	$m = sprintf("%02d",$pd[1]);
	$y = substr(($pd[2]+543),-2);
	$paydate = $d.$m.$y;
	$txt = '';
	
	$tot_wages = 0; $tot_sso = 0; $sso = 0; $ssoc = 0;
	$sql = "SELECT * FROM ".$_SESSION['rego']['payroll_dbase']." WHERE month = '".$_SESSION['rego']['cur_month']."' AND entity = '".$_SESSION['rego']['gov_entity']."' AND branch = '".$_SESSION['rego']['gov_branch']."' AND emp_group = '".$_SESSION['rego']['emp_group']."' AND calc_sso = 1";
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
				$data[$row['emp_id']][] = trim($empinfo['firstname']);
				$data[$row['emp_id']][] = trim($empinfo['lastname']);
				//$basicsalary = $row['salary'] + $fix_allow;
				$basicsalary = (($row['salary'] + $fix_allow + $row['remaining_salary']) - ($row['leave_wop_b'] + $row['absence_b'] + $row['late_early_b']) );
				if($sso_act_max == 'act'){
					$basic_salary = $basicsalary;
				}else{
					$basic_salary = ($basicsalary > $rego_settings['sso_max_wage'] ? $rego_settings['sso_max_wage'] : $basicsalary);
				}
				$basic_salary = ($basicsalary < $rego_settings['sso_min_wage'] ? $rego_settings['sso_min_wage'] : $basicsalary);
				
				$data[$row['emp_id']][] = number_format($basic_salary,2,'','');
				$data[$row['emp_id']][] = number_format($row['social'],2,'','');
				$tot_wages += $basic_salary; 
				$sso += $row['social'];
				$ssoc += $row['social_com'];
			}
			$sso = $sso * 100;
			$ssoc = $ssoc * 100;
			$total_sso = $sso + $ssoc;
			
			$tot_wages = number_format($tot_wages,2,'','');
			$branch = sprintf("%06d",$branch);
		
			$txt 	= '<span class="txt">1 '
					.str_replace('-','',$edata['sso_account']).'&nbsp;'
					.$branch.'&nbsp;'
					.$paydate.'&nbsp;'
					.$period.'&nbsp;'
					.$compname.' '
					.$sso_rate.' '
					.sprintf("%06d",count($data)).' '
					.sprintf("%015d",$tot_wages).' '
					.sprintf("%014d",$total_sso).' '
					.sprintf("%012d",$sso).' '
					.sprintf("%012d",$ssoc)
					."</span>";
			
			$txt .= '<table class="codeTable">';
			foreach($data as $k=>$v){
				$fn = getStrLenTH($v[2]); //var_dump($fn);
				$ln = getStrLenTH($v[3]); //var_dump($ln);
				$txt 	.= '<tr><td>2'.'&nbsp;</td><td>'
						.str_replace('-','',$v[0]).'&nbsp;</td><td>'
						.mb_substr($v[1],0,3).'&nbsp;</td><td>'
						.$v[2].str_repeat('&nbsp;', 30-$fn).'</td><td>'
						.$v[3].str_repeat('&nbsp;', 35-$ln).'</td><td>'
						.sprintf("%014d",$v[4]).'&nbsp;</td><td>'
						.sprintf("%012d",$v[5]).'&nbsp;</td></tr>';
			}
			$txt 	.= '</table>';
		}else{
			$txt = '<i class="fa fa-caret-right"></i> '.$lng['No data available for this month'];
		}
	}
	//var_dump($data);exit;
	//ob_clean();
	echo trim($txt);
	//exit;
?>

</div>