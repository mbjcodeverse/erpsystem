<?php
date_default_timezone_set('Asia/Manila');
require_once '../vendor/autoload.php';

require_once "../controllers/sale.controller.php";
require_once "../models/sale.model.php";

// require_once "../controllers/remittance.controller.php";
// require_once "../models/remittance.model.php";

require_once "../controllers/employees.controller.php";
require_once "../models/employees.model.php";

class printCounterReset{
    public $branchcode;
    public $branch_name;
    public $cashierid;
    public $resetcode;
    public $resetdetail;
    public $resetype;

    public function getCounterReset(){
        $branchcode = $this->branchcode;
        $branch_name = strtoupper($this->branch_name);
        $cashierid = $this->cashierid;

        $resetcode = $this->resetcode;
        $resetdetail = $this->resetdetail;   // By Product Category, By Product Name
        $resetype = $this->resetype;         // Counter, Cash, ..

        $sales = (new ControllerSale)->ctrPrintCashierReset($resetcode,$resetdetail,$resetype);

        $cashier = (new ControllerEmployees)->ctrEmployeeInfo($cashierid);
        $cashier_name = $cashier['fname'].' '.$cashier['lname']; 

        $current_date = date("m/d/Y"); 

        $pdf = new TCPDF();
        $pdf->startPageGroup();
        $pdf->setPrintHeader(false);	    /*remove line on top of the page*/
        $pdf->setPrintFooter(false);        /*remove line at the bottom of the page*/

        $width = $pdf->pixelsToUnits(230); 
        $height = $pdf->pixelsToUnits(600);

        $resolution= array($width, $height);
        $pdf->SetMargins(8, 3, 4, true);

        $pdf->AddPage('P', $resolution);

        $html = '
            <table>
                <tr>
                    <td style="width:180px;text-align:center;font-size:1.2em;font-weight:bold;">G*168 SARI-SARI STORE</td> 
                </tr>

                <tr>
                    <td style="width:180px;text-align:center;font-size:10px;">'.$branch_name.'</td> 
                </tr> 

                <tr>
                    <td style="width:180px;text-align:center;font-size:10px;">'.$current_date.'</td> 
                </tr> 

                <tr>
                    <td style="width:180px;text-align:center;font-size:1.1em;font-weight:bold;">Counter Reset</td> 
                </tr> 

                <tr>
                    <td></td>
                </tr>       
                
                <tr>
                    <td style="width:180px;text-align:left;font-size:10px;">Reset Code: '.$resetcode.'</td> 
                </tr> 
            
                <tr style="background-color:#f2f4f7;">
                    <td style="border: 1px solid #666;width:110px;text-align:left;font-size:11px;">&nbsp;&nbsp;&nbsp;Category </td>
                    <td style="border: 1px solid #666;width:75px;text-align:right;font-size:11px;">Amount&nbsp;&nbsp;&nbsp;</td>              
                </tr>                               
        ';

        $total_amount = 0.00; 
        foreach ($sales as $value) {
            $catdescription = $value["catdescription"];
            $tamount = number_format($value['tamount'],2);
            $sale_amount = $value['tamount'];
            $total_amount = $total_amount + $sale_amount;
            $html .= '
                <tr>
                    <td style="width:110px;text-align:left;font-size:11px;border-right: 1px solid black;border-left: 1px solid black;">&nbsp;&nbsp;'.$catdescription.'</td>
                    <td style="width:75px;text-align:right;font-size:11px;border-right: 1px solid black;">'.$tamount.'&nbsp;&nbsp;&nbsp;</td>              
                </tr>    
            ';
        }

        $total_sales = number_format($total_amount,2);
        $html .= '
            <table style="border: none;">
                <tr>
                    <td style="width:110px;text-align:right;font-size:11px;border-top: 1px solid black;font-weight:bold;">TOTAL SALES</td>
                    <td style="width:75px;text-align:right;font-size:11px;border-top: 1px solid black;font-weight:bold;">'.$total_sales.'</td>
                </tr>            
            </table>
        ';

        $html .= '
            <table style="border: none;">
                <tr>
                    <td style="width:185px;"></td>
                </tr>        
                    
                <tr>
                    <td style="width:180px;font-size:11px;">Resetted by:</td>
                </tr>
                <tr>
                    <td style="width:180px;font-size:11px;"></td>	      
                </tr>
                <tr>
                    <td style="width:110px;border-bottom: 1px solid black;font-size:11px;"></td>
                </tr>
                <tr>
                    <td style="width:110px;font-size:11px;text-align: left">'.$cashier_name.'</td>
                </tr> 
                <tr>
                    <td style="width:180px;font-size:11px;"></td>       
                </tr>     
            </table>
        ';

        $html .= '
            </table>
        ';

        // Print once
        $pdf->writeHTML($html, true, false, true, false, '');
        $pdf->Output('resetslip.pdf', 'I');
    }   
}

$counterSales = new printCounterReset();
$counterSales -> branchcode = $_GET["branchcode"];
$counterSales -> branch_name = $_GET["branch_name"];
$counterSales -> cashierid = $_GET["cashierid"];
$counterSales -> resetcode = $_GET["resetcode"];
$counterSales -> resetdetail = $_GET["resetdetail"];
$counterSales -> resetype = $_GET["resetype"];
$counterSales -> getCounterReset();