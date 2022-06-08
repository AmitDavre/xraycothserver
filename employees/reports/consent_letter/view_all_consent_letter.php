<?php
	if(session_id()==''){session_start();}
	ob_start();
	include('../../../dbconnect/db_connect.php');
	include(DIR.'files/functions.php');
	include(DIR.'files/arrays_'.$_SESSION['rego']['lang'].'.php');
	


	$sql3244 = "SELECT * FROM rego_consent_letter ";
	if($reasdasds = $dbx->query($sql3244)){
		if($rosaddsw = $reasdasds->fetch_assoc()){

				$block1 = $rosaddsw[$_SESSION['rego']['lang'].'_identification'];
				$block2 = $rosaddsw[$_SESSION['rego']['lang'].'_body_text'];
				$block3 = $rosaddsw[$_SESSION['rego']['lang'].'_reference'];
		}
	}		

	// get default logo
	$sql32444 = "SELECT * FROM " .$cid."_entities_data ";
	if($reasdasds4 = $dbc->query($sql32444)){
		if($rosaddsw4 = $reasdasds4->fetch_assoc()){

			$logoimage = $rosaddsw4['logofile'];
			$en_compname = $rosaddsw4['en_compname'];
			$website_status = $rosaddsw4['website_status'];
			$website_name = $rosaddsw4['website_name'];
			$contact_info_status = $rosaddsw4['contact_info_status'];
			$c_company = $rosaddsw4['c_company'];
			$c_address = $rosaddsw4['c_address'];
			$c_function = $rosaddsw4['c_function'];
			$c_telephone = $rosaddsw4['c_telephone'];
			$c_email = $rosaddsw4['c_email'];
		}
	}	

	$sbranches = str_replace(',', "','", $_SESSION['rego']['sel_branches']);
	$sdivisions = str_replace(',', "','", $_SESSION['rego']['sel_divisions']);
	$sdepartments = str_replace(',', "','", $_SESSION['rego']['sel_departments']);
	$steams = str_replace(',', "','", $_SESSION['rego']['sel_teams']);
	
	// $where = "emp_group = '".$_SESSION['rego']['emp_group']."'";
	$whereCond = " branch IN ('".$sbranches."')";
	$whereCond .= " AND division IN ('".$sdivisions."')";
	$whereCond .= " AND department IN ('".$sdepartments."')";
	$whereCond .= " AND team IN ('".$steams."')";



	// get the selected employee information 

	$sql_get_employee_data = "SELECT * FROM " .$cid."_consent_letter where ".$whereCond."";
	if($result_get_employee_data = $dbc->query($sql_get_employee_data)){
		while($row_employee_data = $result_get_employee_data->fetch_assoc()){

			$emp_en_name[] = $row_employee_data;
		}
	}	



	end($emp_en_name);         
	$lastkey = key($emp_en_name);



	$datevalue = date('d-m-Y');
		$html = '<html><body>';

	foreach ($emp_en_name as $key => $value) {
		# code...

	    // block1

		$text1 = str_replace('<b>{EMPLOYEE_NAME}</b>', '<b>'.$value['en_name'].'</b>', $block1);
		$text1 = str_replace('<b>{COMPANY_NAME}</b>', '<b>'.$en_compname.'</b>', $text1);

		if($_GET['r'] == '1')
		{
			$text3 = str_replace('<b>{COMPANY_VALUE_NAME}</b>', '<b>'.$c_company.'</b>', $block3);
			$text3 = str_replace('<b>{ADDRESS_VALUE}</b>', '<b>'.$c_address.'</b>', $text3);
			$text3 = str_replace('<b>{FUNCTION_VALUE}</b>', '<b>'.$c_function.'</b>', $text3);
			$text3 = str_replace('<b>{TELEPHONE_VALUE}</b>', '<b>'.$c_telephone.'</b>', $text3);
			$text3 = str_replace('<b>{EMAIL_VALUE}</b>', '<b>'.$c_email.'</b>', $text3);

		}
		else if($_GET['r'] == '0')
		{

			$text3 = str_replace('<span style="text-align: justify;">Company : <b>{COMPANY_VALUE_NAME}</b></span>', '', $block3);
			$text3 = str_replace('<span style="text-align: justify;">Address: <b>{ADDRESS_VALUE}</b></span>', '', $text3);
			$text3 = str_replace('<span style="text-align: justify;">Function: <b>{FUNCTION_VALUE}</b></span>', '', $text3);
			$text3 = str_replace('<span style="text-align: justify;">Telephone: <b>{TELEPHONE_VALUE}</b></span>', '', $text3);
			$text3 = str_replace('<div style="text-align: justify; margin-left: 25px;">Email : <b>{EMAIL_VALUE}</b></div>', '', $text3);
		}


		if($_GET['d'] == '0')
		{
			// replace date
			$text3 = str_replace('<span style="font-size: 13px;">Date: <b>{DATE_VALUE}</b></span>', '', $text3);
		}
		else if ($_GET['d'] == '1')
		{
			// show date 
			$text3 = str_replace('<b>{DATE_VALUE}</b>', '<b>'.$datevalue.'</b>', $text3);
		}
		

		if($_GET['w'] == '1')
		{
			if($website_status == '1')
			{
				$text3 = str_replace('<span style="text-align: justify;">The Company has established a Privacy Policy to comply with the Personal Data&nbsp; Protection Act B.E. 2562 ( 2019),&nbsp; which may be amended from time to time. The privacy policy can be requested with the company representative (see art6).</span>', '<span style="text-align: justify;">The Company has established a Privacy Policy to comply with the Personal Data&nbsp; Protection Act B.E. 2562 ( 2019),&nbsp; which may be amended from time to time. The privacy policy can be requested with the company representative (see art6) or can be consulted at the company website at <b>{WEBSITE_LINK}</b></span>', $text3);


				$text3 = str_replace('<b>{WEBSITE_LINK}</b>', '<b>'.$website_name.'</b>', $text3);

			}
		}

		// logo file

		if($_GET['l'] == '1')
		{
			$imgfile = '<img src="../../../'.$logoimage.'" style="height:35px;max-width:280px">';
		}
		else if($_GET['l'] == '0')
		{
			$imgfile = '';
		}

		$html .= $imgfile;
		$html .= '<table><tr> <td>&nbsp;</td></tr></table>';
		$html .= $text1;
		$html .= $block2;
		$html .= '<table><tr> <td>&nbsp;</td></tr><tr> <td>&nbsp;</td></tr></table>';
		$html .= $text3;

		if($lastkey != $key)
		{
			$html .= '<page_break>';
		}

	}

		$html .= '</body></html>';	
		

	require_once(DIR."mpdf7/vendor/autoload.php");

	//class mPDF ([ string $mode [, mixed $format [, float $default_font_size [, string $default_font [, float $margin_left , float $margin_right , float $margin_top , float $margin_bottom , float $margin_header , float $margin_footer [, string $orientation ]]]]]])
	$mpdf = new mPDF('UTF-8', 'A4-P', 13, 'leelawadee', 10, 10, 10, 10, 0, 0);
	$mpdf->SetTitle($compinfo[$lang.'_compname'].' ('.strtoupper($_SESSION['rego']['cid']).') - Consent Letter '.$_SESSION['rego']['cur_year']);
	
	// $mpdf->WriteHTML($style,1);
	$mpdf->WriteHTML($html);
	//$mpdf->Output();
	//$mpdf->Output($_SESSION['rego']['cid'].'_A4_payslips_'.$month.'_'.$_SESSION['rego']['cur_year'].'.pdf','I');
	
	$dir = DIR.$_SESSION['rego']['cid'].'/archive/';
	$root = ROOT.$_SESSION['rego']['cid'].'/archive/';
	
	$baseName = $_SESSION['rego']['cid'].'_consent_letter_'.$_SESSION['rego']['year_'.$lang];
	
	$extension = 'pdf';		
	$filename = getFilename($baseName, $extension, $dir);
	
	// if(isset($_GET['a'])){
		// $mpdf->Output(iconv("UTF-8", "TIS-620",$dir.$filename),'F');
	// }
	$mpdf->Output($filename,'I');
	
	// if(isset($_GET['a'])){
	// 	include('save_to_documents.php');
	// }


















	
?>





















