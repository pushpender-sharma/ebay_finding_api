<?php
namespace DB;
final class MySQLi {
	private $link;

	public function __construct($hostname, $username, $password, $database, $port = '3306') {
		$this->link = new \mysqli($hostname, $username, $password, $database, $port);

		if ($this->link->connect_error) {
			trigger_error('Error: Could not make a database link (' . $this->link->connect_errno . ') ' . $this->link->connect_error);
			exit();
		}

		$this->link->set_charset("utf8");
		$this->link->query("SET SQL_MODE = ''");
	}

	public function query($sql) {
                
		$query = $this->link->query($sql);

		if (!$this->link->errno) {
			if ($query instanceof \mysqli_result) {
				$data = array();

				while ($row = $query->fetch_assoc()) {
					$data[] = $row;
				}

				$result = new \stdClass();
				$result->num_rows = $query->num_rows;
				$result->row = isset($data[0]) ? $data[0] : array();
				$result->rows = $data;

				$query->close();

				return $result;
			} else {
				return true;
			}
		} else {
			trigger_error('Error: ' . $this->link->error  . '<br />Error No: ' . $this->link->errno . '<br />---' . $sql."---");
		}
	}

	public function escape($value) {
		return $this->link->real_escape_string($value);
	}

	public function countAffected() {
		return $this->link->affected_rows;
	}

	public function getLastId() {
		return $this->link->insert_id;
	}

	public function __destruct() {
		$this->link->close();
	}
       

        
        }
        
        

$servername="127.0.0.1";
$username="root";
$password="gautam_manish_anil_devs_7";
$db_name = "ebay_db";
$class = 'DB\\MySQLi';
$conn = new $class($servername, $username, $password, $db_name);


$user_query=$conn->query("SELECT id FROM products WHERE id = 1");
if($user_query->num_rows==1){
    $user_id=$user_query->row["id"];
}
else{
    $user_id=0;
}
