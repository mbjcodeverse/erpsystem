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

	static public function ctrPrintCashierReset($resetcode, $resetdetail, $resetype){
		$answer = (new ModelSale)->mdlPrintCashierReset($resetcode, $resetdetail, $resetype);
		return $answer;
	}	

	static public function ctrResetPreview($branchcode, $postedby, $reset_detail, $sale_mode){
		$answer = (new ModelSale)->mdlResetPreview($branchcode, $postedby, $reset_detail, $sale_mode);
		return $answer;
	}
}