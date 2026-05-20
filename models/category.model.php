<?php
require_once "connection.php";
class ModelCategory{
	static public function mdlAddCategory($data){
		$db = new Connection();
		$pdo = $db->connect();
        try{
        	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $pdo->beginTransaction();

            $category_id = $pdo->prepare("SELECT CONCAT('', LPAD((count(id)+1),4,'0')) as gen_id FROM category FOR UPDATE");

            $category_id->execute();
		    $category_code = $category_id -> fetchAll(PDO::FETCH_ASSOC);

			$stmt = $pdo->prepare("INSERT INTO category(categorycode, catdescription) VALUES (:categorycode, :catdescription)");

			$stmt->bindParam(":categorycode", $category_code[0]['gen_id'], PDO::PARAM_STR);
			$stmt->bindParam(":catdescription", $data["catdescription"], PDO::PARAM_STR);

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

	static public function mdlEditCategory($data){
		$db = new Connection();
		$pdo = $db->connect();
        try{
        	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $pdo->beginTransaction();

			$stmt = $pdo->prepare("UPDATE category SET categorycode = :categorycode, catdescription = :catdescription WHERE id = :id");

			$stmt->bindParam(":id", $data["id"], PDO::PARAM_INT);
			$stmt->bindParam(":categorycode", $data["categorycode"], PDO::PARAM_STR);
			$stmt->bindParam(":catdescription", $data["catdescription"], PDO::PARAM_STR);

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

	static public function mdlShowCategory($item, $value){
		$stmt = (new Connection)->connect()->prepare("SELECT * FROM category WHERE $item = :$item");
		$stmt -> bindParam(":".$item, $value, PDO::PARAM_STR);
		$stmt -> execute();
		return $stmt -> fetch();
		$stmt -> close();
		$stmt = null;
	}

	static public function mdlShowCategoryList(){
		$stmt = (new Connection)->connect()->prepare("SELECT * FROM category ORDER BY catdescription");
		$stmt -> execute();
		return $stmt -> fetchAll();
		$stmt -> close();
		$stmt = null;
	}
}