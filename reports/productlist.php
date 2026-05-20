<?php
require_once '../vendor/autoload.php';

require_once '../controllers/products.controller.php';
require_once '../models/products.model.php';

class ProductList {
    public $categorycode;
    public $brandcode;
    public $vatdesc;
    public function printProductList() {
        $categorycode = $this->categorycode;
        $brandcode = $this->brandcode;
        $vatdesc = $this->vatdesc;
        $product_list = (new ControllerProducts)->ctrProductSearchList($categorycode, $brandcode, $vatdesc);

        $pdf = new TCPDF();
        $pdf->setPrintHeader(false);
        $pdf->SetMargins(10, 10, 10);
        // $pdf->AddPage();
        $pdf->AddPage('L');

        $html = '
            <h1>Product List</h1>
            <table border="1" cellpadding="5">
                <thead>
                    <tr style="background-color:#f2f2f2;">
                        <th width="60"><b>Code</b></th>
                        <th width="140"><b>Category</b></th>
                        <th width="315"><b>Product Name</b></th>
                        <th width="90"><b>Unit Price</b></th>
                        <th width="90"><b>Unit Cost</b></th>
                        <th width="90"><b>Profit</b></th>
                    </tr>
                </thead>
                <tbody>
        ';

        foreach ($product_list as $value) {
            $html .= '
                <tr>
                    <td width="60">'.$value["prodid"].'</td>
                    <td width="140">'.$value["catdescription"].'</td>
                    <td width="315">'.$value["prodname"].'</td>
                    <td width="90">'.$value["uprice"].'</td>
                    <td width="90">'.$value["ucost"].'</td>
                    <td width="90">'.$value["profit"].'</td>
                </tr>
            ';
        }

        $html .= '
                </tbody>
            </table>
        ';

        // Print once
        $pdf->writeHTML($html, true, false, true, false, '');
        $pdf->Output('productlist.pdf', 'I');
    }
}

$product_info = new ProductList();
$product_info -> categorycode = $_GET["categorycode"];
$product_info -> brandcode = $_GET["brandcode"];
$product_info -> vatdesc = $_GET["vatdesc"];
$product_info->printProductList();