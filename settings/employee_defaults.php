<?
	
	$data = array();
	$sql = "SELECT * FROM ".$cid."_sys_settings";
	if($res = $dbc->query($sql)){
		if($row = $res->fetch_assoc()){
			$data = $row;
		}
	}else{
		//var_dump(mysqli_error($dbc));
	}
	//$bank_codes = unserialize($rego_settings['bank_codes']);
	//$shiftteams = getShifTeams();
	//var_dump($data); exit;

?>
	<style>
		.xdatepick {
			cursor:pointer;
			padding-left:20px !important;
			min-width:110px;
		}
	</style>
	
	<h2 style="padding-right:60px">
		<i class="fa fa-cog"></i>&nbsp; <?=$lng['Employee defaults']?>
		<span style="display:none; font-style:italic; color:#b00; padding-left:30px" id="sAlert"><i class="fa fa-exclamation-triangle fa-mr"></i><?=$lng['Data is not updated to last changes made']?></span>
	</h2>		
	
	<form id="systemForm">
		<div class="main">
			<div style="padding:0 0 0 20px" id="dump"></div>
	
					<div class="tab-content-left">
						<div style="overflow-x:auto">
					
							<table class="basicTable inputs" border="0">
								<thead>
									<tr style="line-height:100%">
										<th colspan="2"><?=$lng['Employee ID']?></th>
									</tr>
								</thead>
								<tbody>
									<tr>
										<th><?=$lng['Automatic numbering']?></th>
										<td>
											<select name="auto_id" style="width:100%">
												<? foreach($noyes01 as $k=>$v){
														echo '<option ';
														if($data['auto_id']==$k){echo 'selected';}
														echo ' value="'.$k.'">'.$v.'</option>';
												} ?>
											</select>
										</td>
									</tr>
									<tr>
										<th>
											<i data-toggle="tooltip" title="Max. 4 digits" class="fa fa-exclamation-circle fa-lg fa-mr"></i><?=$lng['Start at']?>
										</th>
										<td>
											<input placeholder="0001" maxlength="4" class="sel numeric" type="text" name="id_start" value="<?=$data['id_start']?>">
										</td>
									</tr>
									<tr>
										<th>
											<i data-toggle="tooltip" title="Max. 3 characters separated by comma" class="fa fa-exclamation-circle fa-lg fa-mr"></i><?=$lng['Prefix']?>
										</th>
										<td>
											<input maxlength="39" placeholder="EMP,OFF,OPE ..." type="text" name="id_prefix" value="<?=$data['id_prefix']?>">
										</td>
									</tr>
									<tr>
										<th><?=$lng['Scan ID']?></th>
										<td>
											<select name="scan_id" style="width:100%">
												<option <? if($data['scan_id'] == 0){echo 'selected';}?> value="0"><?=$lng['Empty']?></option>
												<option <? if($data['scan_id'] == 1){echo 'selected';}?> value="1"><?=$lng['Same employee ID']?></option>
											</select>
										</td>
									</tr>
									<tr><td style="line-height:10px">&nbsp;</td></tr>
								</tbody>
								<thead>
									<tr style="line-height:100%">
										<th colspan="2"><?=$lng['Other settings']?></th>
									</tr>
								</thead>
								<tbody>
									<tr>
										<th><?=$lng['Joining date']?></th>
										<td>
											<select name="joining_date" style="width:100%">
												<option <? if($data['joining_date'] == 0){echo 'selected';}?> value="0"><?=$lng['Current date']?> : <?=date('d-m-Y')?></option>
												<option <? if($data['joining_date'] == 1){echo 'selected';}?> value="1"><?=$lng['Empty']?></option>
											</select>
										</td>
									</tr>
									<tr>
										<th><?=$lng['Team']?></th>
										<td>
											<select name="team" style="width:100%">
												<? foreach($teams as $k=>$v){
														echo '<option ';
														if($data['team']==$k ){echo 'selected';}
														echo ' value="'.$k.'">'.$v[$lang].'</option>';
												} ?>
											</select>
										</td>
									</tr>
									<tr>
										<th><?=$lng['Employee group']?></th>
										<td>
											<select name="emp_group" style="width:100%">
												<? foreach($emp_groep as $k=>$v){ ?>
													<option <? if($data['emp_group'] == $k){echo 'selected';}?> value="<?=$k?>"><?=$v?></option>
												<? } ?>
											</select>
										</td>
									</tr>
									<tr>
										<th><?=$lng['Employee type']?></th>
										<td>
											<select name="emp_type" style="width:100%">
												<? foreach($emp_type as $k=>$v){ ?>
													<option <? if($data['emp_type'] == $k){echo 'selected';}?> value="<?=$k?>"><?=$v?></option>
												<? } ?>
											</select>
										</td>
									</tr>
									<tr>
										<th><?=$lng['Employee status']?></th>
										<td>
											<select name="emp_status" style="width:100%">
												<option <? if($data['emp_status'] == 1){echo 'selected';}?> value="1"><?=$lng['Active']?></option>
												<option <? if($data['emp_status'] == 0){echo 'selected';}?> value="0"><?=$lng['on Hold / Canceled']?></option>
											</select>
										</td>
									</tr>
									<tr>
										<th><?=$lng['Accounting code']?></th>
										<td>
											<select name="account_code" style="width:100%">
												<option <? if($data['account_code'] == 0){echo 'selected';}?> value="0"><?=$lng['Indirect']?></option>
												<option <? if($data['account_code'] == 1){echo 'selected';}?> value="1"><?=$lng['Direct']?></option>
											</select>
										</td>
									</tr>
									<tr>
										<th><?=$lng['Position']?></th>
										<td>
											<select name="position" style="width:100%">
												<? foreach($positions as $k=>$v){
														echo '<option ';
														if($data['position']==$k){echo 'selected';}
														echo ' value="'.$k.'">'.$v[$lang].'</option>';
												} ?>
											</select>
										</td>
									</tr>
									<tr>
										<th><?=$lng['Date start Position']?></th>
										<td>
											<select name="date_start" style="width:100%">
												<option <? if($data['date_start'] == 0){echo 'selected';}?> value="0"><?=$lng['Joining date']?> : <?=date('d-m-Y')?></option>
												<option <? if($data['date_start'] == 1){echo 'selected';}?> value="1"><?=$lng['Empty']?></option>
											</select>
										</td>
									</tr>
									<tr>
										<th><?=$lng['Time registration']?></th>
										<td>
											<select name="time_reg" style="width:100%">
												<? foreach($noyes01 as $k=>$v){
														echo '<option ';
														if($data['time_reg']==$k){echo 'selected';}
														echo ' value="'.$k.'">'.$v.'</option>';
												} ?>
											</select>
										</td>
									</tr>
									<tr>
										<th><?=$lng['Take selfie']?></th>
										<td>
											<select name="selfie" style="width:100%">
												<? foreach($noyes01 as $k=>$v){
														echo '<option ';
														if($data['selfie']==$k){echo 'selected';}
														echo ' value="'.$k.'">'.$v.'</option>';
												} ?>
											</select>
										</td>
									</tr>
									<tr>
										<th><?=$lng['Anual leave days']?></th>
										<td>
											<input class="sel numeric" type="text" name="leeve" placeholder="__" value="<?=$data['leeve']?>">
										</td>
									</tr>
									<tr>
										<th><?=$lng['Payment type']?></th>
										<td>
											<select name="pay_type" style="width:100%">
												<option selected value="cash"><?=$lng['Cash']?></option>
											</select>
										</td>
									</tr>
								</tbody>
							</table>
						</div>
					</div>
					
					<div class="tab-content-right">
						<div style="overflow-x:auto">
							<table class="basicTable inputs" border="0">
								<thead>
									<tr style="line-height:100%">
										<th colspan="2"><?=$lng['Pension Fund (PSF) / Provident Fund (PVF)']?></th>
									</tr>
								</thead>
								<tbody>
									<tr>
										<th><?=$lng['Calculate PSF']?></th>
										<td>
											<select name="calc_psf" style="width:100%">
												<? foreach($noyes01 as $k=>$v){
														echo '<option ';
														if($data['calc_psf']==$k){echo 'selected';}
														echo ' value="'.$k.'">'.$v.'</option>';
												} ?>
											</select>
										</td>
									</tr>
									<tr>
										<th><?=$lng['PSF rate employee']?></th>
										<td><input class="sel numeric" type="text" name="psf_rate_emp" placeholder="__" value="<?=$data['psf_rate_emp']?>"></td>
									</tr>
									<tr>
										<th><?=$lng['PSF rate employer']?></th>
										<td><input class="sel numeric" type="text" name="psf_rate_com" placeholder="__" value="<?=$data['psf_rate_com']?>"></td>
									</tr>
									<tr>
										<th><?=$lng['Calculate PVF']?></th>
										<td>
											<select name="calc_pvf" style="width:100%">
												<? foreach($noyes01 as $k=>$v){
														echo '<option ';
														if($data['calc_pvf']==$k){echo 'selected';}
														echo ' value="'.$k.'">'.$v.'</option>';
												} ?>
											</select>
										</td>
									</tr>
									<tr>
										<th><?=$lng['PVF rate employee']?></th>
										<td><input class="sel numeric" type="text" name="pvf_rate_emp" placeholder="__" value="<?=$data['pvf_rate_emp']?>"></td>
									</tr>
									<tr>
										<th><?=$lng['PVF rate employer']?></th>
										<td><input class="sel numeric" type="text" name="pvf_rate_com" placeholder="__" value="<?=$data['pvf_rate_com']?>"></td>
									</tr>
									<tr><td style="line-height:10px">&nbsp;</td></tr>
								</tbody>
								<thead>
									<tr style="line-height:100%">
										<th colspan="2"><?=$lng['Financial / Tax']?></th>
									</tr>
								</thead>
								<tbody>
									<tr>
										<th><?=$lng['Tax calculation method']?></th>
										<td>
											<select name="calc_method" style="width:100%">
												<option <? if($data['calc_method'] == 'cam'){echo "selected";} ?> value="cam"><?=$lng['Calculate in Advance Method']?> (CAM)</option>
												<option <? if($data['calc_method'] == 'acm'){echo "selected";} ?> value="acm"><?=$lng['Accumulative Calculation Method']?> (ACM)</option>
												<option <? if($data['calc_method'] == 'ytd'){echo "selected";} ?> value="ytd"><?=$lng['Year To Date']?> (YTD)</option>
											</select>
										</td>
									</tr>
									<tr>
										<th><?=$lng['Calculate Tax']?></th>
										<td>
											<select name="calc_tax" style="width:100%">
												<option <? if($data['calc_tax'] == '1'){echo 'selected';}?> value="1"><?=$lng['PND']?> 1</option>
												<option <? if($data['calc_tax'] == '3'){echo 'selected';}?> value="3"><?=$lng['PND']?> 3</option>
												<option <? if($data['calc_tax'] == '0'){echo 'selected';}?> value="0"><?=$lng['no Tax']?></option>
											</select>
										</td>
									</tr>
									<tr>
										<th><?=$lng['Calculate SSO']?></th>
										<td>
											<select name="calc_sso" style="width:100%">
												<? foreach($noyes01 as $k=>$v){
														echo '<option ';
														if($data['calc_sso']==$k){echo 'selected';}
														echo ' value="'.$k.'">'.$v.'</option>';
												} ?>
											</select>
										</td>
									</tr>
									<tr>
										<th><?=$lng['Contract type']?></th>
										<td>
											<select name="contract_type" style="width:100%">
												<option <? if($data['contract_type'] == 'month'){echo 'selected';}?> value="month"><?=$lng['Monthly wage']?></option>
												<option <? if($data['contract_type'] == 'day'){echo 'selected';}?> value="day"><?=$lng['Daily wage']?></option>
											</select>
										</td>
									</tr>
									<tr>
										<th><?=$lng['Calculation base']?></th>
										<td>
											<select name="calc_base" style="width:100%">
												<option <? if($data['calc_base'] == 'gross'){echo 'selected';}?> value="gross"><?=$lng['Gross amount']?></option>
												<option <? if($data['calc_base'] == 'net'){echo 'selected';}?> value="net"><?=$lng['Net amount']?></option>
											</select>
										</td>
									</tr>
									<tr>
										<th><?=$lng['Base OT rate']?></th>
										<td>
											<select name="base_ot_rate" style="width:100%">
												<option <? if($data['base_ot_rate'] == 'cal'){echo 'selected';}?> value="cal"><?=$lng['Calculated']?></option>
												<option <? if($data['base_ot_rate'] == 'fix'){echo 'selected';}?> value="fix"><?=$lng['Fixed']?></option>
											</select>
										</td>
									</tr>
									<tr>
										<th><?=$lng['OT rate']?></th>
										<td><input class="sel numeric" type="text" name="ot_rate" placeholder="__" value="<?=$data['ot_rate']?>"></td>
									</tr>
								</tbody>
							</table>
							<div style="height:10px"></div>
							<button class="btn btn-primary btn-fr" id="submitBtn" type="submit"><i class="fa fa-save"></i>&nbsp;&nbsp;<?=$lng['Update']?></button>
				

						</div>
					</div>
					
				</div>
			
			</div>
			
			
	</form>
	
