<?
	if(!isset($_GET['m'])){$_GET['m'] = $_SESSION['rego']['cur_month'];}
	//var_dump($cols); exit;
	include('inc_attendance_month.php');
	//echo '<pre>';
	
	//var_dump($data);
	//echo '</pre>';
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
	white-space:nowrap;
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
	white-space:normal;
	min-width:80px;
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
	
	<h2><i class="fa fa-file-text-o"></i>&nbsp; <?=$lng['Attendance per month']?></h2>		
	
	<div class="main" style="padding-top:15px; top:130px">
		<div style="padding:0 0 0 20px" id="dump"></div>
			
		<select id="monthFilter" style="padding:4px 7px 3px; border:1px #ddd solid; margin:0 0 8px; width:auto; float:left">
		<? foreach($months as $k=>$v){
				echo '<option ';
				if($k == $_GET['m']){echo 'selected ';}
				echo 'value="'.$k.'">'.$v.'</option>';
			} ?>
		</select>
		<button <? if(!$data){echo 'disabled';}?> id="exportExcel" type="button" class="btn btn-primary btn-fr"><i class="fa fa-upload"></i>&nbsp; <?=$lng['Export Excel']?></button>
		<div style="height:8px; clear:both"></div>

			<div class="A4form">
				<div style="overflow-x:auto; width:100%">
					<table class="reportTable" border="0" style="margin:0">
					<thead>
						<tr>
							<th style="padding:0 2px 5px 0" class="head tal"><?=$lng['Attendance report']?> <?=$months[$_GET['m']]?></th>
						</tr>
					</thead>
				</table>
				
				<table class="reportTable" border="0" style="">
					<tbody>
						<tr>
							<? foreach($cols as $k=>$v){ 
									$ta = 'tac'; $width = 'width:7%';
									if($k == 'emp_id' || $k == 'emp_name_en' || $k == 'emp_name_th'){$ta = 'tal';}
									if($k == 'emp_name_en' || $k == 'emp_name_th'){$width = 'width:50%';}
									if($k == 'emp_id'){$width = 'width:1%';}
									echo '<th class="H1 '.$ta.'" style="'.$width.'">'.$v.'</th>';
							} ?>
						</tr>

						<? foreach($data as $key=>$val){ ?>
							<tr>
								<? foreach($val as $k=>$v){
										if($k != 'emp_id' && $k != 'emp_name_en' && $k != 'emp_name_th'){
											if($v == 0){
												echo '<td class="tar">-</td>';
											}else{
												echo '<td class="tar">'.number_format($v,2).'</td>';
											}
										}else{
											echo '<td class="tal">'.$v.'</td>';
										}
								} ?>
							</tr>
						<? } ?>
						
					</tbody>
				</table>
				</div>
			</div>

	</div>
	
	<!-- PAGE RELATED PLUGIN(S) -->

<script type="text/javascript">
	
	$(document).ready(function() {
		
		$('#monthFilter').on('change', function(){
			window.location.href = "index.php?mn=453&m="+this.value;
		})
		
		$('#exportExcel').on('click', function(){
			window.location.href = 'excel/export_attendance_month_excel.php?m=' + <?=$_GET['m']?>;
		})
	});
	
</script>

	
	
	
	
	
	
	
	
	
	
	
	
	
	

