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
            <li class="font-semibold">Create Courses</li>
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
    <?php if(session()->has('errors')) : ?>
    <div role="alert" class="alert alert-error mb-4">
        <ul>
            <?php foreach (session()->getFlashdata('errors') as $error) : ?>
            <li><i class="fa fa-xmark mr-2"></i><?= esc($error) ?></li>
            <?php endforeach ?>
        </ul>
    </div>
    <?php endif ?>
</div>

<div class="card card-lg border border-gray-300 shadow-lg w-full p-4">
    <form id="assignmentForm" action="<?= url_to('store_assignment', $course->id) ?>" method="post"
        enctype="multipart/form-data" class="w-full space-y-2">
        <?= csrf_field(); ?>

        <fieldset class="fieldset">
            <legend class="fieldset-legend">Title</legend>
            <input type="text" name="title" class="input w-full" placeholder="Title" required>
        </fieldset>

        <fieldset class="fieldset">
            <legend class="fieldset-legend">Description</legend>
            <textarea name="description" class="textarea textarea-bordered w-full" placeholder="Description"
                required></textarea>
        </fieldset>

        <fieldset class="fieldset">
            <legend class="fieldset-legend">Due Date</legend>
            <input type="datetime-local" name="due_date" class="input w-full" required>
        </fieldset>

        <fieldset class="fieldset">
            <legend class="fieldset-legend">File <span class="text-xs text-gray-500">(*max 5MB)</span></legend>
            <input type="file" name="file" id="fileInput" class="file-input w-full" required>
        </fieldset>

        <div class="mt-4">
            <iframe id="file-preview" class="w-full border rounded-xl shadow-md" height="500" style="display: none;">
            </iframe>
        </div>

        <button type="submit" class="btn btn-primary w-full mt-4">
            Submit
        </button>
    </form>
</div>

<!-- Pristine.js -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('assignmentForm');
    const fileInput = document.getElementById('fileInput');
    const filePreview = document.getElementById('file-preview');

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

    form.addEventListener('submit', function(e) {
        const valid = pristine.validate();
        if (!valid) {
            e.preventDefault();
        }
    });
});
</script>

<?= $this->endSection() ?>