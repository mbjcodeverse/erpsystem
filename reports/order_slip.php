<?php

$invno = $_GET['invno'];
$cash_tendered = $_GET['cash_tendered'];
$change_amount = $_GET['change_amount'];

// fetch items from database here

?>

<!DOCTYPE html>
<html>
<head>
    <title>Receipt</title>

    <style>

        body{
            font-family: monospace;
            width: 280px;
            margin:0 auto;
            font-size:12px;
        }

        .center{
            text-align:center;
        }

        table{
            width:100%;
            border-collapse:collapse;
        }

        td{
            padding:2px 0;
        }

        .right{
            text-align:right;
        }

        @media print {

            @page{
                margin:0;
            }

            body{
                margin:5px;
            }

        }

    </style>
</head>

<body onload="printReceipt()">

    <div class="center">
        <h3>MY STORE</h3>
        <p>Bacolod City</p>
        <p>Invoice #: <?php echo $invno; ?></p>
    </div>

    <hr>

    <table>

        <tr>
            <td>Coke</td>
            <td class="right">50.00</td>
        </tr>

        <tr>
            <td>Rice</td>
            <td class="right">20.00</td>
        </tr>

    </table>

    <hr>

    <table>

        <tr>
            <td>Total</td>
            <td class="right">70.00</td>
        </tr>

        <tr>
            <td>Cash</td>
            <td class="right"><?php echo $cash_tendered; ?></td>
        </tr>

        <tr>
            <td>Change</td>
            <td class="right"><?php echo $change_amount; ?></td>
        </tr>

    </table>

    <br>

    <div class="center">
        Thank you!
    </div>

    <script>

        function printReceipt(){

            window.focus();

            window.print();

            setTimeout(function(){
                window.close();
            },1000);

        }

    </script>

</body>
</html>