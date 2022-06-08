<?

	/*$month = 'all';
		
		$existing_emps = array();
		$where = '';
		if($month != 'all'){
			$where = ' AND month = '.$month;
		}
		$sql = "SELECT emp_id, month FROM ".$_SESSION['rego']['payroll_dbase']." WHERE paid = 'Y'".$where." ORDER BY month, emp_id ASC";
		if($res = $dbc->query($sql)){
			while($row = $res->fetch_assoc()){
				$existing_emps[$row['month']][$row['emp_id']] = $row['emp_id'];
			}
		}
		//var_dump($existing_emps);
		
		$available_emps = array();
		$sql = "SELECT emp_id, en_name FROM ".$cid."_employees WHERE pr_calculation = 'Y' AND pr_status = '0' AND emp_status = '1' AND emp_type != '4' AND emp_type != '5' ORDER BY emp_id ASC";
		if($res = $dbc->query($sql)){
			while($row = $res->fetch_assoc()){
				$available_emps[$row['emp_id']] = $row['en_name'];
			}
		}
		//var_dump($available_emps);
		
		$missing_emps = array();
		foreach($existing_emps as $key=>$val){
			foreach($available_emps as $k=>$v){
				if(!isset($val[$k])){
					//$missing_month[$key][$v] = $v;
					$missing_emps[$key][$k] = $v;
				}
			}
		}
		//ksort($missing_emps);
		var_dump($missing_emps); exit;*/
	
	$payroll_months = array();
	if($res = $dbc->query("SELECT month FROM ".$_SESSION['rego']['payroll_dbase']." WHERE paid = 'Y' ORDER BY month ASC")){
		while($row = $res->fetch_assoc()){
			$payroll_months[$row['month']] = $months[$row['month']];
		}
	}
	
	$startMonth = date('n', strtotime($sys_settings['pr_startdate']));
	
	$var_allow = getUsedVarAllow($_SESSION['rego']['lang']);
	//var_dump($var_allow);
	$fix_allow = getUsedFixAllow($_SESSION['rego']['lang']);
	//var_dump($fix_allow);
	
	$fix_deduct = unserialize($sys_settings['fix_deduct']);
	$var_deduct = unserialize($sys_settings['var_deduct']);
	//var_dump($fix_deduct); //exit;
	//var_dump($var_deduct); exit;

	$his_cols = array();
		$his_cols[6] = array('salary',$lng['Salary']);
		$his_cols[] = array('ot1b',$lng['OT 1']);
		$his_cols[] = array('ot15b',$lng['OT 1.5']);
		$his_cols[] = array('ot2b',$lng['OT 2']);
		$his_cols[] = array('ot3b',$lng['OT 3']);
		$his_cols[] = array('ootb',$lng['Other OT']);
		foreach($fix_allow as $k=>$v){ 
			$his_cols[] = array('fix_allow_'.$k,$lng['Fixed'].' '.$v);
		}
		foreach($var_allow as $k=>$v){ 
			$his_cols[] = array('var_allow_'.$k,$lng['Variable'].' '.$v);
		}
		$his_cols[] = array('other_income',$lng['Other income']);
		
		$his_cols[] = array('tot_deduct_before',$lng['Deductions'].' (Before tax)');
		$his_cols[] = array('tot_deduct_after',$lng['Deductions'].' (After tax)');
		/*foreach($fix_deduct as $k=>$v){
			if($v['apply'] == 1 && $v['tax'] == 0){
				$his_cols[] = array('fix_deduct_'.$k,$v[$lang]);
			}
		}
		foreach($var_deduct as $k=>$v){
			if($v['apply'] == 1 && $v['tax'] == 0){
				$his_cols[] = array('var_deduct_'.$k,$v[$lang]);
			}
		}*/
		$his_cols[] = array('social',$lng['SSO Employee']);
		$his_cols[] = array('social_com',$lng['SSO Employer']);
		$his_cols[] = array('pvf_employee','PVF employee');
		$his_cols[] = array('pvf_employer','PVF employer');
		$his_cols[] = array('psf_employee',$lng['Pension fund']);
		$his_cols[] = array('tax',$lng['Tax']);
		//var_dump($his_cols);
		end($his_cols);
		$last_col = key($his_cols) + 1;
		//var_dump($last_col);

	$res = $dbc->query("SELECT his_showhide_cols FROM ".$_SESSION['rego']['cid']."_sys_settings");
	$row = $res->fetch_assoc();
	$usedCols = unserialize($row['his_showhide_cols']);
	//var_dump($usedCols); exit;
	
	$emptyCols = array();
	if($usedCols){
		foreach($his_cols as $k=>$v){
			if(!in_array($k, $usedCols)){
				//var_dump($k);
				$emptyCols[] = $k;
			}
		}
	}else{
		for($i=6;$i<=($last_col-1);$i++){
			$emptyCols[] = $i;
		}
		/*$emptyCols[] = 8;
		$emptyCols[] = 9;
		$emptyCols[] = 10;
		$emptyCols[] = 11;
		$emptyCols[] = 12;
		$emptyCols[] = 13;
		$emptyCols[] = 15;*/
		//$usedCols[] = 6;
		//$usedCols[] = 14;
		//$usedCols[] = 16;
	}
	//var_dump($usedCols); //exit;
	//var_dump($emptyCols); //exit;

