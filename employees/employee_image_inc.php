	
	<? if(empty($data['image'])){
		echo '<style>.croppie-container {background:url(../images/profile_image.jpg) no-repeat center center;}</style>';
	}else{
		echo '<style>.croppie-container {background:url(../'.$data['image'].'?'.time().') no-repeat center center;}</style>';
	} ?>

	<div class="pannel left_pannel">
		<table border="0" class="employee-image">
			<tr>
				<td>
					<div id="upload-demo"></div>
					<form id="imageForm">
						<input type="hidden" name="emp_id" value="<?=$_SESSION['rego']['empID']?>">
					</form>
					<input id="selectUserImg" type="file" name="user_img" />
					<button <? if($_SESSION['rego']['empID'] == '0'){echo 'disabled';} ?> onClick="$('#selectUserImg').click();" style="width:calc( 100% - 39px); margin:5px 0 0 0; float:left" class="btn btn-primary" type="button"><i class="fa fa-user"></i>&nbsp;&nbsp;<?=$lng['Select picture']?></button>
					<button id="imageBtn" style="width:36px; margin:5px 0 0 0; float:right" class="btn btn-primary" type="button"><i class="fa fa-save fa-lg"></i></button>
					
				</td>
			</tr>
		</table>
		
		<div style="padding-right:5px">	
		<table border="0" class="employee-info">
			<? if($update == 1){ ?>
			<? if(!empty($data['personal_phone'])){ ?>
			<tr>
				<td>
					<span style="color:#900; font-weight:600; display:block; padding-left:5px"><?=$lng['Phone']?> :</span>
					<span id="per_phone" style="padding-left:15px"><?=$data['personal_phone']?></span>
				</td>
			</tr>
			<? } if(!empty($data['personal_email'])){ ?>
			<tr>
				<td>
					<span style="color:#900; font-weight:600; display:block; padding-left:5px"><?=$lng['email']?> :</span>
					<span style="padding-left:15px"><a id="per_email" href="mailto:<?=$data['personal_email']?>"><?=$data['personal_email']?></a></span>
				</td>
			</tr>
			<? } if(!empty($data['joining_date'])){ ?>
			<tr>
				<td>
					<span style="color:#900; font-weight:600; display:block; padding-left:5px"><?=$lng['Joining date']?> :</span>
					<span style="padding:0 0 3px 15px; line-height:120%; display:block">
						<span id="per_startdate"><?=date('d-m-Y', strtotime($data['joining_date']))?></span><br>
						<span id="per_serv_years" style="font-size:11px"></span>
					</span>
				</td>
			</tr>
			<? }} ?>
		</table>
		</div>
		<div id="imgDump"></div>
	</div>

