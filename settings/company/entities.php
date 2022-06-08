<?
	$disabled = 'disabled';
	$dis_color = 'disColor';
	$xentities = getEntities();
	//var_dump($entities); exit;
	
	$data = array();
	if(isset($_GET['id'])){
		$sql = "SELECT * FROM ".$cid."_entities_data WHERE ref = ".$_GET['id'];
		if($res = $dbc->query($sql)){
			if($row = $res->fetch_assoc()){
				$data = $row;
				if(empty($data['logofile'])){$data['logofile'] = 'images/rego_logo.png';}
				if(empty($data['dig_stamp'])){$data['dig_stamp'] = 'images/dig_stamp.png';}
				if(empty($data['dig_signature'])){$data['dig_signature'] = 'images/dig_signature.png';}
				$th_addr_detail = unserialize($data['th_addr_detail']);
				$en_addr_detail = unserialize($data['en_addr_detail']);
				$website_status = $data['website_status'];
				$website_name = $data['website_name'];
				$contact_info_status = $data['contact_info_status'];
				$c_company = $data['c_company'];
				$c_address = $data['c_address'];
				$c_function = $data['c_function'];
				$c_telephone = $data['c_telephone'];
				$c_email = $data['c_email'];				
				$c_company_th = $data['c_company_th'];
				$c_address_th = $data['c_address_th'];
				$c_function_th = $data['c_function_th'];
				$c_telephone_th = $data['c_telephone_th'];
				$c_email_th = $data['c_email_th'];
				$banks = unserialize($data['banks']);
				$sso_codes = unserialize($data['sso_codes']);
				$data['code'] = $xentities[$_GET['id']]['code'];
			}
		}else{
			var_dump(mysqli_error($dbc));
		}
		$disabled = '';
		$dis_color = '';
	}else{
		$_GET['id'] = 0;
	}
	if(!$data){
		$data['code'] = '';
		$data['en_compname'] = '';
		$data['th_compname'] = '';
		$data['comp_phone'] = '';
		$data['comp_fax'] = '';
		$data['comp_email'] = '';
		$data['tax_id'] = '';
		$data['revenu_branch'] = '';
		$data['sso_account'] = '';
		$sso_codes = array();
		$banks = array();
		$data['logofile'] = 'images/logo.png';
		$data['dig_stamp'] = 'images/dig_stamp.png';
		$data['dig_signature'] = 'images/dig_signature.png';
		$data['digi_stamp'] = 0;
		$data['digi_signature'] = 0;
		
		$data['en_address'] = '';
		$data['th_address'] = '';
		
		$th_addr_detail['building'] = '';
		$th_addr_detail['village'] = '';
		$th_addr_detail['room'] = '';
		$th_addr_detail['floor'] = '';
		$th_addr_detail['number'] = '';
		$th_addr_detail['moo'] = '';
		$th_addr_detail['lane'] = '';
		$th_addr_detail['road'] = '';
		$th_addr_detail['subdistrict'] = '';
		$th_addr_detail['district'] = '';
		$th_addr_detail['province'] = '';
		$th_addr_detail['postal'] = '';
		$th_addr_detail['country'] = '';

		$en_addr_detail['building'] = '';
		$en_addr_detail['village'] = '';
		$en_addr_detail['room'] = '';
		$en_addr_detail['floor'] = '';
		$en_addr_detail['number'] = '';
		$en_addr_detail['moo'] = '';
		$en_addr_detail['lane'] = '';
		$en_addr_detail['road'] = '';
		$en_addr_detail['subdistrict'] = '';
		$en_addr_detail['district'] = '';
		$en_addr_detail['province'] = '';
		$en_addr_detail['postal'] = '';
		$en_addr_detail['country'] = '';
		
		$data['bus_reg'] = '';
		$data['comp_affi'] = '';
		$data['house_reg'] = '';
		$data['vat_reg'] = '';
		$data['bankbook'] = '';
		$data['passfs'] = '';
		$data['paw_tax'] = '';
		$data['attach1'] = '';
		$data['attach2'] = '';
		$data['attach3'] = '';
		
	}
	//var_dump($data); exit;
	//var_dump($th_addr_detail); //exit;
	//var_dump($sso_codes); exit;
	/*if(!$sso_codes){
		$sso_codes[1]['id'] = '&nbsp;';
		$sso_codes[1]['th'] = '';
		$sso_codes[1]['en'] = '';
	}*/
	
	/*if(!$banks){
		$banks[1]['code'] = '';
		$banks[1]['th'] = '';
		$banks[1]['en'] = '';
	}*/
	
	$dir = ROOT.$cid.'/documents/';
	if(!empty($data['bus_reg'])){$bus_reg = '<a href="'.$dir.$data['bus_reg'].'" download>'.$data['bus_reg'].'</a>';}else{$bus_reg = $lng['No file selected'];}
	if(!empty($data['comp_affi'])){$comp_affi = '<a href="'.$dir.$data['comp_affi'].'" download>'.$data['comp_affi'].'</a>';}else{$comp_affi = $lng['No file selected'];}
	if(!empty($data['house_reg'])){$house_reg = '<a href="'.$dir.$data['house_reg'].'" download>'.$data['house_reg'].'</a>';}else{$house_reg = $lng['No file selected'];}
	if(!empty($data['vat_reg'])){$vat_reg = '<a href="'.$dir.$data['vat_reg'].'" download>'.$data['vat_reg'].'</a>';}else{$vat_reg = $lng['No file selected'];}
	if(!empty($data['socsec_fund'])){$socsec_fund = '<a href="'.$dir.$data['socsec_fund'].'" download>'.$data['socsec_fund'].'</a>';}else{$socsec_fund = $lng['No file selected'];}
	if(!empty($data['bankbook'])){$bankbook = '<a href="'.$dir.$data['bankbook'].'" download>'.$data['bankbook'].'</a>';}else{$bankbook = $lng['No file selected'];}
	if(!empty($data['passfs'])){$passfs = '<a href="'.$dir.$data['passfs'].'" download>'.$data['passfs'].'</a>';}else{$passfs = $lng['No file selected'];}
	if(!empty($data['paw_tax'])){$paw_tax = '<a href="'.$dir.$data['paw_tax'].'" download>'.$data['paw_tax'].'</a>';}else{$paw_tax = $lng['No file selected'];}
	if(!empty($data['attach1'])){$attach1 = '<a href="'.$dir.$data['attach1'].'" download>'.$data['attach1'].'</a>';}else{$attach1 = $lng['No file selected'];}
	if(!empty($data['attach2'])){$attach2 = '<a href="'.$dir.$data['attach2'].'" download>'.$data['attach2'].'</a>';}else{$attach2 = $lng['No file selected'];}
	if(!empty($data['attach3'])){$attach3 = '<a href="'.$dir.$data['attach3'].'" download>'.$data['attach3'].'</a>';}else{$attach3 = $lng['No file selected'];}

	//var_dump($banks); //exit;
	$tmp = unserialize($rego_settings['bank_codes']);
	foreach($tmp as $k=>$v){
		if($v['apply']){
			$bank_codes[$v['code']]['th'] = $v['th'];
			$bank_codes[$v['code']]['en'] = $v['en'];
		}
	}
	//unset($sso_codes[3]);
	//var_dump($banks); exit;
