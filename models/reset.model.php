<?php
require_once "connection.php";
class ModelReset{
	static public function mdlGetResetCount($reset_prefix){
		$stmt = (new Connection)->connect()->prepare("SELECT resetcode FROM reset WHERE (SUBSTRING(resetcode,1,9) = '$reset_prefix')");

		$stmt -> execute();
		return $stmt -> fetchAll();
		$stmt -> close();
		$stmt = null;
	}

    static public function mdlPostReset($branchcode, $resetcode, $resetby, $resetype){
		$db = new Connection();
		$pdo = $db->connect();
        try{
        	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $pdo->beginTransaction();

            $stmt = $pdo->prepare("INSERT INTO reset(branchcode, resetcode, resetby, resetype) VALUES (:branchcode, :resetcode, :resetby, :resetype)");

            $stmt->bindParam(":branchcode", $branchcode, PDO::PARAM_STR);
            $stmt->bindParam(":resetcode", $resetcode, PDO::PARAM_STR);
			$stmt->bindParam(":resetby", $resetby, PDO::PARAM_STR);
			$stmt->bindParam(":resetype", $resetype, PDO::PARAM_STR);
			$stmt->execute();
         
            $resetted = 'T';
			$updatesale = $pdo->prepare("UPDATE sales SET resetted = :resetted, resetcode = :resetcode WHERE (branchcode = :branchcode) AND (postedby = :postedby) AND (resetted = 'F')");

            $updatesale->bindParam(":resetted", $resetted, PDO::PARAM_STR);
            $updatesale->bindParam(":branchcode", $branchcode, PDO::PARAM_STR);
            $updatesale->bindParam(":resetcode", $resetcode, PDO::PARAM_STR);
			$updatesale->bindParam(":postedby", $resetby, PDO::PARAM_STR);
			$updatesale->execute();
			
		    $pdo->commit();
		    return "ok";
		}catch (Exception $e){
			$pdo->rollBack();
		}	
		$pdo = null;	
		$stmt = null;
	}
}