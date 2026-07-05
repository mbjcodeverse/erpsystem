<?php

require_once "../controllers/sale.controller.php";
require_once "../models/sale.model.php";

require_once "../controllers/employees.controller.php";
require_once "../models/employees.model.php";

$invno = $_GET["invno"];
// $cash_tendered = $_GET["cash_tendered"];
// $change_amount = $_GET["change_amount"];

$sale = (new ControllerSale)->ctrGetSale($invno);
$salesitems = (new ControllerSale)->ctrGetSaleItems($invno);

$nRec = count($salesitems) . ' item(s)';

$sale_date = $sale['sdate'];
$sdate = substr($sale_date,5,2)."/".substr($sale_date,8,2)."/".substr($sale_date,0,4);

$stime = $sale['stime'];

$amount = number_format($sale['amount'],2);
$discount = number_format($sale['discount'],2);
$netamount = number_format($sale['netamount'],2);

$postedby = $sale['postedby'];

$cashier = (new ControllerEmployees)->ctrEmployeeInfo($postedby);
// $cashier_name = $cashier['fname'].' '.$cashier['lname'];
$cashier_name = $cashier['fname'];

?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">

<title>Order Slip</title>

<style>

@page {
    size: 80mm auto;
    margin: 3mm;
}

body{
    width:72mm;
    margin:0 auto;
    font-family: Arial, Helvetica, sans-serif;
    font-size:11px;
}

.center{
    text-align:center;
}

table{
    width:100%;
    border-collapse:collapse;
}

th{
    border:1px solid #000;
    padding:2px;
    font-size:10px;
}

td{
    padding:2px;
    font-size:10px;
}

.right{
    text-align:right;
}

.line-top{
    border-top:1px solid #000;
}

.line-bottom{
    border-bottom:1px solid #000;
}

</style>

<script>
window.onload = function() {
    setTimeout(function(){
        window.print();
        setTimeout(function(){
            window.close();
        },1000);
    },500);

};
</script>

</head>

<body>

<div class="center">
    <b style="font-size:16px;">ORDER SLIP</b><br>
    Date: <?= $sdate ?> [<?= $stime ?>]<br>
    Inv #: <?= $invno ?>
</div>

<br>

<table>

<tr>
    <th style="font-size:14px;" align="left">Products</th>
    <th style="font-size:14px;">Qty</th>
    <th style="font-size:14px;">Price</th>
    <th style="font-size:14px;">Amount</th>
</tr>

<?php foreach($salesitems as $item): ?>

<tr>
    <td style="font-size:14px;"><?= htmlspecialchars($item['prodname']) ?></td>
    <td style="font-size:14px;" class="right"><?= number_format($item['qty'],3) ?></td>
    <td style="font-size:14px;" class="right"><?= number_format($item['uprice'],2) ?></td>
    <td style="font-size:14px;" class="right"><?= number_format($item['tamount'],2) ?></td>
</tr>

<?php endforeach; ?>

<tr>
    <td style="font-size:14px;" colspan="2" class="line-top"><?= $nRec ?></td>
    <td style="font-size:14px;" class="right line-top">Total</td>
    <td style="font-size:14px;" class="right line-top"><?= $amount ?></td>
</tr>

<tr>
    <td colspan="2"></td>
    <td style="font-size:14px;" class="right">Discount</td>
    <td style="font-size:14px;" class="right"><?= $discount ?></td>
</tr>

<tr>
    <td colspan="2"></td>
    <td style="font-size:14px;" class="right">Amount</td>
    <td style="font-size:14px;" class="right"><?= $netamount ?></td>
</tr>

<tr>
    <td style="font-size:14px;" colspan="2">Cashier: <?= htmlspecialchars($cashier_name) ?></td>
    <!-- <td style="font-size:14px;" class="right line-top">Tendered</td>
    <td style="font-size:14px;" class="right line-top"><?= htmlspecialchars($cash_tendered) ?></td> -->
</tr>

<!-- <tr>
    <td colspan="2"></td>
    <td style="font-size:14px;" class="right">Change</td>
    <td style="font-size:14px;" class="right"><?= htmlspecialchars($change_amount) ?></td>
</tr> -->

<tr><td></td></tr>
<tr><td></td></tr>
<tr><td></td></tr>

<tr>
    <td colspan="4" style="font-size:14px;" class="left">This is not a sales invoice. This document is not valid for claiming input tax.</td>
</tr>

<tr><td></td></tr>
<tr><td></td></tr>

<tr>
    <td colspan="4" style="font-size:14px;" class="left">Please ask for sales invoice.</td>
</tr>
</table>

</body>
</html>