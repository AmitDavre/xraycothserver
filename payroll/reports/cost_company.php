<?
	include('inc_cost_company.php');
	//var_dump($xtotals);
	//var_dump($data);
	//$data = array();
?>	
<style>
.A4form {
	width:100%;
	xmargin:10px 10px 10px 15px;
	background:#fff; 
	padding:20px 30px 20px 30px; 
	box-shadow:0 0 10px rgba(0,0,0,0.4); 
	position:relative;
	min-height:500px;
}
table.reportTable {
	width:100%;
	border-collapse:collapse;
	font-size:13px;
	margin-bottom:10px;
}
table.reportTable b {
	font-weight:600;
	color:#039;
}
table.reportTable thead th {
	padding:4px 8px;
	font-weight:600;
	text-align:center;
	font-size:13px;
}
table.reportTable thead th.head {
	padding:4px 8px;
	font-weight:600;
	text-align:center;
	font-size:16px;
}
table.reportTable tbody th {
	padding:2px 8px;
	white-space:nowrap;
	font-weight:600;
	border:1px solid #eee;
	border-left:0;
}
table.reportTable tbody td {
	padding:2px 8px;
	border:1px solid #eee;
}
table.reportTable tbody td.bold {
	font-weight:600;
}
table.reportTable tbody td:first-child {
	border-left:0;
}
table.reportTable tbody td:last-child {
	border-right:0;
}
table.reportTable tbody th.H1 {
	background:#eee;
	color:#900;
	padding:2px 8px;
	font-weight:600;
	border:1px solid #fff;
	border-bottom:1px solid #bbb;
}
table.reportTable tbody th.H1:last-child {
	xborder-right:0;
}


	xtable.reportTable tbody tr {
		border-bottom:1px solid #eee;
	}
	xtable.reportTable tbody th, 
	xtable.reportTable tbody td {
		padding:3px 8px;
	}
	table.reportTable tbody th {
		text-align:right;
	}
</style>
	
	<h2><i class="fa fa-file-text-o"></i>&nbsp; <?=$lng['Cost to company per year']?></h2>		
	
	<div class="main" style="padding-top:15px; top:130px">
		<div style="padding:0 0 0 20px" id="dump"></div>
			
		<button <? if(!$data){echo 'disabled';}?> id="exportExcel" type="button" class="btn btn-primary btn-fr"><i class="fa fa-upload"></i>&nbsp; <?=$lng['Export Excel']?></button>
		<div style="height:8px; clear:both"></div>
			
			<div class="A4form">
				<div style="overflow-x:auto; width:100%">
					<table class="reportTable" border="0" style="margin:0">
					<thead>
						<tr>
							<th style="padding:0 2px 5px 0" class="head tal"><?=$lng['Cost to company per year']?></th>
						</tr>
					</thead>
				</table>
				
				<table class="reportTable" border="0" style="">
					<tbody>
						<tr>
							<th class="H1" style="width:1%"><?=$lng['Month']?></th>
							<? foreach($months as $v){ 
									echo '<th class="H1 tar" style="width:7%">'.$v.'</th>';
							} ?>
							<th class="H1 tar" style="width:7%"><?=$lng['Total']?></th>
						</tr>

						<? foreach($data as $key=>$val){ ?>
							<tr>
								<? foreach($val as $k=>$v){
										if($k > 0){
											if($k == 13){
												echo '<td class="tar">'.number_format($v,2).'</td>';
											}else{
												if($v == 0){
													echo '<td class="tar">-</td>';
												}else{
													echo '<td class="tar">'.number_format($v,2).'</td>';
												}
											}
										}else{
											echo '<th class="tar">'.$v.'</th>';
										}
								} ?>
							</tr>
						<? } if($data){ ?>
						
						<tr>
							<th class="H1"><?=$lng['Subtotal']?></th>
							<? foreach($totals as $v){ 
									echo '<th class="H1 tar">'.number_format($v,2).'</th>';
							} ?>
						</tr>
						
						<tr><td style="height:10px"></td></tr>

						<? foreach($xdata as $key=>$val){?>
							<tr>
								<? foreach($val as $k=>$v){
										if($k > 0){
											if($k == 13){
												echo '<td class="tar">'.number_format($v,2).'</td>';
											}else{
												if($v == 0){
													echo '<td class="tar">-</td>';
												}else{
													echo '<td class="tar">'.number_format($v,2).'</td>';
												}
											}
										}else{
											echo '<th class="tar">'.$v.'</th>';
										}
								} ?>
							</tr>
						<? } ?>
						
						<tr>
							<th class="H1"><?=$lng['Totals']?></th>
							<? foreach($xtotals as $v){ 
									echo '<th class="H1 tar">'.number_format($v,2).'</th>';
							}} ?>
						</tr>
					</tbody>
				</table>
				</div>
			</div>

	</div>
	
	<!-- PAGE RELATED PLUGIN(S) -->

<script type="text/javascript">
	
	$(document).ready(function() {
		
		$('#exportExcel').on('click', function(){
			window.location.href = 'excel/export_cost_company_excel.php';
		})

	});
	
</script>

	
	
	
	
	
	
	
	
	
	
	
	
	
	

