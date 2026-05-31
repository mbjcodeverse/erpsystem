<?php

class Connection{
	public function connect(){
		// $link = new PDO("mysql:host=localhost;dbname=erp", "root", "");

		$link = new PDO("mysql:host=localhost;dbname=u618435671_erp", "u618435671_erp", "ErP_System1!");

		$link -> exec("set names utf8");
		return $link;
	}
}