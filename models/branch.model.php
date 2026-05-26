<?php
require_once "connection.php";
class ModelBranch{
	static public function mdlAddBranch($data){
		$db = new Connection();
		$pdo = $db->connect();
        try{
        	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $pdo->beginTransaction();

            $branch_id = $pdo->prepare("SELECT CONCAT('B', LPAD((count(id)+1),3,'0')) as gen_id  FROM branch FOR UPDATE");

            $branch_id->execute();
		    $branch_code = $branch_id -> fetchAll(PDO::FETCH_ASSOC);

			$stmt = $pdo->prepare("INSERT INTO branch(branchcode, prefix, isactive, bname, bdescription, baddress, bcontactnum, allowedtrans, resetdetail, grantincentive) VALUES (:branchcode, :prefix,  :isactive, :bname, :bdescription, :baddress, :bcontactnum, :allowedtrans, :resetdetail, :grantincentive)");

			$stmt->bindParam(":branchcode", $branch_code[0]['gen_id'], PDO::PARAM_STR);
			$stmt->bindParam(":prefix", $data["prefix"], PDO::PARAM_STR);
			$stmt->bindParam(":isactive", $data["isactive"], PDO::PARAM_INT);
			$stmt->bindParam(":bname", $data["bname"], PDO::PARAM_STR);
			$stmt->bindParam(":bdescription", $data["bdescription"], PDO::PARAM_STR);
			$stmt->bindParam(":baddress", $data["baddress"], PDO::PARAM_STR);
			$stmt->bindParam(":bcontactnum", $data["bcontactnum"], PDO::PARAM_STR);
			$stmt->bindParam(":allowedtrans", $data["allowedtrans"], PDO::PARAM_STR);
			$stmt->bindParam(":resetdetail", $data["resetdetail"], PDO::PARAM_STR);
			$stmt->bindParam(":grantincentive", $data["grantincentive"], PDO::PARAM_INT);
			$stmt->execute();

			$products = $pdo->prepare("SELECT * FROM products");
            $products->execute();
		    $product_list = $products -> fetchAll(PDO::FETCH_ASSOC);
		    if(count($product_list)!=0){
		    	for($i = 0; $i < count($product_list); $i++){
		    	  $prodid = $product_list[$i]['prodid'];
		    	  $isactive = $product_list[$i]['isactive'];
				  $ucost = $product_list[$i]['ucost'];
		    	  $uprice = $product_list[$i]['uprice'];
		    	  $branchcode = $branch_code[0]['gen_id'];
		    	  $branchprod = $branchcode . $prodid;

		    	  $bp = $pdo->prepare("INSERT INTO productsbranch(prodid, isactive, ucost, uprice, branchcode, branchprod) VALUES (:prodid, :isactive, :ucost, :uprice, :branchcode, :branchprod)");

		    	  $bp->bindParam(":prodid", $prodid, PDO::PARAM_STR);
		    	  $bp->bindParam(":isactive", $isactive, PDO::PARAM_INT);
				  $bp->bindParam(":ucost", $ucost, PDO::PARAM_STR);
		    	  $bp->bindParam(":uprice", $uprice, PDO::PARAM_STR);
		    	  $bp->bindParam(":branchcode", $branchcode, PDO::PARAM_STR);
		    	  $bp->bindParam(":branchprod", $branchprod, PDO::PARAM_STR);
		    	  $bp->execute();
		    	}
		    }			

		    $pdo->commit();
		    return "ok";
		}catch (Exception $e){
			$pdo->rollBack();
			return $e->getMessage();
		}	
		$pdo = null;	
		$stmt = null;
	}

	static public function mdlEditBranch($data){
		$db = new Connection();
		$pdo = $db->connect();
        try{
        	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $pdo->beginTransaction();

			$stmt = $pdo->prepare("UPDATE branch SET prefix = :prefix, isactive = :isactive, bname = :bname, bdescription = :bdescription, baddress = :baddress, bcontactnum = :bcontactnum, allowedtrans = :allowedtrans, resetdetail = :resetdetail, grantincentive = :grantincentive WHERE id = :id");

			$stmt->bindParam(":id", $data["id"], PDO::PARAM_INT);
			$stmt->bindParam(":prefix", $data["prefix"], PDO::PARAM_STR);
			$stmt->bindParam(":isactive", $data["isactive"], PDO::PARAM_INT);
			$stmt->bindParam(":bname", $data["bname"], PDO::PARAM_STR);
			$stmt->bindParam(":bdescription", $data["bdescription"], PDO::PARAM_STR);
			$stmt->bindParam(":baddress", $data["baddress"], PDO::PARAM_STR);
			$stmt->bindParam(":bcontactnum", $data["bcontactnum"], PDO::PARAM_STR);
			$stmt->bindParam(":allowedtrans", $data["allowedtrans"], PDO::PARAM_STR);
			$stmt->bindParam(":resetdetail", $data["resetdetail"], PDO::PARAM_STR);
			$stmt->bindParam(":grantincentive", $data["grantincentive"], PDO::PARAM_INT);
			$stmt->execute();
		    $pdo->commit();
		    return "ok";
		}catch (Exception $e){
			$pdo->rollBack();
			return "error";
		}	
		$pdo = null;	
		$stmt = null;
	}	

	static public function mdlShowBranch($item, $value){
		$stmt = (new Connection)->connect()->prepare("SELECT * FROM branch WHERE $item = :$item");
		$stmt -> bindParam(":".$item, $value, PDO::PARAM_STR);
		$stmt -> execute();
		return $stmt -> fetch();
		$stmt -> close();
		$stmt = null;
	}

	static public function mdlShowBranchName($branchcode){
		$stmt = (new Connection)->connect()->prepare("SELECT * FROM branch WHERE branchcode = '$branchcode'");
		$stmt -> execute();
		return $stmt -> fetch();
		$stmt -> close();
		$stmt = null;
	}	

	static public function mdlShowBranchList(){
		$stmt = (new Connection)->connect()->prepare("SELECT * FROM branch ORDER BY bname");
		$stmt -> execute();
		return $stmt -> fetchAll();
		$stmt -> close();
		$stmt = null;
	}

	static public function mdlGetPrefix($prefix){
		$stmt = (new Connection)->connect()->prepare("SELECT prefix FROM branch WHERE prefix = '$prefix'");
		$stmt -> execute();
		return $stmt -> fetch();
		$stmt -> close();
		$stmt = null;
	}

	static public function mdlGetPrefixCode($branchcode){
		$stmt = (new Connection)->connect()->prepare("SELECT prefix FROM branch WHERE branchcode = '$branchcode'");
		$stmt -> execute();
		return $stmt -> fetch();
		$stmt -> close();
		$stmt = null;
	}			
}