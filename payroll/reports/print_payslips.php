<?
	include(DIR.'files/arrays_'.$_SESSION['rego']['lang'].'.php');
	//if(!isset($_GET['sm'])){$_GET['sm'] = 0;}
	
	$emps = getEmployees($cid,0);
	$emp_id = key($emps);

	$emp_array = '[';
	foreach($emps as $k=>$v){
		$emp_array .= "{data:'".$k."',value:'".$k.' - '.$v['en_name']."'},";
	}
	$emp_array = substr($emp_array,0,-1);
	$emp_array .= ']';
	
	//var_dump($sys_settings); exit;
	//var_dump($_SESSION['rego']['report_id']);
	$payslip_field = unserialize($sys_settings['payslip_field']);
	
	$employee = '';
	$id = false;
	if(isset($_SESSION['rego']['report_id'])){
		$employee = $_SESSION['rego']['report_id'].' - '.$emps[$_SESSION['rego']['report_id']][$lang.'_name'];
		$id = true;
	}
	
	$data = array();
	$id = false;
	if(isset($_SESSION['rego']['report_id'])){
		$id = true;
		$sql = "SELECT * FROM ".$cid."_employees WHERE emp_id = '".$_SESSION['rego']['report_id']."'";
		if($res = $dbc->query($sql)){
			if($row = $res->fetch_assoc()){
				$data = $row;
			}
		}
	}

	include('inc_employee_year.php');
	//var_dump($data); exit;
?>	
<style>
	.A4form {
		width:100%;
		xmargin:10px 10px 10px 15px;
		background:#fff; 
		padding:20px; 
		box-shadow:0 0 10px rgba(0,0,0,0.4); 
		position:relative;
		min-height:0px;
	}
	.reportTable {
		width:100%;
		border-collapse:collapse;
		font-size:13px;
		margin:0 !important;
	}
	.reportTable b {
		font-weight:600;
		color:#039;
	}
	.reportTable thead th {
		padding:4px 8px;
		font-weight:600;
		text-align:center;
		font-size:13px;
		border-bottom:1px solid #bbb;
		background:#eee;
		white-space:nowrap;
	}
	.reportTable tbody th {
		padding:2px 8px;
		white-space:nowrap;
		font-weight:600;
		border:1px solid #eee;
		border-left:0;
		text-align:right;
	}
	.reportTable tbody td {
		white-space:nowrap;
	}
	.reportTable tbody td.bold {
		font-weight:600;
	}
	.reportTable tbody td:first-child {
		border-left:0;
	}
	.reportTable tbody td:last-child {
		border-right:0;
	}
	.reportTable thead th {
		background:#eee;
		color:#900;
		font-weight:600;
		border:1px solid #fff;
		border-bottom:1px solid #bbb;
		text-align:left;
	}
	#optionTable {
		margin-bottom:10px !important;
	}
	#optionTable tbody td {
		padding:5px 10px !important;
		border:1px solid #ddd;
	}
	#optionTable tbody td.nopad {
		padding:0 !important;
	}
	#optionTable tbody td:first-child {
		border-left:0;
	}
	#optionTable tbody td:last-child {
		border-right:0;
	}

