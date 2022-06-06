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
    <link rel="stylesheet" href="form.css">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://kit.fontawesome.com/22f442312a.js" crossorigin="anonymous"></script>
    <title>Blocks</title>
    <style>
        <?php
            include 'sidenav.css';
            include 'sidenav.php';
        ?>
    </style>
</head>
<body>
<?php
        $id = $_GET["id"];
        $sql = "SELECT thread_title, thread_content FROM threads WHERE thread_id = '$id'";
        $result = mysqli_query($conn, $sql);
        if ($result->num_rows > 0)
        {
            while($row = $result->fetch_assoc())
            {
                echo "<div class='content'>";
                    echo "<div class='thread_title'>" . $row['thread_title'] . "</div>";
                    echo "<br>";
                    echo "<div class='thread_content'>" . $row['thread_content'] . "</div>";
                echo "</div>";
                $sql2 = "SELECT t1.rep_id, t1.rep_content, t1.rep_date, t1.reply_by, t2.user_name FROM replies AS t1 LEFT JOIN users AS t2 ON t1.reply_by = t2.user_id WHERE reply_thread = $id ORDER BY rep_id";
                $result2 = mysqli_query($conn, $sql2);
                if ($result2->num_rows > 0)
                {
                    while($row2 = $result2->fetch_assoc())
                    {
                        echo "<div>";
                        echo "<div class='thread'>";
                            echo "<div class='content'>";
                                echo "<div class='title'>" . $row2["rep_content"] . "</a>";
                                echo "</div>";
                                echo "<div class='stats'><p id='time'>Posted on: " . $row2['rep_date'] . "</p>";
                                echo " by ";
                                echo "<a href='ppage.php?id=" . $row2['reply_by'] . "'>" . $row2["user_name"] . "<a></div>";
                                echo "</div>";
                            echo "</div>";
                        echo "</div>";
                    if($_SESSION['user_id']==$row2['reply_by'])
                        {
                            echo "<div id='del_btn'>";
                            echo "<a href='del_reply.php?id=" . $row2['rep_id'] . "'><span class='fa fa-trash'></span></a>";
                            echo "</div>";
                        }
                    }
                }
            }
        }
    else {
        echo "0 results";
      }
        echo "<br>";
        echo "<form action='reply.php?id=" . $id . "' method='post'>" ?>
        <textarea name="content" placeholder="comment" id="text" cols="30" rows="10"></textarea>

        <button type="submit" value="Submit">Submit</button>
        <button type="button" class="cancelbtn" onclick="document.getElementById('text').value = ''">Clear</button>
    </form>
</body>
</html>
