<?

	if(!isset($_GET['sm'])){$_GET['sm'] = 0;}
	$name_position = getFormNamePosition($cid);
	//var_dump($name_position);
	$dataCheck = checkDataForGovForms($_SESSION['rego']['selpr_entities']);
	
	$edata = getEntityData($_SESSION['rego']['gov_entity']);
	$address = unserialize($edata[$_SESSION['rego']['lang'].'_addr_detail']);
	$sso_codes = unserialize($edata['sso_codes']);
	$pr_months = getUsedPayrollMonths();
	//var_dump($pr_months); exit;

	// echo '<pre>';
	// print_r($_SESSION['rego']);
	// echo '</pre>';
	// 	die();
?>	

	<h2>
		<i class="fa fa-file-text-o"></i>&nbsp; <?=$lng['Government forms']?>
		
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?=$lng['Period']?> :
		<select id="govMonth" style="display:inline-block; padding:2px 8px !important; border:0; background:transparent; font-size:16px; color:#003399; font-weight:600; margin-left:-8px">
			<? foreach($pr_months as $k=>$v){ ?>
				<option style="font-size:13px; color:#333" <? if($_SESSION['rego']['gov_month'] == $k){echo 'selected';}?> value="<?=$k?>"><?=$months[$k]?></option>
			<? } ?>
		</select>

		<!-- <? if(!$locked){ ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?=$lng['Form date']?> : <input id="formDate" readonly type="text" class="datepick" value="<?=$_SESSION['rego']['formdate']['endate']?>"><? } ?> -->

		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?=$lng['Form date']?> : <input id="formDate" readonly type="text" class="datepick" value="<?=$_SESSION['rego']['formdate']['endate']?>">
		
		
	
	</h2>	
		
	<div class="main gov-forms">
		<div style="padding:0 0 0 20px" id="dump"></div>
			
		<div class="hide-800" style="width:370px; position:fixed">
			<div class="dashbox-sm green">
				<div class="inner <? if($_GET['sm']==41){ echo 'active';}?>" onclick="window.location.href='index.php?mn=430&sm=41';">
					<i class="fa fa-paperclip"></i>
					<p><?=$lng['P.N.D.1 Monthly Attachment']?></p>
					<i class="fa fa-caret-right"></i>
				</div>
			</div>
			<div class="dashbox-sm green">
				<div class="inner <? if($_GET['sm']==40){ echo 'active';}?>" onclick="window.location.href='index.php?mn=430&sm=40';">
					<i class="fa fa-file-pdf-o"></i>
					<p><?=$lng['P.N.D.1 Monthly']?></p>
					<i class="fa fa-caret-right"></i>
				</div>
			</div>

			<div class="dashbox-sm dblue">
				<div class="inner <? if($_GET['sm']==51){ echo 'active';}?>" onclick="window.location.href='index.php?mn=430&sm=51';">
					<i class="fa fa-paperclip"></i>
					<p><?=$lng['P.N.D.3 Monthly Attachment']?></p>
					<i class="fa fa-caret-right"></i>
				</div>
			</div>
			<div class="dashbox-sm dblue">
				<div class="inner <? if($_GET['sm']==50){ echo 'active';}?>" onclick="window.location.href='index.php?mn=430&sm=50';">
					<i class="fa fa-file-pdf-o"></i>
					<p><?=$lng['P.N.D.3 Monthly']?></p>
					<i class="fa fa-caret-right"></i>
				</div>
			</div>

			<div class="dashbox-sm orange">
				<div class="inner <? if($_GET['sm']==44){ echo 'active';}?>" onclick="window.location.href='index.php?mn=430&sm=44';">
					<i class="fa fa-paperclip"></i>
					<p><?=$lng['SSO Attachment']?></p>
					<i class="fa fa-caret-right"></i>
				</div>
			</div>
			<div class="dashbox-sm orange">
				<div class="inner <? if($_GET['sm']==43){ echo 'active';}?>" onclick="window.location.href='index.php?mn=430&sm=43';">
					<i class="fa fa-file-pdf-o"></i>
					<p><?=$lng['SSO Form']?></p>
					<i class="fa fa-caret-right"></i>
				</div>
			</div>

			<div class="dashbox-sm reds">
				<div class="inner <? if($_GET['sm']==45){ echo 'active';}?>" onclick="window.location.href='index.php?mn=430&sm=45';">
					<i class="fa fa-paperclip"></i>
					<p><?=$lng['PVF Attachment']?></p>
					<i class="fa fa-caret-right"></i>
				</div>
			</div>
			<div class="dashbox-sm reds">
				<div class="inner <? if($_GET['sm']==46){ echo 'active';}?>" onclick="window.location.href='index.php?mn=430&sm=46';">
					<i class="fa fa-file-pdf-o"></i>
					<p><?=$lng['PVF Form']?></p>
					<i class="fa fa-caret-right"></i>
				</div>
			</div>

			<div class="dashbox-sm dblue" style="display:none">
				<div class="inner" onclick="window.location.href='<?=ROOT?>payroll/download_sso_textfile.php';">
					<i class="fa fa-file-text-o"></i>
					<p><?=$lng['Download SSO text file']?></p>
					<i class="fa fa-caret-right"></i>
				</div>
			</div>
			<div class="dashbox-sm dblue" style="display:none">
				<div class="inner" onclick="window.location.href='<?=ROOT?>payroll/download_pnd1_textfile.php';">
					<i class="fa fa-file-text-o"></i>
					<p><?=$lng['Download P.N.D.1 text file']?></p>
					<i class="fa fa-caret-right"></i>
				</div>
			</div>
			<div class="dashbox-sm dblue" style="display:none">
				<div class="inner" onclick="window.location.href='<?=ROOT?>payroll/download_sso_attach_excel.php';">
					<i class="fa fa-file-excel-o"></i>
					<p><?=$lng['Download SSO attachment Excel']?></p>
					<i class="fa fa-caret-right"></i>
				</div>
			</div>
			
			<div class="dashbox-sm purple">
				<div class="inner <? if($_GET['sm']==47){ echo 'active';}?>" onclick="window.location.href='index.php?mn=430&sm=47';">
					<i class="fa fa-file-pdf-o"></i>
					<p><?=$lng['Annual Withholding Tax Certificate']?></p>
					<i class="fa fa-caret-right"></i>
				</div>
			</div>
			
			<div class="dashbox-sm purple">
				<div class="inner <? if($_GET['sm']==48){ echo 'active';}?>" onclick="window.location.href='index.php?mn=430&sm=48';">
					<i class="fa fa-file-pdf-o"></i>
					<p><?=$lng['P.N.D.1 Attachment Kor']?>  <? //=$_SESSION['rego']['year_'.$lang]?></p>
					<i class="fa fa-caret-right"></i>
				</div>
			</div>
			
			<div class="dashbox-sm purple">
				<div class="inner <?php if($_GET['sm']==49){ echo 'active';}?>" onclick="window.location.href='index.php?mn=430&sm=49';">
					<i class="fa fa-file-pdf-o"></i>
					<p><?=$lng['P.N.D.1 Kor']?></p>
					<i class="fa fa-caret-right"></i>
				</div>
			</div>
			
		</div>
		
		<div class="government-forms">
		<?	$helpfile = getHelpfile(430);
			switch($_GET['sm']){
				case 40: include('show_pnd1_monthly_'.$lang.'.php'); break;
				case 41: include('show_pnd1_monthly_attach_'.$lang.'.php'); break;
				case 50: include('show_pnd3_'.$lang.'.php'); break;
				case 51: include('show_pnd3_attach_'.$lang.'.php'); break;
				case 43: include('show_sso_form_'.$lang.'.php'); break;
				case 44: include('show_sso_attach_'.$lang.'.php'); break;
				case 46: include('show_pvf_form_'.$lang.'.php'); break;
				case 45: include('show_pvf_attach_'.$lang.'.php'); break;
				case 47: include('show_wht_certificate.php'); break;
				case 48: include('show_pnd1_attach_kor_'.$lang.'.php'); break;
				case 49: include('show_pnd1kor_'.$lang.'.php'); break;
			}
		?>	
		</div>

	</div>
	
	<!-- Modal Incomplete -->
	<div class="modal fade" id="completeModal" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header" style="background:#a00; color:#fff">
					<h5 class="modal-title"><i class="fa fa-exclamation"></i>&nbsp; Missing data for Government forms<? //=$lng['Contact']?></h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body modal-tabs" style="padding:15px 20px 20px 20px;max-height:550px; overflow-Y:auto">
						<?=$dataCheck?>
				</div>
				<div class="clear"></div>
			 </div>
		</div>
	</div>
	
						
