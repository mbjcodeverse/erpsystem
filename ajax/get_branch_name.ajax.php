<?php
require_once "../controllers/branch.controller.php";
require_once "../models/branch.model.php";

class AjaxBranchName{
    public $branchcode;
    public function ajaxGetBranchName(){
      $branchcode = $this->branchcode;
      $answer = (new ControllerBranch)->ctrShowBranchName($branchcode);
      echo json_encode($answer);
    }
}
 
if(isset($_POST["branchcode"])){
  $getBranchName = new AjaxBranchName();
  $getBranchName -> branchcode = $_POST["branchcode"];
  $getBranchName -> ajaxGetBranchName();
}