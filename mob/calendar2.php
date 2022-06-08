
	<link rel="stylesheet" href="assets/css/monthly.css?<?=time()?>">
			
	<div class="container-fluid" style="padding:0; background:red; height:calc(100% - 70px)">
		<div class="monthly" id="mycalendar"></div>
	</div>
		

	<script type="text/javascript" src="assets/js/monthly.js"></script>
	<script type="text/javascript">

		var sampleEvents = {
		"monthly": [
			{
			"id": 1,
			"name": "Anual leave",
			"startdate": "2021-01-11",
			"enddate": "2021-01-15",
			"starttime": "",
			"endtime": "",
			"color": "#99CCCC",
			"url": ""
			},
			{
			"id": 2,
			"name": "Sick",
			"startdate": "2021-01-05",
			"enddate": "2021-01-05",
			"starttime": "",
			"endtime": "",
			"color": "#CC99CC",
			"url": ""
			},
			{
			"id": 3,
			"name": "Leave first half",
			"startdate": "2021-01-26",
			"enddate": "",
			"starttime": "",
			"endtime": "",
			"color": "#669966",
			"url": ""
			},
			{
			"id": 4,
			"name": "Leave second half",
			"startdate": "2021-01-26",
			"enddate": "",
			"starttime": "",
			"endtime": "",
			"color": "#999966",
			"url": ""
			}
		]
		};
			
		var myEvents;
		$.ajax({
			url: "ajax/mob_calendar_leave_events.php",
			dataType: 'json',
			success:function(result){
				myEvents = result
			},
			error:function (rego, ajaxOptions, thrownError){
				alert(thrownError);
			}
		});
	
	$(document).ready( function() {
		var lang = <?=json_encode($lang)?>;
		
		//alert(lang)
		var th_months = ["มค","กพ","มีค","เมย","พค","มิย","กค","สค","กย","ตค","พย","ธค"];
		var months = ["Jan", "Feb", "Mar", "Apr", "May", "June", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
		if(lang == 'th'){months = th_months;}
		
		var th_days = ['จ.','อ.','พ.','พฤ.','ศ.','ส.','อา.'];
		var days = ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"];
		if(lang == 'th'){days = th_days;}
	
		setTimeout(function(){
			$('#mycalendar').monthly({
				mode: 'event',
				dataType: 'json',
				monthNames: months,
				dayNames: days,
				events: myEvents
			});
		},200);	
			
	});
	
	
</script>









