<?php
class DataB
{
    //attribute data members for the database connection
    private $host = 'localhost';
    private $dbn = 'sms_db';
    private $username = 'root';
    private $password = '';
    private $pdo;

    public function __construct() //default construct for the class
    {
        $connection = "mysql:host=$this->host;dbname=$this->dbn";

        //checking for true connection
        try {
            /*
                Here, we pass the host(our source - MySQl), and the database name that makes the connection
                together with the root(username for connectind to the db) and password of the database to the PDO object.  
                A php data object that provides a simple way to access multiple databases.

           */
            $this->pdo = new PDO($connection, $this->username, $this->password);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            echo 'Database connected successfully!';
        } catch (PDOException $m) {
            echo 'You could not connect!: ' . $m->getMessage();
        }
    }

    //getter function to get the pdo and use it
    public function getPdo()
    {
        return $this->pdo;
    }
}
