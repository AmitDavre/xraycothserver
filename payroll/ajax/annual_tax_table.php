<?	
	
	$colspan = 4;
	if(!$empinfo['calc_pvf'] && !$empinfo['calc_psf']){$colspan = 3;}

	$table = '
		<table class="modalTable" border="0">
			<thead>
			<tr>
				<th class="tac" style="line-height:160%" colspan="2">'.strtoupper($calc_method).'</th>
				<th class="tac" style="line-height:160%" colspan="4">'.$lng['Income'].'</th>
				<th class="tac" style="line-height:160%">'.$lng['Deduct'].'</th>
				<th class="tac" style="line-height:160%">'.$lng['Gross'].'</th>
				<th class="tac" style="line-height:160%" colspan="'.$colspan.'">'.$lng['Deductions'].'</th>
				<th class="tac" style="line-height:160%">Net</th>
			</tr>
			<tr>
				<th class="tac" style="width:1%">'.$lng['Month'].'</th>
				<th class="tac" style="width:1%">'.$lng['Paid'].'</th>
				<th class="tac" style="width:8%">'.$lng['Salary'].'</th>
				<th class="tac" style="width:8%">'.$lng['Fixed allowances'].'</th>
				<th class="tac" style="width:8%">'.$lng['Total OT'].'</th>
				<th class="tac" style="width:8%">'.$lng['Variable income'].'</th>
				<th class="tac" style="width:8%">'.$lng['Before tax'].'</th>
				<th class="tac" style="width:8%">'.$lng['Income'].'</th>
				<th class="tac" style="width:8%">'.$lng['After tax'].'</th>';
			if($empinfo['calc_pvf'] || $empinfo['calc_psf']){$table .= '<th class="tac" style="width:8%">PSF/'.$lng['PVF'].'</th>';}	
			$table .= '
				<th class="tac" style="width:8%">'.$lng['Social'].'</th>
				<th class="tac" style="width:8%">'.$lng['Tax'].'</th>
				<th class="tac" style="width:8%">'.$lng['Income'].'</th>
			</tr>
			</thead>
			<tbody>';
			if($data){ 
				foreach($data as $k=>$v){ 
					if($k < 13){
					$table .= '
					<tr class="tar '.$v['class'].'">
						<td style="white-space:nowrap">'.$v['date'].'</td>
						<td class="tac">'.$v['paid'].'</td>
						<td>'; if($v['salary'] > 0){$table .= number_format($v['salary'],2);}else{$table .= '-';} $table .= '</td>
						<td>'; if($v['allow'] > 0){$table .= number_format($v['allow'],2);}else{$table .= '-';} $table .= '</td>
						<td>'; if($v['ot'] > 0){$table .= number_format($v['ot'],2);}else{$table .= '-';} $table .= '</td>
						<td>'; if($v['other_income'] > 0){$table .= number_format($v['other_income'],2);}else{$table .= '-';} $table .= '</td>
						<td>'; if($v['deduct_before'] > 0){$table .= number_format($v['deduct_before'],2);}else{$table .= '-';} $table .= '</td>
						<td>'; if($v['gross'] > 0){$table .= number_format($v['gross'],2);}else{$table .= '-';} $table .= '</td>
						<td>'; if($v['deduct_after'] > 0){$table .= number_format($v['deduct_after'],2);}else{$table .= '-';} $table .= '</td>';
					if($empinfo['calc_pvf'] || $empinfo['calc_psf']){
						$table .= '<td>'; 
						if($v['pvf'] > 0){
							$table .= number_format($v['pvf'],2);
						}else{
							$table .= '-';
						} 
					$table .= '</td>';}	
					$table .= '
						<td>'; if($v['sso'] > 0){$table .= number_format($v['sso'],2);}else{$table .= '-';} $table .= '</td>
						<td>'; if($v['tax_month'] > 0){$table .= number_format($v['tax_month'],2);}else{$table .= '-';} $table .= '</td>
						<td>'; if($v['net'] > 0){$table .= number_format($v['net'],2);}else{$table .= '-';} $table .= '</td>
					</tr>';
					}else{
						$table .= '</tbody><tfoot class="tar">';
						$table .= '
						<tr>
							<td colspan="2" class="tar">'.$v['date'].'</td>
							<td>'.number_format($v['salary'],2).'</td>
							<td>'.number_format($v['allow'],2).'</td>
							<td>'.number_format($v['ot'],2).'</td>
							<td>'.number_format($v['other_income'],2).'</td>
							<td>'.number_format($v['deduct_before'],2).'</td>
							<td>'.number_format($v['gross'],2).'</td>
							<td>'.number_format($v['deduct_after'],2).'</td>';
						if($empinfo['calc_pvf'] || $empinfo['calc_psf']){$table .= '<td>'.number_format($v['pvf'],2).'</td>';}	
						$table .= '
							<td>'.number_format($v['sso'],2).'</td>
							<td>'.number_format($v['tax_month'],2).'</td>
							<td>'.number_format($v['net'],2).'</td>
						<tr></tfoot></table>';
					}
				}
			}else{
				$table .= '
				<tr>
					<td colspan="15" style="text-align:center">No data available</td>
				<tr></tbody></table>';
			}
