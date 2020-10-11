<?php
  session_start();
    // -------------change checking session token
  if (!isset($_SESSION['token'])) {
    //adjust navbar link 
    header('Location: auth_handler.php?login');
    exit; 
  }else{
    include_once($_SERVER['DOCUMENT_ROOT'].'/controller/users/user_handler.php');
    include_once($_SERVER['DOCUMENT_ROOT'].'/view/common/header.php');

    $user = users::get_user($_SESSION['gid']);
?>


    <div class="container text-center">
      <h2 class="text-muted">Browse Via Book Category or Author</h2>
    </div>


<?php
    include_once($_SERVER['DOCUMENT_ROOT'].'/view/common/footer.php');
  }
?>