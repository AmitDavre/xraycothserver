<?
	if(session_id()==''){session_start();}
	ob_start();
	include("../../dbconnect/db_connect.php");
	include(DIR.'files/arrays_'.$lang.'.php');

	$res = $dbx->query("SELECT * FROM rego_customers WHERE id = '".$_REQUEST['id']."'"); 
	if($row = $res->fetch_assoc()){
		$data = $row;
	}else{
		$data = array();
	}
	//var_dump($data); exit;
	
	$table = '
		<table class="table table-bordered table-sm table-striped" style="width:100%; margin:0">
			<tbody>
				<tr>
					<th>'.$lng['Client ID'].'</th>
					<td>'.strtoupper($data['clientID']).'</td>
				</tr>
				<tr>
					<th>'.$lng['Company'].'</th>
					<td>'.$data[$lang.'_compname'].'</td>
				</tr>
				<tr>
					<th>'.$lng['Contact'].'</th>
					<td>'.$data['name'].'</td>
				</tr>
				<tr>
					<th>'.$lng['Phone'].'</th>
					<td><b><a href="tel:'.$data['phone'].'">'.$data['phone'].'</a></b></td>
				</tr>
				<tr>
					<th>'.$lng['email'].'</th>
					<td><b><a href="mailto:'.$data['email'].'">'.$data['email'].'</a></b></td>
				</tr>
				<tr>
					<th>'.$lng['Joining date'].'</th>
					<td>'.$data['joiningdate'].'</td>
				</tr>
				<tr>
					<th>'.$lng['Period start'].'</th>
					<td>'.$data['period_start'].'</td>
				</tr>
				<tr>
					<th>'.$lng['Period end'].'</th>
					<td>'.$data['period_end'].'</td>
				</tr>
				<tr>
					<th>'.$lng['Subscription'].'</th>
					<td>REGO '.$data['version'].'</td>
				</tr>
				<tr>
					<th>Price year</th>
					<td>'.number_format($data['price_year']).' '.$lng['Baht'].'</td>
				</tr>
				<tr>
					<th>'.$lng['Status'].'</th>
					<td>'.$client_status[$data['status']].'</td>
				</tr>
			</tbody>
		</table>';

	ob_clean();
	echo $table
?>


















