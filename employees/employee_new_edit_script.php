
<script type='text/javascript' src="../assets/js/croppie.js"></script>
<script type="text/javascript" src='../assets/js/autosize.min.js'></script>

<script>
	
	var height = window.innerHeight-265;
	
	function getAge(date){	
		$.get( "../ajax/get_age.php", {date: date}, function( data ){
			$("#emp_age").html(data);
		} );
	}
	function getServYears(date){	
		$.get( "../ajax/get_age.php", {date: date}, function( data ){
			$("#serv_years").html(data);
			$("#per_serv_years").html(data);
		} );
	}
	function getProbation(date){	
		$.get( "ajax/get_probation.php", {date: date}, function( data ){
			$("#probation_date").val(data);
		} );
	}
	function readFileURL(input, id) {
		if (input.files && input.files[0]) {
			var reader = new FileReader();
			reader.onload = function (e) {
				var fileExtension = ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'txt', 'jpg', 'jpeg', 'png', 'gif'];
				var ext = input.files[0].name.split('.').pop();
				if ($.inArray(ext.toLowerCase(), fileExtension) == -1) {
					$("body").overhang({
						type: "error",
						message: '<i class="fa fa-exclamation-triangle"></i>&nbsp;&nbsp;Only '+fileExtension.join(', ')+' formats are allowed !',
						duration: 4,
					})
					input.type = '';
					input.type = 'file';
					return false
				}else{
					$(id).append('<br><input style="margin:0 0 5px 0" class="attachBtn" name="attachment[]" type="file" />');				
				}
			}
			reader.readAsDataURL(input.files[0]);
		}
	};
	
	function readCertificateURL(input) {
		if (input.files && input.files[0]) {
			var reader = new FileReader();
			reader.onload = function (e) {
				var fileExtension = ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'txt', 'jpg', 'jpeg', 'png', 'gif'];
				var ext = input.files[0].name.split('.').pop();
				if ($.inArray(ext.toLowerCase(), fileExtension) == -1) {
					$("body").overhang({
						type: "warn",
						message: '<i class="fa fa-exclamation-triangle"></i>&nbsp;&nbsp;Only '+fileExtension.join(', ')+' formats are allowed !',
						duration: 5,
						closeConfirm: true
					})
					input.type = '';
					input.type = 'file';
					return false
				}else{
					$("#certificateBtn").html(input.files[0].name);			
				}
			}
			reader.readAsDataURL(input.files[0]);
		}
	};
	
	function readAttURL(input,id) {
		if(input.files && input.files[0]) {
			var reader = new FileReader();
			reader.onload = function (e) {
				var fileExtension = ['pdf', 'doc', 'docx', 'xls', 'xlsx'];
				var ext = input.files[0].name.split('.').pop();
				if ($.inArray(ext.toLowerCase(), fileExtension) == -1) {
					$("body").overhang({
						type: "error",
						message: '<i class="fa fa-exclamation-triangle"></i>&nbsp;&nbsp;<?=$lng['Please use only sss files']?>',
						duration: 4,
					})
					$(id).html('<?=$lng['No file selected']?>');
				}else{				
					$(id).html(input.files[0].name);
				}
			}
			reader.readAsDataURL(input.files[0]);
		}
	};
	
	$(document).ready(function() {
		
		var emp_id = <?=json_encode($_SESSION['rego']['empID'])?>;
		var cid = <?=json_encode($cid)?>;
		var update = <?=json_encode($update)?>;
		var joining_date = <?=json_encode($data['joining_date'])?>;
		
		function readFile(input) {
			if(input.files && input.files[0]) {
				var file = input.files[0];
				var reader = new FileReader();
				reader.onload = function (e) {
					var img = new Image;
					var t = file.type.split('/')[1];
					var n = file.name;
					var s = ~~(file.size/1024);
					var msg = "";
					if(s > maxSize){msg = '<?=$lng['Filesize is to bigg']?> ('+(s/1024).format(2)+' Mb) - Max. '+(maxSize/1000)+' Mb';}
					if(t != 'jpeg' && t != 'png' && t != 'jpg'){msg = "<?=$lng['Please use only ... files']?>";}
					if(msg!=''){
						$("body").overhang({
							type: "error",
							message: '<i class="fa fa-exclamation-triangle"></i>&nbsp;&nbsp;'+msg,
							duration: 4,
						})
						return false;
					}
					img.src = e.target.result;
					//alert(e.target.result)
					$('#orig').attr('src', e.target.result);
					$uploadCrop.croppie('bind', {
						url: e.target.result
					});
					$('.upload-demo').addClass('ready');
				}
				reader.readAsDataURL(input.files[0]);
			}else{
				swal("Sorry - you're browser doesn't support the FileReader API");
			}
		};
		
		$('.date_year').datepicker({
			format: "dd-mm-yyyy",
			autoclose: true,
			inline: true,
			language: lang,//lang+'-th',
			todayHighlight: true,
			startView: 'decade',
		}).on('changeDate', function(ev){
			$('#sAlert').fadeIn(200);
			$("#submitBtn").addClass('flash');
		});		
		
		$('.datepick').datepicker({
			format: "dd-mm-yyyy",
			autoclose: true,
			inline: false,
			language: lang,//lang+'-th',
			todayHighlight: true,
			orientation: "bottom left",
			//startView: 'decade',
		}).on('changeDate', function(ev){
			$('#sAlert').fadeIn(200);
			$("#submitBtn").addClass('flash');
		});	
		
	 	$(".notnull").on('blur', function(){
			var str = $(this).val();
			if(str.trim() == ''){$(this).val(0);}
		});
		$("#birthdate").on('change', function(){
			getAge(this.value);
		});
		$("#joining_date").on('change', function(){
			getServYears(this.value)
			getProbation(this.value)
		});
		getAge($("#birthdate").val());
		getServYears(joining_date)
		
		$('#personal_phone').on('keyup', function (e) {
			$("#per_phone").html(this.value);
		});
		$('#personal_email').on('keyup', function (e) {
			$("#per_email").html(this.value);
		});
		$('#startdate').on('change', function (e) {
			$("#per_startdate").html(this.value);
			$("#startdat").val(this.value);
		});
		
		maxSize = 2000;
		minHeight = 150;
		minWidth = 150;
		var $uploadCrop = $('#upload-demo').croppie({
			viewport: {
				width: 150,
				height: 150,
				type: 'square'
			},
			boundary: {
				width: 180,
				height: 180
			},
			mouseWheelZoom: true,
			showZoom: true
		});
		$('#selectUserImg').on('change', function () { 
			readFile(this);
		});
		
		$('#imageBtn').on('click', function () {
			$('#imageForm').submit();
		}) 
		$('#imageForm').on('submit', function (e) { 
			e.preventDefault();
			var file = $('#selectUserImg')[0].files[0];
			var formData = new FormData(this);
			if(file){
				$("#imageBtn i").removeClass('fa-save').addClass('fa-repeat fa-spin');
				var reader = new FileReader();
				reader.readAsDataURL(file);
				reader.onload = function (e) {
					var img = new Image;
					img.src = e.target.result;
					img.onload = function() {
						$uploadCrop.croppie('result', {
							type: 'canvas',
							size: 'viewport',
						}).then(function (resp) {
							formData.append('img_data', resp); 
							$.ajax({
								url: "ajax/update_emp_image.php",
								type: "POST", 
								data: formData,
								cache: false,
								processData:false,
								contentType: false,
								success: function(result){
									//$("#imgDump").html(result); return false;
									if(result == 'success'){
										$('#sAlert').fadeOut(200);
										$("body").overhang({
											type: "success",
											message: '<i class="fa fa-check"></i>&nbsp;&nbsp;<?=$lng['Data updated successfully']?>',
											duration: 2,
										})
									}else{
										$("body").overhang({
											type: "error",
											message: '<i class="fa fa-exclamation-triangle"></i>&nbsp;&nbsp;<?=$lng['Error']?> : '+result,
											duration: 4,
										})
									}
									setTimeout(function(){
										$("#imageBtn i").removeClass('fa-repeat fa-spin').addClass('fa-save');
										$("#imageBtn").removeClass('flash');
										$('#sAlert').fadeOut(200);
									},500);
								},
								error:function (xhr, ajaxOptions, thrownError){
									$("#submitbtn").removeClass('flash');
									$("body").overhang({
										type: "error",
										message: '<i class="fa fa-exclamation-triangle"></i>&nbsp;&nbsp;<?=$lng['Sorry but someting went wrong']?> <b><?=$lng['Error']?></b> : '+thrownError,
										duration: 4,
									})
									setTimeout(function(){
										$("#imageBtn i").removeClass('fa-repeat fa-spin').addClass('fa-save');
										$("#imageBtn").removeClass('flash');
										$('#sAlert').fadeOut(200);
									},500);
								}
							});				
						});
					};
				}
			}
		});
		/*$("form").bind("keypress", function(e) {
			 if (e.keyCode == 13) {
				  //$("#btnSearch").attr('value');
				  //add more buttons here
				  return false;
			 }
		});*/
		//autosize.destroy(document.querySelectorAll('textarea'));
		//autosize(document.querySelectorAll('textarea'));
		

	}) // END DOCUMENT READY ///////////////////////////////////////////////////////////////////////////
	

</script>

