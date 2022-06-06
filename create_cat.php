<?php
session_start();
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
}
else{
if(trim($_SESSION["user_id"]) == 2){
    header("location: index.php?id=new?id=new");
    exit;
    }
}
require_once "config.php";
$id = $title = $content =  "";
$id_err = $category_err = $content_err = "";
$id = $_SESSION['user_id'];
$username = $_SESSION['user_name'];
if($_SERVER["REQUEST_METHOD"] == "POST"){
    $input_title = trim($_POST["cat_name"]);
    if(empty($input_title)){
        $category_err = "Please enter a title.";
    } else{
      $sql = "SELECT cat_name FROM categories WHERE cat_name='$input_title'";
      $result = $conn->query($sql);
      if ($result->num_rows > 0)
      {$category_err = "the category already exists";}
      else
      {$title = $input_title;}
    }
    $input_content = $_POST["cat_description"];
    if(empty($input_content)){
        $content_err = "Please enter content.";
    } else{
        $content = $input_content;
    }
    if(empty($category_err) && empty($content_err)){
        $sql = "INSERT INTO categories (cat_name, cat_description, cat_by) VALUES (?, ?, ?)";

        if($stmt = mysqli_prepare($conn, $sql)){
            mysqli_stmt_bind_param($stmt, "sss", $param_title, $param_content, $id);
            $param_title = $title;
            $param_content = $content;
            $id;
            if(mysqli_stmt_execute($stmt)){
                header("location: index.php?id=new");
                die();
            } else{
                echo "Oops! Something went wrong. Please try again later.";
                die();
            }
        }
        mysqli_stmt_close($stmt);
    }
    mysqli_close($conn);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <link rel="stylesheet" href="style.css">
  <link rel="stylesheet" href="form.css">
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://code.jquery.com/jquery-3.6.0.js"> </script>
    <script src="https://kit.fontawesome.com/22f442312a.js" crossorigin="anonymous"></script>
    <title>Blocks</title>
    <style>
        <?php include 'sidenav.css';
            include 'sidenav.php'; ?>
    </style>
</head>
<body>
    <form class="form_form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" autocomplete="off">
    <div class="container">
        <input type="text" name="cat_name" required placeholder="Category name" class="form-control <?php echo (!empty($category_err)) ? 'is-invalid' : ''; ?>" maxlength="255" placeholder="Type title here">
            <textarea name="cat_description" required maxlength="255" name="title" placeholder="Category description" value="<?php echo $content; ?>"></textarea>
        <span><?php echo $content_err;?></span>
        <button type="submit" value="Add category">Submit</button>
        <button type="button" class="cancelbtn" onclick="location.href='index.php?id=new';">Cancel</button>
    </form>
</body>
</html>