<script type="text/javascript">
	
	$(document).ready(function() {
		//alert(lang)
		var dataCheck = <?=json_encode($dataCheck)?>
		
		if(dataCheck){
			$('#completeModal').modal('toggle');
		}
		
		$('#govMonth').on('change', function() {
			//alert(this.value)
			$.ajax({
				url: "ajax/change_gov_month.php",
				data: {month: this.value},
				//dataType: 'json',
				success: function(data) {
					//$('#dump').html(data)
					window.location.reload();
				},
				error: function (xhr, ajaxOptions, thrownError) {
					alert(thrownError);
				}
			});
		})
		
		$('#formDate').on('change', function() {
			//alert(this.value)
			$.ajax({
				url: "ajax/update_formdate.php",
				data: {date: this.value},
				dataType: 'json',
				success: function(data) {
					//$('#dump').html(data)
					$('.formDay').html(data.d)
					if(lang == 'en'){
						$('.formMonth').html(data.m)
						$('.formtYear').html(data.eny)
						$('.formDate').html(data.endate)
					}else{
						$('.formMonth').html(data.thm)
						$('.formtYear').html(data.thy)
						$('.formDate').html(data.thdate)
					}
				},
				error: function (xhr, ajaxOptions, thrownError) {
					alert(thrownError);
				}
			});
		})
		
		$('.formName, .formPosition').on('change', function() {
			//alert()
			$.ajax({
				url: "ajax/update_formdata.php",
				data: {form_name: $('.formName').val(), form_position: $('.formPosition').val()},
				success: function(response) {
					//$('#dump').html(response)
				},
				error: function (xhr, ajaxOptions, thrownError) {
					alert(thrownError);
				}
			});
		})
		


	});
	
</script>


















