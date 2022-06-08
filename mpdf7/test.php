<?


$html = '
<h1><a name="top"></a>mPDF</h1>
Page {PAGENO} from {nbpg} pages
';


$path = (getenv('MPDF_ROOT')) ? getenv('MPDF_ROOT') : __DIR__;
require_once $path . '/vendor/autoload.php';

//var_dump($path);
//exit;
//$mpdf = new mPDF(['mode' => 'c']);

$mpdf = new mPDF('utf-8', 'A4-P', 9, 'garuda', 10, 10, 20, 8, 4, 4);

$mpdf->SetDisplayMode('fullwidth');
$mpdf->SetHTMLHeader('<div style="text-align:right">Page {PAGENO} from {nbpg} pages</div>');
$mpdf->WriteHTML($html);
$mpdf->SetHTMLFooter('Page {PAGENO} from {nbpg} pages');
$mpdf->Output();
