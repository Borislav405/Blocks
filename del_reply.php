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
$id=$_GET['id'];
$sql = "SELECT reply_by FROM replies WHERE rep_id = '$id'";
$result = mysqli_query($conn, $sql);
if ($result->num_rows > 0) {
  while($row = $result->fetch_assoc())
  {
    if($_SESSION["user_id"] == $row['reply_by']){
    }
    else{
        echo "Sorry you cannot delete this";
        header("location: error.php");
        exit();
    }
  }
}
if(!empty($_POST["id"]))
{
    $sql = "SELECT reply_thread FROM replies WHERE rep_id = $id";
    $result= $conn->query($sql);
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $param_id = trim($_POST["id"]);
            $sql = "DELETE FROM replies WHERE rep_id = '$param_id'";
            if ($conn->query($sql) === TRUE) {
                $sql = "UPDATE threads SET rep_count = rep_count-1  WHERE thread_id = '" . $row['reply_thread']." ';";
                if (mysqli_query($conn, $sql)) {
                    echo "Record updated successfully";
                    echo "<a href='thread.php?id=" . $row['reply_thread'] . "'>Return to thread</a>";
                } else {
                    echo "Error updating record: " . mysqli_error($conn);
                }
            }
            else
            {
                echo "Error updating record: " . $conn->error;
            }
        }
    }
}
else
{
    if(empty($_GET["id"])){
        header("location: error.php");
        exit();
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
<div class="wrapper">
        <div class="container-fluid">
            <div class="">
                <div class="">
                    <h2 class="">Delete Record</h2>
                    <?php         $id = $_GET["id"];
                     echo "<form action='del_reply.php?id=" . $id . "' method='post'>" ?>
                        <div class="">
                            <input type="hidden" name="id" value="<?php echo trim($_GET["id"]); ?>"/>
                            <p>Are you sure you want to delete this reply?</p>
                            <p>
                                <input type="submit" value="Yes" class="">
                                <a href="index.php?id=new" class="btn btn-secondary">No</a>
                            </p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
