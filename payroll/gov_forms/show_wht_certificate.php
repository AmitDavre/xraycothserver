<?php
	if(session_id()==''){session_start();}
	ob_start();

	$emps = getEmployees($cid, $_SESSION['rego']['selpr_entities']);
	$emp_array = getJsonUserEmployees($cid, $lang);
	//var_dump($emp_array);
	
	if($res = $dbc->query("SELECT emp_id FROM ".$_SESSION['rego']['payroll_dbase']." GROUP BY emp_id")){
		while($row = $res->fetch_object()){
			$emplo[$row->emp_id] = $row->emp_id;
		}
	}
	//$emplo = array(1=>10003,2=>10009,3=>10011,4=>10013);
	//var_dump(json_encode($emplo));
	
?>	
	<style>
		input[type=text] {
			padding:0;
			margin:0;
			border:0;
			background:transparent;
			width:12px;
			/*-webkit-box-sizing: border-box;
			-moz-box-sizing: border-box;
			box-sizing: border-box;*/
		}
		input[type=text].ro {
			background:transparent;
			font-weight:bold;
		}
		input[type=text].calc1 {
			text-align:right;
			padding:0 5px;
			width:70px;
		}
		input[type=text].calc2 {
			text-align:right;
			padding:0 5px;
			width:120px;
		}
		div.field {
			position:absolute;
			min-height:12px;
			border:0px solid red;
			font-size:15px;
			line-height:100%;
			white-space:nowrap;
			color: #039;
		}
		div.field13, div.field13n, div.field13c, div.field11, div.field12 {
			position:absolute;
			min-height:12px;
			border:0px solid red;
			font-size:14px;
			line-height:100%;
			white-space:nowrap;
			color:#039;
		}
		div.field13n {
			width:100px;
			text-align:right;
		}
		div.field13c {
			width:192px;
			text-align:center;
		}
		div.field11 {
			min-width:20px;
			font-size:11px;
		}
		div.field12 {
			font-size:14px;
		}
		label input[type="radio"].radiobox + span:before,
		label input[type="checkbox"].checkbox + span:before {
		  font-size: 11px;
		  height: 11px;
		  line-height: 12px;
		  min-width: 12px;
		  margin-right: 5px;
		  margin-top: 1px;
		  padding-top:1px; 
		}
		div.signature {
			width:200px;
			position:absolute;
			border:0px solid red;
			text-align:center;
		}
		div.stamp {
			position:absolute;
			border:0px solid red;
			text-align:center;
		}
	</style>
		
<div class="A4form" style="width:900px;height:1260px; padding:27px 30px 100px; background:#fff url(<?=ROOT?>images/forms/wht_certificate.png) no-repeat; background-size:cover">

<div style="display:block; padding-top:0px">

	<table border="0" style="width:100%; margin-bottom:2px">
		<tr>
			<td style="padding-left:11px">
				<input style="width:200px; border:1px #ccc solid; padding:5px 10px; font-size:13px" placeholder="Search employee ..." type="text" id="autocomplete-name" />
				<input type="hidden" name="emp_id" id="emp_id" value="" />
			</td>
			<td style="padding-left:11px;width:10%">
				<input type="text" id="fromdate" class="datepick" placeholder="Date from..."  style="width:150px; border:1px #ccc solid; padding:5px 10px; font-size:13px">
			</td>
			<td style="padding-left:11px;width:10%">
				<input type="text" id="todate" class="datepick" placeholder="Date to..."  style="width:150px; border:1px #ccc solid; padding:5px 10px; font-size:13px">
			</td>
			<td style="padding-left:11px;width:5%">
				<a class="btn btn-default btn-sm" id="getdatafromdate"><i class="fa fa-reply fa-lg"></i></a>
			</td>
			<td style="padding:0 10px; width:55%"><div id="message" style="display:block; display:none"></div></td>
			<td style="text-align:right; padding-right:13px">
				<div class="btn-group btn-payroll">
					<button class="dropdown-toggle btn btn-primary" data-toggle="dropdown">
						<i class="genPdf fa fa-file-pdf-o"></i>&nbsp; <?=$lng['Generate PDF']?>
					</button>
					<div class="dropdown-menu">
						<a class="dropdown-item" id="print"><?=$lng['Only this employee']?></a>
						<a class="dropdown-item" id="printall"><?=$lng['All employees (one file)']?></a>
					</div>
				</div>
			</td>
		</tr>
	</table>
	
	<div style="position:relative" id="formBody"></div>
	
