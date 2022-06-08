
	<div class="header page_header">
		<div><i class="fa fa-handshake-o"></i>&nbsp; REGO Free Trial<? //=$lng['Year overview']?></div>
		<a style="float:right" href="index.php?mn=2"><i class="fa fa-home"></i></a>
	</div>			

	<div style="position:absolute; top:55px; bottom:0; left:0; right:0; padding:5px 10px 10px">
	
		<form id="trialForm" style="height:100%; position:relative">
			<input name="agent" type="hidden" value="<?=$_SESSION['agent']['agent_id']?>" />
			<input type="hidden" name="token" value="free">
			<!--<input type="hidden" name="version" value="0">-->
			<input type="hidden" name="employees" value="100">
			<input type="hidden" name="lang" value="<?=$lang?>">
			<input type="hidden" name="tax_id" value="">
			<input type="hidden" name="address" value="">
			<input type="hidden" name="postcode" value="">
			<input type="hidden" name="subdistrict" value="">
			<input type="hidden" name="district" value="">
			<input type="hidden" name="province" value="">
			<input type="hidden" name="certificate" value="">
			<input type="hidden" name="date" value="<?=date('d-m-Y')?>">
			<input type="hidden" name="period_start" value="<?=date('d-m-Y')?>">
			<input type="hidden" name="period_end" value="<?=date('d-m-Y', strtotime('+1 month', strtotime(date('Y-m-d'))))?>">
			<input type="hidden" name="price_year" value="0">
			
			<div id="dump3"></div>
			
			<label>Version<? //=$lng['Version']?> <i class="man"></i></label>
			<select name="version">
				<option value="xxx">Select</option>
				<option value="0">REGO 10/20</option>
				<option selected value="mob">REGO Mobile</option>
			</select>
			
			<label><?=$lng['First name']?> <i class="man"></i></label>
			<input name="firstname" type="text" value="Willy" />
			
			<label><?=$lng['Last name']?> <i class="man"></i></label>
			<input name="lastname" type="text" value="Thaimans" />
			
			<label><?=$lng['Company name']?> <i class="man"></i></label>
			<input name="company" type="text" value="Xray" />
			
			<label><?=$lng['Phone']?> <i class="man"></i></label>
			<input name="phone" type="text" value="123456789" />
			
			<label><?=$lng['email']?> / <?=$lng['Username']?> <i class="man"></i></label>
			<input name="email" type="text" value="willy@xrayict.com" />
			
			<label><?=$lng['Password']?> <i class="man"></i></label>
			<input name="pass1" type="password" value="Tinkerbell11" />
			
			<label>Repeat password<? //=$lng['Repeat password']?> <i class="man"></i></label>
			<input name="pass2" type="password" value="Tinkerbell11" />
			
			<label><?=$lng['Comment']?></label>
			<textarea style="margin-bottom:5px" name="comment" rows="4"></textarea>
			
			<button id="trialBtn" style="text-align:center; font-weight:600" type="submit" class="btn btn-success btn-lg btn-block"><i class="fa fa-paper-plane"></i>&nbsp; <?=$lng['Submit']?></button>
			<div style="height:10px"></div>

		</form>
	
	</div>
  
	<!-- Modal -->
	<div class="modal fade" id="successModal" tabindex="-1" role="dialog" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header bg-info" style="padding:0 10px 10px">
					<h5 class="modal-title" style="color:#fff; width:100%; text-align:center">Create new Customer</h5>
				</div>
				<div class="modal-body">
					<div id="trialMsg" style="text-align:center; font-size:16px"></div>
				</div>
			</div>
		</div>
	</div>

