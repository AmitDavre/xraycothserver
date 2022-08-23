<?


	$AllSystemUsersList = AllSystemUsersList();

	$data = array();
	$sql = "SELECT * FROM ".$cid."_comm_centers";
	if($res = $dbc->query($sql)){
		while($row = $res->fetch_assoc()){
			$data[] = $row;
		}
	}



?>
<style type="text/css">
	#datatable tbody td{
		cursor: pointer;
	}
</style>
<h2 style="padding-right:60px">
	<i class="fa fa-comments"></i>&nbsp; <?=$lng['Communication center']?>
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
				<div class="col-md-5"></div>
				<div class="col-md-4">
					
					<a type="button" onclick="window.location.href='index.php?mn=802';" class="btn btn-primary btn-fr">
						<i class="fa fa-plus pr-2"></i> <?=$lng['Add Communication']?>
					</a>
				</div>
			</div>
					
			<table id="datatable" class="dataTable nowrap hoverable" style="width:100%;">
				<thead>
					<tr>
						<th><?=$lng['ID']?> #</th>
						<th><?=$lng['Description']?></th>
						<th><?=$lng['Month']?></th>
						<th><?=$lng['Date']?></th>
						<th><?=$lng['Username']?></th>
						<th><?=$lng['Announcement From']?></th>
						<th><?=$lng['Type']?></th>
						<th><?=$lng['Mode']?></th>
						<th><?=$lng['Category']?></th>
						<th><?=$lng['Send to']?></th>
						<th><?=$lng['Status']?></th>
						<th data-sortable="false"><i class="fa fa-trash fa-lg"></i></th>
					</tr>
				</thead>
				<tbody>
					<?if(isset($data) && is_array($data)){ 
						foreach($data as $v){ 

							//foe status badges...
							if($v['status'] == 1){
								$classBadge = 'badge badge-warning p-1';
							}elseif($v['status'] == 2){
								$classBadge = 'badge badge-primary p-1';
							}elseif($v['status'] == 3){
								$classBadge = 'badge badge-success p-1';
							}else{
								$classBadge = 'badge badge-warning p-1';
							}
								

							if($v['anno_category'] == 2){

								if($_SESSION['RGadmin']['access']['comm_center']['private_msg'] == 1 || $_SESSION['rego']['comm_center']['private_msg'] == 1){ ?>

									<tr id="Row_<?=$v['id']?>" data-status="<?=$v['status']?>">
										<td class="font-weight-bold" onclick="rowClick(<?=$v['id']?>)"><?=$v['anno_id']?></td>
										<td onclick="rowClick(<?=$v['id']?>)"><?=mb_strlen($v['description']) > 30 ? mb_substr($v['description'], 0, 30) . "..." : $v['description'];?></td>
										<td onclick="rowClick(<?=$v['id']?>)"><?=$v['month']?></td>
										<td onclick="rowClick(<?=$v['id']?>)"><?=$v['date']?></td>
										<td onclick="rowClick(<?=$v['id']?>)"><?=$AllSystemUsersList[$v['username']]?></td>
										<td onclick="rowClick(<?=$v['id']?>)"><?=$v['fromss']?></td>
										<td onclick="rowClick(<?=$v['id']?>)"><?=$Announcementtype[$v['anno_type']]?></td>
										<td onclick="rowClick(<?=$v['id']?>)"><?=$AnnouncementMode[$v['anno_mode']]?></td>
										<td onclick="rowClick(<?=$v['id']?>)"><?=$AnnouncementCategory[$v['anno_category']]?></td>
										<td onclick="rowClick(<?=$v['id']?>)"><?=$Topersons[$v['tooss']]?></td>
										<td onclick="rowClick(<?=$v['id']?>)">
											<a class="<?=$classBadge?>"><?=$CCStatus[$v['status']]?></a>
										</td>
										<td>
											<?if($v['status'] != '3'){ 
												if($_SESSION['rego']['comm_center']['del'] == 1){ ?>
													<a id="<?=$v['id']?>" data-toggle="tooltip" title="Remove" onclick="RemoveCommCenter(this);"><i class="fa fa-trash fa-lg"></i></a>
											<? } } ?>
										</td>	
									</tr>
									
							<?	} //private_msg

							}else{ ?>

								<tr id="Row_<?=$v['id']?>" data-status="<?=$v['status']?>">
									<td class="font-weight-bold" onclick="rowClick(<?=$v['id']?>)"><?=$v['anno_id']?></td>
									<td onclick="rowClick(<?=$v['id']?>)"><?=mb_strlen($v['description']) > 30 ? mb_substr($v['description'], 0, 30) . "..." : $v['description'];?></td>
									<td onclick="rowClick(<?=$v['id']?>)"><?=$v['month']?></td>
									<td onclick="rowClick(<?=$v['id']?>)"><?=$v['date']?></td>
									<td onclick="rowClick(<?=$v['id']?>)"><?=$AllSystemUsersList[$v['username']]?></td>
									<td onclick="rowClick(<?=$v['id']?>)"><?=$v['fromss']?></td>
									<td onclick="rowClick(<?=$v['id']?>)"><?=$Announcementtype[$v['anno_type']]?></td>
									<td onclick="rowClick(<?=$v['id']?>)"><?=$AnnouncementMode[$v['anno_mode']]?></td>
									<td onclick="rowClick(<?=$v['id']?>)"><?=$AnnouncementCategory[$v['anno_category']]?></td>
									<td onclick="rowClick(<?=$v['id']?>)"><?=$Topersons[$v['tooss']]?></td>
									<td onclick="rowClick(<?=$v['id']?>)"><a class="<?=$classBadge?>"><?=$CCStatus[$v['status']]?></a></td>
									<td>
										<?if($v['status'] != '3'){
											if($_SESSION['rego']['comm_center']['del'] == 1){ ?>
												<a id="<?=$v['id']?>" data-toggle="tooltip" title="Remove" onclick="RemoveCommCenter(this);"><i class="fa fa-trash fa-lg"></i></a>
										<? } } ?>
									</td>	
								</tr>
					<? } }  } ?>
				</tbody>
			</table>

			<div class="row">
				<div class="col-md-2" style="margin: -35px 0px 0px 185px;">
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
	<div class="openHelp"><i class="fa fa-question-circle fa-lg"></i></div>
	<div id="help">
		<div class="closeHelp"><i class="fa fa-arrow-circle-right"></i></div>
		<div class="innerHelp">
			<?=$helpfile?>
		</div>
	</div>
	<script type="text/javascript">

		function RemoveCommCenter(that){
			var id = that.id; 
			$.ajax({
				url: "ajax/remove_comm_center.php",
				type: 'POST',
				data: {id: id},
				success: function(response){

					$("body").overhang({
						type: "success",
						message: '<i class="fa fa-check"></i>&nbsp;&nbsp;Data removed successfully',
						duration: 3,
						callback: function(v){
							location.reload();
						}
					})
				}
			})
		}

		function rowClick(ccid) {

			/*var status = $('tr#Row_'+ccid).data('status');
			if(status == 3){
				$("body").overhang({
					type: "info",
					message: '<i class="fa fa-check"></i>&nbsp;&nbsp;This announcement is locked',
					duration: 3,
					callback: function(v){
						location.reload();
					}
				})
			}else{*/
				window.location.href='index.php?mn=802&ccid='+ccid;
			//}
		}

		$(document).ready(function() {

			var dtable = $('#datatable').DataTable({

				"lengthChange":  false,
				"searching": true,
				"order": [ [3,'desc'], [0,'desc'] ],
				pagingType: 'full_numbers',
				pageLength: 15,
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
