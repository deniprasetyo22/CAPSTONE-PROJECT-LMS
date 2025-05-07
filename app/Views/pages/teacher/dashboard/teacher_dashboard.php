<?= $this->extend('layouts/admin_layout') ?>

<?= $this->section('title') ?>
<?= $page_title ?>
<?= $this->endSection() ?>

<?= $this->section('admin_content') ?>
<h1 class="text-3xl font-bold text-center text-primary mb-8">Teacher Dashboard</h1>
<p class="ml-4 text-lg font-semibold mb-2">Welcome, <?= $teacher->first_name . ' ' . $teacher->last_name ?>
</p>
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 p-4">
    <!-- Total Students -->
    <div class="card bg-base-100 shadow-xl border">
        <div class="card-body items-center text-center">
            <h2 class="card-title text-primary">Total Courses</h2>
            <p class="text-3xl font-bold text-neutral"><?= esc($total_courses ?? 0) ?></p>
        </div>
    </div>

    <!-- Total Teachers -->
    <div class="card bg-base-100 shadow-xl border">
        <div class="card-body items-center text-center">
            <h2 class="card-title text-primary">Total Assignments</h2>
            <p class="text-3xl font-bold text-neutral"><?= esc($total_assignments ?? 0) ?></p>
        </div>
    </div>

    <!-- Total Courses -->
    <div class="card bg-base-100 shadow-xl border">
        <div class="card-body items-center text-center">
            <h2 class="card-title text-primary">Total Discussions</h2>
            <p class="text-3xl font-bold text-neutral"><?= esc($total_discussions ?? 0) ?></p>
        </div>
    </div>
</div>

<!-- Charts Row -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-4 p-4">
    <!-- Bar Chart: Total Academic Records -->
    <div class="card bg-base-100 shadow-xl border">
        <div class="card-body">
            <h2 class="card-title text-primary text-center">Total Academic Teacher Courses Records</h2>
            <div class="flex justify-center mt-4">
                <canvas id="totalAcademicTeacherCoursesChart" class="w-full max-w-md" height="200"></canvas>
            </div>
        </div>
    </div>

    <!-- Pie Chart: Assignemnt Distribution by Status -->
    <div class="card bg-base-100 shadow-xl border">
        <div class="card-body">
            <h2 class="card-title text-primary text-center">Assignment Distribution by Status</h2>
            <div class="flex justify-center mt-4">
                <canvas id="assignmentChart" class="w-full max-w-md" height="200"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Line Chart: Enrollment Growth -->
<div class="p-4">
    <div class="card bg-base-100 shadow-xl border">
        <div class="card-body">
            <h2 id="titleGrowthChart" class="card-title text-primary text-center"></h2>
            <div class="flex justify-center mt-4">
                <canvas id="enrollmentGrowthChart" height="400"></canvas>
            </div>
        </div>
    </div>
</div>

<script>
const totalAcademicTeacherCourses = <?= $total_academic_teacher_courses ?>;
const enrollmentGrowthMonth = <?= $enrollment_growth_month ?>;
const assignmentByStatus = <?= $assignment_by_status ?>;
const currentYear = new Date().getFullYear();
document.getElementById('titleGrowthChart').textContent = `Enrollment Growth Month ${currentYear}`;
const userChart = new Chart(
    document.getElementById('assignmentChart'), {
        type: 'pie',
        data: assignmentByStatus,
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'right'
                }
            }
        }
    }
);
const academicRecordChart = new Chart(
    document.getElementById('totalAcademicTeacherCoursesChart'), {
        type: 'bar',
        data: totalAcademicTeacherCourses,
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                x: {
                    ticks: {
                        autoSkip: false, // Jangan otomatis skip label
                        maxRotation: 0, // Atur rotasi maksimal (0 = horizontal)
                        minRotation: 0 // Atur rotasi minimal
                    }
                }
            },
            plugins: {
                datalabels: {
                    display: false
                }
            },
        }
    }
);

const enrollmentChart = new Chart(
    document.getElementById('enrollmentGrowthChart'), {
        type: 'line',
        data: enrollmentGrowthMonth,
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    min: 0,
                    max: 20,
                },
                x: {
                    title: {
                        display: true,
                        text: 'Month'
                    },
                    ticks: {
                        autoSkip: false, // Jangan otomatis skip label
                        maxRotation: 0, // Atur rotasi maksimal (0 = horizontal)
                        minRotation: 0 // Atur rotasi minimal
                    }
                }
            },
            plugins: {
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return `Total Enrollment: ${context.raw}`;
                        }
                    }
                }
            }
        }
    }
);
</script>

<?= $this->endSection() ?>