<?php

	if(session_id()==''){session_start();}
	ob_start();
	//var_dump($_REQUEST); exit;
	include("../dbconnect/db_connect.php");

	$locations = array();
	$sql = "SELECT * FROM ".$cid."_leave_time_settings";
	if($res = $dbc->query($sql)){
		if($row = $res->fetch_assoc()){
			$locations = unserialize($row['scan_locations']);
		}
	}

	$sql1 = "SELECT * FROM ".$cid."_branches_data WHERE ref = '".$_REQUEST['bid']."'";
	if($res1 = $dbc->query($sql1)){
		if($row1 = $res1->fetch_assoc())
		{
			$rowData = $row1;
			if($_REQUEST['id'] == '1')
			{
				$loc1 = json_decode($row1['loc1']);
				$locaation_name  = $loc1->name;
				$locaation_latitude  = $loc1->latitude;
				$locaation_longitude  = $loc1->longitude;
				$locaation_qr  = $loc1->qr;

			}			
			else if($_REQUEST['id'] == '2')
			{
				$loc2 = json_decode($row1['loc2']);
				$locaation_name  = $loc2->name;
				$locaation_latitude  = $loc2->latitude;
				$locaation_longitude  = $loc2->longitude;
				$locaation_qr  = $loc2->qr;
			}			
			else if($_REQUEST['id'] == '3')
			{
				$loc3 = json_decode($row1['loc3']);
				$locaation_name  = $loc3->name;
				$locaation_latitude  = $loc3->latitude;
				$locaation_longitude  = $loc3->longitude;
				$locaation_qr  = $loc3->qr;
			}			
			else if($_REQUEST['id'] == '4')
			{
				$loc4 = json_decode($row1['loc4']);
				$locaation_name  = $loc4->name;
				$locaation_latitude  = $loc4->latitude;
				$locaation_longitude  = $loc4->longitude;
				$locaation_qr  = $loc4->qr;
			}			
			else if($_REQUEST['id'] == '5')
			{
				$loc5 = json_decode($row1['loc5']);
				$locaation_name  = $loc5->name;
				$locaation_latitude  = $loc5->latitude;
				$locaation_longitude  = $loc5->longitude;
				$locaation_qr  = $loc5->qr;
			}
		}
	}

	// die();
	//var_dump($locations); exit;
	$explode1 = explode('.th', $locaation_qr);

	$explode2 = explode('?', $explode1[1]);

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
				<div style="text-align:center; width:80%; margin:0 auto; padding-top:100px"><img src="../..'.$explode2[0].'"></div>
				</body></html>';	
			
	//echo $style.$html.$footer; exit;	
			
	require_once("../mpdf7/vendor/autoload.php");
	
	$mpdf=new mPDF('utf-8', 'A4-P', 9, '', 8, 8, 8, 8, 8, 8);
	$mpdf->SetDisplayMode('fullpage');
	$mpdf->WriteHTML($style,1);
	$mpdf->WriteHTML($html);
	$mpdf->Output($filename,'I');
	









