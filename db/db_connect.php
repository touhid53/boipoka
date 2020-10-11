<?php

    /**
     * Dtabase Class
     * Maintain Connection with database
     */
    class database
    {
        private static $servername = "localhost";
        private static $username = "root";
        private static $password = "";
        private static $database = "boipoka";
        public static $conn;

        // ---------- if we directly use static function without creating
        // ---------- object, will it make app slower in multiple connection?
        public static function connect()
        {
            try {
                self::$conn = new mysqli(self::$servername ,self::$username, self::$password, self::$database );
                if (self::$conn->connect_error) {
                    die("Connection failed: " . self::$conn->connect_error);
                    //------------------- head to some error page
                    //------------------- like database down..
                }
                 //echo "Connected successfully";
            } catch (\Throwable $th) {
                throw $th;
            }
        }

        /**
         * Disconnect from database
         *
         * @return void
         */
        public static function disconnect()
        {
            self::$conn->close();
        }
    }
?>