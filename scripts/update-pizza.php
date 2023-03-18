<?php
session_start();
require('connect.php');

$select_categories = "SELECT * FROM `categories`";
$query_sql_categories = $mysqli->query($select_categories);
$query_categories = $mysqli->query($select_categories);

$root = $_SERVER['DOCUMENT_ROOT'] . '/WolfiePizza/';
$get_file = $_FILES["pizzas-photo"]["tmp_name"];
$pizza_id = $_REQUEST['pizza-id'];
$category_id = trim($_REQUEST['category-id']);
$name = trim($_REQUEST['pizza-name']);
$description = trim($_REQUEST['pizza-description']);

if (!($get_file == '')) {
    $now = time();
    $upload_filename = "upload/" . $now . "_" . $_FILES["pizzas-photo"]["name"];
    move_uploaded_file($get_file, $root . $upload_filename);
    $update_sql = <<<HEREDOC
UPDATE `pizza`
SET `category_id`='$category_id',
`name`='$name',
`image`='$upload_filename',
`description`='$description'
WHERE `id` = $pizza_id
HEREDOC;
} else {
    $update_sql = <<<HEREDOC
UPDATE `pizza`
SET `category_id`='$category_id',
`name`='$name',
`description`='$description'
WHERE `id` = $pizza_id
HEREDOC;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-iYQeCzEYFbKjA/T2uDLTpkwGzCiq6soy8tYaI1GyVh/UjpbCx/TYkiZhlZB6+fzT" crossorigin="anonymous">
    <title>WolfiePizza</title>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-info header" aria-label="Eighth navbar example">
        <div class="container">
            <div class="collapse navbar-collapse" id="navbarsExample07">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a href="../index.php"><img src="../images/WolfiePizza_free-file.png" class="company-icon" alt=""></a>
                    </li>
                    <li class="nav-item m25-10">
                        <a class="nav-link active" aria-current="page" href="../index.php">Main</a>
                    </li>
                    <li class="nav-item dropdown m25-10">
                        <a class="nav-link dropdown-toggle" style="color: white;" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Categories
                        </a>
                        <ul class="dropdown-menu">
                            <?php if ($query_sql_categories) {
                                while ($categories = $query_sql_categories->fetch_array()) {
                            ?>
                                    <li><a class="dropdown-item" href="filtrate-pizzas.php?categories_id=<?= $categories['id'] ?>"><?= $categories['name'] ?></a></li>
                            <?php }
                            } ?>
                        </ul>
                    </li>
                    <?php
                    if (empty($_SESSION['login']) and empty($_SESSION['id'])) {
                        echo "
                                    <li class='nav-item m25-10'>
                                        <a class='nav-link active' aria-current='page' href='login.php'>Sign in</a>
                                    </li>
                                    <li class='nav-item m25-10'>
                                        <a class='nav-link active' aria-current='page' href='register.php'>Sign up</a>
                                    </li>
                                ";
                    } else {
                        echo "
                                    <li class='nav-item m25-10'>
                                        <i>
                                            <div class='nav-link active'>" . $_SESSION['login'] . "</div>
                                        </i>
                                    </li>
                                    <li class='nav-item m25-10'>
                                        <a class='nav-link active' aria-current='page' href='sign-out.php'>Sign out</a>
                                    </li>
                                ";
                    }
                    if ($_SESSION['login'] == 'admin') {
                        echo "
                                <li class='nav-item dropdown m25-10'>
                                <a class='nav-link dropdown-toggle' style='color: white;' href='#' role='button' data-bs-toggle='dropdown' aria-expanded='false'>
                                    Admin
                                </a>
                                <ul class='dropdown-menu'>
                                <li><a class='dropdown-item' href='add-pizza.php'>Add Pizza</a></li>
                                <li><a class='dropdown-item' href='add-category.php'>Add Category</a></li>
                                <li><a class='dropdown-item' href='edit-category.php'>Edit Category</a></li>
                                <li><a class='dropdown-item' href='delete-category.php'>Delete Category</a></li>
                                </ul>
                            ";
                    }
                    ?>
                </ul>
                <form role="search" action="search-pizzas.php" method="get">
                    <div class="input-group">
                        <input class="form-control" name="search_text" type="search" placeholder="Search" aria-label="Search">
                        <button type="submit" class="btn btn-warning">Search</button>
                    </div>
                </form>
            </div>
        </div>
    </nav>
    <div class="container">
        <?php
        if($_SESSION['login'] == 'admin') {
            if (!$mysqli->query($update_sql)) { ?>
            <div class='alert alert-danger' role='alert'>Error in updating pizza in the database!</div>
        <?php
            } else {
        ?>
            <div class='alert alert-success' role='alert'>Success on updating pizza!</div>
            <a href='../index.php'><button type='button' class='btn btn-primary'>Main page</button></a>
        <?php
            }
        ?>
        <?php
        } else { ?>
            <div class='alert alert-danger' role='alert'>
                Access denied!
            </div>
        <?php
        } 
        ?>
    </div>
    <nav class="navbar navbar-expand-lg navbar-dark bg-info footer mb-50" aria-label="Eighth navbar example">
        <div class="container">
            <div class="creators">
                <div class="creator">
                    Idea was created by @_no_true_ (Nikita Leontiev)
                </div>
                <div class="creator">
                    Site was created by @wathwolfy (Maxim Nadeev)
                </div>
            </div>
        </div>
    </nav>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-u1OknCvxWvY5kfmNBILK2hRnQC3Pr17a+RTT6rIHI7NnikvbZlHgTPOOmMi466C8" crossorigin="anonymous"></script>
</body>

</html>