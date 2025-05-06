<?= $this->extend('layouts/main_layout') ?>

<?= $this->section('title') ?>
Edit Profile
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
        <?php if(session()->has('errors')) : ?>
        <div role="alert" class="alert alert-error">
            <?php foreach(session('errors') as $error) : ?> <span><i class="fa fa-xmark mr-2"></i> <?= $error ?></span>
            <?php endforeach ?>
        </div>
        <?php endif ?>
    </div>

    <div class="divider px-4 md:px-0 mb-10">
        <h1 class="text-2xl font-semibold divider-title">Edit Profile</h1>
    </div>

    <form id="profileForm" action="<?= url_to('update_student_profile') ?>" method="post" enctype="multipart/form-data"
        class="max-w-3xl mx-auto bg-base-100 p-6 border border-gray-200 rounded-lg">
        <input type="hidden" name="_method" value="PUT">
        <?= csrf_field() ?>

        <fieldset class="mb-4">
            <legend class="fieldset-legend" for="username">Username</legend>
            <input type="text" name="username" id="username" class="input w-full"
                value="<?= old('username', $student->username) ?>" required>
            <?php if (session('errors.username')): ?>
            <div class="text-red-500 text-sm mt-1"><?= esc(session('errors.username')) ?></div>
            <?php endif ?>
        </fieldset>

        <fieldset class="mb-4">
            <legend class="fieldset-legend" for="first_name">First Name</legend>
            <input type="text" name="first_name" id="first_name" class="input w-full"
                value="<?= old('first_name', $student->first_name) ?>" required>
            <?php if (session('errors.first_name')): ?>
            <div class="text-red-500 text-sm mt-1"><?= esc(session('errors.first_name')) ?></div>
            <?php endif ?>
        </fieldset>

        <fieldset class="mb-4">
            <legend class="fieldset-legend" for="last_name">Last Name</legend>
            <input type="text" name="last_name" id="last_name" class="input w-full"
                value="<?= old('last_name', $student->last_name) ?>" required>
            <?php if (session('errors.last_name')): ?>
            <div class="text-red-500 text-sm mt-1"><?= esc(session('errors.last_name')) ?></div>
            <?php endif ?>
        </fieldset>

        <fieldset class="mb-4">
            <legend class="fieldset-legend" for="email">Email</legend>
            <input type="email" name="email" id="email" class="input w-full"
                value="<?= old('email', $student->email) ?>" required>
            <?php if (session('errors.email')): ?>
            <div class="text-red-500 text-sm mt-1"><?= esc(session('errors.email')) ?></div>
            <?php endif ?>
        </fieldset>

        <fieldset class="mb-4">
            <legend class="fieldset-legend" for="password">Password <span class="text-xs text-gray-500">*(Leave
                    empty if
                    no changes)</span></legend>
            <input type="password" name="password" id="password" class="input w-full" value="" placeholder="Password">
            <?php if (session('errors.password')): ?>
            <div class="text-red-500 text-sm mt-1"><?= esc(session('errors.password')) ?></div>
            <?php endif ?>
        </fieldset>

        <fieldset class="mb-4">
            <legend class="fieldset-legend" for="phone">Phone</legend>
            <input type="text" name="phone" id="phone" class="input w-full"
                value="<?= old('phone', $student->phone) ?>">
            <?php if (session('errors.phone')): ?>
            <div class="text-red-500 text-sm mt-1"><?= esc(session('errors.phone')) ?></div>
            <?php endif ?>
        </fieldset>

        <fieldset class="mb-4">
            <legend class="fieldset-legend" for="sex">Gender</legend>
            <select name="sex" id="sex" class="select w-full" required>
                <option value="">-- Select Gender --</option>
                <option value="Male" <?= old('sex', $student->sex) == 'Male' ? 'selected' : '' ?>>Male</option>
                <option value="Female" <?= old('sex', $student->sex) == 'Female' ? 'selected' : '' ?>>Female</option>
            </select>
            <?php if (session('errors.sex')): ?>
            <div class="text-red-500 text-sm mt-1"><?= esc(session('errors.sex')) ?></div>
            <?php endif ?>
        </fieldset>

        <fieldset class="mb-4">
            <legend class="fieldset-legend" for="dob">Date of Birth</legend>
            <input type="date" name="dob" id="dob" class="input w-full" value="<?= old('dob', $student->dob) ?>"
                required>
            <?php if (session('errors.dob')): ?>
            <div class="text-red-500 text-sm mt-1"><?= esc(session('errors.dob')) ?></div>
            <?php endif ?>
        </fieldset>

        <fieldset class="mb-4">
            <legend class="fieldset-legend" for="address">Address</legend>
            <textarea name="address" id="address" class="textarea w-full" rows="5"
                required><?= old('address', $student->address) ?></textarea>
            <?php if (session('errors.address')): ?>
            <div class="text-red-500 text-sm mt-1"><?= esc(session('errors.address')) ?></div>
            <?php endif ?>
        </fieldset>

        <fieldset class="mb-4">
            <legend class="fieldset-legend" for="avatar">Profile Picture <span class="text-xs text-gray-500">*(max
                    5MB)</span></legend>
            <input type="file" accept="image/*" name="profile_picture" id="profile_picture" class="file-input w-full">
            <div id="preview" class="mt-2 flex justify-center">
                <img id="previewImage" src="" alt="Preview" class="w-full max-w-xs hidden" />
            </div>
            <div id="pictureError" class="text-red-500 text-sm mt-1"></div>
            <?php if (session('errors.profile_picture')): ?>
            <div class="text-red-500 text-sm mt-1"><?= esc(session('errors.profile_picture')) ?></div>
            <?php endif ?>
        </fieldset>

        <div class="mt-10">
            <button type="submit" class="btn btn-primary w-full">
                <i class="fa fa-save mr-2"></i>Save Changes
            </button>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('profileForm');

    const pristine = new Pristine(form, {
        classTo: 'mb-4',
        errorClass: 'is-invalid',
        successClass: 'is-valid',
        errorTextParent: 'mb-4',
        errorTextTag: 'div',
        errorTextClass: 'text-red-500 text-sm'
    });

    const profilePictureInput = document.getElementById('profile_picture');
    const previewImage = document.getElementById('previewImage');
    const pictureError = document.getElementById('pictureError');

    const maxFileSize = 5 * 1024 * 1024;
    const allowedExtensions = ["jpg", "jpeg", "png", "webp"];
    const allowedTypes = ["image/jpeg", "image/jpg", "image/png", "image/webp"];

    profilePictureInput.addEventListener('change', function() {
        const file = this.files[0];
        pictureError.textContent = '';
        previewImage.classList.add('hidden');
        previewImage.src = '';

        if (file) {
            const fileSize = file.size;
            const fileExtension = file.name.split('.').pop().toLowerCase();
            const fileType = file.type;

            if (fileSize > maxFileSize) {
                pictureError.textContent = 'File size exceeds the maximum limit of 5MB.';
                this.value = "";
                return;
            }

            if (!allowedExtensions.includes(fileExtension)) {
                pictureError.textContent = 'Only ' + allowedExtensions.join(', ') +
                    ' files are allowed.';
                this.value = "";
                return;
            }

            if (!allowedTypes.includes(fileType)) {
                pictureError.textContent = 'Only image files are allowed.';
                this.value = "";
                return;
            }

            const reader = new FileReader();
            reader.onload = function(e) {
                previewImage.src = e.target.result;
                previewImage.classList.remove('hidden');
            };
            reader.readAsDataURL(file);
        }
    });

    form.addEventListener('submit', function(e) {
        if (!pristine.validate()) {
            e.preventDefault();
        }
    });
});
</script>



<?= $this->endSection() ?>