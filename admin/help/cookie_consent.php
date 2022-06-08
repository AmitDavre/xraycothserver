<?php
		
	$th_content = '';
	$en_content = '';
	$res = $dba->query("SELECT * FROM rego_cookie_consent");
	if($row = $res->fetch_assoc()){
		$th_content = $row['th_content'];
		$en_content = $row['en_content'];
	}	
	//var_dump($th_content);
	//var_dump($en_content);
?>

  	<link rel="stylesheet" href="../assets/css/summernote-bs4.css?<?=time()?>">
		<script type="text/javascript" src="../assets/js/summernote-bs4.js?<?=time()?>"></script>
		<? if($lang == 'th'){ ?>
		<script type="text/javascript" src="../assets/js/summernote-th-TH.js?<?=time()?>"></script>
		<? } ?>

<style>

	table.basicTable.pagesTable tbody tr:hover {
		background:#dfd;
		cursor:pointer;
	}
	table.basicTable.pagesTable tbody tr:hover > td {
		color:#b00;
		xfont-weight:600;
	}
	table.basicTable.pagesTable tbody tr.active {
		background:#ffc;
	}
	table.basicTable.pagesTable tbody tr.active > td {
		color:#b00;
		font-weight:600;
	}
	.card {
		box-shadow:none;
	}





