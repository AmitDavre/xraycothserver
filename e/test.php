<?php

	if(session_id()==''){session_start();} 
	ob_start();
	include('../dbconnect/db_connect.php');
	
?>

<!DOCTYPE html>
<html lang="en-us">
<head>
	<meta charset="utf-8">
	<title> CENSUS </title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	
	<link rel="stylesheet" type="text/css" href="<?=ROOT?>bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="<?=ROOT?>bootstrap/css/bootstrap-datepicker.min.css" />

	<script type="text/javascript" src="<?=ROOT?>js/jquery-1.12.4.js"></script>
		
	<script>
		var lang = 'th';
		var ROOT = <?=json_encode(ROOT)?>;
	</script>
	
</head>

<body>
		
	<input style="cursor:pointer" readonly type="text" class="datepick">

	<script type="text/javascript" src="<?=ROOT?>bootstrap/js/bootstrap.min.js"></script>
	<script type="text/javascript" src="<?=ROOT?>bootstrap/js/bootstrap-datepicker.min.js"></script>
	<script type="text/javascript" src="<?=ROOT?>bootstrap/js/bootstrap-datepicker.th.min.js"></script>
	
	<script>
		$(document).ready(function() {
	
			$('body').on('focus', '.datepick', function() {
				$(this).datepicker({
					format: "dd-mm-yyyy",
					autoclose: true,
					inline: true,
					language: 'th',//lang+'-th',
					todayHighlight: true,
					//startDate : startYear,
					//endDate   : endYear
				})
			})
			
		})
		
	</script>
	







</body>
</html>








