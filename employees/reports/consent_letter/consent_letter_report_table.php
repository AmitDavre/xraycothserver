<?
include(DIR . 'files/arrays_' . $_SESSION['rego']['lang'] . '.php');
//if(!isset($_GET['sm'])){$_GET['sm'] = 0;}

$emps = getEmployees($cid, 0);
$emp_id = key($emps);

$emp_array = '[';
foreach ($emps as $k => $v) {
	$emp_array .= "{data:'" . $k . "',value:'" . $k . ' - ' . $v['en_name'] . "'},";
}
$emp_array = substr($emp_array, 0, -1);
$emp_array .= ']';

//var_dump($sys_settings); exit;
//var_dump($_SESSION['rego']['report_id']);
$payslip_field = unserialize($sys_settings['payslip_field']);

$employee = '';
$id = false;
if (isset($_SESSION['rego']['report_id'])) {
	$employee = $_SESSION['rego']['report_id'] . ' - ' . $emps[$_SESSION['rego']['report_id']][$lang . '_name'];
	$id = true;
}

$data = array();
$id = false;
if (isset($_SESSION['rego']['report_id'])) {
	$id = true;
	$sql = "SELECT * FROM " . $cid . "_employees WHERE emp_id = '" . $_SESSION['rego']['report_id'] . "'";
	if ($res = $dbc->query($sql)) {
		if ($row = $res->fetch_assoc()) {
			$data = $row;
		}
	}
}

include('inc_employee_year.php');

//var_dump($data); exit;


// get employees and send in consent letter table 

$sql3244 = "SELECT * FROM " . $cid . "_employees ";
if ($reasdasds = $dbc->query($sql3244)) {
	while ($rosaddsw = $reasdasds->fetch_assoc()) {
		$dataasd[$rosaddsw['emp_id']] = $rosaddsw;
	}
}

$sql_get_from_consent_letter = "SELECT * FROM " . $cid . "_consent_letter ";
if ($result_from_consent_letter = $dbc->query($sql_get_from_consent_letter)) {
	while ($row_from_consent_letter = $result_from_consent_letter->fetch_assoc()) {
		$data_from_consent_letter[$row_from_consent_letter['emp_id']] = $row_from_consent_letter['emp_id'];
	}
}





// foreach ($dataasd as $key => $value) {


// 	$sql32444 = "SELECT * FROM ".$cid."_consent_letter WHERE emp_id = '".$value['emp_id']."'";
// 	if($reasd545asds = $dbc->query($sql32444)){
// 		if($rosasadddsw = $reasd545asds->fetch_assoc()){
// 		}
// 		else
// 		{
// 			$sqlinsertdata = "INSERT INTO ".$cid."_consent_letter ( `emp_id`, `en_name`, `department`, `position`) VALUES ('".$value['emp_id']."','".$value['en_name']."','".$value['department']."','".$value['position']."')";
// 			$dbc->query($sqlinsertdata);
// 		}
// 	}



// }




// echo '<pre>';
// print_r($dataasd);
// echo '</pre>';

// die();


?>
<style>
	.A4form {
		width: 100%;
		xmargin: 10px 10px 10px 15px;
		background: #fff;
		padding: 20px;
		box-shadow: 0 0 10px rgba(0, 0, 0, 0.4);
		position: relative;
		min-height: 0px;
	}

	.reportTable {
		width: 100%;
		border-collapse: collapse;
		font-size: 13px;
		margin: 0 !important;
	}

	.reportTable b {
		font-weight: 600;
		color: #039;
	}

	.reportTable thead th {
		padding: 4px 8px;
		font-weight: 600;
		text-align: center;
		font-size: 13px;
		border-bottom: 1px solid #bbb;
		background: #eee;
		white-space: nowrap;
	}

	.reportTable tbody th {
		padding: 2px 8px;
		white-space: nowrap;
		font-weight: 600;
		border: 1px solid #eee;
		border-left: 0;
		text-align: right;
	}

	.reportTable tbody td {
		white-space: nowrap;
	}

	.reportTable tbody td.bold {
		font-weight: 600;
	}

	.reportTable tbody td:first-child {
		border-left: 0;
	}

	.reportTable tbody td:last-child {
		border-right: 0;
	}

	.reportTable thead th {
		background: #eee;
		color: #900;
		font-weight: 600;
		border: 1px solid #fff;
		border-bottom: 1px solid #bbb;
		text-align: left;
	}

	#optionTable {
		margin-bottom: 10px !important;
	}

	#optionTable tbody td {
		padding: 5px 10px !important;
		border: 1px solid #ddd;
	}

	#optionTable tbody td.nopad {
		padding: 0 !important;
	}

	#optionTable tbody td:first-child {
		border-left: 0;
	}

	#optionTable tbody td:last-child {
		border-right: 0;
	}
