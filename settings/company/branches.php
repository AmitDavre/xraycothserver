<?

	$getid = $_GET['id'];



	$disabled = 'disabled';
	
	$xentities = getEntities();
	$xbranches = getBranches();
		
	$entity_data['code'] = '';
	$entity_data['th_compname'] = '';
	$entity_data['en_compname'] = '';
	$entity_data['sso_account'] = '';
	$entity = 1;
	$sso_code = 1;
	$sso_codes = array();
	
	$data = array();
	if(isset($_GET['id'])){
		$sql = "SELECT entity FROM ".$cid."_branches WHERE id = ".$_GET['id'];
		if($res = $dbc->query($sql)){
			if($row = $res->fetch_assoc()){
				$entity = $row['entity'];
			}
		}
		//var_dump($entity);
		
		$sql = "SELECT * FROM ".$cid."_branches_data WHERE ref = ".$_GET['id'];
		if($res = $dbc->query($sql)){
			if($row = $res->fetch_assoc()){
				$data = $row;
				$sso_code = $row['sso_code'];
				$loc1 = unserialize($row['loc1']);
				$loc2 = unserialize($row['loc2']);
				$loc3 = unserialize($row['loc3']);
				$loc4 = unserialize($row['loc4']);
			}
		}
		//var_dump($data);

		
		$sql = "SELECT code, en_compname, th_compname, sso_codes, sso_account FROM ".$cid."_entities_data WHERE ref = ".$entity;
		if($res = $dbc->query($sql)){
			if($row = $res->fetch_assoc()){
				$entity_data = $row;
				$sso_codes = unserialize($row['sso_codes']);
			}
		}
		
		$disabled = '';
		//var_dump($sso_codes); exit;
		//var_dump($sso_codes); //exit;
	}
	
	
	if(!$sso_codes){
		$sso_codes[$entity]['code'] = '';
		$sso_codes[$entity]['th'] = '';
		$sso_codes[$entity]['en'] = '';
		$sso_codes[$entity]['line1_th'] = '';
		$sso_codes[$entity]['line2_th'] = '';
		$sso_codes[$entity]['postal_th'] = '';
		$sso_codes[$entity]['line1_en'] = '';
		$sso_codes[$entity]['line2_en'] = '';
		$sso_codes[$entity]['postal_en'] = '';
	}
	
	if(!$data){
		$_GET['id'] = 0;
		$data['ref'] = '';
		$data['bra_code'] = '';
		$data['bra_name_th'] = '';
		$data['bra_name_en'] = '';
		$data['bra_address_th'] = '';
		$data['bra_address_en'] = '';

		$data['scan_system'] = '';
		$data['loc_name'] = '';
		$data['loc_code'] = '';
		$data['loc_qr'] = '';
		$data['latitude'] = '';
		$data['longitude'] = '';
		$data['perimeter'] = '';
		$data['gps'] = 0;
	}
	
	
	//if(!$sso_codes){$sso_codes = array();}
	//var_dump($data); 
	//exit;
	//var_dump($xentities); exit;
	//var_dump($th_addr_detail); //exit;
	//var_dump($en_addr_detail); //exit;
	//var_dump($sso_codes); exit;

	// echo $_GET['id'];
	// die();

?>

<style>
	.redHover:hover {
		color:#c00;
	}
	table.basicTable td select {
		padding:3px 8px !important;
	}
