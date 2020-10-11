<?php
    header('Location: ' . filter_var($_SERVER['HTTP_HOST'], FILTER_SANITIZE_URL));
?>