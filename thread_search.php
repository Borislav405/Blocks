<?php
include('config.php');
$searchTerm = $_GET['term'];
  $sql = "SELECT * FROM threads WHERE thread_title LIKE '%".$searchTerm."%'";
  $result = $conn->query($sql);
  if ($result->num_rows > 0) {
    $searchData = array();
    while($row = $result->fetch_assoc()) {
      $data['thread_id']    = $row['thread_id'];
      $data['label'] = $row['thread_title'];
      $data['value'] = "thread.php?id=" . $row['thread_id'] . "";
     array_push($searchData, $data);
  }
  }
   echo json_encode($searchData);
?>
