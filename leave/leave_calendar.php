<?

	//$leave_types = getLeaveTypes($dbc, $cid);
	$leave_colors = array(
		'bg-color-blue',
		'bg-color-blueLight',
		'bg-color-blueDark',
		'bg-color-green',
		'bg-color-greenLight',
		'bg-color-greenDark',
		'bg-color-red',
		'bg-color-yellow',
		'bg-color-orange',
		'bg-color-orangeDark',
		'bg-color-pink',
		'bg-color-pinkDark',
		'bg-color-purple',
		'bg-color-darken',
		'bg-color-grayDark',
		'bg-color-magenta',
		'bg-color-teal',
		'bg-color-redLight');
	$nr=0;
	$legend[$lng['Unplanned leave']] = 'unplanned';
	$legend[$lng['Planned leave']] = 'planned';
	$legend[$lng['Public holiday']] = 'holiday';
	$legend[$lng['Normal working day']] = 'working';
	$legend[$lng['Non-working day']] = 'nonworking';
	
	foreach($leave_types as $k=>$v){
		$leave_types[$k]['class'] = $leave_colors[$nr]; $nr++;
	}
	foreach($months as $k=>$v){
		$goto[date($_SESSION['rego']['cur_year'].'-'.sprintf('%02d', $k).'-01')] = $v.' '.$_SESSION['rego']['cur_year'];
	}
	$emps = getEmployees($cid,0);
	//var_dump($emps);
	$emp_array = getJsonUserEmployees($cid, $lang);
	//var_dump($emp_array);
