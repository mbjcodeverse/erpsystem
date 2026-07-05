<?php
require_once "../controllers/sale.controller.php";
require_once "../models/sale.model.php";

class AjaxSalesSequence{ 
   public $sales_date; 
   
   public function ajaxDisplaySalesSequence(){
     $sales_date = $this->sales_date;    
     $answer = (new ControllerSale)->ctrGenerateSalesSequence($sales_date);
     echo json_encode($answer);
   }
}

$sale_sequence = new AjaxSalesSequence();
$sale_sequence -> sales_date = $_POST["sales_date"];
$sale_sequence -> ajaxDisplaySalesSequence();