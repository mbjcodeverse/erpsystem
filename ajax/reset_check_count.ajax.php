<?php
require_once "../controllers/reset.controller.php";
require_once "../models/reset.model.php";

class AjaxResetCount{
    public $reset_prefix;
    public function ajaxGetResetCount(){
      $reset_prefix = $this->reset_prefix;
      $answer = (new ControllerReset)->ctrGetResetCount($reset_prefix);
      echo json_encode($answer);
    }
}
 
if(isset($_POST["reset_prefix"])){
  $getGetResetCount = new AjaxResetCount();
  $getGetResetCount -> reset_prefix = $_POST["reset_prefix"];
  $getGetResetCount -> ajaxGetResetCount();
}