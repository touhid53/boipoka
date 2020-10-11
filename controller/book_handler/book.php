<?php
    /**
     * @todo add book isbn & title to "our_book" table
     * when user try to add book to their list.
     */

    /**
     * including GuzzleHttp library
     * its a helper library to use api or make HTTP requests
     */
    require_once($_SERVER['DOCUMENT_ROOT'].'/vendor/autoload.php');
    
    //connect ot database
    include_once($_SERVER['DOCUMENT_ROOT'] . '/db/db_connect.php');

    /**
     * handle if any request submitted to this controller page
     * @todo add some validation
    */
    if ($_POST) {
        // echo json_encode($_POST);

        /**
         * post[action] key for bookPreview page actions
         * post[update] key for profile -(proofileBody.php) page actions
         */
        if (array_key_exists('action', $_POST)) {
            if ($_POST['action'] == "read") {
                book::read($_POST['userID'],$_POST['isbn'],$_POST['bookName']);
            } elseif($_POST['action'] == "later"){
                book::later($_POST['userID'],$_POST['isbn'],$_POST['bookName']);
            }elseif ($_POST['action'] == "reading") {
                book::reading($_POST['userID'],$_POST['isbn'],$_POST['bookName']);
            }
        }elseif(array_key_exists('update', $_POST)){
            // echo json_encode("got you");
            if ($_POST['update'] == "move") {
                $result = book::move($_POST['isbn'],$_POST['from'],$_POST['to'],$_POST['userID']);
                echo json_encode(array("status" => $result));
            } elseif($_POST['update'] == "remove"){
                $result = book::removeBook($_POST['from'],$_POST['userID'],$_POST['isbn']);
                echo json_encode(array("status" => $result));
            }
        }
    }


    
    /**
     * - Book Class, handles book requests
     * - @method getBookDetails() returns one book detail
     * - @method mixed methodName()
     */
    class book{
        
        /**
         * - getBookDetails
         *
         * - @param [type] $isbn
         * - @return 0, if no books
         * - @return -1, if thers query error
         * - @return [array], if book found 
         */
        public static function getBookDetail($isbn)
        {
            /**
             * @todo if book doesn't have isbn, it may have : 
             * OXFORD / oclc / MINN / IND / UCAL / BSB / LCCN / STANFORD... etc.
             * 
             * handle this corner case.
             * example: 
             * **     type": "OTHER",
             *        "identifier": "UOM:39015011715623"
             * =====> other:stanford36105045649873
             * 
             * **     other:minn31951P00908069C
             *        ==> this produce another error, returns 2 books
             * need to handle this too
             */


            /**
             * initialize GuzzleHttp
             * & get book data      
             */
            $client = new GuzzleHttp\Client();
            try {
                $response = $client->get('https://www.googleapis.com/books/v1/volumes?q=isbn:'.$isbn);
                $response = $response->getBody();
                
                /**
                 * Use the second parameter of json_decode to make it return an array
                 */
                $response = json_decode($response, true);

                if ($response['totalItems'] == 0) {
                    return 0;
                } else {
                    $response = $response['items']['0']['volumeInfo'];
                    // validate if all required data available in array
                    // $response = validate($response);

                    return $response;
                }
            } catch (\Throwable $th) {
                return -1;
            }
        }

        /**
         * - @todo Not Implemented & used
         * - validate if all required data available in array
         * - else put demo data
         */
        public function validate($book)
        {
            /**
             * array key na thakle key add kore data dd kore dite hbe
             */
            if (array_key_exists("imageLinks", $book)) {
                $cover = ($book["imageLinks"]["thumbnail"]);
            } else {
                $cover = "/asset/img/128x200.png";
            }
            
        }

        /**
         * -------------------------------------
         * isAddedIn function checks if 
         * the "target isbn" is added in
         * the "targeted table" for
         * the "targeted user" 
         *
         * - @param [type] $table - where to check
         * - - tables: already_read, reading, read_later
         * - @param [type] $gid   - for whom
         * - @param [type] $isbn  - which book
         * - @return boolean    - found or not
         * - @return -1 if query error
         * -------------------------------------
         */
        public function isAddedIn($table,$gid,$isbn)
        {
            database::connect();
            $sql;

            /**
             * request wise query select
             */
            if ($table == "already_read") {
                $sql = "SELECT * FROM already_read WHERE user_id= ? AND isbn = ?";
            } elseif($table == "reading") {
                $sql = "SELECT * FROM reading WHERE user_id= ? AND isbn = ?";
            }elseif ($table == "read_later") {
                $sql = "SELECT * FROM read_later WHERE user_id= ? AND isbn = ?";
            }else {
                return -1;
                exit();
            }
            

            // $sql = "SELECT * FROM already_read WHERE user_id= ? AND isbn = ?";

            $sql = database::$conn->prepare("$sql");
            
            $sql->bind_param("ss", $gid, $isbn);
            $sql->execute();

            $result = $sql->get_result();

            if ($result->num_rows == 1) {
                // user already signed up
                return true; 
            }else {
                return false;
            }

            $result->close();
            $sql->close();
            database::disconnect();
        }


        /**
         * for an user
         * from one table collection
         * move one book 
         * to another table collection
         *
         * - @param [type] $isbn
         * - @param [type] $from
         * - @param [type] $to
         * - @param [type] $gid
         * - @return void
         */
        public function move($isbn, $from, $to, $gid)
        {
            if(book::removeBook($from,$gid,$isbn) && book::addBook($to, $gid, $isbn))
            {
                return true ;
            }else {
                return false;
            }
        }



        /**
         * this function deletes targeted book 
         * from targeted db table 
         * for targeted user
         *  @todo delete operation should be validated before it take place
         * first select -> then delete -> then select = check differences & availibility
         * - @param [type] $table - where to check
         * - - tables: already_read, reading, read_later
         * - @param [type] $gid   - for whom
         * - @param [type] $isbn  - which book
         * @return void
         */
        public static function removeBook($table,$gid,$isbn)
        {
            database::connect();
            $sql;

            /**
             * request wise query select
             */
            if ($table == "already_read") {
                $sql = "DELETE FROM already_read WHERE user_id= ? AND isbn = ?";
            } elseif($table == "reading") {
                $sql = "DELETE FROM reading WHERE user_id= ? AND isbn = ?";
            }elseif ($table == "read_later") {
                $sql = "DELETE FROM read_later WHERE user_id= ? AND isbn = ?";
            }else {
                return -1;
                exit();
            }
            

            $sql = database::$conn->prepare("$sql");
            
            $sql->bind_param("ss", $gid, $isbn);
            $sql->execute();

            if (($sql->affected_rows) > 0) {
                return true; 
            }else {
                return false;
            }
            $sql->close();
            database::disconnect();
        }



        
        /** 
         * this function add targeted book 
         * to targeted db table 
         * for targeted user
         *
         * - @param [type] $table - where to check
         * - - tables: already_read, reading, read_later
         * - @param [type] $gid   - for whom
         * - @param [type] $isbn  - which book
         * @return void
         * 
         */
        public static function addBook($table,$gid,$isbn)
        {
            database::connect();

            /**
             * request wise query select
             */
            if ($table == "already_read") {
                $sql = "INSERT INTO already_read (user_id, isbn, end_date) VALUES (?,?,?)";
            } elseif($table == "reading") {
                $sql = "INSERT INTO reading (user_id, isbn, start_date) VALUES (?,?,?)";
            }elseif ($table == "read_later") {
                $sql = "INSERT INTO read_later (user_id, isbn, start_date) VALUES (?,?,?)";
            }else {
                return -1;
                exit();
            }

            $date = date("Y-m-d");

            // prevnt sql injection 
            $sql = database::$conn->prepare("$sql");
            $sql->bind_param("sss", $gid, $isbn, $date);

            // try inserting into db
            if ($sql->execute() === TRUE) {
                return true;
            } else {
                return(database::$conn->error);
            }
            // return 'hey' . $name;

            $sql->close();
            database::disconnect();
        }

        /**
         * - add book to "our_books" table
         * - if book not in our_books -> add
         * - else do nothing
         */
        public function addToOurBooks($isbn, $bookName)
        {
            database::connect();

            $get = "SELECT * FROM our_books WHERE isbn = ? ";
            $get = database::$conn->prepare("$get");

            $get->bind_param("s",$isbn);
            $get->execute();

            $result = $get->get_result();
            if($result->num_rows < 1){
                $result->close();
                $get->close();

                $add = "INSERT INTO our_books(isbn,book_name) VALUES (?,?)";

                $add = database::$conn->prepare("$add");
                $add->bind_param("ss",$isbn, $bookName);

                if($add->execute() === TRUE){
                    return 1 ;
                }

                $add->close();
                database::disconnect();
            }else{
                return 0 ;
                $result->close();
                $sql->close();
                database::disconnect();
            }
        }

        /**
         * Book Already read
         * add to already_read table, remove from others
         * @param [boolean] $status : show query status
         */
        public static function read($gid,$isbn,$bookName)
        {
            $status = book::isAddedIn("already_read", $gid, $isbn);

            if ($status == 1) {
                $retVal = "Book Already Marked as Read";
            } elseif($status == 0) {
                /** i can check in reading / later table
                 * but I wont
                 * instead i'll remove from both those table
                 * and add to read table
                 * then show book added
                */
                book::removeBook("read_later", $gid, $isbn);
                book::removeBook("reading", $gid, $isbn);
                
                $retVal = book::addBook("already_read", $gid, $isbn);

                if($retVal== true){
                    $retVal = "Book Marked as Read";
                    /**
                     * now check if the book is available in our collection
                     * if not, add book name & isbn to "our_books" table
                     */
                     book::addToOurBooks($isbn,$bookName);
                    
                }else{
                    /**
                     * @todo find possible error which can occur
                     */
                    $retVal = "Unknown Error => ".$retVal;
                }

            }elseif($status == -1) {
                $retVal = "Query Error";
            }
            
            echo json_encode(array('status' => $retVal));
        }



        /**
         * User wants to add Book to read later list
         * if not already read / reading
         * add to read_later table
         * @param [boolean] $status : show query status
         */
        public static function later($gid,$isbn,$bookName)
        {
            $status = book::isAddedIn("read_later", $gid, $isbn);

            if ($status == 1) {
                $retVal = "Book Already available in wish list";
            } elseif($status == 0) {
                if (book::isAddedIn("already_read", $gid, $isbn)) {
                    $retVal = "You already Read this book!";
                } elseif(book::isAddedIn("reading", $gid, $isbn)){
                    $retVal = "You are currently reading this book!";
                }else{
                    $retVal = book::addBook("read_later", $gid, $isbn);
                    if($retVal== true){
                        $retVal = "Book Added to wish List";

                        /**
                         * now check if the book is available in our collection
                         * if not, add book name & isbn to "our_books" table
                         */
                        book::addToOurBooks($isbn,$bookName);

                    }else{
                        /**
                         * @todo find possible error which can occur
                         */
                        $retVal = "Unknown Error => ".$retVal;
                    }
                }
            }elseif($status == -1) {
                $retVal = "Query Error";
            }
            
            echo json_encode(array("status" => $retVal));
            
        }


        /**
         * User wants to add book to currently reading list
         * if not already read
         * add to reading table, 
         * if in later then remove
         * @param [boolean] $status : show query status
         */
        public static function reading($gid,$isbn,$bookName)
        {   
            $status = book::isAddedIn("reading", $gid, $isbn);

            if ($status == 1) {
                $retVal = "This Book is already in reading list!";
            } elseif($status == 0) {
                if (book::isAddedIn("already_read", $gid, $isbn)) {
                    $retVal = "You already Read this book!";
                } elseif(book::isAddedIn("read_later", $gid, $isbn)){
                    // remove book from later table & mark as reading
                    book::removeBook("read_later",$gid,$isbn);

                    $retVal = book::addBook("reading", $gid, $isbn);
                    if($retVal == true){
                        $retVal = "Book Moved to Currently Reading List";
                    }else{
                        /**
                         * @todo find possible error which can occur
                         */
                        $retVal = "Unknown Error => ".$retVal;
                    }
                }else{
                    $retVal = book::addBook("reading", $gid, $isbn);
                    if($retVal== true){
                        $retVal = "Book Added to Currently Reading List";
                        /**
                         * now check if the book is available in our collection
                         * if not, add book name & isbn to "our_books" table
                         */
                        book::addToOurBooks($isbn,$bookName);

                    }else{
                        /**
                         * @todo find possible error which can occur
                         */
                        $retVal = "Unknown Error => ".$retVal;
                    }
                }
            }elseif($status == -1) {
                $retVal = "Query Error";
            }
            
            echo json_encode(array("status" => $retVal));
        }


        public static function getMostReadBooks()
        {
            database::connect();

        $sql = "SELECT our_books.isbn, our_books.book_name, COUNT(already_read.isbn) FROM already_read , our_books WHERE already_read.isbn = our_books.isbn GROUP BY isbn ORDER BY COUNT(our_books.isbn) DESC LIMIT 5";

        $sql = database::$conn->prepare("$sql");
        $sql->execute();

        $result = $sql->get_result();

        if ($result->num_rows  > 0) {
            # code...
            $user = $result->fetch_all(MYSQLI_ASSOC);
            //  do what to do with the data
            
            return $user;
        } else {
            return 0;
        }

        $result->close();
        $sql->close();
        database::disconnect();
        }

    }
?>