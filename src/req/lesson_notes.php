<?php

class LessonnNotes
{
    private $pdo;


    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    //creating or storing the note file into the database
    public function create($course_id, $teacher_id, $note_title, $note_content = null, $note_file = null)
    {
        $note_file_path = null;

        if ($note_file && $note_file['error'] == UPLOAD_ERR_OK) {
            $dir = 'server/resource/';
            $note_file_path = $dir . basename($note_file['name']);


            if (move_uploaded_file($note_file['tmp_name'], $note_file_path)) {
                echo 'File uploaded successfully!';
            } else {
                throw new Exception('Failed to upload');
            }
        }
        $stmt = $this->pdo->prepare(
            'INSERT INTO lesson_notes(
                        course_id, teacher_id, note_title, note_content, note_file_path
                   ) VALUES (
                        :course_id, :teacher, :note_title, :note_content, :note_file_path
                   )'
        );

        $stmt->execute([
            'course_id' => $course_id,
            'teacher_id' => $teacher_id,
            'note_title' => $note_file,
            'note_content' => $note_content,
            'note_file_path' => $note_file_path
        ]);
    }


    public function getNotesByTeacher($teacher_id) //we get a note by using a teacher's id
    {
        $stmt = $this->pdo->prepare('SELECT * FROM lesson_notes WHERE teacher_id = :teacher_id');
        $stmt->execute(['teacher_id' => $teacher_id]);
        return $stmt->fetchAll();
    }

    public function getNotesByCourse($course_id) //getting the notes by using the course id /name
    {
        $stmt = $this->pdo->prepare('SELECT * FROM lesson_notes WHERE course_id = :course_id');
        $stmt->execute(['course_id' => $course_id]);
        return $stmt->fetchAll();
    }
}
