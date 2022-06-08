<?

	if(session_id()==''){session_start();}
	ob_start();
	include('../../../dbconnect/db_connect.php');

	
	$dir = DIR.$cid.'/time/selfies/';
  if(!file_exists($dir)){
   	mkdir($dir);
	}
	
	$img = $_REQUEST['image'];
	$dateval = $_REQUEST['date'];
	$inoutvalue = $_REQUEST['scan_value'];
	$img = str_replace('data:image/png;base64,', '', $img);
	$img = str_replace(' ', '+', $img);
	$fileData = base64_decode($img);
	//saving
	$fileName = date('d-m-Y_His').'.png';
	if(file_put_contents($dir.$fileName, $fileData)){
		$sql = "UPDATE ".$cid."_attendance SET ".$_REQUEST['img']." =  '".$fileName."' WHERE id = '".$_REQUEST['id']."'";

		// update metascandata table 
			$sql1 = "UPDATE ".$cid."_metascandata SET picture =  '".$fileName."' WHERE id = '".$_REQUEST['metaid']."'";
			$dbc->query($sql1);


		if($dbc->query($sql)){
			ob_clean();
				


				if($_REQUEST['contact'] != 'contact')
				{

				// send confirmation email for clock in 


		// get employee name 
		// get clock time 
		// get  company name 

		if($res_getemp = $dbc->query("SELECT * FROM ".$cid."_employees WHERE emp_id = '".$_SESSION['rego']['emp_id']."'")){
			if($row_getemp = $res_getemp->fetch_assoc()){
				$employee_name  = $row_getemp['en_name'];
			}
			else
			{
				$employee_name  = '-';
			}
		}

		// get contact name 

		if($res_getloc = $dbc->query("SELECT * FROM ".$cid."_location WHERE code = '".$_REQUEST['contact']."'")){
			if($row_getloc= $res_getloc->fetch_assoc()){
				$contact_name_var  = $row_getloc['contact_name'];
				$contact_email  = $row_getloc['contact_email'];
				$loc_name  = $row_getloc['loc_name'];
			}
			else
			{
				$contact_name_var = '-';
			}
		}


		// get company name  

		$my_dbaname = $prefix.'admin';


		$dba = new mysqli($my_database,$my_username,$my_password,$my_dbaname);
		mysqli_set_charset($dba,"utf8");
		if($dba->connect_error) {
			echo'<p style="width:900px; margin:0 auto; margin-top:20px;" class="box_err">Error: ('.$dba->connect_errno.') '.$dba->connect_error.'<br>Please try again later or report this error to <a href="mailto:admin@regohr.com">admin@regohr.com</a></p>';
		}

		$sql102 = "SELECT * FROM rego_customers WHERE clientID = '".$_SESSION['rego']['cid']."'";

		if($res102 = $dba->query($sql102)){
			if($res102->num_rows > 0){
				if($row102 = $res102->fetch_assoc())
					{
						$company_name = $row102['th_compname'];
					}
					else
					{
						$company_name = '';
					}
			}
		}









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
	
	<tr ><td style="border:none;">Dear '.$contact_name_var.'</td> </tr>
	<tr><td style="border:none;">We noticed that our employee '.$employee_name.' has checked '.$inoutvalue.' your facilities at '.$loc_name.'.</td> </tr>
	<tr><td style="border:none;">Date and time clocked '.$inoutvalue.' : '.$dateval.'</td></tr>
	<tr><td style="border:none;"> We hope that everything went fine during our employee visit.  If you have any remarks to share with us please do so at email addres company</td></tr>
	<tr><td style="border:none;">With kind regards </td> </tr>
	<tr><td style="border:none;">The HR team from '.$company_name.'</td></tr>

	
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
		$mail->addAddress($contact_email, $_REQUEST['contact_name']); 
		$mail->isHTML(true);                                  
		$mail->Subject = 'Confirmation Email For Scan '.ucwords($inoutvalue).'';
		$mail->Body = $body;
		$mail->WordWrap = 100;
		if(!$mail->send()) {
			echo $mail->ErrorInfo;
		}
		else{
			echo 'success';
		}
	
	}
	else
	{
		echo 'success';
	}



	







		}else{
			ob_clean();
			echo mysqli_error($dbc);
		}



	}else{
		ob_clean();
		echo 'error';
	}















