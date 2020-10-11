<?php
    session_start(); 
    if (isset($_SESSION['token'])) :
        header("Location: index.php");
        exit;
    else : 
        include_once($_SERVER['DOCUMENT_ROOT'].'/controller/users/user_handler.php');
        include_once($_SERVER['DOCUMENT_ROOT'].'/view/common/header.php');
    ?>

<div class="container text-center">
    <img src="https://via.placeholder.com/150/28a745/FFFFFF?text=BP" alt="">
    <h1 class="mx-auto">
        Continue using as a guest...
    </h1>
    <h3>or</h3>
    <div id='signIn'>
        <!-- <a href="/auth_handler.php">login</a> -->
        <!-- <a href="/auth_handler.php?login"><img src="img/google_login_button.png" /></a> -->
        <a href="../auth_handler.php?login">
            <img class="img-fluid" src="/asset/img/google_login_button.png" alt="Logiin With Google">
        </a>
    </div>
</div>

<?php  
    include_once($_SERVER['DOCUMENT_ROOT'].'/view/common/footer.php');
    endif;
?>