</style>
	
	<h2><i class="fa fa-print"></i>&nbsp; <?=$lng['Print payslips']?></h2>		
	
	<div class="main" style="padding-top:15px; top:130px">
		<div style="padding:0 0 0 20px" id="dump"></div>
		
		<table border="0" width="100%" style="margin-bottom:10px">
			<tr>
				<td>
					<div class="searchFilter" style="width:180px; margin:0">
						<input placeholder="<?=$lng['Filter']?>" id="searchFilter" class="sFilter" type="text" />
						<button id="clearSearchbox" type="button" class="clearFilter btn btn-default btn-sm"><i class="fa fa-times"></i></button>
					</div>
				</td>
				<td style="padding-left:5px">
					<select id="MonFilter">
				<? foreach($months as $k=>$v){ ?>
						<option value="<?=$k?>"><?=$v?></option>
				<? } ?>
					</select>
				</td>
				<td style="padding-left:5px">
					<select id="groupFilter">
						<option value="">Staff & Management</option>
						<option value="s">Only Staff</option>
						<option value="m">Only Management</option>
					</select>
				</td>
				<td style="padding-left:5px">
					<select id="depFilter">
						<option value="">All departments</option>
				<? foreach($departments as $k=>$v){ ?>
						<option value="<?=$k?>"><?=$v[$lang]?></option>
				<? } ?>
					</select>
				</td>
				<td width="95%"></td>
				<td>
					<button id="printPayslips" type="button" class="btn btn-primary"><i class="fa fa-print"></i>&nbsp; <?=$lng['Print payslips']?></button>
				</td>
				<td style="padding-left:5px">
					<button id="printPayslipsArchive" type="button" class="btn btn-primary"><i class="fa fa-print"></i>&nbsp; <?=$lng['Print payslips & Archive']?></button>
				</td>
			</tr>
		</table>
		
		
		
		<div style="height:8px; clear:both"></div>
			
			<div class="A4form">
				<div style="overflow-x:hidden; width:100%">
				
				<div style="float:left; width:65%; padding-right:10px; border-right:1px solid #eee">
				<div id="showTable" style="display:none">
				<table id="payslipTable" class="reportTable" border="0">
					<thead>
						<tr>
							<th><?=$lng['Emp. ID']?></th>
							<th><?=$lng['Name']?></th>
							<!--<th><?=$lng['Entity']?></th>
							<th><?=$lng['Branch']?></th>
							<th><?=$lng['Division']?></th>-->
							<th data-visible="false"></th>
							<th data-visible="false"></th>
							<th><?=$lng['Department']?></th>
							<th><?=$lng['Position']?></th>
							<th data-visible="false"></th>
							<th><?=$lng['Earnings']?></th>
							<th><?=$lng['Deductions']?></th>
							<th><?=$lng['Net pay']?></th>
							<th><i class="fa fa-print fa-lg"></i></th>
						</tr>
					</thead>
					<tbody>
					
					</tbody>
				</table>
				</div>
				</div>
				
				<div style="float:right; width:35%; padding-left:10px">
					<form id="payslipOptions">
					<table id="optionTable" class="reportTable" border="0">
						<thead>
							<tr>
								<th colspan="2" style="line-height:24px">Options</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td>Template</td>
								<td class="nopad">
									<select class="options" name="payslip_template" style="width:100%; border:0">
										<option <? if($sys_settings['payslip_template'] == 'la4'){echo "selected ";}?> value="la4"><?=$lng['Laser A4 template']?></option>
										<option <? if($sys_settings['payslip_template'] == 'la5'){echo "selected ";}?> value="la5">Laser A5 template<? //=$lng['Laser A5 template']?></option>
										<option <? if($sys_settings['payslip_template'] == 'tme'){echo "selected ";}?> value="tme"><?=$lng['Thai matrix template (A5 Empty)']?></option>
										<option <? if($sys_settings['payslip_template'] == 'tmp'){echo "selected ";}?> value="tmp"><?=$lng['Thai matrix template (A5 Preprinted)']?></option>
									</select>
								</td>
							</tr>
							<tr>
								<td>Show rate</td>
								<td class="nopad">
									<select class="options" name="payslip_rate" style="width:100%; border:0">
										<option <? if($sys_settings['payslip_rate'] == 'em'){echo "selected ";}?> value="em"><?=$lng['Empty']?></option>
										<option <? if($sys_settings['payslip_rate'] == 'dr'){echo "selected ";}?> value="dr"><?=$lng['Day rate']?></option>
										<option <? if($sys_settings['payslip_rate'] == 'hr'){echo "selected ";}?> value="hr"><?=$lng['Hour rate']?></option>
									</select>
								</td>
							</tr>
							<tr>
								<td valign="top">Payslip YTD fields<? //=$lng['Payslip fields']?></td>
								<td style="line-height:22px; padding-bottom:4px !important">
									<input type="hidden" name="payslip_field[ytd1]" value="0">
									<label><input <? if($payslip_field['ytd1']){echo 'checked';} ?> name="payslip_field[ytd1]" type="checkbox" value="1" class="options checkbox style-0" /><span><?=$lng['YTD. Income']?></span></label><br>
									<input type="hidden" name="payslip_field[ytd2]" value="0">
									<label><input <? if($payslip_field['ytd2']){echo 'checked';} ?> name="payslip_field[ytd2]" type="checkbox" value="1" class="options checkbox style-0" /><span><?=$lng['YTD. Tax']?></span></label><br>
									<input type="hidden" name="payslip_field[ytd3]" value="0">
									<label><input <? if($payslip_field['ytd3']){echo 'checked';} ?> name="payslip_field[ytd3]" type="checkbox" value="1" class="options checkbox style-0" /><span><?=$lng['YTD. Prov. Fund']?></span></label><br>
									<input type="hidden" name="payslip_field[ytd4]" value="0">
									<label><input <? if($payslip_field['ytd4']){echo 'checked';} ?> name="payslip_field[ytd4]" type="checkbox" value="1" class="options checkbox style-0" /><span><?=$lng['YTD. Social SF']?></span></label><br>
									<input type="hidden" name="payslip_field[ytd5]" value="0">
									<label><input <? if($payslip_field['ytd5']){echo 'checked';} ?> name="payslip_field[ytd5]" type="checkbox" value="1" class="options checkbox style-0" /><span><?=$lng['YTD. Other allowance']?></span></label>
								</td>
							</tr>
							<tr>
								<td>Show address</td>
								<td>
									<input type="hidden" name="show_address" value="0">
									<label><input <? if($sys_settings['show_address']){echo 'checked';} ?> name="show_address" type="checkbox" value="1" class="options checkbox style-0" /><span></span></label>
								</td>
							</tr>
							<tr>
								<td>Show Bank info</td>
								<td>
									<input type="hidden" name="show_bankinfo" value="0">
									<label><input <? if($sys_settings['show_bankinfo']){echo 'checked';} ?> name="show_bankinfo" type="checkbox" value="1" class="options checkbox style-0" /><span></span></label>
								</td>
							</tr>
							<tr>
								<td>Show position</td>
								<td>
									<input type="hidden" name="show_position" value="0">
									<label><input <? if($sys_settings['show_position']){echo 'checked';} ?> name="show_position" type="checkbox" value="1" class="options checkbox style-0" /><span></span></label>
								</td>
							</tr>
							<!-- <tr>
								<td>Show department</td>
								<td>
									<input type="hidden" name="show_department" value="0">
									<label><input <? if($sys_settings['show_department']){echo 'checked';} ?> name="show_department" type="checkbox" value="1" class="options checkbox style-0" /><span></span></label>
								</td>
							</tr> -->
							<tr>
								<td>Employee data</td>
								<td>
									<input type="hidden" name="payslip_field[show_empdata]" value="0">
									<label><input <? if($payslip_field['show_empdata']){echo 'checked';} ?> name="payslip_field[show_empdata]" type="checkbox" value="1" class="options checkbox style-0" /><span></span></label>
								</td>
							</tr>
						</tbody>
					</table>
					</form>
					
				</div>
				</div>
	
			</div>
			
	</div>
	
	<!-- PAGE RELATED PLUGIN(S) -->
	<!--<script type="text/javascript" src="../assets/js/jquery.autocomplete.js"></script>-->

