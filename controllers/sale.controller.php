<?php
class ControllerSale{
	static public function ctrAddSale($data){
	   	$answer = (new ModelSale)->mdlAddSale($data);
		return $answer;
	}

	static public function ctrGetSale($invno){
		$answer = (new ModelSale)->mdlGetSale($invno);
		return $answer;
	}

	static public function ctrGetSaleItems($invno){
		$products = (new ModelSale)->mdlGetSaleItems($invno);
		return $products;
	}
}