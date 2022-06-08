	<?php 


		$res = $dba->query("SELECT * FROM rego_privacy_policy");
		if($row = $res->fetch_assoc()){
			$th_content = $row['th_content'];
			$en_content = $row['en_content'];
			if($lang == 'en')
			{
				$contentValue = $row['en_content'];
			}
			else
			{
				$contentValue = $row['th_content'];
			}
		}	

	?>
		<div class="container-fluid" style="xborder:1px solid red">
			<div class="row" style="xborder:1px solid green; padding:20px 25px">
				<div class="col-12" style="padding-bottom: 50px;">
					<div class="page-header">
						<h4 class=""><?=$lng['Privacy Policy Consent']?></h4>
					</div>
					<div class="divider-icon">
						<div><i class="fa fa-user-secret fa-lg"></i></div>
					</div>
					
						<fieldset>
							
							<div class="form-group">
								<p> <?php echo $contentValue; ?></p>
							</div>		

						</fieldset>
						
				</div>
			</div>
		</div>	