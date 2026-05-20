<?php
require_once "../controllers/products.controller.php";
require_once "../models/products.model.php";

class ProductDetails{
    public $prodid;
    public function getProductDetails(){
      $prodid = $this->prodid;
      $answer = (new ControllerProducts)->ctrProductInfo($prodid);
      echo json_encode($answer);
    }
}
 
if(isset($_POST["prodid"])){
  $getProduct = new ProductDetails();
  $getProduct -> prodid = $_POST["prodid"];
  $getProduct -> getProductDetails();
}