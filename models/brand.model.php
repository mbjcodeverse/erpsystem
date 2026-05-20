<?php
require_once "connection.php";
class ModelBrand{
	static public function mdlAddBrand($data){
		$db = new Connection();
		$pdo = $db->connect();
        try{
        	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $pdo->beginTransaction();

            $brand_id = $pdo->prepare("SELECT CONCAT('B', LPAD((count(id)+1),3,'0')) as gen_id FROM brand FOR UPDATE");

            $brand_id->execute();
		    $brand_code = $brand_id -> fetchAll(PDO::FETCH_ASSOC);

			$stmt = $pdo->prepare("INSERT INTO brand(brandcode, brandname) VALUES (:brandcode, :brandname)");

			$stmt->bindParam(":brandcode", $brand_code[0]['gen_id'], PDO::PARAM_STR);
			$stmt->bindParam(":brandname", $data["brandname"], PDO::PARAM_STR);

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

	static public function mdlEditBrand($data){
		$db = new Connection();
		$pdo = $db->connect();
        try{
        	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $pdo->beginTransaction();

			$stmt = $pdo->prepare("UPDATE brand SET brandcode = :brandcode, brandname = :brandname WHERE id = :id");

			$stmt->bindParam(":id", $data["id"], PDO::PARAM_INT);
			$stmt->bindParam(":brandcode", $data["brandcode"], PDO::PARAM_STR);
			$stmt->bindParam(":brandname", $data["brandname"], PDO::PARAM_STR);

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

	static public function mdlShowBrand($item, $value){
		$stmt = (new Connection)->connect()->prepare("SELECT * FROM brand WHERE $item = :$item");
		$stmt -> bindParam(":".$item, $value, PDO::PARAM_STR);
		$stmt -> execute();
		return $stmt -> fetch();
		$stmt -> close();
		$stmt = null;
	}

	static public function mdlShowBrandList(){
		$stmt = (new Connection)->connect()->prepare("SELECT * FROM brand ORDER BY brandname");
		$stmt -> execute();
		return $stmt -> fetchAll();
		$stmt -> close();
		$stmt = null;
	}
}