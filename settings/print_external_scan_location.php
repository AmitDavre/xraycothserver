<?php

	if(session_id()==''){session_start();}
	ob_start();
	//var_dump($_REQUEST); exit;
	include("../dbconnect/db_connect.php");

	
	$sql1 = "SELECT * FROM ".$cid."_location WHERE loc_id = '".$_REQUEST['id']."'";
	if($res1 = $dbc->query($sql1)){
		if($row1 = $res1->fetch_assoc())
		{
				$locaation_name       = $row1['loc_name'];
				$locaation_latitude   = $row1['latitude'];
				$locaation_longitude  = $row1['longitude'];
				$locaation_qr         = $row1['qr'];
		}
	}

	// die();
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
					'.$lng['Latitude'].' : '.$locaation_latitude.' - 
					'.$lng['Longitude'].' : '.$locaation_longitude.'
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
	









