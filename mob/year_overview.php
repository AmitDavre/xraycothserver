	
	<div class="container-fluid" style="xborder:1px solid red">
		<div class="row" style="xborder:1px solid green; padding:10px 3px">

			<div style="width:100%; overflow-x:auto; margin-bottom:5px">
				<section id="overviewTable"></section>
			</div>

			<button onClick="window.open('../payroll/print/print_employee_tax_overview.php?id=<?=$_SESSION['rego']['emp_id']?>');" type="button" class="btn btn-default btn-block"><i class="fa fa-download"></i><?=$lng['Download']?></button>
		
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
				//$('#dump').html(result); return false;
				$('#overviewTable').html(result['table']);
				$('.modalTable').addClass('accordion-table bordered thead-border').removeClass('modalTable');
				$('#showTable').fadeIn(200);
			},
			error:function (xhr, ajaxOptions, thrownError){
				alert(thrownError);
			}
		});
		
		
	})
	
</script>	










