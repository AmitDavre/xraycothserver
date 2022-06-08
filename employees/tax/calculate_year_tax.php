<?php

	function calculateYearTax($income){
		global $rego_settings;
		$rules = unserialize($rego_settings['taxrules']);	
		foreach($rules as $k => $v){
			if($v['to']>0){$band_top[$k] = (int)$v['to'];}
			$band_rate[$k] = (float)$v['percent']/100;
			$band[$k] = 0;
		}
		$band_top = array_reverse($band_top,true);
		$band_rate = array_reverse($band_rate,true);
		foreach($band_top as $k => $v){
			if($income > $v) {
				 $part = $income - $v;
				 //$band [$k+1] = ceil($part * $band_rate[$k+1]);
				 $band [$k+1] = $part * $band_rate[$k+1];
				 $income = $band_top[$k];
			}
			if(isset($part)){
				$data[$k][1] = $part;
				$data[$k][2] = $band[$k+1];
				$data[$k][3] = $band_rate[$k+1];
			}
		}
		$total_tax = 0;
		foreach($band as $v){$total_tax += $v;}
		if(isset($data)){$data = array_reverse($data);}
		
		if($income < $band_top[0]){
			$tax[0][1] = (int)$income; 
			$tax[0][2] = 0;
			$tax[5][1] = 0;
		}else{
			$tax[0][1] = (int)$band_top[0]; 
			$tax[0][2] = 0;
		}

		if(isset($data[0])){$tax[5][1] = $data[0][1]; $tax[5][2] = $data[0][2];}else{$tax[5][2] = 0; $tax[5][2] = 0;}
		if(isset($data[1])){$tax[10][1] = $data[1][1]; $tax[10][2] = $data[1][2];}else{$tax[10][1] = 0; $tax[10][2] = 0;}
		if(isset($data[2])){$tax[15][1] = $data[2][1]; $tax[15][2] = $data[2][2];}else{$tax[15][1] = 0; $tax[15][2] = 0;}
		if(isset($data[3])){$tax[20][1] = $data[3][1]; $tax[20][2] = $data[3][2];}else{$tax[20][1] = 0; $tax[20][2] = 0;}
		if(isset($data[4])){$tax[25][1] = $data[4][1]; $tax[25][2] = $data[4][2];}else{$tax[25][1] = 0; $tax[25][2] = 0;}
		if(isset($data[5])){$tax[30][1] = $data[5][1]; $tax[30][2] = $data[5][2];}else{$tax[30][1] = 0; $tax[30][2] = 0;}
		if(isset($data[6])){$tax[35][1] = $data[6][1]; $tax[35][2] = $data[6][2];}else{$tax[35][1] = 0; $tax[35][2] = 0;}
		$tax['year'] = $total_tax;
		
		return $tax;
	}
	
	
	function calculateYearTaxNet($income){
		global $pr_settings;
		$accum[0] = 0;
		$accum[1] = 150000;
		$accum[2] = 292500;
		$accum[3] = 472500;
		$accum[4] = 685000;
		$accum[5] = 885000;
		$accum[6] = 1635000;
		$accum[7] = 3735000;
	
		$rules = unserialize($pr_settings['taxrules']);
		foreach($rules as $k => $v){ // 865000
			if($income > $accum[$k]){
				if($k < 7){
					if(($income) > $accum[$k+1]){
						$band_tops[$k] = $accum[$k+1] - $accum[$k];
					}else{
						$band_tops[$k] = $income - $accum[$k];
					}
				}else{
					$band_tops[$k] = $income - 3735000;//$band_rate[$k-1];
				}
			}else{
				$band_tops[$k] = 0;
			}
			$band_rate[$k] = (float)$v['percent']/100;
		}
		$band_tops = array_reverse($band_tops,true);
		$band_rate = array_reverse($band_rate,true);
		foreach($band_tops as $k => $v){
			$band_top[$k] = $v + ($v / (1-$band_rate[$k]) * $band_rate[$k]);
		}
		$band_top = array_reverse($band_top,true);
		foreach($band_top as $k => $v){
			if($v > 0){
				$data[$k] = $v  * $band_rate[$k];
			}else{
				$data[$k] = 0;
			}
		}
		$tax[0][1] = $band_top[0]; $tax[0][2] = $data[0];
		$tax[5][1] = $band_top[1]; $tax[5][2] = $data[1];
		$tax[10][1] = $band_top[2]; $tax[10][2] = $data[2];
		$tax[15][1] = $band_top[3]; $tax[15][2] = $data[3];
		$tax[20][1] = $band_top[4]; $tax[20][2] = $data[4];
		$tax[25][1] = $band_top[5]; $tax[25][2] = $data[5];
		$tax[30][1] = $band_top[6]; $tax[30][2] = $data[6];
		$tax[35][1] = $band_top[7]; $tax[35][2] = $data[7];
		
		
		$tax['year'] = array_sum($data);
		$tax['gross'] = array_sum($band_top);
		
		return $tax;
	}
