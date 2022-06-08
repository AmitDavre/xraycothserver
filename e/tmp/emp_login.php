<?
	$err_msg = "";
	//var_dump($_SESSION);
	
	if(isset($_SESSION['xhr']['timestamp']) && $_SESSION['xhr']['timestamp'] == 0){
		/*if(isset($_SESSION['xhr']['logid'])){
			$sql = "UPDATE xhr_logfiles SET time_out = '".date('Y-m-d H:i:s')."' WHERE id = '".$_SESSION['xhr']['logid']."'";
			$res = $dba->query($sql);
		}*/
		unset($_SESSION['xhr'], $_SESSION['payroll']);
		$err_msg = '<div class="msg_alert">'.$lng['logtimeexpired'].'</div>';
	}
	
	if(isset($_POST['login'])){
		//$password = hash('sha256', 'www'); 
		//var_dump('login');
		$ip = getRealUserIp();
		if(!empty($_POST['clientID']) && !empty($_POST['username']) && !empty($_POST['password'])){
			$_POST['clientID'] = strtolower($_POST['clientID']);
			$my_dbcname = $prefix.$_POST['clientID'];	
			$dbc = new mysqli($my_database,$my_username,$my_password,$my_dbcname);
			mysqli_set_charset($dbc,"utf8");
			$password = hash('sha256', $_POST['password']); 
			$dbname = $_POST['clientID']."_employees";
			
				$sql = "SELECT * FROM $dbname WHERE username = '".$_POST['username']."' AND password = '$password'";
				if($res = $dbc->query($sql)){
					if($res->num_rows > 0){
						$row = $res->fetch_object();
						if($row->allow_login != 'Y'){
							//$err_msg = '<div class="msg_alert">This account ('.strtoupper($_POST['clientID']).') is suspended.<br>Please contact X-RAY HR.</div>';
							$err_msg = '<div class="msg_alert">You have no permission to log in.<br>Please contact your suppervisor.</div>';
						}else{
							unset($_SESSION['xhr'], $_SESSION['payroll']);
							$_SESSION['xhr']['cid'] = strtolower($_POST['clientID']);
							$_SESSION['xhr']['id'] = $row->emp_id;
							$_SESSION['xhr']['username'] = $row->username;
							$_SESSION['xhr']['name'] = $row->en_name;
							$_SESSION['xhr']['email'] = $row->personal_email;
							$_SESSION['xhr']['phone'] = $row->personal_phone;
							if(!empty($row->image)){
								$_SESSION['xhr']['img'] = $row->image.'?'.time();
							}else{
								$_SESSION['xhr']['img'] = './images/profile_image.jpg';
							}
							$_SESSION['xhr']['timestamp'] = time();
							//$_SESSION['xhr']['level'] = $row->level;
							//$_SESSION['xhr']['role'] = $row->emp_role;
							//$_SESSION['xhr']['access'] = 'm';
							//$_SESSION['xhr']['timestamp'] = time();
							//$_SESSION['xhr']['lang'] = getXrayLanguage();
	
							setcookie('CID', $_POST['clientID'], time()+31556926 ,'/');
							/*$sql = "INSERT INTO xhr_logfiles (cid, type, name, user_id, time_in, ip) VALUES (
								'".$_POST['clientID']."', 
								'Admin', 
								'".$row->name_th." (".$row->name_en.")', 
								'".$row->user_id."', 
								'".date('Y-m-d H:i:s')."', 
								'".$ip."')";
							$res = $dba->query($sql);*/
							//$_SESSION['xhr']['logid'] = $dba->insert_id;			
							//var_dump($_SESSION['xhr']); exit;
							//$url = 'index.php?mn=2';
							//if($_SESSION['xhr']['role'] == '1'){ $url = './index.php?mn=3&sm=300&id='.$_SESSION['xhr']['id'];}
							//if($row->allow_login == 'Y' && $row->log_status == 1){ $url = './index.php?mn=3&id='.$_SESSION['xhr']['id'];}
							//var_dump($_SESSION['xhr']['role']); var_dump($url); exit;
							header('location: index.php?mn=2');
							exit;
						}
					}else{
						$err_msg = '<div class="box_err">'.$lng['Wrong Username or Password'].'</div>';
					}
				}else{
					$err_msg = '<div class="box_err">Error: '.mysqli_error($dbc).'</div>';
				}
			}else{
				$err_msg = '<div class="msg_error">'.$lng['Please fill in all the fields'].'</div>';
			}
						
		}
	
	if(isset($_COOKIE['CID']) && !isset($_POST['clientID'])) {
		$_POST['clientID'] = strtolower($_COOKIE['CID']);
	}
	// 10003 = 3300400709092
	// 10009 = 3361200144092
	//$_POST['username'] = '3361200144092';
	//$_POST['password'] = 'pandora8';
	//$_POST['username'] = '3361200144092';
	//$_POST['password'] = 'www';
	//var_dump($_POST['clientID']);
?>

	<div class="logform">
		<h2><?=$lng['Login to our secure server']?></h2>
		<div style="padding:10px 30px 30px 30px;">
		<span style="color:#a00; font-size:13px; font-weight:600"><?=$err_msg?></span>
		<? //var_dump($_POST);//echo $dbname; //echo hash('sha256', 'pandora8'); 
		if(isset($_GET['kkk'])){
			//$t = substr($_GET['k'],-10);
			//var_dump(date('d-m-Y @ H:i:s',(int)$t));
			//var_dump(substr($_GET['k'],0,-10));
			//var_dump($_GET['u']);
		
		}

		//$t = '1498110076';//strtotime('+1 days');
		//$k = '7c5427db213e861221cc5a1f79ca092eab1e36824d05f0509c7aa4fa79ab4faa'.'1498110076';//hash('sha256', randomPassword());
		//echo '<a href="//localhost/newXray/xhr0100/index.php?mn=11&k='.$k.'&u='.'100'.'&t='.$t.'">Login</a>';
 ?>
		<form name="logform" class="sform" id="logform" onsubmit="return xvalidateLogform(this)" method="post" style="padding-top:0px;">
		
			<label><?=$lng['Company ID']?> <i class="man"></i></label>
			<input name="clientID" id="clientID" type="text" value="<? if(isset($_POST['clientID'])){echo $_POST['clientID'];} ?>" />

			 <label><?=$lng['Username']?> <i class="man"></i></label>
			 <input name="username" id="username" type="text" value="<? if(isset($_POST['username'])){echo $_POST['username'];} ?>" />
			 
			 <label><?=$lng['Password']?> <i class="man"></i></label>
			 <input name="password" id="password" type="password" value="<? if(isset($_POST['password'])){echo $_POST['password'];} ?>" />
			 
			 <button style="margin-top:15px; float:left" name="login" type="submit" class="btn btn-primary btn-sm"><i class="fa fa-sign-in"></i>&nbsp; <?=$lng['Log-in']?></button>
			 <a style="margin-top:20px; float:right" data-toggle="modal" data-target="#forgotModal" class="btn btn-default btn-xs"><?=$lng['Forgot password']?></a>
			 <div class="clear"></div>
		</form>
		</div>
		<div class="clear"></div>
	</div>












