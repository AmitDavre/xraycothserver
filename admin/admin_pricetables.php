<?
	
	$price_schedule = array();
	$price_activities = array();
	$sql = "SELECT price_schedule, price_activities FROM rego_company_settings";
	if($res = $dba->query($sql)){
		if($row = $res->fetch_assoc()){
			$price_schedule = unserialize($row['price_schedule']);
			$price_activities = unserialize($row['price_activities']);
		}
	}else{
		echo 'Error : '.mysqli_error($dba);
	}
	if(empty($price_activities)){
		$price_activities[4]['activity'] = '';
		$price_activities[4]['min_price'] = 0;
		$price_activities[4]['rate'] = '';
		$price_activities[4]['price'] = 0;
	}
	//$price_schedule[100] = $price_schedule[50];
/*			'max_employees' => string '20' (length=2)
      'price_month' => string '0' (length=1)
      'price_year' => string '0' (length=1)
      'discount' => string '0' (length=1)
      'duration' => string '1' (length=1)
      'emp_com_platform' => string '1' (length=1)
      'leave' => string '0' (length=1)
      'time' => string '0' (length=1)	
	
	
*/	
	//$price_schedule[200] = $price_schedule[100];
	//var_dump($price_schedule); exit;
	//unset($price_activities[5],$price_activities[6],$price_activities[7],$price_activities[8],$price_activities[9],$price_activities[10],$price_activities[11],$price_activities[12]);
	//var_dump($price_activities); exit;

?>

<style>
	.xbasicTable {  
		width:100%;
		table-layout:auto;
		border-collapse:collapse;
		border:none;
		font-size:13px;
		color:#000;
		white-space:nowrap;
	}
	.xbasicTable thead tr {
		border-bottom: 1px #ccc solid;
		background:#eee;
	}
	.xbasicTable tfoot tr {
		border-bottom: 1px #eee solid;
		background:#fff;
	}
	.xbasicTable thead tr th {
		text-align:left;
		width:5%;
		color:#005588;
		font-weight:600;
		vertical-align:middle;
		padding:4px 10px;
		border-right:1px #fff solid;
	}
	.xbasicTable thead tr th:last-child {
		border-right:0;
	}
	.xbasicTable tfoot tr td:last-child {
		border-right:0;
	}
	.xbasicTable thead tr.tac th {
		text-align:center;
	}
	.xbasicTable thead tr.tar th {
		text-align:right;
	}
	.xbasicTable tbody tr {
		border-bottom: 1px #eee solid;
	}
	.xbasicTable tbody.nopadding td,
	.xbasicTable tfoot.nopadding td {
		padding:0;
	}
	.xbasicTable tbody td, 
	.xbasicTable tfoot td { 
		text-align:left;
		vertical-align: middle;
		padding:4px 10px;
		font-weight:400;
		border-right:1px #eee solid;
	}
	.xbasicTable tfoot td.hl { 
		background: #ffd;
	}
	.xbasicTable tbody td.pad410, 
	.xbasicTable tfoot td.pad410 { 
		padding:4px 10px;
	}
	.xbasicTable tbody.bold td, 
	.xbasicTable tfoot.bold td {
		font-weight:600;
	}
	.xbasicTable tbody td:last-child {
		border-right:0;
	}
	.xbasicTable tbody td input[type=text], 
	.xbasicTable tbody td input[type=password], 
	.xbasicTable tbody td select,
	.xbasicTable tfoot td input[type=text], 
	.xbasicTable tfoot td input[type=password], 
	.xbasicTable tfoot td select {
		width:100%;
		padding:4px 10px;
		border:0;
		border-bottom:1px #fff solid;
		margin:0;
		line-height:normal;
		box-sizing: border-box;
		display:inline-block;
		text-align:right;
		background:transparent;
	}
	.xbasicTable tbody td select {
		padding:3px 6px;
		width:auto;
	}
	.tal {
		text-align:left;
	}
	.tac {
		text-align:center;
	}
	.tar {
		text-align:right;
	}
	
