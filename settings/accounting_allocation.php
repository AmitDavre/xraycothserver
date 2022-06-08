<?
	$fix_allow = unserialize($sys_settings['fix_allow']);
	$var_allow = unserialize($sys_settings['var_allow']);
	//var_dump($fix_allow);
	//var_dump($var_allow);

	$res = $dbx->query("SELECT * FROM rego_default_settings");
	if($row = $res->fetch_assoc()){
		$allocations = unserialize($row['allocations']);
	}
	//var_dump($allocations); //exit;
	
	$data = array();
	if($res = $dbc->query("SELECT account_allocations FROM ".$cid."_sys_settings")){
		if($row = $res->fetch_assoc()){
			$data = unserialize($row['account_allocations']);
		}
	}
	//var_dump($data);
	if(isset($_GET['def']) && $allocations){
		foreach($allocations as $key=>$val){
			foreach($val as $kk=>$vv){
				foreach($vv as $k=>$v){
					if(substr($k,0,3) == 'fix'){
						foreach($fix_allow as $fix=>$all){
							if($all['apply'] == 1){
								$data[$key]['direct']['fix_allow_'.$fix] = $allocations[$key]['direct']['fix_allow'];
								$data[$key]['indirect']['fix_allow_'.$fix] = $allocations[$key]['indirect']['fix_allow'];
							}
						}
					}else if(substr($k,0,3) == 'var'){
						foreach($var_allow as $var=>$all){
							if($all['apply'] == 1){
								$data[$key]['direct']['var_allow_'.$var] = $allocations[$key]['direct']['var_allow'];
								$data[$key]['indirect']['var_allow_'.$var] = $allocations[$key]['indirect']['var_allow'];
							}
						}
						//$data[$key]['direct'][$k] = $allocations[$key]['direct']['var_allow'];
						//$data[$key]['indirect'][$k] = $allocations[$key]['indirect']['var_allow'];
					}else{
						$data[$key][$kk][$k] = $allocations[$key][$kk][$k];
					}
				}
			}
		}
	}
	//var_dump($data); exit;

	$account_codes = unserialize($sys_settings['account_codes']);
	
	$debet = array();
	$debet['salary'] = $lng['Basic salary'];
	$debet['total_otb'] = $lng['Overtime'];
	foreach($fix_allow as $k=>$v){
		if($v['apply'] == 1){$debet['fix_allow_'.$k] = $lng['Fixed'].' '.$v[$lang];}
		//$debet['fix_allow_'.$k] = $lng['Fixed'].' '.$v[$lang];
	}
	foreach($var_allow as $k=>$v){
		if($v['apply'] == 1){$debet['var_allow_'.$k] = $lng['Variable'].' '.$v[$lang];}
		//$debet['var_allow_'.$k] = $lng['Variable'].' '.$v[$lang];
	}
	//$debet['bonus'] = $lng['Bonus'];
	$debet['other_income'] = $lng['Other income'];
	$debet['severance'] = $lng['Severance'];
	$debet['absence_b'] = $lng['Absence'];
	$debet['late_early_b'] = $lng['Late Early'];
	$debet['leave_wop_b'] = $lng['Leave WOP'];
	$debet['tot_deduct_after'] = 'Deductions after';//$lng['Uniform'];
	$debet['tot_deduct_before'] = 'Deductions before';//$lng['Other deduct'];
	$debet['pvf_employer'] = $lng['Provident fund'];
	$debet['social'] = $lng['SSO Employee'];
	
	$credit = array();
	$credit['net_income'] = $lng['Net salary cost'];
	$credit['tax_month'] = $lng['PND1'];
	$credit['social'] = $lng['SSO Employee'];
	$credit['social_com'] = $lng['SSO Employer'];
	$credit['pvf_employee'] = $lng['PVF Employee'];
	$credit['pvf_employer'] = $lng['PVF Employer'];
	
	//var_dump($account_codes);
	
