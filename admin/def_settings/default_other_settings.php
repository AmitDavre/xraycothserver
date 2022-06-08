<?
	$disabled = '';
	$readonly = 'readonly';
	$nofocus = 'nofocus';
	//if($_SESSION['RGadmin']['access']['settings']['payroll'] == 0){$disabled = 'disabled';}
	//var_dump(unserialize('a:2:{s:2:"en";a:3:{s:2:"NP";s:11:"no Position";s:3:"DES";s:8:"Designer";s:3:"DEV";s:10:"Develloper";}s:2:"th";a:3:{s:2:"NP";s:36:"ไม่มีตำแหน่ง";s:3:"DES";s:9:"-Designer";s:3:"DEV";s:11:"-Develloper";}}'));


	$settings = array();
	if($res = $dba->query("SELECT * FROM rego_default_settings")){
		$settings = $res->fetch_assoc();
		$settings['bank_codes'] = unserialize($settings['bank_codes']);
	}
	//var_dump($settings);
	
	$acc_count = 0;
	$account_codes = unserialize($settings['account_codes']);
	if($account_codes){
		$acc_count = count($account_codes);
	}
	//var_dump($account_codes);
	
	$allocations = unserialize($settings['allocations']);
	//var_dump($allocations);

	$debet = array();
	
	$debet['salary'] = $lng['Basic salary'];
	$debet['total_otb'] = $lng['Overtime'];
	$debet['fix_allow'] = 'Fixed allowances';//$lng['Overtime'];
	$debet['var_allow'] = 'Variable allowances';//$lng['Overtime'];
/*	foreach($fix_allow as $k=>$v){
		if($v['apply'] == 1){$debet['fix_allow_'.$k] = 'Fix. allow '.$v[$lang];}
	}
	foreach($var_allow as $k=>$v){
		if($v['apply'] == 1){$debet['var_allow_'.$k] = 'Var. allow '.$v[$lang];}
	}
*/	
	//$debet['bonus'] = $lng['Bonus'];
	$debet['other_income'] = $lng['Other income'];
	$debet['severance'] = $lng['Severance'];
	//$debet['xxx'] = $lng['xxx'];
	//$debet['xxx'] = $lng['xxx'];
	//$debet['paid_leave'] = $lng['xxx'];
	$debet['absence_b'] = $lng['Absence'];
	$debet['late_early_b'] = $lng['Late Early'];
	$debet['leave_wop_b'] = $lng['Leave WOP'];
	$debet['tot_deduct_after'] = 'Deductions after';//$lng['Uniform'];
	$debet['tot_deduct_before'] = 'Deductions before';//$lng['Other deduct'];
	$debet['pvf_employer'] = $lng['Provident fund'];
	$debet['social'] = $lng['SSO Employer'];
	
	$credit['net_income'] = $lng['Net salary cost'];
	$credit['tax_month'] = $lng['PND1'];
	$credit['social'] = $lng['SSO Employee'];
	$credit['social_com'] = $lng['SSO Employer'];
	$credit['pvf_employee'] = $lng['PVF Employee'];
	$credit['pvf_employer'] = $lng['PVF Employer'];
	
	//var_dump($debet);
	
?>
 
<style>
	*:disabled {
		cursor:default !important;
	}
	table.basicTable td.info {
		color: #006699;
		font-style:italic;
	}