?>
	
<style>
	table.dataTable thead th {
		white-space:normal;
	}
	table.dataTable thead th.mw {
		min-width:60px;
	}
	table.dataTable tbody td {
		white-space:nowrap;
	}
	table.dataTable tbody td input:read-only:hover {
		background:transparent;
		cursor:default;
	}
	table.dataTable tbody td input {
		margin:0 !important;
		display:block;
	}
	.xSumoSelect{
		padding: 5px 5px 5px 10px !important;
		border:1px #ddd solid !important;
	}
	.xSumoSelect.open > .optWrapper {
		top:29px !important; 
	}

</style>
		
      <form id="import" name="import" enctype="multipart/form-data" style="visibility:hidden; height:0; margin:0; padding:0">
			<input style="visibility:hidden" id="import_attendance" type="file" name="file" />
		</form>
   
  <h2><i class="fa fa-history"></i>&nbsp; <?=$lng['Historical data']?></h2>
	
	<div class="main">
		<div style="padding:0 0 0 20px" id="dump"></div>
		
		<div id="showTable" style="display:none">
			
			<div class="btns-group-left">
				<div class="searchFilter">
					<input placeholder="<?=$lng['Filter']?>" id="searchFilter" class="sFilter" type="text" />
					<button id="clearSearchbox" type="button" class="clearFilter btn btn-default btn-sm"><i class="fa fa-times"></i></button>
				</div>
				<? if($_GET['type'] == 'add'){ ?>
					<select id="sMonth" class="button monthFilter btn-fl">
						<option value="all"><?=$lng['All months']?></option>
						<? foreach($payroll_months as $k=>$v){ ?>
							<option value="<?=$k?>"><?=$v?></option>
						<? } ?>
					</select>
					<select id="addEmp" class="btn-fl">
						<option value="0"><?=$lng['Add employee']?></option>
						<option value="all"><?=$lng['Add all employees']?></option>
					</select>
				<? }else{ ?>
					<select id="sMonth" class="button monthFilter btn-fl">
						<option disabled selected value=""><?=$lng['From']?></option>
						<? foreach($months as $k=>$v){ if($k < $startMonth){ ?>
							<option value="<?=$k?>"><?=$v?></option>
						<? } } ?>
					</select>
					<select id="eMonth" class="button monthFilter btn-fl">
						<option disabled selected value=""><?=$lng['Until']?></option>
					<? foreach($months as $k=>$v){ if($k < $startMonth){ ?>
						<option value="<?=$k?>"><?=$v?></option>
					<? } } ?>
					</select>
				<? } ?>
				<div class="btn-fl">
				<select multiple="multiple" id="showCols">
				<? if($his_cols){ foreach($his_cols as $k=>$v){
						echo '<option class="optCol" value="'.$k.'" ';
						if($usedCols && in_array($k, $usedCols)){echo 'selected ';}
						echo '/>'.$v[1].'</option>';
						//echo '<label><input class="xshowCol" type="checkbox" data-id="'.$k.'"/>'.$k.'</label>';
				} } ?>
				</select>
				</div>
			</div>
			
			<!--<div id="message"></div>-->
			
			<div class="btns-group-right">	
				<button id="export-historic" <? if(!$usedCols){echo 'disabled';}?> onclick="window.location.href='historic/export_historical_excel_file.php'" type="button" class="btn-right btn btn-primary btn-fr">
					<i class="fa fa-upload"></i>&nbsp; <?=$lng['Export']?>
				</button>
				<button id="importBtn" onclick="$('#import_attendance').click()" type="button" class="btn-right btn btn-primary btn-fr">
					<i class="fa fa-download"></i>&nbsp; <?=$lng['Import']?> 
				</button>
				<button id="updateData" type="button" class="btn-right btn btn-primary btn-fr">
					<i class="fa fa-save"></i>&nbsp; <?=$lng['Update']?>
				</button>
				<button onClick="$('#modalUpload').modal('toggle');" type="button" class="btn-right btn btn-primary btn-fr">
					<i class="fa fa-upload"></i>&nbsp; <?=$lng['Upload to Payroll']?>
				</button>
				<button id="resetdata" type="button" class="btn-right btn btn-primary btn-fr">
					<i class="fa fa-trash"></i>&nbsp; <?=$lng['Clear']?> all
				</button>
			</div>
			
			<div style="clear:both"></div>
			
			<form id="historicForm" style="padding:0; margin:0">
			
			<table id="datatable" class="dataTable attendance">
				<thead class="tac">
					<tr style="line-height:100%">
						<th class="par30"><?=$lng['ID']?></th>
						<th class="tal par30" style="width:100%"><?=$lng['Employee name']?></th>
						<th data-sortable="false" class="tac" style="width:1px"><i data-toggle="tooltip" title="<?=$lng['Employee settings']?>" class="fa fa-info-circle fa-lg"></i></th>
						<th style="padding-right:40px"><?=$lng['Month']?></th> 
						<th class="mw" data-sortable="false" style="color:#b00"><?=str_replace(' ','<br>',$lng['Gross income'])?></th> 
						<th class="mw" data-sortable="false" style="color:#b00"><?=str_replace(' ','<br>',$lng['Net income'])?></th> 
					
						<th class="mw" ><?=$lng['Salary']?></th> 
						<th class="mw" data-sortable="false"><?=$lng['OT 1']?></th>
						<th class="mw" data-sortable="false"><?=$lng['OT 1.5']?></th>
						<th class="mw" data-sortable="false"><?=$lng['OT 2']?></th>
						<th class="mw" data-sortable="false"><?=$lng['OT 3']?></th>
						<th class="mw" data-sortable="false"><?=$lng['Other OT']?></th>
						<? foreach($fix_allow as $k=>$v){ 
								echo '<th class="mw" data-sortable="false">'.$lng['Fixed'].' '.$v.'</th>';
							} ?>
						<? foreach($var_allow as $k=>$v){ 
								echo '<th class="mw" data-sortable="false">'.$lng['Variable'].' '.$v.'</th>';
							} ?>
						<th class="mw" data-sortable="false"><?=$lng['Other income']?></th>
						
						<th class="mw" data-sortable="false"><?=$lng['Deductions']?> <?=$lng['Before tax']?></th>
						<th class="mw" data-sortable="false"><?=$lng['Deductions']?> <?=$lng['After tax']?></th>
						
						<!--<? foreach($fix_deduct as $k=>$v){
								if($v['apply'] == 1 && $v['tax'] == 0){
									echo '<th class="mw" data-sortable="false">'.$v[$lang].'</th>';
								}
							} ?>
						<? foreach($var_deduct as $k=>$v){
								if($v['apply'] == 1 && $v['tax'] == 0){
									echo '<th class="mw" data-sortable="false">'.$v[$lang].'</th>';
								}
							} ?>-->
						<th class="mw" data-sortable="false"><?=$lng['SSO Employee']?></th>
						<th class="mw" data-sortable="false"><?=$lng['SSO Employer']?></th>
						<th class="mw" data-sortable="false">PVF employee<? //=$lng['Provident fund']?></th>
						<th class="mw" data-sortable="false">PVF employer<? //=$lng['Provident fund']?></th>
						<th class="mw" data-sortable="false"><?=$lng['Pension fund']?></th>
						<th class="mw" data-sortable="false"><?=$lng['Tax']?></th>
						
						<!--<th data-sortable="false" class="tac" style="width:1px"><a id="resetdata"><i data-toggle="tooltip" title="<?=$lng['Clear database']?>" data-placement="left" class="fa fa-times-circle fa-lg"></i></a></th>-->
						<th data-sortable="false" class="tac" style="width:1px"><i class="fa fa-trash fa-lg"></i></th>
					</tr>
				</thead>
				<tbody>
				
				</tbody>
			</table>
		</div>
		</form>

	</div>

	<!-- Modal Employee settings Tax Info -->
	<div class="modal fade" id="modalEmpinfo" tabindex="-1" role="dialog">
		<div class="modal-dialog" role="document" style="max-width:600px;">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title">
						<span><?=$lng['Employee settings']?> - <span id="mt_name"></span></span>
					</h5>
					<div style=" position:absolute; right:40px; padding:4px 0">
						<a href="#" id="editemp" style="margin-right:8px"><i class="fa fa-edit fa-lg"></i></a>
					</div>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body" style="padding:20px">
					<div id="EmpInfo" style="max-height:500px; overflow-y:auto"></div>
				</div>
			</div>
		</div>
	</div>

	<!-- Modal Upload to payroll -->
	<div class="modal fade" id="modalUpload" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false">
		<div class="modal-dialog" style="width:600px">
		  <div class="modal-content">
				<div class="modal-header" style="background:#b00; color:#fff">
					 <h4 class="modal-title" id="myModalLabel"><i class="fa fa-upload"></i>&nbsp; <?=$lng['Upload to Payroll']?></h4>
				</div>
				<div class="modal-body" style="padding:15px 20px 12px 20px">
					<div style="max-height:350px; overflow-y:auto"><?=getHelpfile(442)?></div>
					<div style="height:15px"></div>
					<button id="uploadData" type="button" class="btn btn-primary btn-fl"><i class="fa fa-upload"></i>&nbsp; <?=$lng['Upload']?></button>
					<button type="button" class="btn btn-primary btn-fr" data-dismiss="modal"><i class="fa fa-times"></i>&nbsp; <?=$lng['Cancel']?></button>
					<div class="clear"></div>
				</div>
			</div>
		</div>
	</div>

