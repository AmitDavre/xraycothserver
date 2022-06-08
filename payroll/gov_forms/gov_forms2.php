<?

	if(!isset($_GET['sm'])){$_GET['sm'] = 0;}
	$name_position = getFormNamePosition($cid);
	//var_dump($name_position);
	$dataCheck = checkDataForGovForms();
	//var_dump($dataCheck); exit;
	
?>	
<style>
.A4form {
	width:900px;
	margin:0;
	margin:0;
	background:#fff; 
	padding:30px; 
	box-shadow:0 0 10px rgba(0,0,0,0.4); 
	position:relative;
	min-height:500px;
}
</style>

	<h2><i class="fa fa-file-text-o"></i>&nbsp; <?=$lng['Government forms']?><? if(!$locked){ ?> - <?=$lng['Form date']?> : <input id="formDate" readonly style="padding:0; display:inline-block; background:transparent; border:1px; width:90px; font-size:16px; cursor:pointer" placeholder="Print date" type="text" class="datepick" value="<?=$_SESSION['rego']['formdate']['endate']?>"> <a onClick="$('#formDate').focus()"><i class="fa fa-edit"></i></a><? } ?></h2>	
		
	<div class="main" style="padding-top:20px; top:130px">
		<div style="padding:0 0 0 20px" id="dump"></div>
			
		<div style="width:370px; position:fixed">
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
		
		<div style="padding:0 0 10px 20px; margin-left:370px">
		<?	$helpfile = getHelpfile(430);
			switch($_GET['sm']){
				case 40: include('show_pnd1_monthly_'.$lang.'.php'); break;
				case 41: include('show_pnd1_monthly_attach_'.$lang.'.php'); break;
				case 43: include('show_sso_form_'.$lang.'.php'); break;
				case 44: include('show_sso_attach_'.$lang.'.php'); break;
				case 46: include('show_pvf_form_'.$lang.'.php'); break;
				case 45: include('show_pvf_attach_'.$lang.'.php'); break;
				case 47: include('show_pnd1_year.php'); break;
				case 48: include('show_pnd1_attach_kor_'.$lang.'.php'); break;
				case 49: include('show_pnd1kor_'.$lang.'.php'); break;
			}
		?>	
		</div>

	</div>
	
	<!-- Modal Incomplete -->
	<div class="modal fade" id="completeModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" xdata-backdrop="static" xdata-keyboard="false">
		 <div class="modal-dialog" style="width:450px">
			  <div class="modal-content">
					<div class="modal-header" style="background:#a00; color:#fff" >
						<div type="button" class="close" data-dismiss="modal">
							<span aria-hidden="true"><i class="fa fa-times"></i></span>
							<span class="sr-only"><?=$lng['Close']?></span>                
						</div>
						<h4 class="modal-title" id="myModalLabel"><i class="fa fa-exclamation"></i>&nbsp; Missing data for Government forms<? //=$lng['Contact']?></h4>
					</div>
					<div class="modal-body" style="padding:15px 20px 20px 20px">
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
		
		$('#formDate').on('change', function() {
			//alert(this.value)
			$.ajax({
				url: ROOT+"payroll/ajax/update_formdate.php",
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
				url: ROOT+"payroll/ajax/update_formdata.php",
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


















