<?
	// $jsonCustomers = getJsonCustomers();
	// $jsonProducts = getJsonProducts('A');
	// $emailTemplates = getEmailTemplates();
	// $contactEmails = getContactEmails();
	
	$tot_record = 1;
	$sqltt = "SELECT COUNT(*) as tot_record FROM ".$cid."_comm_centers WHERE month = '".date('m')."'";
	if($restt = $dbc->query($sqltt)){
		if($row = $restt->fetch_assoc()){
			$tot_record = $row['tot_record'] + 1;
		}
	}

	$AllSystemUsersList = AllSystemUsersList();
	$AllSystemUsersApproverList = AllSystemUsersApproverList();
	$AllSystemUsersEmails = AllSystemUsersEmails();

	$todayYM 	= date('Y').date('m');
	//$todayD 	= date('d');
	$todayD 	= $tot_record;
	$yearm 		= (int)filter_var($todayYM, FILTER_SANITIZE_NUMBER_INT);
	$AnnId 		= str_pad($todayD, 3, "0", STR_PAD_LEFT);
	$AnnIdVal 	= $yearm.'-'.$AnnId;

	//check if this anno is already exist or not...
	//$sqlttchk = "SELECT * FROM ".$cid."_comm_centers WHERE anno_id = '202112-015'";
	$sqlttchk = "SELECT * FROM ".$cid."_comm_centers WHERE month = '".date('m')."' ORDER BY anno_id DESC LIMIT 1";
	if($restt = $dbc->query($sqlttchk)){
		if($resttnn = $restt->num_rows > 0){

			$lastid = $restt->fetch_assoc();
			$explode = explode('-', $lastid['anno_id']); 
			$plus1 = $explode[1] + 1;
			$AnnIdss = str_pad($plus1, 3, "0", STR_PAD_LEFT);
			$AnnIdVal = $explode[0].'-'.$AnnIdss;
		}
	}


	$dataHeader = array();
	$sql = "SELECT * FROM ".$cid."_document_templates";
	if($res = $dbc->query($sql)){
		while($row = $res->fetch_assoc()){
			$dataHeader[$row['id']] = $row;
		}
	}

	$dataFooter = array();
	$sql1 = "SELECT * FROM ".$cid."_footer_templates";
	if($res1 = $dbc->query($sql1)){
		while($rowss = $res1->fetch_assoc()){
			$dataFooter[$rowss['id']] = $rowss;
		}
	}

	$hiddenButtons = '';
	if(isset($_GET['ccid'])){
		$ccid = $_GET['ccid'];
		$attach = array();
		$getAttach = "SELECT * FROM ".$cid."_comm_centers WHERE id='".$ccid."'";
		if($res = $dbc->query($getAttach)){
			if($row = $res->fetch_assoc()){
				$attach = $row;

				if($attach['status'] =='3'){
					$hiddenButtons = 'locked';
				}

				if($attach['sent_to'] !=''){
					$getsenttoEmp = getsenttoEmp($attach['sent_to']);
				}

				if($attach['pdflink'] !=''){
					$pdflink = ROOT.$cid.'/commcenter/archive/'.$attach['pdflink'];

					//send by email...
					$emailtxt = 'Hi, %0D%0A';
					$emailtxt .= '%0D%0A'; 
					$emailtxt .= 'Please click on the below link and review the Announcement. %0D%0A'; 
					$emailtxt .= '%0D%0A'; 
					$emailtxt .= $pdflink; 
					$emailtxt .= '%0D%0A'; 
				}
			}
		}

		//history data...
		$historyarray = array();
		$sql1hh = "SELECT * FROM ".$cid."_commCenters_logs WHERE cc_id = '".$ccid."' ORDER BY id DESC";
		if($res1hh = $dbc->query($sql1hh)){
			while($rowsshh = $res1hh->fetch_assoc()){
				$historyarray[] = $rowsshh;
			}
		}

		//textblock field...
		$sectionValdddss = unserialize($attach['sectionVal']);
		$txtfields = array();
		foreach ($sectionValdddss as $valuetxt) {
			$sql1tt = "SELECT * FROM ".$cid."_textblock_fields WHERE txtblock_id = '".$valuetxt."'";
			if($res1tt = $dbc->query($sql1tt)){
				while($rowsstt = $res1tt->fetch_assoc()){
					$txtfields[$rowsstt['id']] = $rowsstt['name'];
				}
			}
		}

		//get request approval from log history...
		$requestApp = array();
		$sql1rr = "SELECT * FROM ".$cid."_commCenters_logs WHERE `field`='Request result' AND cc_id ='".$ccid."' ORDER BY id desc";
		if($res1rr = $dbc->query($sql1rr)){
			while($rowssrrr = $res1rr->fetch_assoc()){
				$requestApp[] = $rowssrrr;
			}
		}
		

	}else{
		$ccid = 0;
	}
	
	$blocks = array();
	$blockslist = array();
	$blocksaa = array();
	$sql = "SELECT * FROM ".$cid."_document_textblocks";
	if($res = $dbc->query($sql)){
		while($row = $res->fetch_assoc()){
			$blocks[$row['name']] = $row['text'];
			$blockslist[$row['id']] = $row['name'];
			$blocksaa[$row['id']] = $row['text'];
		}
	}
	$blocks[''] = ''; 

	if(isset($attach)){
		$attached = explode(',', $attach['attachments']);
		$sectionValddd = unserialize($attach['sectionVal']);
	}


	// echo '<pre>';
	// print_r($_SESSION);
	// echo '</pre>';
	// exit;
