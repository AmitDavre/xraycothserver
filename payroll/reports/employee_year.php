<?
	include(DIR.'files/arrays_'.$_SESSION['rego']['lang'].'.php');
	//if(!isset($_GET['sm'])){$_GET['sm'] = 0;}
	
	$emps = getEmployees($cid,0);
	$emp_id = key($emps);

	$emp_array = '[';
	foreach($emps as $k=>$v){
		$emp_array .= "{data:'".$k."',value:'".$k.' - '.$v['en_name']."'},";
	}
	$emp_array = substr($emp_array,0,-1);
	$emp_array .= ']';
	
	//var_dump($emps);
	//var_dump($_SESSION['rego']['report_id']);
	
	$employee = '';
	$id = false;
	if(isset($_SESSION['rego']['report_id'])){
		$employee = $_SESSION['rego']['report_id'].' - '.$emps[$_SESSION['rego']['report_id']][$lang.'_name'];
		$id = true;
	}
	
	$data = array();
	$id = false;
	if(isset($_SESSION['rego']['report_id'])){
		$id = true;
		$sql = "SELECT * FROM ".$cid."_employees WHERE emp_id = '".$_SESSION['rego']['report_id']."'";
		if($res = $dbc->query($sql)){
			if($row = $res->fetch_assoc()){
				$data = $row;
			}
		}
	}

	include('inc_employee_year.php');
	//var_dump($data); exit;
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
}
table.reportTable tbody td.H1 {
	background:#eee;
	color:#900;
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
	
	<h2><i class="fa fa-file-pdf-o"></i>&nbsp; <?=$lng['Overview employee per year']?></h2>		
	
	<div class="main" style="padding-top:15px; top:130px">
		<div style="padding:0 0 0 20px" id="dump"></div>

		<div class="searchFilter" style="width:300px">
			<input style="width:100%; font-size:13px; line-height:27px" class="sFilter" placeholder="<?=$lng['Employee']?> ... <?=$lng['Type for hints']?> ..." type="text" id="selectEmployee" value="<?=$employee?>" />
		</div>
		<button id="exportExcel" <? if(!$id){echo 'disabled';}?> type="button" class="btn btn-primary btn-fr"><i class="fa fa-upload"></i>&nbsp; <?=$lng['Export Excel']?></button>
		<div style="height:8px; clear:both"></div>
			
			<div class="A4form">
				<div style="overflow-x:auto; width:100%">
	
				<table class="reportTable" border="0" style="margin:0">
					<thead>
						<tr>
							<th style="padding:0 0 5px 2px" class="head tal"><?=$lng['Employee']?> <? if($id){echo $data['emp_id'].' '.$title[$data['title']].' '.$data[$lang.'_name'];}?></th>
						</tr>
					</thead>
				</table>
				
				<table class="reportTable" border="0">
					<tbody>
						<tr>
							<th class="H1 tal" colspan="6"><?=$lng['Employee']?></th>
						</tr>
						<tr>
							<td><b><?=$lng['ID']?> :</b> <? if($id){echo $data['emp_id'];}?></td>
							<td><b><?=$lng['Name']?> :</b> <? if($id){echo $data[$lang.'_name'];}?></td>
							<td><b><?=$lng['Position']?> :</b> <? if($id){echo $positions[$data['position']][$lang];}?></td>
							<td><b><?=$lng['Joining date']?> :</b> <? if($id){echo date('d-m-Y', strtotime($data['joining_date']));}?></td>
							<td style="border-right:0"><b><?=$lng['Service years']?> :</b> <? if($id){echo $service_years;}?></td>
							<td style="width:40%; border-left:0"></td>
						</tr>
					</tbody>
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
						

						<? if(isset($pr)){ //var_dump($pr); 
							foreach($pr as $key=>$val){ 
								$class = '';
								if($key == 'gross_income' || $key == 'net_income'){$class = 'H1';}
							?>
							<tr>
								<? foreach($val as $k=>$v){
										if($k > 0){
											if($k == 13){
												echo '<td class="tar bold '.$class.'">'.number_format($v,2).'</td>';
											}else{
												if($v == 0){
													echo '<td class="tar '.$class.'">-</td>';
												}else{
													echo '<td class="tar '.$class.'">'.number_format($v,2).'</td>';
												}
											}
										}else{
											echo '<th class="tar '.$class.'">'.$v.'</th>';
										}
								} ?>
							</tr>
						<? }} ?>
						
						<!--<tr>
							<th class="H1">Totals</th>
							<? foreach($short_months as $v){ 
									echo '<th class="H1 tar">0.00</th>';
							
							} ?>
							<th style="border-right:0" class="H1 tar">0.00</th>
						</tr>-->
					</tbody>
				</table>
				</div>
	
			</div>
			
	</div>
	
	<!-- PAGE RELATED PLUGIN(S) -->
	<script type="text/javascript" src="../js/jquery.autocomplete.js"></script>

<script type="text/javascript">
	
	$(document).ready(function() {
		
		var employees = <?=$emp_array?>;
		//var empID;
		//var emps = <?//=json_encode($emps)?>;
		$('#selectEmployee').devbridgeAutocomplete({
			 lookup: employees,
			 onSelect: function (suggestion) {
			 	//$("#emp_id").val(suggestion.data);
				//empID = suggestion.data;
				//alert(suggestion.data);
				$.ajax({
					//url: ROOT+"reports/ajax/select_employee.php",
					url: "ajax/select_employee.php",
					data: {id: suggestion.data},
					success: function(response) {
						//$('#dump').html(response);
						location.reload();
					},
					error: function (xhr, ajaxOptions, thrownError) {
						alert(thrownError);
					}
				});
			 }
		});	
		
		$('#exportExcel').on('click', function(){
			//window.location.href = '<?=ROOT?>reports/excel/export_employee_year_excel.php';
			window.location.href = 'excel/export_employee_year_excel.php';
		})

	});
	
</script>

	
	
	
	
	
	
	
	
	
	
	
	
	
	

