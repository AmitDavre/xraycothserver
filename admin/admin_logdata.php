 
<?php
	
	//var_dump($emp_status);
	$log_cols = array();
		$log_cols[4] = array('user_ip','User IP');
		$log_cols[] = array('server_ip','Server IP');
		$log_cols[] = array('platform','Platform');
		$log_cols[] = array('browser','Browser');
		$log_cols[] = array('uri','URI');
	
	$sCols[] = 4;
	//$sCols[] = 5;
	//$sCols[] = 6;
	$sCols[] = 7;
	//$sCols[] = 8;
	//var_dump(json_encode($sCols));
	$now = date('d-m-Y');
	$from = date('d-m-Y',(strtotime ('-3 day', strtotime($now))));
	$until = date('d-m-Y',(strtotime ('+1 day', strtotime($now))));

?>
<link rel="stylesheet" type="text/css" media="screen" href="../assets/css/sumoselect.css?<?=time()?>">
<style>
.SumoSelect{
	padding: 5px 5px 5px 10px !important;
	border:1px #ddd solid !important;
}
.SumoSelect.open > .optWrapper {
	top:29px !important; 
}
</style>
	
	<h2><i class="fa fa-database"></i>&nbsp; Log Data</h2>
	<div class="main">
		<div style="padding:0 0 0 20px" id="dump"></div>
	
			<div id="showTable" style="display:none">
				
				<div class="searchFilter">
					<input placeholder="<?=$lng['Filter']?>" id="searchFilter" class="sFilter" type="text" />
					<button id="clearSearchbox" type="button" class="clearFilter btn btn-default btn-sm"><i class="fa fa-times"></i></button>
				</div>
				
				<div class="dpicker btn-fl">
					<input readonly placeholder="From" class="xdate_month" id="from" style="width:120px" type="text" value="<?=$from?>" />
					<button data-toggle="tooltip" title="From" onclick="$('#from').focus()" type="button"><i class="fa fa-calendar"></i></button>
				</div>
				
				<div class="dpicker btn-fl">
					<input readonly placeholder="Until" class="xdate_month" id="until" style="width:120px" type="text" value="<?=$until?>" />
					<button data-toggle="tooltip" title="Until" onclick="$('#until').focus()" type="button"><i class="fa fa-calendar"></i></button>
				</div>
				
				<select id="typeFilter" class="button btn-fl">
					<option selected value="log">Log data</option>
					<option value="error">Error data</option>
					<option value="action">Action data</option>
				</select>
				
				<? //if($_SESSION['rego']['access']['employee']['export'] == 1){ ?>
				<!--<button disabled id="expLogdata" type="button" class="btn btn-primary btn-fr"><i class="fa fa-upload"></i> Export data</button>-->	
				<? //} ?>
         <div class="clear"></div>
				    
			<table id="datatable" class="dataTable hoverable selectable nowrap">
				<thead>
				<tr>
					<th><?=$lng['Name']?></th>
					<th data-sortable="false" style="width:1px" class="tac vam"><i class="fa fa-user fa-lg"></i></th>
					<th xdata-sortable="false">Client ID</th>
					<th class="par30"><?=$lng['Date']?></th>
					<th xdata-visible="false" data-sortable="false" class="par30">User IP</th>
					<th xdata-visible="false" data-sortable="false" class="par30">Server IP</th>
					<th xdata-visible="false" data-sortable="false">OS</th>
					<th xdata-visible="false" data-sortable="false">Browser</th>
					<th data-visible="false" data-sortable="false">URI</th>
					<th data-sortable="false">Action</th>
				</tr>
				</thead>

			</table>
			<input type="hidden" id="incomplete" value="0" />
         </div>
			
		</div>
	</div>
	
	<!--<div style="position:fixed; top:0; left:0; background:rgba(0,255,0,0.4); height:216px; width:100%"></div>
	<div style="position:fixed; top:216px; left:0; background:rgba(255,0,0,0.4); height:504px; width:100%"></div>
	<div style="position:fixed; bottom:0; left:0; background:rgba(0,0,255,0.4); height:87px; width:100%"></div>-->

	<!-- PAGE RELATED PLUGIN(S) -->
	<script type="text/javascript" src="../assets/js/jquery.sumoselect.js"></script>

	<script type="text/javascript">
		
		var height = window.innerHeight-303;
		var headerCount = 1;
		
		$(document).ready(function() {
			
			var year = <?=json_encode($_SESSION['RGadmin']['cur_year'])?>;
			
			var from = $('#from').datepicker({
				format: "dd-mm-yyyy",
				autoclose: true,
				inline: true,
				language: '<?=$lang?>-en',//lang+'-th',
				startView: 'year',
				todayHighlight: true,
				//startDate : startYear,
				//endDate   : endYear
			}).on('changeDate', function(e){
				$('#until').datepicker('setDate', '').datepicker('setStartDate', from.val()).focus();
			});
			
			var until = $('#until').datepicker({
				format: "dd-mm-yyyy",
				autoclose: true,
				inline: true,
				language: '<?=$lang?>-en',//lang+'-th',
				startView: 'year',
				todayHighlight: true,
				//startDate : startYear,
				//endDate   : endYear
			}).on('changeDate', function(e){
				dtable.ajax.reload(null, false);
			});
			
			var mySelect = $('#showCols').SumoSelect({
				//okCancelInMulti:true, 
				//selectAll:true,
				csvDispCount:1,
				outputAsCSV : true,
				showTitle : false,
				placeholder: '<?=$lng['Show Hide Columns']?>',
				captionFormat: '<?=$lng['Show Hide Columns']?>',
				captionFormatAllSelected: '<?=$lng['Show Hide Columns']?>',
			});
			
			/*$(".SumoSelect li").bind('click.check', function(event) {
				var nr = $(this).index()+4;
				if($(this).hasClass('selected') == true){
					dtable.column(nr).visible(true);
				}else{
					dtable.column(nr).visible(false);
					
				}
			})*/	
			
			//var att_cols = [];
			/*$('#showCols').on('sumo:closed', function(o) {
				var columns = $(this).val();
				var att_cols = [];
				jQuery.each(columns, function(index, item) {
					//alert(tableCols[item][0])
					att_cols.push({id:item, db:tableCols[item][0], name:tableCols[item][1]})
				})
				$.ajax({
					url:"xajax/save_att_columns.php",
					data: {cols: att_cols},
					success: function(result){
						//$('#dump').html(result);
						//window.location.href='files/export_empty_attendance_excel.php'
					},
					error:function (xhr, ajaxOptions, thrownError){
						alert(thrownError);
					}
				});
			});*/
	
	
			//var sCols = <? //=json_encode($sCols)?>;
			var rows = Math.floor(height/29.64);
			
			var dtable = $('#datatable').DataTable({
				scrollY:        false,
				scrollX:        false,
				scrollCollapse: false,
				//fixedColumns:   false,
				lengthChange:  false,
				searching: 		true,
				ordering: 		true,
				paging: 			true,
				pageLength: 	rows,
				filter: 			true,
				info: 			false,
				<?=$dtable_lang?>
				processing: 	false,
				serverSide: 	true,
				//autoWidth:		false,
				order: [[3, 'desc']],
				ajax: {
					url: "ajax/server_get_logdata.php",
					type: 'POST',
					data: function(d){
						d.action = $('#typeFilter').val();
						d.from = $('#from').val();
						d.until = $('#until').val();
					}
				},
				columnDefs: [
					  { targets: [1], "class": 'pad1' },
					  //{ targets: sCols, visible: true },
					  { targets: [9], width: '80%' },
					  //{ Type: "numeric" },
				],
				initComplete : function( settings, json ) {
					$('#showTable').fadeIn(200);
					dtable.columns.adjust().draw();
				}
			});
			setTimeout(function(){
				//$("#statFilter").trigger('change');
			},50);
			$("#searchFilter").keyup(function() {
				var s = $(this).val();
				dtable.search(s).draw();
			});
			$(document).on("click", "#clearSearchbox", function(e) {
				$('#searchFilter').val('');
				dtable.search('').draw();
			})
			$(document).on("change", "#typeFilter", function(e) {
				dtable.ajax.reload(null, false);
			})
			$(document).on("change", "#depFilter", function(e) {
				var s = $(this).val();
				dtable.column(5).search(s).draw();
			})

			$(document).on("click", "#exportEmployees", function(e){
				$("#modalExportFields").modal('toggle');
			})
			$('#exportForm').on("submit", function(e) {
				e.preventDefault();
				var data = $(this).serialize();
				$.ajax({
					url: ROOT+"employees/ajax/update_employee_export_fields.php",
					data: data,
					type: 'POST',
					success: function(result){
						//$('#dump').html(result);
						window.location.href = 'employees/export_employee_register_excel.php?'+$('#action').val();
						$("#modalExportFields").modal('toggle');
					},
					error:function (xhr, ajaxOptions, thrownError){
						$('#message').html('<div style="margin:0" class="msg_alert">'+thrownError+'</div>').hide().fadeIn(400);
					}
				});
			})
			
			/*$('a[data-toggle="tab"]').on('shown.bs.tab', function(e) {
				localStorage.setItem('activeTab07', $(e.target).data('target'));
			});
			var activeTab = localStorage.getItem('activeTab07');
			if(activeTab){
				//alert(activeTab)
				$('#myTab a[data-target="' + activeTab + '"]').tab('show');
			}else{
				$('#myTab a[data-target="#tab_personal"]').tab('show');
			}*/
				
				
			
		})
	
	</script>





































