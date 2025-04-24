<?= $this->extend('layouts/admin_layout') ?>

<?= $this->section('title') ?>
<?= $page_title ?>
<?= $this->endSection() ?>

<?= $this->section('admin_content') ?>

<div class="container mx-auto mb-4">

    <div class="p-4 md:px-0">
        <?php if (session()->has('success')) : ?>
            <div role="alert" class="alert alert-success">
                <span><i class="fa fa-check mr-2"></i> <?= session('success') ?></span>
            </div>
        <?php endif ?>
        <?php if (session()->has('error')) : ?>
            <div role="alert" class="alert alert-error">
                <span><i class="fa fa-xmark mr-2"></i> <?= session('error') ?></span>
            </div>
        <?php endif ?>
    </div>
    <div class="flex justify-end mb-4">
        <a href="<?= site_url('courses/add') ?>" class="btn bg-blue-200">
            <i class="fa fa-plus mr-2"></i>Add Course
        </a>
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
                        <a href="<?= site_url('/courses/detail/' . $myCourse->course_id) ?>" class="btn btn-primary btn-sm">
                            <i class="fa-solid fa-circle-info"></i>
                        </a>
                        <a href="<?= site_url('courses/edit/' . $myCourse->course_id) ?>" class="btn btn-warning btn-sm">
                            <i class="fa fa-edit text-white"></i>
                        </a>
                        <button type="button" class="btn btn-error btn-sm text-white"
                            onclick="document.getElementById('deleteModal<?= $myCourse->course_id ?>').showModal()">
                            <i class="fa fa-trash"></i>
                        </button>
                        <dialog id="deleteModal<?= $myCourse->course_id ?>" class="modal">
                            <div class="modal-box">
                                <h3 class="font-bold text-lg text-red-600">Delete Confirmation</h3>
                                <p class="py-4">Are you sure you want to delete this course?</p>
                                <div class="modal-action">
                                    <form method="dialog">
                                        <button class="btn btn-error text-white">Cancel</button>
                                    </form>
                                    <form action="<?= site_url('courses/delete/' . $myCourse->course_id) ?>" method="post">
                                        <?= csrf_field() ?>
                                        <input type="hidden" name="_method" value="DELETE">
                                        <button type="submit" class="btn btn-success text-white">Yes, Delete</button>
                                    </form>
                                </div>
                            </div>
                        </dialog>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<?= $this->endSection() ?>