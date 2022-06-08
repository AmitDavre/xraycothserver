
	<div class="header page_header">
		<div><i class="fa fa-comments-o"></i>&nbsp; <?=$lng['Contact']?></div>
		<a style="float:right" href="index.php?mn=2"><i class="fa fa-home"></i></a>
	</div>			
  
	<div style="position:absolute; top:55px; bottom:0; left:0; right:0; padding:10px">
	
		<form id="contactForm" style="height:100%; position:relative">
			<input name="agent_id" type="hidden" value="<?=$_SESSION['agent']['agent_id']?>" />
			<input name="email" type="hidden" value="<?=$_SESSION['agent']['email']?>" />
			<input style="visibility:hidden; height:0; position:absolute" id="contactAttach" type="file" name="contactAttach" />
			
			<label><?=$lng['Name']?> <i class="man"></i></label>
			<input readonly name="name" type="text" value="<?=$_SESSION['agent']['name']?>" />
			
			<label><?=$lng['Subject']?> <i class="man"></i></label>
			<input name="subject" type="text" value="" />
			
			<label><?=$lng['Message Question']?> <i class="man"></i></label>
			<textarea style="margin-bottom:5px" name="comment" rows="6"></textarea>
			
			<button onClick="$('#contactAttach').click()" type="button" class="btn btn-info btn-lg btn-block"><?=$lng['Attachement']?> : <span style="font-size:13px" id="attachMsg"><?=$lng['No file selected']?></span></button>
			
			<button id="contactBtn" style="text-align:center" type="submit" class="btn btn-success btn-lg btn-block"><i class="fa fa-paper-plane"></i>&nbsp; <?=$lng['Submit']?></button>

		</form>
		
		<div id="contactMsg" class="bg-warning" style="position:absolute; bottom:0; left:0; right:0; color:#000; font-size:16px; text-align:center; padding:5px 10px; display:none"></div>
		</div>

	</div>
  














