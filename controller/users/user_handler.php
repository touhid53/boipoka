<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/db/db_connect.php');

/**
 * handle if any request submitted to this page
 * @todo add some validation
 */
if ($_REQUEST) {
    /**
     * @todo -array key validation add
     * net connection na thakle error dicche
     * if(array_key_exists)
     */
    if(array_key_exists('update_profile',$_POST )) {
        users::update_user($_POST['gid'], $_POST['new_name'],$_POST['gender']);
    }
}

class users
{
    //-------- add function documentation
    /**
    * - user signup class
    * - insert google data to database
    */
    public static function signUp($gid, $name, $email, $photo, $gender = null)
    {
        database::connect();

        // prevnt sql injection 
        $sql = database::$conn->prepare("INSERT INTO user_details (google_id, name, email, image_link, gender) VALUES (?,?,?,?,?)");
        $sql->bind_param("sssss", $gid, $name, $email, $photo, $gender);

        // try inserting into db
        if ($sql->execute() === TRUE) {
            return $name."- signed up successfully";
        } else {
            return(database::$conn->error);
        }
        // return 'hey' . $name;

        database::disconnect();
    }


    public static function getLatestUser(Type $var = null)
    {
        database::connect();

        $sql = "SELECT id, name FROM user_details ORDER BY id DESC LIMIT 5";
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


    // --  check if user already signed up with google
    /** 
    *   Check if user is available in our system
    *   @param 
    */
    public static function isUserAvailable(String $google_id)
    {
        database::connect();

        $sql = "SELECT * FROM user_details WHERE google_id= ?";

        $sql = database::$conn->prepare("$sql");
        
        $sql->bind_param("s", $google_id);
        $sql->execute();

        $result = $sql->get_result();

        if ($result->num_rows == 1) {
            // user already signed up
            return "true"; 
        }else {
            return "false";
        }

        $result->close();
        $sql->close();
        database::disconnect();
        
    }




    /**
     * - update user details in databse
     * - only name & gender update available
     * @todo add some validation
     */
    public static function update_user($user_id, $name, $gender)
    {
        database::connect();

        $sql = "UPDATE user_details SET name=? , gender=? WHERE google_id=?";

        $sql = database::$conn->prepare("$sql");

        $sql->bind_param("sss",$name,$gender,$user_id);
        
        if($sql->execute()){
            $_SESSION['user_name'] = $name;
            echo json_encode(array('success' => 1));
        }else {
            echo json_encode(array('success' => 0));
        }

        
    }



    /**
     *  get one perticaler user details
     *  via google id or User ID 
     * saved as : gid /user_id in session
    */
    public static function get_user($id)
    {
        database::connect();

        $sql = "SELECT * FROM user_details WHERE google_id= ? OR id=?";
        /*
        * Prepare the SQL statement for execution - ONLY ONCE.
        */
        $sql = database::$conn->prepare("$sql");
        
        /*
        * Bind variables for the parameter markers (?) in the 
        * SQL statement that was passed to prepare().
        */
        $sql->bind_param("ss", $id,$id);

        /*
        * Execute the prepared SQL statement.
        * When executed any parameter markers which exist will 
        * automatically be replaced with the appropriate data.
        * 
        * @link http://php.net/manual/en/mysqli-stmt.execute.php
        */
        $sql->execute();

        /*
        * Get the result set from the prepared statement.
        * 
        * NOTA BENE:
        * Available only with mysqlnd ("MySQL Native Driver")! If this 
        * is not installed, then uncomment "extension=php_mysqli_mysqlnd.dll" in 
        * PHP config file (php.ini) and restart web server (I assume Apache) and 
        * mysql service. Or use the following functions instead:
        * mysqli_stmt::store_result + mysqli_stmt::bind_result + mysqli_stmt::fetch.
        * 
        */
        $result = $sql->get_result();
        
        /*
        * Fetch data and save it into an array: 
        */
        // -- - - - if user found
        if ($result->num_rows  > 0) {
            # code...
            $user = $result->fetch_array(MYSQLI_ASSOC);
            //  do what to do with the data
            
            return $user;
        } else {
            return 0;
        }
        
        /*
        * Free the memory associated with the result. 
        */
        $result->close();

        /*
        * Close the prepared statement. It also deallocates the statement handle.
        * If the statement has pending or unread results, it cancels them 
        * so that the next query can be executed.
        * 
        * @link http://php.net/manual/en/mysqli-stmt.close.php
        */
        $sql->close();

        /*
        * Close the previously opened database connection.
        * 
        * @link http://php.net/manual/en/mysqli.close.php
        */
        database::disconnect();

    }



    /**
     * - get all users from database
     *
     * @return void
     */
    public static function getAllUser()
    {
        database::connect();

        $sql = "SELECT * FROM user_details";

        $sql = database::$conn->prepare("$sql");
        
        // $sql->bind_param("s", $google_id);
        $sql->execute();

        $result = $sql->get_result();
        
        $user = $result->fetch_array(MYSQLI_ASSOC);
        echo "<pre>";
        print_r($user);
        echo "</pre>";

        $result->close();
        $sql->close();
        database::disconnect();
    }
}
