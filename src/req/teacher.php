<?php
require_once '../config/user.php';
require_once '../req/retrieve_foreignKeys.php';
require_once 'lesson_notes.php';
?>
<?php

class Teacher extends User
{
    private $findIds;
    private $lessonNotes;

    public function __construct($pdo)
    {
        parent::__construct($pdo);
        $this->findIds = new FindIds($pdo); //we are passing the pdo instace to FindIds
        $this->lessonNotes = new LessonnNotes($pdo);
    }


    //methods to implement teacher queries
    public function create($T_id, $subject_id, $student_id, $result_id, $first_name, $last_name, $gender, $email, $pass, $p_image)
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
        $subjectID = $this->findIds->findSubjectById($subject_id);

        //inserting a tuple
        $stmt = $this->pdo->prepare(
            'INSERT INTO teacher(
          T_id,
          subject_id,
          student_id,
          result_id,
          first_name, 
          last_name, 
          gender,
          email,
          pass, 
          p_image) VALUES(
          :id,
          :subject_id,
          :student_id, 
          :result_id,
          :fname,
          :lname, 
          :gender, 
          :email, 
          :pass, 
          :profile_image
          )'
        );

        $stmt->execute(
            [
                'id' => $T_id,
                'course_id' => $subject_id,
                'subject_id' => $student_id,
                'result_id' => $result_id,
                'fname' => $first_name,
                'lname' => $last_name,
                'gender' => $gender,
                'email' => $email,
                'pass' => $pass,
                'profile_iamge' => $p_imagePath
            ]
        );
    }

    //student changing their password and updating their profile picture
    public function updateProfile($T_id, $newPaswword = null, $p_image = null)
    {
        $updateFields = [];
        $params = ['T_id' => $T_id];

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
        $sql_q = 'UPDATE teacher SET ' . implode(',', $updateFields) . 'WHERE T_id = :T_id';
        $stmt = $this->pdo->prepare($sql_q);
        $stmt->execute($params);
    }
    //teacher getting resource 
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

    //teacher get to create a lesson note 
    public function createLessonNotes($course_id, $teacher_id, $note_title, $note_file = null, $note_content = null)
    {
        $teacher_id = $this->findIds->findTeacherBySubjectId($teacher_id);
        $this->lessonNotes->create($course_id, $teacher_id, $note_title, $note_file, $note_content);
    }

    public function viewNotes($teacher_id) //teacher get to view notes
    {
        $teacher = $this->findIds->findTeacherBySubjectId($teacher_id);
        return $this->lessonNotes->getNotesByTeacher($teacher);
    }
}//end of Student class