</style>
	
	<h2><i class="fa fa-table"></i>&nbsp;&nbsp;<?=$lng['Price tables']?> <span style="float:right; display:none; font-style:italic; color:#b00" id="sAlert"><?=$lng['Data is not updated to last changes made']?></span></h2>
	<div class="main">
		<div style="padding:0 0 0 20px" id="dump"></div>
				
		<form id="scheduleForm">
			<table id="sheduleTable" class="xbasicTable">
				<thead>
					<tr class="tar">
						<th class="tal" style="min-width:120px"><?=$lng['Subscription']?></th>
						<th><?=$lng['Max employees']?></th>
						<th><?=$lng['Price / month']?></th>
						<th><?=$lng['Price / year']?></th>
						<th><?=$lng['Discount']?></th>
						<th class="tal"><?=$lng['Duration']?></th>
						<th class="tal">Mobile access<? //=$lng['Emp. com. platform']?></th>
						<th class="tal">Leave module<? //=$lng['Emp. com. platform']?></th>
						<th class="tal">Time module<? //=$lng['Emp. com. platform']?></th>
						<th style="width:80%"></th>
					</tr>
				</thead>
				<tbody class="nopadding tar">
					<? if($price_schedule){ foreach($price_schedule as $k=>$v){ ?>
					<tr>
						<td style="padding:4px 10px"><?=$version[$k]?></td>
						<td><input class="sel numeric5" type="text" name="price_schedule[<?=$k?>][max_employees]" value="<?=$price_schedule[$k]['max_employees']?>"></td>
						<td><input class="sel numeric5" type="text" name="price_schedule[<?=$k?>][price_month]" value="<?=$price_schedule[$k]['price_month']?>"></td>
						<td><input class="sel numeric5" type="text" name="price_schedule[<?=$k?>][price_year]" value="<?=$price_schedule[$k]['price_year']?>"></td>
						<td><input class="sel numeric5" type="text" name="price_schedule[<?=$k?>][discount]" value="<?=$price_schedule[$k]['discount']?>"></td>
						<td style="min-width:120px">
							<select name="price_schedule[<?=$k?>][duration]" style="width:100%">
								<option <? if($price_schedule[$k]['duration']==1){echo 'selected';}?> value="1">1 <?=$lng['Month']?></option>
								<option <? if($price_schedule[$k]['duration']==12){echo 'selected';}?> value="12">12 <?=$lng['Months']?></option>
							</select>
						</td>
						<td>
							<select name="price_schedule[<?=$k?>][emp_com_platform]" style="width:100%">
								<option <? if($price_schedule[$k]['emp_com_platform']=='0'){echo 'selected';}?> value="0"><?=$lng['No']?></option>
								<option <? if($price_schedule[$k]['emp_com_platform']=='1'){echo 'selected';}?> value="1"><?=$lng['Yes']?></option>
							</select>
						</td>
						<td>
							<select name="price_schedule[<?=$k?>][leave]" style="width:100%">
								<option <? if($price_schedule[$k]['leave']=='0'){echo 'selected';}?> value="0"><?=$lng['No']?></option>
								<option <? if($price_schedule[$k]['leave']=='1'){echo 'selected';}?> value="1"><?=$lng['Yes']?></option>
							</select>
						</td>
						<td>
							<select name="price_schedule[<?=$k?>][time]" style="width:100%">
								<option <? if($price_schedule[$k]['time']=='0'){echo 'selected';}?> value="0"><?=$lng['No']?></option>
								<option <? if($price_schedule[$k]['time']=='1'){echo 'selected';}?> value="1"><?=$lng['Yes']?></option>
							</select>
						</td>
						<td></td>
					</tr>
					<? } } ?>
				</tbody>
			</table>
			
			<div style="height:10px"></div>
			<button style="float:right" id="submitbtn" class="btn btn-primary" type="submit"><i class="fa fa-save"></i>&nbsp;&nbsp;<?=$lng['Update']?></button>
					
		</form>
		<div style="clear:both; height:15px"></div>
		
		<form id="activityForm">
			<table id="activityTable" class="xbasicTable">
				<thead>
					<tr class="tar">
						<th class="tal" style="min-width:400px">Activity<? //=$lng['Activity']?></th>
						<th style="min-width:120px">Min. Price<? //=$lng['Max employees']?></th>
						<th style="min-width:120px">Unit rate<? //=$lng['Price / month']?></th>
						<th style="min-width:120px">Price / Unit<? //=$lng['Price / year']?></th>
						<th style="width:80%"></th>
						<th><i class="fa fa-trash fa-lg"></i></th>
					</tr>
				</thead>
				<tbody class="nopadding tar">
					<? if($price_activities){ foreach($price_activities as $k=>$v){ ?>
					<tr>
						<td><input placeholder="__" class="tal" type="text" name="price_activities[<?=$k?>][activity]" value="<?=$price_activities[$k]['activity']?>
"></td>
						<td><input class="sel numeric5" type="text" name="price_activities[<?=$k?>][min_price]" value="<?=$price_activities[$k]['min_price']?>"></td>
						<td><input placeholder="__" type="text" name="price_activities[<?=$k?>][rate]" value="<?=$price_activities[$k]['rate']?>"></td>
						<td><input class="sel numeric5" type="text" name="price_activities[<?=$k?>][price]" value="<?=$price_activities[$k]['price']?>"></td>
						<td></td>
						<td class="tac"><a class="delActivity"><i class="fa fa-trash fa-lg"></i></a></td>
					</tr>
					<? } } ?>
				</tbody>
			</table>
			<div style="height:10px"></div>
			
			<button style="float:left" id="addActivity" class="btn btn-primary btn-xs" type="button"><i class="fa fa-plus"></i>&nbsp;&nbsp;Add line<? //=$lng['Add line']?></button>
					
			<button style="float:right" id="asubmitbtn" class="btn btn-primary" type="submit"><i class="fa fa-save"></i>&nbsp;&nbsp;<?=$lng['Update']?></button>
					
		</form>
		<div style="clear:both"></div>



   </div>
   