?>
<link href="../assets/css/smart_wizard_theme_arrows.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" type="text/css" href="../assets/css/erpStyle.css?<?=time()?>">
<link href="../assets/css/summernote-bs4.css?<?=time()?>" rel="stylesheet" />
<style>
.basicTable input {
	background:transparent;
	text-align:right;
}
.basicTable input.bld {
	font-weight:600;
}
.basicTable input.target {
	font-weight:600;
	color:#a00;
}
a.rcalc i {
	color:#aaa;
}
a.rcalc.activ > i {
	color:#a00;
}
a.acalc.activ > i {
	color:#a00;
}
table.basicTable tbody tr.activ {
	background:#f6fff6;
}

table.basicTable tbody th, table.basicTable tbody td{
	cursor: pointer;
}

.remBlock {
    position: absolute;
    top: 2px !important;
    right: 12px;
    z-index: 999;
    color: red;
}
table#tempsetting tbody tr:hover {
    background: #dfd;
    cursor: pointer;
}

table#ParametersTable input{
	text-align:left !important;
}
table#tempsetting input{
	text-align:left !important;
}

.SumoSelect {
    padding: 0px 2px 1px 5px !important;
    border: 1px #ddd solid !important;
    width: 155px !important;
}

.dropdown-menu{
	padding: 0.5rem 15px;
}

.datetimepicker td, .datetimepicker th {
    width: 23px;
    height: 22px;
}

::-webkit-scrollbar {
    width: 6px;
    height: 15px;
}

.borderDot {
  height: 15px;
  width: 15px;
  background-color: red;
  border-radius: 50%;
  display: inline-block;
  vertical-align: text-bottom;
}

.openHelp {
    padding: 0 0 3px 0;
    text-align: center;
    font-size: 25px;
    cursor: pointer;
    color: #03c;
    line-height: 29px;
    position: absolute;
    top: 90px;
    right: 15px;
}

