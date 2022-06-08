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
	<link rel="stylesheet" href="assets/css/fullcalendar.css?<?=time()?>">
	
	<style>
		.planned {
		  background: #8d8 !important;
		  border:1px #8d8 solid;
		}		
		.unplanned {
		  background: #aaf !important;
		  border:1px #aaf solid;
		}		
		.fc-list-view .planned, 
		.fc-list-view .unplanned {
		  border:0px #8d8 solid;
		}	
		.holiday {
		  background: #fb0 !important;
		  border:1px #fb0 solid;
		}		
		.nonworking {
		  background: #eee !important;
		  border:1px #eee solid;
		}		
		.working {
		  background: #fff !important;
		  border:1px #ddd solid;
		}		
		.planned.half {
		  background: linear-gradient(to right, #8d8 50%, #dfd 50%) !important;
		}		
		.planned.Ahrs {
		  background: linear-gradient(to right, #8d8 10%, #dfd 10%) !important;
		}		
		.planned.Bhrs {
		  background: linear-gradient(to right, #8d8 20%, #dfd 20%) !important;
		}		
		.planned.Chrs {
		  background: linear-gradient(to right, #8d8 30%, #dfd 30%) !important;
		}		
		.planned.Dhrs {
		  background: linear-gradient(to right, #8d8 40%, #dfd 40%) !important;
		}		
		.planned.Ehrs {
		  background: linear-gradient(to right, #8d8 50%, #dfd 50%) !important;
		}		
		.planned.Fhrs {
		  background: linear-gradient(to right, #8d8 60%, #dfd 60%) !important;
		}		
		.planned.Ghrs {
		  background: linear-gradient(to right, #8d8 70%, #dfd 70%) !important;
		}		
		.planned.Hhrs {
		  background: linear-gradient(to right, #8d8 80%, #dfd 80%) !important;
		}		
		.planned.Ihrs {
		  background: linear-gradient(to right, #8d8 90%, #dfd 90%) !important;
		}		
		
		.unplanned.half {
		  background: linear-gradient(to right, #aaf 50%, #eef 50%) !important;
		}		
		.unplanned.Ahrs {
		  background: linear-gradient(to right, #aaf 10%, #eef 10%) !important;
		}		
		.unplanned.Bhrs {
		  background: linear-gradient(to right, #aaf 20%, #eef 20%) !important;
		}		
		.unplanned.Chrs {
		  background: linear-gradient(to right, #aaf 30%, #eef 30%) !important;
		}		
		.unplanned.Dhrs {
		  background: linear-gradient(to right, #aaf 40%, #eef 40%) !important;
		}		
		.unplanned.Ehrs {
		  background: linear-gradient(to right, #aaf 50%, #eef 50%) !important;
		}		
		.unplanned.Fhrs {
		  background: linear-gradient(to right, #aaf 60%, #eef 60%) !important;
		}		
		.unplanned.Ghrs {
		  background: linear-gradient(to right, #aaf 70%, #eef 70%) !important;
		}		
		.unplanned.Hhrs {
		  background: linear-gradient(to right, #aaf 80%, #eef 80%) !important;
		}		
		.unplanned.Ihrs {
		  background: linear-gradient(to right, #aaf 90%, #eef 90%) !important;
		}		
		.fc-event:hover {
		  opacity:0.8;
		}
		.legend {
			display:inline-block;
			border-radius:2px;
			padding:4px 15px;
			font-size:13px;
			margin:0 5px 0 0;
			cursor:default;
		}
	table.detailTable {
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
	}
	</style>

		<div class="main" style="height:calc(100% - 0px); position:relative; padding-bottom:50px;">
			<div id="dump"></div>
			
				<div id="calendar-wrapper" style="position:relative; padding:0; min-height:300px; xheight:calc(100% - 10px); xbackground:rgba(0,0,0,0.3)">
					
					
					<div id="calendar" style="xbackground:red; xdisplay:none; xmax-height:calc(100% - 5px)"></div>				
					
					<table border="0" style="xposition:absolute; xleft:5px; xbottom:5px; xright:5px; xbackground:red; margin:5px">
						<tr>
							<td style="white-space:nowrap; text-align:left">
								<button class="btn btn-primary" id="btn-prev"><i class="fa fa-chevron-left"></i></button>
							</td>
							<td style="white-space:nowrap; text-align:center; width:90%">
								<button class="btn btn-primary" id="btn-month">Month</button>
								<button class="btn btn-primary" id="btn-list">List</button>
								<button class="btn btn-primary" id="btn-today"> <?=$lng['Today']?> </button>
							</td>
							<td style="white-space:nowrap; text-align:right">
								<button class="btn btn-primary" id="btn-next"><i class="fa fa-chevron-right"></i></button>
							</td>
						</tr>
					</table>
				
				</div>
				<div style="height:50px"></div>
					
				
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
	<script src='../assets/js/moment.min.js'></script>
	<script src='assets/js/fullcalendar.js?<?=time()?>'></script>

	<script type="text/javascript">
		
	$(document).ready(function() {
		var height = $('#calendar-wrapper').height() - 70;
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

		/* initialize the calendar */
		$('#calendar').fullCalendar({
			header: {
				center: 'title',
				left: 'month,year',
				right: 'prev,today,next'
			},
			editable: false,
			weekends: true,
			defaultView: 'month',
			//weekNumbers: true,
			eventDurationEditable: false, // resize false
			//locale: 'th',
			firstDay: 1,
			droppable: false,
			html: true, 
			selectable: false,
			//contentHeight: height,
			//hiddenDays: [0,6],//<?//=$non_working_days?>,
			//disabledDays: [0,6],//<?//=$non_working_days?>,
			defaultDate: new Date(),
			/*visibleRange: {
				 start: '2020-01-01',
				 end: '2030-12-31'
			},*/			
			showNonCurrentDates: true,
			events: {
				url: "ajax/json_calendar_leave_event.php",
				data: function(){
					return { emp_id: $('#emp_id').val() };
				}
			},
			/*eventClick: function(calEvent, jsEvent, view) {
			  //alert('Event: ' + calEvent.title);
				$.ajax({
					url: "ajax/get_leave_details.php",
					data: {lid: calEvent.lid},
					dataType: 'json',
					success:function(result){
						//var data = jQuery.parseJSON(result);
						$("#leave_table1").html(result);
						$("#modalLeaveDetails").modal('toggle');
					},
					error:function (rego, ajaxOptions, thrownError){
						alert(thrownError);
					}
				});
    	},*/		
			eventRender: function (event, element, icon) {
				//alert(event.description)
				if (!event.icon == "") {
					if(event.icon == "leave"){
						//element.find('.fc-title').append('<span>' + event.leave + ' - ' + event.description + '</span>');
					}
					if(event.icon == "holiday"){
						//element.find('.fc-title').append('<span>' + event.description + '</span>');
					}
				}
		  },
		  windowResize: function (event, ui) {
				//$('#calendar').fullCalendar('render');
		  },
		});
		/* hide default buttons */
		$('.fc-right, .fc-left').hide();
		$('#btn-prev').click(function () {
			$('.fc-prev-button').click();
			return false;
		});
		$('#btn-next').click(function () {
			$('.fc-next-button').click();
			return false;
		});
		$('#btn-today').click(function () {
			$('.fc-today-button').click();
			return false;
		});
		$('#btn-month').click(function () {
			//$('.fc-month-button').click();
			$('#calendar').fullCalendar('changeView', 'month');
			return false;
		});
		$('#btn-list').click(function () {
			$('#calendar').fullCalendar('changeView', 'listWeek');
			//$('.fc-list-view').click();
			return false;
		});
	
		
		
		
	})

	</script>
