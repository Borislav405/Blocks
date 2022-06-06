<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href='sidenav.css'>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blocks</title>
    <style>
    </style>
</head>
<?php
include_once "config.php";
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    $_SESSION["loggedin"] = true;
    $_SESSION["user_id"] = 2;
    $_SESSION["user_name"] = "Anonymous";
} ?>
<body>
        <div class="sidenav">
          <a href="index.php">Home</a>
          <?php
            if($_SESSION['user_id']!=2){
              echo "<a id='pp' href='ppage.php?id=" . $_SESSION["user_id"] . "'><i class='fa-solid fa-image-portrait'></i> " . $_SESSION["user_name"] . "</a>";?>
              <?php if($_SESSION['user_id']!=2){?>
            <button id="btn-0" class="btns" onclick="document.location='login.php'" ><i class="fa-solid fa-arrow-right-from-bracket"></i> Sign Out</button>
          <?php } ?>
            <button id="btn-3" class="btns" onclick="document.location='notifications.php'" ><i class="fa-regular fa-comment-dots"></i></i> Notifications</button>
            <button id="btn-2" class="btns" onclick="document.location='create_thread.php'" ><i class="fa-solid fa-plus"></i> Create Post</button>
            <button id="btn-" class="btns" onclick="document.location='create_cat.php'" ><i class="fa-solid fa-plus"></i> Create Category</button>
        <?php }else{?> <button id="btn-1" class="btns" onclick="document.location='login.php'" ><i class="fa-solid fa-arrow-right-from-bracket"></i> Sign In</button><?php } ?>
          <button id="btn-5" class="btns" onclick="document.location='categories.php'" ><i class="fa-solid fa-cubes"></i> Categories</button>
          <div id="myModal" class="modal">
            <div class="modal-content">
              <div class="modal-header">
                <span class="close">&times;</span>
                <h2>Notifications</h2>
              </div>
              <?php
              $id= $_SESSION['user_id'];
              $sql = "SELECT report_content, report_date FROM reports WHERE report_to = $id ORDER BY report_date";
              $result = $conn->query($sql);
              if ($result->num_rows > 0)
                  {
                  while($row = $result->fetch_assoc())
                      {
                      echo "<div>";
                      echo "<div class='container' id='content-a'>";
                      echo "<div>";
                          echo "<div class='thread'>";
                              echo "<div class='content'>";
                                  echo "<div class='title_del'>";
                                      echo "<div class='title'>" . $row["report_content"] . "</a>";
                                      echo "</div>";
                                      echo "<p id='time'> Sent on: " . $row['report_date'] . "</p>";
                                      echo "</div>";
                                  echo "</div>";
                              echo "</div>";
                          echo "</div>";
                      echo "</div>";
                      echo "</div>";
                      echo "</div>";
                      }
                  }
                  ?>
            </div>
          </div>
          </div>
</body>
<script>
var modal = document.getElementById("myModal");
var btn = document.getElementById("btn-3");
var span = document.getElementsByClassName("close")[0];
btn.onclick = function() {
  modal.style.display = "block";
}
span.onclick = function() {
  modal.style.display = "none";
}
window.onclick = function(event) {
  if (event.target == modal) {
    modal.style.display = "none";
  }
}
</script>
</html>
