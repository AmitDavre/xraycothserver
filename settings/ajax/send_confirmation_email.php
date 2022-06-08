<?
	
	if(session_id()==''){session_start();}
	ob_start();

	include('../../dbconnect/db_connect.php');



	$scan_value = $_REQUEST['scan_value'];

	if($scan_value == 'in')
	{
		$scanvalue= 'in';
	}
	else if($scan_value == 'out')
	{
		$scanvalue= 'out';
	}


	$folderName = 'wl';
	$fileName = 'ping_external_location.php';
	$id =base64_encode(base64_encode($_REQUEST['print_id']));
	$encodeCid =base64_encode(base64_encode($_SESSION['rego']['cid']));
	// $urlLink =  ROOT.''.$folderName.'/'.$fileName.'?id='.$id.'&cid='.$encodeCid.'' ;


	// UPDATE CONIFRMATION COLUMN 


	if($scan_value == 'in')
	{
		$sql_update = "UPDATE ".$_SESSION['rego']['cid']."_location SET scan_in_confirmation = '1' WHERE loc_id = '".$_REQUEST['print_id']."'";
	}
	else
	{
		$sql_update = "UPDATE ".$_SESSION['rego']['cid']."_location SET scan_out_confirmation = '1' WHERE loc_id = '".$_REQUEST['print_id']."'";
	}

	$dbc->query($sql_update);




	$urlLink =  '';

	// // update the link-expire back to null or 0 

	// $sql_update = "UPDATE ".$_SESSION['rego']['cid']."_employees SET ping_expire = '0' WHERE emp_id = '".$_REQUEST['employee_id']."'";

	// $dbc->query($sql_update);



	// $urlLink  =  $

	
		// Send email ---------------------------------------------------------------------------------------------------------
		require DIR.'PHPMailer/PHPMailerAutoload.php';	

		$body='<html xmlns="http://www.w3.org/1999/xhtml">
            <head>
           <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
       <!--[if !mso]><!-->
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<!--<![endif]-->
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title></title>
<style type="text/css">
* {
	-webkit-font-smoothing: antialiased;
}
body {
	Margin: 0;
	padding: 0;
	min-width: 100%;
	font-family: Arial, sans-serif;
	-webkit-font-smoothing: antialiased;
	mso-line-height-rule: exactly;
}
table {

	border-spacing: 0;
	color: #333333;
	font-family: Arial, sans-serif;
}
table#abcc tr,table#abcc td,table#abcc th {
    border: 1px solid #d6c0c0;
	padding:10px;

}
img {
	border: 0;
}
.wrapper {
	width: 100%;
	table-layout: fixed;
	-webkit-text-size-adjust: 100%;
	-ms-text-size-adjust: 100%;
}
.webkit {
	max-width: 100%;
}
.outer {
	Margin: 0 auto;
	width: 100%;
	max-width: 100%;
}
.full-width-image img {
	width: 100%;
	max-width: 100%;
	height: auto;
}
.inner {
	padding: 10px;
}
p {
	Margin: 0;
	padding-bottom: 10px;
}
.h1 {
	font-size: 21px;
	font-weight: bold;
	Margin-top: 15px;
	Margin-bottom: 5px;
	font-family: Arial, sans-serif;
	-webkit-font-smoothing: antialiased;
}
.h2 {
	font-size: 18px;
	font-weight: bold;
	Margin-top: 10px;
	Margin-bottom: 5px;
	font-family: Arial, sans-serif;
	-webkit-font-smoothing: antialiased;
}
.one-column .contents {
	text-align: left;
	font-family: Arial, sans-serif;
	-webkit-font-smoothing: antialiased;
}
.one-column p {
	font-size: 14px;
	Margin-bottom: 10px;
	font-family: Arial, sans-serif;
	-webkit-font-smoothing: antialiased;
}
.two-column {
	text-align: center;
	font-size: 0;
}
.two-column .column {
	width: 100%;
	max-width: 300px;
	display: inline-block;
	vertical-align: top;
}
.contents {
	width: 100%;
}
.two-column .contents {
	font-size: 14px;
	text-align: left;
}
.two-column img {
	width: 100%;
	max-width: 280px;
	height: auto;
}
.two-column .text {
	padding-top: 10px;
}
.three-column {
	text-align: center;
	font-size: 0;
	padding-top: 10px;
	padding-bottom: 10px;
}
.three-column .column {
	width: 100%;
	max-width: 200px;
	display: inline-block;
	vertical-align: top;
}
.three-column .contents {
	font-size: 14px;
	text-align: center;
}
.three-column img {
	width: 100%;
	max-width: 180px;
	height: auto;
}
.three-column .text {
	padding-top: 10px;
}
.img-align-vertical img {
	display: inline-block;
	vertical-align: middle;
}
@media only screen and (max-device-width: 480px) {
table[class=hide], img[class=hide], td[class=hide] {
	display: none !important;
}
.contents1 {
	width: 100%;
}
.contents1 {
	width: 100%;
}

}

