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
$id = $title = $content = $time = $category =  "";
$id_err = $title_err = $content_err = $time = $category_err = "";
$user_id = $_SESSION['user_id'];
if($_SERVER["REQUEST_METHOD"] == "POST"){
    $category = trim($_POST['category']);
    $result2 = mysqli_query($conn, "SELECT cat_id FROM categories WHERE cat_name = '$category'");
    $row2 = mysqli_fetch_row($result2);
    $input_title = trim($_POST["title"]);
    if(empty($input_title)){
    } else{
        $title = $input_title;
    }
    $input_content = trim($_POST["content"]);
    if(empty($input_content)){
        $content_err = "Please enter content.";
    } else{
        $content = $input_content;
    }
    $input_category = trim($_POST["category"]);
    if(empty($input_category)){
        $category_err = "Please enter category.";
    } else{
        $input_category = trim($_POST["category"]);
        $sql = "SELECT cat_name FROM categories WHERE cat_name='$input_category'";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {$content = $input_content;}
        else { $category_err = "the category doesn't exists"; }
    }
    if(empty($title_err) && empty($content_err) && empty($category_err)){
        $sql = "INSERT INTO threads (thread_title, thread_content, thread_cat, thread_by, thread_date) VALUES (?, ?, ?, ?, NOW())";
        if($stmt = mysqli_prepare($conn, $sql)){
            mysqli_stmt_bind_param($stmt, "ssss", $param_title, $param_content, $param_cat, $param_user);
            $param_title = $title;
            $param_content = $content;
            $param_user = $user_id;
            $param_cat = $row2[0];
            if(mysqli_stmt_execute($stmt)){
                header("location: index.php?id=new");
                exit();
            } else{
                echo "Oops! Something went wrong. Please try again later.";
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
    <meta charset="UTF-8">
        <link rel="stylesheet" href="style.css">
        <link rel="stylesheet" href="form.css">
      <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blocks</title>
    <link rel="stylesheet" href="style.css">
    <script src="https://code.jquery.com/jquery-3.6.0.js"></script>
    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script src="https://kit.fontawesome.com/22f442312a.js" crossorigin="anonymous"></script>
    <script src="https://kit.fontawesome.com/22f442312a.js" crossorigin="anonymous"></script>
    <style>
        <?php include 'sidenav.css';
            include 'sidenav.php'; ?>
    </style>
</head>
<body>
    <div class="form">
    <form class="form_form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" autocomplete="off">
    <div class="container">
        <input type="text" placeholder="Type title here" required maxlength="255" name="title">
            <input type="text" required minlength="4" name="category" id="search" placeholder="Category.." class="form-control">
        <span><?php echo $content_err;?></span>
        <textarea name="content" placeholder="Content" <?php echo (!empty($content_err)) ? 'is-invalid' : ''; ?> value="<?php echo $content; ?>"></textarea>
        <button type="submit">Submit</button>
        <button type="button" class="cancelbtn" onclick="location.href='index.php?id=new';">Cancel</button>
    </form>
    </div>
</body>
</html>
<script>
$( function() {
    $("#search").autocomplete({
    source: 'cat_search.php'
    });
});
</script>
