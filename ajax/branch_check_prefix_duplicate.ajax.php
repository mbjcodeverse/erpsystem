<?php
require_once "../controllers/branch.controller.php";
require_once "../models/branch.model.php";

class AjaxPrefix{
    public $prefix;
    public function ajaxGetPrefix(){
      $prefix = $this->prefix;
      $answer = (new ControllerBranch)->ctrGetPrefix($prefix);
      echo json_encode($answer);
    }
}
 
if(isset($_POST["prefix"])){
  $getPrefix = new AjaxPrefix();
  $getPrefix -> prefix = $_POST["prefix"];
  $getPrefix -> ajaxGetPrefix();
}