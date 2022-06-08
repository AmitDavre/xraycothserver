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
			$nr = $band_rate[$k+1]*100;
			if(isset($part)){
				
				$data[$nr][1] = $part;
				$data[$nr][2] = $nr;
				$data[$nr][3] = $band[$k+1];
			}else{
				$data[$nr][1] = 0;
				$data[$nr][2] = $nr;
				$data[$nr][3] = 0;
			}
		}
		//return $data;
		
		$total_tax = 0;
		foreach($band as $v){$total_tax += $v;}
		//if(isset($data)){$data = array_reverse($data);}
		$data[0][1] = 150000; $data[0][2] = 0; $data[0][3] = 0;
		$data['year'] = $total_tax;
		
		return $data;
	}
	
	function calculateYearTaxNet($income){
		global $rego_settings;
		$data = array();
		$rules = unserialize($rego_settings['taxrules']);	

		foreach($rules as $k => $v){
			if($v['net_to']>0){$band_top[$k] = (int)$v['net_to'];}
			$band_rate[$k] = (float)$v['percent']/100;
			$band[$k] = 0;
		}
		//var_dump($rules);
		$band_top = array_reverse($band_top,true);
		$band_rate = array_reverse($band_rate,true);
		foreach($band_top as $k => $v){
			if($income > $v) {
				 $part = $income - $v;
				 //$band [$k+1] = ceil($part * $band_rate[$k+1]);
				 $band [$k+1] = $part * $band_rate[$k+1];
				 $income = $band_top[$k];
			}
			//var_dump($income);
			$nr = $band_rate[$k+1]*100;
			if(isset($part)){
				$data[$nr][1] = $part;
				$data[$nr][2] = $nr;
				$data[$nr][3] = $band[$k+1];
			}else{
				$data[$nr][1] = 0;
				$data[$nr][2] = $nr;
				$data[$nr][3] = 0;
			}
		}
		$total_tax = 0;
		foreach($band as $v){$total_tax += $v;}
		//if(isset($data)){$data = array_reverse($data);}
		$data[0][1] = 150000; $data[0][2] = 0; $data[0][3] = 0;
		$data['year'] = $total_tax/12;
		return $data;
	}
	
	
	function calculateYearTaxNet2($income){
		global $rego_settings;
		$accum[0] = 0;
		$accum[1] = 150000;
		$accum[2] = 292500;
		$accum[3] = 472500;
		$accum[4] = 685000;
		$accum[5] = 885000;
		$accum[6] = 1635000;
		$accum[7] = 3735000;
	
		$rules = unserialize($rego_settings['taxrules']);
		
		foreach($rules as $k => $v){ // 865000
			if($income > $accum[$k]){
				if(($income) > $accum[$k+1]){
					$band_tops[$k] = $accum[$k+1] - $accum[$k];
				}else{
					$band_tops[$k] = $income - $accum[$k];
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
