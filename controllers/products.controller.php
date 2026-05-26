<?php
class ControllerProducts{
	static public function ctrAddProduct($data){
	   	$answer = (new ModelProducts)->mdlAddProduct($data);
		return $answer;
	}
	static public function ctrEditProduct($data){
		$answer = (new ModelProducts)->mdlEditProduct($data);
		return $answer;
    }

	static public function ctrProductInfo($prodid){
		$answer = (new ModelProducts)->mdlProductInfo($prodid);
		return $answer;
	}

    static public function ctrProductSearchList($categorycode, $brandcode, $vatdesc){
		$answer = (new ModelProducts)->mdlProductSearchList($categorycode, $brandcode, $vatdesc);
		return $answer;
	}	

	static public function ctrBranchProductList($branchcode){
		$answer = (new ModelProducts)->mdlBranchProductList($branchcode);
		return $answer;
	}
}