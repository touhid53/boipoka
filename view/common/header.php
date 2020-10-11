<?php 
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
        $_SESSION['user_photo']= "/asset/img/login.png";
    }elseif(!isset($_SESSION['user_photo'])){
        $_SESSION['user_photo']= "/asset/img/login.png";
    }
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="description" content="BoiPoka, A site for book lovers...">
    <meta name="keywords" content="Book Review, Book Rating, Reading Traking">
    <meta name="viewport" content="width=device-width, initial-scale=1.0 shrink-to-fit=no" >
    <link rel="stylesheet" href="/asset/css/style.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script> -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
</head>

<body>
    <nav class="navbar navbar-expand-md navbar-dark bg-dark" style="margin-bottom: 2%;">
        <div class="container">
        <a class="navbar-brand" href="/index">BoiPoka</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#ourNavigation" aria-controls="ourNavigation" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="ourNavigation">
            <ul class="navbar-nav mr-auto mt-2 mt-lg-0">
                <li class="nav-item">
                    <a class="nav-link" href="/index">Home <span class="sr-only">(current)</span></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/profile">Timeline<span class="sr-only">(current)</span></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link disabled" href="#">Browse Books</a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <img src=<?=$_SESSION['user_photo'];?> alt="Login" width="25" class="rounded-circle d-inline-block align-top">
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                        
                        <?php
                            if(isset($_SESSION['logged_in']) && $_SESSION['logged_in']) :
                        ?>
                            <a class="dropdown-item" href="/view/setting">Setting</a>
                            <!-- <a class="dropdown-item" href="#">Another action</a> -->
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="/auth_handler?logout">Logout</a>
                        <?php
                            else : ?>
                                <a class="dropdown-item" href="/view/login">Login</a>
                        <?php
                            endif;
                        ?>
                    </div>
                </li>
            </ul>
            <form class="form-inline my-2 my-lg-0" autocomplete="off"
                 method="get" action="/view/search">
                    <input class="form-control mr-sm-2" type="text" 
                        id="search_string" name="search_string" 
                        placeholder="Book Name / Author" required>
                    <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
            </form>
        </div>
    </div>
    </nav>