<script type="text/javascript">
	
	$(document).ready(function() {
		
		
		$("#printPayslips").on('click', function(){
			window.open('../print/print_payslips.php?m=<?=$_SESSION['rego']['cur_month']?>', '_blank');
		});
		$("#printPayslipsArchive").on('click', function(){
			window.open('../print/print_payslips.php?m=<?=$_SESSION['rego']['cur_month']?>&a', '_blank');
		});
		
		
		$(document).on("click", ".empPrint", function(e) {
			var id = $(this).data('id');
			window.open('../print/print_payslip_prid.php?id='+id,'_blank');
			//alert(id);
		})
		$(document).on("change", ".options", function(e) {
			$("#payslipOptions").submit();
		})
		$("#payslipOptions").submit(function(e){ 
			e.preventDefault();
			var formData = $(this).serialize();
			$.ajax({
				url: "ajax/update_payslip_options.php",
				type: 'POST',
				data: formData,
				success: function(result){
					//$('#dump').html(result); return false;
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
		 
		 
		 
	 var dtable = $('#payslipTable').DataTable({
		scrollY:        true,//scrY,//heights-288,
		scrollX:        true,
		scrollCollapse: false,
		fixedColumns:   true,
		lengthChange:  	false,
		searching: 		true,
		ordering: 		true,
		paging: 		false,
		pagingType: 'full_numbers',
		//pageLength: 	drows,
		filter: 		true,
		info: 			false,
		autoWidth:		false,
		processing: 	false,
		serverSide: 	true,
		
		<?=$dtable_lang?>
		ajax: {
			url: "ajax/server_get_payslip_data.php",
			type: "POST",
			"data": function(d){
				//d.filter = $('#taxFilter').val();
			}
		},
		columnDefs: [
			{targets: [7,8,9], "class": 'tar bold'},
			{targets: [1], width: '80%'},
		],
		initComplete : function( settings, json ) {
			$('#showTable').fadeIn(200);
			dtable.columns.adjust().draw();
		}
		});
		$("#searchFilter").keyup(function() {
			dtable.search(this.value).draw();
		});		
		$(document).on("click", "#clearSearchbox", function(e) {
			$('#searchFilter').val('');
			dtable.search('').draw();
		})
		$(document).on("change", "#depFilter", function(e) {
			dtable.column(3).search(this.value).draw();
		})
		$(document).on("change", "#MonFilter", function(e) {	
			dtable.column(6).search(this.value).draw();
		})
		$(document).on("change", "#groupFilter", function(e) {
			dtable.column(2).search(this.value).draw();
		})
		
		$('#exportExcel').on('click', function(){
			window.location.href = '<?=ROOT?>reports/excel/export_employee_year_excel.php';
		})

	});
	
</script>

	
	
	
	
	
	
	
	
	
	
	
	
	
	

