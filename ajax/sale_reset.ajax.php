<?php
require_once "../controllers/reset.controller.php";
require_once "../models/reset.model.php";

class resetEntry{ 
  public $branchcode;
  public $resetcode;
  public $resetby;
  public $resetype;

  public function resetEntrySave(){
    $branchcode = $this->branchcode;
    $resetcode = $this->resetcode;
    $resetby = $this->resetby;
    $resetype = $this->resetype;

    $answer = (new ControllerReset)->ctrPostReset($branchcode, $resetcode, $resetby, $resetype);
    return $answer;
  }
}

$processReset = new resetEntry();
$processReset -> branchcode = $_POST["branchcode"];
$processReset -> resetcode = $_POST["resetcode"];
$processReset -> resetby = $_POST["resetby"];
$processReset -> resetype = $_POST["resetype"];
$processReset -> resetEntrySave();