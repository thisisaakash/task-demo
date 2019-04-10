<?php
class Database {
	private $servername = "localhost";
	private $database = "cars";
	private $username = "root";
	private $password = "";
	private $conn = "";
	private $stmt = "";
	public function __construct(){
		$this->conn = new mysqli($this->servername, $this->username, $this->password, $this->database);
	}
	private function getConnection(){
		return new mysqli($this->servername, $this->username, $this->password, $this->database);
	}
	protected function manufacturerExits($table, $data){
		$this->conn = $this->getConnection();
		$this->stmt = $this->conn->prepare("SELECT * FROM `".$table."` WHERE `m_name` = ?;");
		$this->stmt->bind_param('s',$data);
		$this->stmt->execute();
		if($this->stmt->get_result()->num_rows > 0){
			return true;
		} else {
			return false;
		}
	}
	protected function saveManufacturer($table, $data){
		$this->conn = $this->getConnection();
		$this->stmt = $this->conn->prepare("INSERT INTO `".$table."` (`m_name`) VALUES (?);");
		$this->stmt->bind_param('s',$data);
		$this->stmt->execute();
		return $this->conn->insert_id;
	}
	protected function getManufacturersList($table){
		$this->conn = $this->getConnection();
		$this->stmt = $this->conn->prepare("SELECT * FROM ".$table." WHERE m_status = 'active'");
		$this->stmt->execute();
		return $this->stmt->get_result()->fetch_all(MYSQLI_ASSOC);
	}
	protected function modelExits($table, $md_reg_number, $md_chachis_number){
		$this->conn = $this->getConnection();
		$this->stmt = $this->conn->prepare("SELECT * FROM `".$table."` WHERE `md_reg_number` = ? OR `md_chachis_number` = ?;");
		$this->stmt->bind_param('ss',$md_reg_number,$md_chachis_number);
		$this->stmt->execute();
		if($this->stmt->get_result()->num_rows > 0){
			return true;
		} else {
			return false;
		}
	}
	protected function saveModel($table, $data){
		$this->conn = $this->getConnection();
		$this->stmt = $this->conn->prepare("INSERT INTO `".$table."`(`md_name`, `m_id`, `md_color`, `md_mfg_year`, `md_reg_number`, `md_chachis_number`, `md_notes`) VALUES (?, ?, ?, ?, ?, ?, ?);");
		$this->stmt->bind_param('sssssss',$data['md_name'],$data['m_id'],$data['md_color'],$data['md_mfg_year'],$data['md_reg_number'],$data['md_chachis_number'],$data['md_notes']);
		$this->stmt->execute();
		return $this->conn->insert_id;
	}
	protected function saveModelImage($table, $md_id, $mdi_name){
		$this->conn = $this->getConnection();
		$this->stmt = $this->conn->prepare("INSERT INTO `".$table."` (`md_id`, `mdi_name`) VALUES (?,?);");
		$this->stmt->bind_param('ss', $md_id, $mdi_name);
		$this->stmt->execute();
		return $this->conn->insert_id;
	}
	protected function getInventoryList(){
		$this->conn = $this->getConnection();
		$this->stmt = $this->conn->prepare("SELECT *, COUNT(*) FROM manufacturer as m, models as md WHERE m_status = 'active' AND md_status = 'active' AND m.m_id = md.m_id GROUP BY md.m_id HAVING COUNT(*) > 0");
		$this->stmt->execute();
		return $this->stmt->get_result()->fetch_all(MYSQLI_ASSOC);
	}
	protected function getInventoryDetail($m_id){
		$this->conn = $this->getConnection();
		$this->stmt = $this->conn->prepare("SELECT * FROM manufacturer as m, models as md WHERE m_status = 'active' AND md_status = 'active' AND m.m_id = md.m_id AND md.m_id = ".$m_id.";");
		$this->stmt->execute();
		return $this->stmt->get_result()->fetch_all(MYSQLI_ASSOC);
	}
	protected function getModelImage($table, $md_id){
		$this->conn = $this->getConnection();
		$this->stmt = $this->conn->prepare("SELECT * FROM ".$table." WHERE md_id = ".$md_id." AND mdi_status = 'active';");
		$this->stmt->execute();
		return $this->stmt->get_result()->fetch_all(MYSQLI_ASSOC);
	}
	protected function updateModel($table, $md_id){
		$this->conn = $this->getConnection();
		$this->stmt = $this->conn->prepare("UPDATE ".$table." SET md_status = 'sold' WHERE md_id = ".$md_id.";");
		$this->stmt->execute();
		return $this->stmt->affected_rows;
	}
}
?>