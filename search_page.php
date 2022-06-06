</script>
<?php
session_start();
include_once "config.php";
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    session_start();
    $_SESSION["loggedin"] = true;
    $_SESSION["user_id"] = 2;
    $_SESSION["user_name"] = "Anonymous";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="pagination.css">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blocks</title>
    <script src="https://code.jquery.com/jquery-3.6.0.js"> </script>
    <script src="https://kit.fontawesome.com/22f442312a.js" crossorigin="anonymous"></script>
    <style>
        <?php include 'sidenav.css';
            include 'sidenav.php';?>
    </style>
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
  <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
  <script src="frontend-script.js"></script>
</head>
<body>
<?php
	$query = $_GET["search"];
		$query = htmlspecialchars($query);
        $query = "SELECT * FROM threads AS t1 LEFT JOIN users AS t2 ON t1.thread_by = t2.user_id
        WHERE (`thread_title` LIKE '%".$query."%') OR (`thread_content` LIKE '%".$query."%')";
		$result = $conn->query($query);
		if ($result->num_rows > 0) {
			while($row = $result->fetch_assoc()){
                echo "<div class='flr-inner'>";
                echo "<div class='container' id='content-a'>";
                echo "<div>";
                    echo "<div class='thread'>";
                        echo "<div class='content'>";
                            echo "<div class='title_del'>";
                                echo "<div class='title'><a href='thread.php?id=" . $row['thread_id'] . "'>" . $row["thread_title"] . "</a>";
                                echo "</div>";
                                echo "<div class='stats'><p id='time'>Replies on: " . $row['rep_count'] . "</p>";
                                echo "</div>";
                                echo "<div class='stats'><p id='time'>Posted on: " . $row['thread_date'] . "</p>";
                                echo " by ";
                                echo "<a href='ppage.php?id=" . $row['thread_by'] . "'>" . $row["user_name"] . "<a>";
                                if($_SESSION['user_id']==$row['thread_by'])
                                {
                                    echo "<div class='del_btn'>";
                                    echo '<a name="del_thread" href="del_thread.php?id='. $row['thread_id'] .'" title="Delete Record" data-toggle="tooltip"><i class="fa-solid fa-trash-can"></i></a></div>';
                                }
                                echo "</div>";
                            echo "</div>";
                        echo "</div>";
                    echo "</div>";
                echo "</div>";
                echo "</div>";
                echo "</div>";
			}
		}
    $query = $_GET["search"];
  		$query = htmlspecialchars($query);
    $query = "SELECT * FROM categories AS t1 LEFT JOIN users AS t2 ON t1.cat_by = t2.user_id
    WHERE (`cat_name` LIKE '%".$query."%') OR (`cat_description` LIKE '%".$query."%')";
$result = $conn->query($query);
if ($result->num_rows > 0) {
  while($row = $result->fetch_assoc()){
    echo "<div>";
        echo "<div class='thread'>";
            echo "<div class='content'>";
               echo "<div class='title'><a href='category.php?id=" . $row['cat_id'] . "'>" . $row["cat_name"] . "</a>";
               echo "</div>";
               echo "<div class='stats'><p id='time'>Posted on:  </p>";
               echo "by ";
               echo "<a id='creator' href='ppage.php?id=" . $row['cat_by'] . "'>" . $row["user_name"] . "<a></div>";
           echo "</div>";
       echo "</div>";
   echo "</div>";
  }
}
?>
</body>
</html>
