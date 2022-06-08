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
	
	<script type="text/javascript" src="../js/jquery-1.12.4.js"></script>
	
</head>

<body>

	<form id="attachForm" enctype="multipart/form-data" method="post">
		 <input onChange="$('#attachForm').submit()" name="attach" id="attachFile" type="file" />
		 <input type="hidden" name="emp_id" value="DEMO-001" />
		 <input type="hidden" id="attachField" name="field" />
	</form>
	<br><button onClick="$('#attachField').val('family');$('#attachFile').click();">Passport</button>
	<button onClick="$('#attachField').val('IDcard');$('#attachFile').click();">ID Card</button>
	<button onClick="$('#attachField').val('TM40');$('#attachFile').click();">TM40 form</button>
	
	<div id='dump'></div>	

<script type="text/javascript">
		
	$(document).ready(function() {
 		
		$("#attachForm").on('submit', function(e){
			e.preventDefault();
			var formData = new FormData(this);
			//$("#dump").html(formData); return false;
			$.ajax({
				url: "ajax/update_employees_attach.php",
				type: "POST", 
				data: formData,
				cache: false,
				processData:false,
				contentType: false,
				success: function(result){
					
					$("#attachFile").val('');
					$("#dump").html(result); return false;
				},
				error:function (xhr, ajaxOptions, thrownError){
					alert(thrownError);
				}
			});
		});
		
		
		
	});
		
</script>
	
</body>
</html>








