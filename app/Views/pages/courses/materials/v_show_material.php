<?php if (in_groups(['teacher', 'administrator'])) : ?>
    <?= $this->extend('layouts/admin_layout') ?>
<?php elseif (in_groups('student')) : ?>
    <?= $this->extend('layouts/main_layout') ?>
<?php endif ?>

<?= $this->section('title') ?>
<?= $page_title ?>
<?= $this->endSection() ?>

<?php if (in_groups(['teacher', 'administrator'])) : ?>
    <?= $this->section('admin_content') ?>
<?php elseif (in_groups('student')) : ?>
    <?= $this->section('content') ?>
<?php endif ?>

<div class="container mx-auto mb-4">

    <?php if (in_groups(['teacher', 'administrator'])) : ?>
        <div class="bg-gray-200 rounded-md px-4">
            <div class="breadcrumbs mb-4">
                <ul>
                    <?php if (in_groups('teacher')) : ?>
                        <li><a href="<?= url_to('teacher_courses') ?>">Courses</a></li>
                    <?php elseif (in_groups('administrator')) : ?>
                        <li><a href="<?= url_to('admin_courses') ?>">Courses</a></li>
                    <?php endif ?>
                    <li><a href="<?= url_to('show_course', $course->id) ?>">Detail Course</a></li>
                    <li class="font-semibold">Material</li>
                </ul>
            </div>
        </div>
    <?php endif ?>

    <?php if (session()->has('error')) : ?>
        <div class="alert alert-danger mb-4">
            <?= session()->getFlashdata('error') ?>
        </div>
    <?php endif ?>
    <?php if (session()->has('success')) : ?>
        <div class="alert alert-success mb-4">
            <?= session()->getFlashdata('success') ?>
        </div>
    <?php endif ?>

    <div class="card card-lg border border-gray-300 shadow-lg w-full p-6 mt-4">
        <?php if (in_groups('student')) : ?>
            <div class="mb-4 text-blue-500 hover:text-blue-600 hover:underline">
                <a href="<?= url_to('show_course', $course->id) ?>">
                    <i class="fa fa-arrow-left mr-2"></i>Back
                </a>
            </div>
        <?php endif ?>
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
            </div>
        </div>

        <?php if (in_groups('teacher')) : ?>
            <div class="flex justify-end mb-4">
                <a href="<?= url_to('add_file_material', $courseContent->id) ?>" class="btn bg-blue-200">
                    <i class="fa fa-plus mr-2"></i>Add Files
                </a>
            </div>
        <?php endif ?>

        <!-- PDF Viewer -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <?php foreach ($files as $file) : ?>
                <div class="w-full">
                    <div class="card bg-base-100 shadow-sm border border-gray-200">
                        <div class="card-body">
                            <div class="flex flex-row items-center justify-between">
                                <h2 class="card-title">
                                    <a href="<?= url_to('show_file_material', $courseContent->id, $file->file_url) ?>"
                                        target="_blank" class="hover:text-blue-600">
                                        <span class="text-sm font-semibold">
                                            <?= esc($file->file_name) ?>_<?= esc($file->file_url) ?>
                                        </span>
                                    </a>
                                </h2>
                                <div class="dropdown dropdown-end ml-auto">
                                    <label tabindex="0" class="btn btn-ghost btn-sm btn-circle">
                                        <i class="fas fa-ellipsis-v"></i>
                                    </label>
                                    <ul tabindex="0"
                                        class="dropdown-content menu menu-sm bg-base-100 shadow rounded-box w-40">
                                        <li>
                                            <a href="<?= url_to('show_file_material', $courseContent->id, $file->file_url) ?>"
                                                target="_blank">Open</a>
                                        </li>
                                        <?php if (in_groups('teacher')) : ?><li>
                                                <a href="<?= url_to('edit_file_material', $file->id) ?>">Edit</a>
                                            </li>
                                            <li>
                                                <button class="w-full text-left"
                                                    onclick="document.getElementById('deleteModalFile<?= $file->id ?>').showModal()">
                                                    Delete
                                                </button>
                                            </li>
                                        <?php endif ?>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Modal Delete -->
                    <dialog id="deleteModalFile<?= $file->id ?>" class="modal">
                        <div class="modal-box">
                            <h3 class="font-bold text-lg text-red-600">Delete Confirmation</h3>
                            <p class="py-4">Are you sure you want to delete this file?</p>
                            <div class="modal-action">
                                <form method="dialog">
                                    <button class="btn btn-error text-white">Cancel</button>
                                </form>
                                <form action="<?= url_to('delete_file_material', $file->id) ?>" method="post">
                                    <?= csrf_field() ?>
                                    <input type="hidden" name="_method" value="DELETE">
                                    <button type="submit" class="btn btn-success text-white">Yes, Delete</button>
                                </form>
                            </div>
                        </div>
                    </dialog>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

</div>

<?= $this->endSection() ?>