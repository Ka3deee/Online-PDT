<?php
require('connect_mysql.php');

function dd($value)
{
     echo "<pre>", print_r($value, true), "</pre>";
     die();
}

function executeQuery($sql, $data) {
    global $conn;

    $stmt = $conn->prepare($sql);
    $values = array_values($data);
    $types = str_repeat('s', count($values));
    $stmt->bind_param($types, ...$values);
    $stmt->execute();
    return $stmt;
}

function create($table, $data) {
    global $conn;

    //$sql = "INSERT INTO $table SET username=?, email=?, password=?;"
    $sql = "INSERT INTO $table SET ";

    //CONDITION OF THE INSERT QUERY OR CONTINUATION OF THE SQL ABOVE
    $i = 0;
    foreach ($data as $key => $value) {
         if ($i === 0) {
              $sql = $sql . " $key=?";
         } else {
              $sql = $sql . ", $key=?";
         }
         $i++;
    }

    $stmt = executeQuery($sql, $data);
    $id = $stmt->insert_id;
    return $id;
}

function selectOne($table, $conditions)
{
     global $conn;
     
     //$sql = "SELECT * FROM $table WHERE username=?, email=?;"
     $sql = "SELECT * FROM $table";

     // CONDITION OF THE SELECT ONE QUERY OR CONTINUATION OF THE SQL ABOVE
     $i = 0;
     foreach ($conditions as $key => $value) {
          if ($i === 0) {
               $sql = $sql . " WHERE $key=?";
          } else {
               $sql = $sql . " AND $key=?";
          }
          $i++;
     }

     $sql = $sql . " LIMIT 1"; //TO GET ONLY ONE RECORD 
     $stmt = executeQuery($sql, $conditions);
     $records = $stmt->get_result()->fetch_assoc();
     return $records;
}

function update($table, $id, $data) {
     global $conn;
     $sql = "UPDATE $table SET ";
 
     $i = 0;
     foreach ($data as $key => $value) {
          if ($i === 0) {
               $sql = $sql . " $key=?";
          } else {
               $sql = $sql . ", $key=?";
          }
          $i++;
     }
 
     $sql = $sql . " WHERE id=?";
     $data['id'] = $id;
     $stmt = executeQuery($sql, $data);
     return $stmt->affected_rows;
} 

function delete($table, $id) {
     global $conn;
     $sql = "DELETE FROM $table WHERE id=?";
 
     $stmt = executeQuery($sql, ['id' => $id]);
     return $stmt->affected_rows;
 }

function selectAll($table, $conditions = [])
{
    global $conn;
    
    //$sql = "SELECT * FROM $table WHERE username=?, email=?;"
    $sql = "SELECT * FROM $table";
    if (empty($conditions)) {
         $stmt = $conn->prepare($sql);
         $stmt->execute();
         $records = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
         return $records;
    } else { 
         $i = 0;
         foreach ($conditions as $key => $value) {
              if ($i === 0) {
                   $sql = $sql . " WHERE $key=?";
              } else {
                   $sql = $sql . " AND $key=?";
              }
              $i++;
         }

         $stmt = executeQuery($sql, $conditions);
         $records = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
         return $records;
    }
}

?>
