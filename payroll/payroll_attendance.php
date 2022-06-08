<?php
	
	/*var_dump(taxFomGross(480000, 169000));
	var_dump(taxFomNet(480000, 169000));
	
	var_dump(calculateAnualTax(480000, 'gross'));
	var_dump(calculateAnualTax(480000, 'gross')/12);
	var_dump(calculateAnualTax(480000, 'net'));
	var_dump(calculateAnualTax(480000, 'net')/12);
	
	exit;*/
	

//exit;
	
	$data = false;
	$paid = false;
	$max = 0;
	$sql = "SELECT paid FROM ".$_SESSION['rego']['payroll_dbase']." WHERE month = ".$_SESSION['rego']['cur_month'];
	if($res = $dbc->query($sql)){
		while($row = $res->fetch_assoc()){
			$data = true;
			$max ++;
			if($row['paid'] == 'Y'){$paid = true;}
		}
	}
	$var_allow = getUsedVarAllow($lang);
	//$fix_deduct = getUsedFixDeduct($lang);
	$var_deduct = getUsedVarDeduct($lang);
	
	//var_dump(unserialize($pr_settings['var_deduct'])); exit;
	//var_dump($data); exit;
	
	$att_cols = array();
		$att_cols[4] = array('ot1h',$lng['OT 1']);
		$att_cols[] = array('ot15h',$lng['OT 1.5']);
		$att_cols[] = array('ot2h',$lng['OT 2']);
		$att_cols[] = array('ot3h',$lng['OT 3']);
		$att_cols[] = array('ootb',$lng['Other OT'].' ('.$lng['THB'].')');
		$att_cols[] = array('absence',$lng['Absence']);
		$att_cols[] = array('late_early',$lng['Late Early']);
		$att_cols[] = array('leave_wop',$lng['Leave WOP']);
		foreach($var_allow as $k=>$v){
			$att_cols[] = array('var_allow_'.$k,$v);
		}
		$att_cols[] = array('other_income',$lng['Other income']);
		$att_cols[] = array('severance',$lng['Severance']);
		$att_cols[] = array('remaining_salary',$lng['Remaining salary']);
		$att_cols[] = array('notice_payment',$lng['Notice payment']);
		$att_cols[] = array('paid_leave',$lng['Paid leave']);
		foreach($var_deduct as $k=>$v){
			$att_cols[] = array('var_deduct_'.$k,$v);
		}
		/*$att_cols[] = array('uniform',$lng['Uniform']);
		$att_cols[] = array('deduct_3',$lng['Other deduct']);*/
		$att_cols[] = array('advance',$lng['Advance']);
		//var_dump($att_cols);
	
	end($att_cols);
	$last_col = key($att_cols) + 1;
	//var_dump($last_col); //exit;

	$usedAttCols = getUsedAtendanceColumns();
	// echo '<pre>';
	// print_r($usedAttCols);
	// echo '</pre>';
	//var_dump($usedAttCols); exit;
	$usedAttCols = array_values($usedAttCols);

	$res = $dbc->query("SELECT att_showhide_cols FROM ".$_SESSION['rego']['cid']."_sys_settings");
	$row = $res->fetch_assoc();
	$shCols = unserialize($row['att_showhide_cols']);
	if(!$shCols){$shCols = array();}
	//var_dump($shCols); //exit;

	// echo '<pre>';
	// print_r($shCols);
	// echo '</pre>';
	
	$combine = array_unique(array_merge($usedAttCols, $shCols));
	sort($combine);
	//var_dump($combine); //exit;
	$usedCols = $combine;
	if(!$usedCols){$usedCols = array();}
	
	$emptyCols = array();
	foreach($att_cols as $k=>$v){
		if(!in_array($k, $usedCols)){
			$emptyCols[] = $k;
		}
	}
	//var_dump($emptyCols); //exit;
	
	$missing_emps = getMissingEmployeesFromPayroll($cid, $_SESSION['rego']['curr_month']);
	//var_dump($missing_emps); exit;
	$status = getPayrollStatus($_SESSION['rego']['cur_year'].'_'.$_SESSION['rego']['cur_month']);
	//var_dump($status);


	
?>
	
<style>
	table.dataTable thead th {
		text-align:center;
		white-space:normal;
		line-height:100%;
	}
	table.dataTable thead tr.lhnormal th {
		line-height: normal;
		white-space:nowrap;
	}
	table.dataTable tbody td {
		white-space: nowrap;
	}

