<?= $this->extend('layouts/admin_layout') ?>

<?= $this->section('title') ?>
<?= $page_title ?>
<?= $this->endSection() ?>

<?= $this->section('admin_content') ?>

<div>

    <!-- Breadcrumbs -->
    <div class="bg-gray-200 rounded-md px-4">
        <div class="breadcrumbs mb-6">
            <ul>
                <li><a href="<?= url_to('admin_dashboard') ?>">Dashboard</a></li>
                <li><a href="<?= url_to('users') ?>">Users</a></li>
                <li class="font-semibold">Edit User</li>
            </ul>
        </div>
    </div>

    <div class="mb-4">
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

    <div class="card card-lg border border-gray-300 shadow-lg w-full">
        <div class="card-body">
            <div>
                <a href="<?= url_to('users') ?>" class="link link-primary">
                    <i class="fa fa-arrow-left"></i> back
                </a>
            </div>

            <div class="flex justify-center border-b border-gray-300 pb-2 mb-4">
                <h2 class="card-title">Edit User</h2>
            </div>

            <form action="<?= url_to('update_user', $user->id) ?>" method="post" id="editForm">
                <?= csrf_field() ?>
                <input type="hidden" name="_method" value="PUT">

                <fieldset class="mb-4">
                    <label for="fname" class="fieldset-label text-black">First Name</label>
                    <input type="text" name="fname"
                        class="input w-full <?= (session('errors.fname')) ? 'border-red-500' : '' ?>"
                        value="<?= old('fname', $user->first_name) ?>" required>
                    <?php if(session('errors.fname')) : ?>
                    <span class="text-red-500 text-xs"><?= session('errors.fname') ?></span>
                    <?php endif ?>
                </fieldset>

                <fieldset class="mb-4">
                    <label for="lname" class="fieldset-label text-black">Last Name</label>
                    <input type="text" name="lname"
                        class="input w-full <?= (session('errors.lname')) ? 'border-red-500' : '' ?>"
                        value="<?= old('lname', $user->last_name) ?>" required>
                    <?php if(session('errors.lname')) : ?>
                    <span class="text-red-500 text-xs"><?= session('errors.lname') ?></span>
                    <?php endif ?>
                </fieldset>

                <fieldset class="mb-4">
                    <label for="username" class="fieldset-label text-black">Username</label>
                    <input type="text" name="username"
                        class="input w-full <?= (session('errors.username')) ? 'border-red-500' : '' ?>"
                        value="<?= old('username', $user->username) ?>" required>
                    <?php if(session('errors.username')) : ?>
                    <span class="text-red-500 text-xs"><?= session('errors.username') ?></span>
                    <?php endif ?>
                </fieldset>

                <fieldset class="mb-4">
                    <label for="email" class="fieldset-label text-black">Email</label>
                    <input type="email" name="email"
                        class="input w-full <?= (session('errors.email')) ? 'border-red-500' : '' ?>"
                        value="<?= old('email', $user->email) ?>" required>
                    <?php if(session('errors.email')) : ?>
                    <span class="text-red-500 text-xs"><?= session('errors.email') ?></span>
                    <?php endif ?>
                </fieldset>

                <fieldset class="mb-4">
                    <label for="password" class="fieldset-label text-black">Password <span
                            class="text-xs">(Optional)</span></label>
                    <input type="password" name="password"
                        class="input w-full <?= (session('errors.password')) ? 'border-red-500' : '' ?>"
                        value="<?= old('password', $user->password) ?>">
                    <?php if(session('errors.password')) : ?>
                    <span class="text-red-500 text-xs"><?= session('errors.password') ?></span>
                    <?php endif ?>
                </fieldset>

                <fieldset class="mb-4">
                    <label for="phone" class="fieldset-label text-black">Phone</label>
                    <input type="text" name="phone"
                        class="input w-full <?= session('errors.phone') ? 'border-red-500' : '' ?>"
                        value="<?= old('phone', $user->phone) ?>" required>
                    <?php if(session('errors.phone')) : ?>
                    <span class="text-red-500 text-xs"><?= session('errors.phone') ?></span>
                    <?php endif ?>
                </fieldset>

                <fieldset class="mb-4">
                    <label for="sex" class="fieldset-label text-black">Sex</label>
                    <select class="select w-full" name="sex" required>
                        <option value="Male" <?= old('sex', $user->sex) === 'Male' ? 'selected' : '' ?>>Male</option>
                        <option value="Female" <?= old('sex', $user->sex) === 'Female' ? 'selected' : '' ?>>Female
                        </option>
                    </select>
                    <?php if(session('errors.sex')) : ?>
                    <span class="text-red-500 text-xs"><?= session('errors.sex') ?></span>
                    <?php endif ?>
                </fieldset>

                <fieldset class="mb-4">
                    <label for="dob" class="fieldset-label text-black">Date of Birth</label>
                    <input type="date" name="dob" class="input w-full" value="<?= old('dob', $user->dob) ?>" required>
                    <?php if(session('errors.dob')) : ?>
                    <span class="text-red-500 text-xs"><?= session('errors.dob') ?></span>
                    <?php endif ?>
                </fieldset>

                <fieldset class="mb-4">
                    <label for="address" class="fieldset-label text-black">Address</label>
                    <textarea name="address"
                        class="textarea w-full <?= (session('errors.address')) ? 'border-red-500' : '' ?>"
                        required><?= old('address', $user->address) ?></textarea>
                    <?php if(session('errors.address')) : ?>
                    <span class="text-red-500 text-xs"><?= session('errors.address') ?></span>
                    <?php endif ?>
                </fieldset>

                <fieldset class="mb-4">
                    <label for="role" class="fieldset-label text-black">Role</label>
                    <select class="select w-full" name="role" required>
                        <option value="teacher" <?= old('role', $role) === 'teacher' ? 'selected' : '' ?>>
                            Teacher
                        </option>
                        <option value="student" <?= old('role', $role) === 'student' ? 'selected' : '' ?>>
                            Student
                        </option>
                    </select>
                    <?php if(session('errors.role')) : ?>
                    <span class="text-red-500 text-xs"><?= session('errors.role') ?></span>
                    <?php endif ?>
                </fieldset>

                <button type="submit" class="btn btn-primary w-full mt-4">
                    Update
                </button>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('editForm');
    const pristine = new Pristine(form, {
        classTo: 'mb-4',
        errorTextParent: 'mb-4',
        errorTextClass: 'text-red-500 text-sm'
    });

    form.addEventListener('submit', function(e) {
        if (!pristine.validate()) {
            e.preventDefault();
        }
    });
});
</script>

<?= $this->endSection() ?>