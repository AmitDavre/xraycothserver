<? if($userType == 'appr' ) {
	if($sessionTeams != '')
	{
	?>
		<div class="btn-group permissions" style="">
		<select multiple="multiple" id="selBox-teams" name="selBoxteams" >
			<? 

				$teamDD = array();
				foreach($aprTeam as $key =>$teamD){ 

					if(in_array($teamD, $sesTeamArray)){
						$teamDD[] = $teamD;
					}
				?>
				<option <?php if(in_array($teamD, $teamDD)) { echo "selected";} ?>  value="<?php echo $teamD;?>"><?php echo $teamD ;?></option>
			<?  } ?>
	    </select>
		</div>
<?php } }
else {  if(!empty($datateamsS['code_id'])) {?>

<div class="btn-group permissions" style="">
		<select multiple="multiple" id="selBox-teams" name="selBoxteams" >
			<? foreach($datateamsS['code_id'] as $key =>$teamD){ 
				?>
				<option selected="selected" value="<?php echo $teamD;?>"><?php echo $teamD ;?></option>
			<?  } ?>
	    </select>
</div>

<!-- 
<?  }}?>
 -->

 