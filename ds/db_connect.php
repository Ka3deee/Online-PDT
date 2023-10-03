<?php
//DATABASE CLASS FOR DIRECT RECEIVING, DIRECT LINE INSPECTION AND REPORT Exception

//AUTHOR: Ely Blanquera III
//Version: 2.0.0
class DatabaseClass
{
    
	// Database Configuration
    private $mysql_uname      = "ds";
    private $mysql_pass       = "ds";
    private $mysql_dbase      = "sr_db";

    function __construct( $ip)
    {
        try {
            $this->mysqlconn = new PDO("mysql:host=".$ip.";dbname=".$this->mysql_dbase,$this->mysql_uname, $this->mysql_pass);
            // set the PDO error mode to exception
            $this->mysqlconn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            //echo "Connected!";
            
          } catch(PDOException $e) {
            echo "Connection failed: " . $e->getMessage();
          }

    }

    // =============== Direct Receiving Functions ================= //
	public function get_ar_new(){
		//get max ar_ref
		$sql = "SELECT max(ar_ref) as 'ar_ref' FROM ar_sum_tbl where ar_m_ref='DIRECT'"; 					
        $result = $this->mysqlconn->query($sql);
        if($result->rowCount() > 0){
            $row1 = $result->fetch(PDO::FETCH_ASSOC);
            return ((int)$row1['ar_ref']) + 1;  
        }      
	}
    public function get_query($sql){				
        return  $this->mysqlconn->query($sql);   
	}

    public function _insert_data($string){
        $Effected_rows = 0;	
		try {
            
            $this->mysqlconn->beginTransaction();	        
            $Effected_rows = $this->mysqlconn->exec($string);
            $this->mysqlconn->commit();
            return $Effected_rows;
              
        }catch(Exception $exception){
            $this->mysqlconn->rollBack();      
            return $Effected_rows;
        }      
	}
    public function _update_data($string){
        $Effected_rows = 0;	
		try {
            
            $this->mysqlconn->beginTransaction();	        
            $Effected_rows = $this->mysqlconn->exec($string);
            $this->mysqlconn->commit();
            return $Effected_rows;
              
        }catch(Exception $exception){
            $this->mysqlconn->rollBack();      
            return $Effected_rows;
        }      
	}
    public function getdata($sql)
    {				
        $result = $this->mysqlconn->query($sql);
        if($result->rowCount() > 0){
           $Mysql_result =  $result->fetchAll(\PDO::FETCH_ASSOC);
           return  $Mysql_result;
        }else{
            return "No-Data";
        }         
    }

}

//$obj = new DatabaseClass();
//print_r($obj->get_ar_new());