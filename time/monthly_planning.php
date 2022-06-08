<?
	//$shiftteams = getShifTeams();
	//var_dump($shiftteams); exit;
	if(!isset($_GET['m'])){$_GET['m'] = $_SESSION['rego']['cur_month'];}
	$today = strtotime(date('Y-m-d', strtotime("+1 day")));
	$ym = $_SESSION['rego']['cur_year'].'-'.$_GET['m'].'-';
	$str = $_SESSION['rego']['cur_year'].'-'.$_GET['m'].'-1';
	$cols = date('t', strtotime($str));
	//$now = strtotime($ym.date('d'));
	//var_dump($str);
	//var_dump($cols);

	// echo '<pre>';
	// print_r($_SESSION);
	// echo '</pre>';
	// die();
	//var_dump(substr(date('l', strtotime($ym.'1')),0,2));
	
	$getDefault = 'disabled';
	$sql = "SELECT month FROM ".$cid."_monthly_shiftplans_".$cur_year." WHERE month = ".$_GET['m']." GROUP BY month LIMIT 1";
	if($res = $dbc->query($sql)){
		if($res->num_rows == null){
			$getDefault = '';
		}
	}
	//var_dump($getDefault);
	
	$emps = getActiveEmployees();

	// echo '<pre>';
	// print_r($emps);
	// echo '</pre>';
	// die();
	$existing_emps = array();
	$sql = "SELECT emp_id FROM ".$cid."_monthly_shiftplans_".$cur_year." WHERE month = ".$_GET['m'];
	if($res = $dbc->query($sql)){
		while($row = $res->fetch_assoc()){
			$existing_emps[] = $row['emp_id'];
		}
	}
	//var_dump($existing_emps);
	foreach($existing_emps as $v){
		unset($emps[$v]);
	}
	//var_dump($emps); exit;
	
	$teams = array();
	$tmp = getShifTeams();
	if($_SESSION['rego']['teams'] == 'all'){
		$teams = $tmp;
	}else{
		if(!empty($_SESSION['rego']['teams'])){
			$team = explode(',', $_SESSION['rego']['teams']);
			foreach($team as $k=>$v){
				$teams[$v] = $tmp[$v];
			}
		}
	}
	//var_dump($teams);



	// get team names of employees 

	$empDetails = array();
	$res14 = $dbc->query("SELECT * FROM ".$cid."_employees ORDER BY emp_id ASC");
	if($res14->num_rows > 0){
		while($row14 = $res14->fetch_assoc()){

			$empDetails[] = ['emp_id' => $row14['emp_id'] , 'team' => $row14['teams']];

		}
	}


	// get all teams name with scan value 

	$res15 = $dbc->query("SELECT * FROM ".$cid."_shiftplans_".$cur_year."");
	if($res15->num_rows > 0){
		while($row15 = $res15->fetch_assoc()){

			$scanvalues[] = unserialize($row15['ss_data']);

		}
	}

	$scanValArr = array();
	foreach ($scanvalues as $key15 => $value15) {

		$scanValArr[] = ['team' => $value15['team_id'], 'noscan' => $value15['noscan']];
	}


	$teamArr = array();
	foreach ($scanValArr as $key16 => $value16) {

		if($value16['noscan'] == '1')
		{
			$teamArr[] = $value16['team'];
		}


	}

	$finalEmpId = array();

	foreach ($empDetails as $key17 => $value17) {

		if(in_array($value17['team'], $teamArr)){
					$finalEmpId[] = $value17['emp_id']; // includes no scan team members 
		}
	}




	$notActive = array();

	// GET INACTIVE EMPLOYEES 
	$res10 = $dbc->query("SELECT * FROM ".$cid."_employees WHERE emp_status NOT IN (1) AND teams NOT IN ('noteam') ORDER BY emp_id ASC");
	if($res10->num_rows > 0){
		while($row10 = $res10->fetch_assoc()){
			$notActive[] = $row10['emp_id'];
		}
	}	




	// GET EMPLOYEES FROM MONTHLY PLANNING 

	$MonthEmp = array();
	$missingEmp = array();
	$res12 = $dbc->query("SELECT * FROM ".$cid."_monthly_shiftplans_".$cur_year." WHERE month = ".$_GET['m']);
	if($res12->num_rows > 0){
		while($row12 = $res12->fetch_assoc()){
			$MonthEmp[] = $row12['emp_id'];
			}
	}

	$ActiveEmp = array();
	$res11 = $dbc->query("SELECT * FROM ".$cid."_employees WHERE emp_status = '1' AND time_reg = '1'  ORDER BY emp_id ASC");
	if($res11->num_rows > 0){
		while($row11 = $res11->fetch_assoc()){
			$ActiveEmp[] = $row11['emp_id'];

			if(!in_array($row11['emp_id'], $MonthEmp)){
					$notActives[] = $row11['emp_id']; // includes no scan team members 
			}
		}
	}




	$finalDropDown = array();
	foreach ($notActives as $key18 => $value18) {

		// if(!in_array($value18, $finalEmpId)){
					$finalDropDown[] = $value18; // includes no scan team members 
			// }

	}


	$res13 = $dbc->query("SELECT * FROM ".$cid."_monthly_shiftplans_".$cur_year." WHERE month = ".$_GET['m']);
	if($res13->num_rows > 0){
		while($row13 = $res13->fetch_assoc()){
			$empMonth[] = $row13['emp_id'];
		}
	}	

	$res14 = $dbc->query("SELECT * FROM ".$cid."_employees  WHERE emp_status = '1' AND time_reg = '1' ORDER BY emp_id ASC");
	if($res14->num_rows > 0){
		while($row14 = $res14->fetch_assoc()){
			$allemp[] = $row14['emp_id'];
		}
	}

	foreach ($allemp as $key3 => $value3) {

		if (!in_array($value3, $empMonth)) {
			    $defaultPlanArray[] = $value3;
			}

		 
	}