</style>
	
	
	<h2><i class="fa fa-cogs"></i>&nbsp;&nbsp;Other Default settings <span style="float:right; display:none; font-style:italic; color:#b00" id="sAlert"><?=$lng['Data is not updated to last changes made']?></span></h2>
	
	<div class="main" style="overflow:hidden">
		<div style="padding:0 0 0 20px" id="dump"></div>
		
		<form id="otherForm" style="height:100%">
	
			<ul class="nav nav-tabs" id="myTab">
				<li class="nav-item"><a class="nav-link" data-target="#tab_bank" data-toggle="tab"><?=$lng['Bank codes']?></a></li>
				<li class="nav-item"><a class="nav-link" data-target="#tab_accounting" data-toggle="tab"><?=$lng['Accounting Codes']?></a></li>
				<li class="nav-item"><a class="nav-link" data-target="#tab_allocations" data-toggle="tab"><?=$lng['Accounting Allocations']?></a></li>
			</ul>
		
			<div style="position:absolute; top:15px; right:15px;">
					<button class="btn btn-primary" id="submitbtn" type="submit"><i class="fa fa-save"></i>&nbsp;&nbsp;<?=$lng['Update']?></button>
			</div>
	
			<div class="tab-content" style="height:calc(100% - 40px)">
				
				<div class="tab-pane" id="tab_bank" style="height:100%">
					<div style="height:100%; overflow-y:auto">
					<table class="basicTable inputs" border="0" id="fixTable">
						<thead>
							<tr>
								<th style="width:1px">Code</th>
								<th class="tac" style="width:1px"><i class="fa fa-check-square-o fa-lg"></i></th>
								<th style="width:50%"><?=$lng['Bank name']?> (<?=$lng['Thai']?>)</th>
								<th style="width:50%"><?=$lng['Bank name']?> (<?=$lng['English']?>)</th>
							</tr>
						</thead>
						<tbody>
						<?	if($settings['bank_codes']){ foreach($settings['bank_codes'] as $k=>$v){ ?>
							<tr>
								<td class="tac" style="border-right:1px #ddd solid">
									<b><input class="nofocus" readonly placeholder="__" name="bank_codes[<?=$k?>][code]" type="text" value="<?=$v['code']?>" /></b>
								</td>
								<td class="tac" style="border-right:1px #ddd solid">
									<input name="bank_codes[<?=$k?>][apply]" type="hidden" value="0" />
									<label><input <? if($v['apply'] == 1){echo 'checked';} ?> name="bank_codes[<?=$k?>][apply]" type="checkbox" value="1" class="checkbox notxt" /><span></span></label>
								</td>
								<td style="border-right:1px #ddd solid">
									<input placeholder="__" name="bank_codes[<?=$k?>][th]" type="text" value="<?=$v['th']?>" />
								</td>
								<td>
									<input placeholder="__" name="bank_codes[<?=$k?>][en]" type="text" value="<?=$v['en']?>" />
								</td>
							</tr>
						<? }} ?>
						</tbody>
					</table>
					</div>
				</div>
	
				<div class="tab-pane" id="tab_accounting" style="height:100%">
					<div style="height:100%; overflow-y:auto">
					<table class="basicTable inputs" id="accounting_table" border="0">
						<thead>
							<tr>
								<th style="width:10%"><?=$lng['Code']?></th>
								<th style="width:25%"><?=$lng['Account name Thai']?></th>
								<th style="width:25%"><?=$lng['Account name English']?></th>
								<th style="width:40%"></th>
								<th><i class="fa fa-trash fa-lg"></i></th>
							</tr>
						</thead>
						<tbody>
						<? if($account_codes){ foreach($account_codes as $k=>$v){ ?>
							<tr>
								<td><b><input name="account_codes[<?=$k?>][code]" type="text" value="<?=$v['code']?>" /></b></td>
								<td><input name="account_codes[<?=$k?>][th]" type="text" value="<?=$v['th']?>" /></td>
								<td><input name="account_codes[<?=$k?>][en]" type="text" value="<?=$v['en']?>" /></td>
								<td></td>
								<td class="tac"><a class="delCode"><i class="fa fa-trash fa-lg"></i></a></td>
							</tr>
						<? } } ?>
						</tbody>
					</table>
					<div style="height:10px"></div>
					<button class="btn btn-primary btn-xs" type="button" id="addAccountCode"><i class="fa fa-plus"></i>&nbsp;&nbsp;<?=$lng['Add row']?></button>
							
					</div>
				</div>
	
				<div class="tab-pane" id="tab_allocations" style="height:100%">
					<div style="height:100%; overflow-y:auto">
					<p style="font-weight:600; margin:0 0 3px 2px; color:#a00"><?=$lng['Please reload this page if you have changed Accounting Codes']?></p>
					<table class="basicTable inputs" border="0" style="margin-bottom:10px">
						<thead>
							<tr style="line-height:100%">
								<th colspan="4" class="tac" style="color:#a00; font-size:14px"><?=$lng['Profit & Loss Account']?></th>
							</tr>
							<tr style="line-height:100%">
								<th style="width:20%"><?=$lng['Salary element']?></th>
								<th><?=$lng['Direct']?></th>
								<th><?=$lng['Indirect']?></th>
								<th style="width:40%"></th>
							</tr>
						</thead>
						<tbody>
							<? foreach($debet as $key=>$val){ ?>
							<tr>
								<td style="padding:4px 10px !important"><b><?=$val?></b></td>
								<td>
									<select name="allocations[debet][direct][<?=$key?>]" style="min-width:100%; width:auto">
										<option selected value="0"><?=$lng['Select']?></option>
									<? if($account_codes){ foreach($account_codes as $k=>$v){ ?>
										<option <? if(isset($allocations['debet']['direct'][$key]) && $allocations['debet']['direct'][$key] == $v['code']){echo 'selected';}?> value="<?=$v['code']?>"><?=$v['code'].' '.$v[$lang]?></option>
									<? } } ?>
									</select>
								</td>
								<td>
									<select name="allocations[debet][indirect][<?=$key?>]" style="min-width:100%; width:auto">
										<option selected value="0"><?=$lng['Select']?></option>
									<? if($account_codes){ foreach($account_codes as $k=>$v){ ?>
										<option <? if(isset($allocations['debet']['indirect'][$key]) && $allocations['debet']['indirect'][$key] == $v['code']){echo 'selected';}?> value="<?=$v['code']?>"><?=$v['code'].' '.$v[$lang]?></option>
									<? } } ?>
									</select>
								</td>
								<td></td>
							</tr>
							<? } ?>
						</tbody>
					</table>
							
					<table class="basicTable inputs" border="0">
						<thead>
							<tr style="line-height:100%">
								<th colspan="4" class="tac" style="color:#a00; font-size:14px"><?=$lng['Balance sheet Payments']?></th>
							</tr>
							<tr style="line-height:100%">
								<th style="width:20%"><?=$lng['Salary element']?></th>
								<th><?=$lng['Direct']?></th>
								<th><?=$lng['Indirect']?></th>
								<th style="width:40%">&nbsp;</th>
							</tr>
						</thead>
						<tbody>
							<? foreach($credit as $key=>$val){ ?>
							<tr>
								<td style="padding:4px 10px !important"><b><?=$val?></b></td>
								<td>
									<select name="allocations[credit][direct][<?=$key?>]" style="min-width:100%; width:auto">
										<option selected value="0"><?=$lng['Select']?></option>
									<? if($account_codes){ foreach($account_codes as $k=>$v){ ?>
										<option <? if(isset($allocations['credit']['direct'][$key]) && $allocations['credit']['direct'][$key] == $v['code']){echo 'selected';}?> value="<?=$v['code']?>"><?=$v['code'].' '.$v[$lang]?></option>
									<? } } ?>
									</select>
								</td>
								<td>
									<select name="allocations[credit][indirect][<?=$key?>]" style="min-width:100%; width:auto">
										<option selected value="0"><?=$lng['Select']?></option>
									<? if($account_codes){ foreach($account_codes as $k=>$v){ ?>
										<option <? if(isset($allocations['credit']['indirect'][$key]) && $allocations['credit']['indirect'][$key] == $v['code']){echo 'selected';}?> value="<?=$v['code']?>"><?=$v['code'].' '.$v[$lang]?></option>
									<? } } ?>
									</select>
								</td>
								<td></td>
							</tr>
							<? } ?>
						</tbody>
					</table>
					
					</div>
				</div>
	
			</div>
		</form>
	</div>
	
	
