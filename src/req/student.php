<?php
require_once '../config/user.php';
require '../req/retrieve_foreignKeys.php';

class Student extends User
{
    private $findIds;

    public function __construct($pdo)
    {
        parent::__construct($pdo);
        $this->findIds = new FindIds($pdo); // Passing the pdo instance to FindIds
    }

    // Methods to implement student queries
    public function create($adm_number, $course_id, $subject_id, $first_name, $last_name, $gender, $guardian, $guardian_phone)
    {
        // Retrieving the foreign keys to be accessed by the student table
        $courseId = $this->findIds->findCourseById($course_id);
        $subjectID = $this->findIds->findSubjectById($subject_id);

        // Inserting a record
        $stmt = $this->pdo->prepare('INSERT INTO student (adm_number, course_id, subject_id, first_name, last_name, gender, guardian, guardian_phone,pass) VALUES (:id, :course_id, :subject_id, :fname, :lname, :gender, :guardian, :guardian_phone,:pass)');

        $stmt->execute([
            'id' => $adm_number,
            'course_id' => $courseId,
            'subject_id' => $subjectID,
            'fname' => $first_name,
            'lname' => $last_name,
            'gender' => $gender,
            'guardian' => $guardian,
            'guardian_phone' => $guardian_phone,
            'pass' => $adm_number,
        ]);
    }

    // Student changing their password
    public function updateProfile($user_id, $newPassword)
    {
        // Handle password update
        if ($newPassword) {
            // Fetch current password hash for comparison
            $stmt = $this->pdo->prepare('SELECT pass FROM student WHERE adm_number = :user_id');
            $stmt->execute(['user_id' => $user_id]);
            
            // Fetch the result
            $row = $stmt->fetch();
            
            // Check if a result was found
            if ($row) {

                $currentPasswordHash = $row['pass'];
    
                $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
    
                // Debug output
                echo "Current Password Hash: $currentPasswordHash\n";
                echo "New Hashed Password: $hashedPassword\n";
    
                // Check if the new hashed password is different
                if ($hashedPassword === $currentPasswordHash) {
                    echo 'The new password cannot be the same as the old password.';
                    return;
                }
    
                $stmt = $this->pdo->prepare('UPDATE student SET pass = :pass WHERE adm_number = :user_id');
                $stmt->execute([
                    'pass' => $hashedPassword,
                    'user_id' => $row['pass']
                ]);
    
                if ($stmt->rowCount() > 0) {
                    echo 'Password updated successfully!';
                } else {
                    echo 'No changes were made.';
                }
            } else {
                echo "Error: User not found or invalid adm_number.\n";
            }
        } else {
            echo 'No password provided.';
        }
    }
    

    
    
    // Student searching for results
    public function readResults($student_id, $item = null)
    {
        if ($item) {
            $stmt = $this->pdo->prepare('SELECT * FROM result WHERE student_id = :student_id AND subject_id LIKE :subject');
            $stmt->execute([
                'student_id' => $student_id,
                'subject' => '%' . $item . '%'
            ]);
        } else {
            $stmt = $this->pdo->prepare('SELECT * FROM result WHERE student_id = :student_id');
            $stmt->execute(['student_id' => $student_id]);
        }
        return $stmt->fetchAll();
    }
} // End of Student class

