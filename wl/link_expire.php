<?php 

// $employeeId = base64_decode(base64_decode($_GET['emp_id']));
// $cId 		= base64_decode(base64_decode($_GET['cid']));

// function encrypt_decrypt($string, $action = 'encrypt')
// {
//     $encrypt_method = "AES-256-CBC";
//     $secret_key = 'AA74CDCC2BBRT935136HH7B63C27'; // user define private key
//     $secret_iv = '5fgf5HJ5g27'; // user define secret key
//     $key = hash('sha256', $secret_key);
//     $iv = substr(hash('sha256', $secret_iv), 0, 16); // sha256 is hash_hmac_algo
//     if ($action == 'encrypt') {
//         $output = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
//         $output = base64_encode($output);
//     } else if ($action == 'decrypt') {
//         $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
//     }
//     return $output;
// }
 


// echo "Your Encrypted password is = ". $pwd = encrypt_decrypt('21lov?=rwr..0/', 'encrypt');
// echo "Your Decrypted password is = ". encrypt_decrypt($pwd, 'decrypt');


// die();
// $decrypted_id = preg_replace(sprintf('/%s/', $salt), '', $decrypted_id_raw);




?> 


<!DOCTYPE html>
<html lang="en-us">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, maximum-scale=1">
		<meta name="robots" content="noindex, nofollow">
		<title><?=$www_title?></title>
	
		<link rel="icon" type="image/png" sizes="192x192" href="https://regodemo.com/assets/images/192x192.png">
		<link rel="icon" type="image/png" sizes="32x32" href="https://regodemo.com/assets/images/favicon-32x32.png">
		<link rel="icon" type="image/png" sizes="96x96" href="https://regodemo.com/assets/images/favicon-96x96.png">
		<link rel="icon" type="image/png" sizes="16x16" href="https://regodemo.com/assets/images/favicon-16x16.png">
		
		<link rel="stylesheet" href="https://regodemo.com/assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://xray.co.th/hr/assets/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://regodemo.com/assets/css/login_RegoDemo.css?<?=time()?>">
		<link rel="stylesheet" href="https://regodemo.com/assets/css/myBootstrap.css?<?=time()?>">

		<script src="https://regodemo.com/assets/js/jquery-3.2.1.min.js"></script>
		<script src="https://regodemo.com/assets/js/popper.min.js"></script>
		<script src="https://regodemo.com/assets/js/bootstrap.min.js"></script>

	</head>
	
	<body>
		
		<div id="brand_logo">
			<? if($lang=='en'){ ?>
				<a style="margin:0px 0 0 0" data-lng="th" class="langbutton <? if($lang=='th'){echo 'activ';} ?>"><img src="../images/flag_th.png"></a>
			<? }else{ ?>
				<a style="margin:0px 0 0 0" data-lng="en" class="langbutton <? if($lang=='en'){echo 'activ';} ?>"><img src="../images/flag_en.png"></a>
			</td>
			<? } ?>
		</div>

		<div class="header">
			<table width="100%" border="0"><tr>
				<td>
					<img style="margin:0 0 3px 15px; height:40px;" src="<?=$default_logo.'?'.time()?>">
				</td>
				<td style="width:95%"></td>
				<td style="padding-right:20px">
				<? if($lang=='en'){ ?>
					<a style="margin:0px 0 0 0" data-lng="th" class="langbutton <? if($lang=='th'){echo 'activ';} ?>"><img src="../images/flag_th.png"></a>
				<? }else{ ?>
					<a style="margin:0px 0 0 0" data-lng="en" class="langbutton <? if($lang=='en'){echo 'activ';} ?>"><img src="../images/flag_en.png"></a>
				</td>
				<? } ?>
			</tr></table>
		</div>
		
		<div style="padding-top:10vh; xborder:1px solid red;">
			<div class="brand">
				<img src="../images/pkf_people.png">
				<p></p>
			</div>

		</div>
		

		<div class="modal fade" id="errorModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">

		 	<div class="modal-dialog" style="max-width:450px">
				<div class="modal-content">
					<div class="modal-header">
						<h4 class="modal-title" id="myModalLabel"><i class="fa fa-bell"></i>&nbsp;Error Message</h4>
					</div>
					<div class="modal-body" style="padding:20px 25px 25px">
						<div style="max-height:462px; overflow-y:auto">
							<p id="errorMessage" style="font-weight: 600;font-size: 15px;color: red;"></p>

						
							    <span class="wrap"></span>
							  </a>
						</div>
						<div style="height:10px"></div>

						
					</div>
			 	</div>
			</div>
		</div>		


<script type="text/javascript">
	
	// load modal on page load 

	$( document ).ready(function() {
		 $('#errorModal').modal('show');
		 typeWriter();
	});


	var initial = 0;
	function typeWriter(txt) {

		txt = 'Oops, this link is expired.This URL is not valid anymore.Please contact adminstrator.';
		var speed = 40;


	  if (initial < txt.length) {
	    document.getElementById("errorMessage").innerHTML += txt.charAt(initial);
	    initial++;
	    setTimeout(typeWriter, speed);
	  }
	}



	

</script>
	</body>
</html>










