<?
	if(session_id()==''){session_start(); ob_start();}
	//$cid = $_SESSION['rego']['cid'];
	include("../../dbconnect/db_connect.php");
	//var_dump($_REQUEST);// exit;
	


	// count PUB in database 	


	$sql2 = "SELECT * FROM ".$cid."_monthly_shiftplans_".$cur_year." WHERE id = '".$_REQUEST['id']."' ";

	if($res2 = $dbc->query($sql2)){
		if($row2 = $res2->fetch_assoc()){

			for ($i = 1 ; $i <=31; $i++)
			{
				$dataArray[] = $row2['D'.$i];
			}

				


		}
	}

	$occurences = array_count_values($dataArray);
	if(!empty($occurences['PUB']))
	{
		$pubCount = $occurences['PUB'];
	}
	else
	{
		$pubCount = 0;
	}

	if(!empty($occurences['OFF']))
	{
		$offCount = $occurences['OFF'];
	}
	else
	{
		$offCount = 0;
	}
	

	// get shiftplan codes in array from leve time setting table 


	$sql3 = "SELECT * FROM ".$cid."_leave_time_settings WHERE id = '1'";
	if($res3 = $dbc->query($sql3)){
		if($row3 = $res3->fetch_assoc()){

			$shiftplanval = unserialize($row3['shiftplan']);
		
		}
	}




	$allKEys = array();
	foreach ($shiftplanval as $key1 => $value1) {

		$allKEys[$key1] = $key1;
	}
	$allKEys['PUB'] = 'PUB';

	// $splitnumber = ...;
	// $splittedNumbers = explode(",", $splitnumber);
	// $numbers = "'" . implode("', '", $splittedNumbers) ."'";





	// subtract 1 in from off colun if off is selected and add subtract 0 if other is selected 

	$sql1 = "SELECT * FROM ".$cid."_monthly_shiftplans_".$cur_year." WHERE id = '".$_REQUEST['id']."'";
	if($res1 = $dbc->query($sql1)){
		if($row1 = $res1->fetch_assoc()){
			$offdays = $row1['off'];
			$wkd = $row1['wkd'];
			$pub = $row1['pub'];
			$vod = $row1['vod'];
		}
	}

	// echo $_REQUEST['col'];

	// die();

	// if off then add in off subtract in working days
	// if pub then add in pub subtract in working day 
	// if working day from off add in working day subtract in off
	// if working day from pub add in working day subtract in pub 
	// if off from pub  add in off subtract in pub same working day
	// if pub from off add in pub subtract from off same working day
	// if old plan working day and new plan blank subtract in working day 
	// if old plan off and new plan blank subtract in off 
	// if old plan pub and new plan blank subtract in pub 


		if($_REQUEST['val'] == 'OFF' &&  $_REQUEST['oldColVal'] != 'OFF' && $_REQUEST['oldColVal'] != 'PUB' && $_REQUEST['oldColVal'] != '')
		{
			$newoffdays = $offdays + 1;
			$newpub = $pub ;
			$newwkd =  $wkd -1 ;

			// die('1');
		}
		else if($_REQUEST['val'] == 'PUB' &&  $_REQUEST['oldColVal'] != 'OFF' && $_REQUEST['oldColVal'] != 'PUB' && $_REQUEST['oldColVal'] != '' )
		{
			$newoffdays = $offdays;	
			$newpub = $pub + 1 ;
			$newwkd =  $wkd -1 ;
			// die('2');

		}	
		else if($_REQUEST['oldColVal'] == 'OFF' && $_REQUEST['val'] != 'OFF' && $_REQUEST['val'] != 'PUB' && $_REQUEST['val'] != ''  )
		{
			$newoffdays = $offdays -1;
			$newpub = $pub  ;
			$newwkd =  $wkd +1 ;
			// die('3');

		}	
		else if($_REQUEST['oldColVal'] == 'PUB' && $_REQUEST['val'] != 'OFF' &&  $_REQUEST['val'] != 'PUB'  && $_REQUEST['val'] != '' )
		{
			$newoffdays = $offdays ;
			$newpub = $pub -1;
			$newwkd =  $wkd +1 ;
			// die('4');

		}	
		else if($_REQUEST['oldColVal'] == 'PUB' && $_REQUEST['val'] == 'OFF' )
		{
			$newoffdays = $offdays +1 ;
			$newpub = $pub -1;
			$newwkd =  $wkd  ;
			// die('5');

		}	
		else if($_REQUEST['oldColVal'] == 'OFF' && $_REQUEST['val'] == 'PUB' )
		{
			$newoffdays = $offdays - 1 ;
			$newpub = $pub + 1;
			$newwkd =  $wkd  ;
			// die('6');

		}		
		else if($_REQUEST['oldColVal'] == 'OFF' && $_REQUEST['val'] == '' )
		{
			$newoffdays = $offdays - 1 ;
			$newpub = $pub ;
			$newwkd =  $wkd  ;
			// die('7');

		}		
		else if($_REQUEST['oldColVal'] == 'PUB' && $_REQUEST['val'] == '' )
		{
			$newoffdays = $offdays ;
			$newpub = $pub -1 ;
			$newwkd =  $wkd  ;
			// die('8');

		}		
		else if($_REQUEST['oldColVal'] != 'PUB' && $_REQUEST['oldColVal'] != 'OFF' && $_REQUEST['val'] == '' )
		{
			$newoffdays = $offdays ;
			$newpub = $pub ;
			$newwkd =  $wkd -1 ;
			// die('9');

		}		
		else if($_REQUEST['oldColVal'] == '' && $_REQUEST['val'] != 'OFF' && $_REQUEST['val'] != 'PUB' )
		{
			$newoffdays = $offdays  ;
			$newpub = $pub ;
			$newwkd =  $wkd + 1 ;
			// die('10');

		}		
		else if($_REQUEST['oldColVal'] == '' && $_REQUEST['val'] == 'OFF' )
		{
			$newoffdays = $offdays  + 1;
			$newpub = $pub ;
			$newwkd =  $wkd ;
			// die('11');

		}		
		else if($_REQUEST['oldColVal'] == '' && $_REQUEST['val'] == 'PUB' )
		{
			$newoffdays = $offdays  ;
			$newpub = $pub + 1;
			$newwkd =  $wkd ;
			// die('12');

		}
		else
		{
			$newoffdays = $offdays  ;
			$newpub = $pub ;
			$newwkd =  $wkd  ;
		}

	

	



	// $newoffdays = $offdays;
	// $newpub = $pub ;
	// $newwkd =  $wkd  ;




	// if($_REQUEST['val'] == 'PUB')
	// {
	// 	$newpub = $pub + 1 ;
	// 	$newwkd =  $wkd -1;
	// 	$newoffdays = $offdays;



	// }
	// else if($_REQUEST['val'] != 'PUB' )
	// {
	// 	// check if off count is equal to array count 

	// 	if($pubCount == $pub)
	// 	{
	// 		$newpub = $pub;
	// 		$newwkd =  $wkd ;

	// 	}
	// 	else
	// 	{
	// 		$newpub = $pub -1;
	// 		$newwkd =  $wkd +1;
	// 	}
	// 	$newoffdays = $offdays;



	// }





	$sql = "UPDATE ".$cid."_monthly_shiftplans_".$cur_year." SET ".$_REQUEST['col']." = '".$dbc->real_escape_string($_REQUEST['val'])."' , off = '".$newoffdays."' , wkd = '".$newwkd."' , pub = '".$newpub."' WHERE id = '".$_REQUEST['id']."'";

	// $sql2 = "UPDATE ".$cid."_attendance SET plan = '".$dbc->real_escape_string($_REQUEST['val'])."' WHERE id = '".$_REQUEST['id']."'";
	// echo $sql; exit;
		

// update the off days here if we change the off days update in the table 
	if($dbc->query($sql)){
		ob_clean();	echo 'success'; exit;
	}else{
		ob_clean();	echo 'Error : '.mysqli_error($dbc);
	}
	
?>