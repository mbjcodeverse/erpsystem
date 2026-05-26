<?php
require_once "connection.php";
class ModelProducts{
	static public function mdlAddProduct($data){
		$db = new Connection();
		$pdo = $db->connect();
        try{
        	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $pdo->beginTransaction();

            $product_id = $pdo->prepare("SELECT CONCAT('P', LPAD((count(id)+1),5,'0')) as gen_id FROM products FOR UPDATE");

            $product_id->execute();
		    $productid = $product_id -> fetchAll(PDO::FETCH_ASSOC);

			$productcode = $productid[0]['gen_id'];
			$stmt = $pdo->prepare("INSERT INTO products(categorycode, brandcode, purchaseitem, isactive, prodid, barcode, pdesc, sellunit, specs, measure, uprice, profit, ucost, abbr, vatdesc, reorder, disprice, minqty, prodname)
										        VALUES (:categorycode, :brandcode, :purchaseitem, :isactive, :prodid, :barcode, :pdesc, :sellunit, :specs, :measure, :uprice, :profit, :ucost, :abbr, :vatdesc, :reorder, :disprice, :minqty, :prodname)");

			$stmt->bindParam(":categorycode", $data["categorycode"], PDO::PARAM_STR);
			$stmt->bindParam(":brandcode", $data["brandcode"], PDO::PARAM_STR);
			$stmt->bindParam(":purchaseitem", $data["purchaseitem"], PDO::PARAM_INT);
			$stmt->bindParam(":isactive", $data["isactive"], PDO::PARAM_INT);
			$stmt->bindParam(":prodid", $productid[0]['gen_id'], PDO::PARAM_STR);
			$stmt->bindParam(":barcode", $data["barcode"], PDO::PARAM_STR);
			$stmt->bindParam(":pdesc", $data["pdesc"], PDO::PARAM_STR);
			$stmt->bindParam(":sellunit", $data["sellunit"], PDO::PARAM_STR);
			$stmt->bindParam(":specs", $data["specs"], PDO::PARAM_STR);
			$stmt->bindParam(":measure", $data["measure"], PDO::PARAM_STR);
			$stmt->bindParam(":uprice", $data["uprice"], PDO::PARAM_STR);
			$stmt->bindParam(":profit", $data["profit"], PDO::PARAM_STR);
			$stmt->bindParam(":ucost", $data["ucost"], PDO::PARAM_STR);
			$stmt->bindParam(":abbr", $data["abbr"], PDO::PARAM_STR);
			$stmt->bindParam(":vatdesc", $data["vatdesc"], PDO::PARAM_STR);
			$stmt->bindParam(":reorder", $data["reorder"], PDO::PARAM_STR);
			$stmt->bindParam(":disprice", $data["disprice"], PDO::PARAM_STR);
			$stmt->bindParam(":minqty", $data["minqty"], PDO::PARAM_STR);
			$stmt->bindParam(":prodname", $data["prodname"], PDO::PARAM_STR);
			$stmt->execute();

			$branches = $pdo->prepare("SELECT * FROM branch");
            $branches->execute();
		    $branch = $branches -> fetchAll(PDO::FETCH_ASSOC);
		    if(count($branch)!=0){
		    	for($i = 0; $i < count($branch); $i++){
		    	  $branchcode = $branch[$i]['branchcode'];
		    	  $branchprod = $branch[$i]['branchcode'].$prodid[0]['gen_id'];

		    	  $bp = $pdo->prepare("INSERT INTO branchproducts(prodid, isactive, ucost, uprice, branchcode, branchprod) VALUES (:prodid, :isactive, :ucost, :uprice, :branchcode, :branchprod)");

		    	  $bp->bindParam(":prodid", $prodid[0]['gen_id'], PDO::PARAM_STR);
		    	  $bp->bindParam(":isactive", $data["isactive"], PDO::PARAM_INT);
				  $bp->bindParam(":ucost", $data["ucost"], PDO::PARAM_STR);
		    	  $bp->bindParam(":uprice", $data["uprice"], PDO::PARAM_STR);
		    	  $bp->bindParam(":branchcode", $branchcode, PDO::PARAM_STR);
		    	  $bp->bindParam(":branchprod", $branchprod, PDO::PARAM_STR);
		    	  $bp->execute();
		    	}
		    }

		    $pdo->commit();
		    return $productcode;
		}catch (Exception $e){
			$pdo->rollBack();
			return $e->getMessage();
		}	
		$pdo = null;	
		$stmt = null;
	}

	static public function mdlEditProduct($data){
		$db = new Connection();
		$pdo = $db->connect();
        try{
        	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $pdo->beginTransaction();

			$productcode = $data["prodid"];
			$stmt = $pdo->prepare("UPDATE products SET categorycode = :categorycode,
													   brandcode = :brandcode,
													   purchaseitem = :purchaseitem,
													   isactive = :isactive,
													   prodid = :prodid,
													   barcode = :barcode,
													   pdesc = :pdesc,
													   sellunit = :sellunit,
													   specs = :specs,
													   measure = :measure,
													   uprice = :uprice,
													   profit = :profit,
													   ucost = :ucost,
													   abbr = :abbr,
													   vatdesc = :vatdesc,
													   reorder = :reorder,
													   disprice = :disprice,
													   minqty = :minqty,
													   prodname = :prodname
													WHERE prodid = :prodid");

			$stmt->bindParam(":categorycode", $data["categorycode"], PDO::PARAM_STR);
			$stmt->bindParam(":brandcode", $data["brandcode"], PDO::PARAM_STR);
			$stmt->bindParam(":purchaseitem", $data["purchaseitem"], PDO::PARAM_INT);
			$stmt->bindParam(":isactive", $data["isactive"], PDO::PARAM_INT);
			$stmt->bindParam(":prodid", $data["prodid"], PDO::PARAM_STR);
			$stmt->bindParam(":barcode", $data["barcode"], PDO::PARAM_STR);
			$stmt->bindParam(":pdesc", $data["pdesc"], PDO::PARAM_STR);
			$stmt->bindParam(":sellunit", $data["sellunit"], PDO::PARAM_STR);
			$stmt->bindParam(":specs", $data["specs"], PDO::PARAM_STR);
			$stmt->bindParam(":measure", $data["measure"], PDO::PARAM_STR);
			$stmt->bindParam(":uprice", $data["uprice"], PDO::PARAM_STR);
			$stmt->bindParam(":profit", $data["profit"], PDO::PARAM_STR);
			$stmt->bindParam(":ucost", $data["ucost"], PDO::PARAM_STR);
			$stmt->bindParam(":abbr", $data["abbr"], PDO::PARAM_STR);
			$stmt->bindParam(":vatdesc", $data["vatdesc"], PDO::PARAM_STR);
			$stmt->bindParam(":reorder", $data["reorder"], PDO::PARAM_STR);
			$stmt->bindParam(":disprice", $data["disprice"], PDO::PARAM_STR);
			$stmt->bindParam(":minqty", $data["minqty"], PDO::PARAM_STR);
			$stmt->bindParam(":prodname", $data["prodname"], PDO::PARAM_STR);
			$stmt->execute();  

			$branch_products = $pdo->prepare("UPDATE productsbranch SET isactive = :isactive,
																		ucost = :ucost,
																		uprice = :uprice
																	WHERE prodid = :prodid");
			$branch_products->bindParam(":prodid", $data["prodid"], PDO::PARAM_STR);
			$branch_products->bindParam(":ucost", $data["ucost"], PDO::PARAM_STR);
			$branch_products->bindParam(":uprice", $data["uprice"], PDO::PARAM_STR);
			$branch_products->bindParam(":isactive", $data["isactive"], PDO::PARAM_STR);
			$branch_products->execute();

		    $pdo->commit();
		    return $productcode;
		}catch (Exception $e){
			$pdo->rollBack();
			return "error";
		}	
		$pdo = null;	
		$stmt = null;
	}		

    static public function mdlProductSearchList($categorycode, $brandcode, $vatdesc){
        if ($categorycode != ''){
			$category_code = " AND (c.categorycode = '$categorycode')";
		}else{
			$category_code = "";
		}

        if ($brandcode != ''){
			$brand_code = " AND (b.brandcode = '$brandcode')";
		}else{
			$brand_code = "";
		}

        if ($vatdesc != ''){
			$vat_desc = " AND (p.vatdesc = '$vatdesc')";
		}else{
			$vat_desc = "";
		}

        $whereClause = "WHERE (p.prodid != '')" . $category_code . $brand_code . $vat_desc;

        $stmt = (new Connection)->connect()->prepare("SELECT c.catdescription,p.prodid,
                                                             p.prodname,p.uprice,p.ucost,p.profit
                                                      FROM category c INNER JOIN products p ON (c.categorycode = p.categorycode)
                                                                      LEFT JOIN brand b ON (b.brandcode = p.brandcode)
                                                      $whereClause ORDER BY p.prodname");    
        $stmt -> execute();
		return $stmt -> fetchAll();
		$stmt -> close();
		$stmt = null;                                                 
    }

	public static function mdlProductInfo($prodid){
		$sql = "SELECT * FROM products WHERE prodid = :prodid LIMIT 1";
		$conn = (new Connection)->connect();
		$stmt = $conn->prepare($sql);
		$stmt->bindParam(':prodid', $prodid, PDO::PARAM_STR);
		$stmt->execute();
		$result = $stmt->fetch(PDO::FETCH_ASSOC);
		$stmt->closeCursor();
		$stmt = null;
		return $result ?: null;
	}

	public static function mdlBranchProductList(string $branchcode): array{
		try {
			$conn = (new Connection)->connect();
			$sql = "SELECT 
					p.prodid,
					p.prodname,
					p.barcode,
					bp.uprice,
					bp.ucost,
					(bp.uprice - bp.ucost) AS profit,
					p.disprice,
					p.minqty,
					p.vatdesc
				FROM products p
				INNER JOIN productsbranch bp 
					ON p.prodid = bp.prodid
				WHERE bp.branchcode = :branchcode
				ORDER BY p.prodname";

			$stmt = $conn->prepare($sql);
			$stmt->bindParam(':branchcode', $branchcode, PDO::PARAM_STR);
			$stmt->execute();
			return $stmt->fetchAll(PDO::FETCH_ASSOC);
		} catch (PDOException $e) {
			error_log($e->getMessage());
			return [];
		} finally {
			$stmt = null;
			$conn = null;
		}
	}		
}