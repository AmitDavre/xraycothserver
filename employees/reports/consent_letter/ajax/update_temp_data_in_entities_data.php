<?
	if(session_id()==''){session_start();}
	ob_start();
	include('../../../../dbconnect/db_connect.php');
	include(DIR.'files/functions.php');


;

    $sql3244 = "UPDATE ".$cid. "_sys_settings SET c_company_temp = '".$_REQUEST['field1']. "', c_address_temp = '".$_REQUEST['field2']. "', c_function_temp = '" . $_REQUEST['field3'] . "', c_telephone_temp = '" . $_REQUEST['field4']. "', c_email_temp = '" . $_REQUEST['field5'] . "' WHERE id= '1' ";

    $dbc->query($sql3244);

		
	



ob_clean();
echo "success";
exit;
