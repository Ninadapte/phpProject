<?php
    // Turn off all error reporting
   
   
    error_reporting(~E_NOTICE);

    class DatabaseCON
    {
        //connection specific constants
        const servername = "servername";
        const username = "username";
        const password = "password";
        const dbname = "databasename";
        private $connection;
        
        public function __construct()
        {
            $this->makeConnection();
        }

        //make the connection with the database and return the connection object
        function makeConnection()
        {
            $this->connection = new mysqli(self::servername, self::username, self::password,self::dbname);
            if ($this->connection->connect_error) {
                echo 'Connection failed with database';
                $this->connection = 0;
                return False;
                }
                
                return True;
            
        }

        #query the database
        function query($query)
        {
            if(isset($this->connection) && intval($this->connection) != 0)
            {
            $result = $this->connection->query($query);
            if ($result === TRUE)
            {
              
               return $result;
             } else {
               
               
               return $result;
             }
            }
            else{
                return False;
            }
        }

        //to close the database
        function closeDatabase()
        {
            if(isset($this->connection))
            {
                $this->connection->close();
            }
        }

        //to check if connection was successful
        function issuccess()
        {
            
            if (isset($this->connection) && intval($this->connection)!=0)
            {
                return True;
            }
            return False;
            
        }
        function checkExists($email)
        {
            $query = 'select * from table where email = \''.$email.'\'';
            $result = $this->connection->query($query);
            if($result->num_rows > 0)
            {
                return True;
            }
            return False;
        }
    }

     
    
    $database = new  DatabaseCON();

    
?>