
	<div class="header page_header">
		<div><i class="fa fa-unlock-alt"></i>&nbsp; <?=$lng['Change password']?></div>
		<a style="float:right" href="index.php?mn=2"><i class="fa fa-home"></i></a>
	</div>			
  
	<div style="position:absolute; top:55px; bottom:0; left:0; right:0; padding:10px">
		
		<form id="changePassForm">

			 <label><?=$lng['Old password']?> <i class="man"></i></label>
			 <input name="opass" id="opass" type="text" />
			 
			 <label><?=$lng['New password']?> <i class="man"></i></label>
			 <input name="npass" id="npass" type="password" />
			 
			 <label><?=$lng['Repeat new password']?> <i class="man"></i></label>
			 <input name="rpass" id="rpass" type="password" />
			 
			<button style="text-align:center; margin-top:10px" type="submit" class="btn btn-success btn-lg btn-block"><i class="fa fa-save"></i>&nbsp; <?=$lng['Change password']?></button>
			
		</form>
		<div style="dump3"></div>
		
		<div id="passMsg" class="bg-warning" style="position:absolute; bottom:0; left:0; right:0; color:#000; font-size:16px; text-align:center; padding:5px 10px; display:none"></div>
		</div>

	</div>
  














