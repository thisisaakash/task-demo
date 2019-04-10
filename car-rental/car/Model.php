<?php
require_once("Database.php");
class Model extends Database {
	public $table;
	public function __construct($table = null){
		if($table != null){
			$this->table = $table;
		} else {
			$this->table = "models";
		}
	}
	public function addModel($data){
		if($this->ModelExits($this->table, $data['md_reg_number'], $data['md_chachis_number'])){
			return "exist";
		} else {
			return $this->saveModel($this->table, $data);
		}
	}
	public function addModelImage($md_id, $mdi_name){
		return $this->saveModelImage("mdimages", $md_id, $mdi_name);
	}
	public function getModelImages($md_id){
		return $this->getModelImage("mdimages", $md_id);
	}
	public function soldModal($md_id){
		return $this->updateModel($this->table, $md_id);
	}
}
?>