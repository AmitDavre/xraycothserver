<?
	include(DIR.'files/arrays_'.$_SESSION['rego']['lang'].'.php');
	if(!isset($_GET['sm'])){$_GET['sm'] = 0;}
	$name_position = getFormNamePosition($cid);
	//var_dump($compinfo);
	$bank = $compinfo['bank_name'];
	//$bank = '025';
	//$bank = '004';
	
?>	
<style>
.A4form {
	width:900px;
	margin:0;
	margin-top:20px;
	margin-bottom:50px; 
	background:#fff; 
	padding:30px; 
	box-shadow:0 0 10px rgba(0,0,0,0.4); 
	position:relative;
	min-height:500px;
}
</style>

	<div class="widget autoheight" style="margin-bottom:15px;">
		<h2><i class="fa fa-upload"></i>&nbsp; <?=$lng['Export center']?></h2>		
		<div class="widget_body" style="padding-top:0">
			
			<table border="0" style="width:100%">
			<tr>
			<td style="vertical-align:top; padding-top:18px; min-width:300px;">
				
				<div class="dashbox-sm dblue">
					<div class="inner <? if($_GET['sm']==40){ echo 'active';}?>" onclick="window.location.href='index.php?mn=420&sm=40';">
						<i class="fa fa-file-text-o"></i>
						<p><?=$lng['SSO text file']?></p>
						<i class="fa fa-caret-right"></i>
					</div>
				</div>
				<div class="dashbox-sm dblue">
					<div class="inner <? if($_GET['sm']==41){ echo 'active';}?>" onclick="window.location.href='index.php?mn=420&sm=41';">
						<i class="fa fa-file-text-o"></i>
						<p><?=$lng['P.N.D.1 text file']?></p>
						<i class="fa fa-caret-right"></i>
					</div>
				</div>
				<div class="dashbox-sm dblue">
					<div class="inner <? if($_GET['sm']==42){ echo 'active';}?>" onclick="window.location.href='index.php?mn=420&sm=42';">
						<i class="fa fa-file-excel-o"></i>
						<p><?=$lng['SSO Excel file']?></p>
						<i class="fa fa-caret-right"></i>
					</div>
				</div>
				<div class="dashbox-sm dblue">
					<div class="inner <? if($_GET['sm']==43){ echo 'active';}?>" onclick="window.location.href='index.php?mn=420&sm=43';">
						<i class="fa fa-calculator"></i>
						<p><?=$lng['Accounting entries']?></p>
						<i class="fa fa-caret-right"></i>
					</div>
				</div>
				
				<div class="dashbox-sm green">
					<div style="position:relative; height:40px" class="inner <? if($bank != '002'){ echo 'disabled ';} if($_GET['sm']==45){ echo 'active';}?>" <? if($bank == '002'){ ?>onclick="window.location.href='index.php?mn=420&sm=45';"<? } ?>>
						<img style="width:40px; height:40px; position:absolute; top:0; left:0" src="../../images/bkb.png" />
						<p style="padding-left:40px; padding-top:2px"><?=$lng['Bangkok Bank']?></p>
						<i class="fa fa-caret-right"></i>
					</div>
				</div>
				<div class="dashbox-sm green">
					<div style="position:relative; height:40px" class="inner <? if($bank != '004'){ echo 'disabled ';} if($_GET['sm']==46){ echo 'active';}?>" <? if($bank == '004'){ ?>onclick="window.location.href='index.php?mn=420&sm=46';"<? } ?>>
						<img style="width:40px; height:40px; position:absolute; top:0; left:0" src="../../images/kkb.png" />
						<p style="padding-left:40px; padding-top:2px"><?=$lng['Kasikorn Bank']?></p>
						<i class="fa fa-caret-right"></i>
					</div>
				</div>
				<div class="dashbox-sm green">
					<div style="position:relative; height:40px" class="inner <? if($bank != '014'){ echo 'disabled ';} if($_GET['sm']==47){ echo 'active';}?>" <? if($bank == '014'){ ?>onclick="window.location.href='index.php?mn=420&sm=47';"<? } ?>>
						<img style="width:40px; height:40px; position:absolute; top:0; left:0" src="../../images/scb.jpg" />
						<p style="padding-left:40px; padding-top:2px"><?=$lng['Siam Commercial Bank']?></p>
						<i class="fa fa-caret-right"></i>
					</div>
				</div>
				<div class="dashbox-sm green">
					<div style="position:relative; height:40px" class="inner <? if($bank != '006'){ echo 'disabled ';} if($_GET['sm']==48){ echo 'active';}?>" <? if($bank == '006'){ ?>onclick="window.location.href='index.php?mn=420&sm=48';"<? } ?>>
						<img style="width:40px; height:40px; position:absolute; top:0; left:0" src="../../images/ktb2.jpg" />
						<p style="padding-left:40px; padding-top:2px"><?=$lng['Krungthai Bank']?></p>
						<i class="fa fa-caret-right"></i>
					</div>
				</div>
				<div class="dashbox-sm green">
					<div style="position:relative; height:40px" class="inner <? if($bank != '011'){ echo 'disabled ';} if($_GET['sm']==49){ echo 'active';}?>" <? if($bank == '011'){ ?>onclick="window.location.href='index.php?mn=420&sm=49';"<? } ?>>
						<img style="width:40px; height:40px; position:absolute; top:0; left:0" src="../../images/tmb2.png" />
						<p style="padding-left:40px; padding-top:2px"><?=$lng['TMB Bank']?></p>
						<i class="fa fa-caret-right"></i>
					</div>
				</div>
				<div class="dashbox-sm green">
					<div style="position:relative; height:40px" class="inner <? if($bank != '024'){ echo 'disabled ';} if($_GET['sm']==50){ echo 'active';}?>" <? if($bank == '024'){ ?>onclick="window.location.href='index.php?mn=420&sm=50';"<? } ?>>
						<img style="width:40px; height:40px; position:absolute; top:0; left:0" src="../../images/uob.png" />
						<p style="padding-left:40px; padding-top:2px"><?=$lng['UOB Bank']?></p>
						<i class="fa fa-caret-right"></i>
					</div>
				</div>
				<div class="dashbox-sm green">
					<div style="position:relative; height:40px" class="inner <? if($bank != '025'){ echo 'disabled ';} if($_GET['sm']==51){ echo 'active';}?>" <? if($bank == '025'){ ?>onclick="window.location.href='index.php?mn=420&sm=51';"<? } ?>>
						<img style="width:40px; height:40px; position:absolute; top:0; left:0" src="../../images/kungsri.png" />
						<p style="padding-left:40px; padding-top:2px"><?=$lng['Kungsri Bank']?></p>
						<i class="fa fa-caret-right"></i>
					</div>
				</div>
				
			</td>
			<td style="width:90%; padding:0 5px 0 30px; vertical-align:top; overflow:hidden; text-align:left">
				
				<?
					switch($_GET['sm']){
						case 40: include('sso_textfile.php'); break;
						case 41: include('pnd1_textfile.php'); break;
						case 42: include('sso_attach_excel.php'); break;
						case 43: include('accounting_entries.php'); break;
						
						case 45: include('bbl_textfile.php'); break;
						case 46: include('kkb_textfile.php'); break;
						case 47: include('scb_textfile.php'); break;
						case 48: include('ktb_textfile.php'); break;
						case 49: include('tmb_textfile.php'); break;
						case 50: include('uob_textfile.php'); break;
						case 51: include('ksb_textfile.php'); break;
					}
				?>	
			</td>
		</tr>
		</table>	

		</div>
	</div>
		
						
<script type="text/javascript">
	
	$(document).ready(function() {
		//alert(lang)
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
		
		$('#prdate').datepicker({
			format: "dd-mm-yyyy",
			autoclose: true,
			inline: true,
			language: lang,//lang+'-th',
			todayHighlight: true,
			//daysOfWeekDisabled: "0,6",
		}).on('changeDate', function(e) {
			 $('#pDate').html(e.format('ddmmyy'));
		});
		$('#smdate').datepicker({
			format: "dd-mm-yyyy",
			autoclose: true,
			inline: true,
			language: lang,//lang+'-th',
			todayHighlight: true,
			daysOfWeekDisabled: "0,6",
		}).on('changeDate', function(e) {
			 $('#sDate').html(e.format('ddmmyy'));
		});
		
	});
	
</script>


















