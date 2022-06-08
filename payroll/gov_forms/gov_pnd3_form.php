<?php

		if($res = $dbc->query("SELECT ".$lang."_addr_detail, ".$lang."_compname, comp_phone, tax_id, revenu_branch, dig_signature, digi_signature, dig_stamp, digi_stamp FROM ".$cid."_entities_data WHERE ref = '".$_SESSION['rego']['gov_entity']."'")){
			$data = $res->fetch_assoc();
			$address = unserialize($data[$lang.'_addr_detail']);
		}else{
			//echo mysqli_error($dbc);
		}
		
		$form = getPND3attach($_SESSION['rego']['payroll_dbase'],$_SESSION['rego']['gov_month'],$_SESSION['rego']['gov_entity']);
		$emps = count($form['d']); 
		$income = $form['tot_income']; 
		$tax = $form['tot_tax'];
		
		$chars = '';
		$s = number_format((float)$tax, 2, '.', '');
		if($lang == 'en'){
			$locale = 'en_US';
			$fmt = numfmt_create($locale, NumberFormatter::SPELLOUT);
			$chars = numfmt_format($fmt, round($tax,2));
		}else{
			$chars = getThaiCharNumber($s);
		}
		
		
		
		//var_dump($data);
		//var_dump($address);
		//var_dump($tax);
		//exit;
		
		$pages = 1;
		if($emps > 7){
			$pages = ceil($emps/7);
		}
	
		$month = $cur_month;
		$p = str_replace('-','',$data['tax_id']);
		if(strlen($p)!== 13){$p = '?????????????';}
		$pin = str_split($p);
		
		$branch = sprintf("%05d",$data['revenu_branch']);
		$branch = str_split($branch);
	
		//$address = unserialize($compinfo[$lang.'_addr_detail']);
		if($address && $address['postal'] == ''){$address['postal'] = '?????';}
		if(strlen($address['postal']) != 5){$address['postal'] = '?????';}
		$post = str_split($address['postal']);
		
		$rfill = 1;
		$fill = '';
		$pag = 1;
		$pnd1_controlnr = '';
		$docnr = '';
		$docdate = '';
		$totperson = $emps;
		$totincome = 0;
		$tottax = 0;
		$surcharge = 0;
		$total = 0;

?>