<script type="text/javascript">
	var testing = 0 ;
	var height = window.innerHeight-303;
	var headerCount = 1;
	var payment_term = 0;
	var rows = Math.floor(height/29.64);
	var blocks = <?=json_encode($blocks)?>;
	var blockslist = <?=json_encode($blockslist)?>;
	
	//var jsonProducts = <?=json_encode($jsonProducts)?>;
	//var jsonCustomers = <?=json_encode($jsonCustomers)?>;
	//var emailTemplates = <?=json_encode($emailTemplates)?>;
	//var quote_id = <?=json_encode($quote_id)?>;
	//var update = <?=json_encode($update)?>;
	//var textBlocks = <?=json_encode($blocks)?>;


	var dataHeader = <?=json_encode($dataHeader)?>;
	var dataFooter = <?=json_encode($dataFooter)?>;
	var ccid = <?=$ccid;?>;


	$(document).ready(function() {

		//$('#btnPrint a').tooltip('show');

		if(ccid == ''){
			ModalParameters();
		}

		$(document).on('click', "#btnSend", function(e){
			//$('#emailForm input[name="recipients"]').val(contactEmails)
			$('#mMsg').html('').hide()
			$('#modalEmail').modal('toggle')
		})

		$(document).on('click', "#btnPrint", function(e){
			var id = <?=$ccid;?>;
			window.open('print/print_pdf.php?id='+id,'_blank')
		})

    	function nl2br (str, is_xhtml) {   
		    var breakTag = (is_xhtml || typeof is_xhtml === 'undefined') ? '<br />' : '<br>';    
		    return (str + '').replace(/([^>\r\n]?)(\r\n|\n\r|\r|\n)/g, '$1'+ breakTag +'$2');
		}

    	$(document).on('click', "#btnView", function(e){
			$('#quoteForm .row').slideToggle(200);
			$('#quoteForm .note-toolbar').slideToggle(200);
			$('#quoteForm .remBlock').slideToggle(200);
		})

		$(document).on('change', "#headerOpt", function() {
			var id = this.value;

			var addRow = '<tr id="headerSec" style="border-bottom:1px solid #ddd;">'+
								'<td style="vertical-align:top; max-height:100px; padding-top:10px">'+
									'<img style="display:block; max-height:60px" src="'+dataHeader[id]['logo']+'" />'+
								'</td>'+
								'<td style="vertical-align:top; text-align:right; padding-bottom:8px">'+
									'<span style="font-size:16px; font-weight:600">'+dataHeader[id]['company']+'</span><br>'+
									nl2br(dataHeader[id]['address'])+
								'</td>'+
							'<tr>';
			$('#HeaderData tr').remove();
			$('#HeaderData').append(addRow);
			$('#cHeader').attr('checked',true);
		})

		$(document).on('change', "#footerOpt", function() {
			var id = this.value;
			var addRow = '<p id="fsec"><b>Phone : </b>'+dataFooter[id]['phone']+' <b> &nbsp;•&nbsp; </b><b>Fax : </b>'+dataFooter[id]['fax']+' <b> &nbsp;•&nbsp; </b><b>eMail : </b>'+dataFooter[id]['email']+' <b> &nbsp;•&nbsp; </b><b>Website : </b>'+dataFooter[id]['website']+' <b> &nbsp;•&nbsp; </b><b>VAT : </b>'+dataFooter[id]['vat']+' <br><b>'+dataFooter[id]['bank1']+' : </b>'+dataFooter[id]['acc1']+'<b> - Swift/Bic : </b>'+dataFooter[id]['bic1']+' <b> &nbsp;•&nbsp; </b><b>'+dataFooter[id]['bank2']+' : </b>'+dataFooter[id]['acc2']+'<b> - Swift/Bic : </b>'+dataFooter[id]['bic2']+'</p>';
			$('#footerSec p').remove();
			$('#footerSec').append(addRow);
			$('#cFooter').attr('checked',true);
		})

		var summerOptions = {
			placeholder: 'Start typing ...',
			//tooltip: false,
			maximumImageFileSize: 50000,
			//height: targetOffset,
			disableResizeEditor: true,
			toolbar: [
					['cleaner',['cleaner']],
					['style', ['style', 'bold', 'italic', 'underline', 'cleaner']],
					['fontsize', ['fontsize']],
					['fontname', ['fontname']],
					['color', ['color']],
					['para', ['ul', 'ol', 'paragraph']],
					['insert', ['link', 'picture']],
					['misc', ['undo', 'redo']],
					['view', ['fullscreen', 'codeview']],
				],
			callbacks: {
                    onImageUpload: function (data) {
                        data.pop();
                    }
                }
		}


		/*var summerOptions = {
			placeholder: 'Start typing ...',
			tooltip: false,
			//disableResizeEditor: true
			//dialogsInBody: true,
			maximumImageFileSize: 50000,
		}*/

		$('.summernote').summernote(summerOptions);


		


		$('.summernote').each(function(e){
			$(this).summernote('disable');
		});
		
		var allsel = [];
		$(document).on('change', "#textBlock", function() {

			var id = this.value; 

			var text = id;
			var netxt = text.replace(" ", "");

			var addRow = '<tr>'+
					'<td style="padding-bottom:10px; position:relative"><input type="hidden" id="'+netxt+'" name="sectionArr[]" value="'+id+'"><textarea name="areas[]" class="summernote">'+ blocks[id] + '</textarea><div class="remBlock" data-id="'+netxt+'"><a><i class="fa fa-times-circle fa-lg"></i></a></div></td>'+
					'</tr>';
			$('#summerTable').append(addRow)
			$('.summernote').summernote(summerOptions);
			$(this).val('')
			$('#cSection').attr('checked',true);			
		})

		$(document).on('click', "#cHeader", function(e){
			if($(this).is(':checked')){
				$('#headerSec').slideDown(200)
			}else{
				$('#headerSec').slideUp(200)
			}
		})

		$(document).on('click', "#cSection", function(e){
			if($(this).is(':checked')){
				$('#summerTable').slideDown(200)
			}else{
				$('#summerTable').slideUp(200)
			}
		})

		$(document).on('click', "#cFooter", function(e){
			if($(this).is(':checked')){
				$('#fsec').slideDown(200)
			}else{
				$('#fsec').slideUp(200)
			}
		})


		$(document).on('click', ".remBlock", function(e) {
			var rowreqq = $(this).data('id'); 
			//alert(rowreqq);
			$('#quoteForm #summerTable tbody input#'+rowreqq).remove();
			//$('#quoteForm #summerTable tbody input#dddddd').val('');
			$(this).closest('tr').remove();
		})
		
		function clearForm(){
			//$('#rightTable').fadeOut(200);
			$('#sDraft').removeClass('active')
			$('#sOpen').removeClass('active')
			$('#sPaid').removeClass('active')
			
			$('#quoteForm fieldset').prop('disabled', true)
			$('#quoteForm').trigger('reset')
			$('input[name="status"]').val(0)
			//$('#title').html('')
			$('#name').html('')
			$('#address').html('')			
			$('#quote_id').html('')			
			$("#itemTable tbody").html('')
			$('#summerTable tbody').html('')
			$('#addLine').trigger('click')
			calculate()	
			
			$('#btnEdit').hide()
			$('#btnUpdate').hide()
			$('#btnSave').hide()
			$('#btnSend').hide()
			$('#btnPrint').hide()
			$('#btnOrder').hide()
			$('#btnCancel').hide()

			$("#eMsg").html('').hide();
			$("#mMsg").html('').hide();
			//$('.basicTable').addClass('editable')
		}
		

		$("#btnUpdate, #btnSave").on('click', function(e){ 
			$("#quoteForm").submit()
		})

		$("#quoteForm").submit(function(e){ 
			e.preventDefault();
			
			var data = $(this).serialize();
			$.ajax({
				//type: 'post',
				url:  "ajax/save_comm_center.php",
				data: data,
				success: function(result){				
					
					if(result == 'success'){
						$("body").overhang({
							type: "success",
							message: '<i class="fa fa-check"></i>&nbsp;&nbsp;<?=$lng['Data updated successfully']?>',
							duration: 2,
							callback: function(value){

								var ccid = <?=$ccid?>;
								if(ccid > 0){
									//$('#btnPrint').click(); //generate pdf
									window.open('print/print_pdf.php?id='+ccid);
									window.location.reload();
								}else{
									window.location.href = 'index.php?mn=801';
								}
							}
						})
					}else{
						$("body").overhang({
							type: "error",
							message: '<i class="fa fa-exclamation-triangle"></i>&nbsp;&nbsp;<?=$lng['Error']?>: '+result,
							duration: 3,
						})
					}
				}
			});
		});
		

		if(ccid > 0){ getCCdata(ccid); } //calling function
		function getCCdata(id) {

			$.ajax({
				url: "ajax/get_cc_data.php",
				data: {id: id},
				dataType: "json",
				success: function(data){

					if(data.id !== 0){

						

						$('input[name="id"]').val(data.id);

						var newarr = []
						var addRow1 = '';
						$.each(data.sectionVal, function (key, val) { 
							var text = blockslist[key];
							var netxt = text.replace(" ", "");
							addRow1 += '<input id="'+netxt+'" type="hidden" name="sectionArr[]" value="'+blockslist[val]+'">';
							newarr.push(blockslist[val]);
						})

						//console.log(newarr);
						//$('#quoteForm #summerTable tbody').append(addRow1);
						//--------------- header start ----------------//
						if(data.settings.header == 1){

							var id = data.headerval;
							var addRow = '<tr id="headerSec" style="border-bottom:1px solid #ddd;">'+
								'<td style="vertical-align:top; max-height:100px; padding-top:10px">'+
									'<img style="display:block; max-height:60px" src="'+dataHeader[id]['logo']+'" />'+
								'</td>'+
								'<td style="vertical-align:top; text-align:right; padding-bottom:8px">'+
									'<span style="font-size:16px; font-weight:600">'+dataHeader[id]['company']+'</span><br>'+
									nl2br(dataHeader[id]['address'])+
								'</td>'+
							'<tr>';
							$('#HeaderData tr').remove();
							$('#HeaderData').append(addRow);
							$('#cHeader').attr('checked',true);

						}else{
							$('#cHeader').attr('checked',false);
						}

						$('select#headerOpt option[value="'+data.headerval+'"]').attr('selected',true);
						//--------------- header end ----------------//
						//console.log(blockslist);
						//console.log(data.sectionVal);
						//--------------- section start ----------------//
						if(data.settings.section == 1){
							var addRow = '';
							var keyCount = 0;
							$.each(data.areas, function (key, val) {
								keyCount++;
								var text = newarr[key];
								//var netxt = text.replace(" ", "");
								addRow += 
									'<tr id="'+keyCount+'"><td style="padding-bottom:10px; position:relative"><textarea name="areas['+key+']" class="summernote" id="summernote'+keyCount+'">'+val+'</textarea><div class="remBlock" data-id="'+keyCount+'"><a><i class="fa fa-times-circle fa-lg"></i></a></div></td></tr>';
							})
							$('#summerTable tbody').append(addRow);
							$('.summernote').summernote(summerOptions);
							$('.summernote').each(function(e){
								//$(this).summernote('disable');
							});

							$('#cSection').attr('checked',true);
						}
						//--------------- section end ----------------//

						//--------------- footer start ----------------//
						if(data.settings.footer == 1){
							var id = data.footerval;
							var addRow = '<p id="fsec"><b>Phone : </b>'+dataFooter[id]['phone']+' <b> &nbsp;•&nbsp; </b><b>Fax : </b>'+dataFooter[id]['fax']+' <b> &nbsp;•&nbsp; </b><b>eMail : </b>'+dataFooter[id]['email']+' <b> &nbsp;•&nbsp; </b><b>Website : </b>'+dataFooter[id]['website']+' <b> &nbsp;•&nbsp; </b><b>VAT : </b>'+dataFooter[id]['vat']+' <br><b>'+dataFooter[id]['bank1']+' : </b>'+dataFooter[id]['acc1']+'<b> - Swift/Bic : </b>'+dataFooter[id]['bic1']+' <b> &nbsp;•&nbsp; </b><b>'+dataFooter[id]['bank2']+' : </b>'+dataFooter[id]['acc2']+'<b> - Swift/Bic : </b>'+dataFooter[id]['bic2']+'</p>';
							$('#footerSec p').remove();
							$('#footerSec').append(addRow);
							$('#cFooter').attr('checked',true);
						}else{
							$('#cFooter').attr('checked',false);
						}

						$('select#footerOpt option[value="'+data.footerval+'"]').attr('selected',true);
						//--------------- footer end ----------------//

					}
				}
			})
		}

		$('#parForm').on('click', function (e) { 
			$('#parameterForm').submit();
		});

		$('#parameterForm').on('submit', function (e) { 
			e.preventDefault();

			var formData = new FormData($('#parameterForm')[0]);
			$.ajax({
				type : 'POST',
				url: "ajax/save_parameters.php",
				data: formData,
				//dataType: 'json',
				cache: false,
				contentType: false,
				processData: false,
				success: function(result){

					var data = JSON.parse(result);

					//alert(data.lastid);

					if($.trim(data.msg) == 'success'){
						$("body").overhang({
							type: "success",
							message: '<i class="fa fa-check"></i>&nbsp;&nbsp;<?=$lng['Data updated successfully']?>',
							duration: 2,
							callback: function(value){
								window.location.href = 'index.php?mn=802&ccid='+data.lastid;
							}
						})
					}else{
						$("body").overhang({
							type: "error",
							message: '<i class="fa fa-exclamation-triangle"></i>&nbsp;&nbsp;<?=$lng['Error']?>: '+data.msg,
							duration: 3,
						})
					}
				}
			})
		})



		$('#attachBtn').on('click', function (e) { 
			$('#attachimgs').submit();
		});

		$('#attachimgs').on('submit', function (e) { 
			e.preventDefault();
			
			//var data = $(this).serialize();
			var formData = new FormData($('#attachimgs')[0]);
			
			$.ajax({
				type : 'post',
				url: "ajax/save_attachment.php",
				data: formData,
				//dataType: 'json',
				cache: false,
				contentType: false,
				processData: false,
				success: function(result){	

					if(result == 'success'){
						$("body").overhang({
							type: "success",
							message: '<i class="fa fa-check"></i>&nbsp;&nbsp;<?=$lng['Data updated successfully']?>',
							duration: 2,
							callback: function(value){

								var ccid = <?=$ccid?>;
								if(ccid > 0){
									location.reload();
								}else{
									window.location.href = 'index.php?mn=801';
								}
							}
						})
					}else{
						$("body").overhang({
							type: "error",
							message: '<i class="fa fa-exclamation-triangle"></i>&nbsp;&nbsp;<?=$lng['Error']?>: '+result,
							duration: 3,
						})
					}
				}
			})
			
		});

	
		$('#rightTable').fadeIn(200);
		
	})
	
	function ReviewAndSubmit(){

		var hideeshow = '<?=$attach['approval']?>';
		if(hideeshow == 1){
			$('tr.showhide').css('display','table-row');
		}else{
			var needApp = '<?=$_SESSION['rego']['comm_center']['need_approver']?>';
			if(needApp == 1){
				$('tr.showhide').css('display','table-row');
			}else{
				$('tr.showhide').css('display','none');
			}
		}
	}
	
	function showhidefn(that){
		//alert('fdf');
		if($(that).is(':checked')){
			$('tr.showhide').css('display','table-row');
			$('button#btnrequest').attr('disabled',false);
			$('input[name="no_app_req"]').val('1');
			
		}else{
			$('tr.showhide').css('display','none');
			$('input[name="no_app_req"]').val('0');
			$('button#btnrequest').attr('disabled',true);
		}

		var hideeshow = '<?=$attach['approval']?>';
		if(hideeshow == 1){
			$('tr.showhide').css('display','table-row');
		}
	}


	function RemoveAttach(that,key){

		var id = that.id;
		var attch = $('.delattch_'+key).data('value');

		$.ajax({
				type : 'post',
				url: "ajax/remove_attachment.php",
				data: {id: id, attch: attch},
				success: function(result){	

					if(result == 'success'){
						$("body").overhang({
							type: "success",
							message: '<i class="fa fa-check"></i>&nbsp;&nbsp;Data remove successfully',
							duration: 2,
							callback: function(value){

								var ccid = <?=$ccid?>;
								if(ccid > 0){
									location.reload();
								}else{
									window.location.href = 'index.php?mn=801';
								}
							}
						})
					}else{
						$("body").overhang({
							type: "error",
							message: '<i class="fa fa-exclamation-triangle"></i>&nbsp;&nbsp;<?=$lng['Error']?>: '+result,
							duration: 3,
						})
					}
				}
			})
	}

	function ModalParameters(){
		$('#modalParameters').modal('toggle');
	}

	function AddfieldtosummerNote(key,val,bid){

		var selval = $('input#'+val+key).val();
		//var blocksaa = <?=json_encode($blocksaa)?>;
		var blocksaa = $('#summernote'+key).summernote("code");

		var checkStr = val;
		var checkStr1 = checkStr.replace(/_/g," ");
		var checkStr2 = '{'+checkStr1+'}';

		var replacedStr = blocksaa;
		var Newstr = replacedStr.replaceAll(checkStr2,selval);

		$('#summernote'+key).summernote("code", Newstr);

		$("body").overhang({
			type: "success",
			message: '<i class="fa fa-check"></i>&nbsp;&nbsp;'+checkStr2+' added successfully',
			duration: 2,
		})
		
		// alert(selval);
		// $('#summernote'+key).summernote('editor.saveRange');
		// $('#summernote'+key).summernote('editor.restoreRange');
		// $('#summernote'+key).summernote('editor.focus');
	 	// $('#summernote'+key).summernote('editor.insertText', selval);
	}

	function SendandLockAnno(type){

		var ccid = <?=$ccid;?>;
		var submitStatus = $('select[name="sub_status"]').val();
		var publishdate = $('input[name="publish_date"]').val();

		$.ajax({
			type : 'post',
			url: "ajax/send_and_lock.php",
			data: {ccid: ccid, submitStatus: submitStatus, publishdate: publishdate},
			success: function(result){

				if(result == 'success'){
					$("body").overhang({
						type: "success",
						message: '<i class="fa fa-check"></i>&nbsp;&nbsp;Announcement sent successfully',
						duration: 2,
						callback: function(value){

							window.open('print/print_pdf.php?id='+ccid,'_blank');
							window.location.href = 'index.php?mn=801';
						}
					})
				}else{
					$("body").overhang({
						type: "error",
						message: '<i class="fa fa-exclamation-triangle"></i>&nbsp;&nbsp;<?=$lng['Error']?>: '+result,
						duration: 3,
					})
				}
			}
		})
	}


	function RequestAppresult(type){

		var ccid = <?=$ccid;?>;
		var remark_approver = $('textarea[name="remark_app"]').val();

		$.ajax({
				type : 'post',
				url: "ajax/request_approval_result.php",
				data: {ccid: ccid, results: type, remark_approver: remark_approver},
				success: function(result){

					if(type == 1){
						var reqaa = 'approved';
					}else{
						var reqaa = 'rejected';
					}

					if(result == 'success'){
						$("body").overhang({
							type: "success",
							message: '<i class="fa fa-check"></i>&nbsp;&nbsp;Request '+reqaa+' successfully',
							duration: 2,
							callback: function(value){
								location.reload();
							}
						})
					}else{
						$("body").overhang({
							type: "error",
							message: '<i class="fa fa-exclamation-triangle"></i>&nbsp;&nbsp;<?=$lng['Error']?>: '+result,
							duration: 3,
						})
					}
				}
			});
	}

	function SentrequestforApproval(){

		var approval = $('input[name="no_app_req"]').val();
		var approver = $('select#appval').val();
		var remark_approver = $('textarea[name="remark_app"]').val();
		var ccid = <?=$ccid;?>;

		// alert(approval);
		// alert(approver);
		// alert(remark_approver);

		if(approver == '' || approver == 'undefined' || approver == null){
			$("body").overhang({
				type: "error",
				message: '<i class="fa fa-exclamation-triangle"></i>&nbsp;&nbsp;Approver not selected',
				duration: 3,
			})

		}else{

			$.ajax({
				type : 'post',
				url: "ajax/request_approval.php",
				data: {approval: approval, approver: approver, remark_approver:remark_approver, ccid:ccid},
				success: function(result){	

					if(result == 'success'){
						$("body").overhang({
							type: "success",
							message: '<i class="fa fa-check"></i>&nbsp;&nbsp;Request sent successfully',
							duration: 2,
							callback: function(value){
								location.reload();
							}
						})
					}else{
						$("body").overhang({
							type: "error",
							message: '<i class="fa fa-exclamation-triangle"></i>&nbsp;&nbsp;<?=$lng['Error']?>: '+result,
							duration: 3,
						})
					}
				}
			})
		}
	}

	
</script>

