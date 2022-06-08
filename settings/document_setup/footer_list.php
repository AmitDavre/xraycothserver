<?
	$data = array();
	$sql = "SELECT * FROM ".$cid."_footer_templates";
	if($res = $dbc->query($sql)){
		while($row = $res->fetch_assoc()){
			$data[] = $row;
		}
	}
?>
<style type="text/css">
	#datatable tbody td{
		cursor: pointer;;
	}
</style>
<h2 style="padding-right:60px">
	<i class="fa fa-file-text"></i>&nbsp; <?=$lng['Footer templates']?>
</h2>

	<div class="main">
		<div style="padding:0 0 0 20px" id="dump"></div>

			<div class="row">
				<div class="col-md-3">
					<div class="searchFilter" style="margin:0 0 8px 0;width: 200px;">
						<input style="margin:0" placeholder="Filter" class="sFilter" id="searchFilter" type="text">
						<button id="clearSearchbox" type="button" class="clearFilter"><i class="fa fa-times"></i></button>
					</div>
				</div>
				<div class="col-md-7"></div>
				<div class="col-md-2">
					
					<a type="button" onclick="window.location.href='index.php?mn=311&fid=n';" class="btn btn-primary btn-fr">
						<i class="fa fa-plus pr-2"></i> <?=$lng['New'].' '.$lng['Footer']?>
					</a>
				</div>
			</div>
					
			<table id="datatable" class="dataTable nowrap hoverable" style="width:100%;">
				<thead>
					<tr>
						<th><?=$lng['ID']?> #</th>
						<th><?=$lng['Description']?></th>
						<th><?=$lng['Remarks']?></th>
						<th data-sortable="false"><i class="fa fa-trash fa-lg"></i></th>
					</tr>
				</thead>
				<tbody>
					<?if(isset($data) && is_array($data)){ 
						foreach($data as $v){ ?>
						<tr>
							<td onclick="rowClick(<?=$v['id']?>)"><?=$v['id']?></td>
							<td onclick="rowClick(<?=$v['id']?>)"><?=$v['note']?></td>
							<td onclick="rowClick(<?=$v['id']?>)"><?=$v['remark']?></td>
							<td><a id="<?=$v['id']?>" onclick="RemoveFooter(this)"><i class="fa fa-trash fa-lg"></i></a></td>	
						</tr>
					<? } } ?>
				</tbody>
			</table>
			<div class="row">
				<div class="col-md-2" style="margin: -33px 0px 0px 180px;">
					<select id="pageLength" class="button btn-fl">
						<option selected="" value="">Rows / page</option>
						<option value="10">10 Rows / page</option>
						<option value="15">15 Rows / page</option>
						<option value="20">20 Rows / page</option>
						<option value="30">30 Rows / page</option>
						<option value="40">40 Rows / page</option>
						<option value="50">50 Rows / page</option>
					</select>
				</div>
			</div>

	</div>
	<script type="text/javascript">

		function RemoveFooter(that){
			var id = that.id; 
			$.ajax({
				url:"document_setup/ajax/remove_footer.php",
				type: 'POST',
				data: {id: id},
				success: function(response){

					$("body").overhang({
						type: "success",
						message: '<i class="fa fa-check"></i>&nbsp;&nbsp;Footer removed successfully',
						duration: 3,
						callback: function(v){
							location.reload();
						}
					})
				}
			})
		}

		function rowClick(fid) {
			window.location.href='index.php?mn=311&fid='+fid;
		}

		$(document).ready(function() {

			var dtable = $('#datatable').DataTable({

				"lengthChange":  false,
				"searching": true,
				pagingType: 'full_numbers',
				pageLength: 10,
				<?=$dtable_lang?>

			});

			$("#searchFilter").keyup(function() {
				dtable.search(this.value).draw();
			});
			$("#clearSearchbox").click(function() {
				$("#searchFilter").val('');
				dtable.search('').draw();
			});

			$(document).on("change", "#pageLength", function(e) {
				if(this.value > 0){
					dtable.page.len( this.value ).draw();
				}
			});

		});
	</script>