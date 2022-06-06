<?php
session_start();
include_once "config.php";
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    $_SESSION["loggedin"] = true;
    $_SESSION["user_id"] = 2;
    $_SESSION["user_name"] = "Anonymous";
}
if(!isset($_GET["id"])){
  header("Location:index.php?id=top");
  die();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="pagination.css">
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blocks</title>
    <script src="https://code.jquery.com/jquery-3.6.0.js"></script>
    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script src="https://kit.fontawesome.com/22f442312a.js" crossorigin="anonymous"></script>
    <style> <?php include 'sidenav.css';include 'sidenav.php'; ?> </style>
</head>
<body>
<?php
    if (!isset ($_GET['page']) ) {
        $page = 1;
    } else {
        $page = $_GET['page'];
    }
$results_per_page = 10;
$page_first_result = ($page-1) * $results_per_page;
function get_paging_info($tot_rows,$pp,$curr_page)
{
    $pages = ceil($tot_rows / $pp);
    $data = array();
    $data['pages']     = $pages;
    $data['curr_page'] = $curr_page;
    return $data;
}
    $query = "SELECT * from threads";
    $result = mysqli_query($conn, $query);
    $count = mysqli_num_rows($result);
    $paging_info = get_paging_info($count,10,$page);
    $max = 4;
    if($page < $max)
        $sp = 1;
    elseif($paging_info['curr_page'] >= ($paging_info['pages'] - floor($max / 2)) )
        $sp = $paging_info['pages'] - $max + 1;
    elseif($paging_info['curr_page'] >= $max)
        $sp = $paging_info['curr_page']  - floor($max/2);
        ?>
<div class="topnav">
  <form class="container_nav" action="search_page.php?id=thread">
<input type="text" required minlength="4" name="search" id="search" placeholder="Search.." class="form-control">
<button type="submit" name="submit"><i class="fa-solid fa-magnifying-glass"></i></button></li>
</form>
<script>
$( function() {
    $( "#search" ).autocomplete({
    source: 'thread_search.php'
    });
});
$(document).ready(function() {
    $("#search").autocomplete({
        source: 'thread_search.php',
        select: function( event, ui ) {
            window.location.href = ui.item.value;
        }
    });
});
</script>
<div class="sort">
    <div class="button"><a id="new" href="index.php?id=new">new</a></div>
    <div class="button"><a id="top" href="index.php?id=top">top</a></div>
</div>
<div class="page_title">
  Posts
</div>
</div>
<?php
if ($_GET["id"] == 'new'){
    echo "<script>";
    echo "var element = document.getElementById('new');";
    echo "element.classList.add('active');";
    echo "</script>";
    $sql = "SELECT t1.thread_id, t1.thread_title, t1.rep_count, t1.thread_date, t1.thread_by, t1.thread_cat, t2.user_name,c.cat_id, c.cat_name, c.cat_by FROM threads AS t1 LEFT JOIN users AS t2 ON t1.thread_by = t2.user_id LEFT JOIN categories AS c ON t1.thread_cat = c.cat_id ORDER BY thread_date DESC LIMIT " . $page_first_result . ',' . $results_per_page;
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
                            echo "<p id='time'> Posted on: " . $row['thread_date'] . "</p>";
                            echo " by ";
                            echo "<a id='creator' href='ppage.php?id=" . $row['thread_by'] . "'>" . $row["user_name"] . "<a>";
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
elseif($_GET["id"] == 'top'){
    echo "<script>";
    echo "var element = document.getElementById('top');";
    echo "element.classList.add('active');";
    echo "</script>";
    $sql = "SELECT t1.thread_id, t1.thread_title, t1.rep_count, t1.thread_date, t1.thread_by, t1.thread_cat, t2.user_name,c.cat_id, c.cat_name, c.cat_by FROM threads AS t1 LEFT JOIN users AS t2 ON t1.thread_by = t2.user_id LEFT JOIN categories AS c ON t1.thread_cat = c.cat_id ORDER BY rep_count DESC LIMIT " . $page_first_result . ',' . $results_per_page;
    $result = $conn->query($sql);
    if ($result->num_rows > 0)
    {
    while($row = $result->fetch_assoc())
    {
        ?>
        <div class="flr-inner">
        <div class="container" id="content-a">
    <?php
    echo "<div>";
    echo "<div class='container' id='content-a'>";
    echo "<div>";
        echo "<div class='thread'>";
            echo "<div class='content'>";
                echo "<div class='title_del'>";
                    echo "<div class='title'><a href='category.php?id=" . $row['cat_id'] . "'> " . $row["cat_name"] . "</a> / <a href='thread.php?id=" . $row['thread_id'] . "'>" . $row["thread_title"] . "</a>";
                    echo "</div>";
                    echo "<div class='stats'><p id='time'>Replies: " . $row['rep_count'] . " /</p>";
                    echo "<p id='time'> Posted on: " . $row['thread_date'] . "</p>";
                    echo " by ";
                    echo "<a id='creator' href='ppage.php?id=" . $row['thread_by'] . "'>" . $row["user_name"] . "<a>";
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
?>
</body>
</html>
<div class=pagination>
<?php if($page != 1){ ?>
    <a class='pagination' href='index.php?id=<?php echo $_GET["id"]; ?>&page=<?php echo ($paging_info['curr_page'] - 1); ?>' title='Page <?php echo ($paging_info['curr_page'] - 1); ?>'>Prev</a>
<?php }
if($paging_info['curr_page'] >= $max) : ?>
    <a class='pagination' href='index.php?id=<?php echo $_GET["id"]; ?>&page=1' title='Page 1'>1</a>
    <span>..</span>
<?php endif;
for($i = $sp; $i <= ($sp + $max -1);$i++) : ?>
    <?php
        if($i > $paging_info['pages'])
            continue;
            if($paging_info['curr_page'] == $i) : ?>
            <span class='bold'><?php echo $i; ?></span>
    <?php   else : ?>
            <a class='pagination' href='index.php?id=<?php echo $_GET["id"]; ?>&page=<?php echo $i; ?>' title='Page <?php echo $i; ?>'><?php echo $i; ?></a>
    <?php endif;
endfor; ?>
<?php if($paging_info['curr_page'] < ($paging_info['pages'] - floor($max / 2))) : ?>
    <span>..</span>
<a class='pagination' href='index.php?id=<?php echo $_GET["id"]; ?>&page=<?php echo ($paging_info['pages']); ?>' title='Page <?php echo ($paging_info['pages']); ?>'><?php echo $paging_info['pages']; ?></a>
<?php endif; ?>
<?php if($paging_info['curr_page'] < $paging_info['pages']) : ?>
    <a class='pagination' href='index.php?id=<?php echo $_GET["id"]; ?>&page=<?php echo ($paging_info['curr_page'] + 1); ?>' title='Page <?php echo ($paging_info['curr_page'] + 1); ?>'>Next</a>
    <?php endif; ?>
</div>
