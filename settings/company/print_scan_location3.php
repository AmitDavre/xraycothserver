<?php

	if(session_id()==''){session_start();}
	ob_start();
	//var_dump($_REQUEST); exit;
	include("../../dbconnect/db_connect.php");

	$locations = array();
	$sql = "SELECT loc1,loc2,loc3,loc4,loc_name, loc_qr, latitude, longitude FROM ".$cid."_branches_data WHERE ref = ".$_REQUEST['id'];
	if($res = $dbc->query($sql)){
		if($row = $res->fetch_assoc()){
			$data = $row;
			$loc1 = unserialize($row['loc3']);
		}
	}
	//var_dump($data); exit;

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
				<div style="padding:40px 0 20px; font-size:50px; text-align:center; line-height:110%">'.$loc1['loc_name'].'</div>
				<div style="font-size:20px; text-align:center">
					'.$lng['Latitude'].' : '.$loc1['latitude'].' - 
					'.$lng['Longitude'].' : '.$loc1['longitude'].'
				</div>
				<div style="text-align:center; width:80%; margin:0 auto; padding-top:100px"><img src="'.ROOT.$loc1['loc_qr'].'"></div>
				</body></html>';	
			
	//echo $style.$html.$footer; exit;	
			
	require_once("../../mpdf7/vendor/autoload.php");
	
	$mpdf=new mPDF('utf-8', 'A4-P', 9, '', 8, 8, 8, 8, 8, 8);
	$mpdf->SetDisplayMode('fullpage');
	$mpdf->WriteHTML($style,1);
	$mpdf->WriteHTML($html);
	$mpdf->Output($filename,'I');
	









