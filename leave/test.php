<?php

	if(session_id()==''){session_start();} 
	ob_start();
	include('../dbconnect/db_connect.php');
	include(DIR.'files/functions.php');
	include(DIR.'leave/functions.php');
	include(DIR.'time/functions.php');
	include(DIR.'files/arrays_'.$_SESSION['rego']['lang'].'.php');

	$logger = false;
	if(isset($_SESSION['rego']['cid']) && !empty($_SESSION['rego']['cid'])){
		if(isset($_SESSION['RGadmin'])){
			$logtime = 86000;
		}
		if(time() - $_SESSION['rego']['timestamp'] > $logtime) {
			$_SESSION['rego']['timestamp'] = 0;
			$logger = false; 
		}else{
			$_SESSION['rego']['timestamp'] = time();
			$logger = true;
		}
		//$leave_types = getSelLeaves($cid);
		$leave_types = getSelLeaveTypes($cid);
		$leave_period_start = '01-01-'.date('Y');
		$leave_period_end = '31-12-'.date('Y');
		//$leave_request_before = $compinfo['request'];
		//var_dump($leave_types);
		
	}
?>

<!DOCTYPE html>
<html lang="en-us">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
		<meta name="robots" content="noindex, nofollow">
		<title>REGO HR</title>
	
		<link rel="shortcut icon" href="../images/favicon.ico?x" type="image/x-icon">
		<link rel="icon" href="../images/favicon.ico?x" type="image/x-icon">
    
		<link rel="stylesheet" href="../assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/css/font-awesome.min.css">
    <link rel="stylesheet" href="../assets/css/line-awesome.min.css">
    <link rel="stylesheet" href="../assets/css/style.css?<?=time()?>">
		<link rel="stylesheet" href="../assets/css/navigation.css?<?=time()?>">
		<link rel="stylesheet" href="../assets/datepicker/bootstrap-datepicker.css" />
		<link rel="stylesheet" href="../assets/css/myBootstrap.css?<?=time()?>">
		<link rel="stylesheet" href="../assets/css/basicTable.css?<?=time()?>">
		<link rel="stylesheet" href="../assets/css/myForm.css?<?=time()?>">
		<link rel="stylesheet" href="../assets/css/dataTables.bootstrap4.min.css?<?=time()?>">
		<link rel="stylesheet" href="../assets/css/myDatatables.css?<?=time()?>">
		<link rel="stylesheet" href="../assets/css/overhang.min.css?<?=time()?>">
		<link rel="stylesheet" href="../assets/css/responsive.css?<?=time()?>">

		<!--<link rel="stylesheet" href="../timepicker/dist/jquery-clockpicker.min.css">-->
		<link rel="stylesheet" href="../css/autocomplete.css?<?=time()?>">
		<link rel="stylesheet" href="../yearcalendar/css/bootstrap-year-calendar2.css?<?=time()?>">
		
		<script src="../assets/js/jquery-3.2.1.min.js"></script>
		<script src="../assets/js/jquery-ui.min.js"></script>
	

