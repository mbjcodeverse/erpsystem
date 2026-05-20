<?php
require_once "../controllers/products.controller.php";
require_once "../models/products.model.php";

class productsAddEdit{
  public $trans_type; 
  public $categorycode;
  public $brandcode;
  public $purchaseitem;
  public $isactive;
  public $prodid;
  public $barcode;
  public $pdesc;
  public $sellunit;
  public $specs;
  public $measure;
  public $uprice;
  public $profit;
  public $ucost;
  public $abbr;
  public $vatdesc;
  public $reorder;
  public $disprice;
  public $minqty;
  public $prodname;

  public function productsAddEditRoutine(){
    $trans_type = $this->trans_type;
    $categorycode = $this->categorycode;
    $brandcode = $this->brandcode;
  	$purchaseitem = $this->purchaseitem;
    $isactive = $this->isactive;
  	$prodid = $this->prodid;
    $barcode = $this->barcode;
  	$pdesc = $this->pdesc;
    $sellunit = $this->sellunit;
    $specs = $this->specs;
    $measure = $this->measure;
  	$uprice = $this->uprice;
    $profit = $this->profit;
    $ucost = $this->ucost;
    $abbr = $this->abbr;
    $vatdesc = $this->vatdesc;
    $reorder = $this->reorder;
    $disprice = $this->disprice;
    $minqty = $this->minqty;
    $prodname = $this->prodname;

    $data = array("categorycode"=>$categorycode,
                  "brandcode"=>$brandcode,
                  "purchaseitem"=>$purchaseitem,
                  "isactive"=>$isactive,
                  "prodid"=>$prodid,
                  "barcode"=>$barcode,
                  "pdesc"=>$pdesc,
                  "sellunit"=>$sellunit,
                  "specs"=>$specs,
                  "measure"=>$measure,
                  "uprice"=>$uprice,
                  "profit"=>$profit,
                  "ucost"=>$ucost,
                  "abbr"=>$abbr,
                  "vatdesc"=>$vatdesc,
                  "reorder"=>$reorder,
                  "disprice"=>$disprice,
                  "minqty"=>$minqty,
                  "prodname"=>$prodname);

    if ($trans_type == 'New'){
      $answer = (new ControllerProducts)->ctrAddProduct($data);
      echo $answer;
    }else{
      $answer = (new ControllerProducts)->ctrEditProduct($data);
      echo $answer;
    }

  }
}

$process_product = new productsAddEdit();

$process_product -> trans_type = $_POST["trans_type"];
$process_product -> categorycode = $_POST["categorycode"];
$process_product -> brandcode = $_POST["brandcode"];
$process_product -> purchaseitem = $_POST["purchaseitem"];
$process_product -> isactive = $_POST["isactive"];
$process_product -> prodid = $_POST["prodid"];
$process_product -> barcode = $_POST["barcode"];
$process_product -> pdesc = $_POST["pdesc"];
$process_product -> sellunit = $_POST["sellunit"];
$process_product -> specs = $_POST["specs"];
$process_product -> measure = $_POST["measure"];
$process_product -> uprice = $_POST["uprice"];
$process_product -> profit = $_POST["profit"];
$process_product -> ucost = $_POST["ucost"];
$process_product -> abbr = $_POST["abbr"];
$process_product -> vatdesc = $_POST["vatdesc"];
$process_product -> reorder = $_POST["reorder"];
$process_product -> disprice = $_POST["disprice"];
$process_product -> minqty = $_POST["minqty"];
$process_product -> prodname = $_POST["prodname"];

$process_product -> productsAddEditRoutine();