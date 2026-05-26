<?php
require_once "../controllers/userrights.controller.php";
require_once "../models/userrights.model.php";

class resetAccount{
  public $userid;
  public $username;
  public $password;
  public $overridekey;

  public function resetAccountSave(){
    $userid = $this->userid;
    $username = $this->username;
    $password = $this->password;
    $overridekey = $this->overridekey;
    $data = array("userid"=>$userid,
                  "username"=>$username,
                  "password"=>$password,
                  "overridekey"=>$overridekey);
    $answer = (new ControllerUserRights)->ctrResetAccount($data);
    echo $answer;
  }
}

$inputUserID = new resetAccount();
$inputUserID -> userid = $_POST["userid"];
$inputUserID -> username = $_POST["username"];
$inputUserID -> password = $_POST["password"];
$inputUserID -> overridekey = $_POST["overridekey"];
$inputUserID -> resetAccountSave();