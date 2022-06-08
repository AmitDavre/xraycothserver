<?php
	if(session_id()==''){session_start();}
	ob_start();
	include('../../../dbconnect/db_connect.php');
	include(DIR.'files/functions.php');
	include(DIR.'files/arrays_'.$_SESSION['rego']['lang'].'.php');
	
	$langVar = $_GET['la'];

	if($langVar == 'la4')
	{
		$langvalue = 'en';
	}
	else
	{
		$langvalue = 'th';
	}


	$sql_get_employee_data = "SELECT * FROM " .$cid."_employees WHERE emp_id = '".$_GET['id']."' ";
	if($result_get_employee_data = $dbc->query($sql_get_employee_data)){
		if($row_employee_data = $result_get_employee_data->fetch_assoc()){

			$emp_en_name = $row_employee_data['en_name'];
			$emp_title = $row_employee_data['title'];
			$emp_entity = $row_employee_data['entity'];
		}
	}	


	$sql3244 = "SELECT * FROM rego_consent_letter ";
	if($reasdasds = $dbx->query($sql3244)){
		if($rosaddsw = $reasdasds->fetch_assoc()){

				$block1 = $rosaddsw[$langvalue.'_identification'];
				$block2 = $rosaddsw[$langvalue.'_body_text'];
				$block3 = $rosaddsw[$langvalue.'_reference'];
		}
	}		

	// get default logo
	$sql32444 = "SELECT * FROM " .$cid."_entities_data WHERE ref= '".$emp_entity."'";
	if($reasdasds4 = $dbc->query($sql32444)){
		if($rosaddsw4 = $reasdasds4->fetch_assoc()){

			$logoimage = $rosaddsw4['logofile'];
			$en_compname = $rosaddsw4['en_compname'];
			$website_status = $rosaddsw4['website_status'];
			$website_name = 'https://www.'.$rosaddsw4['website_name'];
			$contact_info_status = $rosaddsw4['contact_info_status'];
			$c_company = $rosaddsw4['c_company'];
			$c_address = $rosaddsw4['c_address'];
			$c_function = $rosaddsw4['c_function'];
			$c_telephone = $rosaddsw4['c_telephone'];
			$c_email = $rosaddsw4['c_email'];
		}
	}	


	// get the selected employee information 

	


	$datevalue = date('d-m-Y');
	// block1
	$text1 = str_replace('<b>{EMPLOYEE_NAME}</b>', '<b>'.$emp_en_name.'</b>', $block1);
	$text1 = str_replace('<b>{COMPANY_NAME}</b>', '<b>'.$en_compname.'</b>', $text1);
	$text1 = str_replace('<b>{GENDER_VALUE}</b>', '<b>'.$title[$emp_title].'</b>', $text1);




	

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

		if($langvalue == 'en')
		{
			$text3 = str_replace('<span style="text-align: justify;">Company :  <span id="restrict11"><b>{COMPANY_VALUE_NAME}</b></span></span>', '', $block3);
			$text3 = str_replace('<span style="text-align: justify;">Address:  <span id="restrict12"><b>{ADDRESS_VALUE}</b></span></span>', '', $text3);
			$text3 = str_replace('<span style="text-align: justify;">Function:  <span id="restrict13"><b>{FUNCTION_VALUE}</b></span></span>', '', $text3);
			$text3 = str_replace('<span style="text-align: justify;">Telephone:  <span id="restrict14"><b>{TELEPHONE_VALUE}</b></span></span>', '', $text3);
			$text3 = str_replace('<div style="text-align: justify; margin-left: 25px;">Email :  <span id="restrict15"><b>{EMAIL_VALUE}</b></span></div>', '', $text3);
		}
		else if($langvalue == 'th')
		{
			$text3 = str_replace('<span style="text-align: justify;">บริษัท :  <span id="restrict21"><b>{COMPANY_VALUE_NAME}</b></span></span>', '', $block3);
			$text3 = str_replace('<span style="text-align: justify;">ที่อยู่ :  <span id="restrict22"><b>{ADDRESS_VALUE}</b></span></span>', '', $text3);
			$text3 = str_replace('<span style="text-align: justify;">ฝ่าย :  <span id="restrict23"><b>{FUNCTION_VALUE}</b></span></span>', '', $text3);
			$text3 = str_replace('<span style="text-align: justify;">โทรศัพท์ :  <span id="restrict24"><b>{TELEPHONE_VALUE}</b></span></span>', '', $text3);
			$text3 = str_replace('<div style="text-align: justify; margin-left: 25px;">อีเมล์ :  <span id="restrict25"><b>{EMAIL_VALUE}</b></span></div>', '', $text3);
		}
		
	}




	if($_GET['d'] == '')
	{

		// replace date
		if($langvalue == 'en')
		{
			$text3 = str_replace('<span id="restrict26"><b>{DATE_VALUE}</b></span>', '', $text3);
		}
		else if($langvalue == 'th')
		{
			$text3 = str_replace('<span id="restrict27"><b>{DATE_VALUE}</b></span>', '', $text3);
		}
	}
	else 
	{
		// show date 
		$text3 = str_replace('<b>{DATE_VALUE}</b>', '<b>'.$_GET['d'].'</b>', $text3);
	}
	



	if($_GET['w'] == '1')
	{
		if($website_status == '1')
		{


			$optiontext = preg_replace('/(.*){OPTION_1_START}(.*){OPTION_2_END}(.*)/s', '\2', $text3);
			$optiontext1 = preg_replace('/(.*){OPTION_2_START}(.*){OPTION_2_END}(.*)/s', '\2', $text3);

			$text3 = str_replace($optiontext, $optiontext1.'</b>', $text3);
			$text3 = str_replace('<b>{OPTION_1_START}</b>', '', $text3);
			$text3 = str_replace('{OPTION_2_END}', '', $text3);

		}
		else
		{

			$optiontext = preg_replace('/(.*){OPTION_1_START}(.*){OPTION_2_END}(.*)/s', '\2', $text3);
			$optiontext1 = preg_replace('/(.*){OPTION_1_START}(.*){OPTION_1_END}(.*)/s', '\2', $text3);


			$text3 = str_replace($optiontext, $optiontext1.'</b>', $text3);
			$text3 = str_replace('<b>{OPTION_1_START}</b>', '', $text3);
			$text3 = str_replace('{OPTION_2_END}', '', $text3);
		}

		$text3 = str_replace('<b>{WEBSITE_LINK}</b>', '<b>'.$website_name.'</b>', $text3);

	}
	else
	{

			$optiontext = preg_replace('/(.*){OPTION_1_START}(.*){OPTION_2_END}(.*)/s', '\2', $text3);
			$optiontext1 = preg_replace('/(.*){OPTION_1_START}(.*){OPTION_1_END}(.*)/s', '\2', $text3);


			$text3 = str_replace($optiontext, $optiontext1.'</b>', $text3);
			$text3 = str_replace('<b>{OPTION_1_START}</b>', '', $text3);
			$text3 = str_replace('{OPTION_2_END}', '', $text3);
	
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


	// strip empty representtaive columns 

	if($c_company == '')
	{
		if($langVar == 'en')
		{
			$text3 = str_replace('<span style="text-align: justify;">Company :  <span id="restrict11"><b></b></span></span>', ' ', $text3);
		}
		else if($langVar == 'th')
		{
			$text3 = str_replace('<span style="text-align: justify;">บริษัท :  <span id="restrict21"><b></b></span></span>', ' ', $text3);
		}
	}	
	if($c_address == '')
	{

		if($langvalue == 'en')
		{
			$text3 = str_replace('<span style="text-align: justify;">Address:  <span id="restrict12"><b></b></span></span>', ' ', $text3);
		}
		else if($langvalue == 'th')
		{
			$text3 = str_replace('<span style="text-align: justify;">ที่อยู่ :  <span id="restrict22"><b></b></span></span>', ' ', $text3);
		}
	}
	if($c_function == '')
	{
		if($langvalue == 'en')
		{
			$text3 = str_replace('<span style="text-align: justify;">Function:  <span id="restrict13"><b></b></span></span>', ' ', $text3);
		}
		else if($langvalue == 'th')
		{
			$text3 = str_replace('<span style="text-align: justify;">ฝ่าย :  <span id="restrict23"><b></b></span></span>', ' ', $text3);
		}
	}
	if($c_telephone == '')
	{
		if($langvalue == 'en')
		{
			$text3 = str_replace('<span style="text-align: justify;">Telephone:  <span id="restrict14"><b></b></span></span>', ' ', $text3);
		}
		else if($langvalue == 'th')
		{
			$text3 = str_replace('<span style="text-align: justify;">โทรศัพท์ :  <span id="restrict24"><b></b></span></span>', ' ', $text3);
		}
	}	
	if($c_email == '')
	{
		if($langvalue == 'en')
		{
			$text3 = str_replace('<div style="text-align: justify; margin-left: 25px;">Email :  <span id="restrict15"><b></b></span></div>', ' ', $text3);
		}
		else if($langvalue == 'th')
		{
			$text3 = str_replace('<div style="text-align: justify; margin-left: 25px;">อีเมล์ :  <span id="restrict25"><b></b></span></div>', ' ', $text3);
		}
	}



	$html = '<html><body>';
	$html .= $imgfile;
	$html .= '<table><tr> <td>&nbsp;</td></tr></table>';
	$html .= $text1;
	$html .= $block2;
	$html .= '<table><tr> <td>&nbsp;</td></tr><tr> <td>&nbsp;</td></tr></table>';
	$html .= $text3;
	$html .= '</body></html>';	
	

	
	require_once(DIR."mpdf7/vendor/autoload.php");

	//class mPDF ([ string $mode [, mixed $format [, float $default_font_size [, string $default_font [, float $margin_left , float $margin_right , float $margin_top , float $margin_bottom , float $margin_header , float $margin_footer [, string $orientation ]]]]]])
	$mpdf = new mPDF('UTF-8', 'A4-P', 13, 'leelawadee', 10, 10, 10, 10, 0, 0);
	$mpdf->SetTitle($compinfo[$lang.'_compname'].' ('.strtoupper($_SESSION['rego']['cid']).') '.$_SESSION['rego']['cur_year']);
	
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
		$mpdf->Output(iconv("UTF-8", "TIS-620",$dir.$filename),'F');
	// }
	$mpdf->Output($filename,'I');
	
	// if(isset($_GET['a'])){
	// 	include('save_to_documents.php');
	// }


















	
?>





















