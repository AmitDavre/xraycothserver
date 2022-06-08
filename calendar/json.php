<!DOCTYPE html>
<html>
<head>
<meta charset='utf-8' />
<link href='fullcalendar.css?<?=time()?>' rel='stylesheet' />
<script src='lib/moment.min.js'></script>
<script src='lib/jquery.min.js'></script>
<script src='fullcalendar.min.js'></script>
<script>

	$(document).ready(function() {
	
		$('#calendar').fullCalendar({
			header: {
				left: 'prev,next today',
				center: 'title',
				right: 'month,listWeek'
			},
			defaultDate: '2017-11-12',
			editable: false,
			//navLinks: true, // can click day/week names to navigate views
			//eventLimit: true, // allow "more" link when too many events
			events: {
				url: 'php/get-events.php',
				error: function() {
					$('#script-warning').show();
				}
			},
			loading: function(bool) {
				$('#loading').toggle(bool);
			}
		});
		
	});

</script>
<style>

	body {
		margin: 0;
		padding: 0;
		font-family: sans-serif, Arial,Verdana;
		font-size: 14px;
	}

	#script-warning {
		display: none;
		background: #eee;
		border-bottom: 1px solid #ddd;
		padding: 0 10px;
		line-height: 40px;
		text-align: center;
		font-weight: bold;
		font-size: 12px;
		color: red;
	}

	#loading {
		display: none;
		position: absolute;
		top: 10px;
		right: 10px;
	}

	#calendar {
		width: 100%;
		margin: 0;
		padding:0;
	}

</style>
</head>
<body>
					<table border="0" style="width:100%; position:absolute; left:0; top:5px">
						<tr>
							<td style="white-space:nowrap; text-align:left">
								<button class="btn btn-primary" id="btn-prev"><<i class="fa fa-chevron-left"></i></button>
								<button class="btn btn-primary" id="btn-next">><i class="fa fa-chevron-right"></i></button>
								<button class="btn btn-primary" id="btn-today"> Today<? //=$lng['Today']?> </button>
							</td>
							<td style="width:80%"></td>
							<td style="white-space:nowrap; text-align:right">
								<button class="btn btn-primary" id="btn-month">Month</button>
								<button class="btn btn-primary" id="btn-list">List</button>
							</td>
						</tr>
					</table>

	<div id='calendar'></div>
	
	
	
	<script type="text/javascript">
		
	$(document).ready(function() {
		//var height = $('#calendar-wrapper').height() - 40;
		//alert(height);

		/*var fcyear = true;	
		$('#calendar:not(".fc-event")').on('contextmenu', function (e) {
			 e.preventDefault()
		})
		$(document).on('change', '#gotoMonth', function () {
			$('#calendar').fullCalendar('gotoDate', $(this).val());
			$(this).val(0)
		});*/
		
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
			//locale: 'th',
			firstDay: 1,
			droppable: false,
			html: true, 
			selectable: false,
			//contentHeight: height,
			//hiddenDays: [0,6],//<?//=$non_working_days?>,
			disabledDays: [0,6],//<?//=$non_working_days?>,
			defaultDate: new Date(),
			visibleRange: {
				 start: '2020-01-01',
				 end: '2030-12-31'
			},			
			showNonCurrentDates: false,
			events: {
				url: "leave/ajax/json_calendar_leave_events3.php",
				data: function(){
					return { emp_id: $('#emp_id').val() };
				}
			},
			eventClick: function(calEvent, jsEvent, view) {
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
    	},		
			eventRender: function (event, element, icon) {
				//alert(event.description)
				if (!event.icon == "") {
					if(event.icon == "leave"){
						element.find('.fc-title').append('<span>' + event.leave + ' - ' + event.description + '</span>');
					}
					if(event.icon == "holiday"){
						//element.find('.fc-title').append('<span>' + event.description + '</span>');
					}
				}
		  },
		  windowResize: function (event, ui) {
				$('#calendar').fullCalendar('render');
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
			$('.fc-month-button').click();
			return false;
		});
		$('#btn-list').click(function () {
			$('#calendar').fullCalendar('changeView', 'listWeek');
			//$('.fc-list-view').click();
			return false;
		});
	
	
		
		
		
	})

	</script>
</body>
</html>
