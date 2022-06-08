		<? if(!empty($_SESSION['rego']['mn_entities'])){ ?>
		<div class="btn-group permissions" style="display:none">	
		    <select multiple="multiple" id="selBox-entities">
					<? foreach($entities as $k=>$v){if(in_array($k, explode(',', $_SESSION['rego']['mn_entities']))){ ?>
					<option <? if(in_array($k, explode(',', $_SESSION['rego']['sel_entities']))){echo 'selected';} ?> value="<?=$k?>"><?=$v[$lang]?></option>
					<? }} ?>
		    </select>
		</div>	
		<? }
		else{ ?>

			<div class="btn-group permissions" style="display:none">	
			    <select multiple="multiple" id="selBox-entities">
						<? foreach($entities as $k=>$v){if(in_array($k, explode(',', $_SESSION['rego']['mn_entities']))){ ?>
						<option <? if(in_array($k, explode(',', $_SESSION['rego']['sel_entities']))){echo 'selected';} ?> value="<?=$k?>"><?=$v[$lang]?></option>
						<? }} ?>
			    </select>
			</div>	

		<?php } ?>
		
		<? if(!empty($_SESSION['rego']['mn_branches'])){ ?>	
		<div class="btn-group permissions" style="display:none">
	    <select multiple="multiple" id="selBox-branches">
				<? foreach($branches as $k=>$v){if(in_array($k, explode(',', $_SESSION['rego']['mn_branches']))){ ?>
				<option <? if(in_array($k, explode(',', $_SESSION['rego']['sel_branches']))){echo 'selected';} ?> value="<?=$k?>"><?=$v[$lang]?></option>
				<? }} ?>
	    </select>
	    </div>
		<? } else { ?>
			<div class="btn-group permissions" style="display:none">
			    <select multiple="multiple" id="selBox-branches">
						<? foreach($branches as $k=>$v){if(in_array($k, explode(',', $_SESSION['rego']['mn_branches']))){ ?>
						<option <? if(in_array($k, explode(',', $_SESSION['rego']['sel_branches']))){echo 'selected';} ?> value="<?=$k?>"><?=$v[$lang]?></option>
						<? }} ?>
			    </select>
		    </div>
		<?php } ?>
		
		<? if(count($divisions) > 1){
			if(!empty($_SESSION['rego']['mn_divisions'])){ ?>
			<div class="btn-group permissions" style="display:none">
			<select multiple="multiple" id="selBox-divisions">
				<? foreach($divisions as $k=>$v){if(in_array($k, explode(',', $_SESSION['rego']['mn_divisions']))){ ?>
				<option <? if(in_array($k, explode(',', $_SESSION['rego']['sel_divisions']))){echo 'selected';} ?> value="<?=$k?>"><?=$v[$lang]?></option>
				<? }} ?>
			</select>
			</div>
		<? } else { ?> 

			<div class="btn-group permissions" style="display:none">
				<select multiple="multiple" id="selBox-divisions">
					<? foreach($divisions as $k=>$v){if(in_array($k, explode(',', $_SESSION['rego']['mn_divisions']))){ ?>
					<option <? if(in_array($k, explode(',', $_SESSION['rego']['sel_divisions']))){echo 'selected';} ?> value="<?=$k?>"><?=$v[$lang]?></option>
					<? }} ?>
				</select>
			</div>

		<?php } } ?>
		
		<? if(count($departments) > 1){
			if(!empty($_SESSION['rego']['mn_departments'])){ ?>
			<div class="btn-group permissions" style="display:none">
			<select multiple="multiple" id="selBox-departments">
				<? foreach($departments as $k=>$v){if(in_array($k, explode(',',$_SESSION['rego']['mn_departments']))){ ?>
				<option <? if(in_array($k, explode(',',$_SESSION['rego']['sel_departments']))){echo 'selected';} ?> value="<?=$k?>"><?=$v[$lang]?></option>
				<? }} ?>
			</select>
			</div>
		<? } else{ ?> 

			<div class="btn-group permissions" style="display:none">
				<select multiple="multiple" id="selBox-departments">
					<? foreach($departments as $k=>$v){if(in_array($k, explode(',',$_SESSION['rego']['mn_departments']))){ ?>
					<option <? if(in_array($k, explode(',',$_SESSION['rego']['sel_departments']))){echo 'selected';} ?> value="<?=$k?>"><?=$v[$lang]?></option>
					<? }} ?>
				</select>
			</div>



		<?php }}?>
    
		<? if(!empty($_SESSION['rego']['mn_teams'])){ ?>
		<div class="btn-group permissions" style="display:none">
		<select multiple="multiple" id="selBox-teams">
			<? foreach($teams as $k=>$v){if(in_array($k, explode(',',$_SESSION['rego']['mn_teams']))){ ?>
			<option <? if(in_array($k, explode(',',$_SESSION['rego']['sel_teams']))){echo 'selected';} ?> value="<?=$k?>"><?=$v[$lang]?></option>
			<? }} ?>
    </select>
		</div>
		<? } else { ?> 

		<div class="btn-group permissions" style="display:none">
			<select multiple="multiple" id="selBox-teams">
				<? foreach($teams as $k=>$v){if(in_array($k, explode(',',$_SESSION['rego']['mn_teams']))){ ?>
				<option <? if(in_array($k, explode(',',$_SESSION['rego']['sel_teams']))){echo 'selected';} ?> value="<?=$k?>"><?=$v[$lang]?></option>
				<? }} ?>
		    </select>
		</div>


		<?php }?>
		
		<? if($comp_settings['emp_group']){ ?>
		<? if($_SESSION['rego']['access_group'] == 'all'){ ?>
		<div class="btn-group permissions" style="display:none">
			<button class="dropdown-toggle" data-toggle="dropdown">
				<span><?=$emp_group[$_SESSION['rego']['emp_group']]?></span> <span class="caret"></span>
			</button>
				<div class="dropdown-menu">
					<a class="dropdown-item empGroup" data-id="s" href="#"><?=$emp_group['s']?></a>
					<a class="dropdown-item empGroup" data-id="m" href="#"><?=$emp_group['m']?></a>
				</div>
		</div>
		<? }else{ ?>
		<div class="btn-group"> 
			<button>
				<span><?=$emp_group[$_SESSION['rego']['emp_group']]?></span>
			</button>
		</div>
		<? }} ?>
