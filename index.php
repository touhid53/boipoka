<?php
    session_start();
    include_once($_SERVER['DOCUMENT_ROOT'].'/view/common/header.php');
    // include header - navbar

    include_once($_SERVER['DOCUMENT_ROOT'].'/controller/users/user_handler.php');
    include_once($_SERVER['DOCUMENT_ROOT'].'/controller/book_handler/book.php');

    $some_user = users::getLatestUser();
    $some_books = book::getMostReadBooks();
?>
<title>BoiPoka</title>
<section id="topx ">
    <div class="container">
        <div class="row ">
            <div class="col-lg-8">
                <div class="carouselWrapper">
                    <div class="myCarousel">
                        <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
                            <ol class="carousel-indicators">
                                <li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
                                <li data-target="#carouselExampleIndicators" data-slide-to="1"></li>
                                <li data-target="#carouselExampleIndicators" data-slide-to="2"></li>
                            </ol>
                            <div class="carousel-inner">
                                <div class="carousel-item active">
                                    <img class="d-block w-100" src="/asset/img/boipoka.png" alt="...">
                                    <div class="carousel-caption d-none d-md-block">
                                        <h5>BoiPoka.org</h5>
                                        <p>Your Own Platform for Book lovers like You...</p>
                                    </div>
                                </div>
                                <div class="carousel-item">
                                    <img class="d-block w-100" src="/asset/img/child-reading.jpg" alt="...">
                                    <div class="carousel-caption d-none d-md-block">
                                        <h5>Books are your best friends</h5>
                                        <p>Read & get to know more about them...</p>
                                    </div>
                                </div>
                                <div class="carousel-item">
                                    <img class="d-block w-100" src="/asset/img/library2.png" alt="...">
                                    <div class="carousel-caption d-none d-md-block">
                                        <h5>Keep Track...</h5>
                                        <p>Log your reading diary here....</p>
                                    </div>
                                </div>
                            </div>
                            <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button"
                                data-slide="prev">
                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                <span class="sr-only">Previous</span>
                            </a>
                            <a class="carousel-control-next" href="#carouselExampleIndicators" role="button"
                                data-slide="next">
                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                <span class="sr-only">Next</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="introPad">

                    <div class="introWrapper">
                        <div class="intro">
                            <h1 class="text-center">
                                Hello, BoiPokas...
                            </h1>

                        </div>

                        <div class="introAction">
                            <!-- check if user is logged in -->
                            <?php if (isset($_SESSION['token'])) : ?>
                            <p class="text-center">You're ready to-</p>
                            <h2 class="text-center">Keep Reading..</h2>
                            <br>
                            <h2 class="text-center">Keep track</h2>
                            <br>
                            <h2 class="text-center">Build Wish List</h2>
                            <?php else : ?>
                            <div id='signIn'>
                                <p class="text-center">To</p>
                                <h2 class="text-center">Keep Reading..</h2>
                                <br>
                                <h2 class="text-center">Keep track &</h2>
                                <br>
                                <h2 class="text-center">Build Wish List</h2>
                                <a href="auth_handler.php?login">
                                    <img src="/asset/img/google_login_button.png" alt="Logiin With Google"
                                        class="img-fluid">
                                </a>
                            </div>
                            <?php endif; ?>
                            <!-- end of checking -->
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</section>
<hr>
<div class="belowList m-3">
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <div class="recentUser m-3">
                    <h3 class="text-center">Recent users: </h3>
                    <table class="table table-hover ">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Name</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php
                            /** 
                             * if table is not empty,
                             * show  table
                             * else show notthing message
                             */
                            if ($some_user != 0){
                                foreach($some_user as $key=>$value): 
                            ?>
                            <tr>
                                <th scope="row"><?=$key+1;?></th>
                                <td><a href="profile?id=<?=$value['id'];?>">
                                        <?=$value['name'];?>
                                    </a></td>
                            </tr>

                            <?php 
                                endforeach; 
                                
                                }else {
                                    echo "<td colspan=3><h3>Nothing in list!</h3></td>";
                                } 
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="col-md-6">
                <div class="mostReadBooks m-3">
                    <h3 class="text-center">Most read Books: </h3>
                    <table class="table table-hover ">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Name</th>
                            </tr>
                        </thead>

                        <tbody>

                            <?php
                            /** 
                             * if table is not empty,
                             * show  table
                             * else show notthing message
                             */
                            if ($some_books != 0){
                                foreach($some_books as $key=>$value): 
                            ?>
                            <tr>
                                <th scope="row"><?=$key+1;?></th>
                                <td>
                                    <a href="/view/bookPreview?isbn=<?=$value['isbn'];?>">
                                        <?=$value['book_name'];?>
                                    </a>
                                </td>
                            </tr>

                            <?php 
                                endforeach; 

                                }else {
                                    echo "<td colspan=3><h3>Nothing in list!</h3></td>";
                                } 
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>


<?php
    include_once($_SERVER['DOCUMENT_ROOT'].'/view/common/footer.php');
?>