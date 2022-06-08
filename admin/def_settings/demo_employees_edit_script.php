
<script type='text/javascript' src="../assets/js/croppie.js"></script>
<script type="text/javascript" src='../assets/js/autosize.min.js'></script>

<script type="text/javascript">
	
	var height = window.innerHeight-265;
	var headerCount = 1;
	
	function getAge(){	
		$.get( "def_settings/ajax/get_age.php", { date: $("#birthdate").val() }, function( data ){
			$("#emp_age").html(data);
		} );
	}
	function getServYears(){	
		$.get( "def_settings/ajax/get_age.php", { date: $("#startdate").val() }, function( data ){
			$("#serv_years").html(data);
			$("#per_serv_years").html(data);
		} );
	}
	
	$(document).ready(function() {
		
		var emp_id = <?=json_encode($_GET['id'])?>;
		var cid = <?=json_encode($cid)?>;
		var update = <?=json_encode($update)?>;
		var fix_allow = <?=json_encode($fix_allow)?>;
		var workdays = <?=json_encode($workdays)?>;
		var dayhours = <?=json_encode($dayhours)?>;
		
	// PERSONAL FORM /////////////////////////////////////////////////////////////////////////////
		$('#employeeForm input, #employeeForm textarea').on('keyup', function(e){
			$('#sAlert').fadeIn(200);
			$("#submitBtn").addClass('flash');
		})
		$('#employeeForm select').on('change', function(e){
			$('#sAlert').fadeIn(200);
			$("#submitBtn").addClass('flash');
		})
		$('#employeeForm .date_year').datepicker({
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
		
		$('#employeeForm .datepick').datepicker({
			format: "dd-mm-yyyy",
			autoclose: true,
			inline: true,
			language: lang,//lang+'-th',
			todayHighlight: true,
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
			/*$.each(fix_allow, function(i, v){
				if(v.rate == 'Y'){
					wage += parseFloat($('input[name="fix_allow_'+i+'"]').val());
				}
			})*/
			//alert(dayhours);
			if($('#contract_type').val() == 'day'){
				var day_rate = parseInt(wage);
				var hour_rate = parseInt(wage) / parseInt(dayhours);
			}else{
				var day_rate = parseInt(wage) / parseInt(workdays);
				var hour_rate = (parseInt(wage) / parseInt(workdays)) / parseInt(dayhours);
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

		$("#employeeForm").on('submit', function(e){
			e.preventDefault();
			// CHECK TITLE, FIRSTNAME AND LASTNAME ////////////////////////////////////////////////////////////////
			var err = 0;
			if($("#emp_id").val() == ''){err = 1;}
			if($('select[name="title"]').val() == null){err = 1;}
			if($('input[name="firstname"]').val() == ''){err = 1;}
			if($('input[name="lastname"]').val() == ''){err = 1;}
			if(err){
				$("body").overhang({
					type: "warn",
					message: '<i class="fa fa-exclamation-triangle"></i>&nbsp;&nbsp;<?=$lng['Please fill in required fields']?>',
					duration: 4,
					closeConfirm: true
				})
				return false;
			}
			//return false;
			
			$("#submitBtn i").removeClass('fa-save').addClass('fa-repeat fa-spin');
			var formData = new FormData(this);
			var file = $('#selectUserImg')[0].files[0];
			if(!file){
				$.ajax({
					url: "def_settings/ajax/update_demo_employees.php",
					type: "POST", 
					data: formData,
					//dataType: 'json',
					cache: false,
					processData:false,
					contentType: false,
					success: function(result){
						//$("#dump").html(result); return false;
						if(result == 'success'){
							$("body").overhang({
								type: "success",
								message: '<i class="fa fa-check"></i>&nbsp;&nbsp;<?=$lng['Employee updated successfuly']?>',
								duration: 2,
							})
							setTimeout(function(){
								//location.reload();
								//location.href = 'index.php?mn=102&id='+$("#emp_id").val();
							},2000);
						}else{
							$("body").overhang({
								type: "warn",
								message: '<i class="fa fa-exclamation-triangle"></i>&nbsp;&nbsp;<?=$lng['Error']?> : ' + result,
								duration: 4,
								closeConfirm: true
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
				return false;
			}
			//setTimeout(function(){
				var reader = new FileReader();
				reader.readAsDataURL(file);
				reader.onload = function (e) {
					var img = new Image;
					img.src = e.target.result;
					img.onload = function() {
						$uploadCrop.croppie('result', {
							type: 'canvas',
							size: 'viewport',
							//format: 'jpeg',
							//quality: 0.5
						}).then(function (resp) {
							//formData += '&img_data='+resp; 
							formData.append('img_data', resp); 
							//$("#dump").html(resp); return false;
							$.ajax({
								url: AROOT+"def_settings/ajax/update_demo_employees.php",
								type: "POST", 
								data: formData,
								cache: false,
								processData:false,
								contentType: false,
								success: function(result){
									//$("#dump").html(data.result); return false;
									if(result == 'success'){
										$("body").overhang({
											type: "success",
											message: '<i class="fa fa-check"></i>&nbsp;&nbsp;<?=$lng['Employee updated successfuly']?>',
											duration: 2,
										})
										setTimeout(function(){
											//location.reload();
											//location.href = 'index.php?mn=102&id='+$("#emp_id").val();
										},2000);
									}else{
										$("body").overhang({
											type: "warn",
											message: '<i class="fa fa-exclamation-triangle"></i>&nbsp;&nbsp;<?=$lng['Error']?> : ' + result,
											duration: 4,
											closeConfirm: true
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
										message: '<i class="fa fa-exclamation-triangle"></i>&nbsp;&nbsp;<?=$lng['Sorry but someting went wrong']?> <b><?=$lng['Error']?></b> : ' + thrownError,
										duration: 8,
										closeConfirm: "true",
									})
									setTimeout(function(){
										$("#submitBtn i").removeClass('fa-repeat fa-spin').addClass('fa-save');
										$("#submitBtn").removeClass('flash');
										$('#sAlert').fadeOut(200);
									},500);
								}
							});				
						});
					};
				}
			//},100);
			return false;
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
							type: "warn",
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
		  if (input.files && input.files[0]) {
				var reader = new FileReader();
				reader.onload = function (e) {
					var fileExtension = ['pdf', 'doc', 'docx', 'xls', 'xlsx'];
					var ext = input.files[0].name.split('.').pop();
					if ($.inArray(ext.toLowerCase(), fileExtension) == -1) {
						$("body").overhang({
							type: "warn",
							message: '<i class="fa fa-exclamation-triangle"></i>&nbsp;&nbsp;<?=$lng['Please use only sss files']?>',
							duration: 5,
							closeConfirm: true
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
							type: "warn",
							message: '<i class="fa fa-exclamation-triangle"></i>&nbsp;&nbsp;'+msg,
							duration: 5,
							closeConfirm: true
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
		
		
		$('a[data-toggle="tab"]').on('shown.bs.tab', function(e) {
			localStorage.setItem('activeTab01', $(e.target).data('target'));
		});
		var activeTab = localStorage.getItem('activeTab01');
		if(activeTab && update == 1){
			$('#myTab a[data-target="' + activeTab + '"]').tab('show');
		}else{
			$('#myTab a[data-target="#tab_personal"]').tab('show');
		}
		
		/*$('a[data-toggle="tab"]').on('shown.bs.tab', function(e) {
			localStorage.setItem('activeTabEmp', $(e.target).attr('href'));
		});
		var activeTabEmp = localStorage.getItem('activeTabEmp');
		if(activeTabEmp){
			$('.nav-link[href="' + activeTabEmp + '"]').tab('show');
		}else{
			$('.nav-link[href="#tab_personal"]').tab('show');
		}*/

		//autosize.destroy(document.querySelectorAll('textarea'));
		//autosize(document.querySelectorAll('textarea'));

	}) // END DOCUMENT READY ///////////////////////////////////////////////////////////////////////////
	

</script>

