</script>
<?php session_start();
include_once "config.php";
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    $_SESSION["loggedin"] = true;
    $_SESSION["user_id"] = 2;
    $_SESSION["user_name"] = "Anonymous";} ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="pagination.css">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Title</title>
    <script src="https://code.jquery.com/jquery-3.6.0.js"> </script>
    <script src="https://kit.fontawesome.com/22f442312a.js" crossorigin="anonymous"></script>
    <style>
        <?php include 'sidenav.css';
            include 'sidenav.php';?>
    </style>
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
  <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
</head>
<body>
<!-- Searchbar -->
    <div class="topnav">
<form class="container_nav" action="search_page.php?id=category">
<input type="text" required minlength="4" name="search" id="search" placeholder="Search.." class="form-control">
<button type="submit" name="submit"><i class="fa-solid fa-magnifying-glass"></i></button></li>
</form>
<script>
$( function() {
    $( "#search" ).autocomplete({
    source: 'cat_search.php'
    });
});
</script>
<div class="page_title">
  Categories
</div>
</div>
<!-- New_Content //////////////////////-->
<?php
    $sql = "SELECT t1.cat_id, t1.cat_name, t1.cat_description , t1.cat_by, t2.user_name FROM categories AS t1 LEFT JOIN users AS t2 ON t1.cat_by = t2.user_id ORDER BY cat_id";
    $result = $conn->query($sql);
    if ($result->num_rows > 0)
    {
        while($row = $result->fetch_assoc())
        {
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
