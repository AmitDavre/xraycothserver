		
		<? /*if(!empty($_SESSION['rego']['mn_entities'])){ ?>
		<div class="btn-group" style="opacity:0">	
    <select id="selBox-entities">
			<? foreach($entities as $k=>$v){if(in_array($k, explode(',', $_SESSION['rego']['mn_entities']))){ ?>
			<option <? if(in_array($k, explode(',', $_SESSION['rego']['selpr_entities']))){echo 'selected';} ?> value="<?=$k?>"><?=$v[$lang]?></option>
			<? }} ?>
    </select>
		</div>	
		<? } */?>

		<? if(!empty($_SESSION['rego']['mn_entities'])){ ?>
		<div class="btn-group" style="opacity:0">	
		    <select multiple="multiple" id="selBox-entities">
					<? foreach($entities as $k=>$v){if(in_array($k, explode(',', $_SESSION['rego']['mn_entities']))){ ?>
					<option <? if(in_array($k, explode(',', $_SESSION['rego']['selpr_entities']))){echo 'selected';} ?> value="<?=$k?>"><?=$v[$lang]?></option>
					<? }} ?>
		    </select>
		</div>	
		<? } ?>
		
		<? if(!empty($_SESSION['rego']['mn_branches'])){ ?>	
		<div class="btn-group" style="opacity:0">
    <select multiple="multiple" id="selBox-branches">
			<? foreach($branches as $k=>$v){if(in_array($k, explode(',', $_SESSION['rego']['mn_branches']))){ ?>
			<option <? if(in_array($k, explode(',', $_SESSION['rego']['selpr_branches']))){echo 'selected';} ?> value="<?=$k?>"><?=$v[$lang]?></option>
			<? }} ?>
    </select>
    </div>
		<? } ?>
		
		<? if(count($divisions) > 1){
			if(!empty($_SESSION['rego']['mn_divisions'])){ ?>
			<div class="btn-group" style="opacity:0">
			<select multiple="multiple" id="selBox-divisions">
				<? foreach($divisions as $k=>$v){if(in_array($k, explode(',', $_SESSION['rego']['mn_divisions']))){ ?>
				<option <? if(in_array($k, explode(',', $_SESSION['rego']['selpr_divisions']))){echo 'selected';} ?> value="<?=$k?>"><?=$v[$lang]?></option>
				<? }} ?>
			</select>
			</div>
		<? }} ?>
		
		<? if(count($departments) > 1){
			if(!empty($_SESSION['rego']['mn_departments'])){ ?>
			<div class="btn-group" style="opacity:0">
			<select multiple="multiple" id="selBox-departments">
				<? foreach($departments as $k=>$v){if(in_array($k, explode(',',$_SESSION['rego']['mn_departments']))){ ?>
				<option <? if(in_array($k, explode(',',$_SESSION['rego']['selpr_departments']))){echo 'selected';} ?> value="<?=$k?>"><?=$v[$lang]?></option>
				<? }} ?>
			</select>
			</div>
		<? }} ?>
    
		<? if(!empty($_SESSION['rego']['mn_teams'])){ ?>
		<div class="btn-group" style="opacity:0">
		<select multiple="multiple" id="selBox-teams">
			<? foreach($teams as $k=>$v){if(in_array($k, explode(',',$_SESSION['rego']['mn_teams']))){ ?>
			<option <? if(in_array($k, explode(',',$_SESSION['rego']['selpr_teams']))){echo 'selected';} ?> value="<?=$k?>"><?=$v[$lang]?></option>
			<? }} ?>
    </select>
		</div>
		<? } ?>
		