/*.tooltip-inner {
    max-width: 300px;
    padding: 5px 12px 6px !important;
    color: #ffffff;
    text-align: center;
    text-decoration: none;
    background-color: #900;
    border-radius: 3px;
    font-weight: 600 !important;
}

.bs-tooltip-top .arrow::before {
    top: 0;
    border-width: 1.2rem 0.4rem 0;
    border-top-color: #900;
} */
</style>
	
	<div style="height:100%; border:0px solid red; position:relative;top: 51px;">
		
		<div class="breadcrumbs" style="position: initial;background: #eee;">
			
			<h2 style="padding:0px;line-height: 32px;background: #eee;font-size:14px;">
				<i class="fa fa-comments mr-1"></i>
				<?if($ccid > 0){?>
					<?=$attach['anno_id']?> from <?=date('d M, Y', strtotime($attach['date']))?> from <?=$AllSystemUsersList[$attach['username']]?>
				<? }else{ echo $lng['Communication center']; } ?>
			</h2>
		
			<div class="sw-theme-arrows" id="" style="width:400px; text-align:right; border-left:1px solid #ddd; position:absolute; left:30%; top:0;">
				<ul class="step-anchor" style="margin:0">
					<?
					$dactive = $aactive = $sactive = $ractive = '';
					if($attach['status'] == 1){
						$dactive = 'class="active"';
					}elseif($attach['status'] == 2){
						$aactive = 'class="active"';
					}elseif($attach['status'] == 3){
						$sactive = 'class="active"';
					}?>
					 <li id="sDraft" <?=$dactive?>><a><?=$lng['Draft']?></a></li>
					 <li id="sOrder" <?=$aactive?>><a><?=$lng['Approved']?></a></li>
					 <li id="sSend" <?=$sactive?>><a><?=$lng['Send']?></a></li>
				</ul>
			</div>
		</div>
		
		
		<div style="position:absolute; left:0; top:33px; right:70%; bottom:0; background:#fff;">
			
			<div class="smallNav" >
				<ul>
					
					
					<li><a class="font-weight-bold" style="color:#005588;"><?=$lng['Template Settings']?></a></li>
					
				</ul>
			</div>
			
			<div id="leftTable" style="left:10px; top:45px; right:10px; bottom:15px; background:#fff; overflow-Y:auto; padding:0; display:xnone; overflow-X:hidden">
				
					<table id="tempsetting" class="basicTable ml-3" style="width:100%;" border="0">
						<!-- <thead>
							<tr>
								<td colspan="2"></td>
							</tr>
						</thead> -->
						<tbody>
							<tr data-toggle="collapse" data-target="#ftb">
								<th class="tal" colspan="3"><?=$lng['Features text block'];?></th>	
							</tr>
							<? $keyCountss = 0; 
							foreach($blockslist as $k=>$v){ if($k == $sectionValddd[$k]){ $keyCountss++; ?>
								<tr data-toggle="collapse" data-target="#tr<?=$keyCountss;?>">
									<td class="tal" colspan="2" style="font-weight: 600 !important;"><i class="fa fa-arrow-right"></i> <?=$v?></td>
									<td class="tal"></td>
								</tr>

								<? foreach($txtfields as $key => $val){
										if($val == 'DATE'){ 
											//$addClass='class="datepickcc p-0"';
											$addClass='class="p-0"';
										}else{
											$addClass='class="p-0"';
										}
								?>

									<tr id="tr<?=$keyCountss;?>" class="collapse sdsdsd">
										<td class="tal" style="font-weight: 600 !important;">
											<?=ucfirst(strtolower($val));?>:
										</td>
										<td>
											<input type="text" name="<?=$val;?>" id="<?=str_replace(' ', '_', $val);?><?=$keyCountss;?>" <?=$addClass?> placeholder="<?=ucfirst(strtolower($val));?>..." >
										</td>
										<th>
											<button type="button" class="btn btn-primary btn-sm mr-3" onclick="AddfieldtosummerNote(<?=$keyCountss?>,'<?=str_replace(' ', '_', $val);?>', <?=$k?>);"><?=$lng['Add'];?></button>
										</th>
										
									</tr>
								<? } ?>
								<!--<tr id="tr<?=$keyCountss;?>" class="collapse sdsdsd">
									<td>
										<input type="text" name="datess" id="datess<?=$keyCountss;?>" class="datepick p-0" value="" placeholder="<?=$lng['Date'];?>...">
									</td>
									<th>
										<button type="button" class="btn btn-primary btn-sm mr-3" onclick="AddfieldtosummerNote(<?=$keyCountss;?>,'datess');"><?=$lng['Add'];?></button>
									</th>
									
								</tr>
								<tr id="tr<?=$keyCountss;?>" class="collapse sdsdsd">
									<td>
										<input type="text" name="fromss" id="fromss<?=$keyCountss;?>" value="" class="p-0" placeholder="<?=$lng['From'];?>...">
									</td>
									<th>
										<button type="button" class="btn btn-primary btn-sm mr-3" onclick="AddfieldtosummerNote(<?=$keyCountss;?>,'fromss');"><?=$lng['Add'];?></button>
									</th>
									
								</tr>
								<tr id="tr<?=$keyCountss;?>" class="collapse sdsdsd">
									<td>
										<input type="text" name="toss" id="toss<?=$keyCountss;?>" value="" class="p-0" placeholder="<?=$lng['To'];?>...">
									</td>
									<th>
										<button type="button" class="btn btn-primary btn-sm mr-3" onclick="AddfieldtosummerNote(<?=$keyCountss;?>,'toss');"><?=$lng['Add'];?></button>
									</th>
									
								</tr>
								<tr id="tr<?=$keyCountss;?>" class="collapse sdsdsd">
									<td>
										<input type="text" name="subjectss" id="subjectss<?=$keyCountss;?>" value="" class="p-0" placeholder="<?=$lng['Subject'];?>...">
									</td>
									<th>
										<button type="button" class="btn btn-primary btn-sm mr-3" onclick="AddfieldtosummerNote(<?=$keyCountss;?>,'subjectss');"><?=$lng['Add'];?></button>
									</th>
									
								</tr>-->

							<? } } ?>

							<tr data-toggle="collapse" data-target="#attch">
								<th class="tal" colspan="3"><?=$lng['Attachment'];?></th>
							</tr>

								<? 
								$attcont = 0;
								if(isset($attached)){ foreach($attached as $k=>$v){ if($v != '') { $attcont++; ?>
									<tr id="attch" class="collapse">
										<td class="tal" colspan="2">
											<a class="btn btn-default btn-xs" target="_blank" href="<?=$v;?>"> <?=$lng['Attachment'];?> - <?=$attcont?></a>
										</td>
										<td>
											<?php if($hiddenButtons == ''){ ?>
												<a type="button" class="delattch_<?=$k;?>" data-value="<?=$v;?>" id="<?=$ccid?>" onclick="RemoveAttach(this,<?=$k;?>);" data-toggle="tooltip" title="Remove"><i class="fa fa-times-circle text-danger fa-lg"></i></a>
											<?php } ?>
										</td>
									</tr>
								<? } } } ?>

								<tr id="attch" class="collapse" >
									<form method="post" id="attachimgs" enctype="multipart/form-data">
										<input type="hidden" name="iddds" value="<?=$ccid?>">
										<td class="tal" colspan="2">
											<input type="file" name="filedd" class="btn-sm">
										</td>
										<td class="tal">
											<?php if($hiddenButtons == ''){ ?>
												<button id="attachBtn" class="btn btn-primary btn-sm mr-3" type="button">
													<?=$lng['Save']?>
												</button>
											<? } ?>
										</td>
									</form>
								</tr>

							<tr onclick="ModalParameters()">
								<th class="tal" colspan="3"><?=$lng['Parameters'];?></th>	
							</tr>

							<tr onclick="openSendtopopup()">
								<th class="tal" colspan="2"><?=$lng['Send to'];?></th>	
								<th class="tal" colspan="1">
									<? if($attach['sent_to'] ==''){ ?>
										<span class="text-danger font-weight-bold float-right mr-4"><?=$lng['No Team selected']?></span>
									<? } ?>
								</th>	
							</tr>

							<tr data-toggle="collapse" data-target="#reviewApp" onclick="ReviewAndSubmit()">
								<th class="tal" colspan="3"><?=$lng['Review and Submit'];?></th>	
							</tr>

							<tr id="reviewApp" class="collapse">
								<th class="tal"><?=$lng['Approval'];?></th>
								<td colspan="2">
									<?php
										if($attach['approval'] == 1){
											$valchkbox = 1; $chkboxss='checked="checked"';
										}else{
											$valchkbox = 0; $chkboxss='';
										}
									?>

									<? if($_SESSION['rego']['comm_center']['need_approver'] == 1){ 
										$valchkbox = 1; $chkboxss='checked="checked"';
									?>
										<!-- <input type="checkbox" name="no_app_req" value="<?=$valchkbox?>" <?=$chkboxss?> class="ml-2" onclick="showhidefn(this)"> -->
										<input type="hidden" name="no_app_req" value="<?=$valchkbox?>" <?=$chkboxss?> class="ml-2">
										<span class="ml-2 text-italic">Approval required</span>
									<? }else{ ?>
										<input type="checkbox" name="no_app_req" value="0" class="ml-2" onclick="showhidefn(this)">
										<span class="ml-2 text-italic">No approval required</span>
									<? } ?>
								</td>
							</tr>

								
							<tr id="reviewApp" class="collapse showhide">
								<th class="tal"><?=$lng['Approver '];?></th>
								<td colspan="2">
									<select name="approver" id="appval" style="width: 100%;">
										<option value="" selected disabled>Select One</option>
										<? foreach($AllSystemUsersApproverList as $k=>$v){ ?>
											<option value="<?=$k?>" <?if($attach['approver'] == $k){echo 'selected="selected"';}?>><?=$v?></option>
										<? } ?>
									</select>
								</td>
							</tr>
							<tr id="reviewApp" class="collapse showhide">
								<th class="tal"><?=$lng['Approval Status'];?></th>
								<td colspan="2">
									<? 
										if(is_array($requestApp) && isset($requestApp)){ 

											if($requestApp[0]['new'] == 'Reject'){

												echo '<span class="borderDot" style="background: red;"></span>';
												echo ' Rejected on '.date('d-m-Y @ H:i', strtotime($requestApp[0]['date']));

											}else if($requestApp[0]['new'] == 'Approved'){

												echo '<span class="borderDot" style="background: green;"></span>';
												echo ' Approved on '.date('d-m-Y @ H:i', strtotime($requestApp[0]['date']));

											}else{
												echo '<span class="borderDot" style="background: yellow;"></span>';
												echo ' No action from approver';
											}
										}else{
												echo '<span class="borderDot" style="background: yellow;"></span>';
												echo ' No action from approver';
										}
									?>
								</td>
							</tr>
							<? if($attach['approver'] !=''){ if($attach['approver'] == $_SESSION['rego']['id']){ ?>
								<tr id="reviewApp" class="collapse">
									<th class="tal"><?=$lng['Remarks approver'];?></th>
									<td colspan="2">
										<textarea rows="4" name="remark_app" type="text" placeholder="type here..."><?=$attach['remark_approver']?></textarea>
									</td>
								</tr>
							<? } } ?>

							<tr id="reviewApp" class="collapse showhide">
								<td>
									<? if($attach['username'] == $_SESSION['rego']['id']){ 
										if($hiddenButtons == ''){
											$reqapp = '';
										}else{
											$reqapp = 'disabled="disabled"';
										}
									}else{
										$reqapp = 'disabled="disabled"';
									} ?>
									<button id="btnrequest" type="button" class="btn btn-primary btn-sm" <?=$reqapp?> onclick="SentrequestforApproval();"><i class="fa fa-paper-plane mr-2"></i><?=$lng['Request approval']?>
									</button>
								</td>
								<th class="tal" colspan="2">
									<? if($attach['approver'] !=''){ if($attach['approver'] == $_SESSION['rego']['id']){ 
										if($hiddenButtons == ''){ $btndissAR=''; }else{  $btndissAR='disabled="disabled"'; } ?>
										<button class="btn btn-primary btn-sm" <?=$btndissAR;?> onclick="RequestAppresult(1)"><?=$lng['Approve']?></button>
										<button class="btn btn-danger btn-sm" <?=$btndissAR;?> onclick="RequestAppresult(2)"><?=$lng['Reject']?></button>
									<? } } ?>
								</th>
							</tr>

							<tr id="reviewApp" class="collapse">
								<th class="tal"><?=$lng['Submit Status'];?></th>
								<td colspan="2">
									<select name="sub_status" style="width: 100%;pointer-events: none;">
											<option value="" disabled>Select One</option>

											<?
											$ones = '';
											$twos = '';
											if($attach['submit_status'] !=''){
												if($attach['submit_status'] == 1){ $ones = 'selected="selected"';}else{ $ones = ''; }
												if($attach['submit_status'] == 2){ $twos = 'selected="selected"';}else{ $twos = ''; }
											}else{
												if($_SESSION['rego']['comm_center']['need_approver'] == 1){ 
													$twos = 'selected="selected"';
												}else{ 
													$twos = ''; 
												}
											}?>

											<option value="1" <?=$ones;?>>Ready to submit</option>
											<option value="2" <?=$twos;?>>Awaiting approval</option>
									</select>
								</td>
							</tr>
							<tr id="reviewApp" class="collapse">
								<th class="tal"><?=$lng['Publish Date'];?></th>
								<td colspan="2">
									<?
										if(isset($attach['publish_on'])){
											if($attach['publish_on'] == 'Not published'){
												$attchvalsss = '';
											}else{
												$attchvalsss = date('d-m-Y H:i', strtotime($attach['publish_on']));
											}
										}else{ $attchvalsss = ''; }
									?>
									<input type="text" name="publish_date" class="datetimepicker" value="<?=$attchvalsss;?>">
								</td>
							</tr>
							<? 
								$btndiss = '';
								if($hiddenButtons == ''){

									if($attach['sent_to'] == ''){ 
										$btndiss = 'disabled="disabled"';
									}else{
										if($attach['submit_status'] == 2){
											$btndiss = 'disabled="disabled"';
										}else{
											if($attach['username'] != $_SESSION['rego']['id']){
												$btndiss = 'disabled="disabled"';
											}
										}
									}
								}else{
									$btndiss = 'disabled="disabled"';
								}
							?>
							<tr id="reviewApp" class="collapse">
								<th class="tal"></th>
								<td colspan="2">
									<button type="button" class="btn btn-primary btn-sm" id="sendlock" <?=$btndiss;?> onclick="SendandLockAnno()">
										<i class="fa fa-paper-plane mr-2"></i><?=$lng['Send & Lock']?>
									</button>
									
								</td>
							</tr>
									

							<tr data-toggle="modal" data-target="#modalLoghistory">
								<th class="tal" colspan="3"><?=$lng['Log History'];?></th>	
							</tr>

						</tbody>
					</table>

			</div>
		</div>
					
		<div style="position:absolute; left:30%; top:33px; right:0; bottom:0; background: #f6f6f6; border-left:1px solid #ddd">
			
			<div class="smallNav">
				<ul>
					<li id="btnView"><a><i class="fa fa-eye"></i> &nbsp;<?=$lng['Preview']?></a>
					<li id="btnEdit" style="display:none"><a><i class="fa fa-edit"></i> &nbsp;<?=$lng['Edit']?></a>
					<?php if($hiddenButtons == ''){ ?>
						<li id="btnSave" style="display:xnone"><a><i class="fa fa-save"></i> &nbsp;<?=$lng['Save']?></a>
					<? } ?>
					<li id="btnUpdate" style="display:none"><a><i class="fa fa-save"></i> &nbsp;<?=$lng['Update']?></a>
					<li id="btnCancel" style="display:none"><a><i class="fa fa-times"></i> &nbsp;<?=$lng['Cancel']?></a>
					<li id="btnValidate" style="display:none"><a><i class="fa fa-check"></i> &nbsp;<?=$lng['Validate']?></a>
					</a>
					<?if($ccid > 0){ ?>
						<li id="btnSend"><a id="qEmail" href="mailto:?subject=Review Announcement (<?=$attach['anno_id']?>)&body=<?=$emailtxt;?>"><i class="fa fa-paper-plane"></i> &nbsp;<?=$lng['Send by email']?>
						<li id="btnPrint"><a id="qPrint" target="_blank" data-toggle="tooltip" data-placement="top" title="Print PDF before sent"><i class="fa fa-print"></i> &nbsp;<?=$lng['Print PDF']?></a>
						
					<? } ?>
				</ul>
				  
			</div>
			
			<div id="rightTable" style="position:absolute; left:15px; top:45px; right:15px; bottom:15px; background:#fff; overflow-Y:auto; padding:15px 15px 100px 15px; display:none">

				<form id="quoteForm" autocomplete="off" method="post">

					<!-- <fieldset > -->

						<input type="hidden" name="id" value="<?=$ccid;?>">
 						
						<div class="row">
							<div class="col-md-4">
								<label>
									<input type="hidden" name="settings[header]" value="0">
									<input id="cHeader" type="checkbox" name="settings[header]" value="1" class="checkbox"><span> Show header</span>
								</label>
							</div>

							<div class="col-md-8">
								<select name="headerval" id="headerOpt" style="width: 100%">
									<option selected disabled value=""><?=$lng['Add new header']?></option>
									<?foreach ($dataHeader as $key => $value) { ?>
										<option value="<?=$value['id']?>"><?=$value['company']?></option>
									<? } ?>
								</select>
							</div>
						</div>

						<table id="HeaderData" border="0" style="width:100%; margin-bottom:15px;">
							<!-----header append here---->
						</table>

						<div class="row mb-2">
							<div class="col-md-4">
								<label>
									<input type="hidden" name="settings[section]" value="0">
									<input id="cSection" type="checkbox" name="settings[section]" value="1" class="checkbox"><span> Show Sections</span>
								</label>
							</div>
							<div class="col-md-8">
								<select name="sectionval[]" class="button" id="textBlock" style="width:100%">
									<option selected disabled value=""><?=$lng['Add new section']?></option>
									<option value=""><?=$lng['Add empty editable section']?></option>
									<? foreach($blocks as $k=>$v){ if($v !=''){ ?>
									<option value="<?=$k?>"><?=$k?></option>
									<? } } ?>
								</select>
							</div>
						</div>

						<table id="summerTable" style="width:100%; margin-bottom:15px;">
							<tbody>
								
								<? $keyCount = 0;
								foreach($blockslist as $k=>$v){ if($k == $sectionValddd[$k]){ $keyCount++; ?>
									<input type="hidden" name="sectionArr[]" id="<?=$keyCount?>" value="<?=$v?>">
								<? } } ?>

								<!-----section append here---->

							</tbody>
						</table>

						<div class="row">
							<div class="col-md-4">
								<label>
									<input type="hidden" name="settings[footer]" value="0">
									<input id="cFooter" type="checkbox" name="settings[footer]" value="1" class="checkbox"><span> Show Footer</span>
								</label>
							</div>
							<div class="col-md-8">
								<select name="footerval" class="button" id="footerOpt" style="width:100%">
									<option selected disabled value=""><?=$lng['Add new footer']?></option>
									<? foreach($dataFooter as $k=>$v){ ?>
										<option value="<?=$v['id']?>">Footer - <?=$v['id']?></option>
									<? } ?>
								</select>
							</div>
						</div>


						<div id="footerSec" style="border-top: 1px solid rgb(221, 221, 221); text-align: center; line-height: 160%; padding-top: 5px; font-size: 12px; margin-top: 15px;">
							<!-----footer append here----> 				
						</div>

					<!-- </fieldset> -->
				</form>
			
			</div>
			
		</div>
		

	</div>
	<div class="openHelp"><i class="fa fa-question-circle fa-lg"></i></div>
	<div id="help">
		<div class="closeHelp"><i class="fa fa-arrow-circle-right"></i></div>
		<div class="innerHelp">
			<?=$helpfile?>
		</div>
	</div>

