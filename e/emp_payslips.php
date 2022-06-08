<?
	
	$sql = "SELECT * FROM ".$_SESSION['rego']['payroll_dbase']." WHERE emp_id = '".$_SESSION['rego']['emp_id']."' AND paid = 'Y' ORDER by month DESC";
	$res = $dbc->query($sql);
	while($row = $res->fetch_assoc()){
		$pr_months[$row['month']] = (int)$row['month'];
	}
	//var_dump($pr_months); exit;
	$field = unserialize($sys_settings['payslip_field']);
	//var_dump($months);
	
?>

<style>
.payslipTable {
	width:100%;
	xmin-width:800px;
	border-collapse:collapse;
}
.topTable {
	width:100%;
	min-width:800px;
	table-layout:fixed;
}

.payslipTable th {
	padding:5px 6px;
	white-space:nowrap;
	border:1px solid #ccc;
	background:#f9f9f9;
}
.payslipTable td {
	padding:4px 8px;
	white-space:nowrap;
	border:1px solid #ccc;
	text-align:right;
}
.payslipTable th.tar {
	text-align:right;
}
.payslipTable th.tac {
	text-align:center;
}
.pop1 {
	max-width:200px; 
	width:160px; 
	padding:3px 0px;
}
.pop1 button {
	width:100%; 
	padding:0px 10px !important;
	line-height:20px !important;
	margin:2px 0;
	text-align:left;
	color:#fff;
}
.topTable td {
	padding:3px 0;
}

	.pannel {
		position:absolute; 
		top:130px; 
		bottom:10px; 
		border:0px solid red;
		padding:15px;
		box-size:border-box;
		overflow:hidden;
	}
	.left_pannel {
		left:0; 
		width:205px;
		padding-right:5px;
	}
	.main_pannel {
		left:205px; 
		right:0;
		padding-left:5px;
	}
	b {
		font-weight:600;
	}
	input, select, textarea {
		background:transparent !important;
	}
	textarea{  
		box-sizing: border-box;
		resize: none;
		overflow:hidden;
	}
		.fileBtn {
			display:block;
			margin-top:5px;
		}
		.fileBtn [type="file"]{
			border:0;
			visibility:false;
			position:absolute;
			width:0px;
			height:0px;
		}
		.fileBtn label{
			background:#eee;
			background: linear-gradient(to bottom, #eee, #ddd);
			border-radius: 2px;
			border:1px #ccc solid;
			padding:1px 8px;
			line-height:18px;
			white-space:nowrap;
			color: #000;
			cursor: pointer;
			display: inline-block;
			xfont-family: 'Open Sans', sans-serif;
			font-size:13px;
			font-weight:400;
		}
		.fileBtn label:hover{
			background: linear-gradient(to bottom, #ddd, #eee);
		}
		.fileBtn p {
			padding:0 0 0 5px;
			margin:0;
			display:inline-block;
			xfont-family: Arial, Helvetica, sans-serif;
			font-size:13px;
		}
</style>

<div style="width:100%">
	<h2><i class="fa fa-list-ul"></i>&nbsp; <?=$lng['Payslips'].' '.$_SESSION['rego']['year_'.$lang]?></h2>

	<div class="pannel left_pannel">
		<? include('emp_picture.php'); ?>
	</div>
	
	<div class="pannel main_pannel">
	
		<? if(empty($pr_months)){
			echo '<div class="msg_alert">'.$lng['No data available in Database'].'</div>';
		}else{ ?>
	
		<ul class="nav nav-tabs" id="myTab" <? if($lang=='th'){ echo 'style="font-size:12px"';}?>>
		<?	reset($pr_months); $last = key($pr_months); //var_dump($last);
			foreach($pr_months as $k => $v){
				echo '<li  class="nav-item"><a class="nav-link ';
				if($k == $last){echo 'active';}
				echo '" data-target="#tab_'.$k.'" data-toggle="tab">'.$months[(int)$k].'</a></li>';
			} ?>
		</ul>

		<div class="tab-content" style="height:calc(100% - 25px); padding:10px">
		
			<? foreach($pr_months as $m => $v){ ?>
					<div style="padding:10px 10px; height:100%; overflow-Y:auto" class="tab-pane <? if($m == $last){echo 'show active';} ?>" id="tab_<?=$m?>">
			<?
			
			$data = getPayslipData($_SESSION['rego']['emp_id'], $m, $lang, "em");
			//var_dump($lang);
			$table = '
					<div style="border:0px solid red; padding:20px; width:850px; box-shadow: 0 0 10px rgba(0,0,0,0.2)">
					<table class="topTable" border="0">
						<tr>
							<td style="width:34%"><b>'.$lng['Emp. ID'].' :</b> '.$data['emp_id'].'</td>
							<td style="width:46%"><b>'.$lng['Position'].' :</b> '.$data['position'].'</td>
							<td style="white-space:nowrap"><b>'.$lng['Period'].' :</b> '.$data['period'].'</td>
						</tr>
						<tr>
							<td><b>'.$lng['Name'].' :</b> '.$data['name_'.$lang].'</td>
							<td><b>'.$lng['Department'].' :</b> -</td>
							<td style="white-space:nowrap"><b>'.$lng['Account'].' :</b> '.$data['account'].'</td>
						</tr>
					</table>
					
					<table class="payslipTable" style="margin-top:10px" border="0">
						<tr>
							<th style="width:35%">'.$lng['Earnings'].'</th>
							<th class="tac">'.$lng['Number'].'</th>
							<th class="tac">&nbsp;&nbsp;&nbsp;&nbsp;'.$lng['Amount'].'&nbsp;&nbsp;&nbsp;&nbsp;</th>
							<th style="width:35%">'.$lng['Deductions'].'</th>
							<th class="tac">'.$lng['Number'].'</th>
							<th class="tac">&nbsp;&nbsp;&nbsp;&nbsp;'.$lng['Amount'].'&nbsp;&nbsp;&nbsp;&nbsp;</th>
						</tr>
							<tr style="height:250px">
								<td valign="top" style="text-align:left">';
								foreach($data['earnings'] as $k=>$v){
									$table .= $v[0].'<br>';
								}
				$table .= ' </td>
								<td valign="top">';
								foreach($data['earnings'] as $k=>$v){
									$table .= $v[1].'<br>';
								}
				$table .= ' </td>
								<td valign="top">';
								foreach($data['earnings'] as $k=>$v){
									$table .= $v[2].'<br>';
								}
				$table .= ' </td>
								<td valign="top" style="text-align:left">';
								foreach($data['deduct'] as $k=>$v){
									$table .= $v[0].'<br>';
								}
				$table .= ' </td>
								<td valign="top">';
								foreach($data['deduct'] as $k=>$v){
									$table .= $v[1].'<br>';
								}
				$table .= ' </td>
								<td valign="top">';
								foreach($data['deduct'] as $k=>$v){
									$table .= $v[2].'<br>';
								}
				$table .= ' </td>
							</tr>
							<tr>
								<th class="tar">'.$lng['Total earnings'].'</th>
								<td colspan="2"><b>'.$data['gross_income'].'</b></td>
								<th class="tar">'.$lng['Total deductions'].'</th>
								<td colspan="2"><b>'.$data['tot_deductions'].'</b></td>
							</tr>
							<tr>
								<th colspan="4" class="tar">'.$lng['Net to pay'].'</th>
								<td colspan="2"><b style="font-weight:700">'.$data['net_income'].'</b></td>
							</tr>
					</table>
					<table class="payslipTable" style="table-layout:fixed; margin-top:10px">
						<tr><td>';
							if(isset($field['ytd1'])){ $table .= $data['asalary'];}
							$table .= '</td><td>';
							if(isset($field['ytd2'])){ $table .= $data['atax'];}
							$table .= '</td><td>';
							if(isset($field['ytd3'])){ $table .= $data['aprovfund'];}
							$table .= '</td><td>';
							if(isset($field['ytd4'])){ $table .= $data['asocial'];}
							$table .= '</td><td>';
							if(isset($field['ytd5'])){ $table .= $data['aother'];}
							$table .= '</td>
						</tr>
						<tr>
							<th style="white-space:normal" class="tac">'.$lng['YTD. Income'].'</th>
							<th style="white-space:normal" class="tac">'.$lng['YTD. Tax'].'</th>
							<th style="white-space:normal" class="tac">'.$lng['YTD. Prov. Fund'].'</th>
							<th style="white-space:normal" class="tac">'.$lng['YTD. Social SF'].'</th>
							<th style="white-space:normal" class="tac">'.$lng['YTD. Other allowance'].'</th>
						</tr>
					</table>
					</div>';
			
			echo $table; ?>
			
			<a target="_blank" href="<?=ROOT?>mob/print_payslips.php?m=<?=$m?>&id=<?=$data['emp_id']?>" style="margin:15px 0 0 0; display:inline-block" class="btn btn-primary"><i class="fa fa-download"></i>&nbsp; <?=$lng['Download'].' / '.$lng['Print']?></a>
		
		</div>
		<? } ?>
		
	</div>
	
	<? } ?>

</div>
	