</style>
<!--[if (gte mso 9)|(IE)]>
	<style type="text/css">
		table {border-collapse: collapse !important;}
	</style>
	<![endif]-->
</head>

<body style="Margin:0;padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;min-width:100%;background-color:#f3f2f0;">
<center class="wrapper" style="width:100%;table-layout:fixed;-webkit-text-size-adjust:100%;-ms-text-size-adjust:100%;background-color:#f3f2f0;">
  <table  id="dfs" width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color:#f3f2f0;" bgcolor="#f3f2f0;">
    <tr>
      <td width="100%"><div class="webkit" style="max-width:100%;Margin:0 auto;"> 
          
          <!--[if (gte mso 9)|(IE)]>

						<table width="600" align="center" cellpadding="0" cellspacing="0" border="0" style="border-spacing:0" >
							<tr>
								<td style="padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;" >
								<![endif]--> 
          
          <!-- ======= start main body ======= -->
          <table class="outer" align="center" cellpadding="0" cellspacing="0" border="0" style="border-spacing:0;Margin:0 auto;width:100%;max-width:100%;">
            <tr>
              <td style="padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;"><!-- ======= start header ======= -->
                
                <table id="sa" border="0" width="100%" cellpadding="0" cellspacing="0"  >
                  <tr>
                    <td><table style="width:100%;" cellpadding="0" cellspacing="0" border="0">
                        <tbody>
                          <tr>
                            <td align="center">
                                <a href="https://www.regodemo.com/images/pkf_people.png" target="_blank" style="float: left; margin-left: 42%; margin-top: 20px;margin-bottom: 20px;"><img src="https://www.regodemo.com/images/pkf_people.png" alt="" width="120" height="120" style="border-width:0; max-width:120px;height:auto; display:block" align="left"/></a>
                             </td>
                          </tr>
                        </tbody>
                      </table></td>
                  </tr>
                </table>
                
                <!-- ======= end header ======= --> 
                
                <!-- ======= start hero ======= -->
                
                <table id="d" class="one-column" border="0" cellpadding="0" cellspacing="0" width="100%" style="border-spacing:0; border-left:1px solid #e8e7e5; border-right:1px solid #e8e7e5; border-bottom:1px solid #e8e7e5; border-top:1px solid #e8e7e5" bgcolor="#FFFFFF">
                  <tr>
                    <td background="#41abe2" bgcolor="#41abe2" width="600" height="50" valign="top" align="center" style=""> 
                        <p style="color: #ffffff; font-size: 21px;text-align: center;font-family: Verdana, Geneva, sans-serif; line-height: 61px; margin: 2px;  padding: 0; float:left; margin-left:19px;">CONFIRMATION EMAIL FOR SCAN  </p>
                      </td>
                  </tr>
                </table>
				
				 </td>
                  </tr>
				 
				<tr>
				 <td class="abc" style="padding: 20px 36px;  background: white;"> 
                <table id="d" class="one-column" border="0" cellpadding="0" cellspacing="0" width="100%" style="border-spacing:0;" bgcolor="#FFFFFF">
                  <tr>
                    <td > 
                        <p style="color:#011e40; font-size: 14px; text-align: left; font-family: Verdana, Geneva, sans-serif; line-height: 45px; margin: -2px; padding: 0; padding: 0px 13px;"></p><br>

                      </td>
                  </tr>
                </table>

          
                <table id="abcc" border="0" cellpadding="0" cellspacing="0" width="100%" style="#e8e7e5" bgcolor="#FFFFFF">
      <thead>
		
		
		<tr>
		
			
		</tr>
	</thead>
	
	<tbody>
	
	<tr ><td style="border:none;">Dear  contact name</td> </tr>
	<tr><td style="border:none;">We noticed that our employee <employee name> has checked in your facilities.</td> </tr>
	<tr><td style="border:none;">Date and time clock  in : date time clock in</td></tr>
	<tr><td style="border:none;">Date and time clock out : date time clock out</td></tr>
	<tr><td style="border:none;"> We hope that everything went fine during our employee visit.  If you have any remarks to share with us please do so at email addres company</td></tr>
	<tr><td style="border:none;">With kind regards </td> </tr>
	<tr><td style="border:none;">The HR team from company name</td></tr>

	
 <tr>



