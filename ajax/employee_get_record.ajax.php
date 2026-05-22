<?php
require_once "../controllers/employees.controller.php";
require_once "../models/employees.model.php";

class EmployeeDetails{
    public $empid;
    public function getEmployeeDetails(){
      $empid = $this->empid;
      $answer = (new ControllerEmployees)->ctrEmployeeInfo($empid);
      echo json_encode($answer);
    }
}
 
if(isset($_POST["empid"])){
  $getEmployee = new EmployeeDetails();
  $getEmployee -> empid = $_POST["empid"];
  $getEmployee -> getEmployeeDetails();
}