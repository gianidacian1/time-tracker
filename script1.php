<?php


$servername = "localhost";
$username = "root";
$password = "";
$dbname = "time2";

try {
  $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $data = $argv;
  
  $select = "SELECT t.name, t.status, tt.start_date, tt.end_date, SEC_TO_TIME(t.total_time) as elapsed_time  FROM tasks_time tt left join tasks t on tt.task_id =t.id
                    WHERE DATE(tt.start_date)='$data[1]'
                    AND DATE(tt.start_date)='$data[2]'
                    AND t.name = '$data[3]'
                    ";

  // $select = $select . "AND tt.start_date=" . $data[2];

  // $select = $select . "AND t.name=" . $data[3];

  $stmt = $conn->prepare($select);
  $stmt->execute();
 
 
  // set the resulting array to associative
  $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
  $tasks = $stmt->fetchAll();
  var_dump($tasks);exit;

} catch(PDOException $e) {
  echo "Error: " . $e->getMessage();
}
$conn = null;
var_dump($select, $tasks);
?>