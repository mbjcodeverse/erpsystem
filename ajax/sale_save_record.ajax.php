<?php
require_once "../controllers/sale.controller.php";
require_once "../models/sale.model.php";

class salesOrderEntry{ 
  public $prefix;
  public $userid;

  public $branchcode;
  public $sdate;
  public $stime;
  public $salemode;
  public $customercode;
  public $soldto;
  public $status;
  public $vatable;
  public $excempt;
  public $vatamnt;
  public $amount;
  public $discount;
  public $netamount;
  public $postedby;
  public $productlist;

  public function salesOrderEntrySave(){
    $prefix = $this->prefix;
    $userid = $this->userid;

  	$branchcode = $this->branchcode;
  	$sdate = $this->sdate;
    $stime = $this->stime;
  	$salemode = $this->salemode;
    $customercode = $this->customercode;
  	$soldto = $this->soldto;
  	$status = $this->status;
    $vatable = $this->vatable;
    $excempt = $this->excempt;
    $vatamnt = $this->vatamnt;
  	$amount = $this->amount;
  	$discount = $this->discount;
  	$netamount = $this->netamount; 
    $postedby = $this->postedby; 
  	$productlist = $this->productlist;

    $data = array("branchcode"=>$branchcode,
                  "prefix"=>$prefix,
                  "userid"=>$userid,
                  "sdate"=>$sdate,
                  "stime"=>$stime,
                  "salemode"=>$salemode,
                  "customercode"=>$customercode,
                  "soldto"=>$soldto,
                  "status"=>$status,
                  "vatable"=>$vatable,
                  "excempt"=>$excempt,
                  "vatamnt"=>$vatamnt,                  
                  "amount"=>$amount,
                  "discount"=>$discount,
                  "netamount"=>$netamount,
                  "postedby"=>$postedby,
                  "productlist"=>$productlist);

    $answer = (new ControllerSale)->ctrAddSale($data);
    echo $answer;
  }
}

$processSales = new salesOrderEntry();

$processSales -> prefix = $_POST["prefix"];
$processSales -> userid = $_POST["userid"];
$processSales -> branchcode = $_POST["branchcode"];
$processSales -> sdate = $_POST["sdate"];
$processSales -> stime = $_POST["stime"];
$processSales -> salemode = $_POST["salemode"];
$processSales -> customercode = $_POST["customercode"];
$processSales -> soldto = $_POST["soldto"];
$processSales -> status = $_POST["status"];
$processSales -> vatable = $_POST["vatable"];
$processSales -> excempt = $_POST["excempt"];
$processSales -> vatamnt = $_POST["vatamnt"];
$processSales -> amount = $_POST["amount"];
$processSales -> discount = $_POST["discount"];
$processSales -> netamount = $_POST["netamount"];
$processSales -> postedby = $_POST["postedby"];
$processSales -> productlist = $_POST["productlist"];

$processSales -> salesOrderEntrySave();