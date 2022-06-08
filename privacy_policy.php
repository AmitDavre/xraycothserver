<?
	$content = '';
	$res = $dbx->query("SELECT * FROM rego_privacy_policy");
	if($row = $res->fetch_assoc()){
		$content = $row[$lang.'_content'];
	}	
?>	
	
	<div class="terms-content">
		<div class="row">
			<div class="col-12 xcol-xl-9">
				<div class="card flex-fill">
					<div class="card-header">
						<h5 class="card-title mb-0" style="font-weight:600"><?=$lng['Privacy policy']?></h5>
					</div>
					<div class="card-body">
						<?=$content?>	
					</div>
				</div>
			</div>
		</div>
	</div>
	
	
	
	
		
						
