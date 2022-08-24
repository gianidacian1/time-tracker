<?php


$servername = "localhost";
$username = "root";
$password = "";
$dbname = "time2";

try {
  $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $stmt = $conn->prepare("SELECT name, status,
                          (SELECT start_date FROM tasks_time WHERE task_id = tasks.id ORDER BY tasks_time.id ASC LIMIT 1) as start_date,
                          (SELECT end_date FROM tasks_time WHERE task_id = tasks.id ORDER BY tasks_time.id DESC LIMIT 1) as end_date,
                          SEC_TO_TIME(total_time) as elapsed FROM tasks"
                        );
  $stmt->execute();

  // set the resulting array to associative
  $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
  $tasks = $stmt->fetchAll();
} catch(PDOException $e) {
  echo "Error: " . $e->getMessage();
}
$conn = null;
print_r($tasks);
?>