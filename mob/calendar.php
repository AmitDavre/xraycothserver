<?

?>	
	<link rel="stylesheet" href="assets/css/fullcalendar.css?<?=time()?>">
	
	<div class="main" style="height:calc(100% - 0px); position:relative; padding-bottom:50px;">
		<div id="dump"></div>
		
		<div id="calendar-wrapper" style="padding:0; min-height:300px; display:none">
			<div id="calendar"></div>				
			
			<table border="0" style="margin:5px">
				<tr>
					<td style="white-space:nowrap; text-align:left">
						<button style="padding:0 10px 0 16px" class="btn btn-primary" id="btn-prev"><i class="fa fa-chevron-left"></i></button>
					</td>
					<td style="white-space:nowrap; text-align:center; width:95%">
						<button class="btn btn-primary" id="btn-month"><?=$lng['Month']?></button>
						<button class="btn btn-primary" id="btn-list"><?=$lng['List']?></button>
						<button class="btn btn-primary" id="btn-today"><?=$lng['Today']?></button>
					</td>
					<td style="white-space:nowrap; text-align:right">
						<button style="padding:0 8px 0 18px" class="btn btn-primary" id="btn-next"><i class="fa fa-chevron-right"></i></button>
					</td>
				</tr>
			</table>
		
		</div>
		<div style="height:50px"></div>
		
	</div>
	
	<script src='../assets/js/moment.min.js'></script>
	<script src='assets/js/fullcalendar.js?<?=time()?>'></script>
	<script src='assets/js/th.js?<?=time()?>'></script>

	<script type="text/javascript">
		
	$(document).ready(function() {
		var lang = <?=json_encode($_SESSION['rego']['lang'])?>;

		$('#calendar').fullCalendar({
			header: {
				center: 'title',
				left: 'month,year',
				right: 'prev,today,next'
			},
			editable: false,
			weekends: true,
			defaultView: 'month',
			eventDurationEditable: false, // resize false
			locale: lang,
			firstDay: 1,
			droppable: false,
			html: true, 
			selectable: false,
			defaultDate: new Date(),
			showNonCurrentDates: true,
			events: {
				url: "ajax/json_calendar_leave_event.php"
			},
			eventAfterAllRender: function(view){
        $('#calendar-wrapper').fadeIn(200);
      }
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
