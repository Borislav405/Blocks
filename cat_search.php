<?php
include('config.php');
$searchTerm = $_GET['term'];
  $sql = "SELECT * FROM categories WHERE cat_name LIKE '%".$searchTerm."%'";
  $result = $conn->query($sql);
  if ($result->num_rows > 0) {
    $searchData = array();
    while($row = $result->fetch_assoc()) {
      $data['cat_id']    = $row['cat_id'];
      $data['label'] = $row['cat_name'];
     array_push($searchData, $data);
   }
}
   echo json_encode($searchData);
?>
// Hello
//  wasdwasdwasd wadas