</style>

<h2><i class="fa fa-print"></i>&nbsp; <?= $lng['Consent Letter'] ?></h2>

<div class="main" style="padding-top:15px; top:130px">
	<div style="padding:0 0 0 20px" id="dump"></div>

	<table border="0" width="100%" style="margin-bottom:10px">
		<tr>
			<td>
				<div class="searchFilter" style="width:180px; margin:0">
					<input placeholder="<?= $lng['Filter'] ?>" id="searchFilter" class="sFilter" type="text" />
					<button id="clearSearchbox" type="button" class="clearFilter btn btn-default btn-sm"><i class="fa fa-times"></i></button>



				</div>
			</td>
			<td style="padding-left:5px">
				<select id="addEmp" onchange="addDEmloyee();">
					<option value="add"><?= $lng['Add employee'] ?></option>
					<option value="all">All employees</option>

					<?php
					foreach ($dataasd as $key => $value) {
						if (!in_array($value['emp_id'], $data_from_consent_letter)) { ?>

							<option value="<?php echo $value['emp_id']; ?>"> <?php echo $value['emp_id']; ?> - <?php echo $value['en_name']; ?></option>

					<?php }
					}

					?>

				</select>
			</td>
			<td width="95%"></td>

			<!-- 				<td style="padding-left:5px">
					 <button id="view_all_consent_letter" type="button" class="btn btn-primary"><i class="fa fa-eye"></i>&nbsp; <?= $lng['Print Preview'] ?></button> 

				</td> -->
			<td>
				<button id="print_all_consent_letter" type="button" class="btn btn-primary"><i class="fa fa-print"></i>&nbsp; <?= $lng['Print consent letter'] ?></button>
			</td>
		</tr>
	</table>



	<div style="height:8px; clear:both"></div>

	<div class="A4form">
		<div style="overflow-x:hidden; width:100%">

			<div style="float:left; width:65%; padding-right:10px; border-right:1px solid #eee">
				<div id="showTable" style="display:none">
					<table id="payslipTable" class="reportTable" border="0">
						<thead>
							<tr>
								<th><?= $lng['Emp. ID'] ?></th>
								<th><?= $lng['Name'] ?></th>
								<th><?= $lng['Department'] ?></th>
								<th><?= $lng['Position'] ?></th>
								<th><i class="fa fa-print fa-lg"></i></th>
								<th><i class="fa fa-eye fa-lg"></i></th>
								<th><i class="fa fa-trash fa-lg"></i></th>
							</tr>
						</thead>
						<tbody>

						</tbody>
					</table>
				</div>
			</div>

			<div style="float:right; width:35%; padding-left:10px">
				<form id="payslipOptions">
					<table id="optionTable" class="reportTable" border="0">
						<thead>
							<tr>
								<th colspan="2" style="line-height:24px">Options</th>
							</tr>
						</thead>
						<tbody>




							<tr>
								<td>Language</td>
								<td class="nopad">
									<select class="options" name="show_lang_field" id="show_lang_field" style="width:100%; border:0">
										<option value="la4">English</option>
										<option value="la5">Thai</option>
									</select>
								</td>
							</tr>
							<tr>
								<td>Show Website info</td>
								<td>
									<label><input name="show_website_info" type="checkbox" value="1" class="options checkbox style-0" /><span></span></label>
								</td>
							</tr>
							<tr>
								<td>Show Company Logo</td>
								<td>
									<label><input checked="checked" name="show_logo" type="checkbox" value="1" class="options checkbox style-0" /><span></span></label>
								</td>
							</tr>
							<tr>
								<td>Date Letter</td>
								<td>
									<!-- <label><input name="show_date_field" id="show_date_field" type="checkbox" value="1" class="options checkbox style-0" /><span></span></label> -->
									<input name="show_date_field" id="show_date_field" type="text" value="" class="date_year1 " placeholder="..." />
								</td>
							</tr>
							<tr>
								<td>Show Representative details</td>
								<td>
									<label><input id="show_representative" name="show_representative" type="checkbox" value="1" class="options checkbox style-0" /><span></span></label>
								</td>
							</tr>
							<tr class="displaynonetr" style="display: none;">
								<td>Company</td>
								<td>
									<input name="field1" id="field1" type="text" value="" class=" " placeholder="..." />
								</td>
							</tr>
							<tr class="displaynonetr" style="display: none;">
								<td>Address</td>
								<td>
									<input name="field2" id="field2" type="text" value="" class=" " placeholder="..." />
								</td>
							</tr>
							<tr class="displaynonetr" style="display: none;">
								<td>Function</td>
								<td>
									<input name="field3" id="field3" type="text" value="" class=" " placeholder="..." />
								</td>
							</tr>
							<tr class="displaynonetr" style="display: none;">
								<td>Telephone</td>
								<td>
									<input name="field4" id="field4" type="text" value="" class=" " placeholder="..." />
								</td>
							</tr>
							<tr class="displaynonetr" style="display: none;">
								<td>Email</td>
								<td>
									<input name="field5" id="field5" type="text" value="" class=" " placeholder="..." />
								</td>
							</tr>
						</tbody>
					</table>
				</form>

			</div>
		</div>

	</div>