?>

<style>
	.redbold {
		font-weight:600;
		color:#000;
	}

	@-webkit-keyframes blinkred {
	  from { background-color: red; }
	  to { background-color: inherit; }
	}
	@-moz-keyframes blinkred {
	  from { background-color: red; }
	  to { background-color: inherit; }
	}
	@-o-keyframes blinkred {
	  from { background-color: red; }
	  to { background-color: inherit; }
	}
	@keyframes blinkred {
	  from { background-color: red; }
	  to { background-color: inherit; }
	}
	.blinkred {
	  -webkit-animation: blinkred 1s infinite; /* Safari 4+ */
	  -moz-animation:    blinkred 1s infinite; /* Fx 5+ */
	  -o-animation:      blinkred 1s infinite; /* Opera 12+ */
	  animation:         blinkred 1s infinite; /* IE 10+ */
	}



	table.mytable thead th {
		text-align:center;
		line-height:100%;
		font-weight:600;
		white-space:normal;
	}
	table.mytable tbody td img {
		padding:0px !important;
		display:block !important;
		margin:1px !important;
		height:28px !important;
	}
	.dataTableHidden {
		visibility:hidden;
		
	}
	.red {
		color:#b00;
	}
	.green {
		color:#080
	}
	.blue {
		color: #06c;
	}
	table.mytable tbody td {
		font-weight:600;
		xfont-size:12px;
	}
	table.mytable thead th {
		xfont-weight:700;
		xfont-size:12px;
		color:#000000 !important;
	}
.subinfo {
	font-weight:600; 
	color:#005588; 
	display:inline-block; 
	border-left:1px #ccc solid; 
	padding:2px 0 2px 10px;
}



	
/*.filebutton p {
	position:absolute;
	left:120px;
	top:5px;
	font-weight:400;
}
.filebutton {
	position:relative;
	min-width:170px;
	max-width:190px;
	width:170px;
	height:30px;
	margin:0;
	padding:0;
	border:0px red dotted;
}
.filebutton.tiny {
	height:24px;
	border:0px red dotted;
	margin:0;
}
.filebutton label:hover {
	background:#ffcc00;
	background: linear-gradient(to bottom, #ffcc00, #cc9900);
	color:#000;
	text-decoration:none;
	box-shadow:inset 0 0 5px rgba(0,0,0,0.2);
}
.filebutton label {
	position:absolute;
	top:0px;
	left:0px;
	min-width:170px;
	max-width:190px;
	width:170px;
	height:30px;
	line-height:30px;
	cursor:pointer;
	margin:0;
	padding:0 15px;;
	text-align:center;
	overflow:hidden;
	cursor:pointer;
	background: linear-gradient(to bottom, #339933, #006600);
	color:#fff;
	font-size:14px;
	border:none;
	outline:none;
	text-decoration:none;
	display:inline-block;
	font-family:inherit;
	font-weight:normal;
	border-radius:2px;
	white-space:nowrap;
}
.filebutton label.tiny {
	width:90px;
	height:24px;
	line-height:24px;
	font-size:13px;
}
*/

/* Dropdown Button */
.sdropbtn {
  background: transparent;
  color: #000;
  padding: 3px;
  font-size: 12px;
  border: none;
  text-align:center;
  width:100% !important;
}

/* The container <div> - needed to position the dropdown content */
.sdrop {
  position: relative;
  display: inline-block;
  width:100%;
}