</style>

	<h2 style="padding-right:60px"><i class="fa fa-cog fa-mr"></i> <?=$lng['Branches']?>
		<span style="display:none; font-style:italic; color:#b00; padding-left:30px" id="sAlert"><i class="fa fa-exclamation-triangle fa-mr"></i><?=$lng['Data is not updated to last changes made']?></span>
	</h2>		

	<div class="main">
		
			<div class="tab-content-left" style="width:530px">
				<form id="branchForm">
					<table class="basicTable inputs hoverable selectable" id="branchTable" border="0">
						<thead>
							<tr>
								<th style="width:16%"><?=$lng['Code']?></th>
								<th style="width:42%"><?=$lng['Branch']?> <?=$lng['Thai']?></th>
								<th style="width:42%"><?=$lng['Branch']?> <?=$lng['English']?></th>
								<th><?=$lng['Entity']?></th>
							</tr>
						</thead>
						<tbody>
						<? foreach($xbranches as $key=>$val){ ?>
							<tr <? if($_GET['id'] == $key){echo 'class="selected"';}?>>
								<td>
									<input class="redHover" onclick="window.location.href='index.php?mn=6012&id=<?=$key?>'" readonly style="font-weight:600; cursor:pointer" name="branches[<?=$key?>][code]" type="text" value="<?=$val['code']?>" />
								</td>
								<td><input class="braName" name="branches[<?=$key?>][th]" type="text" value="<?=$val['th']?>" /></td>
								<td><input class="braName" name="branches[<?=$key?>][en]" type="text" value="<?=$val['en']?>" /></td>
								<td style="padding:0">
									<select class="braSelect" style="width:auto; min-width:100%; background:transparent" name="branches[<?=$key?>][entity]">
										<option disabled value=""><?=$lng['Select']?></option>
										<? foreach($xentities as $k=>$v){ ?>
											<option <? if($val['entity'] == $k){echo 'selected';}?> value="<?=$k?>"><?=$v['code']?></option>
										<? } ?>
									</select>
								</td>
							</tr>
						<? } ?>
						</tbody>
					</table>
					<div style="height:10px"></div>
					<button <? if(!$_SESSION['rego']['standard'][$standard]['add_branch']){ echo 'disabled';}?> class="btn btn-primary btn-xs" type="button" id="addBranch"><i class="fa fa-plus fa-mr"></i><?=$lng['Add row']?></button>
					<button id="btn-branch" class="btn btn-primary btn-fr" type="submit"><i class="fa fa-save fa-mr"></i><?=$lng['Update']?></button>
				</form>
				<div style="padding:0 0 0 20px" id="dump"></div>
			</div>
			
				<div class="tab-content-right" style="width:calc(100% - 530px); position:relative">
					<ul class="nav nav-tabs">
						<li class="nav-item"><a class="nav-link" href="#branch_details" data-toggle="tab"><?=$lng['Branch']?></a></li>
						<!-- <li class="nav-item"><a class="nav-link" href="#branch_location" data-toggle="tab"><?=$lng['Location']?></a></li> -->
						<!--<li class="nav-item"><a class="nav-link" href="#branch_documents" data-toggle="tab"><?=$lng['Documents']?></a></li>-->
					</ul>
					<form id="dataForm" style="height:calc(100% - 35px)">
						<fieldset style="height:100%" <?=$disabled?>>
						<input name="ref" type="hidden" value="<?=$getid?>">
						<input id="common_branch_id" name="common_branch_id" type="hidden" value="<?=$getid?>">
						<input id="serialno" name="serialno" type="hidden" value="1">

				
						<button id="btn-data" class="btn btn-primary btn-fr" style="margin:0; position:absolute; right:0; top:0" type="submit"><i class="fa fa-save fa-mr"></i><?=$lng['Update']?></button>
					
						<div class="tab-content" style="height:100%; padding:10px">
							<div class="tab-pane" id="branch_details">
								<table class="basicTable" border="0">
									<thead>
										<tr style="line-height:100%">
											<th colspan="2"><?=$lng['BRANCH DETAILS']?></th>
										</tr>
									</thead>
									<tbody>
										<tr>
											<th><?=$lng['Branch name']?> <?=$lng['Thai']?></th>
											<td class="nopad"><input placeholder="..." name="bra_name_th" type="text" value="<?=$data['bra_name_th']?>" /></td>
										</tr>
										<tr>
											<th><?=$lng['Branch name']?> <?=$lng['English']?></th>
											<td class="nopad"><input placeholder="..." name="bra_name_en" type="text" value="<?=$data['bra_name_en']?>" /></td>
										</tr>
										<tr>
											<th><?=$lng['Address']?> <?=$lng['Thai']?></th>
											<td class="nopad"><textarea placeholder="..." name="bra_address_th" rows="3"><?=$data['bra_address_th']?></textarea></td>
										</tr>
										<tr>
											<th><?=$lng['Address']?> <?=$lng['English']?></th>
											<td class="nopad"><textarea placeholder="..." name="bra_address_en" rows="3"><?=$data['bra_address_en']?></textarea></td>
										</tr>
									<thead>
										<tr style="line-height:100%">
											<th colspan="2"><?=$lng['ENTITY DETAILS']?></th>
										</tr>
									</thead>
									<tbody>
										<tr>
										<tr>
											<th><?=$lng['Entity code']?></th>
											<td><?=$entity_data['code']?></td>
										</tr>
										<tr>
											<th style="width:5%;"><?=$lng['Company name in Thai']?></th>
											<td><?=$entity_data['th_compname']?></td>
										</tr>
										<tr>
											<th><?=$lng['Company name in English']?></th>
											<td><?=$entity_data['en_compname']?></td>
										</tr>
										<tr>
											<th><?=$lng['SSO account ID']?></th>
											<td><?=$entity_data['sso_account']?></td>
										</tr>
									<thead>
										<tr style="line-height:100%">
											<th colspan="2"><?=$lng['SSO DETAILS']?></th>
										</tr>
									</thead>
									<tbody>
										<tr>
											<th><?=$lng['SSO Code']?></th>
											<td class="nopad">
												<select style="width:100%" id="selSSOcode" name="sso_code">
													<option disabled value=""><?=$lng['Select']?></option>
													<? foreach($sso_codes as $k=>$v){ ?>
														<option <? if($sso_code == $k){echo 'selected';}?> value="<?=$k?>"><?=$v['code']?></option>
													<? } ?>
												</select>
										</tr>
										<tr>
											<th><?=$lng['SSO Name']?> <?=$lng['Thai']?></th>
											<td id="sso_name_th"><?=$sso_codes[$sso_code]['th']?></td>
										</tr>
										<tr>
											<th><?=$lng['SSO Name']?> <?=$lng['English']?></th>
											<td id="sso_name_en"><?=$sso_codes[$sso_code]['en']?></td>
										</tr>
										<tr>
											<th><?=$lng['SSO Address']?> <?=$lng['Thai']?></th>
											<td id="sso_address_th">
												<?=$sso_codes[$sso_code]['line1_th']?><br>
												<?=$sso_codes[$sso_code]['line2_th']?><br>
												<?=$sso_codes[$sso_code]['postal_th']?>
											</td>
										</tr>
										<tr>
											<th><?=$lng['SSO Address']?> <?=$lng['English']?></th>
											<td id="sso_address_en">
												<?=$sso_codes[$sso_code]['line1_en']?><br>
												<?=$sso_codes[$sso_code]['line2_en']?><br>
												<?=$sso_codes[$sso_code]['postal_en']?>
											</td>
										</tr>
									</tbody>
								</table>
							</div>
							
					<!-- 		<div class="tab-pane" id="branch_location">
								<table border="0" width="100%" style="table-layout:fixed">
									<tr>
										<td style="vertical-align:top; width:500px; padding:0">
											<table width="100%" border="0" class="basicTable inputs nowrap" style="margin-bottom:10px">
												<th><?=$lng['Time scan system']?></th>
												<td>
													<Select name="scan_system" style="width:100%">
													<? foreach($scan_system as $k=>$v){
															echo '<option ';
															if($loc1['scan_system'] == $k){echo 'selected';}
															//if($k=='other'){echo 'disabled';}
															echo ' value="'.$k.'">'.$v.'</option>';
													} ?>
													</Select>
												</td>
												<tr>
													<th><?=$lng['Location name']?></th>
													<td style="width:100%" >
														<input type="text" name="loc_name" value="<?=$loc1['loc_name']?>" onchange="get_id();">
														<input id="code" type="hidden" name="loc_code" value="<?=$loc1['loc_code']?>">
														<input id="qr" type="hidden" name="loc_qr" value="<?=$loc1['loc_qr']?>">
													</td>
												</tr>
												<tr>
													<th><?=$lng['Latitude']?></th>
													<td><input type="text" name="latitude" value="<?=$loc1['latitude']?>"></td>
												</tr>
												<tr>
													<th><?=$lng['Longitude']?></th>
													<td><input type="text" name="longitude" value="<?=$loc1['longitude']?>"></td>
												</tr>
												<tr>
													<th><?=$lng['Scan perimeter']?></th>
													<td><input class="numeric sel" type="text" name="perimeter" value="<?=$loc1['perimeter']?>"></td>
												</tr>
												<tr>
													<th><?=$lng['Mobile GPS location']?></th>
													<td>
														<Select name="gps" style="width:100%">
															<option <? if($loc1['gps']=='0'){echo 'selected';} ?> value="0"><?=$lng['ON - Scan QR code only within perimeter']?></option>
															<option <? if($loc1['gps']=='1'){echo 'selected';} ?> value="1"><?=$lng['OFF - No GPS location required']?></option>
														</Select>
													</td>
												</tr>
												<tr style="border:0 !important">
													<td colspan="2" style="padding:10px 0 0 !important">
														<button id="newQRcode" data-id="<?=$loc1['ref']?>" type="button" style="width:49%; text-align:center; margin:0" class="btn btn-primary btn-fl"><?=$lng['Create new QR code']?></button>
														<button id="printLocation" <? if(empty($loc1['loc_qr'])){echo 'disabled';}?> data-id="<?=$loc1['ref']?>" type="button" style="width:49%; text-align:center; margin:0" class="btn btn-primary btn-fr"><i class="fa fa-print"></i> &nbsp;<?=$lng['Print QR code']?></button>
													</td>
												</tr>
											</table>
										</td><td valign="top" style="padding-left:10px">
											<? if(empty($loc1['loc_qr'])){ ?>
												<img id="QRimage" style="height:218px; padding:0" src="../images/qrcode.png?123">
											<? }else{ ?>
												<img id="QRimage" width="218px" src="<?=ROOT.$loc1['loc_qr']?>">
											<? } ?>						
								
										</td>
									</tr>
								</table>
									
								<h6 style="background:#eee; padding:6px 10px; margin:0; border-radius:3px 3px 0 0">
									<i class="fa fa-arrow-circle-down"></i>&nbsp;&nbsp;<?=$lng['Google Map']?> - <span style="text-transform:none"><?=$compinfo[$lang.'_compname']?></span>
								</h6>
								<div style="height:calc(100% - 250px);" id="map-canvas"></div>
							</div> -->
						
				
						</div>
					
						</fieldset>
					</form>
				</div>				
	</div>
	