<style>
.confDelete {
	background:red;
	border:0;
}
</style>
	
   <!-- PAGE RELATED PLUGIN(S) -->
	
	<script type="text/javascript">
		
	var height = window.innerHeight-357;
	var headerCount = 1;
		
	$(document).ready(function() {
		
		var tablerows = Math.floor(height/25);
		var cid = <?=json_encode($cid)?>;//.replace('x',acc);
		var year = <?=json_encode($_SESSION['rego']['cur_year'])?>;
		var month = <?=json_encode(sprintf('%02d', $_SESSION['rego']['cur_month']))?>;
		var tableCols = <?=json_encode($his_cols)?>;
		var emptyCols = <?=json_encode($emptyCols)?>;
		var last_col = <?=json_encode($last_col)?>;
		
		$("#sMonth, #eMonth").on('change', function(){ // Create Historical data ///////////////////////////////////////
			if($("#sMonth").val() == null || $("#eMonth").val() == null){return false}
			$.ajax({
				url: ROOT+"payroll/ajax/create_historic_data.php",
				data: {sMonth: $("#sMonth").val(), eMonth: $("#eMonth").val()},
				success: function(response) {
					//$("#dump").html(response); return false;
					dtable.ajax.reload(null, false);
				}
			});
		})
		
		
		var mySelect = $('#showCols').SumoSelect({
			//okCancelInMulti:true, 
			selectAll: false,
			csvDispCount: 1,
			outputAsCSV : true,
			showTitle : false,
			placeholder: '<?=$lng['Show Hide Columns']?>',
			captionFormat: '<?=$lng['Show Hide Columns']?>',
			captionFormatAllSelected: '<?=$lng['Show Hide Columns']?>',
			triggerChangeCombined:true
		});
		$(".SumoSelect li").bind('click.check', function(event) {
			var nr = $(this).index()+6;
			if($(this).hasClass('selected') == true){
				dtable.column(nr).visible(true);
				dtable.ajax.reload(null, false);
			}else{
				dtable.column(nr).visible(false);
				dtable.ajax.reload(null, false);
			}
    })		
		$('select#showCols').on('sumo:closing', function(o) {
			//alert('sumo:closing'); 
			var columns = $(this).val();
			var his_cols = [];
			$.each(columns, function(index, item) {
				his_cols.push({id:item, db:tableCols[item][0], name:tableCols[item][1]})
			})
			$.ajax({
				url: "ajax/save_his_columns.php",
				data: {cols: his_cols},
				success: function(result){
					if(result > 0){
						$('#export-historic').prop('disabled', false)
					}else{
						$('#export-historic').prop('disabled', true)
					}
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
		
		function saveHistoricLine(id, val, field){
			$.ajax({
				type: "POST",
				url: ROOT+"payroll/ajax/save_historic_data.php",
				data: {id: id, val: val, field: field},
				dataType: "text",
				success: function(response) {
					//$("#dump").html(response)
				}
			});
		}

		$(document).on('click', '#updateData', function(){
			$("#historicForm").submit();
		})

		$(document).on('click', '#uploadData', function(){
			$('#uploadData i').removeClass('fa-upload').addClass('fa-refresh fa-spin');
			$.ajax({
				url: "ajax/upload_history_to_payroll.php",
				success: function(response) {
					//$("#dump").html(response); return false;
					if($.trim(response) == 'success'){
						$("body").overhang({
							type: "success",
							message: '<i class="fa fa-check"></i>&nbsp;&nbsp;Data uploaded successfuly',
							duration: 2,
						})
						setTimeout(function(){
							//$('#modalUpload').modal('toggle');
							location.reload();
						}, 500);
					}else{
						$("body").overhang({
							type: "warn",
							message: '<i class="fa fa-exclamation-triangle"></i>&nbsp;&nbsp;'+response,
							closeConfirm: "true",
							duration: 5,
						})
					}
					setTimeout(function(){
						$('#uploadData i').removeClass('fa-refresh fa-spin').addClass('fa-upload'); 
					}, 1000);
				},
				error:function (xhr, ajaxOptions, thrownError){
					$("body").overhang({
						type: "error",
						message: '<i class="fa fa-exclamation-triangle"></i>&nbsp;&nbsp;<?=$lng['Sorry but someting went wrong']?> <b><?=$lng['Error']?></b> : '+thrownError,
						duration: 8,
						closeConfirm: "true",
					})
					setTimeout(function(){
						$('#uploadData i').removeClass('fa-refresh fa-spin').addClass('fa-upload');
						//$("#uploadData").removeClass('flash');
					}, 1000);
				}
			})
		})

		$(document).on('click', '#uploadPayroll', function(){
			$('#modalUpload').modal('toggle');
			return false;
			$('#uploadData i').removeClass('fa-upload').addClass('fa-refresh fa-spin');
			$.ajax({
				url: ROOT+"payroll/ajax/upload_history_to_payroll.php",
				success: function(response) {
					$("#dump").html(response); return false;
					if($.trim(response) == 'success'){
						$("body").overhang({
							type: "success",
							message: '<i class="fa fa-check"></i>&nbsp;&nbsp;Data uploaded successfuly',
							duration: 2,
						})
					}else{
						$("body").overhang({
							type: "warn",
							message: '<i class="fa fa-exclamation-triangle"></i>&nbsp;&nbsp;'+response,
							closeConfirm: "true",
							duration: 4,
						})
					}
					setTimeout(function(){
						$('#uploadData i').removeClass('fa-refresh fa-spin').addClass('fa-upload'); 
						//$("#uploadData").removeClass('flash');
					}, 1000);
				},
				error:function (xhr, ajaxOptions, thrownError){
					$("body").overhang({
						type: "error",
						message: '<i class="fa fa-exclamation-triangle"></i>&nbsp;&nbsp;<?=$lng['Sorry but someting went wrong']?> <b><?=$lng['Error']?></b> : '+thrownError,
						duration: 4,
					})
					setTimeout(function(){
						$('#uploadData i').removeClass('fa-refresh fa-spin').addClass('fa-upload');
						//$("#uploadData").removeClass('flash');
					}, 1000);
				}
			})
		})

		$(document).on("keyup", "#historicForm input", function(e){
			$("#updateData").addClass('flash');
		})
		$(document).on("submit", "#historicForm", function(e){
			e.preventDefault();
			$('#updateData i').removeClass('fa-save').addClass('fa-refresh fa-spin');
			//var data = $(this).serialize();
			var data = dtable.$('input').serialize();
			$.ajax({
				type: "POST",
				url: ROOT+"payroll/ajax/save_historic_data.php",
				data: data,
				success: function(response) {
					//$("#dump").html(response); return false;
					if($.trim(response) == 'success'){
						$("body").overhang({
							type: "success",
							message: '<i class="fa fa-check"></i>&nbsp;&nbsp;Data updated successfuly',
							duration: 2,
						})
					}else{
						$("body").overhang({
							type: "warn",
							message: '<i class="fa fa-exclamation-triangle"></i>&nbsp;&nbsp;'+response,
							duration: 4,
						})
					}
					dtable.ajax.reload(null, false);
					setTimeout(function(){
						$('#updateData i').removeClass('fa-refresh fa-spin').addClass('fa-save'); 
						$("#updateData").removeClass('flash');
					}, 1000);
				},
				error:function (xhr, ajaxOptions, thrownError){
					$("body").overhang({
						type: "error",
						message: '<i class="fa fa-exclamation-triangle"></i>&nbsp;&nbsp;<?=$lng['Sorry but someting went wrong']?> <b><?=$lng['Error']?></b> : '+thrownError,
						duration: 8,
						closeConfirm: "true",
					})
					setTimeout(function(){
						$('#updateData i').removeClass('fa-refresh fa-spin').addClass('fa-save');
						$("#updateData").removeClass('flash');
					}, 1000);
				}
			})
			
		})
		
		$("#addEmp").on('change', function(){
			//$('#calculate i').removeClass('fa-calculator').addClass('fa-refresh fa-spin');
			if(this.value == 0){return false}
			var ids = [this.value];
			if(this.value == 'all'){
				//$("#addEmp option[value='all']").remove();
				var ids = [];
				$("#addEmp option").each(function(){
					if($(this).val() != 0 && $(this).val() != 'all'){
						ids.push($(this).val())
					}
				})
			}
			//alert(ids)
			$.ajax({
				url: ROOT+"payroll/ajax/add_historic_data.php",
				data: {ids: ids, months: $('#sMonth').val()},
				success: function(response) {
					//$("#dump").html(response); return false;
					$("#addEmp").val('0');
					$.each(ids, function(index, item) {
						$("#addEmp option[value="+item+"]").remove();
					})
					dtable.ajax.reload(null, false);
				}
			});
		})
		
		$('#resetdata').confirmation({
			container: 'body',
			rootSelector: '#resetdata',
			singleton: true,
			animated: 'fade',
			placement: 'left',
			popout: true,
			html: true,
			title 			: '<?=$lng['Are you sure']?>',
			btnOkClass 		: 'btn btn-danger',
			btnOkLabel 		: 'Clear<? //=$lng['Delete']?>',
			btnCancelClass 	: 'btn btn-success',
			btnCancelLabel 	: '<?=$lng['Cancel']?>',
			onConfirm: function() { 
				//$('#resetdata i').removeClass('fa-trash').addClass('fa-refresh fa-spin');
				$.ajax({
					url: ROOT+"payroll/ajax/truncate_historic_data.php",
					success: function(response) {
						//$("#dump").html(response)
						//alert(response)
						
						//setTimeout(function(){
							//$('#resetdata i').removeClass('fa-refresh fa-spin').addClass('fa-trash');
							dtable.ajax.reload(null, false);
						//}, 500);
						getMissingEmployees($('#sMonth').val())
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
			}
		});
		
		 var dtable = $('#datatable').DataTable({
			scrollY:        false,//scrY,//heights-288,
			scrollX:        true,
			scrollCollapse: false,
			fixedColumns:   true,
			//stateSave: 		true,
			lengthChange:  	false,
			searching: 		true,
			ordering: 		true,
			paging: 		true,
			pageLength: tablerows,
			filter: 		true,
			info: 			false,
			//autoWidth:		false,
			<?=$dtable_lang?>
			processing: false,
			serverSide: true,
			order: [],
			ajax: {
				url: ROOT+"payroll/ajax/server_historic_data.php",
				type: "POST",
				//data: function(d){ 
					//d.imports = true;
				//}
			},
			columnDefs: [
				//{"targets": [7,8,9,10,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31,32], "visible": false, "searchable": false},
				{"targets": emptyCols, "visible": false},
				//{"targets": [6], "visible": true},
				{"targets": [0,1], "class": 'pad410 tal'},
				{"targets": [3], "class": 'pad410 tal mw10'},
				{"targets": [1], width: '60%'},
				{"targets": [2,last_col], "class": 'tac mw10'},
				//{"targets": [3,4,5,6,7,8,9,10,11,12], "class": 'mw90'},
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
		
		$(document).on("change", "#import_attendance", function(e){
			e.preventDefault();
			var id = cid;
			var ff = $(this).val().toLowerCase();
			ff = ff.replace(/.*[\/\\]/, '');
			var ext =  ff.split('.').pop();
			f = ff.substr(0, ff.lastIndexOf('.'));
			var r = f.split('_');
			if(!(ext == 'xls' || ext == 'xlsx')){
				$("body").overhang({
					type: "error",
					message: '<i class="fa fa-exclamation-triangle"></i>&nbsp;&nbsp;<?=$lng['Please use Excel files only']?> (.xls, .xlsx)',
					duration: 4,
				})
				return false;
			}
			if(r.length != 3){
				$("body").overhang({
					type: "error",
					message: '<i class="fa fa-exclamation-triangle"></i>&nbsp;&nbsp;Wrong file format ! Please use [ '+id+'_historic-data'+'_'+year+'.xls ]',
					duration: 4,
				})
				return false;
			}else{
				var s1 = r[0]; // ID
				var s2 = r[1]; // Filename
				var s3 = (r[2]).substring(0,4); // Year
				if(s1 != id){
					$("body").overhang({
						type: "error",
						message: '<i class="fa fa-exclamation-triangle"></i>&nbsp;&nbsp;File ID dont match selected client ('+s1+') ! Please use [ '+id+'-historic-data'+'_'+year+'.xls ]',
						duration: 4,
					})
					return false;
				}else if(s2 !== 'historic-data'){
					$("body").overhang({
						type: "error",
						message: '<i class="fa fa-exclamation-triangle"></i>&nbsp;&nbsp;Wrong file name ('+s2+') ! Please use [ '+id+'_historic-data'+'_'+year+'.xls ]',
						duration: 4,
					})
					return false;
				}else if(s3.indexOf(year) == -1){
					$("body").overhang({
						type: "error",
						message: '<i class="fa fa-exclamation-triangle"></i>&nbsp;&nbsp;Wrong year ('+s3+') ! Please use [ '+id+'_historic-data'+'_'+year+'.xls ]',
						duration: 4,
					})
					return false;
				}
			}
			$("form#import").submit();
		});
		
		$(document).on("submit", "#import", function(e){
			e.preventDefault();
			$("body").overhang({
				type: "warn",
				message: '<i class="fa fa-exclamation-triangle"></i>&nbsp;&nbsp;One moment please, importing data.&nbsp;&nbsp;<i class="fa fa-refresh fa-spin"></i>',
				//closeConfirm: "true",
				//duration: 10,
			})
			$('#importBtn i').removeClass('fa-download').addClass('fa-refresh fa-spin');
			//return false;
			var file = $("#import_attendance")[0].files[0];
			var data = new FormData($(this)[0]);
			setTimeout(function(){
			$.ajax({
				url: ROOT+"payroll/ajax/import_historic_data.php",
				type: 'POST',
				data: data,
				async: false,
				cache: false,
				contentType: false,
				processData: false,
				success: function(result){
					//$('#dump').html(result); return false;
					setTimeout(function(){
						$(".overhang").slideUp(200); 
						$('#importBtn i').removeClass('fa-refresh fa-spin').addClass('fa-download');
					}, 800);
					setTimeout(function(){
						if($.trim(result) == 'success'){
							$("body").overhang({
								type: "success",
								message: '<i class="fa fa-check"></i>&nbsp;&nbsp;Data imported successfuly',
								duration: 2,
							})
							
							setTimeout(function(){dtable.ajax.reload(null, false);}, 500);
						}else{
							$("body").overhang({
								type: "error",
								message: '<i class="fa fa-exclamation-triangle"></i>&nbsp;&nbsp;'+result,
								duration: 4,
							})
							
						}
					}, 1000);
					$("#import_attendance").val('');		
				},
				error:function (xhr, ajaxOptions, thrownError){
					$("body").overhang({
						type: "error",
						message: '<i class="fa fa-exclamation-triangle"></i>&nbsp;&nbsp;<?=$lng['Sorry but someting went wrong']?> <b><?=$lng['Error']?></b> : '+thrownError,
						duration: 4,
					})
					$('#importBtn i').removeClass('fa-refresh fa-spin').addClass('fa-download');
				}
			});}, 300);
		});
		
		$(document).ajaxComplete(function( event,request, settings ) {
			$('.delLine').confirmation({
				container: 'body',
				rootSelector: '.delLine',
				singleton: true,
				animated: 'fade',
				placement: 'left',
				popout: true,
				html: true,
				title 			: '<?=$lng['Are you sure']?>',
				btnOkClass 		: 'btn btn-danger',
				btnOkLabel 		: '<?=$lng['Delete']?>',
				btnCancelClass 	: 'btn btn-success',
				btnCancelLabel 	: '<?=$lng['Cancel']?>',
				onConfirm: function() { 
					$.ajax({
						url: ROOT+"payroll/ajax/delete_historic_line.php",
						data:{id: $(this).data('id')},
						success: function(result){
							//$('#dump').html(result); return false;
							getMissingEmployees($('#sMonth').val())
							dtable.ajax.reload(null, false);
						}
					});
				}
			});
		});

		function getMissingEmployees(m){
			$.getJSON('ajax/get_missing_employees_payroll.php?m='+m, function(result) {
				var options = $('#addEmp');
				options.empty()
				options.append(new Option('<?=$lng['Add employee']?>', 0));
				options.append(new Option('<?=$lng['Add all employees']?>', 'all'));
				$.each(result, function(index, txt) {
					options.append(new Option(index+' - '+txt, index));
				});
			});
		}
		getMissingEmployees($('#sMonth').val());
		//alert($('#sMonth').val())
		
		$(document).on("change", "#sMonth", function(){
			 getMissingEmployees(this.value)
		});
		$(document).on("click", ".showEmpinfo", function(e) {
			e.preventDefault();
			var id = $(this).data('id');
			var name = $(this).data('name');
			//alert(id);
			$.ajax({
				url: ROOT+"payroll/ajax/get_modal_employee_info.php",
				cache: false,
				data: {id: id},
				success:function(result){
					//var data = jQuery.parseJSON(result);
					//alert(result);
					$('#editemp').attr('href','../employees/index.php?mn=102&id='+id);
					$('#mt_name').html(id+' '+name);
					$('#EmpInfo').html(result);
					$('#modalEmpinfo').modal('toggle');
				},
				error:function (xhr, ajaxOptions, thrownError){
					alert(thrownError);
				}
			});
		});
		
	})
	
	</script>
















