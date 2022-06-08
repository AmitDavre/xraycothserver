	
	<!-- Modal Login -->
	<div class="modal fade" id="modalExpired" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
		 <div class="modal-dialog" style="width:350px">
			  <div class="modal-content">
					<div class="modal-header">
						 <h4 class="modal-title" id="myModalLabel"><i class="fa fa-clock-o"></i>&nbsp; <?=$lng['Logtime expired']?></h4>
					</div>
					<div class="modal-body" style="padding:20px 30px 30px">
						<div style="font-size:15px; padding-bottom:4px"><?=str_replace('{(name)}', '<b>'.$_SESSION['rego']['fname'].'</b>', $lng['Hi are you still there'])?></div>
						<span id="relogMsg" style="color:#b00"></span>
						<form id="relogForm" class="sform" style="padding-top:6px;">
							 <input placeholder="<?=$lng['Password']?>" name="repassword" type="password" />
							 <button class="btn btn-primary" style="margin-top:10px; float:left" type="submit"><i class="fa fa-sign-in"></i>&nbsp;<?=$lng['Log-in']?></button>
							 <button class="btn btn-primary logout" style="margin-top:10px; float:right" type="button"><i class="fa fa-times"></i>&nbsp; <?=$lng['Exit']?></button>
							 <div style="clear:both"></div>
						</form>
					</div>
					<div class="clear"></div>
			  </div>
		 </div>
	</div>
