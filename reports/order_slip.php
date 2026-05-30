<?php

require_once "../controllers/sale.controller.php";
require_once "../models/sale.model.php";

require_once "../controllers/employees.controller.php";
require_once "../models/employees.model.php";

$invno = $_GET["invno"];
$cash_tendered = $_GET["cash_tendered"];
$change_amount = $_GET["change_amount"];

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
$cashier_name = $cashier['fname'].' '.$cashier['lname'];

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
    <b>ORDER SLIP</b><br>
    Date: <?= $sdate ?> [<?= $stime ?>]<br>
    Inv #: <?= $invno ?>
</div>

<br>

<table>

<tr>
    <th align="left">Products</th>
    <th>Qty</th>
    <th>Price</th>
    <th>Amount</th>
</tr>

<?php foreach($salesitems as $item): ?>

<tr>
    <td><?= htmlspecialchars($item['prodname']) ?></td>
    <td class="right"><?= number_format($item['qty'],2) ?></td>
    <td class="right"><?= number_format($item['uprice'],2) ?></td>
    <td class="right"><?= number_format($item['tamount'],2) ?></td>
</tr>

<?php endforeach; ?>

<tr>
    <td colspan="2" class="line-top"><?= $nRec ?></td>
    <td class="right line-top">Total</td>
    <td class="right line-top"><?= $amount ?></td>
</tr>

<tr>
    <td colspan="2"></td>
    <td class="right">Discount</td>
    <td class="right"><?= $discount ?></td>
</tr>

<tr>
    <td colspan="2"></td>
    <td class="right">Amount</td>
    <td class="right"><?= $netamount ?></td>
</tr>

<tr>
    <td colspan="2">Cashier: <?= htmlspecialchars($cashier_name) ?></td>
    <td class="right line-top">Tendered</td>
    <td class="right line-top"><?= htmlspecialchars($cash_tendered) ?></td>
</tr>

<tr>
    <td colspan="2"></td>
    <td class="right line-bottom">Change</td>
    <td class="right line-bottom"><?= htmlspecialchars($change_amount) ?></td>
</tr>

</table>

</body>
</html>