?>	
	<link rel="stylesheet" href="../assets/css/fullcalendar.css?<?=time()?>">
	
	<style>
	/*table.detailTable {
		width:100%;
	}
	table.detailTable tbody td {
		padding:2px 8px !important;
		border:0;
	}
	table.detailTable tr {
		border-bottom:1px solid #eee;
	}
	table.detailTable tr:last-child {
		border-bottom:0;
	}*/
		
		
	</style>

	<h2><i class="fa fa-calendar"></i>&nbsp;&nbsp;Leave calendar <span style="float:right"><!--Leave period :  <?=date('d/m/Y', strtotime($leave_periods[$_SESSION['rego']['cur_month']]['start']))?> - <?=date('d/m/Y', strtotime($leave_periods[$_SESSION['rego']['cur_month']]['end']))?>--></h2>		
		<div class="main" style="height:calc(100% - 130px)">
			<div id="dump"></div>
			
				<div id="calendar-wrapper" style="position:relative; padding:0 0 15px 0; min-height:300px; height:calc(100% - 30px)">
					
					<table border="0" style="width:100%; position:absolute; left:0; top:5px">
						<tr>
							<td>
								<div class="searchFilter" style="margin-bottom:0px; width:250px">
									<input style="width:100%" class="sFilter" placeholder="<?=$lng['Type for hints']?> ..." type="text" id="selectEmployee" />
									<input id="emp_id" type="hidden" value="" />
								</div>
							</td>
							<td style="padding-left:0px">
								<select id="gotoMonth" class="button">
									<option disabled selected value="0"><?=$lng['Select month']?></option>
									<? foreach($goto as $k=>$v){ ?>
											<option value="<?=$k?>"><?=$v?></option>
									<? } ?>
								</select>
							</td>
							<td style="width:90%"></td>
							<td style="white-space:nowrap">
								<button style="margin:0 1px" class="btn btn-primary" id="btn-month"><?=$lng['Month']?></button>
								<button style="margin:0" class="btn btn-primary" id="btn-list"><?=$lng['List']?></button>
								<button style="margin:0" class="btn btn-primary" id="btn-today"><?=$lng['Today']?></button>
							</td>
							<td style="white-space:nowrap; padding-left:15px">
								<button class="btn btn-primary" id="btn-prev">&nbsp;<i class="fa fa-chevron-left"></i>&nbsp;</button>
								<button class="btn btn-primary" id="btn-next">&nbsp;<i class="fa fa-chevron-right"></i>&nbsp;</button>
							</td>
						</tr>
					</table>
					
					<div id="calendar" style="background:transparent; xdisplay:none"></div>				
				
				
				</div>
				
				<div>
				<? foreach($legend as $k=>$v){ ?>
					<div class="legend <?=$v?>"><?=$k?></div>
				<? } ?>
				<div class="clear"></div>
				</div>
				
	</div>
	
	<!-- Modal modalLeaveDetails -->
	<div class="modal fade" id="modalLeaveDetails" tabindex="-1" role="dialog">
		<div class="modal-dialog" role="document" style="max-width:600">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title">
						<i class="fa fa-plane"></i>&nbsp; <?=$lng['Leave details']?> <span id="memp_id"></span>
					</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body" style="padding:15px 20px 12px">
					<span id="leave_table1"></span>
					<div style="padding-top:10px; float:right; display:none">
						<button type="button" class="btn btn-primary btn-xs"><i class="fa fa-thumbs-up"></i>&nbsp; Approve</button>&nbsp;
						<button type="button" class="btn btn-primary btn-xs"><i class="fa fa-thumbs-down"></i>&nbsp; Reject</button>&nbsp;
						<button type="button" class="btn btn-primary btn-xs"><i class="fa fa-times-circle"></i>&nbsp; Cancel</button>
					</div>
					<div class="clear"></div>
				</div>
			</div><!-- /.modal-content -->
		</div><!-- /.modal-dialog -->
	</div><!-- /.modal -->

	<!-- PAGE RELATED PLUGIN(S) -->
	<script src="../assets/js/jquery.autocomplete.js"></script>
	
	<script src="../assets/js/jquery-ui.min.js"></script>
	<script src='../assets/js/moment.min.js'></script>
	<script src='../assets/js/fullcalendar.js?<?=time()?>'></script>
	<? if($lang == 'th'){ ?>
	<script src="../assets/js/fullcalendar-th.js?<?=time()?>"></script>
	<? } ?>

	<script type="text/javascript">


		
	$(document).ready(function() {
		var height = $('#calendar-wrapper').height() - 40;
		//alert(height);

		var fcyear = true;	
		$('#calendar:not(".fc-event")').on('contextmenu', function (e) {
			 e.preventDefault()
		})
		$(document).on('change', '#gotoMonth', function () {
			$('#calendar').fullCalendar('gotoDate', $(this).val());
			$(this).val(0)
		});
		
		var employees = <?=json_encode($emp_array)?>;
		//var emps = <?//=json_encode($emps)?>;
		$('#selectEmployee').devbridgeAutocomplete({
			 lookup: employees,
			 onSelect: function (suggestion) {
			 	$("#emp_id").val(suggestion.data);
				$('#calendar').fullCalendar('refetchEvents');
			 }
		});	

		/* initialize the calendar */
		$('#calendar').fullCalendar({
			header: {
				center: 'title',
				left: '',//'month,year',
				right: 'prev,today,next'
			},
			editable: false,
			weekends: true,
			weekNumbers: true,
			eventDurationEditable: false, // resize false
			locale: lang,
			firstDay: 1,
			droppable: false,
			html: true, 
			selectable: false,
			contentHeight: height,
			defaultDate: new Date(),
			showNonCurrentDates: false,

			events: {
				url: ROOT+"leave/ajax/json_calendar_leave_events3.php",
				data: function(){


					return { emp_id: $('#emp_id').val() };
				}
		

			},
			eventRender: function(calEvent, element) {                                          
				    element.find('span.fc-title').html(element.find('span.fc-title').text());                   

			},

			eventClick: function(calEvent, jsEvent, view) {
				$.ajax({
					url: "ajax/get_leave_alt_details.php",
					data: {id: calEvent.lid},
					success:function(result){
						$("#leave_table1").html(result);
						$("#modalLeaveDetails").modal('toggle');
					},
					error:function (rego, ajaxOptions, thrownError){
						alert(thrownError);
					}
				});
    	},		
			/*eventRender: function (event, element, icon) {
				//alert(event.description)
				if (!event.icon == "") {
					if(event.icon == "leave"){
						//element.find('.fc-title').append('<span>' + event.leave + ' - ' + event.description + '</span>');
					}
					if(event.icon == "holiday"){
						//element.find('.fc-title').append('<span>' + event.description + '</span>');
					}
				}
		  },*/
		  windowResize: function (event, ui) {
				$('#calendar').fullCalendar('render');
		  },
		});
		/* hide default buttons */
		$('.fc-right, .fc-left').hide();
		$('#btn-prev').click(function () {
			$('.fc-prev-button').click();
		});
		$('#btn-next').click(function () {
			$('.fc-next-button').click();
		});
		$('#btn-today').click(function () {
			$('.fc-today-button').click();
		});
		$('#btn-month').click(function () {
			$('#calendar').fullCalendar('changeView', 'month');
		});
		$('#btn-list').click(function () {
			$('#calendar').fullCalendar('changeView', 'listWeek');
		});
	
		
		
		
	})

	</script>
