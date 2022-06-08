<?
	if(session_id()==''){session_start(); ob_start();}
	include("../../dbconnect/db_connect.php");



	$sql = "SELECT  non_email FROM rego_default_settings ";
	if($res = $dba->query($sql)){
		if($row = $res->fetch_assoc()){
			$emailnon = $row['non_email'];
		}
	}		




	if($emailnon)
	{	



		$unserlizearray = unserialize($emailnon);

		$emailnons = $unserlizearray;
		$emailnons[] = $_REQUEST['emailnon'];

		$newnonemail =  serialize($emailnons);
	}
	else
	{

		$arrayVal[] = $_REQUEST['emailnon'];
		$searlizearr = serialize($arrayVal);

		$newnonemail = $searlizearr;
	}




	$sql = "UPDATE rego_default_settings SET non_email = '".$newnonemail."'  ";

	if($dba->query($sql)){
		ob_clean();
		echo 'success';
	}else{
		echo mysqli_error($dba);
	}





