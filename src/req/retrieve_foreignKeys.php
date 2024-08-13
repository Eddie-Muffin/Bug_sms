<?php

class FindIds
{
    private $pdo;

    public function __construct($pdo) //passing the pdo to get access to the database
    {
        $this->pdo = $pdo; //using this pdo instance for database operations.  
    }

    public function findCourseById($course) //retrieving the course id from it's table to be used as a foreign key in other tables
    {
        /*
                    Here, we're passing $course as a placeholder to get the assigned course in the table where 
                    the column name is course_name.Apllies to the rests. 
                */
        $statement = $this->pdo->prepare("SELECT C_id FROM courses WHERE course_name = :name");
        $statement->execute(['name' => $course]);
        return $statement->fetchColumn();
    }

    public function findSubjectById($subject) // retrieving subject id
    {
        $statement = $this->pdo->prepare("SELECT S_id FROM subjects WHERE subject_name = :name");
        $statement->execute(['name' => $subject]);
        return $statement->fetchColumn();
    }

    public function findResultById($result) //retrieving result id
    {
        $statement = $this->pdo->prepare("SELECT R_id FROM result WHERE grade = :name");
        $statement->execute(['name' => $result]);
        return $statement->fetchColumn();
    }

    public function findTeacherBySubjectId($t_id) //retrieving teacher id
    {
        $statement = $this->pdo->prepare("SELECT id FROM teacher WHERE subject_name = :name");
        $statement->execute(['name' => $t_id]);
        return $statement->fetchColumn();
    }
    public function findStudentById($student) //retrieving student id
    {
        $statement = $this->pdo->prepare("SELECT adm_number FROM student WHERE student_id = :student");
        $statement->execute(['student' => $student]);
        return $statement->fetchColumn();
    }
}
