<?php
require_once "../controllers/branch.controller.php";
require_once "../models/branch.model.php";

class saveBranch{
  public $trans_type; 

  public $id;
  public $prefix;
  public $isactive;
  public $bname;
  public $bdescription;
  public $baddress;
  public $bcontactnum;
  public $allowedtrans;
  public $resetdetail;
  public $grantincentive;

  public function saveBranchRecord(){
    $trans_type = $this->trans_type;

    $id = $this->id;
  	$prefix = $this->prefix;
  	$isactive = $this->isactive;
  	$bname = $this->bname;
    $bdescription = $this->bdescription;
  	$baddress = $this->baddress;
  	$bcontactnum = $this->bcontactnum;
  	$allowedtrans = $this->allowedtrans;
    $resetdetail = $this->resetdetail;
    $grantincentive = $this->grantincentive;

    $data = array("id"=>$id,
                  "prefix"=>$prefix,
    	            "isactive"=>$isactive,
                  "bname"=>$bname,
                  "bdescription"=>$bdescription,
                  "baddress"=>$baddress,
                  "bcontactnum"=>$bcontactnum,
                  "allowedtrans"=>$allowedtrans,
                  "resetdetail"=>$resetdetail,
                  "grantincentive"=>$grantincentive);

    if ($trans_type == 'New'){
      $answer = (new ControllerBranch)->ctrCreateBranch($data);
      return $answer;
    }else{
      $answer = (new ControllerBranch)->ctrEditBranch($data);
      return $answer;
    }

  }
}

$save_branch = new saveBranch();
$save_branch -> trans_type = $_POST["trans_type"];

$save_branch -> id = $_POST["id"];
$save_branch -> prefix = $_POST["prefix"];
$save_branch -> isactive = $_POST["isactive"];
$save_branch -> bname = $_POST["bname"];
$save_branch -> bdescription = $_POST["bdescription"];
$save_branch -> baddress = $_POST["baddress"];
$save_branch -> bcontactnum = $_POST["bcontactnum"];
$save_branch -> allowedtrans = $_POST["allowedtrans"];
$save_branch -> resetdetail = $_POST["resetdetail"];
$save_branch -> grantincentive = $_POST["grantincentive"];

$save_branch -> saveBranchRecord();