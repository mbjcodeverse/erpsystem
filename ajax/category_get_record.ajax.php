<?php
require_once "../controllers/category.controller.php";
require_once "../models/category.model.php";

class AjaxCategory{
    public $idCategory;
    public function ajaxGetCategory(){
      $item = "id";
      $value = $this->idCategory;
      $answer = (new ControllerCategory)->ctrShowCategory($item, $value);
      echo json_encode($answer);
    }
}
 
if(isset($_POST["idCategory"])){
  $getCategory = new AjaxCategory();
  $getCategory -> idCategory = $_POST["idCategory"];
  $getCategory -> ajaxGetCategory();
}