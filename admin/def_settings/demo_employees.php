<?

?>
 
<style>

</style>
	
	<h2><i class="fa fa-cogs"></i>&nbsp;&nbsp;<?=$lng['Demo employees']?><span style="float:right; display:none; font-style:italic; color:#b00" id="sAlert"><?=$lng['Data is not updated to last changes made']?></span></h2>
	
	<div class="main">
		<div style="padding:0 0 0 20px" id="dump"></div>
		
		<div id="showTable" style="display:none">
			<table id="datatable" class="dataTable nowrap" style="width:100%">
				<thead>
				<tr>
					<th><?=$lng['ID']?></th>
					<th style="width:1px; text-align:center !important"><i class="fa fa-user fa-lg"></i></th>
					<th style="width:1px"><i data-toggle="tooltip" title="<?=$lng['Edit employee']?>" class="fa fa-pencil-square-o fa-lg"></i></th>
					<th><?=$lng['Name']?></th>
					<th><?=$lng['Basic salary']?></th>
					<th><?=$lng['SSO']?></th>
					<th><?=$lng['PVF']?></th>
					<th><?=$lng['Tax']?></th>
					<th><?=$lng['Tax method']?></th>
					<th><?=$lng['Tax deductions']?></th>
					<th style="width:90%"><?=$lng['Status']?></th>
				</tr>
				</thead>

			</table>
		</div>

	</div>
	
<script>
	
	var headerCount = 1;
	
	$(document).ready(function() {
		
		var dtable = $('#datatable').DataTable({
			scrollY:        	false,
			scrollX:        	false,
			scrollCollapse: 	false,
			fixedColumns:   	false,
			lengthChange:  	false,
			searching: 			false,
			ordering: 			false,
			paging: 				false,
			pageLength: 		15,
			filter: 				false,
			info: 				false,
			<?=$dtable_lang?>
			processing: 		false,
			serverSide: 		true,
			ajax: {
				url: "def_settings/ajax/server_get_demo_employees.php",
			},
			columnDefs: [
				  {"targets": [1], "class": 'pad1' },
				  {"targets": [8], "class": 'tac' },
				  {"targets": [4,9], "class": 'tar' }
			],
			initComplete : function( settings, json ) {
				$('#showTable').fadeIn(200);
				dtable.columns.adjust().draw();
			}
		});
		
		
	});

</script>	













