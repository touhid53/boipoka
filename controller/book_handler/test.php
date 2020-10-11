<?php
    include_once($_SERVER['DOCUMENT_ROOT'].'/controller/book_handler/book.php');
    session_start();
    echo "<pre>";
    // print_r( book::getBookDetail("9844590019"));
    print_r($_SESSION);
    echo "</pre>";

?>