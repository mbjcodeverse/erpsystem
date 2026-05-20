<?php
require_once "../controllers/brand.controller.php";
require_once "../models/brand.model.php";

class AjaxBrand{
    public $idBrand;
    public function ajaxGetBrand(){
      $item = "id";
      $value = $this->idBrand;
      $answer = (new ControllerBrand)->ctrShowBrand($item, $value);
      echo json_encode($answer);
    }
}
 
if(isset($_POST["idBrand"])){
  $getBrand = new AjaxBrand();
  $getBrand -> idBrand = $_POST["idBrand"];
  $getBrand -> ajaxGetBrand();
}