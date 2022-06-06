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
$username = $_SESSION['user_name'];
$result = mysqli_query($conn, "SELECT user_id FROM users WHERE user_name = '$username'");
$row = mysqli_fetch_row($result);
if($_SERVER["REQUEST_METHOD"] == "POST"){
    if(!empty(trim($_POST["content"])))
    {
        $sql = "INSERT INTO replies (rep_content, rep_date, reply_thread, reply_by) VALUES ('" . trim($_POST["content"]) . "', NOW()," . $_GET['id'] . "," . $_SESSION['user_id'] . ")";
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
                echo "Record updated successfully";
                echo "<a href='thread.php?id=" . $id . "'>Return to thread</a>";
            } else {
                echo "Error updating record: " . mysqli_error($conn);
            }
        }
    }
    else{
        echo 'Comment box is empty';
        header("location:reply.php?id=" . $id . "");
    }
}
else{
    echo 'This file cannot be called directly.';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blocks</title>
</head>
<body>
</body>
</html>
