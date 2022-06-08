	
	<table class="basicTable" border="0">
		<tbody>
		<tr><th style="width:5%">
			<label><?=$lng['Joining date']?> :</label>
		</th><td>
			<input class="dateinput" name="joiningdate" type="text" value="<?=$_POST['joiningdate']?>" />
		</td></tr>
		<tr><th>
			<label><?=$lng['Latitude']?> :</label>
		</th><td>
			<input name="latitude" class="float410" id="latitude" type="text" value="<?=$_POST['latitude']?>" />
		</td></tr>
		<tr><th>
			<label><?=$lng['Longitude']?> :</label>
		</th><td>
			<input name="longitude" class="float410" id="longitude" type="text" value="<?=$_POST['longitude']?>" />
		</td></tr>
		<tr><th>
			<label><?=$lng['Prefered language']?> :</label>
		</th><td style="display:none">
			<select readonly name="preflang" id="preflang">
				<option <? if($_POST['preflang'] == 'en'){echo "selected ";} ?>value="en"><?=$lng['English']?></option>
				<option <? if($_POST['preflang'] == 'th'){echo "selected ";} ?>value="th"><?=$lng['Thai']?></option>
			</select>
		</td></tr>
		<tr><th style="vertical-align:top">
			<label><?=$lng['Remarks']?> :</label>
		</th><td>
			<textarea name="remarks" rows="5"><?=$_POST['remarks']?></textarea>
		</td></tr>
		</tbody>
	</table>
	<h6 style="background:#eee; padding:6px 10px; margin:10px 0 0 0; border-radius:3px 3px 0 0"><i class="fa fa-arrow-circle-down"></i>&nbsp;&nbsp;<?=$lng['Google Map']?> - <span style="text-transform:none"><?=$_POST[$lang.'_compname']?></span></h6>
	<div style="height:400px;" id="map-canvas"><p style="padding:10px 15px">Map <?=$_POST[$lang.'_compname']?></p></div>
	