/* Dropdown Content (Hidden by Default) */
.sdrop-content {
  display: none;
  position: absolute;
  background: #333;
  border: 0px solid red;
  min-width: 40px;
  width:auto;
  box-shadow: 1px 1px 5px rgba(0,0,0,0.2);
  z-index: 1;
  padding:2px;
}

/* Links inside the dropdown */
.sdrop-content a {
  color: #fff;
  padding: 1px 6px;
  font-size:11px;
  text-decoration: none;
  display: block;
  white-space:nowrap;
  text-align:left;
}

/* Change color of dropdown links on hover */
.sdrop-content a:hover {background-color: #09d; color:#fff}

/* Show the dropdown menu on hover */
.xsdrop:hover .xsdrop-content {display: block;}
.xsdrop. xsdrop-content:active {display: block;}

/* Change the background color of the dropdown button when the dropdown content is shown */
.sdrop:hover .sdropbtn {background: transparent;}
.sdroptd:hover {background: #ddd}
.xsdropbtn:hover {color:#fff}

table.dataTable tbody td {
	padding:0 !important;
	text-align:center;
}
table.dataTable thead th.pad48 {
	padding:4px 8px;
}
table.dataTable tbody td.pad410 {
	padding:4px 10px !important;
}
select.dbselect {
    -webkit-appearance: none;
    -moz-appearance: none;
    text-indent: 1px;
    text-overflow: '';
	 cursor:pointer;
}

.highlight{
	background: burlywood;
}

table.editTable thead tr {
    background: #eee;
    color: #900;
}
table.editTable thead th {
    padding: 2px 8px;
    font-weight: 600;
    text-align: left;
    /*border-right: 1px solid #fff;*/
    border-bottom: 1px solid #ccc;
}
table.editTable tbody th {
    padding: 2px 8px;
    white-space: nowrap;
    font-weight: 600;
    border-left: 0;
}
table.editTable td.nopad {
    padding: 0;
}
table.editTable input[type="text"] {
    border: 0;
    padding: 2px 8px;
    background: #ffe;
    width: 100%;
}


table.editTable select {
    border: 0;
    padding: 1px 6px !important;
    width: auto;
    min-width: 100%;
    background: #ffe;
}

.addtraingleClass{
     background: linear-gradient(-45deg,#4e4a4a 50%, #83838e 50%)!important;
}


.addselectCss {
	font-size: 10px!important;
    height: 25px!important;
}

</style>		
	
	<h2><i class="fa fa-clock-o"></i>&nbsp;&nbsp;<?=$lng['Monthly shift planning']?>&nbsp;&nbsp;<i class="fa fa-arrow-circle-right"></i>&nbsp;&nbsp;<?=$lng['From']?> <?=date('d-m-Y', strtotime($time_period['start']))?> <?=$lng['Until']?> <?=date('d-m-Y', strtotime($time_period['end']))?>
	</h2>
	<div class="main">
		<div id="dump"></div>

		<table border="0" width="100%" style="margin-bottom:8px; display:none">
			<tr>
				<td class="vat" style="display:none">
					<div class="searchFilter">
						<input placeholder="<?=$lng['Filter']?>" id="searchFilter" class="sFilter" type="text" />
						<button id="clearSearchbox" type="button" class="clearFilter btn btn-default btn-sm"><i class="fa fa-times"></i></button>
					</div>
				</td>
				<td class="vat" style="padding-left:0px">
				</td>
				<td class="vat" style="padding-left:5px">
				</td>
				<td class="vat" style="padding-left:5px"></td>
				<td style="width:80%; padding-left:10px"><div id="message"></div></td>
				<td class="vat" style="padding-left:10px; display:none">
					<form id="importForm" enctype="multipart/form-data">
						<input style="visibility:hidden; height:0; width:0; position:absolute; left:-5000px" type="file" name="timesheet" id="timesheet" />
						<button style="display:none" id="importBut" type="button" onclick="$('#timesheet').click();" class="btn btn-primary btn-sm"><i class="fa fa-download"></i>&nbsp; Import Planning</button>
					</form>
				</td>
				<td class="vat" style="padding-left:5px">
				</td>
				<td class="vat" style="padding-left:5px;">
				</td>
			</tr>
		</table>
			<?php if($_SESSION['rego']['time_planning']['edit']) { ?>
		<input type="hidden" name="selBoxTeamVal" id="selBoxTeamVal" value="">   
		 <select id="monthFilter" class="button btn-fl">
		 <? foreach($months as $k=>$v){ ?>
			<option <? if($_GET['m'] == $k){echo 'selected';}?> value="<?=$k?>"><?=$v?></option>
		 <? } ?>
		 </select>
<!-- 			<select id="teamFilter" class="btn-fl">
				<option value="all"><?=$lng['All shiftteams']?></option>
			 <? foreach($teams as $k=>$v){ ?>
				<option value="<?=$k?>"><?=$v?></option>
			 <? } ?>
		</select> -->
		<? if($finalDropDown){ ?>
		<select id="addPlan" class="btn-fl">
			<option disabled selected value=""><?=$lng['Add shiftplan for']?> . . .</option>
						<? foreach ($finalDropDown as $key => $value) {
				
				$res13 = $dbc->query("SELECT * FROM ".$cid."_employees WHERE emp_id  = '".$value."'");
				if($res13->num_rows > 0){
					while($row13 = $res13->fetch_assoc())
					{
						echo '<option value="'.$row13['emp_id'].'" >'.$row13['emp_id'].' - '.$row13['en_name'].'</option>';
					}
				}	
			} ?>
		</select>
		<? } ?>



 		<select id="addEmp" class="btn-fl addEmp" onchange="addDEmloyee();" >
				<option  disabled selected><?=$lng['Add employee']?></option>
			<? 
				foreach ($defaultPlanArray as $key4 => $value4) {
					$res13 = $dbc->query("SELECT * FROM ".$cid."_employees WHERE emp_id = '".$value4."'");
					if($res13->num_rows > 0){
						while($row13 = $res13->fetch_assoc())
						{
							echo '<option value="'.$row13['emp_id'].'" >'.$row13['emp_id'].' - '.$row13['en_name'].'</option>';
						}
					}	
				}

			 ?>
		</select> 

		
		<button id="exportPlanning" type="button" class="btn btn-primary btn-fr"><i class="fa fa-download"></i>&nbsp; <?=$lng['Export Excel']?></button>

<!-- 		<button <?=$getDefault?> id="getDefPlanning" type="button" class="btn btn-primary btn-fr"><i class="fa fa-download"></i>&nbsp; <?=$lng['Get default planning']?></button>	 -->	

		<button <?=$getDefault?> id="getDefPlannings" type="button" class="btn btn-primary btn-fr"><i class="fa fa-download"></i>&nbsp; <?=$lng['Get default planning']?></button>

		<button id="confirmBut" type="button" class="btn btn-primary btn-fr"><i class="fa fa-thumbs-up"></i>&nbsp; <?=$lng['Confirm planning']?></button>
		<?php } ?>

		<div id="showTable" style="display:xnone; clear:both; position:relative">
			<input type="hidden" name="hiddenColumnOldval" id="hiddenColumnOldval" value="">
			<table id="datatable" class="monthData dataTable nowrap compact hoverable" width="100%" style="overflow:visible !important">
				<thead class="tac">
				<tr style="line-height:100%">
					<th style="min-width:80px"><?=$lng['Emp. ID']?></th>
					<th style="min-width:80px"><?=$lng['Team']?></th>
					<th class="tal" style="width:50%"><?=$lng['Employee']?></th>
					<th data-sortable="false" class="pad48">WRK</th>
					<th data-sortable="false" class="pad48">OFF</th>
					<th data-sortable="false" class="pad48">PUB</th>
					<th data-sortable="false" class="pad48">PEN</th>
					<th data-sortable="false" class="pad48"><a  title="Delete"><i class="fa fa-trash fa-lg"></i></a></th>
					<? for($i=1;$i<=$cols;$i++){ 
							/*if(strtotime($ym.$i) < $today){
								echo '<th data-sortable="false" class="pad48" style="color:#999">'.substr(date('l', strtotime($ym.$i)),0,2).'<br>'.$i.'</th>';
							}else{*/
								echo '<th data-sortable="false" class="pad48">'.substr(date('l', strtotime($ym.$i)),0,2).'<br>'.$i.'</th>';
							//}
					} ?>
				</tr>
				</thead>
				<tbody>
	
				</tbody>
			</table>
			<div style="position:absolute;top:0px; right:0; background:#eee; height:35px; width:6px; border-bottom:1px solid #ccc"></div>
		</div>
		
		
	</div>
	
	<!--<div style="position:absolute;top:219px; left:0; right:0; background:rgba(255,0,0,0.5); height:672px;"></div>-->

	<!-- POPUP MODAL  -->

	<div class="modal fade" id="modalPopup" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
		 <div class="modal-dialog" style="max-width:450px">
			  <div class="modal-content">
					<div class="modal-header">
						 <h4 class="modal-title" id="myModalLabel"><i class="fa fa-exclamation-triangle"></i>&nbsp; <?=$lng['Alert Message']?></h4>
					</div>
					<div class="modal-body" style="padding:20px 25px 25px">
						<div style="max-height:462px; overflow-y:auto">
							<p style="font-weight: 600;"><?=$lng['Alert Warning']?></p>
						</div>
						<div style="height:10px"></div>
						<button style="float:right" class="btn btn-primary" type="button" data-dismiss="modal"><i class="fa fa-times"></i>&nbsp; <?=$lng['No']?></button>
						<button style="float:right;margin-right: 5px;" class="btn btn-primary" type="button" data-dismiss="modal" onclick="getDefaultPlanning();" ><i class="fa fa-check"></i>&nbsp; <?=$lng['Yes']?></button>
						<div class="clear"></div>
					</div>
					
			  </div>
		 </div>
	</div>	



<div class="modal fade" id="leaveRequestModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
	<input type="hidden" name="hiddenEmpID" id="hiddenEmpID" value="">
	<input type="hidden" name="hiddenCount" id="hiddenCount" value="">
		 <div class="modal-dialog" style="max-width:650px">
			  <div class="modal-content">
					<div class="modal-header">
						 <h4 class="modal-title" id="myModalLabel"><i class="fa fa-clone"></i>&nbsp; OFF Day Request</h4>
					</div>
					<div class="modal-body" style="padding:20px 25px 25px">
						<div style="max-height:650px; overflow-y:auto">

							<table id="teamNameTable" class="editTable" border="0" style="width:100%;margin-bottom: 10px;">
								<tbody>
									<tr>
										<th><?=$lng['Team Name']?></th>
										<td>
											<span id="teamnameModal" ></span>
										</td>
									</tr>
								</tbody>
							</table>		
	
							<table id="leaveDatesTable" class="editTable" border="0" style="width:100%;">
								<thead>
									<th style="width:20%;">Requested Date</th>
									<th >Employee Name</th>
									<th >Emp. ID</th>
									<th >Already On OFF Day</th>
									<th >Action</th>
									
								</thead>
								<tbody>
								
								</tbody>
							</table>					

						</div>
						<div style="height:10px"></div>
						<button style="float:right;" class="btn btn-primary" type="button" data-dismiss="modal" onclick="updateOffday();"><i class="fa fa-check"></i>&nbsp; <?=$lng['Submit']?></button>
						<button style="float:right;margin-right: 5px;" class="btn btn-primary" type="button" data-dismiss="modal" ><i class="fa fa-times"></i>&nbsp; <?=$lng['Cancel']?></button>

						<div class="clear"></div>
					</div>
					
			  </div>
		 </div>
	</div>	


<script>


	$(document).ready(function() {

		var select_button_text = $('#selBox-teams option:selected')
                .toArray().map(item => item.text).join();

		$('#selBoxTeamVal').val(select_button_text);

	});






	var headerCount = 1;
	var scrY = window.innerHeight-240;
	var dheight = window.innerHeight-303;
	var drows = Math.floor(dheight/28);
	


	$(document).ready(function() {
		
		var dtable = $('#datatable').DataTable({
			scrollY:        false,//scrY,//heights-260,
			scrollX:        true,
			//scrollCollapse: false,
			fixedColumns: 	true,
			lengthChange: 	false,
			pageLength: 	drows,
			paging: 			true,
			searching:		true,
			ordering:		true,
			filter: 			false,
			info: 			false,
			//autoWidth:		false,
			<?=$dtable_lang?>
			processing: true,
			serverSide: true,
			columnDefs: [
				{ targets: [0,1], class: 'tal pad410' },
				//{ targets: [1], width: '50%' },
			],

			fnCreatedRow: function (nRow, aData, iDataIndex) {
		        $(nRow).attr('id', 'my' + iDataIndex); // or whatever you choose to set as the id
		    },
			ajax: {
				url: "ajax/server_get_monthly_shiftplan.php",
				type: "POST", 
				data: function(d){
					d.emp_id = $("#emp_id").val();
					d.teamfilter = $("#teamFilter").val();
					d.monthfilter = $("#monthFilter").val();
					d.selBoxTeamVal = $("#selBoxTeamVal").val();

				}
			},
			initComplete: function( settings, json ) {
			

				console.log(json);
				$('#showTable').fadeIn(200);
				dtable.columns.adjust().draw();
				/*setTimeout(function(){
					dtable.columns.adjust().draw();
					//$("#calcBut").trigger('click')
				},100);*/



				$('table.monthData > tbody  > tr').each(function() 
				{
					var leave_request = $(this).find("span#leave_request").html();
					//alert(leave_request);
					// if(leave_request == 'yes')
					// {
					// 	//$('.monthData tr td:nth-child(7)').addClass('highlightYes');  
					// 	$('.monthData tr td:nth-child(7)').removeClass('highlightNo');
					// }else if(leave_request == 'No'){
					// 	$('.monthData tr td:nth-child(7)').addClass('highlightNo');
					// 	$('.monthData tr td:nth-child(7)').removeClass('highlightYes');
					// }

					// alert(leave_request);
					if(leave_request == 'yes')
					{
						//$('span.yes').css('background','red'); 
						$("span.yes").parents('td').addClass('blinkred');
					}

						
				});



				// get td before span tag with id leave request 
				// $('.monthData tr td:nth-child(7)').addClass('highlight');
				// var penVal = $('.monthData tr td:nth-child(7) span#leave_request').html();

			}
		});
		$("#searchFilter").keyup(function() {
			var s = $(this).val();
			dtable.search(s).draw();
		});
		$(document).on("click", "#clearSearchbox", function(e) {
			$('#searchFilter').val('');
			dtable.search('').draw();
		})
		$(document).on('change', '#teamFilter', function(){
			dtable.ajax.reload(null, false);
		})
		$(document).on('change', '#monthFilter', function(){
			location.href = 'index.php?mn=5&m='+this.value;
		})

		$(document).on("click", "#confirmBut", function(e) {
			$('#confirmBut i').removeClass('fa-thumbs-up').addClass('fa-repeat fa-spin');
			$.ajax({
				url: "ajax/confirm_monthly_shiftplan.php",
				data: {month: $('#monthFilter').val()},
				type: "POST", 
				success: function(response){
					//$('#dump').html(response); return false;
					if(response == 'success'){
						$("body").overhang({
							type: "success",
							message: '<i class="fa fa-check"></i>&nbsp;&nbsp;<?=$lng['Shiftplan confirmed successfully']?>',
							duration: 2,
						})
					}else{
						$("body").overhang({
							type: "warn",
							message: '<i class="fa fa-exclamation-triangle"></i>&nbsp;&nbsp;<?=$lng['Error']?> : ' + responce,
							duration: 4,
							closeConfirm: true
						})
					}
					setTimeout(function(){$('#confirmBut i').removeClass('fa-repeat fa-spin').addClass('fa-thumbs-up');},500);
				},
				error:function (xhr, ajaxOptions, thrownError){
					$("body").overhang({
						type: "error",
						message: '<i class="fa fa-exclamation-triangle"></i>&nbsp;&nbsp;<?=$lng['Sorry but someting went wrong']?> <b><?=$lng['Error']?></b> : '+thrownError,
						duration: 8,
						closeConfirm: "true",
					})
					setTimeout(function(){$('#confirmBut i').removeClass('fa-repeat fa-spin').addClass('fa-thumbs-up');},500);
				}
			});
		})

		$(document).on("click", "#exportPlanning", function(e) {
			window.location.href='ajax/export_monthly_shiftplan.php?month='+$('#monthFilter').val();
		})
		

		$(document).on("click", "#getDefPlannings", function(e) {
			$('#getDefPlanning i').removeClass('fa-download').addClass('fa-refresh fa-spin');
			$("#modalPopup").modal('toggle');
		})

		$(document).on("click", "#getDefPlanning", function(e) {
			$('#getDefPlanning i').removeClass('fa-download').addClass('fa-refresh fa-spin');
			$.ajax({
				url: "ajax/get_default_shiftplan.php",
				type: "POST", 
				data: {month: $('#monthFilter').val()},
				success: function(response	){
					//$('#dump').html(response); return false;
					if(response == 'success'){
						//$('#getDefPlanning').prop('disabled', true)
						//dtable.ajax.reload(null, false);
						location.reload();
					}else{
						$("body").overhang({
							type: "error",
							message: '<i class="fa fa-exclamation-triangle"></i>&nbsp;&nbsp;<?=$lng['Error']?> : ' + response,
							duration: 4,
							closeConfirm: true
						})
					}
					setTimeout(function(){$('#getDefPlanning i').removeClass('fa-refresh fa-spin').addClass('fa-download');},500);					
				},
				error:function (xhr, ajaxOptions, thrownError){
					$("body").overhang({
						type: "error",
						message: '<i class="fa fa-exclamation-triangle"></i>&nbsp;&nbsp;<?=$lng['Sorry but someting went wrong']?> <b><?=$lng['Error']?></b> : '+thrownError,
						duration: 8,
						closeConfirm: "true",
					})
					setTimeout(function(){$('#getDefPlanning i').removeClass('fa-refresh fa-spin').addClass('fa-download');},500);	
				}
			});
		})

		$(document).on("change", "#addPlan", function(e) {
			$.ajax({
				url: "ajax/add_shiftplan_for.php",
				type: "POST", 
				data: {month: $('#monthFilter').val(), emp_id: this.value},
				success: function(response){
					//$('#dump').html(response); return false;
					if(response == 'success'){
						location.reload();
					}else{
						$("body").overhang({
							type: "error",
							message: '<i class="fa fa-exclamation-triangle"></i>&nbsp;&nbsp;<?=$lng['Error']?> : ' + responce,
							duration: 4,
						})
					}
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
		})



		$(document).on("focusin", ".dbselect", function(e) {
			var columnval = $(this).val();

			$('#hiddenColumnOldval').val(columnval);

		})	



		$(document).on("change", ".dbselect", function(e) {
			var id = $(this).closest('tr').find('.emp_id').html()+'-'+$('#monthFilter').val();
			var col = $(this).data('col');
			var val = $(this).val();
			var oldColVal = $('#hiddenColumnOldval').val();
			$(".tooltip").tooltip("hide");

		

			$.ajax({
				url: "ajax/update_monthly_shiftplan.php",
				type: "POST", 
				data: {id: id, col: col, val: val,oldColVal:oldColVal},
				success: function(response){
					
					dtable.ajax.reload(null, false);
					$('#hiddenColumnOldval').val('');
				},
				error:function (xhr, ajaxOptions, thrownError){
					alert(thrownError);
				}
			});
		})			


		$(document).on("change", "#selBox-teams", function(e) {

			var select_button_text = $('#selBox-teams option:selected')
                .toArray().map(item => item.text).join();

			$('#selBoxTeamVal').val(select_button_text);

			var cid= '<?php echo $cid ?>';
			var username= "<?php echo $_SESSION['rego']['username'] ?>";

			$.ajax({
				url: "ajax/update_user_team_session.php",
				type: "POST", 
				data: {teams: select_button_text,cid:cid,username:username},
				success: function(response){
					
					dtable.ajax.reload(null, false);
				},
				error:function (xhr, ajaxOptions, thrownError){
					alert(thrownError);
				}
			});
		})	




		// $(document).on("click", ".deleteicon", function(e) {
		// 	var empId = $(this).closest('tr').find('.emp_id').html();

		// 	var monthVal = $('#monthFilter').val();
		// 		$.ajax({
		// 		url: "ajax/delete_monthly_shiftplan.php",
		// 		type: "POST", 
		// 		data: {empId: empId, monthVal: monthVal},
		// 		success: function(response){
					
		// 			dtable.ajax.reload(null, false);
		// 		},
		// 		error:function (xhr, ajaxOptions, thrownError){
		// 			alert(thrownError);
		// 		}
		// 	});
		// })

	});


		$(document).ajaxComplete(function( event,request, settings ) {
			$('.delEmployee').confirmation({
				container: 'body',
				rootSelector: '.delEmployee',
				singleton: true,
				animated: 'fade',
				placement: 'left',
				popout: true,
				html: true,
				title 			: '<?=$lng['Are you sure']?>',
				btnOkClass 		: 'btn btn-danger',
				btnOkLabel 		: '<?=$lng['Delete']?>',
				btnCancelClass 	: 'btn btn-success',
				btnCancelLabel 	: '<?=$lng['Cancel']?>',
				onConfirm: function() { 

				var monthVal = $('#monthFilter').val();

				// alert($(this).data('id'));

					$.ajax({
						url: "ajax/delete_monthly_shiftplan.php",
						type: "POST", 
						data:{empId:$(this).data('id'), monthVal:monthVal},
						success: function(ajaxresult){
							location.reload();
								
						}
					});
				}
			});
		});


		function addDEmloyee() {
			 	var emp_id = $('#addEmp').val();
			$.ajax({
				url: "ajax/add_shiftplan_for_defaultplanning.php",
				type: "POST", 
				data: {month: $('#monthFilter').val(), emp_id: emp_id},
				success: function(response){
					//$('#dump').html(response); return false;
					if(response == 'success'){
						location.reload();
					}else{
						$("body").overhang({
							type: "error",
							message: '<i class="fa fa-exclamation-triangle"></i>&nbsp;&nbsp;<?=$lng['Error']?> : ' + responce,
							duration: 4,
						})
					}
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
		}
	
	function getDefaultPlanning()
	{
		$('#getDefPlannings i').removeClass('fa-download').addClass('fa-refresh fa-spin');
			$.ajax({
				url: "ajax/get_default_shiftplan.php",
				type: "POST", 
				data: {month: $('#monthFilter').val()},
				success: function(response){
					//$('#dump').html(response); return false;
					if($.trim(response) != ''){
						//$('#getDefPlanning').prop('disabled', true)
						//dtable.ajax.reload(null, false);
						location.reload();
					}else{
						$("body").overhang({
							type: "error",
							message: '<i class="fa fa-exclamation-triangle"></i>&nbsp;&nbsp;<?=$lng['Error']?> : ' + response,
							duration: 4,
							closeConfirm: true
						})
					}
					setTimeout(function(){$('#getDefPlannings i').removeClass('fa-refresh fa-spin').addClass('fa-download');},500);					
				},
				error:function (xhr, ajaxOptions, thrownError){
					$("body").overhang({
						type: "error",
						message: '<i class="fa fa-exclamation-triangle"></i>&nbsp;&nbsp;<?=$lng['Sorry but someting went wrong']?> <b><?=$lng['Error']?></b> : '+thrownError,
						duration: 8,
						closeConfirm: "true",
					})
					setTimeout(function(){$('#getDefPlannings i').removeClass('fa-refresh fa-spin').addClass('fa-download');},500);	
				}
			});
	}


	// DISPLAY LEAVE REQUESTS 
	$(document).on('click', '.yes', function(){

		var id=$(this).attr("data-id");


		$.ajax({
				url: "ajax/get_leave_requests.php",
				type: 'POST',
				data: {'id':id},
				success: function(response){

					var data = JSON.parse(response);

					if(response != '')
					{	
			            $("#leaveDatesTable > tbody").html(""); // REMOVE OLD TR 
			            var count =1;
						$.each(data, function(index, value) {
							var counter = count++;

							$('span#teamnameModal').html(value.team_name);
			                var tr= '<tr><th ><span id="date_'+counter+'">'+value.requested_date+'</span></th><th ><span id="name_'+counter+'">'+value.employee_name+'</span></th><th ><span id="empid_'+counter+'">'+value.emp_id+'</span></th><th></th><th ><span id="action_'+counter+'">'+value.select+'</span></th></tr>';

			                $('#leaveDatesTable tbody').append(tr);  // INSERT NEW TR 
							$("#leaveRequestModal").modal('toggle'); // OPEN LEAVE REQUEST MODAL 



			            });
					}
				},

			});

	});


	function updateOffday()
	{
		// get action box value and update in table off day 
		var monthFilter = $('#monthFilter').val();
		var count =1;
		$('#leaveDatesTable > tbody  > tr').each(function() 
		{	
			var counter = count++;
			var date = $(this).find('span#date_'+counter).html();
			var action = $(this).find('span#action_'+counter+' select').val();
			var emp_id = $(this).find('span#empid_'+counter).html();
			$('#hiddenEmpID').val(emp_id);


			if(action  == 'OA')
			{
				// run approve ajax 

				$.ajax({
					url: "ajax/update_off_days_approve.php",
					type: "POST", 
					data: {date: date, emp_id: emp_id,action:action,monthFilter:monthFilter},
					success: function(response){
						if($.trim(response) == 'success'){

							// send empid to hidden field 
							
							$("body").overhang({
							type: "success",
							message: '<i class="fa fa-check"></i>&nbsp;&nbsp; Changes made for OFF day successfully.',
							duration: 2,
						})
						}else{
							$("body").overhang({
								type: "error",
								message: '<i class="fa fa-exclamation-triangle"></i>&nbsp;&nbsp;<?=$lng['Error']?> ',
								duration: 4,
							})
						}
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


			}
			else if (action == 'OR')
			{
				// run rejected ajax 

				$.ajax({
					url: "ajax/update_off_days_reject.php",
					type: "POST", 
					data: {date: date, emp_id: emp_id,action:action,monthFilter:monthFilter},
					success: function(response){
						if($.trim(response) == 'success'){
							$("body").overhang({
							type: "success",
							message: '<i class="fa fa-check"></i>&nbsp;&nbsp; Changes made for OFF day successfully.',
							duration: 2,
						})
						}else{
							$("body").overhang({
								type: "error",
								message: '<i class="fa fa-exclamation-triangle"></i>&nbsp;&nbsp;<?=$lng['Error']?> ',
								duration: 4,
							})
						}
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

			}

		})

		var hiddenEmpID = $('#hiddenEmpID').val();
		getTrCount(monthFilter,hiddenEmpID);

		

	}

	function getTrCount(monthFilter,hiddenEmpID){

		var count = 1;
		var count1 = 1;
		$('#leaveDatesTable > tbody  > tr').each(function() 
		{
			var counter = count++;
			var action = $(this).find('span#action_'+counter+' select').val();

			if(action == 'OA')
			{
				$('#hiddenCount').val(counter);
			}


			// get selected approved rows 

			var approve = $(this).find('span#action_'+counter+' select option.selDetect').val();
			if(approve == 'OA')
			{
				var counter1 = count1++;

			}


		})


		var hiddenCount = $('#hiddenCount').val();

		$.ajax({
			url: "ajax/update_vod.php",
			type: "POST", 
			data: {monthFilter: monthFilter, hiddenEmpID: hiddenEmpID,hiddenCount:hiddenCount},
			success: function(response){

				setTimeout(function() {
				    location.reload();
				}, 700);
				
			},

		})


		
	}


</script>



	
