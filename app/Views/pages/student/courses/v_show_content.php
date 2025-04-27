<?= $this->extend('layouts/main_layout') ?>

<?= $this->section('title') ?>
<?= esc($page_title) ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="max-w-4xl mx-auto px-4 py-8">
    <!-- Header -->
    <div class="flex items-start gap-4 mb-4">
        <div class="avatar avatar-placeholder">
            <div class="bg-primary text-neutral-content w-12 rounded-full">
                <i class="fas fa-file text-md"></i>
            </div>
        </div>
        <div>
            <h1 class="text-2xl font-semibold"><?= esc($courseContent->title) ?></h1>
            <p class="text-sm text-gray-500">
                <?= esc($course->name) ?> • <?= date('j M Y', strtotime($courseContent->created_at)) ?>
            </p>
        </div>
    </div>

    <!-- File Viewer -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <?php foreach ($files as $file): ?>
        <div class="card bg-base-100 shadow-sm border border-gray-200">
            <div class="card-body">
                <h2 class="card-title"><?= esc($file->file_name ?? 'File') ?></h2>
                <div class="card-actions justify-end">
                    <a href="<?= base_url('student/courses/file/' . esc($file->file_url)) ?>" target="_blank"
                        class="btn btn-sm btn-primary">
                        View
                    </a>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>

</div>

<?= $this->endSection() ?>