<?php 
	require 'Database.php';

	class Model extends Database {
		protected $db;

		public function __construct() {
			$this->db = new Database;
		}

		public function readRooms() {
			$query = "SELECT * FROM rooms WHERE room_status =1 ";
			$this->db->prepare($query);
			return $this->db->fetchAll() ?? false;
		}

		public function readAsignaciones() {
			$query = "SELECT * FROM asignaciones WHERE asignacion_status =1 ";
			$this->db->prepare($query);
			return $this->db->fetchAll() ?? false;
		}

		public function readDeskOcupados() {
			$query = "SELECT * FROM desks WHERE desk_status =0 ";
			$this->db->prepare($query);
			return $this->db->fetchAll() ?? false;
		}

		public function readTiempoDesk($desk_num) {
			$query = "SELECT desk_time, desk_paused FROM desks WHERE desk_num = $desk_num AND desk_status =0 ";
			$this->db->prepare($query);
			return $this->db->fetch() ?? false;
		}

		public function readDeskByNum($desk_num) {
			$query = "SELECT * FROM desks WHERE desk_num = '$desk_num' AND desk_status =0 ";
			$this->db->prepare($query);
			return $this->db->fetch() ?? false;
		}

		public function saveTareasCompletadas($puesto,$asignacion,$cantidad) {
			$query = "INSERT INTO tareas_completadas (puesto,asignacion,cantidad) VALUES ('$puesto','$asignacion','$cantidad')";
			$this->db->prepare($query);
			return $this->db->execute() ?? false;
		}

		public function createDesk($room_id,$desk_num,$desk_time) {
			$query = "INSERT INTO desks (room_id,desk_num,desk_time,desk_status) VALUES ('$room_id','$desk_num','$desk_time', 0)";
			$this->db->prepare($query);
			return $this->db->execute() ?? false;
		}


		public function updateDesk($room_id,$desk_num,$desk_time) {
			$query = "UPDATE desks SET desk_time = '$desk_time' WHERE desk_num = '$desk_num'";
			$this->db->prepare($query);
			return $this->db->execute() ?? false;
		}

		public function updatePauseDesk($desk_num,$desk_paused) {
			$query = "UPDATE desks SET desk_paused = '$desk_paused' WHERE desk_num = '$desk_num'";
			$this->db->prepare($query);
			return $this->db->execute() ?? false;
		}


		public function deleteDesk($desk_num) {
			$query = "DELETE FROM desks WHERE desk_num = '$desk_num'";
			$this->db->prepare($query);
			return $this->db->execute() ?? false;
		}



	}
