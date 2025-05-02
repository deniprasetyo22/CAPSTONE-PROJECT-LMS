<?php if (in_groups('teacher')) : ?>
<?= $this->extend('layouts/admin_layout') ?>
<?php elseif (in_groups('student')) : ?>
<?= $this->extend('layouts/main_layout') ?>
<?php endif ?>

<?= $this->section('title') ?>
<?= $page_title ?>
<?= $this->endSection() ?>

<?php if (in_groups('teacher')) : ?>
<?= $this->section('admin_content') ?>
<?php elseif (in_groups('student')) : ?>
<?= $this->section('content') ?>
<?php endif ?>

<div class="container mx-auto mb-4">

    <?php if (in_groups('teacher')) : ?>
    <div class="bg-gray-200 rounded-md px-4">
        <div class="breadcrumbs mb-6">
            <ul>
                <li><a href="<?= url_to('teacher_courses') ?>">Course</a></li>
                <li><a href="<?= url_to('show_course', $course->id) ?>">Course Details</a></li>
                <li class="font-semibold">Assignment</li>
            </ul>
        </div>
    </div>
    <?php endif ?>

    <div class="my-4">
        <?php if (session()->has('error')) : ?>
        <div role="alert" class="alert alert-error mb-4">
            <span><i class="fa fa-xmark mr-2"></i><?= session('error') ?></span>
        </div>
        <?php endif ?>
        <?php if (session()->has('success')) : ?>
        <div role="alert" class="alert alert-success">
            <span><i class="fa fa-check mr-2"></i> <?= session('success') ?></span>
        </div>
        <?php endif ?>
        <?php if (session()->has('errors')) : ?>
        <div role="alert" class="alert alert-error mb-4">
            <ul>
                <?php foreach (session()->getFlashdata('errors') as $error) : ?>
                <li><i class="fa fa-xmark mr-2"></i><?= esc($error) ?></li>
                <?php endforeach ?>
            </ul>
        </div>
        <?php endif ?>
    </div>

    <div class="overflow-x-auto bg-white rounded-box border border-gray-300 shadow-lg p-6">
        <div class="mb-4 text-blue-500 hover:text-blue-600 hover:underline">
            <a href="<?= url_to('show_course', $assignment->course_id) ?>">
                <i class="fa fa-arrow-left mr-2"></i>Back
            </a>
        </div>
        <h2 class="text-center text-2xl font-bold mb-4"><?= esc($assignment->title) ?></h2>

        <table class="table mb-10">
            <tbody>
                <tr class="hover:bg-gray-100">
                    <th>Title</th>
                    <td><?= esc($assignment->title) ?></td>
                </tr>
                <tr class="hover:bg-gray-100">
                    <th>Description</th>
                    <td><?= esc($assignment->description) ?></td>
                </tr>
                <tr class="hover:bg-gray-100">
                    <th>Due Date</th>
                    <td><?= esc($assignment->due_date) ?></td>
                </tr>
                <tr class="hover:bg-gray-100">
                    <th>File</th>
                    <td class="flex justify-between items-center">
                        <a href="<?= url_to('file_assignment', $assignment->course_id, $assignment->file_url) ?>"
                            class="text-blue-600 hover:underline" target="_blank">
                            <i class="fa fa-file mr-2"></i> <?= esc($assignment->file_url) ?>
                        </a>

                        <div class="dropdown dropdown-end ml-auto">
                            <label tabindex="0" class="btn btn-ghost btn-sm btn-circle">
                                <i class="fas fa-ellipsis-v"></i>
                            </label>
                            <ul tabindex="0" class="dropdown-content menu menu-sm bg-base-100 shadow rounded-box w-40">
                                <li>
                                    <a href="<?= url_to('file_assignment', $assignment->course_id, $assignment->file_url) ?>"
                                        target="_blank">
                                        Open
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </td>
                </tr>

                <?php if (in_groups('student')) : ?>
                <tr class="hover:bg-gray-100">
                    <th>Submission</th>
                    <td>
                        <?php if ($getAssignmentSubmission === null) : ?>
                        <form action="<?= url_to('submit_assignment', $assignment->id) ?>" method="post"
                            enctype="multipart/form-data" id="submissionForm" class="mt-4">
                            <?= csrf_field() ?>
                            <div class="flex items-center gap-4">
                                <fieldset class="fieldset flex-1">
                                    <input type="file" name="file" id="fileInput" class="file-input w-full" required>
                                </fieldset>
                                <button type="submit" class="btn btn-primary whitespace-nowrap">Submit</button>
                            </div>
                            <div class="mt-4">
                                <iframe id="submission-preview" class="w-full border rounded-xl shadow-md" height="500"
                                    style="display: none;">
                                </iframe>
                            </div>
                        </form>
                        <?php else : ?>
                        <div class="flex justify-between items-center">
                            <a href="<?= url_to('submission_file', $assignment->id, $getAssignmentSubmission->file_name) ?>"
                                target="_blank" class="text-blue-600 hover:underline flex items-center"
                                id="submission-preview">
                                <i class="fa fa-file mr-2"></i>
                                <span><?= esc($getAssignmentSubmission->file_name) ?></span>
                            </a>

                            <div class="dropdown dropdown-end ml-auto">
                                <div tabindex="0" role="button" class="btn btn-ghost btn-sm btn-circle">
                                    <i class="fas fa-ellipsis-v text-gray-600"></i>
                                </div>
                                <ul tabindex="0"
                                    class="dropdown-content menu menu-sm bg-base-100 shadow rounded-box w-40">
                                    <li>
                                        <a href="<?= url_to('submission_file', $assignment->id, $getAssignmentSubmission->file_name) ?>"
                                            target="_blank">
                                            Open
                                        </a>
                                        <button
                                            onclick="document.getElementById('deleteModal<?= $getAssignmentSubmission->id ?>').showModal()">
                                            Delete
                                        </button>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <dialog id="deleteModal<?= $getAssignmentSubmission->id ?>" class="modal">
                            <div class="modal-box">
                                <h3 class="font-bold text-lg text-red-600">Delete Confirmation</h3>
                                <p class="py-4">Are you sure you want to delete this submission?</p>
                                <div class="modal-action">
                                    <form method="dialog">
                                        <button class="btn btn-error text-white">Cancel</button>
                                    </form>
                                    <form action="<?= url_to('delete_submission', $getAssignmentSubmission->id) ?>"
                                        method="post">
                                        <?= csrf_field() ?>
                                        <input type="hidden" name="_method" value="DELETE">
                                        <button type="submit" class="btn btn-success text-white">Yes, Delete</button>
                                    </form>
                                </div>
                            </div>
                        </dialog>
                        <?php endif; ?>
                    </td>
                </tr>

                <?php elseif (in_groups('teacher')) : ?>
                <tr class="hover:bg-gray-100">
                    <th class="align-top">Submission</th>
                    <td>
                        <?php if (empty($getAllAssignmentSubmissions)) : ?>
                        <div class="text-center">No Submission</div>
                        <?php else : ?>
                        <div class="overflow-x-auto">
                            <table class="table">
                                <thead class="bg-gray-100">
                                    <tr>
                                        <th>Student Name</th>
                                        <th>File</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($getAllAssignmentSubmissions as $submission) : ?>
                                    <tr class="hover:bg-gray-100">
                                        <td><?= $submission->first_name ?> <?= $submission->last_name ?></td>
                                        <td>
                                            <a href="<?= url_to('submission_file', $assignment->id, $submission->file_name) ?>"
                                                class="text-blue-600 hover:underline" target="_blank">
                                                <i class="fa fa-file mr-2"></i> <?= $submission->file_name ?>
                                            </a>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>


<script>
document.addEventListener('DOMContentLoaded', function() {
    const filePreview = document.getElementById('submission-preview');
    const form = document.getElementById('submissionForm');
    const fileInput = document.getElementById('fileInput');

    const pristine = new Pristine(form, {
        classTo: 'fieldset',
        errorClass: 'has-error',
        successClass: 'has-success',
        errorTextParent: 'fieldset',
        errorTextTag: 'div',
        errorTextClass: 'text-red-500 text-xs'
    });

    pristine.addValidator(fileInput, function() {
        if (fileInput.files.length === 0) return false;
        const file = fileInput.files[0];
        return file.size <= 5 * 1024 * 1024; // 5MB
    }, "File must be 5MB or smaller.");

    fileInput.addEventListener('change', function() {
        if (fileInput.files.length > 0) {
            const file = fileInput.files[0];
            const reader = new FileReader();
            reader.onload = function(e) {
                filePreview.src = e.target.result;
                filePreview.style.display = 'block';
            }
            reader.readAsDataURL(file);
        } else {
            filePreview.src = '';
            filePreview.style.display = 'none';
        }
    });
});
</script>

<?= $this->endSection() ?>