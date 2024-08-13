<?php
require_once '../config/user.php';
require_once '../req/retrieve_foreignKeys.php';
require_once 'lesson_notes.php';
?>
<?php

class Admin extends User
{
    private $findIds;
    private $notes;

    public function __construct($pdo)
    {
        parent::__construct($pdo);
        $this->findIds = new FindIds($pdo); //we are passing the pdo instace to FindIds
        $this->notes = new LessonnNotes($pdo);
    }


    //methods to implement teacher queries
    public function create($id, $student_id, $teacher_id, $result_id, $first_name, $last_name, $email, $date_of_brith, $pass, $phone, $gender, $p_image)
    {
        if ($_FILES['p_image']['error'] == UPLOAD_ERR_OK) {
            $Dir = 'server/uploadpics/';
            $file = $Dir . basename($_FILES['p_image']['name']);

            if (move_uploaded_file($p_image['tmp_name'], $file)) {
                $p_imagePath = $file;
            } else {
                throw new Exception("Fialed to upload"); //when it fails to upload
            }
        } else {
            $p_imagePath = null; //no image is uploaded
        }


        //retrieving the foreign keys to be accessed by the student table.
        $studentID = $this->findIds->findStudentById($student_id);
        $resultID = $this->findIds->findResultById($result_id);
        $teacherID = $this->findIds->findTeacherBySubjectId($teacher_id);

        //inserting a tuple
        $stmt = $this->pdo->prepare(
            'INSERT INTO teacher(
          id,
          student_id,
          teacher_id,
          result_id,
          first_name, 
          last_name, 
          email,
          date_of_birth,
          pass, 
          phone,
          gender,
          p_image) VALUES(
          :id,
          :student_id,
          :teacher_id, 
          :result_id,
          :fname,
          :lname, 
          :email, 
          :date_of_birth, 
          :pass, 
          :phone,
          :gender,
          :profile_image
          )'
        );

        $stmt->execute(
            [
                'id' => $id,
                'student_id' => $student_id,
                'teacher_id' => $teacher_id,
                'result_id' => $result_id,
                'fname' => $first_name,
                'lname' => $last_name,
                'email' => $email,
                'date_of_birth' => $date_of_brith,
                'pass' => $pass,
                'phone' => $phone,
                'gender' => $gender,
                'profile_iamge' => $p_imagePath
            ]
        );
    }

    //admin changing their password and updating their profile picture
    public function updateProfile($id, $newPaswword = null, $p_image = null)
    {
        $updateFields = [];
        $params = ['id' => $id];

        if ($newPaswword) //if the password is new
        {
            $updateFields = 'password = :password';
            $params['password'] = password_hash($newPaswword, PASSWORD_DEFAULT);
        }
        //updating profile image
        if ($p_image && $_FILES['p_image']['error'] == UPLOAD_ERR_OK) {
            $Dir = 'server/uploadpics/';
            $file = $Dir . basename($_FILES['p_image']['name']);

            if (move_uploaded_file($p_image['tmp_name'], $file)) {
                $updateFields[] = 'p_image = :profile_image';
                $params['profile_image'] = $file;
            } else {
                throw new Exception("Fialed to upload"); //when it fails to upload
            }
        }
        // adding the fields to update them
        $sql_q = 'UPDATE administrator SET ' . implode(',', $updateFields) . 'WHERE id = :id';
        $stmt = $this->pdo->prepare($sql_q);
        $stmt->execute($params);
    }
    //admin getting report 
    public function searchResource($course_id, $item)
    {
        $stmt = $this->pdo->prepare('SELECT * FROM resources WHERE course_id = :course_id AND title LIKE :search');
        $stmt->execute([
            'course_id' => $course_id,
            'search' => '%' . $item . '%'
        ]);
    }

    public function searchStudent($student_id, $fname) //teacher searching for a student
    {
        $stmt = $this->pdo->prepare('SELECT * FROM student WHERE adm_number = :student_id AND fname LIKE :name');
        $stmt->execute([
            'student_id' => $student_id,
            'name' => '%' . $fname . '%'
        ]);
    }
    public function getStudentReults($student) //admin getting student's result/report
    {
        $stmt = $this->pdo->prepare('SELECT * FROM result WHERE student_id = :student_id');
        $stmt->execute(['student_id' => $student]);
        return $stmt->fetchAll();
    }


    //teacher searching for student's results
    public function readResults($student_id, $item = null)
    {
        if ($item) {
            $stmt = $this->pdo->prepare('SELECT FROM result WHERE student_id = :student_id AND subject_id LIKE :subject');
            $stmt->execute([
                'student_id' => $student_id,
                'subject' => '%' . $item . '%'
            ]);
        } else {
            $stmt = $this->pdo->prepare('SELECT FROM result WHERE student_id = :student_id');
            $stmt->execute(['student_id' => $student_id]);
        }
        return $stmt->fetchAll();
    }
    public function viewNotesByCourse($course_id) //admin gets to view teachers lesson notes  by accessing it from the lessonNotes class
    {
        return $this->notes->getNotesByCourse($course_id);
    }
}//end of Student class