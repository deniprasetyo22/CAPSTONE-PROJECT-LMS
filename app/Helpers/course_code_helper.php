<?php
function generate_course_code(): string
{
    $courseModel = new \App\Models\CourseModel();
    // Generate 3 random uppercase letters
    $letters = substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0, 3);
    
    // Generate 3 random digits
    $numbers = rand(100, 999);
    
    // Combine letters and numbers
    $courseCode = $letters . $numbers;

    // Check if the generated code already exists in the database
    $course = $courseModel->where('code', $courseCode)->first();
    if ($course) {
        // If it exists, generate a new code
        return generate_course_code();
    }

    return $courseCode;
}