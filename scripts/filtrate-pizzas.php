<?php
session_start();
require('connect.php');
$cats_id = $_REQUEST['categories_id'];
$select_pizzas = "SELECT * FROM `pizza` WHERE `category_id` = " . $cats_id;
$select_categories = "SELECT * FROM `categories`";
$select_cats = "SELECT * FROM `categories` WHERE `id` = $cats_id";
$select_query_cats = $mysqli->query($select_cats);
$query_sql_pizzas = $mysqli->query($select_pizzas);
$query_sql_categories = $mysqli->query($select_categories);

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
                                    <!-- <li>
                                        <hr class="dropdown-divider">
                                    </li> -->
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
        <h1 id="all-pizzas">
            <?php if ($select_query_cats) {
                $cats = $select_query_cats->fetch_array();
                echo $cats['name'];
            }
            ?>
        </h1>
    </div>
    <div class="container">
        <div class="pizzas">
            <?php if ($query_sql_pizzas) {
                while ($pizzas = $query_sql_pizzas->fetch_array()) {
            ?>
                    <div class="card" style="width: 18rem;">
                        <img src="../<?= $pizzas['image'] ?>" class="card-img-top card-image" alt="...">
                        <div class="card-body">
                            <h5 class="card-title"><?= $pizzas['name'] ?></h5>
                            <?php
                            $select_category_pizza = "SELECT * FROM `categories` WHERE categories.id =" . $pizzas['category_id'];
                            $query_sql_category_pizza = $mysqli->query($select_category_pizza);
                            if ($query_sql_category_pizza) {
                                $category = $query_sql_category_pizza->fetch_array();
                            ?>
                                <p class="card-text"><?= $category['name'] ?></p>
                            <?php }
                            ?>
                            <a href="show-pizza.php?id=<?= $pizzas['id'] ?>" class="btn btn-primary" style="margin-bottom: 5px">More</a>
                            <?php if ($_SESSION['login'] == 'admin') { ?>
                                <a href="edit-pizza.php?id=<?= $pizzas['id'] ?>&category_id=<?= $pizzas['category_id'] ?>" class="btn btn-primary" style="margin-bottom: 5px">Edit Pizza</a>
                                <a href="delete-pizza.php?id=<?= $pizzas['id'] ?>" class="btn btn-primary">Delete Pizza</a>
                            <?php } ?>
                        </div>
                    </div>
            <?php }
            } ?>
        </div>
    </div>

    <nav class="navbar navbar-expand-lg navbar-dark bg-info footer" aria-label="Eighth navbar example">
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