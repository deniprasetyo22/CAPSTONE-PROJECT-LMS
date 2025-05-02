<?= $this->extend('layouts/admin_layout') ?>

<?= $this->section('title') ?>Add Course Content<?= $this->endSection() ?>

<?= $this->section('admin_content') ?>

<div>
    <!-- Breadcrumbs -->
    <div class="bg-gray-200 rounded-md px-4">
        <div class="breadcrumbs mb-6">
            <ul>
                <li><a href="<?= url_to('teacher_courses') ?>">Courses</a></li>
                <li><a href="<?= url_to('show_course',$course_id) ?>">Course Details</a></li>
                <li class="font-semibold">Create Course Content</li>
            </ul>
        </div>
    </div>
    <div class="card card-lg border border-gray-300 shadow-lg w-full">
        <div class="card-body">
            <div class="mb-4 text-blue-500 hover:text-blue-600 hover:underline">
                <a href="<?= url_to('show_course', $course_id) ?>">
                    <i class="fa fa-arrow-left mr-2"></i>Back
                </a>
            </div>

            <div class="flex justify-center border-b border-gray-300 pb-2 mb-4">
                <h2 class="card-title">Add Course Content</h2>
            </div>

            <?php if (session('errors')) : ?>
            <div class="mb-4 p-3 text-red-700 bg-red-100 border border-red-400 rounded">
                <?php foreach (session('errors') as $error) : ?>
                <p><?= $error ?></p>
                <?php endforeach ?>
            </div>
            <?php endif ?>

            <form id="courseContentForm" action="<?= url_to('store_material', $course_id) ?>" method="post"
                enctype="multipart/form-data">
                <?php csrf_field() ?>

                <fieldset class="mb-4">
                    <legend class="fieldset-legend">Title</legend>
                    <input type="text" name="title" data-pristine-required
                        data-pristine-required-message="Title is required"
                        class="input w-full <?= (session('errors.title')) ? 'border-red-500' : '' ?>"
                        placeholder="Title" value="<?= old('title') ?>">
                </fieldset>

                <fieldset class="mb-4">
                    <legend class="fieldset-legend">File <span class="text-xs text-gray-500">(*max 5MB)</span></legend>
                    <input type="file" name="userfile" id="userfile" class="file-input file-input-bordered w-full"
                        data-pristine-required-message="Please choose file to upload" />
                    <div id="file-error" class="text-error text-sm mt-2 hidden"></div>
                    <div class="mt-4">
                        <iframe id="file-preview" class="w-full border rounded-xl shadow-md" height="500"
                            style="display: none;">
                        </iframe>
                    </div>
                </fieldset>

                <div class="flex justify-end">
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>



<script>
document.addEventListener("DOMContentLoaded", function() {
    var form = document.getElementById("courseContentForm");

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