?>

<style>
	.redHover:hover {
		color:#c00;
	}
	.disColor {
		color:#bbb !important;
	}
</style>

	<h2 style="padding-right:60px"><i class="fa fa-cog fa-mr"></i> <?=$lng['Entities']?>
		<span style="display:none; font-style:italic; color:#b00; padding-left:30px" id="sAlert"><i class="fa fa-exclamation-triangle fa-mr"></i><?=$lng['Data is not updated to last changes made']?></span>
	</h2>		

	<div class="main">
		
		<div class="tab-content-left" style="width:500px; height:100%; overflow-y:auto">
			<form id="entitiesForm">
				<table class="basicTable inputs hoverable selectable" id="entityTable" border="0">
					<thead>
						<tr>
							<th style="width:16%"><?=$lng['Entity code']?></th>
							<th style="width:42%"><?=$lng['Entity']?> <?=$lng['Thai']?></th>
							<th style="width:42%"><?=$lng['Entity']?> <?=$lng['English']?></th>
						</tr>
					</thead>
					<tbody>
					<? foreach($entities as $k=>$v){ ?>
						<tr <? if($_GET['id'] == $k){echo 'class="selected"';}?>>
							<td><input maxlength="6" class="redHover" onclick="window.location.href='index.php?mn=6011&id=<?=$k?>'" readonly style="font-weight:600; cursor:pointer" name="entities[<?=$k?>][code]" type="text" value="<?=$v['code']?>" /></td>
							<td><input class="entName" name="entities[<?=$k?>][th]" type="text" value="<?=$v['th']?>" /></td>
							<td><input class="entName" name="entities[<?=$k?>][en]" type="text" value="<?=$v['en']?>" /></td>
						</tr>
					<? } ?>
					</tbody>
				</table>
				<div style="height:10px"></div>
				<button <? if(!$_SESSION['rego']['standard'][$standard]['add_entity']){ echo 'disabled';}?> class="btn btn-primary btn-xs" type="button" id="addEntity"><i class="fa fa-plus fa-mr"></i><?=$lng['Add row']?></button>
				<button id="btn-entity" class="btn btn-primary btn-fr" type="submit"><i class="fa fa-save fa-mr"></i><?=$lng['Update']?></button>
			</form>
			<div style="padding:0 0 0 20px" id="dump"></div>
		</div>
		
		<div class="tab-content-right" style="width:calc(100% - 500px); position:relative">
			<form id="companyForm" style="height:100%">
			<fieldset <?=$disabled?> style="height:100%">
			<input name="ref" type="hidden" value="<?=$_GET['id']?>">
			<input name="code" type="hidden" value="<?=$data['code']?>">
				<ul class="nav nav-tabs">
					<li class="nav-item"><a class="nav-link <?=$dis_color?>" href="#entity_company" <?=$disabled?>data-toggle="tab"><?=$lng['Company']?></a></li>
					<li class="nav-item"><a class="nav-link <?=$dis_color?>" href="#entity_sso" <?=$disabled?>data-toggle="tab"><?=$lng['SSO Branches']?></a></li>
					<li class="nav-item"><a class="nav-link <?=$dis_color?>" href="#entity_bank" <?=$disabled?>data-toggle="tab"><?=$lng['Banks']?></a></li>
					<li class="nav-item"><a class="nav-link <?=$dis_color?>" href="#entity_address" <?=$disabled?>data-toggle="tab"><?=$lng['Address']?></a></li>
					<li class="nav-item"><a class="nav-link <?=$dis_color?>" href="#entity_pdpa" <?=$disabled?>data-toggle="tab"><?=$lng['PDPA']?></a></li>
					<li class="nav-item"><a class="nav-link <?=$dis_color?>" href="#entity_documents" <?=$disabled?>data-toggle="tab"><?=$lng['Documents']?></a></li>
					<li class="nav-item" style="float:right"></li>
				</ul>
				<button id="btn-company" class="btn btn-primary btn-fr" style="margin:0; position:absolute; right:0; top:0" id="xsubmitBtn" type="submit"><i class="fa fa-save fa-mr"></i><?=$lng['Update']?></button>
				<div class="tab-content" style="height:calc(100% - 35px); padding:10px">
					
					<div class="tab-pane" id="entity_company" style="height:100%; overflow-y:auto">
						<div class="tab-content-left">
							<table class="basicTable inputs" border="0">
								<thead>
								</thead>
								<tbody>
									<tr>
										<th style="width:5%;"><i class="man"></i><?=$lng['Company name in Thai']?></th>
										<td><input name="th_compname" id="th_compname" type="text" value="<?=$data['th_compname']?>" /></td>
									</tr>
									<tr>
										<th><i class="man"></i><?=$lng['Company name in English']?></th>
										<td><input name="en_compname" id="en_compname" type="text" value="<?=$data['en_compname']?>" /></td>
									</tr>
									<tr>
										<th><?=$lng['Company phone']?></th>
										<td><input name="comp_phone" type="text" value="<?=$data['comp_phone']?>" /></td>
									</tr>
									<tr>
										<th><?=$lng['Company fax']?></th>
										<td><input name="comp_fax" type="text" value="<?=$data['comp_fax']?>" /></td>
									</tr>
									<tr>
										<th><?=$lng['Company eMail']?></th>
										<td><input name="comp_email" id="comp_email" type="text" value="<?=$data['comp_email']?>" /></td>
									</tr>
									<tr>
										<th><i class="man"></i><?=$lng['Company Tax ID']?></th>
										<td><input class="tax_id_number"  name="tax_id" id="tax_id" type="text" value="<?=$data['tax_id']?>" /></td>
									</tr>
									<tr>
										<th><i class="man"></i><?=$lng['Revenu Branch']?></th>
										<td><input class="branch5" name="revenu_branch" id="revenu_branch" type="text" value="<?=$data['revenu_branch']?>" /></td>
									</tr>
									<tr>
										<th><?=$lng['SSO account ID']?></th>
										<td><input class="sso_id_number" name="sso_account" id="sso_account" type="text" value="<?=$data['sso_account']?>" /></td>
									</tr>
								</tbody>
							</table>
							
						</div>
						<div class="tab-content-right">
							<table class="basicTable" border="0">
								<tbody>
								<tr>
									<th class="vat"><?=$lng['Company logo']?><br>
										<small><?=$lng['Max. width']?>: 350px<br /><?=$lng['Max. height']?>: 80px</small>
									</th>
									<td style="padding:10px">
										<img class="logoimg" style="max-height:45px; margin-bottom:10px" src="<?=ROOT.$data['logofile'].'?'.time()?>" />
										<button style="margin-right:8px" onclick="$('#complogo').click()" class="btn btn-default btn-xs" type="button"><?=$lng['Select file']?></button><span id="logoname"><?=$lng['No file selected']?></span>
										<input style="visibility:hidden; height:0; float:left" type="file" name="complogo" id="complogo" />
									</td>
								</tr>
								<tr>
									<th class="vat">
										<input type="hidden" name="digi_stamp" value="0">
										<label><input <? if($data['digi_stamp'] == 1){echo 'checked';} ?> type="checkbox" class="checkbox" name="digi_stamp" value="1"><xspan><?=$lng['Digital stamp']?></span></label><br />
										<small>500 x 500 px</small>
									</th>
									<td style="padding:10px">
										<img class="stamp_img" style="height:80px; margin-bottom:10px" src="<?=ROOT.$data['dig_stamp'].'?'.time()?>" />
										<button style="margin-right:8px" onclick="$('#dig_stamp').click()" class="btn btn-default btn-xs" type="button"><?=$lng['Select file']?></button><span id="stamp_name"><?=$lng['No file selected']?></span>
										<input style="visibility:hidden; height:0; float:left" type="file" name="dig_stamp" id="dig_stamp" />
									</td>
								</tr>
								<tr>
									<th class="vat">
										<input type="hidden" name="digi_signature" value="0">
										<label><input <? if($data['digi_signature'] == 1){echo 'checked';} ?> type="checkbox" class="checkbox" name="digi_signature" value="1"><b><?=$lng['Digital signature']?></b></label><br />
										<small>600 x 120 px</small>
									</th>
									<td style="padding:10px">
										<img class="signature_img" style="height:50px; margin-bottom:10px" src="<?=ROOT.$data['dig_signature'].'?'.time()?>" />
										<button style="margin-right:8px" onclick="$('#dig_signature').click()" class="btn btn-default btn-xs" type="button"><?=$lng['Select file']?></button><span id="signature_name"><?=$lng['No file selected']?></span>
										<input style="visibility:hidden; height:0; float:left" type="file" name="dig_signature" id="dig_signature" />
									</td>
								</tr>
								</tbody>
							</table>
						</div>
					</div>
					
					<div class="tab-pane" id="entity_sso" style="height:100%; overflow-y:auto">
						<table id="ssoTable" class="basicTable inputs" border="0">
							<thead>
								<tr style="line-height:100%; background:#f6f6f6">
									<th><?=$lng['SSO Code']?></th>
									<th><?=$lng['Name']?> <?=$lng['Thai']?></th>
									<th><?=$lng['Name']?> <?=$lng['English']?></th>
									<th><?=$lng['Address']?> <?=$lng['Thai']?></th>
									<th><?=$lng['Address']?> <?=$lng['English']?></th>
								</tr>
							</thead>
							<tbody>
								<? if($sso_codes){ foreach($sso_codes as $k=>$v){ ?>
								<tr>
									<td style="vertical-align:top; min-width:100px">
										<input class="branch6" style="font-weight:600" placeholder="..." name="sso_codes[<?=$k?>][code]" type="text" value="<?=$v['code']?>">
									</td>
									<td style="vertical-align:top; width:20%">
										<input placeholder="..."  name="sso_codes[<?=$k?>][th]" type="text" value="<?=$v['th']?>">
									</td>
									<td style="vertical-align:top; width:20%">
										<input placeholder="..." name="sso_codes[<?=$k?>][en]" type="text" value="<?=$v['en']?>">
									</td>
									<td style="vertical-align:top; width:30%">
										<input style="padding-bottom:1px" type="text" name="sso_codes[<?=$k?>][line1_th]" placeholder="Address" value="<?=$v['line1_th']?>"><br>
										<input style="padding-top:1px; padding-bottom:1px" type="text" name="sso_codes[<?=$k?>][line2_th]" placeholder="Subdistrict, District, Province" value="<?=$v['line2_th']?>"><br>
										<input style="padding-top:1px" type="text" name="sso_codes[<?=$k?>][postal_th]" placeholder="Postal code" value="<?=$v['postal_th']?>">
									</td>
									<td style="vertical-align:top; width:30%">
										<input style="padding-bottom:1px" type="text" name="sso_codes[<?=$k?>][line1_en]" placeholder="Address" value="<?=$v['line1_en']?>"><br>
										<input style="padding-top:1px; padding-bottom:1px" type="text" name="sso_codes[<?=$k?>][line2_en]" placeholder="Subdistrict, District, Province" value="<?=$v['line2_en']?>"><br>
										<input style="padding-top:1px" type="text" name="sso_codes[<?=$k?>][postal_en]" placeholder="Postal code" value="<?=$v['postal_en']?>">
									</td>
								</tr>
								<? }} ?>
							</tbody>
						</table>
						<button style="margin-top:10px" class="btn btn-primary btn-xs" type="button" id="addSSO"><i class="fa fa-plus fa-mr"></i><?=$lng['Add row']?></button>
					</div>
					
					<div class="tab-pane" id="entity_bank" style="height:100%; overflow-y:auto">
						<table id="bankTable" class="basicTable inputs" border="0">
							<thead>
								<tr style="line-height:100%; background:#f6f6f6">
									<th style="min-width:200px"><?=$lng['Bank']?></th>
									<th style="min-width:280px"><?=$lng['Account name']?></th>
									<th style="min-width:180px"><?=$lng['Account number']?></th>
									<th style="width:80%"></th>
								</tr>
							</thead>
							<tbody>
								<? if($banks){ foreach($banks as $key=>$val){ ?>
								<tr>
									<td>
										<select name="banks[<?=$key?>][code]" style="width:auto; min-width:100%">
										<? foreach($bank_codes as $k=>$v){ ?>
											<option <? if($val['code'] == $k){echo 'selected';}?> value="<?=$k?>"><?=$v[$lang]?></option>
										<? } ?>
										</select>
									</td>
									<td>
										<input placeholder="..."  name="banks[<?=$key?>][name]" type="text" value="<?=$val['name']?>">
									</td>
									<td>
										<input class="bankaccount" name="banks[<?=$key?>][number]" type="text" value="<?=$val['number']?>">
									</td>
									<td></td>
								</tr>
								<? }} ?>
							</tbody>
						</table>
						<button style="margin-top:10px" class="btn btn-primary btn-xs" type="button" id="addBank"><i class="fa fa-plus fa-mr"></i><?=$lng['Add row']?></button>
					</div>
					
					<div class="tab-pane" id="entity_address">
						<div class="tab-content-left">
							<table class="basicTable inputs" border="0" style="margin-bottom:10px">
								<thead>
									<tr>
										<th><i class="man"></i><?=$lng['Address in Thai']?>&nbsp;&nbsp; <i style="font-weight:400;color:#b00"><?=$lng['for PDF forms']?> - <?=$lng['max. 3 lines']?></i></th>
									</tr>
								</thead>
								<tbody>
									<tr>
										<td><textarea class="maxlines3" name="th_address" id="th_address" rows="3"><?=$data['th_address']?></textarea></td>
									</tr>
								</tbody>
							</table>
							<table class="basicTable inputs" border="0" style="margin-bottom:10px">
								<thead>
									<tr>
										<th colspan="4"><i class="man"></i><?=$lng['Address details Thai']?>&nbsp;&nbsp; <i style="font-weight:400;color:#b00"><?=$lng['for governement forms']?></i></th>
									</tr>
								</thead>
								<tbody>
									<tr>
										<th><?=$lng['Building']?> :</th>
										<td><input class="sub" type="text" id="th_building" name="th_addr_detail[building]" placeholder="__" value="<?=$th_addr_detail['building']?>">
										</td>
									</tr>
									<tr>
										<th><?=$lng['Village']?> :</th>
										<td><input class="sub" type="text" id="th_village" name="th_addr_detail[village]" placeholder="__" value="<?=$th_addr_detail['village']?>">
										</td>
									</tr>		
									<tr>
										<th><?=$lng['Room']?> :</th>
										<td><input class="sub" type="text" id="th_room" name="th_addr_detail[room]" placeholder="__" value="<?=$th_addr_detail['room']?>">
										</td>
									</tr>		
									<tr>
										<th><?=$lng['Floor']?> :</th>
										<td><input class="sub" type="text" id="th_floor" name="th_addr_detail[floor]" placeholder="__" value="<?=$th_addr_detail['floor']?>">
										</td>
									</tr>
									<tr>
										<th><i class="man"></i><?=$lng['Number']?> :</th>
										<td><input class="sub" type="text" id="th_number" name="th_addr_detail[number]" placeholder="__" value="<?=$th_addr_detail['number']?>">
										</td>
									</tr>		
									<tr>
										<th><i class="man"></i><?=$lng['Moo']?> :</th>
										<td><input class="sub" type="text" id="th_moo" name="th_addr_detail[moo]" placeholder="__" value="<?=$th_addr_detail['moo']?>">
										</td>
									</tr>		
									<tr>
										<th><?=$lng['Lane']?> :</th>
										<td colspan="3"><input class="sub" type="text" id="th_lane" name="th_addr_detail[lane]" placeholder="__" value="<?=$th_addr_detail['lane']?>">
										</td>
									</tr>		
									<tr>
										<th><?=$lng['Road']?> :</th>
										<td><input class="sub" type="text" id="th_road" name="th_addr_detail[road]" placeholder="__" value="<?=$th_addr_detail['road']?>">
										</td>
									</tr>		
									<tr>
										<th><?=$lng['Sub district']?> :</th>
										<td><input class="sub" type="text" id="th_subdistrict" name="th_addr_detail[subdistrict]" placeholder="__" value="<?=$th_addr_detail['subdistrict']?>">
										</td>
									</tr>		
									<tr>
										<th><i class="man"></i><?=$lng['District']?> :</th>
										<td colspan="3"><input class="sub" type="text" id="th_district" name="th_addr_detail[district]" placeholder="__" value="<?=$th_addr_detail['district']?>">
										</td>
									</tr>		
									<tr>
										<th><i class="man"></i><?=$lng['Province']?> :</th>
										<td><input class="sub" type="text" id="th_province" name="th_addr_detail[province]" placeholder="__" value="<?=$th_addr_detail['province']?>">
										</td>
									</tr>		
									<tr>
										<th><i class="man"></i><?=$lng['Postal code']?> :</th>
										<td><input class="sub" type="text" id="th_postal" name="th_addr_detail[postal]" placeholder="__" value="<?=$th_addr_detail['postal']?>">
										</td>
									</tr>		
									<tr>
										<th><?=$lng['Country']?> :</th>
										<td colspan="3"><input class="sub" type="text" id="th_country" name="th_addr_detail[country]" placeholder="__" value="<?=$th_addr_detail['country']?>"></td>
									</tr>		
								</tbody>
							</table>
						</div>
						<div class="tab-content-right">
							<table class="basicTable inputs" border="0" style="margin-bottom:10px">
								<thead>
									<tr>
										<th><?=$lng['Address in English']?>&nbsp;&nbsp; <i style="font-weight:400;color:#b00"><?=$lng['for PDF forms']?> - <?=$lng['max. 3 lines']?></i></th>
									</tr>
								</thead>
								<tbody>
									<tr>
										<td><textarea class="maxlines3" name="en_address" id="en_address" rows="3"><?=$data['en_address']?></textarea></td>
									</tr>
								</tbody>
							</table>
							<table class="basicTable inputs" border="0" style="margin-bottom:10px">
								<thead>
									<tr>
										<th colspan="4"><?=$lng['Address details English']?>&nbsp;&nbsp; <i style="font-weight:400;color:#b00"><?=$lng['for governement forms']?></i></th>
									</tr>
								</thead>
								<tbody>
									<tr>
										<th><?=$lng['Building']?> :</th>
										<td><input class="sub" type="text" id="en_building" name="en_addr_detail[building]" placeholder="__" value="<?=$en_addr_detail['building']?>">
										</td>
									</tr>
									<tr>
										<th><?=$lng['Village']?> :</th>
										<td><input class="sub" type="text" id="en_village" name="en_addr_detail[village]" placeholder="__" value="<?=$en_addr_detail['village']?>">
										</td>
									</tr>		
									<tr>
										<th><?=$lng['Room']?> :</th>
										<td><input class="sub" type="text" id="en_room" name="en_addr_detail[room]" placeholder="__" value="<?=$en_addr_detail['room']?>">
										</td>
									</tr>		
									<tr>
										<th><?=$lng['Floor']?> :</th>
										<td><input class="sub" type="text" id="en_floor" name="en_addr_detail[floor]" placeholder="__" value="<?=$en_addr_detail['floor']?>">
										</td>
									</tr>
									<tr>
										<th><?=$lng['Number']?> :</th>
										<td><input class="sub" type="text" id="en_number" name="en_addr_detail[number]" placeholder="__" value="<?=$en_addr_detail['number']?>">
										</td>
									</tr>		
									<tr>
										<th><?=$lng['Moo']?> :</th>
										<td><input class="sub" type="text" id="en_moo" name="en_addr_detail[moo]" placeholder="__" value="<?=$en_addr_detail['moo']?>">
										</td>
									</tr>		
									<tr>
										<th><?=$lng['Lane']?> :</th>
										<td colspan="3"><input class="sub" type="text" id="en_lane" name="en_addr_detail[lane]" placeholder="__" value="<?=$en_addr_detail['lane']?>">
										</td>
									</tr>		
									<tr>
										<th><?=$lng['Road']?> :</th>
										<td><input class="sub" type="text" id="en_road" name="en_addr_detail[road]" placeholder="__" value="<?=$en_addr_detail['road']?>">
										</td>
									</tr>		
									<tr>
										<th><?=$lng['Sub district']?> :</th>
										<td><input class="sub" type="text" id="en_subdistrict" name="en_addr_detail[subdistrict]" placeholder="__" value="<?=$en_addr_detail['subdistrict']?>">
										</td>
									</tr>		
									<tr>
										<th><?=$lng['District']?> :</th>
										<td colspan="3"><input class="sub" type="text" id="en_district" name="en_addr_detail[district]" placeholder="__" value="<?=$en_addr_detail['district']?>">
										</td>
									</tr>		
									<tr>
										<th><?=$lng['Province']?> :</th>
										<td><input class="sub" type="text" id="en_province" name="en_addr_detail[province]" placeholder="__" value="<?=$en_addr_detail['province']?>">
										</td>
									</tr>		
									<tr>
										<th><?=$lng['Postal code']?> :</th>
										<td><input class="sub" type="text" id="en_postal" name="en_addr_detail[postal]" placeholder="__" value="<?=$en_addr_detail['postal']?>">
										</td>
									</tr>		
									<tr>
										<th><?=$lng['Country']?> :</th>
										<td colspan="3"><input class="sub" type="text" id="en_country" name="en_addr_detail[country]" placeholder="__" value="<?=$en_addr_detail['country']?>"></td>
									</tr>		
								</tbody>
							</table>
						</div>
					</div>					

					<div class="tab-pane" id="entity_pdpa">
						<div class="tab-content">
							<table class="basicTable inputs" border="0" style="margin-bottom:10px">
								<thead>
									<tr>
										<th colspan="4"><?=$lng['Website reference']?>&nbsp;&nbsp; <i style="font-weight:400;color:#b00"></i></th>
									</tr>
								</thead>
								<tbody>
									<tr>
										<th><?=$lng['ON/OFF']?> :</th>
										<td>
											<label><input  style="margin-left: 8px;" <? if($website_status == '1'){echo 'checked';} ?> name="website_status" type="checkbox" value="1" class="options checkbox style-0" /><span></span></label>
										</td>
									</tr>
									<tr>
										<th><?=$lng['Website Name']?> </th>
										
										<td><input class="sub" type="text" id="website_name" name="website_name" autocomplete="off" placeholder="regohr.com" value="<?=$website_name?>">
										</td>
									</tr>
									<tr>
										<th> Full URL</th>
										
										<td><input readonly="readonly" class="sub website_name1" type="text"  placeholder="regohr.com" value="https://www.<?=$website_name?>">
										</td>
									</tr>
								</tbody>
							</table>							

							<table class="basicTable inputs" border="0" style="margin-bottom:10px">
								<thead>
									<tr>
										<th colspan="4"><?=$lng['Contact information']?>&nbsp;English<i style="font-weight:400;color:#b00"></i></th>
									</tr>
								</thead>
								<tbody>
									<tr>
										<th><?=$lng['ON/OFF']?> :</th>
										<td>
											<label><input  style="margin-left: 8px;" <? if($contact_info_status == '1'){echo 'checked';} ?> name="contact_info_status" type="checkbox" value="1" class="options checkbox style-0" /><span></span></label>
										</td>
									</tr>
									<tr>
										<th><?=$lng['Company']?> :</th>
										<td><input class="sub" type="text" id="c_company" name="c_company" placeholder="__" value="<?=$c_company?>">
										</td>
									</tr>
									<tr>
										<th><?=$lng['Address']?> :</th>
										<td><input class="sub" type="text" id="c_address" name="c_address" placeholder="__" value="<?=$c_address?>">
										</td>
									</tr>									
									<tr>
										<th><?=$lng['Function']?> :</th>
										<td><input class="sub" type="text" id="c_function" name="c_function" placeholder="__" value="<?=$c_function?>">
										</td>
									</tr>									
									<tr>
										<th><?=$lng['Telephone']?> :</th>
										<td><input class="sub" type="text" id="c_telephone" name="c_telephone" placeholder="__" value="<?=$c_telephone?>">
										</td>
									</tr>									
									<tr>
										<th><?=$lng['Email']?> :</th>
										<td><input class="sub" type="text" id="c_email" name="c_email" placeholder="__" value="<?=$c_email?>">
										</td>
									</tr>
								</tbody>
							</table>	

							<table class="basicTable inputs" border="0" style="margin-bottom:10px">
								<thead>
									<tr>
										<th colspan="4"><?=$lng['Contact information']?>&nbsp;Thai<i style="font-weight:400;color:#b00"></i></th>
									</tr>
								</thead>
								<tbody>
									
									<tr>
										<th><?=$lng['Company']?> :</th>
										<td><input class="sub" type="text" id="c_company_th" name="c_company_th" placeholder="__" value="<?=$c_company_th?>">
										</td>
									</tr>
									<tr>
										<th><?=$lng['Address']?> :</th>
										<td><input class="sub" type="text" id="c_address_th" name="c_address_th" placeholder="__" value="<?=$c_address_th?>">
										</td>
									</tr>									
									<tr>
										<th><?=$lng['Function']?> :</th>
										<td><input class="sub" type="text" id="c_function_th" name="c_function_th" placeholder="__" value="<?=$c_function_th?>">
										</td>
									</tr>									
									<tr>
										<th><?=$lng['Telephone']?> :</th>
										<td><input class="sub" type="text" id="c_telephone_th" name="c_telephone_th" placeholder="__" value="<?=$c_telephone_th?>">
										</td>
									</tr>									
									<tr>
										<th><?=$lng['Email']?> :</th>
										<td><input class="sub" type="text" id="c_email_th" name="c_email_th" placeholder="__" value="<?=$c_email_th?>">
										</td>
									</tr>
								</tbody>
							</table>
					
						</div>
						<div class="tab-content-right">
							
						</div>
					</div>
					
					<div class="tab-pane" id="entity_documents">
						<div style="height:0; width:0; overflow:hidden">
							<input style="visibility:hidden; height:0" type="file" name="bus_reg" id="bus_reg" />
							<input style="visibility:hidden; height:0" type="file" name="comp_affi" id="comp_affi" />
							<input style="visibility:hidden; height:0" type="file" name="house_reg" id="house_reg" />
							<input style="visibility:hidden; height:0" type="file" name="vat_reg" id="vat_reg" />
							<input style="visibility:hidden; height:0" type="file" name="socsec_fund" id="socsec_fund" />
							<input style="visibility:hidden; height:0" type="file" name="bankbook" id="bankbook" />
							<input style="visibility:hidden; height:0" type="file" name="passfs" id="passfs" />
							<input style="visibility:hidden; height:0" type="file" name="paw_tax" id="paw_tax" />
							<input style="visibility:hidden; height:0" type="file" name="attach1" id="attach1" />
							<input style="visibility:hidden; height:0" type="file" name="attach2" id="attach2" />
							<input style="visibility:hidden; height:0" type="file" name="attach3" id="attach3" />
						</div>
						<div style="overflow-x:auto">
						<table class="basicTable vam attach thwrap" border="0">
							<tbody>
								<tr>
									<th style="width:20%; min-width:220px"><?=$lng['Business registration']?></th>
									<td style="width:80%">
										<button onclick="$('#bus_reg').click()" class="btn btn-default btn-fa" type="button"><i class="fa fa-upload"></i></button><span id="bus_reg_name"><?=$bus_reg?></span>
									</td>
									<td><a href="#" data-id="bus_reg" class="delDoc"><i class="fa fa-trash fa-lg"></i></a></td>
								</tr>
								<tr>
									<th><?=$lng['Company affidavit']?></th><td>
										<button onclick="$('#comp_affi').click()" class="btn btn-default btn-fa" type="button"><i class="fa fa-upload"></i></button><span id="comp_affi_name"><?=$comp_affi?></span>
									</td>
									<td><a href="#" data-id="comp_affi" class="delDoc"><i class="fa fa-trash fa-lg"></i></a></td>
								</tr>
								<tr>
									<th><?=$lng['House registration multiple']?></th><td>
										<button onclick="$('#house_reg').click()" class="btn btn-default btn-fa" type="button"><i class="fa fa-upload"></i></button><span id="house_reg_name"><?=$house_reg?></span>
									</td>
									<td><a href="#" data-id="house_reg" class="delDoc"><i class="fa fa-trash fa-lg"></i></a></td>
								</tr>
								<tr>
									<th><?=$lng['VAT registration']?></th><td>
										<button onclick="$('#vat_reg').click()" class="btn btn-default btn-fa" type="button"><i class="fa fa-upload"></i></button><span id="vat_reg_name"><?=$vat_reg?></span>
									</td>
									<td><a href="#" data-id="vat_reg" class="delDoc"><i class="fa fa-trash fa-lg"></i></a></td>
								</tr>
								<tr>
									<th><?=$lng['Social security fund']?></th><td>
										<button onclick="$('#socsec_fund').click()" class="btn btn-default btn-fa" type="button"><i class="fa fa-upload"></i></button><span id="socsec_fund_name"><?=$socsec_fund?></span>
									</td>
									<td><a href="#" data-id="socsec_fund" class="delDoc"><i class="fa fa-trash fa-lg"></i></a></td>
								</tr>
								<tr>
									<th><?=$lng['Copy of bankbook']?></th><td>
										<button onclick="$('#bankbook').click()" class="btn btn-default btn-fa" type="button"><i class="fa fa-upload"></i></button><span id="bankbook_name"><?=$bankbook?></span>
									</td>
									<td><a href="#" data-id="bankbook" class="delDoc"><i class="fa fa-trash fa-lg"></i></a></td>
								</tr>
								<tr>
									<th><?=$lng['Power attorney ss fund']?></th>
									<td>
										<button onclick="$('#passfs').click()" class="btn btn-default btn-fa" type="button"><i class="fa fa-upload"></i></button><span id="passfs_name"><?=$passfs?></span>
									</td>
									<td><a href="#" data-id="passfs" class="delDoc"><i class="fa fa-trash fa-lg"></i></a></td>
								</tr>
								<tr>
									<th><?=$lng['Power attorney withholding tax']?></th>
									<td>
										<button onclick="$('#paw_tax').click()" class="btn btn-default btn-fa" type="button"><i class="fa fa-upload"></i></button><span id="paw_tax_name"><?=$paw_tax?></span>
									</td>
									<td><a href="#" data-id="paw_tax" class="delDoc"><i class="fa fa-trash fa-lg"></i></a></td>
								</tr>
								<tr>
									<th><?=$lng['Additional file']?></th>
									<td>
										<button onclick="$('#attach1').click()" class="btn btn-default btn-fa" type="button"><i class="fa fa-upload"></i></button><span id="attach1_name"><?=$attach1?></span>
									</td>
									<td><a href="#" data-id="attach1" class="delDoc"><i class="fa fa-trash fa-lg"></i></a></td>
								</tr>
								<tr>
									<th><?=$lng['Additional file']?></th>
									<td>
										<button onclick="$('#attach2').click()" class="btn btn-default btn-fa" type="button"><i class="fa fa-upload"></i></button><span id="attach2_name"><?=$attach2?></span>
									</td>
									<td><a href="#" data-id="attach2" class="delDoc"><i class="fa fa-trash fa-lg"></i></a></td>
								</tr>
								<tr>
									<th><?=$lng['Additional file']?></th>
									<td>
										<button onclick="$('#attach3').click()" class="btn btn-default btn-fa" type="button"><i class="fa fa-upload"></i></button><span id="attach3_name"><?=$attach3?></span>
									</td>
									<td><a href="#" data-id="attach3" class="delDoc"><i class="fa fa-trash fa-lg"></i></a></td>
								</tr>
							</tbody>
						</table>
						</div>	
					</div>
				</div>
			</fieldset>
			</form>
		</div>
	
	</div>
	

