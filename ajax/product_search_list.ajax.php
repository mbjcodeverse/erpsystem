<?php
require_once "../controllers/products.controller.php";
require_once "../models/products.model.php";

class AjaxProductList{ 
   public $categorycode;  
   public $brandcode;
   public $vatdesc;

   public function ajaxDisplayProductList(){
     $categorycode = $this->categorycode;
     $brandcode = $this->brandcode;
     $vatdesc = $this->vatdesc;

     $answer = (new ControllerProducts)->ctrProductSearchList($categorycode, $brandcode, $vatdesc);
     echo json_encode($answer);
   }
}

$product_list = new AjaxProductList();
$product_list -> categorycode = $_POST["categorycode"];
$product_list -> brandcode = $_POST["brandcode"];
$product_list -> vatdesc = $_POST["vatdesc"];
$product_list -> ajaxDisplayProductList();