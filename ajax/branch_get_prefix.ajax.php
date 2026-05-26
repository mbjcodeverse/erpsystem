<?php
require_once "../controllers/branch.controller.php";
require_once "../models/branch.model.php";

class AjaxGetPrefix{
    public $branchcode;
    public function ajaxGetPrefixCode(){
      $branchcode = $this->branchcode;
      $answer = (new ControllerBranch)->ctrGetPrefixCode($branchcode);
      echo json_encode($answer);
    }
}
 
if(isset($_POST["branchcode"])){
  $getPrefix = new AjaxGetPrefix();
  $getPrefix -> branchcode = $_POST["branchcode"];
  $getPrefix -> ajaxGetPrefixCode();
}