</div>

<!-- PAGE RELATED PLUGIN(S) -->
<!--<script type="text/javascript" src="../assets/js/jquery.autocomplete.js"></script>-->

<script type="text/javascript">
	$(document).ready(function() {


		$('.date_year1').datepicker({

			format: "dd-mm-yyyy",
			autoclose: true,
			inline: true,
			language: lang, //lang+'-th',
			todayHighlight: true,
			setDate: "06-01-21",

			// startView: 'decade',
		}).on('changeDate', function(ev) {
			$('#sAlert').fadeIn(200);
			$("#submitBtn").addClass('flash');
		});

		var myDate = new Date();
		$('.date_year1').datepicker('setDate', myDate);




		$("#view_all_consent_letter").on('click', function() {

			var d = $('#show_date_field').val();

			if ($('input[name="show_website_info"]').is(':checked')) {
				// checked
				var w = '1';
			} else {
				// unchecked
				var w = '0';
			}

			if ($('input[name="show_logo"]').is(':checked')) {
				// checked
				var l = '1';
			} else {
				// unchecked
				var l = '0';
			}

			if ($('input[name="show_representative"]').is(':checked')) {
				// checked
				var r = '1';
			} else {
				// unchecked
				var r = '0';
			}

			var la = $('#show_lang_field').val();

			window.open('consent_letter/view_all_consent_letter.php?d=' + d + '&w=' + w + '&l=' + l + '&r=' + r + '&la=' + la, '_blank');

		});

		$("#print_all_consent_letter").on('click', function() {

			var d = $('#show_date_field').val();

			if ($('input[name="show_website_info"]').is(':checked')) {
				// checked
				var w = '1';
			} else {
				// unchecked
				var w = '0';
			}

			if ($('input[name="show_logo"]').is(':checked')) {
				// checked
				var l = '1';
			} else {
				// unchecked
				var l = '0';
			}

			if ($('input[name="show_representative"]').is(':checked')) {
				// checked
				var r = '1';
			} else {
				// unchecked
				var r = '0';
			}

			var la = $('#show_lang_field').val();
			window.open('consent_letter/print_all_consent_letter.php?d=' + d + '&w=' + w + '&l=' + l + '&r=' + r + '&la=' + la, '_blank');


		});


		$(document).on("click", ".empPrint", function(e) {
			var id = $(this).data('id');

			var d = $('#show_date_field').val();

			if ($('input[name="show_website_info"]').is(':checked')) {
				// checked
				var w = '1';
			} else {
				// unchecked
				var w = '0';
			}

			if ($('input[name="show_logo"]').is(':checked')) {
				// checked
				var l = '1';
			} else {
				// unchecked
				var l = '0';
			}

			if ($('input[name="show_representative"]').is(':checked')) {
				// checked
				var r = '1';
			} else {
				// unchecked
				var r = '0';
			}

			var la = $('#show_lang_field').val();

			window.open('consent_letter/print_consent_letter.php?id=' + id + '&d=' + d + '&w=' + w + '&l=' + l + '&r=' + r + '&la=' + la, '_blank');
			//alert(id);
		})

		$(document).on("click", ".empView", function(e) {
			var id = $(this).data('id');

			var d = $('#show_date_field').val();

			if ($('input[name="show_website_info"]').is(':checked')) {
				// checked
				var w = '1';
			} else {
				// unchecked
				var w = '0';
			}

			if ($('input[name="show_logo"]').is(':checked')) {
				// checked
				var l = '1';
			} else {
				// unchecked
				var l = '0';
			}

			if ($('input[name="show_representative"]').is(':checked')) {
				// checked
				var r = '1';
			} else {
				// unchecked
				var r = '0';
			}

			var la = $('#show_lang_field').val();

			var field1 = $('#field1').var();
			var field2 = $('#field2').var();
			var field3 = $('#field3').val();
			var field4 = $('#field4').val();
			var field5 = $('#field5').val();

			
			// $.ajax({
			// 	url: "ajax/.php",
			// 	type: 'POST',
			// 	data: formData,
			// 	success: function(result) {
			// 		//$('#dump').html(result); return false;
			// 			window.open('consent_letter/view_consent_letter.php?id=' + id + '&d=' + d + '&w=' + w + '&l=' + l + '&r=' + r + '&la=' + la, '_blank');
			// 	},
			// });



			// window.open('consent_letter/view_consent_letter.php?id='+id+'&d='+d+'&w='+w+'&l='+l+'&r='+r+'&la='+la, 'window name', 'window settings')
			//alert(id);
		})
		// $(document).on("change", ".options", function(e) {
		// 	$("#payslipOptions").submit();
		// })
		// $("#payslipOptions").submit(function(e){ 
		// 	e.preventDefault();
		// 	var formData = $(this).serialize();
		// 	$.ajax({
		// 		url: "ajax/update_payslip_options.php",
		// 		type: 'POST',
		// 		data: formData,
		// 		success: function(result){
		// 			//$('#dump').html(result); return false;
		// 		},
		// 		error:function (xhr, ajaxOptions, thrownError){
		// 			$("body").overhang({
		// 				type: "error",
		// 				message: '<i class="fa fa-exclamation-triangle"></i>&nbsp;&nbsp;<?= $lng['Sorry but someting went wrong'] ?> <b><?= $lng['Error'] ?></b> : '+thrownError,
		// 				duration: 4,
		// 			})
		// 		}
		// 	});
		// });



		var dtable = $('#payslipTable').DataTable({
			scrollY: true, //scrY,//heights-288,
			scrollX: true,
			scrollCollapse: false,
			fixedColumns: true,
			lengthChange: false,
			searching: true,
			ordering: true,
			paging: false,
			pagingType: 'full_numbers',
			//pageLength: 	drows,
			filter: true,
			info: false,
			autoWidth: false,
			processing: false,
			serverSide: true,

			<?= $dtable_lang ?>
			ajax: {
				url: "consent_letter/ajax/server_get_employes.php",
				type: "POST",
				"data": function(d) {
					// d.filter = $('#taxFilter').val();
				}
			},
			columnDefs: [
				// {targets: [7,8,9], "class": 'tar bold'},
				// {targets: [1], width: '80%'},
			],
			initComplete: function(settings, json) {
				$('#showTable').fadeIn(200);
				dtable.columns.adjust().draw();
			}
		});
		$("#searchFilter").keyup(function() {
			dtable.search(this.value).draw();
		});
		$(document).on("click", "#clearSearchbox", function(e) {
			$('#searchFilter').val('');
			dtable.search('').draw();
		})


	});

	// add selected employee in the consent letter table 

	function addDEmloyee() {
		var emp_id = $('#addEmp').val();
		$.ajax({
			url: "consent_letter/ajax/add_selected_employee_in_the_consent_letter_table.php",
			type: "POST",
			data: {
				emp_id: emp_id
			},
			success: function(response) {
				//$('#dump').html(response); return false;
				if (response == 'success') {
					location.reload();
				} else {
					$("body").overhang({
						type: "error",
						message: '<i class="fa fa-exclamation-triangle"></i>&nbsp;&nbsp;<?= $lng['Error'] ?> : ' + responce,
						duration: 4,
					})
				}
			},
			error: function(xhr, ajaxOptions, thrownError) {
				$("body").overhang({
					type: "error",
					message: '<i class="fa fa-exclamation-triangle"></i>&nbsp;&nbsp;<?= $lng['Sorry but someting went wrong'] ?> <b><?= $lng['Error'] ?></b> : ' + thrownError,
					duration: 8,
					closeConfirm: "true",
				})
			}
		});
	}


	$(document).on("click", ".emptrash", function(e) {
		var id = $(this).data('id');
		$.ajax({
			url: "consent_letter/ajax/delete_selected_employee_in_the_consent_letter_table.php",
			type: "POST",
			data: {
				id: id
			},
			success: function(response) {
				//$('#dump').html(response); return false;
				if (response == 'success') {
					location.reload();
				} else {
					$("body").overhang({
						type: "error",
						message: '<i class="fa fa-exclamation-triangle"></i>&nbsp;&nbsp;<?= $lng['Error'] ?> : ' + responce,
						duration: 4,
					})
				}
			},
			error: function(xhr, ajaxOptions, thrownError) {
				$("body").overhang({
					type: "error",
					message: '<i class="fa fa-exclamation-triangle"></i>&nbsp;&nbsp;<?= $lng['Sorry but someting went wrong'] ?> <b><?= $lng['Error'] ?></b> : ' + thrownError,
					duration: 8,
					closeConfirm: "true",
				})
			}
		});

	})





	$('#show_representative').click(function() {
		if ($(this).is(':checked')) {

			$('.displaynonetr').css('display', '');
		} else {

			$('.displaynonetr').css('display', 'none');
		}
	});
</script>