<div id="modalParameters" class="modal fade" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false">
	<div class="modal-dialog" role="document" style="max-width:800px">
		<div class="modal-content">
			<form id="parameterForm" autocomplete="off" method="post">
				<div class="modal-header">
				  	<h5 class="modal-title"><i class="fa fa-cog"></i>&nbsp;&nbsp;<?=$lng['Parameters'];?></h5>
				  	<? if($ccid > 0){ ?>
					  	<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						 	<span aria-hidden="true">&times;</span>
					  	</button>
					<? }else{ ?>
						<a type="button" class="btn btn-default" href="index.php?mn=801">
						 	<i class="fa fa-angle-double-left fa-lg"></i>&nbsp;Back 
					  	</a>
					<? } ?>
				</div>
				<div class="modal-body" style="padding: 10px">
					

					<table class="basicTable inputs" id="ParametersTable">
						<input type="hidden" name="id" value="<?=$ccid?>">
						<tbody>
							<tr>
								<th style="opacity: 0.6;"><?=$lng['Announcement ID'];?></th>
								<td>
									<input type="text" name="anno_id" value="<?=isset($attach) ? $attach['anno_id'] : $AnnIdVal?>" readonly="readonly">
								</td>
								<th><?=$lng['Username'];?></th>
								<td>
									<!-- <input type="text" name="username" value="<?=isset($attach) ? $attach['username'] : $_SESSION['rego']['username']?>"> -->
									<select name="username" style="width: 100%;">
										<? foreach($AllSystemUsersList as $k=>$v){ ?>
											<option value="<?=$k?>" <?if(isset($attach['username'])){ if($k == $attach['username']){echo 'selected';} }else{ if($k == $_SESSION['rego']['id']){echo 'selected';} } ?>><?=$v?></option>
										<? } ?>
									</select>
								</td>
							</tr>
							
							<tr>
								<th style="opacity: 0.6;"><?=$lng['Publish On'];?></th>
								<td>
									<?
										if(isset($attach['publish_on'])){
											if($attach['publish_on'] == 'Not published'){
												$attchval = 'Not published';
											}else{
												$attchval = date('d-m-Y H:i', strtotime($attach['publish_on']));
											}
										}else{ $attchval = 'Not published'; }
									?>
									<input type="text" name="publish_on" class="datetimepickersss" value="<?=$attchval;?>">
								</td>
								<th style="opacity: 0.6;"><?=$lng['Date'];?></th>
								<td>
									<input type="text" name="date" class="" value="<?=isset($attach['date']) ? date('d-m-Y', strtotime($attach['date'])) : date('d-m-Y');?>" readonly="readonly">
								</td>
							</tr>
							<tr>
								<th><?=$lng['Type'];?></th>
								<td>
									<select name="anno_type" style="width: 100%;">
									<? foreach($Announcementtype as $k=>$v){ ?>
										<option value="<?=$k?>" <?if($k == $attach['anno_type']){echo 'selected';}?>><?=$v?></option>
									<? } ?>
									</select>
								</td>
							
								<th style="opacity: 0.6;"><?=$lng['Approval required'];?></th>
								<td>
									
									<select name="appr_required" style="width: 100%;pointer-events: none;">
									<? foreach($noyes01 as $k=>$v){

										if(isset($attach['appr_required'])){
											if($k == $attach['appr_required']){
												$attchvalnnyy = 'selected';
											}else{
												$attchvalnnyy = '';
											}
										}else{ 
											if($k == $_SESSION['rego']['comm_center']['need_approver']){
												$attchvalnnyy = 'selected';
											}else{
												$attchvalnnyy = '';
											}
										}

									?>
										<option value="<?=$k?>" <?=$attchvalnnyy;?> ><?=$v?></option>
									<? } ?>
									</select>
								</td>
							</tr>

							<tr>
								<th><?=$lng['From'];?></th>
								<td>
									<input type="text" name="fromss" value="<?=isset($attach['fromss']) ? $attach['fromss'] : $_SESSION['rego']['username']?>">
								</td>
							
								<th><?=$lng['To'];?></th>
								<td>
									<select name="tooss" style="width: 100%;">
									<? foreach($Topersons as $k=>$v){ ?>
										<option value="<?=$k?>" <?if($k == $attach['tooss']){echo 'selected';}?>><?=$v?></option>
									<? } ?>
									</select>
								</td>
							</tr>
							<tr>
								<th><?=$lng['Mode'];?></th>
								<td>
									<select name="anno_mode" style="width: 100%;">
									<? foreach($AnnouncementMode as $k=>$v){ ?>
										<option value="<?=$k?>" <?if($k == $attach['anno_mode']){echo 'selected';}?>><?=$v?></option>
									<? } ?>
									</select>
								</td>
							
								<th><?=$lng['Category'];?></th>
								<td>
									<? if($_SESSION['rego']['comm_center']['private_msg'] == 1){
											$addcsssty = '';
										}else{
											$addcsssty = 'pointer-events:none;';
										}
									?>
									<select name="anno_category" style="width: 100%;<?=$addcsssty?>" onchange="Categorychg(this.value);">
									<? foreach($AnnouncementCategory as $k=>$v){ ?>
										<option value="<?=$k?>" <?if($k == $attach['anno_category']){echo 'selected';}?>><?=$v?></option>
									<? } ?>
									</select>
								</td>
							</tr>
							<tr>
								<th><?=$lng['Description'];?></th>
								<td colspan="3">
									<textarea rows="4" id="descrpp" name="description"><?=isset($attach) ? $attach['description'] : '';?></textarea>
								</td>
							</tr>
							
						</tbody>
					</table>

				</div>
				<div class="modal-footer">
					<? if($ccid > 0){ ?>
			        	<button type="button" class="btn btn-primary" data-dismiss="modal"><i class="fa fa-times"></i> <?=$lng['Cancel'];?></button>
			        <? } ?>
					<?php if($hiddenButtons == ''){ ?>
			        	<button id="parForm" type="button" class="btn btn-primary"><i class="fa fa-save"></i> <?=$lng['Save'];?></button>
			        <? } ?>
			    </div>
			</form>
		</div>
	</div>
