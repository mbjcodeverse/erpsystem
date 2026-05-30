<?php
date_default_timezone_set('Asia/Manila');
require_once "connection.php";
class ModelSale{
	static public function mdlAddSale($data){
		$db = new Connection();
		$pdo = $db->connect();
        try{
        	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $pdo->beginTransaction();

			$code_format = $data["prefix"] 
             . substr($data["userid"], 1, 4) 
             . substr(date("Y"), -2);

			$stmt = $pdo->prepare("
				SELECT invno
				FROM sales
				WHERE invno LIKE :code
				ORDER BY invno DESC
				LIMIT 1
			");

			$stmt->execute([
				':code' => $code_format . '%'
			]);

			$lastSale = $stmt->fetch(PDO::FETCH_ASSOC);
			if ($lastSale) {
				$lastSequence = (int) substr($lastSale['invno'], -5);
				$nextSequence = $lastSequence + 1;
			} else {
				$nextSequence = 1;
			}

			// Final invoice number
			$salecode = $code_format . str_pad($nextSequence, 5, '0', STR_PAD_LEFT);

		    $resetted = 'F';
            $uploaded = 'T';
			$resetcode = '';

			$stmt = $pdo->prepare("INSERT INTO sales(branchcode, invno, sdate, stime, salemode, customercode, soldto, status, vatable, excempt, vatamnt, amount, discount, netamount, postedby, resetted, resetcode, uploaded, productlist) VALUES (:branchcode, :invno, :sdate, :stime, :salemode, :customercode, :soldto, :status, :vatable, :excempt, :vatamnt, :amount, :discount, :netamount, :postedby, :resetted, :resetcode, :uploaded, :productlist)");	

			$stmt->bindParam(":branchcode", $data["branchcode"], PDO::PARAM_STR);
			$stmt->bindParam(":invno", $salecode, PDO::PARAM_STR);
			$stmt->bindParam(":sdate", $data["sdate"], PDO::PARAM_STR);
			$stmt->bindParam(":stime", $data["stime"], PDO::PARAM_STR);	
			$stmt->bindParam(":salemode", $data["salemode"], PDO::PARAM_STR);
			$stmt->bindParam(":customercode", $data["customercode"], PDO::PARAM_STR);
			$stmt->bindParam(":soldto", $data["soldto"], PDO::PARAM_STR);
			$stmt->bindParam(":status", $data["status"], PDO::PARAM_STR);	
            $stmt->bindParam(":vatable", $data["vatable"], PDO::PARAM_STR);
            $stmt->bindParam(":excempt", $data["excempt"], PDO::PARAM_STR);
            $stmt->bindParam(":vatamnt", $data["vatamnt"], PDO::PARAM_STR);	
            $stmt->bindParam(":amount", $data["amount"], PDO::PARAM_STR);
            $stmt->bindParam(":discount", $data["discount"], PDO::PARAM_STR);
            $stmt->bindParam(":netamount", $data["netamount"], PDO::PARAM_STR);
            $stmt->bindParam(":postedby", $data["postedby"], PDO::PARAM_STR);	
            $stmt->bindParam(":resetted", $resetted, PDO::PARAM_STR);
			$stmt->bindParam(":resetcode", $resetcode, PDO::PARAM_STR);
            $stmt->bindParam(":uploaded", $uploaded, PDO::PARAM_STR);
            $stmt->bindParam(":productlist", $data["productlist"], PDO::PARAM_STR);
			$stmt->execute();

			$resetted = 'F';
			$uploaded = 'F';
			$itemsList = json_decode($data["productlist"]);
			foreach($itemsList as $product){
				$items = $pdo->prepare("INSERT INTO salesitems(invno, qty, ucost, uprice, origprice, tamount, prodid, uploaded) VALUES (:invno, :qty, :ucost, :uprice, :origprice, :tamount, :prodid, :uploaded)");

				$items->bindParam(":invno", $salecode, PDO::PARAM_STR);
				$items->bindParam(":qty", $product->qty, PDO::PARAM_STR);
				$items->bindParam(":ucost", $product->ucost, PDO::PARAM_STR);
				$items->bindParam(":uprice", $product->uprice, PDO::PARAM_STR);
				$items->bindParam(":origprice", $product->origprice, PDO::PARAM_STR);
				$items->bindParam(":tamount", $product->tamount, PDO::PARAM_STR);
				$items->bindParam(":prodid", $product->prodid, PDO::PARAM_STR);
				$items->bindParam(":uploaded", $uploaded, PDO::PARAM_STR);
				$items->execute();
			}			

		    $pdo->commit();
		    return $salecode;
		}catch (Exception $e){
			$pdo->rollBack();
			return $e->getMessage();
		}	
		$pdo = null;	
		$stmt = null;
	}  	

	public static function mdlGetSale($invno){
		$conn = (new Connection)->connect();

		$stmt = $conn->prepare("
			SELECT *
			FROM sales
			WHERE invno = :invno
			LIMIT 1
		");

		$stmt->bindParam(':invno', $invno, PDO::PARAM_STR);
		$stmt->execute();
		$result = $stmt->fetch(PDO::FETCH_ASSOC);
		$stmt = null;
		$conn = null;
		return $result;
	}

	public static function mdlGetSaleItems($invno){
		try {
			$conn = (new Connection)->connect();
			$stmt = $conn->prepare("
				SELECT 
					si.qty,
					si.ucost,
					si.uprice,
					si.tamount,
					si.prodid,
					p.prodname,
					p.vatdesc
				FROM salesitems AS si
				INNER JOIN products AS p 
					ON si.prodid = p.prodid
				WHERE si.invno = :invno
			");
			$stmt->bindValue(':invno', $invno, PDO::PARAM_STR);
			$stmt->execute();
			return $stmt->fetchAll(PDO::FETCH_ASSOC);
		} catch (PDOException $e) {
			error_log("Database Error: " . $e->getMessage());
			return [];
		} finally {
			// Proper cleanup
			$stmt = null;
			$conn = null;
		}
	}

	static public function mdlGenerateSalesReport($reptype, $branchcode, $start_date, $end_date, $categorycode, $salemode, $status){
		if ($branchcode != ''){
			$branch = " AND (s.branchcode = '$branchcode')";
		}else{
			$branch = "";
		}

		if ($categorycode != ''){
			$category_code = " AND (c.categorycode = '$categorycode')";
		}else{
			$category_code = "";
		}		

		if ($status != ''){
			if ($status == '[ Sold - Return ]'){
			  $sale_status = " AND ((s.status = 'Sold') OR (s.status = 'Return'))";	
			}else{
			  $sale_status = " AND (s.status = '$status')";
			}
		}else{
			$sale_status = "";
		}

		if ($salemode != ''){
		    $sale_mode = " AND (s.salemode = '$salemode')";
		}else{
			$sale_mode = "";
		}		

		if(!empty($end_date)){
			$dates = " AND (s.sdate BETWEEN '$start_date' AND '$end_date')";
		}else{
			$dates = "";
		}					

		$whereClause = "WHERE (s.invno != '')" . $branch . $sale_status . $sale_mode . $dates . $category_code;

		if ($reptype == 1){
			$stmt = (new Connection)->connect()->prepare("SELECT c.catdescription,
																 SUM(si.qty) as total_qty,
																 SUM(si.tamount) as total_amount,
																 SUM(si.ucost * ABS(si.qty)) as total_cost,
																 SUM((si.uprice - si.ucost) * ABS(si.qty)) as total_profit
														   FROM category as c INNER JOIN products as p ON (c.categorycode = p.categorycode)
														                      INNER JOIN salesitems as si ON (p.prodid = si.prodid)
																			  INNER JOIN sales as s ON (s.invno = si.invno)
												                 $whereClause GROUP BY c.catdescription WITH ROLLUP");												 
	    }elseif ($reptype == 2){
			$stmt = (new Connection)->connect()->prepare("SELECT c.catdescription,
																 p.prodname,
																 SUM(si.qty) as total_qty,
																 SUM(si.tamount) as total_amount,
																 SUM(si.ucost * ABS(si.qty)) as total_cost,
																 SUM((si.uprice - si.ucost) * ABS(si.qty)) as total_profit
															FROM category as c INNER JOIN products as p ON (c.categorycode = p.categorycode)
																			   INNER JOIN salesitems AS si ON (p.prodid = si.prodid)
																			   INNER JOIN sales as s ON (s.invno = si.invno)
																  $whereClause GROUP BY c.catdescription,p.prodname WITH ROLLUP");	    	
	    }elseif ($reptype == 3){
			$stmt = (new Connection)->connect()->prepare("SELECT s.id,
																 MAX(s.sdate) AS sdate,
																 MAX(s.invno) AS invno,
																 MAX(s.status) AS status,
																 p.prodname,
																 SUM(si.qty) AS qty,
																 AVG(si.uprice) AS uprice,
																 SUM(si.tamount) AS tamount,
																 SUM(si.ucost * ABS(si.qty)) AS cost,
																 SUM((si.uprice - si.ucost) * ABS(si.qty)) AS profit
															 FROM category AS c INNER JOIN products AS p ON c.categorycode = p.categorycode
																				INNER JOIN salesitems AS si ON p.prodid = si.prodid
																				INNER JOIN sales AS s ON s.invno = si.invno
															       $whereClause GROUP BY s.id, p.prodname WITH ROLLUP");
		}

		$stmt -> execute();
		return $stmt -> fetchAll();
		$stmt -> close();
		$stmt = null;	
	}

	static public function mdlPrintCashierReset($resetcode, $resetdetail, $resetype){
		$pdo = (new Connection())->connect();
		try {
			if ($resetdetail === 'By Product Category') {
				$sql = "SELECT 
						c.catdescription,
						SUM(si.tamount) AS tamount
					FROM category c
					INNER JOIN products p 
						ON c.categorycode = p.categorycode
					INNER JOIN salesitems si 
						ON p.prodid = si.prodid
					INNER JOIN sales s 
						ON si.invno = s.invno
					WHERE 
						s.resetcode = :resetcode
						AND s.status <> 'Void'
						AND s.salemode = :resetype
					GROUP BY c.catdescription
					ORDER BY c.catdescription";
			} else {
				$sql = "";
			}

			$stmt = $pdo->prepare($sql);
			$stmt->bindParam(':resetcode', $resetcode, PDO::PARAM_STR);
			$stmt->bindParam(':resetype', $resetype, PDO::PARAM_STR);
			$stmt->execute();
			return $stmt->fetchAll(PDO::FETCH_ASSOC);
		} catch (PDOException $e) {
			return [
				'error' => true,
				'message' => $e->getMessage()
			];
		} finally {
			$stmt = null;
			$pdo = null;
		}
	}	

	static public function mdlResetPreview($branchcode, $postedby, $reset_detail, $sale_mode){
		if ($reset_detail == 'By Product Category'){			// By Product Category
			$stmt = (new Connection)->connect()->prepare("SELECT c.catdescription,SUM(si.tamount) AS tamount 
							FROM category AS c INNER JOIN products AS p ON (c.categorycode = p.categorycode)
												INNER JOIN salesitems AS si ON (p.prodid = si.prodid)
												INNER JOIN sales AS s ON (si.invno = s.invno)
												WHERE (s.branchcode = '$branchcode') AND
													  (s.postedby = '$postedby') AND
													  (s.resetted = 'F') AND
													  (s.status != 'Void') AND 
													  (s.salemode = '$sale_mode') 
												GROUP BY c.catdescription ORDER BY c.catdescription");
			$stmt -> execute();
			return $stmt -> fetchAll();
			$stmt -> close();
			$stmt = null;									
		}
	}
}