<script>
	
	$(document).ready(function() {

		var sso_codes = <?=json_encode($sso_codes)?>;
		$("#selSSOcode").on('change', function(){
			$('#sso_name_th').html(sso_codes[this.value]['th']);
			$('#sso_name_en').html(sso_codes[this.value]['en']);
			$('#sso_address_th').html(sso_codes[this.value]['line1_th']+'<br>'+sso_codes[this.value]['line2_th']+'<br>'+sso_codes[this.value]['postal_th']);
			$('#sso_address_en').html(sso_codes[this.value]['line1_en']+'<br>'+sso_codes[this.value]['line2_en']+'<br>'+sso_codes[this.value]['postal_en']);
			$("#sAlert").fadeIn(200);
			$("#btn-data").addClass('flash');
		});
		
		$(document).on('click', '#newQRcode', function () {
			var id  = $(this).data('id');
			$.ajax({
				url: "company/ajax/create_qrcode.php",
				data: {id: id},
				dataType: 'json',
				success: function(result){
					//$("#dump").html(result); return false;
					$("#QRimage").attr('src',ROOT+result.qr);
					$("#qr").val(result.qr);
					$("#code").val(result.code);
					$("#btn-company").addClass('flash');
					$("#sAlert").fadeIn(200);
				},
				error:function (xhr, ajaxOptions, thrownError){
					$("body").overhang({
						type: "error",
						message: '<i class="fa fa-exclamation-triangle"></i>&nbsp;&nbsp;<?=$lng['Sorry but someting went wrong']?> <b><?=$lng['Error']?></b> : '+thrownError,
						duration: 4,
					})
				}
			});
		})		

		// $(document).on('click', '#newQRcode2', function () {
		// 	var id  = $(this).data('id');
		// 	$.ajax({
		// 		url: "company/ajax/create_qrcode2.php",
		// 		data: {id: id},
		// 		dataType: 'json',
		// 		success: function(result){
		// 			//$("#dump").html(result); return false;
		// 			$("#QRimage2").attr('src',ROOT+result.qr);
		// 			$("#qr2").val(result.qr);
		// 			$("#code2").val(result.code);
		// 			$("#btn-company").addClass('flash');
		// 			$("#sAlert").fadeIn(200);
		// 		},
		// 		error:function (xhr, ajaxOptions, thrownError){
		// 			$("body").overhang({
		// 				type: "error",
		// 				message: '<i class="fa fa-exclamation-triangle"></i>&nbsp;&nbsp;<?=$lng['Sorry but someting went wrong']?> <b><?=$lng['Error']?></b> : '+thrownError,
		// 				duration: 4,
		// 			})
		// 		}
		// 	});
		// })		

		// $(document).on('click', '#newQRcode3', function () {
		// 	var id  = $(this).data('id');
		// 	$.ajax({
		// 		url: "company/ajax/create_qrcode3.php",
		// 		data: {id: id},
		// 		dataType: 'json',
		// 		success: function(result){
		// 			//$("#dump").html(result); return false;
		// 			$("#QRimage3").attr('src',ROOT+result.qr);
		// 			$("#qr3").val(result.qr);
		// 			$("#code3").val(result.code);
		// 			$("#btn-company").addClass('flash');
		// 			$("#sAlert").fadeIn(200);
		// 		},
		// 		error:function (xhr, ajaxOptions, thrownError){
		// 			$("body").overhang({
		// 				type: "error",
		// 				message: '<i class="fa fa-exclamation-triangle"></i>&nbsp;&nbsp;<?=$lng['Sorry but someting went wrong']?> <b><?=$lng['Error']?></b> : '+thrownError,
		// 				duration: 4,
		// 			})
		// 		}
		// 	});
		// })		
		// $(document).on('click', '#newQRcode4', function () {
		// 	var id  = $(this).data('id');
		// 	$.ajax({
		// 		url: "company/ajax/create_qrcode4.php",
		// 		data: {id: id},
		// 		dataType: 'json',
		// 		success: function(result){
		// 			//$("#dump").html(result); return false;
		// 			$("#QRimage4").attr('src',ROOT+result.qr);
		// 			$("#qr4").val(result.qr);
		// 			$("#code4").val(result.code);
		// 			$("#btn-company").addClass('flash');
		// 			$("#sAlert").fadeIn(200);
		// 		},
		// 		error:function (xhr, ajaxOptions, thrownError){
		// 			$("body").overhang({
		// 				type: "error",
		// 				message: '<i class="fa fa-exclamation-triangle"></i>&nbsp;&nbsp;<?=$lng['Sorry but someting went wrong']?> <b><?=$lng['Error']?></b> : '+thrownError,
		// 				duration: 4,
		// 			})
		// 		}
		// 	});
		// })
			
		$("#printLocation").on('click', function(e){ 
			var id = $(this).data('id');
			window.open('company/print_scan_location.php?id='+id,'_blank');
		});		

		// $("#printLocation2").on('click', function(e){ 
		// 	var id = $(this).data('id');
		// 	window.open('company/print_scan_location2.php?id='+id,'_blank');
		// });		

		// $("#printLocation3").on('click', function(e){ 
		// 	var id = $(this).data('id');
		// 	window.open('company/print_scan_location3.php?id='+id,'_blank');
		// });		

		// $("#printLocation4").on('click', function(e){ 
		// 	var id = $(this).data('id');
		// 	window.open('company/print_scan_location4.php?id='+id,'_blank');
		// });
	
		$("#addBranch").click(function(){
			var row = $("#branchTable tbody tr").length + 1;
			var addrow = '<tr>'+
					'<td>'+
						'<input style="font-weight:600; cursor:pointer" name="branches['+row+'][code]" type="text" />'+
					'</td>'+
					'<td><input class="braName" name="branches['+row+'][th]" type="text" /></td>'+
					'<td><input class="braName" name="branches['+row+'][en]" type="text" /></td>'+
					'<td style="padding:0">'+
						'<select style="width:auto; min-width:100%; background:transparent" name="branches['+row+'][entity]">'+
							'<option value=""><?=$lng['Select']?></option>'+
							'<? foreach($xentities as $k=>$v){ ?>'+
								'<option value="<?=$k?>"><?=$v['code']?></option>'+
							'<? } ?>'+
						'</select>'+
					'</td>'+
				'</tr>';
			$("#branchTable tbody").append(addrow);
			$("#sAlert").fadeIn(200);
			$("#btn-branch").addClass('flash');
		});
		$("#branchForm").submit(function(e){ 
			e.preventDefault();
			var err = 0;
			$(".braCode").each(function(val){
				if($(this).val() == ''){err = 1;}
			})
			if(err){
				$("body").overhang({
					type: "error",
					message: '<i class="fa fa-exclamation-triangle"></i>&nbsp;&nbsp;Branch code can not be empty',
					duration: 2,
				})
				return false;
			}
			$("#btn-branch i").removeClass('fa-save').addClass('fa-refresh fa-spin');
			var formData = $(this).serialize();
			$.ajax({
				url: "company/ajax/update_branches.php",
				data: formData,
				success: function(result){
					//$('#dump').html(result); return false;
					if(result == 'success'){
						$("body").overhang({
							type: "success",
							message: '<i class="fa fa-check"></i>&nbsp;&nbsp;<?=$lng['Data updated successfully']?>',
							duration: 2,
						})
						setTimeout(function(){location.reload();},1500);
					}else{
						$("body").overhang({
							type: "error",
							message: '<i class="fa fa-exclamation-triangle"></i>&nbsp;&nbsp;<?=$lng['Error']?> : '+result,
							duration: 4,
						})
					}
					setTimeout(function(){$("#btn-branch i").removeClass('fa-refresh fa-spin').addClass('fa-save');},500);
				},
				error:function (xhr, ajaxOptions, thrownError){
					$("body").overhang({
						type: "error",
						message: '<i class="fa fa-exclamation-triangle"></i>&nbsp;&nbsp;<?=$lng['Sorry but someting went wrong']?> <b><?=$lng['Error']?></b> : '+thrownError,
						duration: 4,
					})
				}
			});
		});
		$('.braName').on('keyup', function() {
			$("#btn-branch").addClass('flash');
			$("#sAlert").fadeIn(200);
		});
		$('.braSelect').on('change', function() {
			$("#btn-branch").addClass('flash');
			$("#sAlert").fadeIn(200);
		});
		
		
		
		$("#dataForm").submit(function(e){ 
			e.preventDefault();
			$("#btn-data i").removeClass('fa-save').addClass('fa-refresh fa-spin');
			var err = false;
			var data = new FormData($(this)[0]);

			$.ajax({
				url: "company/ajax/update_branches_data1.php",
				type: 'POST',
				data: data,
				async: false,
				cache: false,
				contentType: false,
				processData: false,
				success: function(result){
					//$('#dump').html(result); return false;
					$("#btn-data").removeClass('flash');
					$("#sAlert").fadeOut(200);
					if(result == 'success'){
						$("body").overhang({
							type: "success",
							message: '<i class="fa fa-check"></i>&nbsp;&nbsp;<?=$lng['Data updated successfully']?>',
							duration: 2,
						})
						//setTimeout(function(){location.reload();},2000);
					}else{
						$("body").overhang({
							type: "error",
							message: '<i class="fa fa-exclamation-triangle"></i>&nbsp;&nbsp;<?=$lng['Error']?> : '+result,
							duration: 4,
						})
					}
					setTimeout(function(){$("#btn-data i").removeClass('fa-refresh fa-spin').addClass('fa-save');},500);
				},
				error:function (xhr, ajaxOptions, thrownError){
					$("body").overhang({
						type: "error",
						message: '<i class="fa fa-exclamation-triangle"></i>&nbsp;&nbsp;<?=$lng['Sorry but someting went wrong']?> <b><?=$lng['Error']?></b> : '+thrownError,
						duration: 4,
					})
				}
			});
		});	

		// $("#dataForm2").submit(function(e){ 
		// 	e.preventDefault();
		// 	$("#btn-data i").removeClass('fa-save').addClass('fa-refresh fa-spin');
		// 	var err = false;
		// 	var data = new FormData($(this)[0]);
		// 	$.ajax({
		// 		url: "company/ajax/update_branches_data2.php",
		// 		type: 'POST',
		// 		data: data,
		// 		async: false,
		// 		cache: false,
		// 		contentType: false,
		// 		processData: false,
		// 		success: function(result){
		// 			//$('#dump').html(result); return false;
		// 			$("#btn-data").removeClass('flash');
		// 			$("#sAlert").fadeOut(200);
		// 			if(result == 'success'){
		// 				$("body").overhang({
		// 					type: "success",
		// 					message: '<i class="fa fa-check"></i>&nbsp;&nbsp;<?=$lng['Data updated successfully']?>',
		// 					duration: 2,
		// 				})
		// 				//setTimeout(function(){location.reload();},2000);
		// 			}else{
		// 				$("body").overhang({
		// 					type: "error",
		// 					message: '<i class="fa fa-exclamation-triangle"></i>&nbsp;&nbsp;<?=$lng['Error']?> : '+result,
		// 					duration: 4,
		// 				})
		// 			}
		// 			setTimeout(function(){$("#btn-data i").removeClass('fa-refresh fa-spin').addClass('fa-save');},500);
		// 		},
		// 		error:function (xhr, ajaxOptions, thrownError){
		// 			$("body").overhang({
		// 				type: "error",
		// 				message: '<i class="fa fa-exclamation-triangle"></i>&nbsp;&nbsp;<?=$lng['Sorry but someting went wrong']?> <b><?=$lng['Error']?></b> : '+thrownError,
		// 				duration: 4,
		// 			})
		// 		}
		// 	});
		// });		

		// $("#dataForm3").submit(function(e){ 
		// 	e.preventDefault();
		// 	$("#btn-data i").removeClass('fa-save').addClass('fa-refresh fa-spin');
		// 	var err = false;
		// 	var data = new FormData($(this)[0]);
		// 	$.ajax({
		// 		url: "company/ajax/update_branches_data3.php",
		// 		type: 'POST',
		// 		data: data,
		// 		async: false,
		// 		cache: false,
		// 		contentType: false,
		// 		processData: false,
		// 		success: function(result){
		// 			//$('#dump').html(result); return false;
		// 			$("#btn-data").removeClass('flash');
		// 			$("#sAlert").fadeOut(200);
		// 			if(result == 'success'){
		// 				$("body").overhang({
		// 					type: "success",
		// 					message: '<i class="fa fa-check"></i>&nbsp;&nbsp;<?=$lng['Data updated successfully']?>',
		// 					duration: 2,
		// 				})
		// 				//setTimeout(function(){location.reload();},2000);
		// 			}else{
		// 				$("body").overhang({
		// 					type: "error",
		// 					message: '<i class="fa fa-exclamation-triangle"></i>&nbsp;&nbsp;<?=$lng['Error']?> : '+result,
		// 					duration: 4,
		// 				})
		// 			}
		// 			setTimeout(function(){$("#btn-data i").removeClass('fa-refresh fa-spin').addClass('fa-save');},500);
		// 		},
		// 		error:function (xhr, ajaxOptions, thrownError){
		// 			$("body").overhang({
		// 				type: "error",
		// 				message: '<i class="fa fa-exclamation-triangle"></i>&nbsp;&nbsp;<?=$lng['Sorry but someting went wrong']?> <b><?=$lng['Error']?></b> : '+thrownError,
		// 				duration: 4,
		// 			})
		// 		}
		// 	});
		// });		

		// $("#dataForm4").submit(function(e){ 
		// 	e.preventDefault();
		// 	$("#btn-data i").removeClass('fa-save').addClass('fa-refresh fa-spin');
		// 	var err = false;
		// 	var data = new FormData($(this)[0]);
		// 	$.ajax({
		// 		url: "company/ajax/update_branches_data4.php",
		// 		type: 'POST',
		// 		data: data,
		// 		async: false,
		// 		cache: false,
		// 		contentType: false,
		// 		processData: false,
		// 		success: function(result){
		// 			//$('#dump').html(result); return false;
		// 			$("#btn-data").removeClass('flash');
		// 			$("#sAlert").fadeOut(200);
		// 			if(result == 'success'){
		// 				$("body").overhang({
		// 					type: "success",
		// 					message: '<i class="fa fa-check"></i>&nbsp;&nbsp;<?=$lng['Data updated successfully']?>',
		// 					duration: 2,
		// 				})
		// 				//setTimeout(function(){location.reload();},2000);
		// 			}else{
		// 				$("body").overhang({
		// 					type: "error",
		// 					message: '<i class="fa fa-exclamation-triangle"></i>&nbsp;&nbsp;<?=$lng['Error']?> : '+result,
		// 					duration: 4,
		// 				})
		// 			}
		// 			setTimeout(function(){$("#btn-data i").removeClass('fa-refresh fa-spin').addClass('fa-save');},500);
		// 		},
		// 		error:function (xhr, ajaxOptions, thrownError){
		// 			$("body").overhang({
		// 				type: "error",
		// 				message: '<i class="fa fa-exclamation-triangle"></i>&nbsp;&nbsp;<?=$lng['Sorry but someting went wrong']?> <b><?=$lng['Error']?></b> : '+thrownError,
		// 				duration: 4,
		// 			})
		// 		}
		// 	});
		// });
		
		$('.delDoc').confirmation({
			container: 'body',
			rootSelector: '.delDoc',
			singleton: true,
			animated: 'fade',
			placement: 'left',
			popout: true,
			html: true,
			title 			: '<?=$lng['Are you sure']?>',
			btnOkClass 		: 'btn btn-danger',
			btnOkLabel 		: '<?=$lng['Delete']?>',
			btnOkIconContent: '',
			btnCancelClass 	: 'btn btn-success',
			btnCancelLabel 	: '<?=$lng['Cancel']?>',
			onConfirm: function() { 
				$.ajax({
					url: ROOT+"settings/ajax/delete_document.php",
					data:{doc: $(this).data('id')},
					success: function(result){
						//$('#dump').html(result); return false;
						location.reload();
					}
				});
			}
		});
		
		
		var activeTabBraCom = localStorage.getItem('activeTabBraCom');
		if(activeTabBraCom){
			$('.nav-link[href="' + activeTabBraCom + '"]').tab('show');
		}else{
			$('.nav-link[href="#branch_details"]').tab('show');
		}
		$('a[data-toggle="tab"]').on('shown.bs.tab', function(e) {
			localStorage.setItem('activeTabBraCom', $(e.target).attr('href'));
		});
		
		$('#dataForm input, #dataForm textarea').on('keyup', function (e) {
			$("#btn-data").addClass('flash');
			$("#sAlert").fadeIn(200);
			//alert('click')
		});
		$('#dataForm select').on('change', function (e) {
			$("#btn-data").addClass('flash');
			$("#sAlert").fadeIn(200);
			//alert('click')
		});
		
		
		
		var latitude = <?=json_encode($data['latitude'])?>;
		var longitude = <?=json_encode($data['longitude'])?>;
		
		function addInfoWindow(marker, message) {
			var infoWindow = new google.maps.InfoWindow({
					content: message
			});
			google.maps.event.addListener(marker, 'click', function () {
					infoWindow.open(map, marker);
			});
		}		
		function initialize() {
			var myLatlng = new google.maps.LatLng(latitude, longitude);
			var mapOptions = {
				scrollwheel: false,
				navigationControl: false,
				mapTypeControl: false,
				scaleControl: false,
				draggable: true,
				zoom: 19,
				mapTypeId: google.maps.MapTypeId.ROADMAP,
				center: myLatlng
			}
			var map = new google.maps.Map(document.getElementById('map-canvas'), mapOptions);
			var marker, i, myinfo;
			var content = '<?=$data['loc_name']?>';
			if(latitude != '' && longitude != ''){
				marker = new google.maps.Marker({
					position: new google.maps.LatLng(latitude, longitude),
					map: map,
					title: '<?=$data['loc_name']?>'
				});
				var infowindow = new google.maps.InfoWindow()
				google.maps.event.addListener(marker,'click', (function(marker,content,infowindow){ 
					return function() {
						infowindow.setContent(content);
						infowindow.open(map,marker);
					};
				})(marker,content,infowindow)); 
			}
					
			$(window).resize(function() {
				 google.maps.event.trigger(map, "resize");
			});
			google.maps.event.addListener(map, "idle", function(){
				google.maps.event.trigger(map, 'resize'); 
			});			
		}	

		// function initialize2() {
		// 	var myLatlng = new google.maps.LatLng(latitude, longitude);
		// 	var mapOptions = {
		// 		scrollwheel: false,
		// 		navigationControl: false,
		// 		mapTypeControl: false,
		// 		scaleControl: false,
		// 		draggable: true,
		// 		zoom: 19,
		// 		mapTypeId: google.maps.MapTypeId.ROADMAP,
		// 		center: myLatlng
		// 	}
		// 	var map = new google.maps.Map(document.getElementById('map-canvas2'), mapOptions);
		// 	var marker, i, myinfo;
		// 	var content = '<?=$data['loc_name']?>';
		// 	if(latitude != '' && longitude != ''){
		// 		marker = new google.maps.Marker({
		// 			position: new google.maps.LatLng(latitude, longitude),
		// 			map: map,
		// 			title: '<?=$data['loc_name']?>'
		// 		});
		// 		var infowindow = new google.maps.InfoWindow()
		// 		google.maps.event.addListener(marker,'click', (function(marker,content,infowindow){ 
		// 			return function() {
		// 				infowindow.setContent(content);
		// 				infowindow.open(map,marker);
		// 			};
		// 		})(marker,content,infowindow)); 
		// 	}
					
		// 	$(window).resize(function() {
		// 		 google.maps.event.trigger(map, "resize");
		// 	});
		// 	google.maps.event.addListener(map, "idle", function(){
		// 		google.maps.event.trigger(map, 'resize'); 
		// 	});			
		// }	

		// function initialize3() {
		// 	var myLatlng = new google.maps.LatLng(latitude, longitude);
		// 	var mapOptions = {
		// 		scrollwheel: false,
		// 		navigationControl: false,
		// 		mapTypeControl: false,
		// 		scaleControl: false,
		// 		draggable: true,
		// 		zoom: 19,
		// 		mapTypeId: google.maps.MapTypeId.ROADMAP,
		// 		center: myLatlng
		// 	}
		// 	var map = new google.maps.Map(document.getElementById('map-canvas3'), mapOptions);
		// 	var marker, i, myinfo;
		// 	var content = '<?=$data['loc_name']?>';
		// 	if(latitude != '' && longitude != ''){
		// 		marker = new google.maps.Marker({
		// 			position: new google.maps.LatLng(latitude, longitude),
		// 			map: map,
		// 			title: '<?=$data['loc_name']?>'
		// 		});
		// 		var infowindow = new google.maps.InfoWindow()
		// 		google.maps.event.addListener(marker,'click', (function(marker,content,infowindow){ 
		// 			return function() {
		// 				infowindow.setContent(content);
		// 				infowindow.open(map,marker);
		// 			};
		// 		})(marker,content,infowindow)); 
		// 	}
					
		// 	$(window).resize(function() {
		// 		 google.maps.event.trigger(map, "resize");
		// 	});
		// 	google.maps.event.addListener(map, "idle", function(){
		// 		google.maps.event.trigger(map, 'resize'); 
		// 	});			
		// }		
		// function initialize4() {
		// 	var myLatlng = new google.maps.LatLng(latitude, longitude);
		// 	var mapOptions = {
		// 		scrollwheel: false,
		// 		navigationControl: false,
		// 		mapTypeControl: false,
		// 		scaleControl: false,
		// 		draggable: true,
		// 		zoom: 19,
		// 		mapTypeId: google.maps.MapTypeId.ROADMAP,
		// 		center: myLatlng
		// 	}
		// 	var map = new google.maps.Map(document.getElementById('map-canvas3'), mapOptions);
		// 	var marker, i, myinfo;
		// 	var content = '<?=$data['loc_name']?>';
		// 	if(latitude != '' && longitude != ''){
		// 		marker = new google.maps.Marker({
		// 			position: new google.maps.LatLng(latitude, longitude),
		// 			map: map,
		// 			title: '<?=$data['loc_name']?>'
		// 		});
		// 		var infowindow = new google.maps.InfoWindow()
		// 		google.maps.event.addListener(marker,'click', (function(marker,content,infowindow){ 
		// 			return function() {
		// 				infowindow.setContent(content);
		// 				infowindow.open(map,marker);
		// 			};
		// 		})(marker,content,infowindow)); 
		// 	}
					
		// 	$(window).resize(function() {
		// 		 google.maps.event.trigger(map, "resize");
		// 	});
		// 	google.maps.event.addListener(map, "idle", function(){
		// 		google.maps.event.trigger(map, 'resize'); 
		// 	});			
		// }
		initialize();
		// initialize2();
		// initialize3();
		// initialize4();
	
	});

</script>	

<script type="text/javascript">
	
	function get_id()
	{
		var getid= '<?php  echo $getid ?>';
		$('#common_branch_id').val(getid);
	}
</script>
	
	
	
	
	
	
	
	
	
	
	
	
	
	
