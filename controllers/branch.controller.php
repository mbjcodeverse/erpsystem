<?php
class ControllerBranch{
	static public function ctrCreateBranch($data){
	   	$answer = (new ModelBranch)->mdlAddBranch($data);
		return $answer;
	}

	static public function ctrEditBranch($data){
	   	$answer = (new ModelBranch)->mdlEditBranch($data);
		return $answer;
	}

	static public function ctrShowBranch($item, $value){
		$answer = (new ModelBranch)->mdlShowBranch($item, $value);
		return $answer;
	}	

	static public function ctrShowBranchName($branchcode){
		$answer = (new ModelBranch)->mdlShowBranchName($branchcode);
		return $answer;
	}			

	static public function ctrShowBranchList(){
		$answer = (new ModelBranch)->mdlShowBranchList();
		return $answer;
	}

	static public function ctrGetPrefix($prefix){
		$answer = (new ModelBranch)->mdlGetPrefix($prefix);
		return $answer;
	}

	static public function ctrGetPrefixCode($branchcode){
		$answer = (new ModelBranch)->mdlGetPrefixCode($branchcode);
		return $answer;
	}		
}