?>

	<h2 style="padding-right:60px"><i class="fa fa-file-code-o"></i>&nbsp; <?=$lng['Accounting Allocations']?>
		<span style="display:none; font-style:italic; color:#b00; padding-left:30px" id="sAlert"><i class="fa fa-exclamation-triangle fa-mr"></i><?=$lng['Data is not updated to last changes made']?></span>
	</h2>		
         
	<form id="accountForm" style="height:calc(100% - 40px)">
		<div class="main">
			<div style="padding:0 0 0 20px" id="dump"></div>
				<button onClick="location.href='index.php?mn=606&def'" class="btn btn-primary btn-fr" type="button"><i class="fa fa-cog"></i>&nbsp;&nbsp;<?=$lng['Get defaults']?></button>
				<button class="btn btn-primary btn-fr" id="submitbtn" type="submit"><i class="fa fa-save"></i>&nbsp;&nbsp;<?=$lng['Update']?></button>
				<div style="clear:both"></div>
				<? //=var_dump($data)?>
				<table class="basicTable inputs" xid="accounting_table" border="0">
					<thead>
						<tr style="line-height:100%">
							<th colspan="3" class="tac" style="color:#a00; font-size:14px"><?=$lng['Profit & Loss Account']?></th>
						</tr>
						<tr style="line-height:100%">
							<th style="width:20%"><?=$lng['Salary element']?></th>
							<th><?=$lng['Direct']?></th>
							<th><?=$lng['Indirect']?></th>
						</tr>
					</thead>
					<tbody>
						<? foreach($debet as $key=>$val){ ?>
						<tr>
							<td style="padding:4px 10px !important"><b><?=$val?></b></td>
							<td>
								<select name="debet[direct][<?=$key?>]" style="width:auto; min-width:100%">
									<option selected value="0"><?=$lng['Select']?></option>
								<? if($account_codes){ foreach($account_codes as $k=>$v){ ?>
									<option <? if(isset($data['debet']['direct'][$key]) && $data['debet']['direct'][$key] == $v['code']){echo 'selected';}?> value="<?=$v['code']?>"><?=$v['code'].' '.$v[$lang]?></option>
								<? } } ?>
								</select>
							</td>
							<td>
								<select name="debet[indirect][<?=$key?>]" style="width:auto; min-width:100%">
									<option selected value="0"><?=$lng['Select']?></option>
								<? if($account_codes){ foreach($account_codes as $k=>$v){ ?>
									<option <? if(isset($data['debet']['indirect'][$key]) && $data['debet']['indirect'][$key] == $v['code']){echo 'selected';}?> value="<?=$v['code']?>"><?=$v['code'].' '.$v[$lang]?></option>
								<? } } ?>
								</select>
							</td>
						</tr>
						<? } ?>
						
					</tbody>
				</table>
				<div style="height:10px"></div>
			
				<table class="basicTable inputs" xid="accounting_table" border="0">
					<thead>
						<tr style="line-height:100%">
							<th colspan="3" class="tac" style="color:#a00; font-size:14px"><?=$lng['Balance sheet Payments']?></th>
						</tr>
						<tr style="line-height:100%">
							<th style="width:20%"><?=$lng['Salary element']?></th>
							<th><?=$lng['Direct']?></th>
							<th><?=$lng['Indirect']?></th>
						</tr>
					</thead>
					<tbody>
						<? foreach($credit as $key=>$val){ ?>
						<tr>
							<td style="padding:4px 10px !important"><b><?=$val?></b></td>
							<td>
								<select name="credit[direct][<?=$key?>]" style="width:auto; min-width:100%">
									<option selected value="0"><?=$lng['Select']?></option>
								<? if($account_codes){ foreach($account_codes as $k=>$v){ ?>
									<option <? if(isset($data['credit']['direct'][$key]) && $data['credit']['direct'][$key] == $v['code']){echo 'selected';}?> value="<?=$v['code']?>"><?=$v['code'].' '.$v[$lang]?></option>
								<? } } ?>
								</select>
							</td>
							<td>
								<select name="credit[indirect][<?=$key?>]" style="width:auto; min-width:100%">
									<option selected value="0"><?=$lng['Select']?></option>
								<? if($account_codes){ foreach($account_codes as $k=>$v){ ?>
									<option <? if(isset($data['credit']['indirect'][$key]) && $data['credit']['indirect'][$key] == $v['code']){echo 'selected';}?> value="<?=$v['code']?>"><?=$v['code'].' '.$v[$lang]?></option>
								<? } } ?>
								</select>
							</td>
						</tr>
						<? } ?>
					</tbody>
				</table>
				<div style="height:10px"></div>
				
		</div>
	</form>
	
<script>
	
$(document).ready(function() {
	
	$("#accountForm").submit(function(e){ 
		e.preventDefault();
		$("#submitbtn i").removeClass('fa-save').addClass('fa-refresh fa-spin');
		var data = $(this).serialize();
		//alert(data)
		$.ajax({
			url: ROOT+"settings/ajax/update_account_settings.php",
			type: 'POST',
			data: data,
			success: function(result){
				//$('#dump').html(result); return false;
				$("#submitbtn").removeClass('flash');
				$("#sAlert").fadeOut(200);
				if(result == 'success'){
					$("body").overhang({
						type: "success",
						message: '<i class="fa fa-check"></i>&nbsp;&nbsp;<?=$lng['Data updated successfuly']?>',
						duration: 2,
					})
				}else{
					$("body").overhang({
						type: "error",
						message: '<i class="fa fa-exclamation-triangle"></i>&nbsp;&nbsp;<?=$lng['Error']?> : '+result,
						duration: 4,
						//closeConfirm: true
					})
				}
				setTimeout(function(){$("#submitbtn i").removeClass('fa-refresh fa-spin').addClass('fa-save');},500);
			},
			error:function (xhr, ajaxOptions, thrownError){
				$("body").overhang({
					type: "error",
					message: '<i class="fa fa-exclamation-triangle"></i>&nbsp;&nbsp;<?=$lng['Sorry but someting went wrong']?> <b><?=$lng['Error']?></b> : '+thrownError,
					duration: 8,
					closeConfirm: "true",
				})
				setTimeout(function(){$("#submitbtn i").removeClass('fa-refresh fa-spin').addClass('fa-save');},500);
			}
		});
	});
	
	$('select').on('change', function(e){
		//alert()
		$("#submitbtn").addClass('flash');
		$("#sAlert").fadeIn(200);
	});
			
});

</script>	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
