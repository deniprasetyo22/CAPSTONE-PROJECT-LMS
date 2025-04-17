<?= $this->extend('layouts/main_layout') ?>

<?= $this->section('title') ?>List Courses<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 p-6">
    <?php foreach ($courses as $course): ?>
        <div class="card bg-base-100 shadow-md border border-gray-200">
            <div class="card-body">
                <h2 class="card-title text-lg font-semibold text-primary"><?= esc($course->code) ?> - <?= esc($course->name) ?></h2>
                <p class="text-sm text-gray-600">
                    <?= esc($course->description) ?>
                </p>
                <div class="mt-4 text-sm text-gray-500">
                    <p><strong>Duration:</strong> <?= esc($course->expected_duration) ?> months</p>
                    <p><strong>Level:</strong> <?= esc($course->levelName) ?></p>
                </div>
                <div class="card-actions justify-end mt-4">
                    <button class="btn btn-sm btn-primary">Enroll Now</button>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<?= $this->endSection() ?>