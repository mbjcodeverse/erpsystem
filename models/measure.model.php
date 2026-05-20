<?php
require_once "connection.php";

class MeasureModel{
	static public function mdlShowAllMeasure(){
		$stmt = (new Connection)->connect()->prepare("SELECT * FROM measure ORDER BY mdesc");
		$stmt -> execute();
		return $stmt -> fetchAll();
		$stmt -> close();
		$stmt = null;
	}
}