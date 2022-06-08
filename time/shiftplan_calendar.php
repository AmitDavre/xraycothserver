<?
	if($_SESSION['xhr']['access']['time']['module'] == 0){
		echo '<div class="msg_nopermit">You have no permission<br>to enter this page</div>'; 
		exit;
	}
	
	$shiftplan_list = getShiftplanList($cid);
	//$goto[date(($_SESSION['xhr']['cur_year']-1).'-'.sprintf('%02d', $k).'-01')] = $v.' '.($_SESSION['xhr']['cur_year']-1);
	foreach($months as $k=>$v){
		$goto[date($_SESSION['xhr']['cur_year'].'-'.sprintf('%02d', $k).'-01')] = $v.' '.$_SESSION['xhr']['cur_year'];
	}
	//$goto[date(($_SESSION['xhr']['cur_year']+1).'-'.sprintf('%02d', $k).'-01')] = $v.' '.($_SESSION['xhr']['cur_year']+1);
?>
	<link rel="stylesheet" href="../fullcalendar370/fullcalendar.css?<?=time()?>">

<style>
.fc-event {
  position: relative;
  display: block;
  font-size: 20px;
  line-height:28px;
  xheight:62px;
  border:0px red solid;
  background-color: transparent;
  border-radius:0;
  color:#999;
  font-weight: 400;
  white-space:normal;
  text-align:left;
  padding:0 5px;
  top:-25px;
  cursor:default !important;
}
.fc-event .fc-title {
  padding:0;
  cursor:default !important;
}
.fc-event .fc-title span {
  font-size: 12px;
  line-height:14px;
  display:block;
  color:#999;
  background:#eee;
  font-weight:500;
  padding:3px 5px;
  border-radius:1px;
  white-space:normal;
  border-left:5px solid rgba(0,0,0,0.2);
  cursor:default !important;
}
.fc-event .fc-title span.fc-payday {
  color:#fff;
  background: #356e35;
}
.fc-event .fc-title span.fc-nonwork {
  color:#fff;
  background: #57889c;
}
.fc-event .fc-title span.fc-holiday {
  color: #fff;
  background: #ac5287;
}
.fc-event .fc-title span.fc-leave {
  color: #fff;
  background: #b09b5b;
}
.fc-event:hover {
  color:#c00;
}
.fc-toolbar {
	xdisplay:none;
	xbackground:red;
	margin:0 !important;
	xborder:1px red solid !important;
}
.fc-toolbar h2 {
  text-shadow: none !important;
  margin:0 !important;
  padding:0 !important;
  display:block !important;
  font-size: 24px !important;
  background:transparent !important;
  border:0 !important;
  font-weight: 600 !important;
  line-height:40px !important;
  color:#333 !important;
}
.fc-sat, .fc-sun {
}
.fc-week-number {
	font-weight:700;
	color: #0099CC;
	cursor:default !important;
}
td.fc-sat, td.fc-sun {
  xbackground-color: #eee;
}
.fc-day-number {
  padding: 3px 7px 0 0 !important;
  font-weight:600;
  color:#b00;
  font-size:16px;
  cursor:default !important;
}
.fc-day {
  cursor:default !important;
}
.fc-unthemed .fc-disabled-day {
	opacity: 0.5;
	background: #fff url(../images/bg-disabled.png);
}

</style>

	<div class="widget autoheight" style="margin-bottom:15px">
		<h2 class=""><i class="fa fa-calendar"></i>&nbsp; Shiftplan calendar <span><? //=$acid?></span></h2>
		<div class="widget_body">
					
				<div style="position:relative">
					<table border="0" style="width:100%; position:absolute; top:5px">
						<tr>
							<td>
								<select id="shiftType" class="button">
									<option disabled selected value="0">Select shiftplan</option>
									<? foreach($shiftplan_list as $k=>$v){ ?>
											<option value="<?=$k?>"><?=$v?> (<?=$k?>)</option>
									<? } ?>
								</select>
							</td>
							<td style="padding-left:10px">
								<select id="gotoMonth" class="button">
									<option disabled selected value="0">Select Month</option>
									<? foreach($goto as $k=>$v){ ?>
											<option value="<?=$k?>"><?=$v?></option>
									<? } ?>
								</select>
							</td>
							<td style="width:90%"></td>
							<td style="white-space:nowrap">
								<button class="btn btn-primary btn-sm" id="btn-prev"><i class="fa fa-chevron-left"></i></button>
								<button class="btn btn-primary btn-sm" id="btn-today"> <?=$lng['Today']?> </button>
								<button class="btn btn-primary btn-sm" id="btn-next"><i class="fa fa-chevron-right"></i></button>
							</td>
						</tr>
					</table>

					<div id="calendar"></div>
					
				</div>

		</div>
	</div>
	
	<!-- PAGE RELATED PLUGIN(S) -->
	<script src="../fullcalendar370/lib/jquery-ui.min.js"></script>
	<script src='../fullcalendar370/lib/moment.min.js'></script>
	<script src='../fullcalendar370/fullcalendar.js'></script>
	<? if($lang == 'th'){ ?>
	<script src="../fullcalendar370/locale/th.js?<?=time()?>"></script>
	<? } ?>
	
	<script type="text/javascript">
			
		$(document).ready(function() {
	 		
			$(document).on('change', '#shiftType', function () {
				$('#calendar').fullCalendar('refetchEvents');
			});
			$(document).on('change', '#gotoMonth', function () {
				$('#calendar').fullCalendar('gotoDate', $(this).val());
				$(this).val(0)
			});
			$('#calendar:not(".fc-event")').on('contextmenu', function (e) {
				 e.preventDefault()
			})
					
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
				selectable: false,
				contentHeight: 560,
				//hiddenDays: [0,6],//<?//=$non_working_days?>,
				defaultDate: new Date(),
				visibleRange: {
					  start: '2018-01-01',
					  end: '2018-12-31'
				},			
				showNonCurrentDates: false,
				events: {
					url: ROOT+"settings/ajax/server_get_shiftplan.php",
					data: function(){
						return { cid: '<?=$cid?>', code: $('#shiftType').val() };
					}
				},
				eventRender: function (event, element, icon) {
					if (!event.descr == "") {
						 element.find('.fc-title').append('<br/><span class="' + event.class + '">' + event.descr + '</span>');
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
			
		})

	</script>
