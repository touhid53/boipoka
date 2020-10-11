<?php
    session_start();
    // include header - navbar
    include_once($_SERVER['DOCUMENT_ROOT'].'/view/common/header.php');
    // include book handler class
    include_once($_SERVER['DOCUMENT_ROOT'].'/controller/book_handler/book.php');
    

    /**
     * get & set isbn from url request
     * initially isbn variable set to null
     */
    $isbn = null;
    if (isset($_GET['isbn']) && $_GET['isbn']!=='') {
        // corner case handle: remove all spaces from isbn
        $_GET['isbn'] = preg_replace('/\s+/', '', $_GET['isbn']);
        $isbn = $_GET['isbn'];
    }
?>
<title>Book Preview</title>

<?php
    // error messages
    $notFoundErrorMsg = '
    <div class="container text-center">
        <h1>Sorry,Book Not Found!</h1>
        <h5>Try another one.</h5>
    </div>';

    $connectionErrorMsg = '
    <div class="container text-center">
        <h1>Sorry,Error Occured!</h1>
        <h5>Can not connect</h5>
    </div>
      ';

    /**
     * if isbn is empty or null show-no book
     * else try to get book
     * if not found show error message
     * if cant get book show error
     * else save book details in variables
     */
    if (is_null($isbn)) {
        echo $notFoundErrorMsg;
        exit();
    }else{
        // get book details fromm book class
        $book_detail = book::getBookDetail($isbn);

        if ($book_detail == 0) {
            echo $notFoundErrorMsg; 
            exit();
        } else if ($book_detail == -1) {
            echo $connectionErrorMsg;
            exit();
        }else{
            if (array_key_exists("imageLinks", $book_detail)) {
                $cover = ($book_detail["imageLinks"]["thumbnail"]);
            } else {
                $cover = "/asset/img/128x200.png";
            }

            // details
            if (array_key_exists("description", $book_detail)) {
                $description = ($book_detail["description"]);
            } else {
                $description = "No Description Available";
            }
            
            // rating
            if (array_key_exists("averageRating", $book_detail)) {
                $averageRating = ($book_detail["averageRating"]);
                $ratingsCount = ($book_detail["ratingsCount"]);
            } else {
                $averageRating = "None";
                $ratingsCount = "0";
            }

            // pageCount
            if (array_key_exists("pageCount", $book_detail)) {
                $pageCount = ($book_detail["pageCount"]);
            } else {
                $pageCount = "Not Available";
            }
            
            // publishedDate
            if (array_key_exists("publishedDate", $book_detail)) {
                $publishedDate = ($book_detail["publishedDate"]);
            } else {
                $publishedDate = "Not Available";
            }

            // published Date
            if (array_key_exists("publishedDate", $book_detail)) {
                $publishedDate = ($book_detail["publishedDate"]);
            } else {
                $publishedDate = "Not Available";
            }


            //  book details data
            $cover;
            $title = ($book_detail["title"]);
            $authors = ($book_detail["authors"]);
            //.$publisher = ($book_detail["publisher"]);
            $pageCount;
            $publishedDate;
            $description;
            $averageRating;
            $ratingsCount;
            $previewLink = ($book_detail["previewLink"]);
        }
    }
?>
<!-- Show book Details here -->
<div class="container">
    <div class="row">
        <div class="col-md-4">
            <div class="bookCover" <div class="bookWraper" style="text-align: center;">
                <img src="<?=$cover?>" alt="Book cover" class="img-fluid">
            </div>
        </div>
        <div class="col">
            <div class="bookDetails">
                <h3 id="bookTitle"><?=$title;?></h3>
                <p>Author: <?=$authors[0];?></p>
                <p>Published: <?=$publishedDate;?></p>
                <p>ISBN:<span id="isbn"><?=$isbn;?></span></p>
                <p>Pages: <?=$pageCount;?></p>
                <p>Average Rating: <?=$averageRating;?> (<?=$ratingsCount;?> reviews)</p>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col">
            <div class="bookDescription">
                <b>Description: </b> <?=$description; ?>
            </div>
        </div>
    </div>
    <br>
    <div class="row">
        <div class="col">
            <div class="readButton" style="text-align: center;">
                <a href="<?=$previewLink?>" target="_blank" id="googleRead">
                    <button class="btn btn-success" type="button">Read this now</button>
                </a>
            </div>
        </div>
        <div class="col">
            <div class="addToMyList">
                <div class="btn-group dropup">
                    <?php 
                        if (key_exists("gid",$_SESSION)):
                        ?>
                    <button type="button" class="btn btn-danger dropdown-toggle" data-toggle="dropdown"
                        aria-haspopup="true" aria-expanded="false">
                        Add this book to...
                    </button>
                    <div class="dropdown-menu">
                        <a class="dropdown-item" id="reading" href="#">Reading Now</a>
                        <a class="dropdown-item" id="later" href="#">Read later</a>
                        <a class="dropdown-item" id="read" href="#">Read</a>
                    </div>

                    <?php 
                        else : 
                    ?>
                    <a href="login.php">
                        <button type="button" class="btn btn-danger">
                            Login to add book to your list.
                        </button>
                    </a>
                    <?php 
                        endif;
                    ?>


                </div>
            </div>
        </div>
    </div>
</div>
<script>
    /**
    *   when DOM is ready, 
    *   disable 3 link clicks & do backend operation for them
    */
    $(document).ready(function () {
        // get isbn from this page
        let isbn = document.getElementById('isbn').innerText;
        let book_name = document.getElementById('bookTitle').innerText;

        /**
        *   ajax - on 'read / read later/ reading' - link click,
        *   send post request 
        *   to URL with data(as object)
        *   callBack function check operation status
        */

        // --- - - - - - click finction start
        //book already read / reading/ read later function
        $('#read , #later, #reading').click(function (e) {
            e.preventDefault();
            // call ajax
            $.post(
                "../controller/book_handler/book.php",
                {
                    action: this.id,
                    userID: "<?=$_SESSION['gid'];?>",
                    isbn: isbn,
                    bookName: book_name
                },
                function (response) {
                    response = JSON.parse(response);
                    // console.log(response.status);
                    // console.log(response);
                    alert(response.status);
                }
            );
            return false;
            // ---  - - - - -- click function end
        });
    });
</script>

<?php
    include_once($_SERVER['DOCUMENT_ROOT'].'/view/common/footer.php');
?>