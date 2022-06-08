
<style>
	table.basicTable tbody td.pad05 {
		padding:0 5px;
	}
</style>
		
	<h2><i class="fa fa-life-ring"></i>&nbsp; Support desk<? //=$lng['Approvals']?></h2>
	<div class="main">
		<div style="padding:0 0 0 20px" id="dump"></div>
		
			<div id="showTable" style="display:none">
				<table border="0" style="width:100%; margin-bottom:10px">
					<tr>
						<!--<td>
							<select id="customerFilter" class="button">
								<option selected value="">All Customers</option>
								<option value="">PKF Thailand</option>
								<option value="">Other customer</option>
							</select>
						</td>-->
						<td>
							<select id="statusFilter" class="button">
								<option selected value="0">Open tickets</option>
								<option value="1">Closed tickets</option>
								<option value="">All tickets</option>
							</select>
						</td>
						<td style="padding-left:10px">
							<select id="priorFilter" class="button">
								<option value="2">High priority</option>
								<option value="1">Medium priority</option>
								<option value="0">Low priority</option>
								<option selected value="">All priorities</option>
							</select>
						</td>
						<td style="width:90%"></td>
					</tr>
				</table>
				
				<table id="datatable" class="basicTable hoverable selectable" border="0" style="width:100%">
					<thead>
						<tr>
							<th>Ticket ID</th>
							<th>Created</th>
							<th data-sortable="false">Subject</th>
							<th data-sortable="false">Customer</th>
							<th data-sortable="false">Applicant</th>
							<th data-sortable="false">Type</th>
							<th data-sortable="false">Priority</th>
							<th data-sortable="false">Status</th>
							<th data-sortable="false" style="width:60%"></th>
						</tr>
					</thead>
					<tbody>
	
					</tbody>
				</table>
			</div>
		
	</div>
	
	<!-- PAGE RELATED PLUGIN(S) -->
	
	<script type="text/javascript">
		var headerCount = 1;
		
		$(document).ready(function() {
			
			var dtable = $('#datatable').DataTable({
				scrollY:        false,
				scrollX:        true,
				scrollCollapse: false,
				fixedColumns:   false,
				lengthChange:  false,
				searching: true,
				ordering: true,
				paging: true,
				pageLength: 17,
				filter: true,
				info: false,
				<?=$dtable_lang?>
				processing: false,
				serverSide: true,
				order:[1, 'desc'],
				ajax: {
					url: AROOT+"support/ajax/server_get_support.php",
					type: 'POST',
					"data": function(d){
						d.status = $('#statusFilter').val();
						d.priority = $('#priorFilter').val();
						//d.customer = $('#customerFilter').val();
					}
				},
				columnDefs: [
					  {"targets": [0], "class": 'bold' },
					  {"targets": [6,7], "class": 'pad05 tac' },
					  //{"targets": sCols, "visible": true },
				],
				initComplete : function( settings, json ) {
					$('#showTable').fadeIn(200);
					dtable.columns.adjust().draw();
				}
			});
			$('#statusFilter, #priorFilter').on('change', function(){
				dtable.ajax.reload(null, false);
			});
			$(document).on('click', '.ticket', function(){
				var id = $(this).data('id');
				//alert(id)
				window.location.href='index.php?mn=91&id='+id; 
				return false;
			});

		})
	
	</script>























