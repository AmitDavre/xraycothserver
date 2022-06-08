<?
	if(session_id()==''){session_start(); ob_start();}
	//$err_msg = "";

	if(empty($_REQUEST['cid']) || empty($_REQUEST['username']) || empty($_REQUEST['email'])){
		echo 'empty'; exit;
	}
	$protocol = strtolower(substr($_SERVER["SERVER_PROTOCOL"],0,strpos( $_SERVER["SERVER_PROTOCOL"],'/'))).'://';
	//define('URL', $protocol.$_SERVER['HTTP_HOST'].'/'.dirname(substr($_SERVER['SCRIPT_FILENAME'], strlen( $_SERVER[ 'DOCUMENT_ROOT' ] ) )).'/');
	if($_SERVER['SERVER_NAME']=='localhost'){
		$root = $protocol.$_SERVER['HTTP_HOST'].'/hr/e/index.php';
	}else{
		$root = $protocol.$_SERVER['HTTP_HOST'].'/demo/e/index.php';
	}
	//var_dump($root); exit;
	//include('../../inc/config.php');
	//$cid = strtolower($_REQUEST['cid']);
	include('../../dbconnect/db_connect.php');
	include(DIR.'files/arrays_'.$_SESSION['xhr']['lang'].'.php');
	$lang = $_SESSION['xhr']['lang'];
	$lng_name = $lang.'_name';
	$lng = getLangVariables($lang);
	
	$my_dbcname = $prefix.$cid;	
	$dbc = new mysqli($my_database,$my_username,$my_password,$my_dbcname);
	mysqli_set_charset($dbc,"utf8");
	
	$template = getEmailTemplate($dbc, $lang.'_forgot_pass', $cid);
	
	$password = randomPassword();
	$pass = hash('sha256', $password);
	$dbname = $cid.'_employees';
	
	$sql = "SELECT emp_id, en_name, th_name, username, personal_email FROM $dbname WHERE username = '".$_REQUEST['username']."' AND personal_email = '".$_REQUEST['email']."'";
	if($res = $dbc->query($sql)){ 
		if($res->num_rows == 0){
			echo 'wrong'; exit;
		}else{
			$row = $res->fetch_object();
			if($lang=='en'){$name = $row->en_name;}else{$name = $row->th_name;}
			$email = $row->personal_email;
			$emp_id = $row->emp_id;
				
			//var_dump($template); //exit;
			$temp = nl2br($template['body']);
			
			$temp = str_replace('{RECIPIENT}',$name, $temp);
			$temp = str_replace('{NEW_PASSWORD}',$password, $temp);
			$temp = str_replace('{CLICK_HERE_APP_LINK}', "<a href='".$root."'>click here</a>", $temp);

			require '../../PHPMailer/PHPMailerAutoload.php';
			$mail_subject = $template['sub'];
			
			$body = "<html>
					<head>
					<style type='text/css'>
						body, th, td {font-family:Calibri, Verdana;} 
						a:link, a:visited {color: #a00;text-decoration:none;}
					</style>
					<meta http-equiv='Content-Type' content='text/html; charset=utf-8' />
					</head>
					<body>
						<table border='0'>
						<tr>
							<td>";
			
			$body .= $temp;
			
			$body .= "</td>
					 	</tr>
					</table>
					</body></html>";
			
			$mail = new PHPMailer;
			$mail->CharSet = 'UTF-8';
			$mail->From = $from_email;
			$mail->FromName = $compinfo[$lang.'_compname'];
			$mail->addAddress($email, $name);
			//$mail->addBCC('info@xrayhr.com','New password Request');
			$mail->addReplyTo($reply_email, $compinfo[$lang.'_compname']);
			$mail->isHTML(true);                                  
			$mail->Subject = $mail_subject;
			$mail->Body = $body;
			$mail->WordWrap = 100;                                
			
			$sql = "UPDATE $dbname SET password = '".$dbc->real_escape_string($pass)."' WHERE emp_id = '".$emp_id."'";
			if(!$dbc->query($sql)){
				echo 'error';
			}else{
				if(!$mail->send()) {
					echo 'nocon';
				}else{
					echo 'success';
				}
			}
		}
	}else{
		echo 'error';
		//echo mysqli_error($dbc);
	}
	
	//var_dump($_REQUEST); exit;
?>








