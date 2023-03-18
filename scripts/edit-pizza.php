<?php
session_start();
require('connect.php');
$select_categories = "SELECT * FROM `categories`";
$query_sql_categories = $mysqli->query($select_categories);
$query_categories = $mysqli->query($select_categories);
$pizza_id = $_REQUEST['id'];
$pizza_category_id = $_REQUEST['category_id'];
$select_pizza = <<<HEREDOC
    SELECT
    categories.id as 'id_category',
    categories.name as 'category_name',
    `image`,
    `description`,
    pizza.id as `id`,
    pizza.name as `name`
    FROM `pizza`, `categories`
    WHERE pizza.id = $pizza_id and categories.id = $pizza_category_id
HEREDOC;
$query_select_pizza = $mysqli->query($select_pizza);
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
        <?php if($_SESSION['login'] == 'admin') { ?>
        <h1 class='h1-style'>Edit Pizza</h1>
        <?php if ($query_select_pizza) {
                $pizzas = $query_select_pizza->fetch_array();
        ?>
        <form action="update-pizza.php?pizza-id=<?=$pizzas['id']?>" class='add-pizza-form' method="post" enctype='multipart/form-data'>
            <div class="mb-3">
                <label for="pizza-category" class="form-label">Pizza category</label>
                <select class="form-select" aria-label="Default select example" name='category-id'>
                    <?php
                    if ($query_categories) {
                        while ($select_cats = $query_categories->fetch_array()) {
                            if($select_cats['id'] == $pizzas['id_category']) { ?>
                                <option value="<?=$pizzas['id_category']?>" selected><?=$pizzas['category_name']?></option>
                            <?php
                            } else {
                            ?>
                                <option value="<?=$select_cats['id']?>"><?=$select_cats['name']?></option>
                            <?php 
                            }
                        }
                    }
                    ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="pizza-name" class="form-label">Pizza name</label>
                <input type="text" class="form-control" value='<?= $pizzas['name'] ?>' name='pizza-name' required placeholder="Enter pizza name">
            </div>
            <div class="mb-3">
                <label for="pizza-description" class="form-label">Pizza description</label>
                <textarea class="form-control" name='pizza-description' placeholder="Enter description" rows="3"><?= $pizzas['description'] ?></textarea>
            </div>
            <div class="mb-3">
                <label for="pizzas-photo" class="form-label">Choose pizza's photo</label>
                <input type="file" class="form-control" name='pizzas-photo'>
            </div>
            <?php }
            ?>
            <button type="submit" class="btn btn-primary col-1">Send</button>
        </form>
        <?php } else { ?>
            <div class='alert alert-danger' role='alert'>
                Access denied!
            </div>
        <?php } ?>
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