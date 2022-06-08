<?
	/*if($_SESSION['rego']['access']['leave']['module'] == 0){
		echo '<div class="msg_nopermit">You have no permission<br>to enter this page</div>'; 
		exit;
	}*/

?>
<style>
	table.dataTable tbody td, table.dataTable thead th {
		text-align:center;
	}
	table.dataTable tbody td {
		min-width:24px;
		padding:5px 8px !important;
	}
</style>
	
	<h2><i class="fa fa-table"></i>&nbsp; Report center</h2>

	<div class="main">
			
			<table border="0" style="width:100%; margin-bottom:8px; xdisplay:none"><tr>
				<td>
					<div class="searchFilter" style="margin:0px; width:220px">
						<input style="width:" placeholder="<?=$lng['Filter']?> employee" id="searchFilter" class="sFilter" type="text" />
						<button id="clearSearchbox" type="button" class="clearFilter btn btn-default btn-sm"><i class="fa fa-times"></i></button>
					</div>
				</td>
				<td style="padding-left:5px">
					<div class="dpicker">
						<input readonly placeholder="From" class="xdate_month" id="from" style="width:120px" type="text" value="<?=$leave_period_start?>" />
						<button onclick="$('#from').focus()" type="button"><i class="fa fa-calendar"></i></button>
					</div>
				</td>
				<td style="padding-left:5px">
					<div class="dpicker">
						<input readonly placeholder="Until" class="xdate_month" id="until" style="width:120px" type="text" value="<?=$leave_period_end?>" />
						<button onclick="$('#until').focus()" type="button"><i class="fa fa-calendar"></i></button>
					</div>
				</td>
				<td style="width:80%"></td>
				<td style="padding-left:5px">
					<!-- <button type="button" class="btn btn-primary"><i class="fa fa-file-pdf-o"></i>&nbsp; <?=$lng['PDF']?></button> -->
				</td>
				<td style="padding-left:5px">
					<button type="button" class="btn btn-primary"><i class="fa fa-file-excel-o"></i>&nbsp; <?=$lng['Excel']?></button>
				</td>
			
			</tr></table>
			
			<div id="showTable" style="display:none">
				<table id="datatable" class="dataTable nowrap compact" width="100%">
					<thead>
					<tr>
						<th class="tac par30" style="width:1%"><?=$lng['ID']?></th>
						<th style="width:50%"><?=$lng['Name']?></th>
						<!--<th data-sortable="false"><?=$lng['Entity']?></th>
						<th data-sortable="false"><?=$lng['Branch']?></th>
						<th data-sortable="false"><?=$lng['Division']?></th>
						<th data-sortable="false"><?=$lng['Department']?></th>
						<th data-sortable="false"><?=$lng['Team']?></th>
						<th data-sortable="false"><?=$lng['Employee']?></th>-->
						<th class="tal" style="min-width:50px" data-toggle="tooltip" title="% Attendance">Att %</th>
						<th class="tac" data-sortable="false" data-toggle="tooltip" title="Planned leave">PL</th>
						<th class="tac" data-sortable="false" data-toggle="tooltip" title="Unplanned leave">uPL</th>
						<th class="tac" data-sortable="false" data-toggle="tooltip" title="% Unplanned leave">uPL%</th>
						<? foreach($leave_types as $k=>$v){ ?>
						<th class="tac" data-sortable="false" data-toggle="tooltip" title="<?=$v[$lang]?>"><?=$k?></th>
						<? } ?>
					</tr>
					</thead>
					<tbody>
	
					</tbody>
				</table>
			</div>
			
	</div>
	
	<!-- PAGE RELATED PLUGIN(S) -->
	
	<script type="text/javascript">
		//var heights = window.innerHeight-260;
		//var scrY = heights;//true;
		var headerCount = 1;
		
		$(document).ready(function() {
			//alert('ready');
			
			var dtable = $('#datatable').DataTable({
				scrollY:        false,//scrY,//heights-260,
				scrollX:        false,
				scrollCollapse: false,
				fixedColumns: 	false,
				lengthChange: 	false,
				pageLength: 	16,
				paging: 		true,
				searching:		true,
				ordering:		true,
				filter: 		false,
				info: 			true,
				//autoWidth:		false,
				//order: [[ 8, "desc" ]],
				<?=$dtable_lang?>
				processing: false,
				serverSide: true,
				columnDefs: [
					//{ targets: [1,2,3,4], class: 'tal' },
					{ targets: [0,1], class: 'tal' },
					//{ targets: [8], sType: "numeric" },
					//{ targets: [2,3],  visible: false }
				],
				ajax: {
					url: "ajax/server_get_report_data.php",
					"data": function(d){
						d.from = $("#from").val();
						d.until = $("#until").val();
					}
        		},
				initComplete : function( settings, json ) {
					$('#showTable').fadeIn(200);
					//$(".fa-repeat").removeClass('fa-repeat fa-spin').addClass('fa-table');
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
			$(document).on("change", "#branchFilter", function(e) {
				var s = $(this).val();
				dtable.column(2).search(s).draw();
			})
			$(document).on("change", "#groupFilter", function(e) {
				var s = $(this).val();
				dtable.column(3).search(s).draw();
			})
			$(document).on("change", "#depFilter", function(e) {
				var s = $(this).val();
				dtable.column(4).search(s).draw();
			})
		
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
			

		});
		
</script>















