<?php
	
	if(session_id()==''){session_start();}
	ob_start();
	include('../../dbconnect/db_connect.php');
	include(DIR.'files/functions.php');
	include(DIR.'files/arrays_'.$_SESSION['rego']['lang'].'.php');
	include(DIR.'files/payroll_functions.php');

	$name_position = getFormNamePosition($cid);

	$edata = getEntityData($_SESSION['rego']['gov_entity']);
	$entityBranches = getEntityBranches($_SESSION['rego']['gov_entity']);
	$branch = sprintf("%06d",$entityBranches[$_SESSION['rego']['gov_branch']]['code']);
	
$style = '
	<style>
		@page {
			margin: 10px 10px 10px 10px;
		}
		body, html, table {
			font-family: "leelawadee", "garuda";
			font-family: "leelawadee";
			font-size:11px;
		}
		table.taxtable {
			border-collapse:collapse;
			border:1px #000 solid;
			width:100%;
			line-height:140%;
		}
		table.taxtable th, table.taxtable td {
			border:0px solid #000;
			padding:10px 15px;
			line-height:140%;
			color:#111;
			font-family: inherit;
			vertical-align:middle;
			text-align:center;
			font-size:12px;
			white-space:nowrap;
			font-weight:normal;
		}
		table.taxtable th {
			background:#eee;
		}
		.footer {
			font-size:10px;
			font-weight:normal;
			text-align:right;
			float:right;
			color:#ccc;
			width:300px;
		}
	</style>';
				
$footer = '<div class="footer">Form generated by : Xray HR</div>';
	
$html = '<html><body>';
		