<script>
	
$(document).ready(function() {
			
	$('.xdatepick').datepicker({
		format: "dd-mm-yyyy",
		autoclose: true,
		inline: true,
		orientation: 'auto bottom',
		language: lang,
		todayHighlight: true,
		startView: 'year',
		//startDate : startYear,
		//endDate   : endYear
	})

	$('.tend').on('change', function(){
		var m = $(this).data('m')+1;
		var parts = this.value.split('-');
		var d = parts[2]+'-'+parts[1]+'-'+parts[0]; 				
		var today = moment(d);
		var tomorrow = moment(today).add(1, 'days').format('DD-MM-YYYY');
		if(m <= 12){
			$('.tstart'+m).val(tomorrow);
		}
	})
	$('.lend').on('change', function(){
		var m = $(this).data('m')+1;
		var parts = this.value.split('-');
		var d = parts[2]+'-'+parts[1]+'-'+parts[0]; 				
		var today = moment(d);
		var tomorrow = moment(today).add(1, 'days').format('DD-MM-YYYY');
		if(m <= 12){
			$('.lstart'+m).val(tomorrow);
		}
	})
	$('.pend').on('change', function(){
		var m = $(this).data('m')+1;
		var parts = this.value.split('-');
		var d = parts[2]+'-'+parts[1]+'-'+parts[0]; 				
		var today = moment(d);
		var tomorrow = moment(today).add(1, 'days').format('DD-MM-YYYY');
		if(m <= 12){
			$('.pstart'+m).val(tomorrow);
		}
	})

	$("#systemForm").submit(function(e){ 
		e.preventDefault();
		$("#submitBtn i").removeClass('fa-save').addClass('fa-refresh fa-spin');
		var formData = $(this).serialize();
		$.ajax({
			url: "ajax/update_employee_defaults.php",
			type: 'POST',
			data: formData,
			success: function(result){
				//$('#dump').html(result); return false;
				$("#submitBtn").removeClass('flash');
				$("#sAlert").fadeOut(200);
				if(result == 'success'){
					$("body").overhang({
						type: "success",
						message: '<i class="fa fa-check"></i>&nbsp;&nbsp;Data updated successfuly',
						duration: 2,
					})
					//setTimeout(function(){location.reload();},2000);
				}else{
					$("body").overhang({
						type: "error",
						message: '<i class="fa fa-exclamation-triangle"></i>&nbsp;&nbsp;<?=$lng['Error']?> : '+result,
						duration: 4,
					})
				}
				setTimeout(function(){$("#submitBtn i").removeClass('fa-refresh fa-spin').addClass('fa-save');},500);
			},
			error:function (xhr, ajaxOptions, thrownError){
				$("body").overhang({
					type: "error",
					message: '<i class="fa fa-exclamation-triangle"></i>&nbsp;&nbsp;<?=$lng['Sorry but someting went wrong']?> <b><?=$lng['Error']?></b> : '+thrownError,
					duration: 4,
				})
			}
		});
	});
	
	var activeTabSys = localStorage.getItem('activeTabSys');
	if(activeTabSys){
		$('.nav-link[href="' + activeTabSys + '"]').tab('show');
	}else{
		$('.nav-link[href="#tab_system"]').tab('show');
	}
	$('a[data-toggle="tab"]').on('shown.bs.tab', function(e) {
		localStorage.setItem('activeTabSys', $(e.target).attr('href'));
	});
	
	$('input, textarea').on('keyup', function (e) {
		$("#submitBtn").addClass('flash');
		$("#sAlert").fadeIn(200);
		//alert('click')
	});
	$('select, .checkbox').on('change', function (e) {
		$("#submitBtn").addClass('flash');
		$("#sAlert").fadeIn(200);
		//alert('click')
	});
			

});

</script>	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
