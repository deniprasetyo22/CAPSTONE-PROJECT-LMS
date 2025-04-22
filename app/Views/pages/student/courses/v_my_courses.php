<?= $this->extend('layouts/main_layout') ?>

<?= $this->section('title') ?>
<?= $page_title ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="container mx-auto mb-4">

    <div class="p-4 md:px-0">
        <?php if(session()->has('success')) : ?>
        <div role="alert" class="alert alert-success">
            <span><i class="fa fa-check mr-2"></i> <?= session('success') ?></span>
        </div>
        <?php endif ?>
        <?php if(session()->has('error')) : ?>
        <div role="alert" class="alert alert-error">
            <span><i class="fa fa-xmark mr-2"></i> <?= session('error') ?></span>
        </div>
        <?php endif ?>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 p-4 md:px-0">
        <?php foreach ($myCourses as $myCourse): ?>
        <div class="card bg-base-100 shadow-md border border-gray-200">
            <figure class="py-20 bg-blue-200">
                <span class="text-xl font-semibold"><?= esc($myCourse->name) ?></span>
            </figure>
            <div class="card-body">
                <h2 class="card-title text-lg font-semibold text-primary">
                    <?= esc($myCourse->code) ?> - <?= esc($myCourse->name) ?>
                </h2>
                <p class="text-sm text-gray-600"><?= esc($myCourse->description) ?></p>
                <div class="mt-4 text-sm text-gray-500">
                    <p><strong>Duration:</strong> <?= esc($myCourse->expected_duration) ?> months</p>
                    <p><strong>Level:</strong> <?= esc($myCourse->levelName) ?></p>
                </div>
                <div class="card-actions justify-end mt-4">
                    <a href="">
                        <button class="btn btn-sm btn-primary">View</button>
                    </a>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>

<?= $this->endSection() ?>