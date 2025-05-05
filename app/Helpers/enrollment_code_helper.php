<?php
function generate_enrollment_code(): string
{
    $courseModel = new \App\Models\CourseModel();
    $prefix = 'ENR';
    $year = date('Y');

    $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    $random = '';
    for ($i = 0; $i < 8; $i++) {
        $random .= $characters[random_int(0, strlen($characters) - 1)];
    }

    // Check if the generated code already exists in the database
    $course = $courseModel->where('enrollment_code', "{$prefix}-{$year}-{$random}")->first();
    if ($course) {
        // If it exists, generate a new code
        return generate_enrollment_code();
    }

    return "{$prefix}-{$year}-{$random}";
}
