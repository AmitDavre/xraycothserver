<?php
if(session_id()==''){session_start();}
ob_start();
include('../../dbconnect/db_connect.php');
include(DIR.'files/functions.php');
include(DIR.'time/functions.php');

// include(ROOT.'dbconnect/db_connect.php');
	// include(ROOT.'time/functions.php');

require_once DIR.'PhpSpreadsheet/vendor/autoload.php';


$cid = $_SESSION['rego']['cid'];


use PhpOffice\PhpSpreadsheet\Helper\Sample;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

// $conn = new mysqli("localhost","root","root","test");
$sql = "SELECT * FROM ".$cid."_scanfiles ";
$result = $dbc->query($sql);
$write_array = array();

$date = date('Y-m-d H:i:s');
$fileName = "Scan Data.xlsx";
$write_array[] = array("S.No","Date","Period","Filename","Scan system","IN/OUT","Import","Validated");
if($result->num_rows > 0) 
{
	$count = 1;
    while($row = $result->fetch_assoc()) 
    {
    	$counter = $count++;
    	if($row["import"] == '1')
    	{
    		$importVal = 'Yes';
    	}
    	else
    	{
    		$importVal = 'No';
    	}

    	if($row["status"] == '1')
    	{
    		$statusVal = 'Yes';
    	}
    	else
    	{
    		$statusVal = 'No';
    	}
        $write_array[] = array($counter,date('d-m-Y' , strtotime($row["date"])),$row["period"],$row["filename"],$row["scansystem"],$row["in_out"],$importVal,$statusVal);
    }
}


$spreadsheet = new Spreadsheet();
$spreadsheet->setActiveSheetIndex(0);
$spreadsheet->getActiveSheet()->fromArray($write_array,NULL,'A1');
$spreadsheet->getActiveSheet()->setTitle("My Excel");
$spreadsheet->getActiveSheet()->getColumnDimension('A')->setWidth(10);
$spreadsheet->getActiveSheet()->getColumnDimension('B')->setWidth(15);
$spreadsheet->getActiveSheet()->getColumnDimension('C')->setWidth(15);
$spreadsheet->getActiveSheet()->getColumnDimension('D')->setWidth(18);
$spreadsheet->getActiveSheet()->getColumnDimension('E')->setWidth(15);
$spreadsheet->getActiveSheet()->getColumnDimension('F')->setWidth(15);
$spreadsheet->getActiveSheet()->getColumnDimension('G')->setWidth(15);
$spreadsheet->getActiveSheet()->getColumnDimension('H')->setWidth(20);
$spreadsheet->getActiveSheet()->getStyle('A1')->getFont()->setBold( true );
$spreadsheet->getActiveSheet()->getStyle('B1')->getFont()->setBold( true );
$spreadsheet->getActiveSheet()->getStyle('C1')->getFont()->setBold( true );
$spreadsheet->getActiveSheet()->getStyle('D1')->getFont()->setBold( true );
$spreadsheet->getActiveSheet()->getStyle('E1')->getFont()->setBold( true );
$spreadsheet->getActiveSheet()->getStyle('F1')->getFont()->setBold( true );
$spreadsheet->getActiveSheet()->getStyle('G1')->getFont()->setBold( true );
$spreadsheet->getActiveSheet()->getStyle('H1')->getFont()->setBold( true );


header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="Scan_Data_'.$date.'.xlsx"');
header('Cache-Control: max-age=0');
header('Cache-Control: max-age=1');
header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
header('Cache-Control: cache, must-revalidate');
header('Pragma: public');
$writer = IOFactory::createWriter($spreadsheet, 'Xlsx');

ob_clean();
$writer->save('php://output');
exit;

?>