<script>
	
$(document).ready(function() {
	
	$("#addSSO").click(function(){
		var row = $("#ssoTable tbody tr").length+1;
		//if(row > 2){return false;}
		var addrow = '<tr>'+
			'<td style="vertical-align:top; min-width:100px">'+
				'<input style="font-weight:600" class="branch6"  name="sso_codes['+row+'][code]" type="text">'+
			'</td>'+
			'<td style="vertical-align:top; width:20%">'+
				'<input placeholder="..." name="sso_codes['+row+'][th]" type="text">'+
			'</td>'+
			'<td style="vertical-align:top; width:20%">'+
				'<input placeholder="..." name="sso_codes['+row+'][en]" type="text">'+
			'</td>'+
			'<td style="vertical-align:top; width:30%">'+
				'<input style="padding-bottom:1px" type="text" name="sso_codes['+row+'][line1_th]" placeholder="Address"><br>'+
				'<input style="padding-bottom:1px" type="text" name="sso_codes['+row+'][line2_th]" placeholder="Subdistrict, District, Province"><br>'+
				'<input style="padding-top:1px" type="text" name="sso_codes['+row+'][postal_th]" placeholder="Postal code">'+
			'</td>'+
			'<td style="vertical-align:top; width:30%">'+
				'<input style="padding-bottom:1px" type="text" name="sso_codes['+row+'][line1_en]" placeholder="Address"><br>'+
				'<input style="padding-bottom:1px" type="text" name="sso_codes['+row+'][line2_en]" placeholder="Subdistrict, District, Province"><br>'+
				'<input style="padding-top:1px" type="text" name="sso_codes['+row+'][postal_en]" placeholder="Postal code">'+
			'</td>'+
		'</tr>';
		
		$("#ssoTable").append(addrow);
		$(".branch6").mask("999999", {placeholder: "******"});
	});
	
	$("#addBank").click(function(){
		var row = $("#bankTable tbody tr").length+1;
		if(row > 5){return false;}
		var addrow = '<tr>'+
				'<td>'+
					'<select name="banks['+row+'][code]" style="width:auto; min-width:100%">'+
					'<? foreach($bank_codes as $k=>$v){ ?>'+
						'<option value="<?=$k?>"><?=$v[$lang]?></option>'+
					'<? } ?>'+
					'</select>'+
				'</td>'+
				'<td>'+
					'<input placeholder="..."  name="banks['+row+'][name]" type="text">'+
				'</td>'+
				'<td>'+
					'<input class="bankaccount" name="banks['+row+'][number]" type="text">'+
				'</td>'+
				'<td></td>'+
			'</tr>';
		
		$("#bankTable tbody").append(addrow);
		$(".bankaccount").mask("999-9-99999-9", {placeholder: "***_*_*****_*"});
	});
	
	$("#addEntity").click(function(){
		var row = $("#entityTable tbody tr").length + 1;
		var addrow = '<tr>'+
			'<td><input maxlength="6" class="entCode" style="font-weight:600" name="entities['+row+'][code]" type="text" /></td>'+
			'<td><input name="entities['+row+'][th]" type="text" /></td>'+
			'<td><input name="entities['+row+'][en]" type="text" /></td>'+
		'</tr>';
		$("#entityTable tbody").append(addrow);
		$("#sAlert").fadeIn(200);
		$("#btn-entity").addClass('flash');
	});
	$("#entitiesForm").submit(function(e){ 
		e.preventDefault();
		var err = 0;
		$(".entCode").each(function(val){
			if($(this).val() == ''){err = 1;}
		})
		if(err){
			$("body").overhang({
				type: "error",
				message: '<i class="fa fa-exclamation-triangle"></i>&nbsp;&nbsp;Entity code can not be empty',
				duration: 2,
			})
			return false;
		}
		$("#btn-entity i").removeClass('fa-save').addClass('fa-refresh fa-spin');
		var formData = $(this).serialize();
		$.ajax({
			url: "company/ajax/update_entities.php",
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
				setTimeout(function(){$("#btn-entity i").removeClass('fa-refresh fa-spin').addClass('fa-save');},500);
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
	$('.entName').on('keyup', function (e) {
		$("#btn-entity").addClass('flash');
		$("#sAlert").fadeIn(200);
	});
	$("#companyForm").submit(function(e){ 
		e.preventDefault();
		$("#btn-company i").removeClass('fa-save').addClass('fa-refresh fa-spin');
		var err = false;
		if($("#th_compname").val() == ''){err = true;}
		if($("#en_compname").val() == ''){err = true;}
		if($("#tax_id").val() == ''){err = true;}
		if($("#revenu_branch").val() == ''){err = true;}
		//if($("#sso_account").val() == ''){err = true;}
		
		if($("#th_address").val() == ''){err = true;}
		if($("#th_number").val() == ''){err = true;}
		if($("#th_moo").val() == ''){err = true;}
		//if($("#th_subdistrict").val() == ''){err = true;}
		if($("#th_district").val() == ''){err = true;}
		if($("#th_province").val() == ''){err = true;}
		if($("#th_postal").val() == ''){err = true;}
		
		if(err){
			$("body").overhang({
				type: "error",
				message: '<i class="fa fa-exclamation-triangle"></i>&nbsp;&nbsp;<?=$lng['Please fill in required fields']?> in every Tab',
				duration: 4,
			})
			setTimeout(function(){$("#submitBtn i").removeClass('fa-refresh fa-spin').addClass('fa-save');},500);
			return false;
		}
		
		var data = new FormData($(this)[0]);
		$.ajax({
			url: "company/ajax/update_entities_data.php",
			type: 'POST',
			data: data,
			async: false,
			cache: false,
			contentType: false,
			processData: false,
			success: function(result){
				//$('#dump').html(result); return false;
				$("#btn-company").removeClass('flash');
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
				setTimeout(function(){$("#btn-company i").removeClass('fa-refresh fa-spin').addClass('fa-save');},500);
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
	$("#ssoForm").submit(function(e){ 
		e.preventDefault();
		//$("#btn-company i").removeClass('fa-save').addClass('fa-refresh fa-spin');
		var err = false;
		/*if($("#th_compname").val() == ''){err = true;}
		if($("#tax_id").val() == ''){err = true;}
		if($("#branch").val() == ''){err = true;}
		if($("#th_address").val() == ''){err = true;}
		if($("#th_number").val() == ''){err = true;}
		if($("#th_moo").val() == ''){err = true;}
		if($("#th_subdistrict").val() == ''){err = true;}
		if($("#th_district").val() == ''){err = true;}
		if($("#th_province").val() == ''){err = true;}
		if($("#th_postal").val() == ''){err = true;}*/
		
		/*if(err){
			$("body").overhang({
				type: "error",
				message: '<i class="fa fa-exclamation-triangle"></i>&nbsp;&nbsp;<?=$lng['Please fill in required fields']?>',
				duration: 4,
				//closeConfirm: true
			})
			setTimeout(function(){$("#submitBtn i").removeClass('fa-refresh fa-spin').addClass('fa-save');},500);
			return false;
		}*/
		
		var data = $(this).serialize();
		$.ajax({
			url: "company/ajax/update_entities_data.php",
			data: data,
			success: function(result){
				//$('#dump').html(result); return false;
				//$("#btn-company").removeClass('flash');
				//$("#sAlert").fadeOut(200);
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
				setTimeout(function(){$("#btn-company i").removeClass('fa-refresh fa-spin').addClass('fa-save');},500);
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
	
	$("#complogo").on("change", function(e){
		e.preventDefault();
		//var id = cid.replace('x',acc);
		//alert('id');
		var ff = $(this).val().toLowerCase();
		ff = ff.replace(/.*[\/\\]/, '');
		var ext =  ff.split('.').pop();
		f = ff.substr(0, ff.lastIndexOf('.'));
		var r = f.split('_');
		//alert(ff)
		$('#err_msg').html('');
		if(!(ext == 'jpg' || ext == 'jpeg' || ext == 'png' || ext == 'gif')){
			$('#message').html('<div class="msg_alert">Please use only .jpg - .jpeg - .png - .gif files</div>').fadeIn(200);
			setTimeout(function(){$("#message").fadeOut(200);},4000);
			return false;
		}
		$('#logoname').html(ff);
		var file = $(this)[0].files[0];
		if(file){
			var reader = new FileReader();
			reader.readAsDataURL(file);
			reader.onload = function(e) {
				var img = new Image;
				$('.logoimg').prop('src', e.target.result);
			}
		}
		//$('#message').html('');
	});

	$("#dig_signature").on("change", function(e){
		e.preventDefault();
		//var id = cid.replace('x',acc);
		//alert('id');
		var ff = $(this).val().toLowerCase();
		ff = ff.replace(/.*[\/\\]/, '');
		var ext =  ff.split('.').pop();
		f = ff.substr(0, ff.lastIndexOf('.'));
		var r = f.split('_');
		//alert(ff)
		$('#err_msg').html('');
		if(!(ext == 'jpg' || ext == 'jpeg' || ext == 'png' || ext == 'gif')){
			$('#message').html('<div class="msg_alert">Please use only .jpg - .jpeg - .png - .gif files</div>').fadeIn(200);
			setTimeout(function(){$("#message").fadeOut(200);},4000);
			return false;
		}
		$('#signature_name').html(ff);
		var file = $(this)[0].files[0];
		if(file){
			var reader = new FileReader();
			reader.readAsDataURL(file);
			reader.onload = function(e) {
				var img = new Image;
				$('.signature_img').prop('src', e.target.result);
			}
		}
		//$('#message').html('');
	});

	$("#dig_stamp").on("change", function(e){
		e.preventDefault();
		//var id = cid.replace('x',acc);
		//alert('id');
		var ff = $(this).val().toLowerCase();
		ff = ff.replace(/.*[\/\\]/, '');
		var ext =  ff.split('.').pop();
		f = ff.substr(0, ff.lastIndexOf('.'));
		var r = f.split('_');
		//alert(ff)
		$('#err_msg').html('');
		if(!(ext == 'jpg' || ext == 'jpeg' || ext == 'png' || ext == 'gif')){
			$('#message').html('<div class="msg_alert">Please use only .jpg - .jpeg - .png - .gif files</div>').fadeIn(200);
			setTimeout(function(){$("#message").fadeOut(200);},4000);
			return false;
		}
		$('#stamp_name').html(ff);
		var file = $(this)[0].files[0];
		if(file){
			var reader = new FileReader();
			reader.readAsDataURL(file);
			reader.onload = function(e) {
				var img = new Image;
				$('.stamp_img').prop('src', e.target.result);
			}
		}
		//$('#message').html('');
	});

	function readAttURL(input,id) {
	  if (input.files && input.files[0]) {
			var reader = new FileReader();
			reader.onload = function (e) {
				var fileExtension = ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'jpg', 'jpeg', 'png'];
				var ext = input.files[0].name.split('.').pop();
				if ($.inArray(ext.toLowerCase(), fileExtension) == -1) {
					//alert("Only  " + fileExtension.join(', ')+ "  formats are allowed !");
					$(id).html('<b style="color:#b00;font-weight:600">Only  ' + fileExtension.join(', ')+ '  formats are allowed !</b>');
				}else{				
					$(id).html(input.files[0].name);
					$("#submitBtn").addClass('flash');
					$("#sAlert").fadeIn(200);
				}
			}
			reader.readAsDataURL(input.files[0]);
	  }
   }
	 
    $("#med_insurance_att").change(function(){
        readAttURL(this,'#med_insurance_name');
    });
    $("#comp_rule_reg_att").change(function(){
        readAttURL(this,'#rulereg_name');
    });
    $("#bus_reg").change(function(){
        readAttURL(this,'#bus_reg_name');
    });
    $("#comp_affi").change(function(){
        readAttURL(this,'#comp_affi_name');
    });
    $("#house_reg").change(function(){
        readAttURL(this,'#house_reg_name');
    });
    $("#vat_reg").change(function(){
        readAttURL(this,'#vat_reg_name');
    });
    $("#socsec_fund").change(function(){
        readAttURL(this,'#socsec_fund_name');
    });
    $("#bankbook").change(function(){
        readAttURL(this,'#bankbook_name');
    });
    $("#passfs").change(function(){
        readAttURL(this,'#passfs_name');
    });
    $("#paw_tax").change(function(){
        readAttURL(this,'#paw_tax_name');
    });
    $("#attach1").change(function(){
        readAttURL(this,'#attach1_name');
    });
    $("#attach2").change(function(){
        readAttURL(this,'#attach2_name');
    });
    $("#attach3").change(function(){
        readAttURL(this,'#attach3_name');
    });
	
	
	var activeTabEntCom = localStorage.getItem('activeTabEntCom');
	if(activeTabEntCom){
		$('.nav-link[href="' + activeTabEntCom + '"]').tab('show');
	}else{
		$('.nav-link[href="#entity_company"]').tab('show');
	}
	$('a[data-toggle="tab"]').on('shown.bs.tab', function(e) {
		localStorage.setItem('activeTabEntCom', $(e.target).attr('href'));
	});
	
	$(document).on('keyup', 'input, textarea', function (e) {
		$("#btn-company").addClass('flash');
		$("#sAlert").fadeIn(200);
	});
	$(document).on('change', 'select, .checkbox', function (e) {
	//$('select, .checkbox').on('change', function (e) {
		$("#btn-company").addClass('flash');
		$("#sAlert").fadeIn(200);
	});
			
});


$('#website_name').keyup(function() {
    var dInput = this.value;
    $(".website_name1").val('https://www.'+dInput);
});

</script>	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