</style>
	
	<form id="termsForm">
	<h2><i class="fa fa-file-text-o fa-lg"></i>&nbsp;&nbsp;Cookie Consent<button id="subButton" class="btn btn-primary" style="float:right; margin-top:3px"><i class="fa fa-save"></i>&nbsp; Update</button></h2>
	
	<div class="main">
		<div style="padding:0 0 0 20px" id="dump"></div>
		
			<table style="width:100%; height:calc(100% - 3px); table-layout:fixed" border="0">
				<tr>
					<td style="width:50%; vertical-align:top; padding-right:10px; border-right:1px solid #eee">
				
						<table id="helpTable1" class="basicTable wsnormal" style="height:100%; width:100%">
							<thead>
								<tr>
									<th>Thai</th>
								</tr>
							</thead>
							<tbody>
								<tr style="border:x0">
									<td id="helpTD" style="vertical-align:top; padding:0">
										<textarea name="th_content" id="th_content" class="summernote1"></textarea>
									</td>
								</tr>
							</tbody>
						</table>
					
					</td>
					<td style="width:50%; vertical-align:top; padding-left:10px">
							
						<table id="helpTable2" class="basicTable wsnormal" style="height:100%; width:100%">
							<thead>
								<tr>
									<th>English</th>
								</tr>
							</thead>
							<tbody>
								<tr style="border:x0">
									<td style="vertical-align:top; padding:0">
										<textarea name="en_content" id="en_content" class="summernote2"></textarea>
									</td>
								</tr>
							</tbody>
						</table>
			
					</td>
				</tr>
			</table>	

   </div>
	</form>
					
	<script>

		$(document).ready(function() {

		

		

			
			var thcontent = <?=json_encode($th_content)?>;
			var encontent = <?=json_encode($en_content)?>;
			
			var targetOffset = $('#helpTD').height()-38;
			var summerOptions = {
				placeholder: 'Start typing ...',
				tooltip: false,
				height: targetOffset,
				disableResizeEditor: true,
				disableDragAndDrop: true,
				fontNames: ['Arial', 'Arial Black'],
				fontSizes: ['13','15','18','20','22','26'],
				toolbar: [
					['style', ['bold', 'italic', 'clear']],
					['fontsize', ['fontsize']],
					//['fontname', ['fontname']],
					['color', ['color']],																																																						
					['para', ['ul', 'ol', 'paragraph']],
              	['insert', ['link']],
              	//['view', ['fullscreen', 'codeview']],
              	['view', ['fullscreen']],
					['misc', ['undo', 'redo']],
				],
				styleTags: ['p', 'h2', 'h3'],
				callbacks: {
				  onPaste: function (e) {
					var bufferText = ((e.originalEvent || e).clipboardData || window.clipboardData).getData('Text');
					e.preventDefault();
					// Firefox fix
					setTimeout(function () {
						 document.execCommand('insertText', false, bufferText);
					}, 10);
				  }
				 }
		  	}
			$('.summernote1').summernote(summerOptions);
			$('.summernote2').summernote(summerOptions);
			$(".summernote1").summernote("code", thcontent);
			$(".summernote2").summernote("code", encontent);


			$('div').on('input', function() {
			  var span = $('#restrict').text();
			  if (span.indexOf('clicking here') < 0) {
			    $('#restrict').text('clicking here');
			    $('#restrict').css('pointer-events','none');
			  }			  
			  var span1 = $('#restrict1').text();
			  if (span1.indexOf('clicking here') < 0) {
			    $('#restrict1').text('clicking here');
			    $('#restrict1').css('pointer-events','none');
			  }
			});


			$('#restrict').on('cut', function(e) {
			    e.preventDefault();
			});			

			$('#restrict1').on('cut', function(e) {
			    e.preventDefault();
			});

			$('#restrict').keydown(function(event) {
				console.log(event);

		        if (event.ctrlKey==true && (event.which == '75')) {
		            event.preventDefault();
		         }

		          if  (event.which == '8' || event.which == '46') {
		            event.preventDefault();
		         }

		    });				

		    $('#restrict1').keydown(function(event) {
				console.log(event);

		        if (event.ctrlKey==true && (event.which == '75')) {
		            event.preventDefault();
		         }

		          if  (event.which == '8' || event.which == '46') {
		            event.preventDefault();
		         }

		    });	

			$('#restrict').keyup(function(event) {

				console.log(event);
		        if (event.ctrlKey==true && (event.which == '75')) {
		            event.preventDefault();
		         }

		          if  (event.which == '8' || event.which == '46') {
		            event.preventDefault();
		         }

		    });				

		    $('#restrict1').keyup(function(event) {

				console.log(event);
		        if (event.ctrlKey==true && (event.which == '75')) {
		            event.preventDefault();
		         }

		          if  (event.which == '8' || event.which == '46') {
		            event.preventDefault();
		         }

		    });			

			var unFocus = function () {
			if (document.selection) {
			document.selection.empty()
			} else {
			window.getSelection().removeAllRanges()
			}

			}


			document.getElementById('restrict').onmousemove = function () {
			unFocus()
			}		

			document.getElementById('restrict').onmouseup = function () {
			unFocus()
			}			

			document.getElementById('restrict1').onmousemove = function () {
			unFocus()
			}		

			document.getElementById('restrict1').onmouseup = function () {
			unFocus()
			}



			$( "#restrict" ).on( "click", function() {

			  	document.getElementById('restrict').onmousemove = function () {
				unFocus()
				}		

				document.getElementById('restrict').onmouseup = function () {
				unFocus()
				}
			
			});

			$( "#restrict1" ).on( "click", function() {

			  	document.getElementById('restrict1').onmousemove = function () {
				unFocus()
				}		

				document.getElementById('restrict1').onmouseup = function () {
				unFocus()
				}
			
			});






	
			$("#termsForm").submit(function(e){ 
				e.preventDefault();
				var data = $(this).serialize();
				$.ajax({
					url:"help/ajax/update_cookie_consent.php",
					data: data,
					type: 'POST',
					success: function(result){
						//$('#dump').html(result); return false;
						if(result == 'success'){
							$("body").overhang({
								type: "success",
								message: '<i class="fa fa-check"></i>&nbsp;&nbsp;Data updated successfuly<? //=$lng['Data updated successfuly']?>',
								duration: 2,
							})
							//setTimeout(function(){location.reload();},2000);	
						}else{
							$("body").overhang({
								type: "error",
								message: '<i class="fa fa-exclamation-triangle"></i>&nbsp;&nbsp;<?=$lng['Error']?> : '+data.result,
								duration: 4,
							})
						}
					},
					error:function (xhr, ajaxOptions, thrownError){
						$("body").overhang({
							type: "error",
							message: '<i class="fa fa-exclamation-triangle"></i>&nbsp;&nbsp;<?=$lng['Sorry but someting went wrong']?> <b><?=$lng['Error']?></b> : '+thrownError,
							duration: 8,
							closeConfirm: "true",
						})
					}
				});
			});
		
		});
	
		$(document).on('keyup', function (e) {

			$("#subButton").addClass('flash');
			$("#sAlert").fadeIn(200);
		});	

	</script>
						














