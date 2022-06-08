<?
	include(DIR.'files/arrays_'.$_SESSION['rego']['lang'].'.php');
	if(!isset($_GET['sm'])){$_GET['sm'] = 0;}
	$name_position = getFormNamePosition($cid);
	
	/*mb_internal_encoding('UTF-8');
	var_dump(mb_strlen('1234567890'));
	var_dump(mb_strlen('12345678าา'));
	var_dump(mb_substr('12345678าา',-5));
	var_dump(mb_strlen(mb_substr('12345678าา',-5)));
	
	
	
	exit;*/
	
	$edata = getEntityData($_SESSION['rego']['gov_entity']);
	$address = unserialize($edata[$_SESSION['rego']['lang'].'_addr_detail']);
	$sso_codes = unserialize($edata['sso_codes']);
	$branch = sprintf("%06d",$sso_codes[$_SESSION['rego']['gov_branch']]['code']);
	
	$period = $_SESSION['rego']['curr_month'].substr($_SESSION['rego']['year_'.$lang],-2);
	
	$sso_act_max = $pr_settings['sso_act_max'];
	$sso_rate = sprintf("%04d",number_format((float)$pr_settings['sso_rate_emp'], 2, '', ''));
	
	
	$banks = array();
	$tmp = unserialize($edata['banks']);
	if($tmp){
		foreach($tmp as $k=>$v){
			$banks[$v['code']]['name'] = $v['name'];
			$banks[$v['code']]['number'] = $v['number'];
		}
	}
	//var_dump($banks); //exit;
	//var_dump($edata); exit;
	//$bank = '025';
	//$bank = '004';
	if(!$banks && $_GET['sm'] > 44){$_GET['sm'] = 40;}
	
?>	
<style>
	.dashbox-sm .inner i {
		width:24px;
		text-align:center;
	}
