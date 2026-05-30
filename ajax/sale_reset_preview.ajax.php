<?php
require_once "../controllers/sale.controller.php";
require_once "../models/sale.model.php";

class AjaxGetPreviewReset{
    public $branchcode;
    public $postedby;
    public $reset_detail;
    public $sale_mode;

    public function ajaxCheckPreviewReset(){
      $branchcode = $this->branchcode;
      $postedby = $this->postedby;
      $reset_detail = $this->reset_detail;
      $sale_mode = $this->sale_mode;

      $answer = (new ControllerSale)->ctrResetPreview($branchcode, $postedby, $reset_detail, $sale_mode);
      echo json_encode($answer);
    }
}
 
$resetCounter = new AjaxGetPreviewReset();
$resetCounter -> branchcode = $_POST["branchcode"];
$resetCounter -> postedby = $_POST["postedby"];
$resetCounter -> reset_detail = $_POST["reset_detail"];
$resetCounter -> sale_mode = $_POST["sale_mode"];
$resetCounter -> ajaxCheckPreviewReset();