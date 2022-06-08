<?php

?>	

<style>
	.list-group-item {
		font-size:15px;
		text-align:left;
		line-height:140%;
		border-radius:0;
		padding:5px 10px !important;
	}
	.list-group-item.active {
		font-size:20px;
		font-weight:500;
		text-align:left;
		padding:8px 20px !important;
	}
	.list-group-item b {
		font-weight:500;
		display:block;
	}
	.list-group-item b.action {
		font-weight:500;
		display:block;
		padding-left:5px;
		font-size:16px;
		color:#666;
	}
	.list-group-item span {
		display:block;
		color:#c00;
		font-weight:500;
	}
	.list-group-item .status {
		position:absolute;
		top:5px;
		right:5px;
		font-size:15px;
		background:#aaa;
		color:#fff;
		width:40px;
		height:40px;
		line-height:40px;
		border-radius:3px;
		text-align:center; 
	}
	.list-group-item .status.RQ {
		background: #aaa;
	}
	.list-group-item .status.RQ:before {
		font-family: "FontAwesome";
		font-size:20px;
		content: "\f254";
	}

	.list-group-item .status.AP {
		background: #009966;
	}
	.list-group-item .status.AP:before {
		font-family: "FontAwesome";
		font-size:24px;
		content: "\f164";
	}

	.list-group-item .status.RJ {
		background: #c00;
	}
	.list-group-item .status.RJ:before {
		font-family: "FontAwesome";
		font-size:24px;
		content: "\f165";
	}

	.list-group-item .status.CA {
		background: #f90;
	}
	.list-group-item .status.CA:before {
		font-family: "FontAwesome";
		font-size:24px;
		content: "\f00d";
	}
	
	.list-group-item .status.TA {
		background: #09c;
	}
	.list-group-item .status.TA:before {
		font-family: "FontAwesome";
		font-size:24px;
		content: "\f1da";
	}
	
	
	
	.list-group-item .delete:before {
		position:absolute;
		top:10px;
		right:60px;
		font-size:15px;
		background:#c00;
		color:#fff;
		width:40px;
		height:40px;
		line-height:40px;
		xborder-radius:50%;
		text-align:center; 
		font-family: "FontAwesome";
		font-size:24px;
		content: "\f1f8";
		cursor:pointer;
	}
	.list-group-item .image {
		width:60px;
		position:absolute;
		top:7px;
		left:7px;
	}
	.list-group-item .image img {
		border-radius:3px !important;
	}
	.noStyle {
		border:0;
		background:#fff;
	}
	.noStyle tr {
		border-bottom:1px solid #ddd;
	}
	.noStyle tr:last-child {
		border-bottom:0;
	}
	.noStyle td {
		border:0;
		background:#fff;
		padding:2px 10px;
		border-right:1px solid #ddd;
	}
	.table-sm td {
		padding:5px 10px !important;
	}
	.table-sm th {
		padding:0 10px !important;
		line-height:30px;
	}
</style>


	<div class="container-fluid" style="padding:0">
		
		<div style="padding:10px">							
			<div class="btn-group btn-group-toggle" data-toggle="buttons" style="width:100%">
				<label class="btn btn-outline-default active <?=$appTime?>" <?=$appTime?>onClick="window.location.href='index.php?mn=1702'">
					<input type="radio" checked><?=$lng['Time']?>
				</label>
			</div>
		</div>
		

		
		<div class="tab-content mt-2" style="padding:0 10px">
			
			<div class="tab-pane fade show active" id="pending" role="tabpanel">

				<table border="0" style="width:100%; table-layout:fixed; border-collapse:collapse; margin-bottom:3px">
					<tr>
						<td>
							
							<button data-toggle="modal" data-target="#startModal" type="button" class="btn btn-primary btn-lg btn-block tac"><span style="font-size: 18px;line-height: 18px;" id="leavestart"><i class="fa fa-calendar"></i><?=$lng['Select']?></span></button>

						


						</td>
						<td>
							<button data-toggle="modal" data-target="#leavetypeModal" type="button" class="btn btn-danger btn-lg btn-block tac"><span id="btn_leavetype">Show all<? //=$lng['Select Leave type']?></span></button>
						</td>
					</tr>
				</table>
			
				<ul class="list-group">
					<li class="list-group-item rounded-0" style="padding:0 !important; border:0">
		
						<table id="regDatatable" border="0" width="100%" class="table table-sm tac table-bordered table-striped">
							<thead>
								<tr style="background:#ccc; border:1px solid #ccc">
									<th class="tal">Employee</th>
									<th class="tac">First</th>
									<th class="tac">Last</th>
								</tr>
							</thead>
							<tbody>

							</tbody>
						</table>
					</li>
				</ul>
				<div style="height:10px"></div>
			
			
			</div>

		</div>
		
		
			
	</div>	


		<!-- Modal -->
	<div class="modal fade" id="leavetypeModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel5" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-body" style="color:#333">
					<div class="list-group">
							<a data-dismiss="modal" class="myList regFilter list-group-item" data-id="" onclick="gettimedata('all');">Show all</a>
							<a data-dismiss="modal" class="myList regFilter list-group-item" data-id="scan" onclick="gettimedata('scan');">All incomplete scans</a>
							<a data-dismiss="modal" class="myList regFilter list-group-item" data-id="ctime" onclick="gettimedata('ctime');">All confirm plan</a>
							<a data-dismiss="modal" class="myList regFilter list-group-item" data-id="itime" onclick="gettimedata('itime');">All non-confirm plan</a>
							<a data-dismiss="modal" class="myList regFilter list-group-item" data-id="ot" onclick="gettimedata('ot');">All with overtime</a>
							<a data-dismiss="modal" class="myList regFilter list-group-item" data-id="leave" onclick="gettimedata('leave');">All with leave</a>
							<a data-dismiss="modal" class="myList regFilter list-group-item" data-id="late" onclick="gettimedata('late');">All late/early</a>
							<a data-dismiss="modal" class="myList regFilter list-group-item" data-id="plan" onclick="gettimedata('plan');">All without plan</a>
					</div>
				</div>
			</div>
		</div>
	</div>
			

				<!-- Modal -->
	<div class="modal fade my-modal" id="startModal" tabindex="-1" role="dialog" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title"><?=$lng['Select']?></h5>
				</div>
				<div class="modal-body" style="color:#333">
					<div class="list-group">
						<div id="regDate"></div>
					</div>
				</div>
			</div>
		</div>
	</div>
	
			
<script type="text/javascript">
	
	function gettimedata(value)
	{
		var dateSelect = $('#leavestart').html();

		if(dateSelect == '<i class="fa fa-calendar"></i>Select')
		{
			alert('Please Select Date First');
			return false;
		}
		else
		{
			// RUN AJAX AND GET DATA 

			$("#regDatatable > tbody").html("");
			$.ajax({
				url: "ajax/get_time_data.php",
				data: {dateSelect: dateSelect, scanValue: value},
				success: function(result){
					var data = JSON.parse(result);
					console.log(data);

					$.each(data, function(index, value) {
		                var tr ='<tr style="background:#ccc; border:1px solid #ccc"><td class="tal">'+value.en_name+'</td><td class="tac">'+value.scan1+'</td><td class="tac">'+value.scan2+'</td></tr>';
		                $('#regDatatable tbody').append(tr);
            		});





				},
				error:function (xhr, ajaxOptions, thrownError){
					alert(thrownError);
				}
			});

		}
	}
	
	
</script>	










