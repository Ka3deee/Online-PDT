<?php 
    class database{
        public $que;
        private $result=array();
        private $local_servername = "localhost";
        private $local_username = "smrapp123";
        private $local_password = "123";
        private $local_dbname = "porcv_db";
        private $conn = '';
    
        public function __construct(){
            try {
                $this->mysqli ="mysql:host=".$this->local_servername.";dbname=".$this->local_dbname;
                $this->conn = new PDO($this->mysqli,$this->local_username, $this->local_password);
                $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch(PDOException $e) {
                echo "Connection failed: " . $e->getMessage();
                }
        }

        public function insert($table,$para=array()){
            $table_columns = implode(',', array_keys($para));
            $table_value = implode("','", $para);

            $sql="INSERT INTO $table($table_columns) VALUES('$table_value')";

            $result = $this->conn->query($sql);
        }

        public function update($table,$para=array(),$id){
            $args = array();

            foreach ($para as $key => $value) {
                $args[] = "$key = '$value'"; 
            }

            $sql="UPDATE  $table SET " . implode(',', $args);

            $sql .=" WHERE $id";

            $result = $this->conn->query($sql);
        }

        public function delete($table,$id){
            $sql="DELETE FROM $table";
            $sql .=" WHERE $id ";
            $sql;
            $result = $this->conn->query($sql);
        }

        public $sql;

        public function select($table,$rows="*",$where = null){
            if ($where != null) {
                $sql="SELECT $rows FROM $table WHERE $where";
            }else{
                $sql="SELECT $rows FROM $table";
            }
            $this->sql = $result = $this->conn->query($sql);
        }

        public function __destruct(){
            $this->conn = null;
        }
    }
?>