</div>


<!----------------- Loghistory ------------------>
<div id="modalLoghistory" class="modal fade" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false">
	<div class="modal-dialog" role="document" style="max-width:900px">
		<div class="modal-content">
			<!-- <form id="parameterForm" autocomplete="off" method="post"> -->
				<div class="modal-header">
				  	<h5 class="modal-title"><i class="fa fa-history"></i>&nbsp;&nbsp;<?=$lng['Log History'];?></h5>
				  	<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					 	<span aria-hidden="true">&times;</span>
				  	</button>
				</div>
				<div class="modal-body" style="padding: 10px">

					<table id="datatable" class="dataTable nowrap hoverable" style="width:100%;">
						<thead>
							<tr>
								<th><?=$lng['Field']?></th>
								<th><?=$lng['Previous value']?></th>
								<th><?=$lng['Current value']?></th>
								<th><?=$lng['Changed by']?></th>
								<th><?=$lng['Date']?></th>
							</tr>
						</thead>
						<tbody>
							<? if(isset($historyarray) && is_array($historyarray)){ 
								foreach($historyarray as $v){ 

									$pre = $v['prev'];
									$new = $v['new'];
									if($v['field'] == 'Username') { 
										$pre = $AllSystemUsersList[$v['prev']];
										$new = $AllSystemUsersList[$v['new']];
									}elseif($v['field'] == 'Category') { 
										$pre = $AnnouncementCategory[$v['prev']];
										$new = $AnnouncementCategory[$v['new']];
									}elseif($v['field'] == 'Mode') { 
										$pre = $AnnouncementMode[$v['prev']];
										$new = $AnnouncementMode[$v['new']];
									}elseif($v['field'] == 'To') { 
										$pre = $Topersons[$v['prev']];
										$new = $Topersons[$v['new']];
									}elseif($v['field'] == 'Approval required') { 
										$pre = $noyes01[$v['prev']];
										$new = $noyes01[$v['new']];
									}elseif($v['field'] == 'Type') { 
										$pre = $Announcementtype[$v['prev']];
										$new = $Announcementtype[$v['new']];
									}elseif($v['field'] == 'Send to') { 

										$explodeTeamspre = $v['prev'];
										$preArr = array();
										if($explodeTeamspre !=''){
											$preTeams = explode(',', $explodeTeamspre);
											foreach ($preTeams as $keyp => $valuep) {
												$preArr[] = $teams[$valuep]['code'];
											}
										}else{ $preArr = array();}

										$explodeTeamsnew = $v['new'];
										$newArr = array();
										if($explodeTeamsnew !=''){
											$newTeams = explode(',', $explodeTeamsnew); 
											foreach ($newTeams as $keyp => $valuep) {
												$newArr[] = $teams[$valuep]['code'];
											}
										}else{ $newArr = array();}

										$pre = implode(', ', $preArr);
										$new = implode(', ', $newArr);
									}

									?>
									<tr>
										<td><?=$v['field']?></td>
										<td><?=mb_strlen($pre) > 30 ? mb_substr($pre, 0, 30) . "..." : $pre;?></td>
										<td><?=mb_strlen($new) > 30 ? mb_substr($new, 0, 30) . "..." : $new;?></td>
										<td><?=$v['user']?></td>
										<td><?=date('d-m-Y @ H:i', strtotime($v['date']))?></td>
									</tr>
							<? } } ?>
						</tbody>
					</table>

				</div>
				<div class="modal-footer">
			        <button type="button" class="btn btn-primary" data-dismiss="modal"><?=$lng['Cancel'];?></button>
			    </div>
			<!-- </form> -->
		</div>
	</div>
</div>
<!----------------- Loghistory ------------------>



	
<script src="../assets/js/summernote-bs4.js?<?=time()?>"></script>
	
<? include('comm_data_script.php'); ?>
<script type="text/javascript">

	function openSendtopopup(){
		var ccid = <?=$ccid;?>;
		if(ccid > 0){
			window.location.href = 'index.php?mn=803&ccid='+ccid;
		}
	}

	function Categorychg(valss){
		var text = "This message has been sent to you confidentially, do not share the content with other persons.";
		if(valss == 2){
			$('#descrpp').val(text);
		}else{
			$('#descrpp').val('');
		}
	}
	
	$(document).ready(function(){

		var dtable = $('#datatable').DataTable({

			"lengthChange":  false,
			"searching": true,
			order: [4, 'desc'],
			<?=$dtable_lang?>

		});



	})
</script>