<?php
require_once "../controllers/employees.controller.php";
require_once "../models/employees.model.php";

class AjaxEmployeeList{ 
   public function ajaxDisplayEmployeeList(){
     $answer = (new ControllerEmployees)->ctrEmployeeList();
     echo json_encode($answer);
   }
}

$product_list = new AjaxEmployeeList();
$product_list -> ajaxDisplayEmployeeList();