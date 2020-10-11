<?php
    session_start();
    include_once($_SERVER['DOCUMENT_ROOT'] . '/view/common/header.php');
    /**
     * if search button pressed and some search string sent to this page
     * else search string is empty
     */
    $search_string = "";
    if (isset($_GET['search_string'])) {
        $search_string = $_GET['search_string'];
    }
?>
<title>Document</title>
<!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script> -->

<div class="container">

    <div id="searchResult">
        <h6 class="text-muted">Powered By <i>Google Books</i></h6>
        <h4>
            Search Result For -<span id="searchString"> <?=$search_string;?> </span>
        </h4>
        Total <b><span id="totalBooks">...</span></b> Books Found. <br>

        <div class="container" id="bookList">
        <!-- after search books will be appended here -->
        </div>
    </div>

</div>

<!-- 
    following javascript section requests books from google books api
 -->
<script>
    // searchString & setting variables are for URL building
    // URl in ajax function request google books
    let searchString = document.getElementById("searchString").innerText;
    let setting = `&maxResults=10&startIndex=0&fields=totalItems,items(id,volumeInfo(title,authors,publishedDate,industryIdentifiers,imageLinks))`;

    // ajax api call & book list show
    $(document).ready(function () {
        $.ajax({
            url: "https://www.googleapis.com/books/v1/volumes?q="+searchString+setting,
            type: "GET",
            success: function (result) {
                let fragment = new DocumentFragment(); 
                let bookCover="../asset/img/128x200.png";
                // console.log(result);
                document.getElementById("totalBooks").innerHTML = (result.totalItems);


                for (var i = 0; i < result.items.length; i++) {
                    // book identifier
                    let isbn = result.items[i].volumeInfo.industryIdentifiers[0].identifier;
                    // check if book cover is available in google or not
                    if (result.items[i].volumeInfo.imageLinks !== undefined) {
                        bookCover = result.items[i].volumeInfo.imageLinks.thumbnail;
                    }else{
                        bookCover = "../asset/img/128x200.png";
                    }

                    $('#bookList').append(
                        '<div class="card" id="books">'+
                        '<div class="card-horizontal">'+
                        '<div class="img-square-wrapper">'+
                        '<img class="" src="'+bookCover+'" alt="Card image cap">'+
                        '</div>'+
                        '<div class="card-body">'+
                        '<a class="link" href="bookPreview?isbn='+isbn+'"><h5 class="card-title">'+
                        result.items[i].volumeInfo.title+
                        '</a></h5>'+
                        '<b>Author: </b>' + result.items[i].volumeInfo.authors+ '<br>'+
                        '<b>Published: </b>' + result.items[i].volumeInfo.publishedDate+ '<br>'+
                        '<b>ISBN: </b>' + isbn+
                        '</div>'+
                        '</div>'+
                        '</div>'+
                        '</div>'
                    );
                }
            },
            error: function (request, status, error) {
                console.log(request.responseText);
            }
        });
    });
</script>

<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/view/common/footer.php');

?>
