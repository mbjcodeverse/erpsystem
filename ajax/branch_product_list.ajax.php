<?php
require_once "../controllers/products.controller.php";
require_once "../models/products.model.php";

class branchProductList{
   public $branchcode;

   public function ajaxBranchProductList(){
     $branchcode = $this->branchcode;

     $answer = (new ControllerProducts)->ctrBranchProductList($branchcode);
     echo json_encode($answer);
   }
}

$product = new branchProductList();
$product -> branchcode = $_POST["branchcode"];
$product -> ajaxBranchProductList();