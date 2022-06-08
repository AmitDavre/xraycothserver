<?php

	ob_start();

	$ciddd =$_GET['cid']; 
	$iddd =$_GET['id']; 

	/* include hashids lib */

	// include("../lib/Hashids/Hashids.php");

	// /* create the class object with minimum hashid length of 8 */
	// $hashids = new Hashids\Hashids('this is my salt', 8);
	//  encode several numbers into one id (length of id is going to be at least 8) 
	// $id = $hashids->encode(1337, 5);
	// /* decode the same id */
	// $numbers = $hashids->decode($id);
	// /* `$numbers` is always an array */
	// var_dump($id, $numbers);
	
	// exit;



	
	$prefix = 'xraycoth_';

	$my_database = 'localhost';
	$my_username = 'xraycoth_rego';
	$my_password = 'uL4!v*H1Ka6No5';
	$prefix = 'xraycoth_';
	$my_dbaname = $prefix.$ciddd;




	$dba = new mysqli($my_database,$my_username,$my_password,$my_dbaname);
	mysqli_set_charset($dba,"utf8");
	if($dba->connect_error) {
		echo'<p style="width:900px; margin:0 auto; margin-top:20px;" class="box_err">Error: ('.$dba->connect_errno.') '.$dba->connect_error.'<br>Please try again later or report this error to <a href="mailto:admin@regohr.com">admin@regohr.com</a></p>';
	}


	
	$sql1 = "SELECT * FROM ".$ciddd."_location WHERE loc_id = '".$iddd."'";


	if($res1 = $dba->query($sql1)){
		if($row1 = $res1->fetch_assoc())
		{

				$locaation_name       = $row1['loc_name'];
				$locaation_latitude   = $row1['latitude'];
				$locaation_longitude  = $row1['longitude'];
				$locaation_qr         = $row1['qr'];
		}
	}

	//var_dump($locations); exit;
	$explode1 = explode('hr/', $locaation_qr);

	// $explode2 = explode('?', $explode1[1]);






	// echo '<pre>';
	// print_r($explode1);
	// print_r($explode2);
	// echo '</pre>';

	// die();


	$style = '
		<style>
			@page {
				margin: 10px 10px 10px 10px;
			}
			body, html, table {
				font-family: "leelawadee", "garuda";
				font-family: "leelawadee";
				font-size:20px;
			}
		</style>';
	
	$html = '<html><body>
				<div style="padding:40px 0 20px; font-size:50px; text-align:center; line-height:110%">'.$locaation_name.'</div>
				<div style="font-size:20px; text-align:center">
				Latitude : '.$locaation_latitude.' - 
				Longitude : '.$locaation_longitude.'
				</div>
				<div style="text-align:center; width:80%; margin:0 auto; padding-top:100px"><img src="../'.$explode1['1'].'"></div>
				</body></html>';	
			
	//echo $style.$html.$footer; exit;	
			
	require_once("../mpdf7/vendor/autoload.php");
	
	$mpdf=new mPDF('utf-8', 'A4-P', 9, '', 8, 8, 8, 8, 8, 8);
	$mpdf->SetDisplayMode('fullpage');
	$mpdf->WriteHTML($style,1);
	$mpdf->WriteHTML($html);
	$mpdf->Output($filename,'I');
	









