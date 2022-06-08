	
<style>
	table.basicTable tbody tr:hover {
		background:#cfc;
		cursor:default;
	}
	table.basicTable tr.igrey td {
		color:#999;
	}
	.modalTable thead th {
		white-space:normal !important;
		vertical-align:middle !important;
		line-height:140%;
		text-align:center !important;
	}

	.pannel {
		position:absolute; 
		top:130px; 
		bottom:10px; 
		border:0px solid red;
		padding:15px;
		box-size:border-box;
		overflow:hidden;
	}
	.left_pannel {
		left:0; 
		width:205px;
		padding-right:5px;
	}
	.main_pannel {
		left:205px; 
		right:0;
		padding-left:5px;
	}
	b {
		font-weight:600;
	}
	input, select, textarea {
		background:transparent !important;
	}
	textarea{  
		box-sizing: border-box;
		resize: none;
		overflow:hidden;
	}
		.fileBtn {
			display:block;
			margin-top:5px;
		}
		.fileBtn [type="file"]{
			border:0;
			visibility:false;
			position:absolute;
			width:0px;
			height:0px;
		}
		.fileBtn label{
			background:#eee;
			background: linear-gradient(to bottom, #eee, #ddd);
			border-radius: 2px;
			border:1px #ccc solid;
			padding:1px 8px;
			line-height:18px;
			white-space:nowrap;
			color: #000;
			cursor: pointer;
			display: inline-block;
			xfont-family: 'Open Sans', sans-serif;
			font-size:13px;
			font-weight:400;
		}
		.fileBtn label:hover{
			background: linear-gradient(to bottom, #ddd, #eee);
		}
		.fileBtn p {
			padding:0 0 0 5px;
			margin:0;
			display:inline-block;
			xfont-family: Arial, Helvetica, sans-serif;
			font-size:13px;
		}
</style>

<div style="width:100%">
	<h2><i class="fa fa-list-ul"></i>&nbsp; <?=$lng['Year overview'].' '.$_SESSION['rego']['year_'.$lang]?></h2>
	<div id="dump"></div>
	
	<div class="pannel left_pannel">
		<? include('emp_picture.php'); ?>
	</div>
	
	<div class="pannel main_pannel" style="padding-bottom:0">
		<div style="padding:10px; border:1px #ccc solid; height:100%; height:100%; overflow-Y:auto">
			<div id="showTable" style="display:none">
				<div id="overviewTable"></div>
				<button style="margin-top:10px" onClick="window.open('<?=ROOT?>payroll/print/print_employee_tax_overview.php?id=<?=$_SESSION['rego']['emp_id']?>',' _blank')" class="btn btn-primary"><i class="fa fa-download"></i>&nbsp;&nbsp;<?=$lng['Download'].' / '.$lng['Print']?></button>
			</div>
		</div>

	</div>
	
</div>


<script type="text/javascript">
	
	$(document).ready(function() {
		
		var emp_id = <?=json_encode($_SESSION['rego']['emp_id'])?>;

		$.ajax({
			url: "../payroll/ajax/get_annual_overview.php",
			data: {id: emp_id},
			dataType: 'json',
			success: function(result){
				//$('#dump').html(result);
				$('#overviewTable').html(result.table);
				$('#showTable').fadeIn();
			},
			error:function (xhr, ajaxOptions, thrownError){
				alert(thrownError);
			}
		});
		
	})
	
</script>

