<script>

	$(document).ready(function() {
	
		var aCnt = '<?=json_encode(count($price_activities))?>'
		$("#addActivity").click(function(e){
			var row = 
				'<tr>'+
					'<td><input placeholder="__" class="tal" type="text" name="price_activities['+aCnt+'][activity]"></td>'+
					'<td><input class="sel numeric5" type="text" name="price_activities['+aCnt+'][min_price]" value="0"></td>'+
					'<td><input placeholder="__" type="text" name="price_activities['+aCnt+'][rate]"></td>'+
					'<td><input class="sel numeric5" type="text" name="price_activities['+aCnt+'][price]" value="0"></td>'+
					'<td></td>'+
					'<td class="tac"><a class="delActivity"><i class="fa fa-trash fa-lg"></i></a></td>'+
				'</tr>';
				aCnt ++;
			$("#activityTable tbody").append(row);
		}) 
		$(document).on('click', '.delActivity', function(e){
			$(this).closest('tr').remove();
		})
		
		$("#scheduleForm").submit(function(e){ 
			e.preventDefault();
			$("#submitbtn i").removeClass('fa-save').addClass('fa-refresh fa-spin');
			var data = $(this).serialize();
			$.ajax({
				type: 'POST',
				url: AROOT+"ajax/save_price_schedule.php",
				data: data,
				success: function(result){
					//$("#dump").html(result); return false
					if(result == 'success'){
						$("body").overhang({
							type: "success",
							message: '<i class="fa fa-check"></i>&nbsp;&nbsp;Data updated successfuly',
							duration: 2,
						})
					}else{
						$("body").overhang({
							type: "warn",
							message: '<i class="fa fa-exclamation-triangle"></i>&nbsp;&nbsp;<?=$lng['Error']?> : '+result,
							duration: 4,
							closeConfirm: true
						})
					}
					setTimeout(function(){
						$("#submitbtn i").removeClass('fa-refresh fa-spin').addClass('fa-save');
						$("#submitbtn").removeClass('flash');
						$("#sAlert").fadeOut(200);
					},300);
				},
				error:function (xhr, ajaxOptions, thrownError){
					$("body").overhang({
						type: "error",
						message: '<i class="fa fa-exclamation-triangle"></i>&nbsp;&nbsp;<?=$lng['Sorry but someting went wrong']?> <b><?=$lng['Error']?></b> : '+thrownError,
						duration: 8,
						closeConfirm: "true",
					})
				}
			});
		});
		
		$("#activityForm").submit(function(e){ 
			e.preventDefault();
			$("#asubmitbtn i").removeClass('fa-save').addClass('fa-refresh fa-spin');
			var data = $(this).serialize();
			$.ajax({
				type: 'POST',
				url: AROOT+"ajax/save_price_activities.php",
				data: data,
				success: function(result){
					//$("#dump").html(result); return false
					if(result == 'success'){
						$("body").overhang({
							type: "success",
							message: '<i class="fa fa-check"></i>&nbsp;&nbsp;Data updated successfuly',
							duration: 2,
						})
					}else{
						$("body").overhang({
							type: "warn",
							message: '<i class="fa fa-exclamation-triangle"></i>&nbsp;&nbsp;<?=$lng['Error']?> : '+result,
							duration: 4,
							closeConfirm: true
						})
					}
					setTimeout(function(){
						$("#asubmitbtn i").removeClass('fa-refresh fa-spin').addClass('fa-save');
						$("#asubmitbtn").removeClass('flash');
						$("#sAlert").fadeOut(200);
					},300);
				},
				error:function (xhr, ajaxOptions, thrownError){
					$("body").overhang({
						type: "error",
						message: '<i class="fa fa-exclamation-triangle"></i>&nbsp;&nbsp;<?=$lng['Sorry but someting went wrong']?> <b><?=$lng['Error']?></b> : '+thrownError,
						duration: 8,
						closeConfirm: "true",
					})
				}
			});
		});
		
		setTimeout(function(){
			$('body').on('change', '#scheduleForm input, #scheduleForm select', function (e) {
				$("#submitbtn").addClass('flash');
				$("#sAlert").fadeIn(200);
			});	
			$('body').on('change', '#activityForm input', function (e) {
				$("#asubmitbtn").addClass('flash');
				$("#sAlert").fadeIn(200);
			});	
		},500);

	});

</script>
						




























