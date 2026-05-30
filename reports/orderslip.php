<?php
require_once '../vendor/autoload.php';

require_once "../controllers/sale.controller.php";
require_once "../models/sale.model.php";

require_once "../controllers/employees.controller.php";
require_once "../models/employees.model.php";

class printOrderSlip{
    public $invno;
    public $cash_tendered;
    public $change_amount;

    public function getOrderSlip(){
        $invno = $this->invno;   
        $cash_tendered = $this->cash_tendered;
        $change_amount = $this->change_amount;     

        $prefix = strtolower(substr($invno,0,2));

        $sale = (new ControllerSale)->ctrGetSale($invno);
        $salesitems = (new ControllerSale)->ctrGetSaleItems($invno);
        $nRec = count($salesitems).' item(s)';

        $sale_date = $sale['sdate'];
        $sdate = substr($sale_date,5,2)."/".substr($sale_date,8,2)."/".substr($sale_date,0,4);

        $stime = $sale['stime'];
        $soldto = strtoupper($sale['soldto']);
        $amount = number_format($sale['amount'],2);
        $discount = number_format($sale['discount'],2);
        $amount = number_format($sale['amount'],2);
        $netamount = number_format($sale['netamount'],2);
        $vatamnt = number_format($sale['vatamnt'],2);
        $excempt = number_format($sale['excempt'],2);
        $vatable = number_format($sale['vatable'],2);
        $postedby = $sale['postedby'];

        $cashier = (new ControllerEmployees)->ctrEmployeeInfo($postedby);
        $cashier_name = $cashier['fname'].' '.$cashier['lname'];  

        $pdf = new TCPDF();
        $pdf->startPageGroup();
        $pdf->setPrintHeader(false);	/*remove line on top of the page*/
        $pdf->setPrintFooter(false);    /*remove line at the bottom of the page*/

        $width = $pdf->pixelsToUnits(230); 
        $height = $pdf->pixelsToUnits(600);

        $resolution= array($width, $height);
        $pdf->SetMargins(7, 0, 4, true);

        $pdf->AddPage('P', $resolution);

        $html = '
            <table>
                <tr>
                    <td style="width:180px;text-align:center;font-size:1.2em;font-weight:bold;">
                        ORDER SLIP
                    </td> 
                </tr>

                <tr>
                    <td style="width:180px;text-align:center;font-size:9px;">
                        Date: '.$sdate.' ['.$stime.']
                    </td> 
                </tr> 

                <tr>
                    <td style="width:180px;text-align:center;font-size:9px;">
                        Inv #: '.$invno.'
                    </td> 
                </tr> 

                <tr>
                    <td></td>
                </tr>        

                <tr style="background-color:#f2f4f7;">
                    <td style="border:1px solid #666;width:70px;text-align:left;font-size:7px;">
                        &nbsp;&nbsp;Products
                    </td>

                    <td style="border:1px solid #666;width:35px;text-align:right;font-size:7px;">
                        Qty&nbsp;&nbsp;&nbsp;
                    </td> 

                    <td style="border:1px solid #666;width:40px;text-align:right;font-size:7px;">
                        Price&nbsp;&nbsp;&nbsp;
                    </td> 

                    <td style="border:1px solid #666;width:45px;text-align:right;font-size:7px;">
                        Amount&nbsp;&nbsp;&nbsp;
                    </td>            
                </tr>                   
        ';

        foreach ($salesitems as $value) {
            $prodname = $value["prodname"];
            $qty = number_format($value['qty'],2);
            $uprice = number_format($value['uprice'],2);
            $tamount = number_format($value['tamount'],2);
            $html .= '
                <tr>
                    <td style="font-family: Arial Narrow, Helvetica, sans-serif;width:70px;text-align:left;font-size:7px;border-right: 1px solid black;border-left: 1px solid black;">&nbsp;&nbsp;'.$prodname.'</td>
                    <td style="width:35px;text-align:right;font-size:7px;border-right: 1px solid black;">'.$qty.'&nbsp;&nbsp;</td>  
                    <td style="width:40px;text-align:right;font-size:7px;border-right: 1px solid black;">'.$uprice.'&nbsp;&nbsp;</td>  
                    <td style="width:45px;text-align:right;font-size:7px;border-right: 1px solid black;">'.$tamount.'&nbsp;&nbsp;</td>            
                </tr>     
            ';
        }

        $html .= '
            <tr>
                <td colspan="2" style="width:105px;text-align:left;font-size:7px;border-top: 0.5px solid black;">'.$nRec.'</td>
                <td style="width:40px;text-align:right;font-size:7px;border-top: 0.5px solid black;">Total</td>  
                <td style="width:45px;text-align:right;font-size:7px;border-top: 0.5px solid black;">'.$amount.'</td>            
            </tr>
            <tr>
                <td colspan="2" style="width:105px;text-align:left;font-size:7px;"></td>
                <td style="width:40px;text-align:right;font-size:7px;">Discount</td>  
                <td style="width:45px;text-align:right;font-size:7px;">'.$discount.'</td>            
            </tr> 
            <tr>
                <td colspan="2" style="width:105px;text-align:left;font-size:7px;"></td>
                <td style="width:40px;text-align:right;font-size:7px;">Amount</td>  
                <td style="width:45px;text-align:right;font-size:7px;">'.$netamount.'</td>            
            </tr>  
            <tr>
                <td colspan="2" style="width:105px;text-align:left;font-size:7px;">Cashier: '.$cashier_name.'</td>
                <td style="width:40px;text-align:right;font-size:7px;border-top: 0.5px solid black;">Tendered</td>  
                <td style="width:45px;text-align:right;font-size:7px;border-top: 0.5px solid black;">'.$cash_tendered.'</td>            
            </tr> 
            <tr>
                <td colspan="2" style="width:105px;text-align:left;font-size:7px;"></td>
                <td style="width:40px;text-align:right;font-size:8px;border-bottom: 0.5px solid black;">Change</td>  
                <td style="width:45px;text-align:right;font-size:9px;border-bottom: 0.5px solid black;">'.$change_amount.'</td>            
            </tr> 
        ';

        $html .= '
            </table>
        ';

        // Print once
        $pdf->writeHTML($html, true, false, true, false, '');
        // Auto-print PDF
        $pdf->IncludeJS('print(true);');
        $pdf->Output('orderslip.pdf', 'I');
    }
}

$order_slip = new printOrderSlip();
$order_slip -> invno = $_GET["invno"];
$order_slip -> cash_tendered = $_GET["cash_tendered"];
$order_slip -> change_amount = $_GET["change_amount"];
$order_slip -> getOrderSlip();