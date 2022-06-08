<!doctype html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>ClockPicker</title>
	
	<link rel="stylesheet" href="../assets/css/bootstrap.min.css">
	<link rel="stylesheet" href="../assets/datepicker/bootstrap-datepicker.css?<?=time()?>" />
	<link rel="stylesheet" href="../clockpicker/bootstrap4-clockpicker.css">
		
	<script src="../assets/js/jquery-3.2.1.min.js"></script>
	<script src="../assets/js/jquery-ui.min.js"></script>

</head>
<body>

	<button type="button" class="btn" data-toggle="modal" data-target="#modalAddLeave" data-focus="false">Modal</button>
	<input id="xtimeFrom" class="timePic clockpicker" xreadonly placeholder="From 00:00" type="text" value="">
	
	<!-- Modal addLeave -->
	<div class="modal fade" id="modalAddLeave" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static" data-keyboard="false">
		<div class="modal-dialog" role="document" style="max-width:600px">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title"><i class="fa fa-plane"></i>&nbsp; Add Leave <span id="memp_id"></span></h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body" style="padding:15px 20px 20px">
					<table id="requestTable" class="basicTable inputs" border="1" width="100%">
						<tbody>
							<tr>
								<th><i class="man"></i>First day</th>
								<td><input style="cursor:pointer" readonly type="text" id="startdate"></td>
							</tr>
							<tr>
								<th><i class="man"></i>Last day</th>
								<td><input style="cursor:pointer" readonly type="text" id="enddate"></td>
							</tr>
							<tr>
								<th>Details</th>
								<td colspan="2" style="padding:0; cursor:default;">
									<span id="rangeTable"></span>
								</td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
	
	<div id="popoverRequest" class="d-none">
		<table class="popTable" border="0">
			<tr>
				<td colspan="2">
					<button data-id="full" class="selDayType btn btn-default btn-xs" type="button">Full day</button>
				</td>
			</tr>
			<tr>
				<td><button data-id="first" class="selDayType btn btn-default btn-xs" type="button">First half</button></td>
				<td><button data-id="second" class="selDayType btn btn-default btn-xs" type="button">Second half</button></td>
			</tr>
			<tr>
				<td><input id="timeFrom" class="timePic clockpicker" xreadonly placeholder="From 00:00" type="text" value=""></td>
				<td><input id="timeUntil" class="timePic clockpicker" xdisabled xreadonly placeholder="Until 00:00" type="text" value=""></td>
			</tr>
		</table>
	</div>

	<script src="../assets/js/popper.min.js"></script>
	<script src="../assets/js/bootstrap.min.js"></script>
	<script src="../clockpicker/bootstrap4-clockpicker.js"></script>
	<script src="../assets/datepicker/bootstrap-datepicker.min.js"></script>

	<script type="text/javascript">
		
		//$("#modalAddLeave").modal('toggle')
		
		$("#popper").on('click', function(){
		$("#modalAddLeave").modal('toggle')
		/*$("#popoverRequest").html(
				'<input id="timeFrom" class="timePic" readonly placeholder="From 00:00" type="text">'+
				'<input id="timeUntil" class="timePic" disabled readonly placeholder="Until 00:00" type="text">'
			)*/
		})

		$(function() {
				
				$('.clockpicker').clockpicker({
					autoclose: true,
					placement: 'bottom',
					align: 'left',
					//sanitize: false,
					afterDone: function() {
						//$('#timeFrom').trigger("change");
						//alert($('#xtimeFrom').val())
						
						//$('#timeUntil').prop('disabled', false);
						//$('#timeUntil').focus();
					}
				});
			
			function bindClockPicker() {
				$('.clockpicker').clockpicker({
					autoclose: true,
					placement: 'bottom',
					align: 'left',
					afterDone: function() {
						$('#timeFrom').trigger("change");
						//alert($('#timeFrom').val())
						//$('#timeUntil').prop('disabled', false);
						//$('#timeUntil').focus();
					}
				});
			}	
			//bindClockPicker();		
			var Popper;
			$('#modalAddLeave').on('show.bs.modal', function () {
				$(document).ajaxComplete(function( event,request, settings ) {
					Popper = $('.dayType').popover({
						placement: 'right',
						container: '#modalAddLeave',
						html: true,
						sanitize: false,
						title: 'xxxx',
						//content: '<input id="timeFrom" class="clockpicker" xreadonly placeholder="From 00:00" type="text" value="">'
						content: function () {
								return $("#popoverRequest").html();
						}
					}).on('shown.bs.popover', function () {
						//bindClockPicker();
						
					})
					
					
				})
				
			})

			
			$(document).on('focus',"#timeFrom", function(){
				$(this).clockpicker({
					autoclose: true,
					placement: 'bottom',
					align: 'left',
					afterDone: function() {
						$('#timeFrom').trigger('change')
						//alert($('#timeFrom').val())
						$('#timeUntil').prop('disabled', false);
						$('#timeUntil').focus();
					}
				})
			});			

			$(document).on('focus',"#timeUntil", function(){
				$(this).clockpicker({
					autoclose: true,
					placement: 'bottom',
					align: 'right',
					afterDone: function() {
						$('#timeUntil').trigger("change");
					}
				});
			});			
			
			var dayType;
			$(document).on('click','.dayType', function(e) {
				dayType = $(this).data('id');
				//alert(dayType)
			});			
			$(document).on('click','.selDayType', function(e) {
				var type = $(this).data('id');
				 $('.day'+dayType).html($(this).text());
				$('#mday'+dayType).val(type);
				$('.dayType').popover('hide');
			});			
			
			$(document).on('change','#timeFrom', function(e) {
				var hours = $('#timeFrom').val();
				//alert('xxx '+hours)
				//$('.day'+dayType).html(hours);
				//$('#mday'+dayType).val(hours);
				//$('.dayType').popover('hide');
			});			
			$(document).on('change','#timeUntil', function(e) {
				var hours = $('#timeFrom').val() + ' - ' + $(this).val();
				//alert(hours)
				//$('.day'+dayType).html(hours);
				//$('#mday'+dayType).val(hours);
				//$('.dayType').popover('hide');
			});			
			var startDate = $('#startdate').datepicker({
				format: "D dd-mm-yyyy", 
				multidate: false,
				keyboardNavigation: false,
				autoclose: true,
				startView: 'month',
				orientation: "auto",
				//startDate: new Date(),
				leftArrow: '<i class="fa fa-arrow-left"></i>',
				rightArrow: '<i class="fa fa-arrow-right"></i>',
				todayHighlight: true,
				language: 'en',
			}).on('changeDate', function(e){
				$('#enddate').datepicker('setDate', startDate.val()).datepicker('setStartDate', startDate.val()).focus();
			});
			var endDate = $('#enddate').datepicker({
				format: "D dd-mm-yyyy",
				multidate: false,
				keyboardNavigation: false,
				autoclose: true,
				startView: 'month',
				startDate: new Date(),
				leftArrow: '<i class="fa fa-arrow-left"></i>',
				rightArrow: '<i class="fa fa-arrow-right"></i>',
				todayHighlight: true,
				language: 'en',
			}).on('changeDate', function(e){
				if($('#startdate').val() !== ''){
					$.ajax({
						url: "ajax/get_leave_range.php",
						data: {startDate: $('#startdate').val(), endDate: e.format()},
						success: function(result){
							$('#rangeTable').html(result); 
							
							//return false;
						},
						error:function (xhr, ajaxOptions, thrownError){
							alert(thrownError);
						}
					});
				}
			})
		});

</script>








</body>
</html>