</div>
	
</div>
	
	<script type="text/javascript" src="../assets/js/jquery.autocomplete.js"></script>

	<script type="text/javascript">
		
		$(document).ready(function() {
			
			var emp_array = <?=json_encode($emp_array)?>;
			var emps = <?=json_encode($emps)?>;
			$('#autocomplete-name').devbridgeAutocomplete({
				 lookup: emp_array,
				 onSelect: function (suggestion) {
				 	//alert(suggestion.data)
				 	$("#emp_id").val(suggestion.data);

				 	var fromdate = $('#fromdate').val();
				 	var todate = $('#todate').val();

				 	//alert(fromdate);
				 	//alert(todate);

					$.ajax({
						url: "ajax/get_wht_certificate.php",
						data: {emp_id: suggestion.data, fromdate:fromdate, todate:todate},
						success: function(result){
							//$('#dump').html(result);
							$('#formBody').html(result);
						},
						error:function (xhr, ajaxOptions, thrownError){
							alert(thrownError);
						}
					});
				 }
			});	
			
			$(document).on("click", "#getdatafromdate", function(e) {
				var emp_id = $('#emp_id').val();
				if(emp_id != ''){
					var fromdate = $('#fromdate').val();
				 	var todate = $('#todate').val();
				 	if(fromdate && todate != ''){

						$.ajax({
							url: "ajax/get_wht_certificate.php",
							data: {emp_id: emp_id, fromdate:fromdate, todate:todate},
							success: function(result){
								//$('#dump').html(result);
								$('#formBody').html(result);
							},
							error:function (xhr, ajaxOptions, thrownError){
								alert(thrownError);
							}
						});
					}else{
						$("body").overhang({
							type: "error",
							message: '<i class="fa fa-exclamation-triangle"></i>&nbsp;&nbsp;<?=$lng['Error']?>: Both dates are required',
							duration: 2,
						})
					}
				}else{
					$("body").overhang({
						type: "error",
						message: '<i class="fa fa-exclamation-triangle"></i>&nbsp;&nbsp;<?=$lng['Error']?>: Employee is not selected',
						duration: 2,
					})
				}
			});

			$(document).on("click", "#print", function(e) {
				var data = $("#tax_return").serialize();
				//alert(data); //return false;

				var fromdate = $('#fromdate').val();
				var todate = $('#todate').val();

				if($('#emp_id').val() != ''){
					window.open(ROOT+'payroll/print/print_wht_certificate.php?id='+$('#emp_id').val()+'&from='+fromdate+'&to='+todate);
				}
			});
			
			var emplo = <?=json_encode($emplo)?>;
			var cemplo = '<?=count($emplo)?>';
			
			$(document).on("click", "#printall", function(e) {

				var fromdate = $('#fromdate').val();
				var todate = $('#todate').val();

				$("body").overhang({
					type: "confirm",
					message: "<?=$lng['This may take a while, depending on the number of pages. Continue ?']?>",
					yesMessage:"<?=$lng['Yes']?>",
					noMessage:"<?=$lng['No']?>",
					callback: function (value) {
					 	if(value){window.open(ROOT+'payroll/print/render_wht_certificates.php?from='+fromdate+'&to='+todate);}
				  	}
				})
				return false;
			})
			
		})
	
	</script>













