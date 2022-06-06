<?php
session_start();
include_once "config.php";

if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
}
else{
if(trim($_SESSION["user_id"]) == 2){
    header("location: index.php?id=new");
    exit;
    }
}
if(!empty($_POST["id"])){
    if(!empty(trim($_POST["content"])))
{
    $results = mysqli_query($conn, "SELECT t.thread_id, t.thread_by, u.user_id FROM threads AS t LEFT JOIN users AS u ON t.thread_by = u.user_id");
    if ($results->num_rows > 0)
    {
    while($rows = $results->fetch_assoc()){

    $sql = "INSERT INTO reports (report_content, report_date, report_to) VALUES ('" . trim($_POST["content"]) . "', NOW()," . $rows['thread_by'] . ")";
        $result = mysqli_query($conn, $sql);
        if(!$result)
        {
            echo 'Your reply has not been saved, please try again later.';
        }
        else
        {
            $id=$_GET['id'];
            $sql="UPDATE threads SET rep_count = rep_count + 1  WHERE thread_id = '$id'";
            if (mysqli_query($conn, $sql)) {
                require_once "config.php";
                $sql = "DELETE FROM threads WHERE thread_id = ?";

            if($stmt = mysqli_prepare($conn, $sql)){
                mysqli_stmt_bind_param($stmt, "i", $param_id);

                $param_id = trim($_POST["id"]);

                if(mysqli_stmt_execute($stmt)){
                    header("location: index.php?id=new");
                    exit();
                } else{
                    echo "Oops! Something went wrong. Please try again later.";
                }
            }
            mysqli_stmt_close($stmt);

            mysqli_close($link);
                echo "Record updated successfully";
                echo "<a href='thread.php?id=" . $id . "'>Return to thread</a>";
            } else {
                echo "Error updating record: " . mysqli_error($conn);
            }
        }
}
} else{
    if(empty($_GET["id"])){
        header("location: error.php");
        exit();
        }
      }
  }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Blocks</title>
    <style>
        .wrapper{
            width: 600px;
            margin: 0 auto;
        }
    </style>
</head>
<body>
    <?php
$id=$_GET["id"];
$sql = "SELECT t1.thread_by, t1.thread_cat, c.cat_id, c.cat_by FROM threads AS t1 LEFT JOIN categories AS c ON t1.thread_cat = c.cat_id WHERE thread_id = '$id'";
$result = mysqli_query($conn, $sql);
if ($result->num_rows > 0) {
  while($row = $result->fetch_assoc())
  {
    if($_SESSION["user_id"] == $row['thread_by']){
        ?>
        <div class="wrapper">
        <div class="">
            <div class="">
                <div class="">
                    <h2 class="">Delete Record</h2>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <div class="">
                            <input type="hidden" name="id" value="<?php echo trim($_GET["id"]); ?>"/>
                            <p>Are you sure you want to delete this thread?</p>
                            <p>
                                <input type="submit" value="Yes" class="">
                                <a href="index.php?id=new" class="">No</a>
                            </p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <?php
    }
    else if($_SESSION["user_id"] == $row['cat_by']){
        ?>
        <div class="wrapper">
        <div class="">
            <div class="">
                <div class="">
                    <h2 class="">Delete Record</h2>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <div class="">
                            <input type="hidden" name="id" value="<?php echo trim($_GET["id"]); ?>"/>
                            <p>Are you sure you want to delete this thread?</p>
                            <form action='reply.php?id=" . $id . "' method='post'>
                            <textarea name="content" placeholder="comment" id="text" cols="30" rows="10"></textarea>
                            <button onclick="document.getElementById('text').value = ''">Clear</button>
                            <input type="submit" value="Yes" class="">
                            <a href="index.php" class="">No</a>
                            </p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
        <?php
    }
    else{
        echo "Sorry you cannot delete this";
        header("location: error.php");
        exit();
    }
  }
}
    ?>
</body>
</html>