</style>

	<h2><i class="fa fa-upload"></i>&nbsp; <?=$lng['Export center']?></h2>		
	
	<div class="main export-center">
		<div style="padding:0 0 0 20px" id="dump"></div>
			
		<div class="hide-800" style="width:300px; position:fixed">	
			
			<div class="dashbox-sm dblue">
				<div class="inner <? if($_GET['sm']==40){ echo 'active';}?>" onclick="window.location.href='index.php?mn=420&sm=40';">
					<i class="fa fa-file-text-o"></i>
					<p><?=$lng['P.N.D.1 text file']?></p>
					<i class="fa fa-caret-right"></i>
				</div>
			</div>
			<div class="dashbox-sm dblue">
				<div class="inner <? if($_GET['sm']==41){ echo 'active';}?>" onclick="window.location.href='index.php?mn=420&sm=41';">
					<i class="fa fa-file-text-o"></i>
					<p>P.N.D.3 text file<? //=$lng['P.N.D.1 text file']?></p>
					<i class="fa fa-caret-right"></i>
				</div>
			</div>
			<div class="dashbox-sm dblue">
				<div class="inner <? if($_GET['sm']==43){ echo 'active';}?>" onclick="window.location.href='index.php?mn=420&sm=43';">
					<i class="fa fa-file-text-o"></i>
					<p><?=$lng['SSO text file']?></p>
					<i class="fa fa-caret-right"></i>
				</div>
			</div>
			<div class="dashbox-sm dblue">
				<div class="inner <? if($_GET['sm']==44){ echo 'active';}?>" onclick="window.location.href='index.php?mn=420&sm=44';">
					<i class="fa fa-file-excel-o"></i>
					<p><?=$lng['SSO Excel file']?></p>
					<i class="fa fa-caret-right"></i>
				</div>
			</div>
			<div class="dashbox-sm dblue">
				<div class="inner <? if($_GET['sm']==42){ echo 'active';}?>" onclick="window.location.href='index.php?mn=420&sm=42';">
					<i class="fa fa-calculator"></i>
					<p><?=$lng['Accounting entries']?></p>
					<i class="fa fa-caret-right"></i>
				</div>
			</div>
			<div class="dashbox-sm dblue">
				<div class="inner <? if($_GET['sm']==53){ echo 'active';}?>" onclick="window.location.href='index.php?mn=420&sm=53';">
					<i class="fa fa-bitcoin"></i>
					<p><?=$lng['Cash payment list']?></p>
					<i class="fa fa-caret-right"></i>
				</div>
			</div>
			
			<div class="dashbox-sm green">
				<div style="position:relative; height:40px" class="inner <? if(!isset($banks['002'])){ echo 'disabled ';} if($_GET['sm']==45){ echo 'active';}?>" <? if(isset($banks['002'])){ ?>onclick="window.location.href='index.php?mn=420&sm=45';"<? } ?>>
					<img style="width:40px; height:40px; position:absolute; top:0; left:0" src="../images/bkb.png" />
					<p style="padding-left:40px; padding-top:2px"><?=$lng['Bangkok Bank']?></p>
					<i class="fa fa-caret-right"></i>
				</div>
			</div>
			<div class="dashbox-sm green">
				<div style="position:relative; height:40px" class="inner <? if(!isset($banks['004'])){ echo 'disabled ';} if($_GET['sm']==46){ echo 'active';}?>" <? if(isset($banks['004'])){ ?>onclick="window.location.href='index.php?mn=420&sm=46';"<? } ?>>
					<img style="width:40px; height:40px; position:absolute; top:0; left:0" src="../images/kkb.png" />
					<p style="padding-left:40px; padding-top:2px"><?=$lng['Kasikorn Bank']?></p>
					<i class="fa fa-caret-right"></i>
				</div>
			</div>
			<div class="dashbox-sm green">
				<div style="position:relative; height:40px" class="inner <? if(!isset($banks['014'])){ echo 'disabled ';} if($_GET['sm']==47){ echo 'active';}?>" <? if(isset($banks['014'])){ ?>onclick="window.location.href='index.php?mn=420&sm=47';"<? } ?>>
					<img style="width:40px; height:40px; position:absolute; top:0; left:0" src="../images/scb.jpg" />
					<p style="padding-left:40px; padding-top:2px"><?=$lng['Siam Commercial Bank']?></p>
					<i class="fa fa-caret-right"></i>
				</div>
			</div>
			<div class="dashbox-sm green">
				<div style="position:relative; height:40px" class="inner <? if(!isset($banks['006'])){ echo 'disabled ';} if($_GET['sm']==48){ echo 'active';}?>" <? if(isset($banks['004'])){ ?>onclick="window.location.href='index.php?mn=420&sm=48';"<? } ?>>
					<img style="width:40px; height:40px; position:absolute; top:0; left:0" src="../images/ktb2.jpg" />
					<p style="padding-left:40px; padding-top:2px"><?=$lng['Krungthai Bank']?></p>
					<i class="fa fa-caret-right"></i>
				</div>
			</div>
			<div class="dashbox-sm green">
				<div style="position:relative; height:40px" class="inner <? if(!isset($banks['011'])){ echo 'disabled ';} if($_GET['sm']==49){ echo 'active';}?>" <? if(isset($banks['011'])){ ?>onclick="window.location.href='index.php?mn=420&sm=49';"<? } ?>>
					<img style="width:40px; height:40px; position:absolute; top:0; left:0" src="../images/tmb2.png" />
					<p style="padding-left:40px; padding-top:2px"><?=$lng['TMB Bank']?></p>
					<i class="fa fa-caret-right"></i>
				</div>
			</div>
			
			
			<div class="dashbox-sm green">
				<div style="position:relative; height:40px" class="inner <? if(!isset($banks['065'])){ echo 'disabled ';} if($_GET['sm']==52){ echo 'active';}?>" <? if(isset($banks['065'])){ ?>onclick="window.location.href='index.php?mn=420&sm=52';"<? } ?>>
					<img style="width:40px; height:40px; position:absolute; top:0; left:0" src="../images/Thanachart.png" />
					<p style="padding-left:40px; padding-top:2px"><?=$lng['Thanachart Bank']?></p>
					<i class="fa fa-caret-right"></i>
				</div>
			</div>
			
			
			<div class="dashbox-sm green">
				<div style="position:relative; height:40px" class="inner <? if(!isset($banks['024'])){ echo 'disabled ';} if($_GET['sm']==50){ echo 'active';}?>" <? if(isset($banks['024'])){ ?>onclick="window.location.href='index.php?mn=420&sm=50';"<? } ?>>
					<img style="width:40px; height:40px; position:absolute; top:0; left:0" src="../images/uob.png" />
					<p style="padding-left:40px; padding-top:2px"><?=$lng['UOB Bank']?></p>
					<i class="fa fa-caret-right"></i>
				</div>
			</div>
			<div class="dashbox-sm green">
				<div style="position:relative; height:40px" class="inner <? if(!isset($banks['025'])){ echo 'disabled ';} if($_GET['sm']==51){ echo 'active';}?>" <? if(isset($banks['025'])){ ?>onclick="window.location.href='index.php?mn=420&sm=51';"<? } ?>>
					<img style="width:40px; height:40px; position:absolute; top:0; left:0" src="../images/kungsri.png" />
					<p style="padding-left:40px; padding-top:2px"><?=$lng['Kungsri Bank']?></p>
					<i class="fa fa-caret-right"></i>
				</div>
			</div>
		
		</div>
		
		<div class="export-forms">
			
			<?
				switch($_GET['sm']){
					case 40: include('pnd1_textfile.php'); break;
					case 41: include('pnd3_textfile.php'); break;
					case 43: include('sso_textfile.php'); break;
					case 44: include('sso_attach_excel.php'); break;
					case 42: include('accounting_entries.php'); break;
					case 53: include('cash_payment_list.php'); break;
					
					case 45: include('bbl_payment_list.php'); break;
					case 46: include('kkb_payment_list.php'); break;
					case 47: include('scb_payment_list.php'); break;
					case 48: include('ktb_payment_list.php'); break;
					case 49: include('tmb_payment_list.php'); break;
					case 50: include('uob_payment_list.php'); break;
					case 51: include('ksb_payment_list.php'); break;
					case 52: include('tha_payment_list.php'); break;
				}
			?>	

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
			orientation: 'bottom',
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
			orientation: 'bottom',
			language: lang,//lang+'-th',
			todayHighlight: true,
			daysOfWeekDisabled: "0,6",
		}).on('changeDate', function(e) {
			 $('#sDate').html(e.format('ddmmyy'));
		});

	});
	
</script>


