</head>
<style>
	table.mytable tbody td, 
	table.mytable thead th {
		text-align:left;
		padding:2px 10px;
	}
	
	table.sum tbody td, 
	table.mytable tfoot td {
		text-align:right;
		padding:3px 10px;
	}
	table.sum thead th {
		padding:5px 10px;
		text-align:center;
	}
	table.sum tfoot td {
		font-weight:700;
		background:#eee;
	}
	
	.legend {
		display:inline-block;
		padding:4px 15px;
		font-size:13px;
		margin:0 5px 0 0;
		cursor:default;
	}
	
	.RQ {
	  background: #C5B8EE !important;
	}		
	.AP {
	  background: #9CE7B9 !important;
	}		
	.RJ {
	  background: #F593D3 !important;
	}		
	.CA {
	  background: #F8CD96 !important;
	}		
	.TA {
	  background: #C4DAEE !important;
	}		
	.holiday {
	  background: #fb0 !important;
	}		
	.nonworking {
	  background: #eee !important;
	}		
	.working {
	  background: #fff !important;
	  border:1px #ddd solid;
	}
	table.dataTable tbody td a {
		text-decoration:none;
		font-weight:600;
		color:#003399;
	}	
	table.dataTable tbody td a:hover {
		xcolor:#c00 !important;
	}
	table.dataTable tr td:nth-child(1) {
		cursor:pointer !important;
	}
	table.dataTable tr td:nth-child(1):hover a {
		color:#c00 !important;
		text-decoration:underline;
	}
		
	.date_month, .cdate_month {
		cursor:pointer;
	}
	
	
	table.basicTable table.detailTable {
		width:100%;
	}
	table.basicTable table.detailTable tbody td {
		padding:2px 8px !important;
		border:0;
	}
	table.basicTable table.detailTable tr {
		border-bottom:1px solid #eee;
	}
	table.basicTable table.detailTable tr:last-child {
		border-bottom:0;
	}
	
	
	table.noStyle tr {
		border: 0 !important;
		border-bottom: 1px solid #eee !important;
	}
	table.noStyle tr:last-child {
		border: 0 !important;
		border-bottom: 0 !important;
	}
	table.noStyle th, table.noStyle td {
		margin: 0 !important;
		padding: 4px 8px !important;
		border: 0 !important;
		border-right: 1px solid #eee !important;
		outline: 0 !important;
		xfont-size: 100% !important;
		vertical-align: baseline !important;
		background: transparent !important;
	}
	table.noStyle td:last-child {
		border-right: 0 !important;
	}
		
	.btn-small {
		padding:0 10px;
		margin:0;
		background: #006699;
		color:#fff;
		border:0;
		font-size:14px;
		line-height:30px;
		text-align:center;
		width:100%;
		display:block;
		cursor:pointer;
	}
	.popTable {
		width:230px;
		border-collapse:collapse;
		table-layout:fixed;
		margin:0;
	}
	.popTable td {
		padding:3px;
	}
	.popTable td .btn {
		padding:2px 10px !important;
		text-align:center;
		width:100%;
		display:block !important;
		font-size:14px !important;
		font-weight:400 !important;
		color:#333 !important;
	}
	.popTable td input[type="text"] {
		padding:4px 10px;
		text-align:center;
		width:100%;
		cursor:pointer;
		font-size:14px !important;
		color:#333 !important;
		font-weight:400 !important;
	}
	.popTable td input[type="text"]:hover {
		border:1px solid #999;
	}
	.dayType {
		min-width:100px !important;
		font-size:13px !important;
		font-weight:400 !important;
		text-align:center !important;
	}
	.selEmp {
		line-height:26px;
		width:100%;
		display:block;
		padding:0 8px;
	}
	.selStatus {
		padding:0px 5px 1px !important;
		xmargin:0 3px !important;
		border-radius:2px !important;
		color:#fff !important;
		font-size:12px !important;
	}
	.selStatus option {
		background:#fff;
		color:#000;
	}
	table.dataTable tbody td.pad05 {
		padding:0 5px !important;
	}
	
	.popover {
	  border-radius: 3px;
		max-width:none;
		z-index: 9010; /* A value higher than 1010 that solves the problem */	
	}
	.popover .popover-body {
	  padding:10px !important;
	  color:#a00;
	  font-weight:400;
	  line-height:120%;
	}
	.popover-body span {
	  color:#000;
	}
	

</style>

<body style="padding:100px">
	
	<button type="button" class="btn btn-secondary popRequest">Popover Request</button><br>
	<button type="button" class="btn btn-secondary popReject">Popover Reject</button>
			
		<div id="popoverReject" class="d-none">
			<div style="width:350px">
			<form id="popForm" class="popReject">
					<div>
						<textarea placeholder="Reason" name="comment" rows="4" style="width:100%; border:0; padding:8px;resize:none;color:#333; border:1px solid #eee"></textarea>
					</div>
					<div style="padding:10px 0 5px 0">
						<button type="submit" class="btn btn-default btn-xs butReject" style="display:inline-block;float:left"><i class="fa fa-thumbs-down-o"></i>&nbsp;Submit</button>
						<button type="button" class="btn btn-default btn-xs butCancel" style="display:inline-block;float:right">Cancel</button>
						<div style="clear:both;"></div>
					</div>
			</form>
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
					<td><input id="timeFrom" class="timePic" readonly placeholder="From 00:00" type="text" value=""></td>
					<td><input id="timeUntil" class="timePic" disabled readonly placeholder="Until 00:00" type="text" value=""></td>
				</tr>
			</table>
    </div>	
	
	<script src="../assets/js/popper.min.js"></script>
	<script src="../assets/js/bootstrap.min.js"></script>
	
	
<script type="text/javascript">
		
	$(document).ready(function() {
 		
		$('.popRequest').popover({
			placement: 'right',
			container: 'body',
			html: true,
			sanitize: false,
			//selector: '.example', //Sepcify the selector here
			title: '<span id="pop_title">Title</span>',
			content: function () {
          return $("#popoverRequest").html();
      }
		})

		$('.popReject').popover({
			placement: 'right',
			container: 'body',
			html: true,
			sanitize: false,
			//selector: '.example', //Sepcify the selector here
			title: '<span id="pop_title">Title</span>',
			content: function () {
          return $("#popoverReject").html();
      }
		})

	});
		
</script>
	
</body>
</html>








