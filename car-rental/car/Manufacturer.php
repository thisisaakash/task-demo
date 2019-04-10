<?php
require_once("Database.php");
class Manufacturer extends Database {
	public $table = "manufacturer";
	public function __construct($table = null){
		if($table != null){
			$this->table = $table;
		} else {
			$this->table = "manufacturer";
		}
	}
	public function addManufacturer($data){
		if($this->manufacturerExits($this->table, $data)){
			return "exist";
		} else {
			return $this->saveManufacturer($this->table, $data);
		}
	}
	public function getManufacturers(){
		return $this->getManufacturersList($this->table);
	}
	public function getInventory(){
		return $this->getInventoryList();
	}
	public function getInventoryDetails($m_id){
		return $this->getInventoryDetail($m_id);
	}
}
?>