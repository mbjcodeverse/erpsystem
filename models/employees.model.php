<?php
require_once "connection.php";
class ModelEmployees{
	static public function mdlAddEmployee($data){
		$db = new Connection();
		$pdo = $db->connect();
        try{
        	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $pdo->beginTransaction();

            $emp_id = $pdo->prepare("SELECT CONCAT('EM', LPAD((count(id)+1),5,'0')) as gen_id  FROM employees FOR UPDATE");

            $emp_id->execute();
		    $empid = $emp_id -> fetchAll(PDO::FETCH_ASSOC);

			$empcode = $empid[0]['gen_id'];

			if ($data["bday"] == ''){
				$bday = null;
			}else{
				$bday = $data["bday"];
			}

			$stmt = $pdo->prepare("INSERT INTO employees(empid, isactive, lname, fname, mi, bday, gender, address, mobile, positioncode, sssno, phino, pagibig, tin, estatus) VALUES (:empid, :isactive, :lname, :fname, :mi, :bday, :gender, :address, :mobile, :positioncode, :sssno, :phino, :pagibig, :tin, :estatus)");

			$stmt->bindParam(":empid", $empid[0]['gen_id'], PDO::PARAM_STR);
			$stmt->bindParam(":isactive", $data["isactive"], PDO::PARAM_INT);
			$stmt->bindParam(":lname", $data["lname"], PDO::PARAM_STR);
			$stmt->bindParam(":fname", $data["fname"], PDO::PARAM_STR);
			$stmt->bindParam(":mi", $data["mi"], PDO::PARAM_STR);
			$stmt->bindParam(":bday", $bday, PDO::PARAM_STR);
			$stmt->bindParam(":gender", $data["gender"], PDO::PARAM_STR);
			$stmt->bindParam(":address", $data["address"], PDO::PARAM_STR);
			$stmt->bindParam(":mobile", $data["mobile"], PDO::PARAM_STR);
			$stmt->bindParam(":positioncode", $data["positioncode"], PDO::PARAM_STR);
			$stmt->bindParam(":sssno", $data["sssno"], PDO::PARAM_STR);
			$stmt->bindParam(":phino", $data["phino"], PDO::PARAM_STR);
			$stmt->bindParam(":pagibig", $data["pagibig"], PDO::PARAM_STR);
			$stmt->bindParam(":tin", $data["tin"], PDO::PARAM_STR);
			$stmt->bindParam(":estatus", $data["estatus"], PDO::PARAM_STR);

			$stmt->execute();
		    $pdo->commit();
		    return $empcode;
		}catch (Exception $e){
			$pdo->rollBack();
			return "error";
		}	
		$pdo = null;	
		$stmt = null;
	}

	static public function mdlEditEmployee($data){
		$db = new Connection();
		$pdo = $db->connect();
        try{
        	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $pdo->beginTransaction();

			if ($data["bday"] == ''){
				$bday = null;
			}else{
				$bday = $data["bday"];
			}

			$stmt = $pdo->prepare("UPDATE employees SET empid = :empid, isactive = :isactive, lname = :lname, fname = :fname, mi = :mi, bday = :bday, gender = :gender, address = :address, mobile = :mobile, positioncode = :positioncode, sssno = :sssno, phino = :phino, pagibig = :pagibig, tin = :tin, estatus = :estatus WHERE empid = :empid");

			$stmt->bindParam(":empid", $data["empid"], PDO::PARAM_STR);
			$stmt->bindParam(":isactive", $data["isactive"], PDO::PARAM_INT);
			$stmt->bindParam(":lname", $data["lname"], PDO::PARAM_STR);
			$stmt->bindParam(":fname", $data["fname"], PDO::PARAM_STR);
			$stmt->bindParam(":mi", $data["mi"], PDO::PARAM_STR);
			$stmt->bindParam(":bday", $bday, PDO::PARAM_STR);
			$stmt->bindParam(":gender", $data["gender"], PDO::PARAM_STR);
			$stmt->bindParam(":address", $data["address"], PDO::PARAM_STR);
			$stmt->bindParam(":mobile", $data["mobile"], PDO::PARAM_STR);
			$stmt->bindParam(":positioncode", $data["positioncode"], PDO::PARAM_STR);
			$stmt->bindParam(":sssno", $data["sssno"], PDO::PARAM_STR);
			$stmt->bindParam(":phino", $data["phino"], PDO::PARAM_STR);
			$stmt->bindParam(":pagibig", $data["pagibig"], PDO::PARAM_STR);
			$stmt->bindParam(":tin", $data["tin"], PDO::PARAM_STR);
			$stmt->bindParam(":estatus", $data["estatus"], PDO::PARAM_STR);

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

	static public function mdlEmployeeList(){
        $stmt = (new Connection)->connect()->prepare("SELECT e.empid,e.isactive,e.lname,e.fname,e.mi,
															e.bday,e.gender,e.address,e.mobile,p.positiondesc,
															e.sssno,e.phino,e.pagibig,e.tin,e.estatus
														FROM employees e INNER JOIN position p ON (e.positioncode = p.positioncode)");    
        $stmt -> execute();
		return $stmt -> fetchAll();
		$stmt -> close();
		$stmt = null;                                                 
    }

	public static function mdlEmployeeInfo($empid){
		$sql = "SELECT e.empid,e.isactive,e.lname,e.fname,e.mi,
					   e.bday,e.gender,e.address,e.mobile,p.positiondesc,p.positioncode,
					   e.sssno,e.phino,e.pagibig,e.tin,e.estatus
		        FROM employees e INNER JOIN position p ON (e.positioncode = p.positioncode)
				WHERE empid = :empid LIMIT 1";
		$conn = (new Connection)->connect();
		$stmt = $conn->prepare($sql);
		$stmt->bindParam(':empid', $empid, PDO::PARAM_STR);
		$stmt->execute();
		$result = $stmt->fetch(PDO::FETCH_ASSOC);
		$stmt->closeCursor();
		$stmt = null;
		return $result ?: null;
	}

	static public function mdlShowPosition(){
		$stmt = (new Connection)->connect()->prepare("SELECT * FROM position ORDER BY positiondesc");
		$stmt -> execute();
		return $stmt -> fetchAll();
		$stmt -> close();
		$stmt = null;	
	}
}