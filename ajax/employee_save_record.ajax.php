<?php
require_once "../controllers/employees.controller.php";
require_once "../models/employees.model.php";

class employeeAddEdit{
  public $trans_type; 
  public $empid;
  public $isactive;
  public $lname;
  public $fname;
  public $mi;
  public $bday;
  public $gender;
  public $address;
  public $mobile;
  public $positioncode;
  public $sssno;
  public $phino;
  public $pagibig;
  public $tin;
  public $estatus;

  public function employeeAddEditRoutine(){
    $trans_type = $this->trans_type;
    $empid = $this->empid;
    $isactive = $this->isactive;
  	$lname = $this->lname;
  	$fname = $this->fname;
    $mi = $this->mi;
  	$bday = $this->bday;
    $gender = $this->gender;
    $address = $this->address;
    $mobile = $this->mobile;
  	$positioncode = $this->positioncode;
    $sssno = $this->sssno;
    $phino = $this->phino;
    $pagibig = $this->pagibig;
    $tin = $this->tin;
    $estatus = $this->estatus;

    $data = array("empid"=>$empid,
                  "isactive"=>$isactive,
                  "lname"=>$lname,
                  "fname"=>$fname,
                  "mi"=>$mi,
                  "bday"=>$bday,
                  "gender"=>$gender,
                  "address"=>$address,
                  "mobile"=>$mobile,
                  "positioncode"=>$positioncode,
                  "sssno"=>$sssno,
                  "phino"=>$phino,
                  "pagibig"=>$pagibig,
                  "tin"=>$tin,
                  "estatus"=>$estatus);

    if ($trans_type == 'New'){
      $answer = (new ControllerEmployees)->ctrAddEmployee($data);
      echo $answer;
    }else{
      $answer = (new ControllerEmployees)->ctrEditEmployee($data);
      echo $answer;
    }

  }
}

$process_employee = new employeeAddEdit();

$process_employee -> trans_type = $_POST["trans_type"];
$process_employee -> empid = $_POST["empid"];
$process_employee -> isactive = $_POST["isactive"];
$process_employee -> lname = $_POST["lname"];
$process_employee -> isactive = $_POST["isactive"];
$process_employee -> fname = $_POST["fname"];
$process_employee -> mi = $_POST["mi"];
$process_employee -> bday = $_POST["bday"];
$process_employee -> gender = $_POST["gender"];
$process_employee -> address = $_POST["address"];
$process_employee -> mobile = $_POST["mobile"];
$process_employee -> positioncode = $_POST["positioncode"];
$process_employee -> sssno = $_POST["sssno"];
$process_employee -> phino = $_POST["phino"];
$process_employee -> pagibig = $_POST["pagibig"];
$process_employee -> tin = $_POST["tin"];
$process_employee -> estatus = $_POST["estatus"];

$process_employee -> employeeAddEditRoutine();