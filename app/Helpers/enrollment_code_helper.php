<?php
function generate_enrollment_code(): string
{
    $prefix = 'ENR';
    $year = date('Y');

    $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    $random = '';
    for ($i = 0; $i < 8; $i++) {
        $random .= $characters[random_int(0, strlen($characters) - 1)];
    }

    return "{$prefix}-{$year}-{$random}";
}
