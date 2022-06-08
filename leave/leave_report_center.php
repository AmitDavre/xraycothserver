<?
	if(!isset($_GET['sm'])){$_GET['sm'] = 0;}
	$title = getAppMetadata('title', $lang);
	//var_dump($title);
	$report_list[1] = 'Leave record';
	$report_list[] = 'Leave summary per person';
	$report_list[] = 'Leave summary per Branch/Department';
	$report_list[] = 'Sick leave record per person';
	$report_list[] = 'Leave comparison per month';
	$report_list[] = 'Leave ...';
	
	$attendance_list[1] = 'Attendance record';
	$attendance_list[] = 'Attendance summary per person';
	$attendance_list[] = 'Attendance summary per Branch/Department';
	$attendance_list[] = 'Attendance ...';
	$attendance_list[] = 'Attendance ...';
	$attendance_list[] = 'Attendance ...';
	
	
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
	min-height:1000px;
}
</style>

	<div class="widget autoheight" style="margin-bottom:15px">
		<h2><i class="fa fa-file-pdf-o"></i>&nbsp; Report center <span style="float:right">Leave period :  <?=date('d/m/Y', strtotime($leave_periods[$_SESSION['xhr']['cur_month']]['start']))?> - <?=date('d/m/Y', strtotime($leave_periods[$_SESSION['xhr']['cur_month']]['end']))?></h2>		
		<div class="widget_body">
			<div class="widget_inner" style="padding:7px 25px 25px">
				
				<table border="0" style="width:100%; margin-top:10px"><tr>
					<td>
						<div class="searchFilter" style="margin-bottom:0px; width:150px">
							<input style="width:" placeholder="<?=$lng['Filter']?> employee" id="searchFilter" class="sFilter" type="text" />
							<button id="clearSearchbox" type="button" class="clearFilter btn but-default but-sm"><i class="fa fa-times"></i></button>
						</div>
					</td>
					<td style="padding-left:10px">
						<select id="branchFilter" style="float:left; padding:5px 7px 4px; border:1px #ccc solid; border-radius:2px">
							<option value="">All Branches</option>
						<? foreach($branches as $k=>$v){
								echo '<option value="'.$k.'">'.$v.'</option>';
							} ?>
						</select>
					</td>
					<td style="padding-left:10px">
						<select id="groupFilter" style="float:left; padding:5px 7px 4px; border:1px #ccc solid; border-radius:2px">
							<option value="">All Groups</option>
						<? foreach($groups as $k=>$v){
								echo '<option value="'.$k.'">'.$v.'</option>';
							} ?>
						</select>
					</td>
					<td style="padding-left:10px">
						<select id="depFilter" style="float:left; padding:5px 7px 4px; border:1px #ccc solid; border-radius:2px">
							<option value="">All departments</option>
						<? foreach($departments as $k=>$v){
								echo '<option value="'.$k.'">'.$v.'</option>';
							} ?>
						</select>
					</td>
					<td style="padding-left:10px">
						<select id="monthFilter" style="float:left; padding:5px 7px 4px; border:1px #ccc solid; border-radius:2px">
							<option value="">All months</option>
						<? foreach($months as $k=>$v){
								echo '<option value="'.$k.'">'.$v.'</option>';
							} ?>
						</select>
					</td>
					<td style="padding-left:10px">
						<div class="dpicker">
							<input readonly placeholder="From" class="xdate_month" id="from" style="float:left; padding:6px 10px 5px; border:1px #ccc solid; border-radius:2px; width:120px" type="text" value="<?=$leave_period_start?>" />
							<button onclick="$('#from').focus()" type="button"><i class="fa fa-calendar"></i></button>
						</div>
					</td>
					<td style="padding-left:10px">
						<div class="dpicker">
							<input readonly placeholder="Until" class="xdate_month" id="until" style="float:left; padding:6px 10px 5px; border:1px #ccc solid; border-radius:2px; width:120px" type="text" value="<?=$leave_period_end?>" />
							<button onclick="$('#until').focus()" type="button"><i class="fa fa-calendar"></i></button>
						</div>
					</td>
					<td style="padding-left:10px">
						<button type="button" class="but but-primary but-sm"><i class="fa fa-file-pdf-o"></i>&nbsp; Show</button>
					</td>
					<td style="width:50%"></td>
					<td style="padding-left:10px">
						<button type="button" class="but but-primary but-sm"><i class="fa fa-file-pdf-o"></i>&nbsp; PDF</button>
					</td>
					<td style="padding-left:10px">
						<button type="button" class="but but-primary but-sm"><i class="fa fa-file-excel-o"></i>&nbsp; Excel</button>
					</td>
				
				</tr></table>
			
			
			
			<table border="0" style="width:100%">
				<tr>
					<td style="vertical-align:top; padding-top:18px;">
				
					<? foreach($report_list as $v){ ?>
						<div class="dashbox5 mod_leave">
							<div class="inner <? //if($_GET['sm']==41){ echo 'active';}?>" onclick="window.location.href='#';">
								<i class="fa fa-file-text-o"></i>
								<p><?=$v?></p>
								<i class="fa fa-caret-right"></i>
							</div>
						</div>
					<? } ?>
					
					<? foreach($attendance_list as $v){ ?>
						<div class="dashbox5 purple">
							<div class="inner <? //if($_GET['sm']==41){ echo 'active';}?>" onclick="window.location.href='#';">
								<i class="fa fa-file-text-o"></i>
								<p><?=$v?></p>
								<i class="fa fa-caret-right"></i>
							</div>
						</div>
					<? } ?>
					
					<img style="width:380px; height:0" src="../images/blank.gif" />
				</td>
				<td style="width:90%; padding:0 5px 0 30px; vertical-align:top; overflow:hidden; text-align:left">
					
					<div class="A4form"></div>
				<?
					switch($_GET['sm']){
						/*case 40: include('show_pnd1.php'); break;
						case 41: include('show_pnd1_attach_'.$lang.'.php'); break;
						case 43: include('show_sso_form.php'); break;
						case 44: include('show_sso_attach.php'); break;
						case 46: include('show_pvf_form.php'); break;
						case 45: include('show_pvf_attach.php'); break;
						case 47: include('show_pnd1_year.php'); break;
						case 48: include('show_pnd1_attach_year_'.$lang.'.php'); break;*/
					}
				?>	
				</td>
			</tr>
		</table>	

		</div>
	</div>
		
<script type="text/javascript">
	
	$(document).ready(function() {
		


	});
	
</script>
						
						


















