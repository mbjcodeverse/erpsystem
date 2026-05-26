<?php
class ControllerReset{
	static public function ctrGetResetCount($reset_prefix){
		$reset_count = (new ModelReset)->mdlGetResetCount($reset_prefix);
		return $reset_count;
	}

    static public function ctrPostReset($branchcode, $resetcode, $resetby, $resetype){
		$answer = (new ModelReset)->mdlPostReset($branchcode, $resetcode, $resetby, $resetype);
        return $answer;
	}
}