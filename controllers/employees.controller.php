<?php
class ControllerEmployees{
	static public function ctrAddEmployee($data){
	   	$answer = (new ModelEmployees)->mdlAddEmployee($data);
		return $answer;
	}
	static public function ctrEditEmployee($data){
		$answer = (new ModelEmployees)->mdlEditEmployee($data);
		return $answer;
    }	
    static public function ctrEmployeeList(){
		$answer = (new ModelEmployees)->mdlEmployeeList();
		return $answer;
	}

	static public function ctrEmployeeInfo($empid){
		$answer = (new ModelEmployees)->mdlEmployeeInfo($empid);
		return $answer;
	}

	static public function ctrShowPosition(){
		$answer = (new ModelEmployees)->mdlShowPosition();
		return $answer;
	}
}
