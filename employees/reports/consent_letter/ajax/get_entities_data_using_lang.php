<?
	if(session_id()==''){session_start();}
	ob_start();
	include('../../../../dbconnect/db_connect.php');
	include(DIR.'files/functions.php');


    $lang =  $_REQUEST['show_lang_field'];
    $sentities = str_replace(',', "','", $_SESSION['rego']['sel_entities']);
    $explodeEntity =  explode(',', $_SESSION['rego']['sel_entities']);

    if(count($explodeEntity) == '1')
    {
        $ref_id = $_SESSION['rego']['sel_entities'];
    }
    else {
        # code...
        echo 'false';

        exit();
    }

    $sql3244 = "SELECT * FROM ".$cid."_entities_data WHERE ref = '". $ref_id."'";
    if($reasdasds = $dbc->query($sql3244)){
        if($rosaddsw = $reasdasds->fetch_assoc()){
           
                echo json_encode($rosaddsw);
        }
    }



	




