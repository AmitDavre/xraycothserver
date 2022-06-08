
<script type='text/javascript' src="../assets/js/croppie.js"></script>
<script type="text/javascript" src='../assets/js/autosize.min.js'></script>

<script type="text/javascript">
	
	var height = window.innerHeight-265;
	var headerCount = 1;
	
	function getAge(){	
		$.get( "../ajax/get_age.php", { date: $("#birthdate").val() }, function( data ){
			$("#emp_age").html(data);
		} );
	}
	function getServYears(){	
		$.get( "../ajax/get_age.php", { date: $("#startdate").val() }, function( data ){
			$("#serv_years").html(data);
			$("#per_serv_years").html(data);
		} );
	}
	function getProbation(){	
		$.get( "ajax/get_probation.php", { date: $("#startdate").val() }, function( data ){
			$("#probation_date").val(data);
		} );
	}
	
	$(document).ready(function() {
		
		var emp_id = <?=json_encode($_GET['id'])?>;
		var cid = <?=json_encode($cid)?>;
		var update = <?=json_encode($update)?>;
		var fix_allow = <?=json_encode($fix_allow)?>;
		var workdays = <?=json_encode($workdays)?>;
		var dayhours = <?=json_encode($dayhours)?>;
		//alert(update)
		
		$('.delDoc').confirmation({
			container: 'body',
			rootSelector: '.delDoc',
			singleton: true,
			animated: 'fade',
			placement: 'left',
			popout: true,
			html: true,
			title: '<?=$lng['Are you sure']?>',
			btnOkClass: 'btn btn-danger',
			btnOkLabel: '<?=$lng['Delete']?>',
			btnCancelClass: 'btn btn-success',
			btnCancelLabel: '<?=$lng['Cancel']?>',
			onConfirm: function() { 
				$.ajax({
					url: "ajax/delete_document.php",
					data:{emp_id: emp_id, doc: $(this).data('id')},
					success: function(result){
						//$('#dump').html(result); return false;
						location.reload();
					}
				});
			}
		});
	
	// PERSONAL FORM /////////////////////////////////////////////////////////////////////////////
		$('input, textarea').on('keyup', function(e){
			$('#sAlert').fadeIn(200);
			$("#submitBtn").addClass('flash');
		})
		$('select').on('change', function(e){
			$('#sAlert').fadeIn(200);
			$("#submitBtn").addClass('flash');
		})
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

		$('input[name="firstname"],input[name="lastname"]').on('change', function(){
			$('input[name="bank_account_name"]').val($('input[name="firstname"]').val()+' '+$('input[name="lastname"]').val());
		})

		$(".calcRate").on('change', function(){
			var wage = parseFloat($('#base_salary').val());
			$.each(fix_allow, function(i, v){
				if(v.rate == 'Y'){
					wage += parseFloat($('input[name="fix_allow_'+i+'"]').val());
				}
			})
			//alert(wage);
			if($('#wage_type').val() == 'day'){
				var day_rate = parseInt(wage);
				var hour_rate = (parseInt(wage) / parseInt(dayhours));
			}else{
				var day_rate = (parseInt(wage) / parseInt(workdays));
				var hour_rate = (parseInt(wage) / parseInt(workdays) / parseInt(dayhours));
			}
			//alert(day_rate)
			//alert(hour_rate)
			$('input[name="day_rate"]').val(day_rate)
			$('input[name="hour_rate"]').val(hour_rate)
			$('#day_rate').val(parseFloat(day_rate).format(2))
			$('#hour_rate').val(parseFloat(hour_rate).format(2))
			
		})

	// TAX FORM /////////////////////////////////////////////////////////////////////////////
		$("#maxrmf").on('click', function(){
			$("#tax_RMF").val(parseInt(($("#base_salary").val()*12) * 0.15));
		});
		$("#maxltf").on('click', function(){
			$("#tax_LTF").val(parseInt(($("#base_salary").val()*12) * 0.15));
		});

	// DOCUMENTS ///////////////////////////////////////////////////////////////////////////////
		$("#att_idcard").change(function(){
			readAttURL(this,'#idcard_name');
			$('#sAlert').fadeIn(200);
			$("#submitBtn").addClass('flash');
		});
		$("#att_housebook").change(function(){
			readAttURL(this,'#housebook_name');
			$('#sAlert').fadeIn(200);
			$("#submitBtn").addClass('flash');
		});
		$("#att_bankbook").change(function(){
			readAttURL(this,'#bankbook_name');
			$('#sAlert').fadeIn(200);
			$("#submitBtn").addClass('flash');
		});
		$("#att_contract").change(function(){
			readAttURL(this,'#contract_name');
			$('#sAlert').fadeIn(200);
			$("#submitBtn").addClass('flash');
		});
		$("#att_employment").change(function(){
			readAttURL(this,'#employment_name');
			$('#sAlert').fadeIn(200);
			$("#submitBtn").addClass('flash');
		});
		$("#attach1").change(function(){
			readAttURL(this,'#attach1_name');
			$('#sAlert').fadeIn(200);
			$("#submitBtn").addClass('flash');
		});
		$("#attach2").change(function(){
			readAttURL(this,'#attach2_name');
			$('#sAlert').fadeIn(200);
			$("#submitBtn").addClass('flash');
		});
		
		$("#emp_id").on('change', function(e){
			$.ajax({
				url: "ajax/check_employee_id.php",
				data: {emp_id: this.value},
				success: function(data){
					if(data == 1){
						$("#emp_id").focus().select();
						$("body").overhang({
							type: "error",
							message: '<i class="fa fa-exclamation-triangle"></i>&nbsp;&nbsp;<?=$lng['ID exist already']?>',
							duration: 4,
							//closeConfirm: true
						})
					}
				}
			});
		})
		
		$("#submitBtn").on('click', function(e){ // SUBMIT EMPLOYEE FORM & PICTURE ///////////////////////////////////
			e.preventDefault();
			var tab = $('.tab-content .active').attr('id');
			if(tab == 'tab_attachments'){
				var data = new FormData($('#attachForm')[0]);
				$.ajax({
					url: "ajax/update_employees_attach.php",
					type: 'POST',
					data: data,
					async: false,
					cache: false,
					contentType: false,
					processData: false,
					success: function(result){
						//$('#dump').html(result); return false;
						$("#submitBtn").removeClass('flash');
						$("#sAlert").fadeOut(200);
						if(result == 'success'){
							$("body").overhang({
								type: "success",
								message: '<i class="fa fa-check"></i>&nbsp;&nbsp;Data updated successfuly',
								duration: 2,
							})
							setTimeout(function(){location.reload();},1500);
						}else{
							$("body").overhang({
								type: "error",
								message: '<i class="fa fa-exclamation-triangle"></i>&nbsp;&nbsp;<?=$lng['Error']?> : '+result,
								duration: 4,
								//closeConfirm: true
							})
						}
						setTimeout(function(){$("#submitBtn i").removeClass('fa-refresh fa-spin').addClass('fa-save');},500);
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
				return false;
			}
			
			var err = 0;
			if($("#emp_id").val() == ''){err = 1;}
			if($('select[name="title"]').val() == null){err = 1;}
			if($('input[name="firstname"]').val() == ''){err = 1;}
			if($('input[name="lastname"]').val() == ''){err = 1;}
			if(err){
				$("body").overhang({
					type: "error",
					message: '<i class="fa fa-exclamation-triangle"></i>&nbsp;&nbsp;<?=$lng['Please fill in required fields']?>',
					duration: 4,
					//closeConfirm: true
				})
				return false;
			}
			
			$("#submitBtn i").removeClass('fa-save').addClass('fa-repeat fa-spin');
			var formData = $('#personalForm, #workForm, #benefitsForm, #taxForm, #resignForm').serialize();
			var file = $('#selectUserImg')[0].files[0];
			
			if(file){
				var fdata = new FormData();
				fdata.append('emp_id', $("#emp_id").val()); 
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
							fdata.append('img_data', resp); 
							$.ajax({
								url: "ajax/update_employee_picture.php",
								type: "POST", 
								data: fdata,
								cache: false,
								processData:false,
								contentType: false,
								success: function(result){
									//$("#dump2").html(result);
								}
							});				
						});
					}
				}
			}
			//return false;
			$.ajax({
				url: "ajax/update_employees_data.php",
				type: "POST", 
				data: formData,
				success: function(result){
					//$("#dump2").html(result); return false;
					if(result == 'exist'){
						$("#emp_id").focus().select();
						$("body").overhang({
							type: "error",
							message: '<i class="fa fa-exclamation-triangle"></i>&nbsp;&nbsp;<?=$lng['ID exist already']?>',
							duration: 4,
							//closeConfirm: true
						})
						return false;
					}
					if(result == 'success'){
						$("body").overhang({
							type: "success",
							message: '<i class="fa fa-check"></i>&nbsp;&nbsp;<?=$lng['Employee updated successfuly']?>',
							duration: 2,
						})
						setTimeout(function(){
							location.reload();
							location.href = 'index.php?mn=102&id='+$("#emp_id").val();
						},1000);
					}else{
						$("body").overhang({
							type: "error",
							message: '<i class="fa fa-exclamation-triangle"></i>&nbsp;&nbsp;<?=$lng['Error']?> : ' + result,
							duration: 4,
							//closeConfirm: true
						})
					}
					setTimeout(function(){
						$("#submitBtn i").removeClass('fa-repeat fa-spin').addClass('fa-save');
						$("#submitBtn").removeClass('flash');
						$('#sAlert').fadeOut(200);
					},500);
				},
				error:function (xhr, ajaxOptions, thrownError){
					$("body").overhang({
						type: "error",
						message: '<i class="fa fa-exclamation-circle"></i>&nbsp;&nbsp;<?=$lng['Sorry but someting went wrong']?> <b><?=$lng['Error']?></b> : ' + thrownError,
						duration: 4,
					})
					setTimeout(function(){
						$("#submitBtn i").removeClass('fa-repeat fa-spin').addClass('fa-save');
						$("#submitBtn").removeClass('flash');
						$('#sAlert').fadeOut(200);
					},500);
				}
			});
		
		});	
		
	 	$(".notnull").on('blur', function(){
			var str = $(this).val();
			if(str.trim() == ''){$(this).val(0);}
		});
		$("#birthdate").on('change', function(){
			getAge();
		});
		$("#startdate").on('change', function(){
			getServYears()
			getProbation()
		});
		getAge();
		getServYears()
		
		/*function readFileURL(input, id, name) {
		  if (input.files && input.files[0]) {
				var reader = new FileReader();
				reader.onload = function (e) {
					var fileExtension = ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'txt', 'jpg', 'jpeg', 'png', 'gif'];
					var ext = input.files[0].name.split('.').pop();
					if ($.inArray(ext.toLowerCase(), fileExtension) == -1) {
						$("body").overhang({
							type: "error",
							message: '<i class="fa fa-exclamation-triangle"></i>&nbsp;&nbsp;<?=$lng['Please use only xxx files']?>',
							duration: 5,
							closeConfirm: true
						})
						input.type = '';
						input.type = 'file';
						return false
					}else{
						$(id).append('<input style="margin:0px 0 5px 0" name="'+name+'[]" class="'+name+'" type="file" />');				
					}
				}
				reader.readAsDataURL(input.files[0]);
		  }
		};*/
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
							//closeConfirm: true
						})
						$(id).html('<?=$lng['No file selected']?>');
					}else{				
						$(id).html(input.files[0].name);
					}
				}
				reader.readAsDataURL(input.files[0]);
		  }
		};
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
							duration: 5,
							//closeConfirm: true
						})
						return false;
					}
					img.src = e.target.result;
					$('#orig').attr('src', e.target.result);
					$uploadCrop.croppie('bind', {
						url: e.target.result
					});
					$('.upload-demo').addClass('ready');
				}
				reader.readAsDataURL(input.files[0]);
			}
		};
		
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
		
		$('select[name="wage_type"]').on('change', function (e) {
			if(this.value == 'day'){
				$('#wageType').html('<?=$lng['Daily wage']?>');
			}else{
				$('#wageType').html('<?=$lng['Basic salary']?>');
			} 
		});
		
		var activeTabEmp = localStorage.getItem('activeTabEmp');
		if(activeTabEmp){
			$('.nav-link[href="' + activeTabEmp + '"]').tab('show');
		}else{
			$('.nav-link[href="#tab_personal"]').tab('show');
		}
		$('a[data-toggle="tab"]').on('shown.bs.tab', function(e) {
			localStorage.setItem('activeTabEmp', $(e.target).attr('href'));
			if($(e.target).data('target') == '#tab_taxinfo'){
				$("#warning").fadeIn(200);
			}else{
				$("#warning").fadeOut(200);
			}
			//autosize.destroy(document.querySelectorAll('textarea'));
			//autosize(document.querySelectorAll('textarea'));
		});
		
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
		/*$("form").bind("keypress", function(e) {
			 if (e.keyCode == 13) {
				  //$("#btnSearch").attr('value');
				  //add more buttons here
				  return false;
			 }
		});*/
		//autosize.destroy(document.querySelectorAll('textarea'));
		autosize(document.querySelectorAll('textarea'));

	}) // END DOCUMENT READY ///////////////////////////////////////////////////////////////////////////
	

</script>

