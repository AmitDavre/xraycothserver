<?php
	
	$account = array();
	if(isset($_SESSION['agent']['agent_id'])){
		if($res = $dbx->query("SELECT * FROM rego_customers WHERE agent = '".$_SESSION['agent']['agent_id']."'")){
			while($row = $res->fetch_assoc()){
				$account[$row['id']] = $row;
			}
		}
	}
	//var_dump($account); exit;
	
?>

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
		<div><i class="fa fa-bar-chart fa-lg"></i>&nbsp; <?=$lng['My account']?></div>
		<a style="float:right" href="index.php?mn=2"><i class="fa fa-home"></i></a>
	</div>			
  
	<div style="position:absolute; top:50px; bottom:0; left:0; right:0; padding:5px; background:#f6f6f6">
		
		<ul class="list-group">
			<? if($account){ foreach($account as $k=>$v){ ?>
				<li data-id="<?=$k?>" class="list-group-item rounded-0 account_details">
					<div style="cursor:pointer">
						<b><?=$v['en_compname']?></b>
						<?=$v['name']?>
						<div style="position:absolute; right:10px; top:8px; text-align:right"><?=$version[$v['version']]?><br>Exp. <?=date('d-m-Y', strtotime($v['period_end']))?></div>
					</div>
				</li>
			<? }}else{ ?>
				<li class="list-group-item rounded-0"><?=$lng['No data available']?></li>
			<? } ?>
		</ul>
		
	</div>
  
	<!-- Modal -->
	<div class="modal fade" id="detailsModal" tabindex="-1" role="dialog" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-body">
					<div id="detailsTable"></div>
				</div>
			</div>
		</div>
	</div>








