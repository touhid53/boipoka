<?php
    /**
     * get data from DB
     * to show in user profile page
    */

    //connect ot database
    include_once($_SERVER['DOCUMENT_ROOT'] . '/db/db_connect.php');
    

    /**
     * 
     */
    class profileViewData
    {
        /**
         * - returns already read books(name & isbn) for an user
         *
         * - @param [googe_id]/$user_gid $userID
         * - @return array of books or FALSE
         */
        public static function get_alreadyReadBooks($userID)
        {  
            database::connect();

            $sql = "SELECT our_books.isbn, our_books.book_name, already_read.end_date FROM our_books, already_read WHERE our_books.isbn = already_read.isbn AND already_read.isbn IN( SELECT isbn FROM already_read WHERE user_id = ? ) GROUP BY isbn";
            
            $sql = database::$conn->prepare("$sql");
            $sql->bind_param("s",$userID);
            $sql->execute();

            $results = $sql->get_result();

            if ($results->num_rows > 0) {
                return $results->fetch_all(MYSQLI_ASSOC);
            } else {
                return 0;
            }

            $results->close();
            $sql->close();
            database::disconnect();
        }


        /**
         * - returns books(name & isbn) added to read later for an user
         *
         * - @param [googe_id]/$user_gid $userID
         * - @return array of books or FALSE
         */
        public static function get_read_later($userID)
        {  
            database::connect();

            $sql = "SELECT isbn, book_name FROM our_books WHERE isbn IN(
            SELECT isbn FROM read_later WHERE user_id = ? )";
            
            $sql = database::$conn->prepare("$sql");
            $sql->bind_param("s",$userID);
            $sql->execute();

            $results = $sql->get_result();

            if ($results->num_rows > 0) {
                return $results->fetch_all(MYSQLI_ASSOC);
            } else {
                return 0;
            }

            $results->close();
            $sql->close();
            database::disconnect();
        }


        /**
         * - returns list of books(name & isbn) 
         *  - which user are currently reading
         *
         * - @param [googe_id]/$user_gid $userID
         * - @return array of books or FALSE
         */
        public static function get_reading($userID)
        {  
            database::connect();

            $sql = "SELECT isbn, book_name FROM our_books WHERE isbn IN(
            SELECT isbn FROM reading WHERE user_id = ? )";
            
            $sql = database::$conn->prepare("$sql");
            $sql->bind_param("s",$userID);
            $sql->execute();

            $results = $sql->get_result();

            if ($results->num_rows > 0) {
                return $results->fetch_all(MYSQLI_ASSOC);
            } else {
                return 0;
            }

            $results->close();
            $sql->close();
            database::disconnect();
        }
        
    }
    

?>