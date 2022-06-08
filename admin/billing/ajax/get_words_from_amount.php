<?
	
	if(session_id()==''){session_start();}
	//var_dump($_REQUEST); exit;
	//$_REQUEST['amount'] = 0;
	if($_SESSION['RGadmin']['lang'] == 'en'){
		
		$f = new NumberFormatter("en", NumberFormatter::SPELLOUT);
		echo '*** '.ucfirst($f->format($_REQUEST['amount'])).' Baht ***';
	
	}else{
		$number = $_REQUEST['amount'];
		if((int)$number == 0){echo '*** ศูนย์บาท ***'; exit;}
		$digit=array('ศูนย์','หนึ่ง','สอง','สาม','สี่','ห้า','หก','เจ็ด','แปด','เก้า','สิบ','สิบเอ็ด');
		$digit2=array('','สิบ','ร้อย','พัน','หมื่น','แสน','ล้าน');
		//$digit=array('null','nung ','song ','sam ','see ','ha ','hok ','tjet ','paet ','kaow ','sip ','et ');
		//$digit2=array('','sip ','roi ','pan ','meung ','sean ','lan ');
		if(strpos($number, '.') !== false){
			$explode_number = explode(".",$number);
			$num0=$explode_number[0]; // เลขจำนวนเต็ม
			$num1=$explode_number[1]; // หลักทศนิยม
		}else{
			$num0=$number; // เลขจำนวนเต็ม
			$num1=''; // หลักทศนิยม
		}
		$bathtext1 = '';// เลขจำนวนเต็ม
		$didit2_chk=strlen($num0)-1;
		for($i=0;$i<=strlen($num0)-1;$i++){
			$cut_input_number=substr($num0,$i,1);
			if($cut_input_number==0){ // ถ้าเลข 0 ไม่ต้องใส่ค่าอะไร
				//$bathtext1.=''."".$digit2[$didit2_chk]; 
			}elseif($cut_input_number==2 && $didit2_chk==1){ // ถ้าเลข 2 อยู่หลักสิบ
				$bathtext1.='ยี่'."".$digit2[$didit2_chk]; 
			}elseif($cut_input_number==1 && $didit2_chk==1){ // ถ้าเลข 1 อยู่หลักสิบ
				//$bathtext1.= ''."".$digit2[$didit2_chk]; 
			}elseif($cut_input_number==1 && $didit2_chk==0){ // ถ้าเลข 1 อยู่หลักหน่วย
				if(substr($num0,$i-1,1)==0){
					$bathtext1.= 'หนึ่ง'."".$digit2[$didit2_chk];
				}else{
					$bathtext1.= 'เอ็ด'."".$digit2[$didit2_chk];
				} 
			}else{
				$bathtext1.= $digit[$cut_input_number]."".$digit2[$didit2_chk];
			}
			$didit2_chk=$didit2_chk-1;
		}
		$bathtext1.='บาท ';
		//$bathtext1.='Bath ';
		// เลขทศนิยม
		$didit2_chk=strlen($num1)-1;
		$satang = false;
		for($i=0;$i<=strlen($num1)-1;$i++){
			$cut_input_number=substr($num1,$i,1);
			if($cut_input_number==0){ // ถ้าเลข 0 ไม่ต้องใส่ค่าอะไร
			
			}elseif($cut_input_number==2 && $didit2_chk==1){ // ถ้าเลข 2 อยู่หลักสิบ
				$bathtext1.='ยี่'."".$digit2[$didit2_chk]; 
				//$bathtext1.='yee'."".$digit2[$didit2_chk]; 
				$satang = true;
			}elseif($cut_input_number==1 && $didit2_chk==1){ // ถ้าเลข 1 อยู่หลักสิบ
				$bathtext1.= ''."".$digit2[$didit2_chk];
				$satang = true;
			}elseif($cut_input_number==1 && $didit2_chk==0){ // ถ้าเลข 1 อยู่หลักหน่วย
				if(substr($num1,$i-1,1)==0){
				$bathtext1.= 'หนึ่ง'."".$digit2[$didit2_chk];
				$satang = true;
				//$bathtext1.= 'neung '."".$digit2[$didit2_chk];
			}else{
				$bathtext1.= 'เอ็ด'."".$digit2[$didit2_chk];
				//$bathtext1.= 'et '."".$digit2[$didit2_chk];
			} 
			$satang = true;
		}else{
			$bathtext1.= $digit[$cut_input_number]."".$digit2[$didit2_chk];
			$satang = true;
		}
		$didit2_chk=$didit2_chk-1;
		}
		if($satang){$bathtext1 .='สตางค์';}else{$bathtext1 .= 'ถ้วน';}
		echo '*** '.$bathtext1.' ***';
	}
