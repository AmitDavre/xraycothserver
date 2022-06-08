
<style>
	.list-group-item {
		font-size:16px;
		text-align:left;
		line-height:140%;
		border-radius:0;
	}
	.list-group-item.active {
		font-size:20px;
		font-weight:500;
		text-align:left;
	}
	.list-group-item b {
		font-weight:500;
		display:block;
	}
	.list-group-item span {
		display:block;
		color:#c00;
	}
	.list-group-item .status {
		position:absolute;
		top:10px;
		right:12px;
		font-size:15px;
		background:#aaa;
		color:#fff;
		width:40px;
		height:40px;
		line-height:40px;
		xborder-radius:50%;
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
		background: #b00;
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
	.list-group-item .delete:before {
		position:absolute;
		top:10px;
		right:10px;
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
	.table-sm th,
	.table-sm td {
		padding:3px 10px !important;
	}
</style>

	<div class="header page_header">
		<div><i class="fa fa-id-card-o"></i>&nbsp; <?=$lng['Personal data']?></div>
		<a href="index.php?mn=2"><i class="fa fa-home"></i></a>
	</div>			
  
	<div style="position:absolute; top:50px; bottom:0; left:0; right:0; padding:5px; background:#f6f6f6">

						<table class="listTable table-bordered table-striped">
							<tbody>
								<tr>
									<th style="width:10%">Agent ID<? //=$lng['Agent ID']?></th>
									<td><?=$data['agent_id']?></td>
								</tr>
								<tr>
									<th><?=$lng['Name']?></th>
									<td><?=$data['th_name']?></td>
								</tr>
								<tr>
									<th>Name English<? //=$lng['Name English']?></th>
									<td><?=$data['en_name']?></td>
								</tr>
								<tr>
									<th><?=$lng['Phone']?></th>
									<td><?=$data['phone']?></td>
								</tr>
								<tr>
									<th><?=$lng['email']?></th>
									<td><?=$data['email']?></td>
								</tr>
								<tr>
									<th><?=$lng['Line ID']?></th>
									<td><?=$data['line_id']?></td>
								</tr>
								<tr>
									<th>Region<? //=$lng['Region']?></th>
									<td><?=$data['region']?></td>
								</tr>
								<tr>
									<th>Startdate<? //=$lng['Startdate']?></th>
									<td><?=$data['startdate']?></td>
								</tr>
								<tr>
									<th>Other job<? //=$lng['Other job']?></th>
									<td><?=$data['other_job']?></td>
								</tr>
								<tr>
									<th><?=$lng['Address']?></th>
									<td><?=$data['address']?></td>
								</tr>
								<tr>
									<th>Tax ID<? //=$lng['Tax ID']?></th>
									<td><?=$data['tax_id']?></td>
								</tr>
								<tr>
									<th><?=$lng['Status']?></th>
									<td><?=$def_status[$data['status']]?></td>
								</tr>
								<tr>
									<th><?=$lng['Certificate']?></th>
									<td>
										<? if(!empty($data['certificate'])){ ?>
													<a download href="<?=ROOT.'admin/uploads/agents/'.$data['certificate']?>"><u><?=$data['certificate']?></u></a>
										<? }else{ ?>
													Not uploaded
										<? } ?>
									</td>
								</tr>
								<tr>
									<th>Agreement<? //=$lng['Agreement']?></th>
									<td>
										<? if(!empty($data['agreement'])){ ?>
													<a download href="<?=ROOT.'admin/uploads/agents/'.$data['agreement']?>"><u><?=$data['agreement']?></u></a>
										<? }else{ ?>
													Not uploaded
										<? } ?>
									</td>
								</tr>
								<tr>
									<th>ID Card<? //=$lng['ID Card']?></th>
									<td>
										<? if(!empty($data['idcard'])){ ?>
													<a download href="<?=ROOT.'admin/uploads/agents/'.$data['idcard']?>"><u><?=$data['idcard']?></u></a>
										<? }else{ ?>
													Not uploaded
										<? } ?>
								</tr>
							</tbody>
						</table>
		
	</div>
  














