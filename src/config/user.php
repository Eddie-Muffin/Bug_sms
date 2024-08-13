
 <?php
    require_once '../config/db_con.php';

    ?>
<?php
class User
{
    protected $pdo; //the php data object to help us have easy access

    public function __construct($pdo) //passing pdo as an argument 
    {
        /*
                passing it as an argument allows as to access an already esxisting database connection
                meaning the user doesn't have to create another database connection. 
            */
        $this->pdo = $pdo;
    }
}

?>