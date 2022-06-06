<?php
session_start();
include_once "config.php";
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
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
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blocks</title>
    <script src="https://kit.fontawesome.com/22f442312a.js" crossorigin="anonymous"></script>
    <style>
        <?php
            include 'sidenav.css';
            include 'sidenav.php';
        ?>
    </style>
</head>
<body>
    <?php
        $id = $_GET['id'];
        $sql = "SELECT * FROM users WHERE user_id = '$id'";
        $result = $conn->query($sql);
        if ($result->num_rows > 0)
        {
            while($row = $result->fetch_assoc())
            {
                echo "<div class='profile_stats'>";
                    echo "<div class='user_info'><div class='user'><div class='info' id='user_name'>" . $row['user_name'] . "</div>";
                    echo "<div class='info' id='user_id'>#" . $row['user_id'] . "</div></div>";
                    echo "<div class='info' id='date'>Created at: " . $row['user_date'] . "</div>";
                echo "</div></div>";
            }
        }
    ?>
<div class="topnav">
  <div class='sort'>
    <?php $id = $_GET['id']; echo "<div class='button'><a id='posts' href='ppage.php?id=" . $id . "'>Posts</a></div>"; ?>
    <?php echo "<div class='button'><a id='reps' href='ppage.php?id=" . $id . "&tag=replies'>Replies</a></div>";?>
  </div>
</div>
            <?php
            if((empty($_GET['tag'])))
            {
              echo "<script>";
              echo "var element = document.getElementById('posts');";
              echo "element.classList.add('active');";
              echo "</script>";
                $id = $_GET['id'];
                $sql = "SELECT t1.thread_id, t1.thread_title, t1.rep_count, t1.thread_date, t1.thread_by, t1.thread_cat, t2.user_name,c.cat_id, c.cat_name, c.cat_by FROM threads AS t1 LEFT JOIN users AS t2 ON t1.thread_by = t2.user_id  LEFT JOIN categories AS c ON t1.thread_cat = c.cat_id WHERE t1.thread_by = '$id' ORDER BY thread_date DESC";
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
                                    echo "<div class='title'><a href='category.php?id=" . $row['cat_id'] . "'> " . $row["cat_name"] . "</a> / <a href='thread.php?id=" . $row['thread_id'] . "'>" . $row["thread_title"] . "</a>";
                                    echo "</div>";
                                    echo "<div class='stats'><p id='time'>Replies: " . $row['rep_count'] . " /</p>";
                                    echo "<p id='time'> Posted on: " . $row['thread_date'] . " </p>";
                                    if($_SESSION['user_id']==$row['thread_by'] || $_SESSION['user_id'] == $row['cat_by'])
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
            }
            else{
              echo "<script>";
              echo "var element = document.getElementById('reps');";
              echo "element.classList.add('active');";
              echo "</script>";
                $sql = "SELECT r.rep_id, r.rep_content, r.rep_date, r.reply_thread, t.thread_title, t.thread_id FROM replies AS r LEFT JOIN threads AS t ON r.reply_thread = t.thread_id WHERE reply_by = '$id'";
                $result = $conn->query($sql);
                if ($result->num_rows > 0)
                {
                    while($row = $result->fetch_assoc())
                    {
                        echo "<div>";
                        echo "<div class='thread'>";
                            echo "<div class='content'>";
                            echo "<div class='title'><a href='thread.php?id=" . $row['reply_thread'] . "'>" . $row["thread_title"] . "</a>";
                            echo "</div>";
                            echo "<p>" . $row['rep_content'] . "</p>";
                            echo "<div class='stats'>";
                            echo "<p id='time'> Posted on: " . $row['rep_date'] . " </p>";
                            echo "</div>";
                    echo "</div>";

                    }
                }
            }
    ?>
</body>
</html>
