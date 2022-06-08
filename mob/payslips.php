<?

	$sql = "SELECT * FROM ".$_SESSION['rego']['payroll_dbase']." WHERE emp_id = '".$_SESSION['rego']['emp_id']."' AND paid = 'Y' ORDER by month DESC";
	
	$pr_months = array();
	if($res = $dbc->query($sql)){
		while($row = $res->fetch_object()){
			$pr_months[$row->month] = (int)$row->month;
		}
	}
	//var_dump($pr_months);
	//var_dump($months);
	$field = unserialize($sys_settings['payslip_field']);
	//var_dump($field);
	//var_dump($data); exit;
	
?>
	<div class="container-fluid" style="padding:0">
            
		<div class="accordion" id="accordionExample1">
		
			<? $n=0; 
			if($pr_months){
			foreach($pr_months as $m => $v){ 
				$data = getPayslipData($_SESSION['rego']['emp_id'], $m, $lang, "em"); //var_dump($data); exit;
				$n++;
			?>
			
			
			<div class="item">
				<div class="accordion-header">
					<button class="btn <? if($n > 1){echo 'collapsed';}?>" type="button" data-toggle="collapse" data-target="#accor<?=$n?>">
						<?=$lng['Payslip'].' '.$months[$v]?>
					</button>
				</div>
				<div id="accor<?=$n?>" class="accordion-body <? if($n == 1){echo 'show';}else{echo 'collapse';}?>" data-parent="#accordionExample1">
					<div class="accordion-content" style="padding:0">
					<table class="accordion-table">
						<tbody>
					<thead>
						<tr>
							<th colspan="2"><?=$lng['Earnings']?></th>
						</tr>
					</thead>
					<tbody>
					<? $table = '';
					foreach($data['earnings'] as $k=>$v){
						if($k != 'rate'){
							$table .= '<tr>
								<th style="width:10%">'.$v[0].'</th>
								<td class="tar">'.$v[2].'</td>
							</tr>';
						}
					}
					$table .= '	
						<tr class="footer">
							<th>'.$lng['Total earnings'].'</th>
							<td class="tar">'.$data['gross_income'].'</td>
						</tr>
						<tr class="space"><td colspan="2"></td></tr>
					</tbody>
					<thead>
						<tr>
							<th colspan="2">'.$lng['Deductions'].'</th>
						</tr>
					</thead>';
					foreach($data['deduct'] as $k=>$v){
						$table .= '<tr>
							<th>'.$v[0].'</th>
							<td class="tar">'.$v[2].'</td>
						</tr>';
					}
					$table .= '
						<tr class="footer">
							<th>'.$lng['Total deductions'].'</th>
							<td class="tar">'.$data['tot_deductions'].'</td>
						</tr>
						<tr class="space"><td colspan="2"></td></tr>
						
						<tr class="total" style="font-size:15px">
							<th>'.$lng['Net to pay'].'</th>
							<td class="tar">'.$data['net_income'].'</td>
						</tr>';
						
			$table .= '
						<tr><td style="padding:2px 0 3px" colspan="2"></td></tr>';
						
						if(isset($field['ytd1'])){ 
							$table .= '
							<tr class="ytd">
								<th>'.$lng['YTD. Income'].'</th>
								<td class="tar">'.$data['asalary'].'</td>
							</tr>';
						}
						if(isset($field['ytd2'])){ 
							$table .= '
							<tr class="ytd">
								<th>'.$lng['YTD. Tax'].'</th>
								<td class="tar">'.$data['atax'].'</td>
							</tr>';
						}
						if(isset($field['ytd3']) && $data['aprovfund'] != '0'){ 
							$table .= '
							<tr class="ytd">
								<th>'.$lng['YTD. Prov. Fund'].'</th>
								<td class="tar">'.$data['aprovfund'].'</td>
							</tr>';
						}
						if(isset($field['ytd4'])){ 
							$table .= '
							<tr class="ytd">
								<th>'.$lng['YTD. Social SF'].'</th>
								<td class="tar">'.$data['asocial'].'</td>
							</tr>';
						}
						if(isset($field['ytd5'])){ 
							$table .= '
							<tr class="ytd">
								<th>'.$lng['YTD. Other allowance'].'</th>
								<td class="tar">'.$data['aother'].'</td>
							</tr>';
						}
						$table .= '
								</tbody>
							</table>';
						
						echo $table; ?>
						
						<div style="padding:5px">
							<button onClick="window.open('print_payslips.php?m=<?=$m.'&id='.$data['emp_id']?>')" style="font-size:16px; margin:0" type="button" class="btn btn-default btn-block"><i class="fa fa-download"></i><?=$lng['Download']?></button>
						</div>
					</div>
				</div>
			</div>
			<? }}else{ ?>
				<div class="static-notification bg-blue-dark tap-dismiss">
					 <p style="color:#fff; padding:8px 10px; text-align:center"><i class="fa fa-info-circle fa-lg"></i>&nbsp; No data available yet</p>
				</div> 					
			<? } ?>

		</div>
		
	</div>








