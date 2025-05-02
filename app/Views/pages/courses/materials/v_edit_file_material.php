<?= $this->extend('layouts/admin_layout') ?>

<?= $this->section('title') ?>Edit Course Content File<?= $this->endSection() ?>

<?= $this->section('admin_content') ?>

<div>
    <!-- Breadcrumbs -->
    <div class="bg-gray-200 rounded-md px-4">
        <div class="breadcrumbs mb-6">
            <ul>
                <li><a href="<?= url_to('teacher_courses') ?>">Courses</a></li>
                <li><a href="<?= url_to('show_course', $courseContent->id) ?>">Detail Course</a></li>
                <li><a href="<?= url_to('show_material', $courseContent->course_id, $courseContent->id) ?>">Material</a>
                </li>
                <li class="font-semibold">Edit Material</li>
            </ul>
        </div>
    </div>
    <div class="card card-lg border border-gray-300 shadow-lg w-full">
        <div class="card-body">
            <div class="flex justify-center border-b border-gray-300 pb-2 mb-4">
                <h2 class="card-title">Edit File</h2>
            </div>

            <?php if (session('errors')) : ?>
            <div class="mb-4 p-3 text-red-700 bg-red-100 border border-red-400 rounded">
                <?php foreach (session('errors') as $error) : ?>
                <p><?= $error ?></p>
                <?php endforeach ?>
            </div>
            <?php endif ?>

            <form action="<?= url_to('update_file_material', $file->id) ?>" method="post" enctype="multipart/form-data"
                id="courseContentFileForm">

                <?= csrf_field() ?>
                <input type="hidden" name="_method" value="PUT">
                <div>
                    <h4 class="text-lg font-semibold mb-4">Upload Course Content File</h4>

                    <div class="form-control mb-4">
                        <label for="userfile" class="label">
                            <span class="label-text">Choose file (PDF - Max 5MB):</span>
                        </label>
                        <input type="file" name="userfile" id="userfile" class="file-input file-input-bordered w-full"
                            data-pristine-required-message="Please choose file to upload" />
                        <div id="file-error" class="text-error text-sm mt-2 hidden"></div>
                    </div>

                    <div class="mt-4">
                        <iframe id="file-preview" class="w-full border rounded-xl shadow-md" height="500"
                            style="display: none;">
                        </iframe>
                    </div>
                </div>


                <div class="flex justify-end">
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php
$existingFileUrl = url_to('show_file_material', $courseContent->id, $file->file_url); // kalau sudah ada symlink
?>

<script>
const existingFileUrl = <?= json_encode($existingFileUrl) ?>;

if (existingFileUrl) {
    const previewFrame = document.getElementById('file-preview');
    previewFrame.src = existingFileUrl;
    previewFrame.style.display = 'block';
}

document.addEventListener("DOMContentLoaded", function() {
    var form = document.getElementById("courseContentFileForm");

    var pristine = new Pristine(form, {
        classTo: 'mb-3',
        errorClass: 'is-invalid',
        successClass: 'is-valid',
        errorTextParent: 'mb-3',
        errorTextTag: 'div',
        errorTextClass: 'text-danger'
    });

    var fileInput = document.getElementById('userfile');
    var fileError = document.getElementById('file-error');
    var filePreview = document.getElementById('file-preview');

    var maxSize = 5 * 1024 * 1024;
    var allowedTypes = ['application/pdf'];
    var allowedExtensions = ['.pdf'];

    pristine.addValidator(fileInput, function(value) {
        filePreview.style.display = 'none';

        if (fileInput.files.length === 0) {
            fileError.textContent = "Please choose a file to upload";
            fileError.style.display = 'block';
            return false;
        }

        var file = fileInput.files[0];
        var validType = allowedTypes.includes(file.type);

        if (!validType) {
            var fileName = file.name.toLowerCase();
            validType = allowedExtensions.some(function(ext) {
                return fileName.endsWith(ext);
            });
        }

        if (!validType) {
            fileError.textContent = "File should be only PDF";
            fileError.style.display = 'block';
            return false;
        }

        if (file.size > maxSize) {
            fileError.textContent = "File size should not be more than 5 MB";
            fileError.style.display = 'block';
            return false;
        }

        var reader = new FileReader();
        reader.onload = function(e) {
            filePreview.src = e.target.result;
            filePreview.style.display = 'block';
        }
        reader.readAsDataURL(file);


        return true;
    }, "Validasi file gagal", 5, false);


    form.addEventListener('submit', function(e) {
        var valid = pristine.validate();
        if (!valid) {
            e.preventDefault();
        }
    });


    fileInput.addEventListener('change', function() {
        fileError.style.display = 'none';
        fileError.style.display = 'none';
        pristine.validate(fileInput);
    });
});
</script>

<?= $this->endSection() ?>