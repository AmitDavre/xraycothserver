<style>
	table.basicTable tbody td.pad05 {
		padding:0 5px;
	}
</style>
		
	<h2><i class="fa fa-life-ring"></i>&nbsp; <?=$lng['Support desk']?></h2>
	<div class="main">
		<div style="padding:0 0 0 20px" id="dump"></div>
		
		<div id="showTable" style="display:none">
			
			<select id="statusFilter" class="button btn-fl">
				<option selected value="0"><?=$lng['Open tickets']?></option>
				<option value="1"><?=$lng['Closed tickets']?></option>
				<option value=""><?=$lng['All tickets']?></option>
			</select>
			<select id="priorFilter" class="button btn-fl">
				<option value="2"><?=$lng['High priority']?></option>
				<option value="1"><?=$lng['Medium priority']?></option>
				<option value="0"><?=$lng['Low priority']?></option>
				<option selected value=""><?=$lng['All priorities']?></option>
			</select>
			<a href="index.php?mn=707" class="btn btn-primary btn-fr"><i class="fa fa-plus"></i>&nbsp; <?=$lng['New ticket']?></a>
			<div style="clear:both"></div>
			
			<table id="datatable" class="basicTable" border="0" style="width:100%">
				<thead>
					<tr>
						<th><?=$lng['Ticket ID']?></th>
						<th><?=$lng['Created']?></th>
						<th data-sortable="false"><?=$lng['Subject']?></th>
						<!--<th data-sortable="false">Company</th>-->
						<th data-sortable="false"><?=$lng['Applicant']?></th>
						<th data-sortable="false"><?=$lng['Type']?></th>
						<th data-sortable="false"><?=$lng['Priority']?></th>
						<th data-sortable="false"><?=$lng['Status']?></th>
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
				pageLength: 12,
				filter: true,
				info: false,
				<?=$dtable_lang?>
				processing: false,
				serverSide: true,
				order:[1, 'desc'],
				ajax: {
					url: "ajax/server_get_support.php",
					type: 'POST',
					"data": function(d){
						d.status = $('#statusFilter').val();
						d.priority = $('#priorFilter').val();
					}
				},
				columnDefs: [
					  {"targets": [0], "class": 'bold' },
					  {"targets": [5,6], "class": 'pad05 tac' },
				],
				initComplete : function( settings, json ) {
					$('#showTable').fadeIn(400);
					dtable.columns.adjust().draw();
				}
			});
			$('#statusFilter, #priorFilter').on('change', function(){
				dtable.ajax.reload(null, false);
			});
			$(document).on('click', '.ticket', function(){
				var id = $(this).data('id');
				window.location.href='index.php?mn=706&id='+id; 
				return false;
			});

		})
	
	</script>























