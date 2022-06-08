<?
	if(session_id()==''){session_start();}
	ob_start();
	include("../../dbconnect/db_connect.php");
	include(DIR.'files/arrays_'.$_SESSION['RGadmin']['lang'].'.php');
	//var_dump($_REQUEST); exit;
	
	$data = array();
	if($res = $dba->query("SELECT * FROM rego_support_desk WHERE ticket = '".$_REQUEST["id"]."'")){
		while($row = $res->fetch_assoc()){
			$data['ticket'] = $row['ticket'];
			$data['priority'] = $sd_prior[$row['priority']][0];
			$data['subject'] = $row['subject'];
			$data['created'] = date('D d-m-Y @ H:i', strtotime($row['created']));
			$data['status'] = $sd_status[$row['status']][0];
			$data['stat'] = $row['status'];
			$data['type'] = $sd_type[$row['type']];
			/*$str = '';
			$tmp = explode(',', $row['recipients']);
			foreach($tmp as $v){
				$str .= $users[$v]['name'].', ';
			}*/
			$data['customer'] = strtoupper($row['cid']).' - '.$row['customer'];
			$data['applicant'] = $row['applicant'];
			$data['phone'] = $row['phone'];
			$data['recipients'] = explode(',', $row['recipients']);
			//$data['link'] = '<a href="'.ROOT.$_SESSION['subdir'].'/index.php?mn=706&id='.$_REQUEST['id'].'">Ticket '.$_REQUEST['id'].'</a>';
			
		}
	}else{
		echo mysqli_error($dba);
	}
	
	$message = array();
	if($res = $dba->query("SELECT * FROM rego_support_tickets  WHERE ticket = '".$_REQUEST["id"]."' ORDER BY date DESC")){
		while($row = $res->fetch_assoc()){
			$message[$row['id']]['message'] = $row['message'];
			$message[$row['id']]['date'] = $row['date'];
			$message[$row['id']]['name'] = $row['name'];
			$message[$row['id']]['recipients'] = $row['recipients'];
			$message[$row['id']]['platform'] = $row['platform'];
			$message[$row['id']]['attachments'] = unserialize($row['attachments']);
			$data['sender'] = $row['name'];
		}
	}else{
		echo mysqli_error($dba);
	}
	
	$body = '';
	foreach($message as $k=>$v){
		if($v['platform'] == 'REG'){$class = 'yellow'; }else{$class = 'green';}
		
		$body .= '
			<table class="msgTable">
				<tr>
					<th class="'.$class.'">'.$v['name'].'<span style="float:right"><i>'.date('D d-m-Y @ H:i', strtotime($v['date'])).'</i></span></th>
				</tr>
				<tr>
					<td class="'.$class.'">'.$v['message'].'</td>
				</tr>
				<tr>
					<td class="'.$class.'" style="padding:5px 10px"><span style="font-weight:600">Recipients : </span>'.$v['recipients'].'</td>
				</tr>';
				if($v['attachments']){ 
					$body .= '
					<tr>
						<td class="'.$class.'" style="padding:5px 10px">';
							foreach($v['attachments'] as $att){
							$body .= '
							<i><a class="attLink" download href="'.$att.'"><i class="fa fa-file-o"></i>&nbsp;&nbsp;'.array_pop(explode('/', $att)).'</a></i><br>';
							}
					$body .= '</td></tr>';
				}
		$body .= '
			</table>';
	}
	
	$response['body'] = $body;
	$response['data'] = $data;
	
	var_dump($response); //exit;
	var_dump($data); //exit;
	ob_clean();
	echo json_encode($response);
	exit;
	
?>
