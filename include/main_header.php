	<?php 

		// echo '<pre>';
		// print_r($lang);
		// echo '</pre>';

		// die();
	?>
	<? if($comp_settings['txt_color'] == 'blue'){ ?>
	<link rel="stylesheet" href="<?=ROOT?>assets/css/pkf.css?<?=time()?>">
	<? } ?>
	<div class="header">
		<table border="0">
			<tr>
				<td class="header-logo" style="padding-right:15px">
					<img src="<?=ROOT.$compinfo['logofile'].'?'.time()?>" />
				</td>
				<td class="header-client">
					<? if(isset($_SESSION['rego']['cid'])){ ?>
							<?=$compinfos[$lang.'_compname']?>&nbsp;-&nbsp;<?=$rego?> 
					<? } ?>
				</td>
				<td style="width:90%; padding-left:20px"><button onmouseover="$('.mnSumoSelect').addClass('open');"onmouseout="$('.mnSumoSelect').removeClass('open');" class="btn btn-outline-success btn-xs">Selections</button></td>

				<td>
					<button type="button" class="btn btn-sm btn-primary"><a style="color:#fff;" href="<?php echo ROOT;?>index.php?mn=2" target="_blank"><?=$lng['Open new window']?></a></button>
				</td>
				<td class="header-date">
					<?=$_SESSION['rego']['cur_date']?>
				</td>
				<? if($lang=='en'){ ?>
				<td>
					<a data-lng="th" class="langbutton <? if($lang=='th'){echo 'activ';} ?>"><img src="<?=ROOT?>images/flag_th.png"></a>
				</td>
				<? }else{ ?>
				<td>
					<a data-lng="en" class="langbutton <? if($lang=='en'){echo 'activ';} ?>"><img src="<?=ROOT?>images/flag_en.png"></a>
				</td>
				<? } ?>
				<td style="padding:0 10px">
					<? if(!isset($_SESSION['RGadmin']['id'])){ ?>
						<button class="btn btn-logout logout"><i class="fa fa-power-off"></i></button>
					<? } ?>
				</td>
			</tr>
		</table>
	</div>
