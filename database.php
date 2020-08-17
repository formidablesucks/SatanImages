<?php
class database {
	private
		$connection,

		$sql,
		$single = true,
		$prepare = [];

	public
		$row_num = 0;

	// Connect to database
		public function __construct(array $credentials) {
			try {
	 			$this->connection = new PDO("mysql:host={$credentials['host']};dbname={$credentials['database']}", $credentials['username'], $credentials['password'], [
					PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4",
					PDO::ATTR_PERSISTENT => true,
					PDO::ATTR_ERRMODE => true,
					PDO::ERRMODE_EXCEPTION => true
				]);
			}
			catch (PDOException $e) {
				error_log("Database failiure: {$e}");
			}
		}
	// Build statement
		public function sql(string $sql) {
			$this->sql = $sql;

			return $this;
		}
		public function single(bool $single = true) {
			$this->single = $single;

			return $this;
		}
		public function prepare(array $prepare = []) {
			$this->prepare = $prepare;

			return $this;
		}
	// Execute statement
		public function get() {
			try {
				$query = $this->connection->prepare($this->sql);
				$query->execute($this->prepare);

				$this->row_num = $query->rowCount();

				if     (!$this->row_num) return [];
				elseif ($this->single)	 return $query->fetch(PDO::FETCH_ASSOC);
				else				   	 return $query->fetchAll(PDO::FETCH_ASSOC);
			}
			catch (PDOException $e) {
				error_log("Database failiure: {$e}");
			}
		}
		public function set() {
			try {
				$query = $this->connection->prepare($this->sql);
				$query->execute($this->prepare);
				
			    $this->row_num = $query->rowCount();				
				
				if ($this->row_num > 0) return true;
				else					return false;
			}
			catch (PDOException $e) {
				error_log("Database failiure: {$e}");
			}
		}
}