</style>
	
    <form id="import" name="import" enctype="multipart/form-data" style="visibility:hidden; height:0; margin:0; padding:0">
			<input style="visibility:hidden" id="import_attendance" type="file" name="file" />
		</form>
   
   	<h2 style="padding-right:60px">
			<i class="fa fa-table"></i>&nbsp;&nbsp;<?=$lng['Monthly attendance']?>&nbsp;&nbsp;
			<span class="hide-480"><i class="fa fa-arrow-circle-right"></i>&nbsp;&nbsp;<?=$months[$_SESSION['rego']['cur_month']].' '.$_SESSION['rego']['year_'.$lang]?>&nbsp;&nbsp;</span>
			<span class="hide-600"><i class="fa fa-arrow-circle-right"></i>&nbsp;&nbsp;<?=$pr_status[$status]?></span>
		<span style="display:none; font-style:italic; color:#b00; padding-left:30px" id="sAlert"><i class="fa fa-exclamation-triangle fa-mr"></i><?=$lng['Data is not updated to last changes made']?></span>
		</h2>
	
	<? if(!$data){ ?> 
      <div style="border-bottom:1px #ddd solid; padding:15px">
			<button type="button" id="createNew" class="btn btn-primary"><i class="fa fa-file-o"></i>&nbsp; <?=$lng['Create new']?></button>
			<div style="padding:0 0 0 20px" id="dump"></div>
	<? }else{ ?>
	
	<div class="main">
		<div style="padding:0 0 0 20px" id="dump"></div>
		
			<div id="showTable" style="display:none">
				
				<table border="0" style="float:left">
					<tr>
						<td style="padding-right:5px">
							<div class="searchFilter" style="width:180px; margin:0">
								<input placeholder="<?=$lng['Filter']?>" id="searchFilter" class="sFilter" type="text" />
								<button id="clearSearchbox" type="button" class="clearFilter btn btn-default btn-sm"><i class="fa fa-times"></i></button>
							</div>
						</td>
						<td style="padding-right:5px">
							<select multiple="multiple" id="showCols" style="height:18px !important">
							<? foreach($att_cols as $k=>$v){
									echo '<option class="optCol" value="'.$k.'" ';
									if($usedCols && in_array($k, $usedCols)){echo 'selected ';}
									echo '/>'.$v[1].'</option>';
									//echo '<label><input class="xshowCol" type="checkbox" data-id="'.$k.'"/>'.$k.'</label>';
							} ?>
							</select>
						</td>
						<? if(!$locked && $max < $_SESSION['rego']['max'] && !empty($missing_emps)){ ?>
						<td style="padding-right:5px">
							<select id="addEmp">
								<option><?=$lng['Add employee']?></option>
							<? foreach($missing_emps as $k=>$v){
									echo '<option value="'.$k.'" />'.$k.' - '.$v.'</option>';
							} ?>
							</select>
						</td>
						<? } ?>
						<td style="padding-right:5px">
							<select id="pageLength" class="button">
								<option selected value=""><?=$lng['Rows / page']?></option>
								<option value="10">10 <?=$lng['Rows / page']?></option>
								<option value="15">15 <?=$lng['Rows / page']?></option>
								<option value="20">20 <?=$lng['Rows / page']?></option>
								<option value="30">30 <?=$lng['Rows / page']?></option>
								<option value="40">40 <?=$lng['Rows / page']?></option>
								<option value="50">50 <?=$lng['Rows / page']?></option>
							</select>
						</td>
						<td style="padding-right:5px">
							<select id="taxFilter" class="button">
								<option selected value="all"><?=$lng['Show all']?></option>
								<option value="1"><?=$lng['PND']?> 1</option>
								<option value="3"><?=$lng['PND']?> 3</option>
								<option value="0"><?=$lng['no Tax']?></option>
							</select>
						</td>
					</tr>
				</table>
						
				<div class="btns-group-right">
					<? if($_SESSION['rego']['version'] > 50){ ?>
					<button id="getAttendance" type="button" class="btn btn-primary btn-fr">
						<i class="fa fa-clock-o"></i>&nbsp; <?=$lng['Get Attendance']?>
					</button>
					<button disabled id="getLeave" type="button" class="btn btn-primary btn-fr">
						<i class="fa fa-plane"></i>&nbsp; <?=$lng['Get Leave']?>
					</button>
					<? } ?>
					
					<button <? if($paid){echo 'disabled';}?> onclick="$('#import_attendance').click()" type="button" class="btn btn-primary btn-fr">
						<i class="fa fa-download"></i>&nbsp; <?=$lng['Import']?> 
					</button>
					<button type="button" class="btn btn-primary btn-fr" onclick="window.location.href='<?=ROOT?>payroll/export_attendance_excel.php';">
						<i class="fa fa-upload"></i>&nbsp; <?=$lng['Export']?></span>
					</button>
					<button <? if($paid){echo 'disabled';}?> id="calculate" type="button" class="btn btn-primary btn-fr">
						<i class="fa fa-calculator"></i>&nbsp; <?=$lng['Save & Calculate Payroll']?>
					</button>
				</div>
				<div style="clear:both"></div>
				
				<form id="payrollForm" style="padding:0; margin:0">
				<table id="datatable" class="dataTable hoverable selectable attendance nowrap">
					<thead>
						<tr class="xlhnormal">
							<th></th>
							<th colspan="2" class="tal"><?=$lng['Employee']?></th>
							<th class="tac"><?=$lng['Days']?></th>
							<th class="tac" colspan="5"><?=$lng['Overtime']?> <?=$lng['Hrs']?></th>
							<th class="tac" colspan="3"><?=$lng['Absence']?> <?=$lng['Hrs']?></th>
							<? if(count($var_allow) > 0){ ?>
							<th class="tac" colspan="<?=count($var_allow)?>"><?=$lng['Variable allowances']?></th>
							<? } ?>
							<th class="tac" colspan="5"><?=$lng['Other income']?></th>
							<? if(count($var_deduct) > 0){ ?>
							<th class="tac" colspan="<?=count($var_deduct)?>"><?=$lng['Deductions']?></th>
							<? } ?>
							<th><?=$lng['Advance']?></th>
							<th colspan="4">&nbsp;</th>
						</tr>
						<tr>
							<th class="par30"><?=$lng['ID']?></th>
							<th class="tal par30"><?=$lng['Employee name']?></th>
							<th data-searchable="false" data-sortable="false" class="tac" style="width:1px"><i data-toggle="tooltip" title="Employee settings" class="fa fa-info-circle fa-lg"></i></th>
							<th data-searchable="false" data-sortable="false"><?=$lng['Paid']?></th> 
							
							<th data-searchable="false" data-sortable="false"><?=$lng['OT 1']?></th>
							<th data-searchable="false" data-sortable="false"><?=$lng['OT 1.5']?></th>
							<th data-searchable="false" data-sortable="false"><?=$lng['OT 2']?></th>
							<th data-searchable="false" data-sortable="false" ><?=$lng['OT 3']?></th>
							<th data-searchable="false" data-sortable="false" ><?=$lng['Other OT'].' &#3647;'?></th>
							
							<th data-searchable="false" data-sortable="false" ><?=$lng['Absence']?></th>
							<th data-searchable="false" data-sortable="false" ><?=$lng['Late Early']?></th>
							<th data-searchable="false" data-sortable="false" ><?=$lng['Leave WOP']?></th>
							<? foreach($var_allow as $k=>$v){ 
									echo '<th data-searchable="false" data-sortable="false">'.$v.'</th>';
								} ?>
							<th data-searchable="false" data-sortable="false"><?=$lng['Other']?></th>
							<th data-searchable="false" data-sortable="false"><?=$lng['Severance']?></th>
							<th data-searchable="false" data-sortable="false"><?=$lng['Remaining salary']?></th>
							<th data-searchable="false" data-sortable="false"><?=$lng['Notice payment']?></th>
							<th data-searchable="false" data-sortable="false"><?=$lng['Paid leave']?></th>
							<? foreach($var_deduct as $k=>$v){ 
									echo '<th data-searchable="false" data-sortable="false">'.$v.'</th>';
								} ?>
							<th data-searchable="false" data-sortable="false"><?=$lng['Advance']?></th>
							
							<th <? //if($_SESSION['rego']['access']['payroll']['del'] == 0){echo 'data-visible="false"';} ?> data-searchable="false" data-sortable="false" style="width:1px"><i data-toggle="tooltip" title="<?=$lng['Delete from payroll']?>" class="fa fa-trash fa-lg"></i></th>
							
						</tr>
					</thead>
					<tbody>
					
					</tbody>
				</table>
				</form>
			
			</div>
	<? } ?>
		</div>
	</div>
	<!--<div style="position:absolute;top:240px; left:0; right:0; background:rgba(255,0,0,0.5); height:650px;"></div>-->
	
	<!-- Modal Employee settings Tax Info -->
	<div class="modal fade" id="modalEmpinfo" tabindex="-1" role="dialog">
		<div class="modal-dialog" role="document" style="max-width:500px;">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title">
						<span id="mt_name"></span>
					</h5>
					<div style=" position:absolute; right:40px; padding:4px 0">
						<a href="#" id="editemp" style="margin-right:8px"><i class="fa fa-edit fa-lg"></i></a>
					</div>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body" style="padding:15px">
					<div id="EmpInfo" style="max-height:500px; overflow-y:auto"></div>
				</div>
			</div>
		</div>
	</div>
	
	<!-- Modal Employee settings Tax Info -->
	<div class="modal fade" id="modalEmpinfo" tabindex="-1" role="dialog">
		<div class="modal-dialog" role="document" style="max-width:500px;">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title">
						<span id="mt_name"></span>
					</h5>
					<div style=" position:absolute; right:40px; padding:4px 0">
						<a href="#" id="editemp" style="margin-right:8px"><i class="fa fa-edit fa-lg"></i></a>
					</div>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body" style="padding:15px">
					<div id="EmpInfo" style="max-height:500px; overflow-y:auto"></div>
				</div>
			</div>
		</div>
	</div>
	
	<!-- Modal Expire -->
	<div class="modal fade" id="modalExpire" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static" data-keyboard="false">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-body text-center" style="padding:20px">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
					<h5 class="modal-title" style="margin-bottom:15px; font-size:24px; line-height:120%; color:#b00">Unfortunately<br>Your subscription has ended</h5>
					<p style="font-size:14px; margin:0 0 5px">If you wish to continue using <b>REGO HR</b><br>please click the button below</p>
					<p style="font-size:14px; margin:0 0 5px">If you have any issues, please call us at <b>0613 918 477</b></p>
					<p style="font-size:14px; margin:0 0 5px">We are more than happy to help</p>
					<center>
						<a href="../myrego/index.php?mn=5" style="margin:10px 0 5px; padding:5px 15px" class="btn btn-primary cbtn-sm">Upgrade / Renew now</a>
					</center>
				</div>
			</div>
		</div>
	</div>
	
	<script type="text/javascript">
		
	var height = window.innerHeight-325;
		
	$(document).ready(function() {
		//alert(window.innerHeight)
		var tablerows = Math.floor(height/26);
		var cid = <?=json_encode($cid)?>;
		var year = <?=json_encode($_SESSION['rego']['cur_year'])?>;
		var month = <?=json_encode($_SESSION['rego']['curr_month'])?>;
		var yesno = <?=json_encode($yesno)?>;
		var data = <?=json_encode($paid)?>;
		var eCols = <?=json_encode($emptyCols)?>;
		var tableCols = <?=json_encode($att_cols)?>;
		var lastCol =  <?=json_encode($last_col)?>;
		var expire =  <?=json_encode($_SESSION['rego']['expire'])?>;
		function bindHourformat(){
			setTimeout(function(){$(".hourFormat").mask("999:99", {placeholder: "000:00"});}, 100);
		}

		$(document).on('click', '#getAttendance', function(){
			$.ajax({
				url: "ajax/get_time_attendance.php",
				dataType: 'json',
				success: function(response) {
					//alert(response['unlocked'])
					//$("#dump").html(response); return false;
					if(response['unlocked'] != 0){
						$("body").overhang({
							type: "error",
							message: '<i class="fa fa-exclamation-triangle"></i>&nbsp;&nbsp;There are ' + response['unlocked'] + ' unlocked rows in Time Attendance for this period',
							duration: 6,
						})
						return false;
					}
					if(response['error'] != ''){
						$("body").overhang({
							type: "error",
							message: '<i class="fa fa-exclamation-triangle"></i>&nbsp;&nbsp;<b><?=$lng['Error']?></b> : ' + response['error'],
							duration: 4,
						})
						return false;
					}
					if(response['success'] != ''){
						$("body").overhang({
							type: "success",
							message: '<i class="fa fa-check"></i>&nbsp;&nbsp;<?=$lng['Data updated successfully']?>',
							duration: 2,
						})
					}
					dtable.ajax.reload(null, false);
					bindHourformat();
					//setTimeout(function(){$('#calculate i').removeClass('fa-refresh fa-spin').addClass('fa-calculator');}, 1000);
				},
				error:function (xhr, ajaxOptions, thrownError){
					$("body").overhang({
						type: "error",
						message: '<i class="fa fa-exclamation-triangle"></i>&nbsp;&nbsp;<?=$lng['Sorry but someting went wrong']?> <b><?=$lng['Error']?></b> : '+thrownError,
						duration: 4,
					})
				}
			});
		})
		
		$(document).on('click', '.editEmployee', function(){
			var id = $(this).data('id')
			$.ajax({
				url: "ajax/get_payroll_line.php",
				data: {id: id},
				dataType: 'json',
				success: function(response) {
					//alert(response)
					//$("#dump").html(response)
					$("#msal1").val(response);
					$("#mday1").val(30);
					$("#mres1").val(response.format(2));
					$('#modalCalc').modal('toggle');
					//dtable.ajax.reload(null, false);
					//setTimeout(function(){$('#calculate i').removeClass('fa-refresh fa-spin').addClass('fa-calculator');}, 1000);
				},
				error:function (xhr, ajaxOptions, thrownError){
					$("body").overhang({
						type: "error",
						message: '<i class="fa fa-exclamation-triangle"></i>&nbsp;&nbsp;<?=$lng['Sorry but someting went wrong']?> <b><?=$lng['Error']?></b> : '+thrownError,
						duration: 3,
					})
				}
			});
		})
		
		$("#addEmp").on('change', function(){
			//$('#calculate i').removeClass('fa-calculator').addClass('fa-refresh fa-spin');
			var id = this.value;
			$.ajax({
				url: "ajax/add_employee_to_attendance.php",
				data: {id: id},
				success: function(response) {
					//$("#dump").html(response)
					location.reload();
					//$("#addEmp option[value="+id+"]").remove();
					//dtable.ajax.reload(null, false);
					//setTimeout(function(){$('#calculate i').removeClass('fa-refresh fa-spin').addClass('fa-calculator');}, 1000);
				}
			});
		})

		var dtable = $('#datatable').DataTable({
			scrollY:        false,//scrY,//heights-288,
			scrollX:        true,
			scrollCollapse: false,
			fixedColumns:   true,
			lengthChange:  	false,
			searching: 		true,
			ordering: 		true,
			paging: 		true,
			pagingType: 'full_numbers',
			pageLength: 	tablerows,
			filter: 		true,
			info: 			true,
			//autoWidth:		false,
			<?=$dtable_lang?>
			processing: 	false,
			serverSide: 	true,
			ajax: {
				url: "ajax/server_get_attendance.php",
				type: "POST",
				"data": function(d){
					d.filter = $('#taxFilter').val();
				}
			},
			columnDefs: [
				{"targets": eCols, "visible": false, "searchable": false},
				{"targets": [0,1], "class": 'pad010 mw65 tal'},
				{"targets": [1], "width": '100%'},
				{"targets": [2], "class": 'tac mw10'},
				{"targets": lastCol, "class": 'tac mw10'},
				{"targets": [3,4,5,6,7,8], "class": 'mw65'},
			],
			initComplete : function( settings, json ) {
				$('#showTable').fadeIn(200);
				dtable.columns.adjust().draw();
				$(".hourFormat").mask("999:99", {placeholder: "000:00"});
			}
		});
		$("#searchFilter").keyup(function() {
			dtable.search(this.value).draw();
			setTimeout(function(){$(".hourFormat").mask("999:99", {placeholder: "000:00"});}, 100);
		});		
		$(document).on("click", "#clearSearchbox", function(e) {
			$('#searchFilter').val('');
			dtable.search('').draw();
			setTimeout(function(){$(".hourFormat").mask("999:99", {placeholder: "000:00"});}, 100);
		})
		$(document).on("change", "#pageLength", function(e) {
			if(this.value > 0){
				dtable.page.len( this.value ).draw();
				bindHourformat();
			}
		})
		$(document).on("change", "#taxFilter", function(e) {
			dtable.ajax.reload(null, false);
			bindHourformat();
		})
		
		var mySelect = $('#showCols').SumoSelect({
			csvDispCount:1,
			outputAsCSV : true,
			showTitle : false,
			placeholder: '<?=$lng['Show Hide Columns']?>',
			captionFormat: '<?=$lng['Show Hide Columns']?>',
			captionFormatAllSelected: '<?=$lng['Show Hide Columns']?>',
		});
		$(".SumoSelect li").bind('click.check', function(event) {
			var nr = $(this).index()+4;
			if($(this).hasClass('selected') == true){
				dtable.column(nr).visible(true);
			}else{
				dtable.column(nr).visible(false);
			}
			bindHourformat();
    })		
		$('select#showCols').on('sumo:closing', function(o) {
			var columns = $(this).val();
			var att_cols = [];
			$.each(columns, function(index, item) {
				//alert(tableCols[item][0])
				att_cols.push({id:item, db:tableCols[item][0], name:tableCols[item][1]})
			})
			$.ajax({
				url: "ajax/save_att_columns.php",
				data: {cols: att_cols},
				success: function(result){
					//$('#dump').html('save_att_columns : '+result);
					
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

		$(document).on("change", "#import_attendance", function(e){
			e.preventDefault();
			var id = cid;
			//alert(cid);
			var ff = $(this).val().toLowerCase();
			ff = ff.replace(/.*[\/\\]/, '');
			var ext =  ff.split('.').pop();
			f = ff.substr(0, ff.lastIndexOf('.'));
			var r = f.split('_');
			//alert(r.length)
			if(!(ext == 'xls' || ext == 'xlsx')){
				$("body").overhang({
					type: "error",
					message: '<i class="fa fa-exclamation-triangle"></i>&nbsp;&nbsp;<?=$lng['Please use Excel files only']?> (.xls, .xlsx)',
					duration: 3,
				})
				return false;
			}
			if(r.length != 4){
				$("body").overhang({
					type: "error",
					message: '<i class="fa fa-exclamation-triangle"></i>&nbsp;&nbsp;<?=$lng['Wrong file format ! Please use']?> [ '+id+'_attendance_'+year+'_'+month+'.xls ]',
					duration: 4,
				})
				return false;
			}else{
				var s1 = r[0];
				var s2 = r[1]; // Filename
				var s3 = r[2]; // Year
				var s4 = r[3].substring(0,2); // Month
				//alert(id+'-'+s1+'-'+s2+'-'+s3+'-'+s4);
				if(s1 != id){
					$("body").overhang({
						type: "error",
						message: '<i class="fa fa-exclamation-triangle"></i>&nbsp;&nbsp;<?=$lng['File ID does not match selected client']?> ('+s1+') ! <?=$lng['Please use']?> [ '+id+'_employees_'+year+'_'+month+'.xls ]',
						duration: 3,
					})
					return false;
				}else if(s2 !== 'attendance'){
					$("body").overhang({
						type: "error",
						message: '<i class="fa fa-exclamation-triangle"></i>&nbsp;&nbsp;<?=$lng['Wrong file name']?> ('+s2+') ! <?=$lng['Please use']?> [ '+id+'_attendance_'+year+'_'+month+'.xls ]',
						duration: 4,
					})
					return false;
				}else if(s3 !== year){
					$("body").overhang({
						type: "error",
						message: '<i class="fa fa-exclamation-triangle"></i>&nbsp;&nbsp;<?=$lng['Wrong year']?> ('+s3+') ! <?=$lng['Please use']?> [ '+id+'_attendance_'+year+'_'+month+'.xls ]',
						duration: 4,
					})
					return false;
				}else if(s4 !== month){ // (s3.indexOf(month) == -1)
					$("body").overhang({
						type: "error",
						message: '<i class="fa fa-exclamation-triangle"></i>&nbsp;&nbsp;<?=$lng['Wrong month']?> ('+s4+') ! <?=$lng['Please use']?> [ '+id+'_attendance_'+year+'_'+month+'.xls ]',
						duration: 4,
					})
					return false;
				}
			}
			$("form#import").submit();
		});
		
		$(document).on("submit", "form#import", function(e){
			e.preventDefault();
			$("#calculate").removeClass('flash');
			$("body").overhang({
				type: "warn",
				message: '<i class="fa fa-exclamation-triangle"></i>&nbsp;&nbsp;<?=$lng['One moment please, importing data']?>&nbsp;&nbsp;<i class="fa fa-refresh fa-spin"></i>',
				closeConfirm: "true",
				//duration: 10,
			})
			$('#impemp i').removeClass('fa-download').addClass('fa-refresh fa-spin');
			var data = new FormData($(this)[0]);
			setTimeout(function(){
			$.ajax({
				url: "ajax/upload_payroll_data.php",
				type: 'POST',
				data: data,
				//async: false,
				cache: false,
				contentType: false,
				processData: false,
				success: function(result){
					$("#calculate").removeClass('flash');
					$("#import_attendance").val('');
					//$('#dump').html(result); return false;
					//alert('jhk')
					setTimeout(function(){
						$(".overhang").slideUp(200); 
						$('#impemp i').removeClass('fa-refresh fa-spin').addClass('fa-download');
					}, 800);
					setTimeout(function(){
						if($.trim(result) == 'success'){
							//setTimeout(function(){$(".overhang").slideUp(200)}, 800);
							//setTimeout(function(){
							$("body").overhang({
								type: "success",
								message: '<i class="fa fa-check"></i>&nbsp;&nbsp;<?=$lng['Data imported successfully. Calculating payroll, please wait']?> . . .',
								duration: 2,
							})
							//}, 1000);
							//$("form#import").trigger('reset');
							$("#import_attendance").val('');
							dtable.ajax.reload(null, false);
							bindHourformat();
							//setTimeout(function(){location.reload();}, 2000);
							return false;
						}else{
							$("body").overhang({
								type: "error",
								message: '<i class="fa fa-exclamation-triangle"></i>&nbsp;&nbsp;'+result,
								duration: 4,
							})
						}	
					}, 1000);	
				},
				error:function (xhr, ajaxOptions, thrownError){
					$("body").overhang({
						type: "error",
						message: '<i class="fa fa-exclamation-triangle"></i>&nbsp;&nbsp;<?=$lng['Sorry but someting went wrong']?> <b><?=$lng['Error']?></b> : '+thrownError,
						duration: 4,
					})
				}
			});}, 300);
		});
		
		<? if(!$paid){ ?>
		$(document).ajaxComplete(function( event,request, settings ) {
			$('.delEmployee').confirmation({
				container: 'body',
				rootSelector: '.delEmployee',
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
					//alert(id);
					//return false;
					$.ajax({
						url: "ajax/delete_employee_from_payroll.php",
						data:{id:$(this).data('id')},
						success: function(ajaxresult){
							location.reload();
							/*dtable.ajax.reload(null, false);
							$.getJSON('ajax/get_missing_employees_payroll.php', function(result) {
								var options = $('#addEmp');
								options.empty()
								options.append(new Option('Add employee', ''));
								$.each(result, function(index, txt) {
									options.append(new Option(txt, index));
								});
							});	*/						
						}
					});
				}
			});
		});
		<? } ?>
		
		$("#createNew").on('click', function(){
			// if(expire <= 0){
			// 	$('#modalExpire').modal('toggle'); 
			// 	return false;
			// }
			
			$.ajax({
				type: "POST",
				url: "ajax/create_new_attendance.php",
				success: function(response) {
					//$("#dump").html(response); return false;
					if($.trim(response) == 'success'){
						location.reload();
					}else if($.trim(response) == 'data'){
						$("body").overhang({
							type: "error",
							message: '<i class="fa fa-exclamation-triangle"></i>&nbsp;&nbsp;No employees available for payroll',
							duration: 4,
						})
					}else{
						$("body").overhang({
							type: "error",
							message: '<i class="fa fa-exclamation-triangle"></i>&nbsp;&nbsp;<?=$lng['Error']?> : ' + response,
							duration: 4,
						})
					}
				},
				error: function (xhr, ajaxOptions, thrownError) {
					$("body").overhang({
						type: "error",
						message: '<i class="fa fa-exclamation-triangle"></i>&nbsp;&nbsp;<?=$lng['Sorry but someting went wrong']?> <b><?=$lng['Error']?></b> : '+thrownError,
						duration: 4,
					})
				}
			});
		})
		
		$(document).on("click", ".showEmpinfo", function(e) {
			e.preventDefault();
			var id = $(this).data('id');
			var name = $(this).data('name');
			//alert(id);
			$.ajax({
				url: "ajax/get_modal_employee_info.php",
				cache: false,
				data: {id: id},
				success:function(result){
					//$('#dump').html(result); return false;
					$('#editemp').attr('href','../employees/index.php?mn=1021&id='+id);
					$('#mt_name').html(id+' '+name);
					$('#EmpInfo').html(result);
					$('#modalEmpinfo').modal('toggle');
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
		
		/*$(document).on('click','#datatable tbody tr', function() {
			var row = $(this).index();
			var id = $(this).closest('tr').find('#empid').val();
		})*/
		
		var change = 0; // saving data first
		$(document).on("change", "input", function(e) {
			change = 1;
			$('#sAlert').fadeIn(200);
			$("#calculate").addClass('flash');
		})
		
		$("#calculate").on('click', function(){
			var data = $("#payrollForm").serialize();
			data += '&change='+change;
			$("#calculate i").removeClass('fa-calculator').addClass('fa-refresh fa-spin');
			//alert(data)
			$.ajax({
				type: "POST",
				url: "ajax/calculate_payroll.php",
				data: data,
				success: function(response) {

					//alert(response);
					//$("#dump").html(response); return false;
					
					$("#calculate i").removeClass('fa-refresh fa-spin').addClass('fa-calculator');
					
					if(response == 'ottime1'){
						$("body").overhang({
							type: "error",
							message: '<i class="fa fa-check"></i>&nbsp;&nbsp; Wrong time format in "<?=$lng['OT 1']?>"<? //=$lng['Payroll calculated successfuly']?>',
							duration: 2,
						})
						$('#sAlert').fadeOut(200);
						$("#calculate").removeClass('flash');
					}else if(response == 'ottime15'){
						$("body").overhang({
							type: "error",
							message: '<i class="fa fa-check"></i>&nbsp;&nbsp; Wrong time format in "<?=$lng['OT 1.5']?>"<? //=$lng['Payroll calculated successfuly']?>',
							duration: 2,
						})
						$('#sAlert').fadeOut(200);
						$("#calculate").removeClass('flash');
					}else if(response == 'ottime2'){
						$("body").overhang({
							type: "error",
							message: '<i class="fa fa-check"></i>&nbsp;&nbsp; Wrong time format in "<?=$lng['OT 2']?>"<? //=$lng['Payroll calculated successfuly']?>',
							duration: 2,
						})
						$('#sAlert').fadeOut(200);
						$("#calculate").removeClass('flash');
					}else if(response == 'ottime3'){
						$("body").overhang({
							type: "error",
							message: '<i class="fa fa-check"></i>&nbsp;&nbsp; Wrong time format in "<?=$lng['OT 3']?>"<? //=$lng['Payroll calculated successfuly']?>',
							duration: 2,
						})
						$('#sAlert').fadeOut(200);
						$("#calculate").removeClass('flash');
					}else if(response == 'abtime'){
						$("body").overhang({
							type: "error",
							message: '<i class="fa fa-check"></i>&nbsp;&nbsp; Wrong time format in "Absence"<? //=$lng['Payroll calculated successfuly']?>',
							duration: 2,
						})
						$('#sAlert').fadeOut(200);
						$("#calculate").removeClass('flash');
					}else if(response == 'latime'){
						$("body").overhang({
							type: "error",
							message: '<i class="fa fa-check"></i>&nbsp;&nbsp; Wrong time format in "Late Early"<? //=$lng['Payroll calculated successfuly']?>',
							duration: 2,
						})
						$('#sAlert').fadeOut(200);
						$("#calculate").removeClass('flash');
					}else if(response == 'lwtime'){
						$("body").overhang({
							type: "error",
							message: '<i class="fa fa-check"></i>&nbsp;&nbsp; Wrong time format in "Leave WOP"<? //=$lng['Payroll calculated successfuly']?>',
							duration: 2,
						})
						$('#sAlert').fadeOut(200);
						$("#calculate").removeClass('flash');
					}else if($.trim(response)=='success'){
						$("body").overhang({
							type: "success",
							message: '<i class="fa fa-check"></i>&nbsp;&nbsp;<?=$lng['Payroll calculated successfuly']?>',
							duration: 2,
						})
						$('#sAlert').fadeOut(200);
						$("#calculate").removeClass('flash');
						dtable.ajax.reload(null, false);
						bindHourformat();
					}else{
						//alert('inthis');
						$("body").overhang({
							type: "error",
							message: '<i class="fa fa-exclamation-triangle"></i>&nbsp;&nbsp;<?=$lng['Sorry but someting went wrong']?>',
							duration: 4,
						})

					}
				},
				error: function (xhr, ajaxOptions, thrownError) {
					$("body").overhang({
						type: "error",
						message: '<i class="fa fa-exclamation-triangle"></i>&nbsp;&nbsp;<?=$lng['Sorry but someting went wrong']?> <b><?=$lng['Error']?></b> : '+thrownError,
						duration: 4,
					})
				}
			})
		})
			
	})
	
	</script>







