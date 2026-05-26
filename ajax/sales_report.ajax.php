<?php
require_once "../controllers/sale.controller.php";
require_once "../models/sale.model.php";

class AjaxSalesReport{ 
   public $reptype; 
   public $branchcode;
   public $start_date;
   public $end_date;
   public $categorycode; 
   public $salemode;  
   public $status;
   
   public function ajaxDisplaySalesReport(){
     $reptype = $this->reptype;
     $branchcode = $this->branchcode;
     $start_date = $this->start_date;
     $end_date = $this->end_date;
     $categorycode = $this->categorycode;
     $salemode = $this->salemode;
     $status = $this->status;
     
     $answer = (new ControllerSale)->ctrGenerateSalesReport($reptype, $branchcode, $start_date, $end_date, $categorycode, $salemode, $status);
     echo json_encode($answer);
   }
}

$sale_report = new AjaxSalesReport();
$sale_report -> reptype = $_POST["reptype"];
$sale_report -> branchcode = $_POST["branchcode"];
$sale_report -> start_date = $_POST["start_date"];
$sale_report -> end_date = $_POST["end_date"];
$sale_report -> categorycode = $_POST["categorycode"];
$sale_report -> salemode = $_POST["salemode"];
$sale_report -> status = $_POST["status"];

$sale_report -> ajaxDisplaySalesReport();