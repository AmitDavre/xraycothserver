<?

	header("Access-Control-Allow-Origin: *");
	
	if(session_id()==''){session_start();}
	ob_start();
	
	$err_mail = 'info@regohr.com';
	$mainError = "";
	$prefix = '';
	if($_SERVER['SERVER_NAME'] == 'census'){
		ini_set('show_errors', 'on');
		error_reporting(E_ALL);
		ini_set('xdebug.var_display_max_depth', -1);
		ini_set('xdebug.var_display_max_children', -1);
		ini_set('xdebug.var_display_max_data', -1);
		$my_database = 'localhost';
		$my_username = 'root';
		$my_password = '';
		$demo = true;
		$prefix = 'payroll_';
	}elseif(strpos($_SERVER['SERVER_NAME'], 'xraydemo') !== false){
		$my_database = 'localhost';
		$my_username = 'xraydemo_wms';
		$my_password = 'Tinkerbell11';
		$demo = false;
		$prefix = 'xraydemo_';
	}elseif($_SERVER['SERVER_NAME'] == 'regothailand.com'){
		$my_database = 'localhost';
		$my_username = 'regothai';
		$my_password = 'tmGgpuCGHbjkUAek';
		$demo = false;
		$prefix = 'regothai_';
	}
	//var_dump($_SERVER['SERVER_NAME']);
	
	$my_dbxname = $prefix.'admin';
	$dbx = @new mysqli($my_database,$my_username,$my_password,$my_dbxname);
	if($dbx->connect_error) {
		echo'<div style="width:100%; margin:30px;"><b>Error :</b> ('.$dbx->connect_errno.') '.$dbx->connect_error.'<br>Please try again later or report this error to the Xray Administrator <a href="mailto:'.$err_mail.'">'.$err_mail.'</a></p>'; exit;
	}else{
		mysqli_set_charset($dbx,"utf8");
	}
	
	//var_dump($_REQUEST); 
	//var_dump('success'); 
	//exit;
	
	$error = false;
	$res = $dbx->query("SELECT clientID FROM customers ORDER BY id ASC");
	while($row = $res->fetch_object()){
		$ids[] = (int)$row->clientID;
	}
	for($id=reset($ids); in_array($id, $ids); $id++);
	$cid = $id;
	if(isset($_REQUEST['pass1'])){
		$_REQUEST['password'] = $_REQUEST['pass1'];
	}
	if(isset($_REQUEST['email'])){
		$_REQUEST['username'] = $_REQUEST['email'];
	}
	if(isset($_REQUEST['agent'])){
		$_REQUEST['agent'] = 'reg';
	}
	$password = $_REQUEST['password'];
	$_REQUEST['password'] = hash('sha256', $_REQUEST['password']);
	$_REQUEST['joiningdate'] = date('d-m-Y');
	$_REQUEST['period_start'] = date('d-m-Y');
	$_REQUEST['period_end'] = date('d-m-Y', strtotime('+30 day',strtotime(date('d-m-Y'))));
	$year = date('Y');

	//var_dump($cid); //exit;
	//var_dump($_REQUEST); exit;
	
	include("create_database.php");
	
	$sql = "INSERT IGNORE INTO company_users (username, password, cid, type, firstname, lastname, name, phone, img, status) VALUES ("; 
		$sql .= "'".$dbx->real_escape_string(strtolower($_REQUEST['username']))."',";
		$sql .= "'".$dbx->real_escape_string($_REQUEST['password'])."',";
		$sql .= "'".$dbx->real_escape_string($cid)."',";
		$sql .= "'".$dbx->real_escape_string('sys')."',";
		$sql .= "'".$dbx->real_escape_string($_REQUEST['firstname'])."',";
		$sql .= "'".$dbx->real_escape_string($_REQUEST['lastname'])."',";
		$sql .= "'".$dbx->real_escape_string($_REQUEST['firstname'].' '.$_REQUEST['lastname'])."',";
		$sql .= "'".$dbx->real_escape_string($_REQUEST['phone'])."',";
		$sql .= "'".$dbx->real_escape_string('images/profile_image.jpg')."',";
		$sql .= "'".$dbx->real_escape_string(1)."')";
	//echo $sql; exit;
	if(!$dbx->query($sql)){
		$error = true;
		ob_clean();
		echo mysqli_error($dbx); exit;
	}
	//echo $error; exit;
	
	if(!$error){
		
	$sql = "INSERT INTO customers (clientID, th_compname, en_compname, firstname, lastname, name, phone, email, joiningdate, period_start, period_end, version, agent, status) VALUES (
		'".$dbx->real_escape_string($cid)."', 
		'".$dbx->real_escape_string($_REQUEST['company'])."', 
		'".$dbx->real_escape_string($_REQUEST['company'])."', 
		'".$dbx->real_escape_string($_REQUEST['firstname'])."', 
		'".$dbx->real_escape_string($_REQUEST['lastname'])."', 
		'".$dbx->real_escape_string($_REQUEST['firstname'].' '.$_REQUEST['lastname'])."', 
		'".$dbx->real_escape_string($_REQUEST['phone'])."', 
		'".$dbx->real_escape_string(strtolower($_REQUEST['username']))."', 
		'".$dbx->real_escape_string($_REQUEST['joiningdate'])."', 
		'".$dbx->real_escape_string($_REQUEST['period_start'])."', 
		'".$dbx->real_escape_string($_REQUEST['period_end'])."', 
		'".$dbx->real_escape_string('free')."', 
		'".$dbx->real_escape_string($_REQUEST['agent'])."', 
		'".$dbx->real_escape_string(1)."')"; 
		//echo $sql; exit;
		
		if(!$res = $dbx->query($sql)){
			ob_clean();
			mysqli_error($dbx); exit;
		}else{
			//exit;
			$protocol = 'http://';
			if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') { $protocol = 'https://';}

			$body = 'Dear '.$_REQUEST['firstname'].',

A new account has been created for you to login at '.$_REQUEST['company'].'

Username : '.$_REQUEST['username'].'
Password : '.$password.'

Please follow this link to login : '.$protocol.$_SERVER['SERVER_NAME'].'

Kind regards,

The REGO Team';
			//echo $body; exit;
			
			require DIR.'PHPMailer/PHPMailerAutoload.php';	
			$mail_subject = 'New user Login for '.$_REQUEST['firstname'].' '.$_REQUEST['lastname'];
			$body = '<html>
							<head>
							<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
							</head>
							<body style="font-size:16px">'.nl2br($body).'</body>
						</html>';
			
			$mail = new PHPMailer;
			$mail->CharSet = 'UTF-8';
			$mail->From = 'info@regohr.com';//$compinfo['admin_mail'];
			$mail->FromName = 'REGO HR Admin';
			$mail->addAddress(strtolower($_REQUEST['username']), $_REQUEST['firstname'].' '.$_REQUEST['lastname']); 
			$mail->isHTML(true);                                  
			$mail->Subject = $mail_subject;
			$mail->Body = $body;
			$mail->WordWrap = 100;
			
			if(!$mail->send()) {
				ob_clean();
				echo $mail->ErrorInfo;
				exit;
			}
			$_SESSION['mob']['cid'] = $cid;
			$_SESSION['mob']['username'] = $_REQUEST['username'];
			$_SESSION['mob']['type'] = 'sys';
			$_SESSION['mob']['emp_id'] = '';
			$_SESSION['mob']['name'] = $_REQUEST['firstname'].' '.$_REQUEST['lastname'];
			$_SESSION['mob']['img'] = 'images/profile_image.jpg';
			$_SESSION['mob']['timestamp'] = time();
			
			ob_clean();
			echo 'success';
		}

	}else{
		echo 'Sorry but something went wrong, please contact the site administrator';
	}













