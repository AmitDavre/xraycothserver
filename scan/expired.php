<?php

	if(session_id()==''){session_start();} 
	ob_start();
	
	include('db_connect.php');
	include(DIR.'files/arrays_'.$lang.'.php');
	//unset($_SESSION['scan']['cid']);
	
	if(!isset($_SESSION['scan']['cid'])){
		header('location: login.php');
	}
	//var_dump($_SESSION); exit;
	
	function getJsonIdsEmployees(){
		global $dbc;
		global $cid;
		global $lang;
		$data = array();
		$sql = "SELECT emp_id, ".$lang."_name, image FROM ".$cid."_employees WHERE emp_status = 1 ORDER BY emp_id ASC";
		if($res = $dbc->query($sql)){
			if($res->num_rows > 0){
				while($row = $res->fetch_assoc()){
					$image = $row['image'];
					if(empty($row['image'])){$image = 'images/profile_image.jpg';}
					$data[] = array('data'=>$row['emp_id'], 'value'=>$row['emp_id'].' - '.$row[$lang.'_name'], 'name'=>$row[$lang.'_name'], 'image'=>$image);
				}
			}
		}
		return $data;
		//return mysqli_error($dbc);
	}
	$emp_array = getJsonIdsEmployees();
	//var_dump($emp_array); exit;
	
	$myear = date('Y');
	if($lang == 'th'){$myear += 543;}
	$thai_date = $weekdays[date('N')].' '.date('d-m-').$myear;


	// GET ADMIN EMAIL
	$my_dbaname = $prefix.'admin';


	$dbadmin = new mysqli($my_database,$my_username,$my_password,$my_dbaname);
	mysqli_set_charset($dbadmin,"utf8");
	if($dbadmin->connect_error) {
		echo'<p style="width:900px; margin:0 auto; margin-top:20px;" class="box_err">Error: ('.$dbadmin->connect_errno.') '.$dbadmin->connect_error.'<br>Please try again later or report this error to <a href="mailto:admin@regohr.com">admin@regohr.com</a></p>';
	}

	$sql102 = "SELECT * FROM rego_company_settings WHERE id = '1'";

	if($res102 = $dbadmin->query($sql102)){
		if($res102->num_rows > 0){
			if($row102 = $res102->fetch_assoc())
				{
					$admin_mail_value = $row102['admin_mail'];  // SELECTED TEAMS STORED IN SESSION 
						
				}
		}
	}



?>


<!DOCTYPE html>
<html>
  <head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title><?=$www_title?></title>
		
		<meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate"/>
  	<meta http-equiv="Pragma" content="no-cache"/>
  	<meta http-equiv="Expires" content="0"/>

		<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700">
		<link rel="stylesheet" href="../assets/css/bootstrap.min.css">
		<link rel="stylesheet" href="../assets/css/font-awesome.min.css">
		<link rel="stylesheet" href="css/style.css?<?=time()?>">

		<script type="text/javascript" src="js/jquery.min.js"></script>
		<script type="text/javascript" src="../assets/js/bootstrap.min.js"></script>
		<script type="text/javascript" src="../assets/js/jquery.autocomplete.js"></script>
		<script type="text/javascript" src="js/adapter.js"></script>
	
	</head>

	<body style="background:#006666">
		
		<div class="time-wrapper">
			<div id="dump"></div>
      
			<div class="body-wrapper">
				<div class="page-wrap d-flex flex-row align-items-center">
				    <div class="container">
				        <div class="row justify-content-center">
				            <div class="col-md-12 text-center">
				                <span style="color: #fff;" class="display-1 d-block" style="margin-top: 200px;">There is a problem with the subscription of your account.</span>
				                <div class="mb-4 lead" style="margin-top: 70px;font-size: 20px;"><span style="color: #fff;">Please contact us at <?php echo $admin_mail_value ;?> for further assistance.</span></div>
				            </div>
				        </div>
				    </div>
				</div>
			
			</div>
				
    </div>
		
		<div class="header">
			<i class="fa fa-clock-o"></i>&nbsp; <?=$lng['Time registration']?>
			<a href="#" id="logout"><i class="fa fa-sign-out fa-lg"></i></a>
		</div>			
		
		<!-- Modal -->
		<div class="modal fade" id="successModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel5" aria-hidden="true" data-backdrop="static" data-keyboard="false" style="z-index:9999">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<div class="modal-body" style="color:#333; text-align:center">
						<div id="successMsg" style="font-size:20px; line-height:160%; padding:10px 0 15px"></div>
						<button id="successBtn" data-dismiss="modal" class="btn btn-success btn-block"><?=$lng['OK']?></button>
					</div>
				</div>
			</div>
		</div>

		<!-- Modal -->
		<div class="modal fade" id="errorModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel5" aria-hidden="true" data-backdrop="static" data-keyboard="false" style="z-index:9999">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<div class="modal-body" style="color:#333; text-align:center">
						<div id="errorMsg" style="font-size:20px; line-height:160%; padding:10px 0 15px"></div>
						<button id="errorBtn" data-dismiss="modal" class="btn btn-danger btn-block"><?=$lng['OK']?></button>
					</div>
				</div>
			</div>
		</div>

	<script> 
		


			$("#logout").on('click', function(e) {
				$.ajax({
					url: "ajax/logout.php",
					success: function(response){
						location.reload();
					}	
				})		
			})

	
			
	</script>

	</body>
</html>
