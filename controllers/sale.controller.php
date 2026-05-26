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

	static public function ctrGenerateSalesReport($reptype, $branchcode, $start_date, $end_date, $categorycode, $salemode, $status){
		$answer = (new ModelSale)->mdlGenerateSalesReport($reptype, $branchcode, $start_date, $end_date, $categorycode, $salemode, $status);
		return $answer;
	}
}