$html .= '
			<div style="font-size:16px; margin-bottom:5px"><b>รายงานเงินนำส่งรายสมาชิก</b></div>
			<table class="taxtable" border="0" style="margin-bottom:10px; border:0">
				<tr>
					<td style="width:65%; text-align:left; line-height:200%; vertical-align:top">
						ชื่อสถานประกอบการ : <span style="font-size:14px">'.$edata[$lang.'_compname'].'</span><br />
						เลขประจำตัวผู้เสียภาษีอากร : '.$edata['tax_id'].'<br />
						ลำดับสาขาที่ : '.$branch.'
					</td>	
					<td style="text-align:right; line-height:200%; vertical-align:top">
						เดือน : '.$_SESSION['rego']['formdate']['thm'].' '.$_SESSION['rego']['formdate']['thy'].' งวดที่ 1 <br>
						วันที่ : '.$_SESSION['rego']['formdate']['thdate'].'
					</td>
				</tr>
			</table>
			
			<table class="taxtable" border="0" style="table-layout:fixed; border:0">
				<tr>
					<th colspan="3" style="text-align:left"><b>จำนวนสมาชิก</b></th>
				</tr>
				<tr>
					<td style="text-align:right; padding-left:50px;border-bottom:1px dotted #999;">จำนวนสมาชิกที่นำส่งเงิน</td>
					<td style="text-align:right;border-bottom:1px dotted #999;">'.$_REQUEST['m'].'</td>
					<td style="text-align:left;border-bottom:1px dotted #999;">ราย</td>
				</tr>
				<tr>
					<td style="text-align:right; padding-left:50px;border-bottom:1px dotted #999;">จำนวนสมาชิกที่หยุดส่งเงิน</td>
					<td style="text-align:right;border-bottom:1px dotted #999;">'.$_REQUEST['s'].'</td>
					<td style="text-align:left;border-bottom:1px dotted #999;">ราย</td>
				</tr>
				<tr>
					<td style="text-align:right; padding-left:50px;border-bottom:1px dotted #999;">จำนวนสมาชิกรวม</td>
					<td style="text-align:right;border-bottom:1px dotted #999;">'.$_REQUEST['t'].'</td>
					<td style="text-align:left;border-bottom:1px dotted #999;">ราย</td>
				</tr>
				<tr>
					<td style="text-align:right; padding-left:50px">จำนวนสมาชิกใหม่</td>
					<td style="text-align:right">'.$_REQUEST['n'].'</td>
					<td style="text-align:left">ราย</td>
				</tr>
				<tr>
					<th colspan="3" style="text-align:left"><b>จำนวนเงินนำส่ง</b></th>
				</tr>
				<tr>
					<td style="text-align:center">เงินสะสม<br /><b>'.$_REQUEST['v1'].'</b></td>
					<td style="text-align:center">เงินสมทบ<br /><b>'.$_REQUEST['v2'].'</b></td>
					<td style="text-align:center">เงินลงทุนรวม<br /><b>'.$_REQUEST['v3'].'</b></td>
				</tr>
				<tr>
					<th colspan="3" style="text-align:left"><b>วันที่จ่ายเงินเข้ากองทุน</b> : '.$_SESSION['rego']['paydate'].'</th>
				</tr>
				<tr>
					<td colspan="3" style="text-align:left; padding:5px">&nbsp;</td>
					
				</tr>
				<tr>
					<th style="text-align:left"><b>ผู้ประสานงาน (จัดทำ)</b></th>
					<th style="text-align:left"></th>
					<th style="text-align:left"><b>ผู้มีอำนาจลงนามของคณะกรรมการกองทุน</b></th>
				</tr>
				<tr>
					<td style="text-align:left; vertical-align:baseline">'.$name_position['name'].'</td>
					<td>';
					
					if($edata['digi_stamp'] == 1 && !empty($edata['dig_stamp'])){
						$html .= '
							<img width="22mm" src="'.ROOT.$edata['dig_stamp'].'?'.time().'" />';
					}
					$html .= '</td><td style="text-align:left;">';
					if($edata['digi_signature'] == 1 && !empty($edata['dig_signature'])){
						$html .= '<img width="55mm" src="'.ROOT.$edata['dig_signature'].'?'.time().'" />';
					}else{
						$html .= '...............................................................................';
					}
				$html .= '</td></tr>
			</table>';
		
	$html .= '</body></html>';	
			
	//echo $style.$html.$footer; exit;	
			
	require_once("../../mpdf7/vendor/autoload.php");
	//class mPDF ($mode, $format , $default_font_size , $default_font , $margin_left , $margin_right , $margin_top , $margin_bottom , $margin_header , $margin_footer , string $orientation ]]]]]])
	$mpdf=new mPDF('utf-8', 'A4-P', 9, '', 8, 8, 10, 8, 8, 5);
	$mpdf->SetDisplayMode('fullpage');
	$mpdf->SetFontSize(9);
	$mpdf->SetTitle($edata[$lang.'_compname'].' ('.strtoupper($_SESSION['rego']['cid']).') - Providend fund - '.$months[(int)$_SESSION['rego']['gov_month']].' '.$_SESSION['rego']['cur_year']);
	$mpdf->WriteHTML($style,1);
	$mpdf->SetHTMLFooter($footer);
	$mpdf->WriteHTML($html);
	//$mpdf->Output($_SESSION['rego']['cid'].'_PVF form_'.$_SESSION['rego']['cur_year'].'_'.$_SESSION['rego']['gov_month'].'.pdf',$action);
	
	$dir = DIR.$_SESSION['rego']['cid'].'/archive/';
	$root = ROOT.$_SESSION['rego']['cid'].'/archive/';
	$baseName = $_SESSION['rego']['cid'].'_pvf_form_'.$_SESSION['rego']['curr_month'].'_'.$_SESSION['rego']['year_'.$lang];
	$extension = 'pdf';		
	$filename = getFilename($baseName, $extension, $dir);
	$doc = $lng['PVF Form'].' '.$_SESSION['rego']['curr_month'].'-'.$_SESSION['rego']['year_'.$lang];

	$mpdf->Output($filename,'I');
	
	if(isset($_REQUEST['a'])){
		$mpdf->Output($dir.$filename,'F');
		include('save_to_documents.php');
	}
	
	exit;









