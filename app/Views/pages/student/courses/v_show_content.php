<?= $this->extend('layouts/main_layout') ?>

<?= $this->section('title') ?>
<?= $page_title ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="max-w-4xl mx-auto px-4 py-8">
    <!-- Header -->
    <div class="flex items-start gap-4 mb-4">
        <div class="avatar">
            <div class="w-12 rounded-full bg-primary text-white flex items-center justify-center">
                <i class="fas fa-file-pdf text-xl"></i>
            </div>
        </div>
        <div>
            <h1 class="text-2xl font-semibold"><?= esc($courseContent->title) ?></h1>
            <p class="text-sm text-gray-500">
                <?= esc($course->name) ?> • <?= date('j M Y', strtotime($courseContent->created_at)) ?>
            </p>
            <p class="text-sm text-gray-400 mt-1">By <?= esc($courseContent->created_by ?? 'Unknown') ?></p>
        </div>
    </div>

    <!-- PDF Viewer -->
    <div class="flex flex-col md:flex-row md:justify-between md:space-x-4 space-y-4 md:space-y-0">
        <div class="w-full md:w-1/2">
            <div class="card card-side bg-base-100 shadow-sm border border-gray-200">
                <div class="card-body">
                    <h2 class="card-title"><?= $courseContent->content_url ?></h2>
                    <div class="card-actions justify-end">
                        <a href="<?= base_url('student/courses/file/' . esc($courseContent->content_url)) ?>"
                            target="_blank" class="btn btn-sm btn-primary">
                            View
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="w-full md:w-1/2 border border-gray-300 rounded-md p-4">
            Test
        </div>
    </div>


</div>

<?= $this->endSection() ?>