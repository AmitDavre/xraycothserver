<?php

// echo date('n');
// $todayDate = date('Y-m-d');
// $arr= explode('-', $todayDate);

// echo '<pre>';
// print_r($arr['2']);
// echo '</pre>';
// die();
if(session_id()==''){session_start();}
ob_start();
include('../../dbconnect/db_connect.php');
include(DIR.'files/functions.php');
include(DIR.'time/functions.php');


// $currentMonth = date('n'); // numberic value 
// $todayDateArr = date('Y-m-d');
// $dateArr= explode('-', $todayDateArr);
// $todayDate = $dateArr['2'];
// $currentYear = $dateArr['0'];

// $dayArray = array(

// 	'1' => '1',
// 	'2' => '2',
// 	'3' => '3',
// 	'4' => '4',
// 	'5' => '5',
// 	'6' => '6',
// 	'7' => '7',
// 	'8' => '8',
// 	'9' => '9',
// 	'10' => '10',
// 	'11' => '11',
// 	'12' => '12',
// 	'13' => '13',
// 	'14' => '14',
// 	'15' => '15',
// 	'16' => '16',
// 	'17' => '17',
// 	'18' => '18',
// 	'19' => '19',
// 	'20' => '20',
// 	'21' => '21',
// 	'22' => '22',
// 	'23' => '23',
// 	'24' => '24',
// 	'25' => '25',
// 	'26' => '26',
// 	'27' => '27',
// 	'28' => '28',
// 	'29' => '29',
// 	'30' => '30',
// 	'31' => '31',


// ); 

// $sql5 = "SELECT * from  ".$cid."_monthly_shiftplans_".$currentYear." WHERE month = '".$currentMonth."' AND sid= '8'";
// $res5 = $dbc->query($sql5);

// if ($res5->num_rows > 0) 
// {
// 	if($res5 = $res5->fetch_assoc())
// 		{
// 			if (in_array('25', $dayArray)) 
// 			{
// 				$testDate =  $res5['D25'];
// 			}

// 		}

	
// }







// die();

require_once DIR.'PhpSpreadsheet/vendor/autoload.php';


$cid = $_SESSION['rego']['cid'];


use PhpOffice\PhpSpreadsheet\Helper\Sample;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;


// $conn = new mysqli("localhost","root","root","test");
$sql = "SELECT * FROM ".$cid."_metascandata WHERE in_or_out = 'in'";
$result = $dbc->query($sql);
$write_array = array();

$date = date('Y-m-d H:i:s');
$fileName = "Scan Data.xlsx";
$write_array[] = array("Start Date","End Date","Start Time","End Time","Break In","Break Out","Scan ID","EMP ID","Employee Name");
if($result->num_rows > 0) 
{
	$count = 1;
    while($row = $result->fetch_assoc()) 
    {
    	$counter = $count++;
   
        // $write_array[] = array($counter,date('d-m-Y' , strtotime($row["datescan"])),$row["scan_in"],date('d-m-Y' , strtotime($row["datescanout"])),$row["scan_out"],$row["scan_id"],$row["emp_id"],$row["emp_name"],);
        $write_array[] = array('');
    }
}


$spreadsheet = new Spreadsheet();
$spreadsheet->setActiveSheetIndex(0);
$spreadsheet->getActiveSheet()->fromArray($write_array,NULL,'A1');
$spreadsheet->getActiveSheet()->setTitle("Sheet 1");
$spreadsheet->getActiveSheet()->getColumnDimension('A')->setWidth(15);
$spreadsheet->getActiveSheet()->getColumnDimension('B')->setWidth(15);
$spreadsheet->getActiveSheet()->getColumnDimension('C')->setWidth(15);
$spreadsheet->getActiveSheet()->getColumnDimension('D')->setWidth(15);
$spreadsheet->getActiveSheet()->getColumnDimension('E')->setWidth(15);
$spreadsheet->getActiveSheet()->getColumnDimension('F')->setWidth(15);
$spreadsheet->getActiveSheet()->getColumnDimension('G')->setWidth(15);
$spreadsheet->getActiveSheet()->getColumnDimension('H')->setWidth(15);
$spreadsheet->getActiveSheet()->getColumnDimension('I')->setWidth(23);

$spreadsheet->getActiveSheet()->getStyle('A1')->getFont()->setBold( true );
$spreadsheet->getActiveSheet()->getStyle('B1')->getFont()->setBold( true );
$spreadsheet->getActiveSheet()->getStyle('C1')->getFont()->setBold( true );
$spreadsheet->getActiveSheet()->getStyle('D1')->getFont()->setBold( true );
$spreadsheet->getActiveSheet()->getStyle('E1')->getFont()->setBold( true );
$spreadsheet->getActiveSheet()->getStyle('F1')->getFont()->setBold( true );
$spreadsheet->getActiveSheet()->getStyle('G1')->getFont()->setBold( true );
$spreadsheet->getActiveSheet()->getStyle('H1')->getFont()->setBold( true );
$spreadsheet->getActiveSheet()->getStyle('I1')->getFont()->setBold( true );





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