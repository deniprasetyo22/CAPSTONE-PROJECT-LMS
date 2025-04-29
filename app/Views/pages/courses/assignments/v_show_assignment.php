<?= $this->extend('layouts/admin_layout') ?>

<?= $this->section('title') ?>
<?= $page_title ?>
<?= $this->endSection() ?>

<?= $this->section('admin_content') ?>

<!-- Breadcrumbs -->
<div class="bg-gray-200 rounded-md px-4">
    <div class="breadcrumbs mb-6">
        <ul>
            <li><a href="<?= url_to('lecturer_courses') ?>">Course</a></li>
            <li><a href="<?= url_to('course_detail', $course->id) ?>">Course Details</a></li>
            <li class="font-semibold">Assignment</li>
        </ul>
    </div>
</div>

<div class="mb-4">
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

<div class="overflow-x-auto">
    <h2 class="text-center text-2xl font-bold mb-4"><?= esc($assignment->title) ?></h2>
    <table class="table">
        <tbody>
            <tr>
                <th>Title</th>
                <td><?= esc($assignment->title) ?></td>
            </tr>
            <tr>
                <th>Description</th>
                <td><?= esc($assignment->description) ?></td>
            </tr>
            <tr>
                <th>Due Date</th>
                <td><?= esc($assignment->due_date) ?></td>
            </tr>
            <tr>
                <th class="flex items-top">File</th>
                <td>
                    <iframe src="<?= url_to('file_assignment', $assignment->course_id, $assignment->file_url) ?>"
                        class="w-full border rounded-xl shadow-md" height="500">
                    </iframe>
                </td>
            </tr>
            <?php if (in_groups('student')) : ?>
            <tr>
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
                                style="display: none;"></iframe>
                        </div>
                    </form>
                    <?php else : ?>
                    <iframe src="<?= url_to('submission_file', $assignment->id, $getAssignmentSubmission->file_name) ?>"
                        class="w-full border rounded-xl shadow-md" height="500"></iframe>

                    <div class="flex justify-end mt-2">
                        <button class="btn btn-sm btn-error text-white"
                            onclick="document.getElementById('deleteModal<?= $getAssignmentSubmission->id ?>').showModal()">
                            Delete
                        </button>
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
            <tr>
                <th class="flex items-top">Submission</th>
                <td>
                    <?php if (empty($getAllAssignmentSubmissions)) : ?>
                    <div class="text-center">No Submission</div>
                    <?php else : ?>
                    <?php foreach ($getAllAssignmentSubmissions as $submission) : ?>
                    <a href="<?= url_to('submission_file', $assignment->id, $submission->file_name) ?>" target="_blank">
                        <div class="flex items-center gap-2 mb-2 text-blue-600">
                            <i class="fa fa-file mr-2"></i>
                            <?= $submission->first_name?> <?= $submission->last_name?> - <?= $submission->file_name?>
                        </div>
                    </a>
                    <?php endforeach; ?>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endif; ?>

        </tbody>
    </table>


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