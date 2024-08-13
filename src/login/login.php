
<?php

    session_start(); //start session
   /*  print_r($_POST);
    ini_set('display_errors', 1);
    error_reporting(E_ALL); */

    require '../config/db_con.php';
    $teacherUrl = './teacher_d.php';
    $adminUrl = './admin_d.php';

    //create an instance of the database connection
    $db = new DataB();
    $pdo = $db->getPdo(); //getting the pdo instance to use

    //linking the login form to the backend
    if($_SERVER['REQUEST_METHOD'] === "POST")
        {
           var_dump($_POST);
           $identity = isset($_POST['options']) ? $_POST['options'] : null;
           $fname =trim($_POST['fname'] ?? '');
           $idNum = trim($_POST['idNum'] ?? ''); 
           

            echo "<br>Debug: Name - " . $fname . ", ID - " . $idNum .", identity- " . $identity;
           /* we have to validate the input field to see whether the user has left it empty or not.
                Mind you, we kinda handled that with JavaScrip to make sure the user enters something in the field
                So, here we are checking if it has been assigned a value or not. 
           
           */
           if(empty($identity) || $identity === 'lists' || empty($fname) || empty($idNum))
               {
                    echo '<br>All fields are mandatory';
               }
                else //if they have been assigned a value.
                {
                   //let's determine which user gets access depending on their identity selection 
                    switch($identity)
                        {
                            case 'student':
                                //we are using the pdo instance here to get access to the database
                                $stmt = $pdo->prepare("SELECT * FROM student WHERE CONCAT(first_name,' ', last_name)= :name AND adm_number = :id"); 
                                break;
                            case 'teacher':
                                //same here for teacher
                                 $stmt = $pdo->prepare("SELECT * FROM teacher WHERE CONCAT(first_name,' ', last_name)= :name AND T_id = :id");
                                 break;
                            case 'administrator':
                                //same
                                 $stmt = $pdo->prepare("SELECT * FROM administrator WHERE CONCAT(first_name,' ', last_name)= :name AND id = :id");
                                 break;
                            default:
                                 echo 'Invalid Selection';
                                 exit();
                        }
                    //using the queries to to get the full name and  their ids/password
                    $stmt->execute(['name' => $fname, 'id' => $idNum]);
                    $user = $stmt->fetch();
                    
                    //check if there's a matching record
                    if($user)
                        {

                           echo 'Welcome' . htmlspecialchars($user['first_name']). '!';
                           $_SESSION['user'] = $user; //storing user in session



                           //take the user to their appropriate location
                           switch($identity)
                               {
                                    case 'student':
                                        header("Location:  https://www.youtube.com/watch?v=1NiJcZrPHvA");
                                        break;
                                    case 'teacher':
                                        header("Location: $teacherUrl");
                                        break;
                                    case 'administrator':
                                        header("Location: $adminUrl");
                                        break;
                               }
                        }
                        else{
                            echo 'Invalid name or Id number';
                        }
                }
        }

?>