</tbody>
	
                             </table><br>
               </td>
            </tr>
			<tr style="background-color:#ffffff;">
			<td style="text-align:center; padding:0px 10px 0px 10px;" >
			<img src="https://regodemo.com/images/loc.png" alt="" width="22" height="22" style="border-width:0; max-width:12px;height:auto; float: none; margin-top: 2px;" align="left"/> 222/75 Moo 7, Nongprue, Banglamung, Chonburi 20150
			</td>  
      </tr>
 <tr style="background-color:#ffffff;"> 			
			<td style="text-align:center; padding:6px 10px 6px 10px;" >
			<img src="https://regodemo.com/images/phone.png" alt="" width="22" height="22" style="border-width:0; max-width:12px;height:auto; float: none;  margin-top: 3px; " align="left"/> +66 (0)6 139 184 77   <img src="https://regodemo.com/images/mail.png" alt="" width="22" height="22" style="border-width:0; max-width:16px;max-height:15px;float: none; margin-left: 7px; margin-top: 2px; height: auto !important;" align="left"/> info@regohr.com
</td>			
</tr>
 <tr style="background-color:#ffffff;">
<td style="text-align:center; padding:0px 10px 10px 10px;" >
			<img src="https://regodemo.com/images/web.png" alt="" width="22" height="22" style="border-width:0; max-width:12px;height:auto;; float: none; margin-top: 4px;" align="left"/> <i class="fa fa-map-marker" aria-hidden="true"></i> Visit us at <a href="https://www.regothailand.com">www.regothailand.com</a>
			</td>
			</tr>
			
			<tr>
			<td>
			<table id="d" class="one-column" border="0" cellpadding="0" cellspacing="0" width="100%" style="border-spacing:0; border-left:1px solid #e8e7e5; border-right:1px solid #e8e7e5; border-bottom:1px solid #e8e7e5; border-top:1px solid #e8e7e5" bgcolor="#FFFFFF">
                  <tr> 
                    <td background="#41abe2" bgcolor="#41abe2" width="600" height="8" valign="top" align="center" style=""> 
                        <p style="color: #ffffff; font-size: 25px;text-align: center;font-family: Verdana, Geneva, sans-serif; line-height: 90px; margin: 2px;  padding: 0; float:left; margin-left:19px;">
                          </p>
                     </td>
                  </tr>
                </table>
			</td>
		</tr>
          </table>
		 
        </div></td>
    </tr>
  </table>
</center>
</body>
</html>';

	
	
		$mail = new PHPMailer;
		$mail->CharSet = 'UTF-8';
		$mail->From = 'info@xray.co.th';
		$mail->FromName = 'Developer Test';
		$mail->addAddress($_REQUEST['contact_email'], $_REQUEST['contact_name']); 
		$mail->isHTML(true);                                  
		$mail->Subject = 'Confirmation Email For Scan In ';
		$mail->Body = $body;
		$mail->WordWrap = 100;
		if(!$mail->send()) {
			echo $mail->ErrorInfo;
		}
		else{
			echo 'success';
		}
	



	
	
	
	
	
	
	
	
