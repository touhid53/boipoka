<?php
    session_start();

    include_once($_SERVER['DOCUMENT_ROOT'].'/controller/users/user_handler.php');
    
    //  session_destroy();

    // echo "<pre>";
    // print_r(users::get_user('6'));
    // print_r($_SESSION);
    // echo "</pre>";

    // echo users::isUserAvailable($_SESSION['gid']);
?>