<script>

	var height = window.innerHeight-265;
	
	$(document).ready(function() {
		
		var acc = <?=json_encode($acc_count + 1)?>;
		$("#addAccountCode").click(function(){
			var rowCount = $('#accounting_table tbody tr').length;
			var addrow = '<tr>'+
				'<td><b><input placeholder="Code" name="account_codes['+acc+'][code]" type="text" /></b></td>'+
				'<td><input placeholder="Account name Thai" name="account_codes['+acc+'][th]" type="text" /></td>'+
				'<td><input placeholder="Account name English" name="account_codes['+acc+'][en]" type="text" /></td>'+
				'<td></td>'+
				'<td class="tac"><a class="delCode"><i class="fa fa-trash fa-lg"></i></a></td>'+
			'</tr>';
			if(acc == 1 || rowCount == 0){
				$("#accounting_table tbody").html(addrow);
			}else{
				$("#accounting_table tr:last").after(addrow);
			}
			acc ++;
		});
		$(document).on('click', '.delCode', function (e) {
			$(this).closest('tr').remove();
			$("#sAlert").fadeIn(200);
			$("#submitbtn").addClass('flash');
		});
	
		$("#otherForm").submit(function(e){ 
			e.preventDefault();
			$("#submitbtn i").removeClass('fa-save').addClass('fa-refresh fa-spin');
			var formData = $(this).serialize();
			$.ajax({
				url: "def_settings/ajax/update_default_other_settings.php",
				type: 'POST',
				data: formData,
				success: function(result){
					//$('#dump').html(result); return false;
					if(result == 'success'){
						$("body").overhang({
							type: "success",
							message: '<i class="fa fa-check"></i>&nbsp;&nbsp;Data updated successfuly',
							duration: 2,
						})
					}else{
						$("body").overhang({
							type: "error",
							message: '<i class="fa fa-exclamation-triangle"></i>&nbsp;&nbsp;<?=$lng['Error']?> : '+result,
							duration: 4,
						})
					}
					setTimeout(function(){
						$("#submitbtn i").removeClass('fa-refresh fa-spin').addClass('fa-save');
						$("#submitbtn").removeClass('flash');
						$("#sAlert").fadeOut(200);
					},300);
				},
				error:function (xhr, ajaxOptions, thrownError){
					$("body").overhang({
						type: "error",
						message: '<i class="fa fa-exclamation-triangle"></i>&nbsp;&nbsp;<?=$lng['Sorry but someting went wrong']?> <b><?=$lng['Error']?></b> : '+thrownError,
						duration: 8,
						closeConfirm: "true",
					})
				}
			});
		});
		
		var activeTab = localStorage.getItem('activeTab11');
		$('a[data-toggle="tab"]').on('shown.bs.tab', function(e) {
			localStorage.setItem('activeTab11', $(e.target).data('target'));
		});
		if(activeTab){
			$('#myTab a[data-target="' + activeTab + '"]').tab('show');
		}else{
			$('#myTab a[data-target="#tab_bank"]').tab('show');
		}
		
		/*$('.autoheight').css('min-height', height);
		$(window).on('resize', function(){
			var height = window.innerHeight-265;
			$('.autoheight').css('min-height', height);
		});*/	
		
		setTimeout(function(){
			$('body').on('change', 'input, textarea, select', function (e) {
				$("#submitbtn").addClass('flash');
				$("#sAlert").fadeIn(200);
			});	
		},1000);
		
	});
	
	</script>	













