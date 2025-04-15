<?= $this->extend('layouts/main_layout') ?>

<?= $this->section('title') ?>Registration<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="w-full flex justify-center py-10 px-4">
    <div class="card card-lg border border-gray-300 shadow-lg w-full max-w-xl">
        <div class="card-body">
            <div class="flex justify-center border-b border-gray-300 pb-2 mb-4">
                <h2 class="card-title">Registration</h2>
            </div>

            <?php if (session('errors')) : ?>
            <div class="mb-4 p-3 text-red-700 bg-red-100 border border-red-400 rounded">
                <?php foreach (session('errors') as $error) : ?>
                <p><?= $error ?></p>
                <?php endforeach ?>
            </div>
            <?php endif ?>

            <form action="<?= route_to('register') ?>" method="post" id="registrationForm">
                <?= csrf_field() ?>

                <fieldset class="mb-4">
                    <label for="fname" class="fieldset-label text-black">First Name</label>
                    <input type="text" name="fname" data-pristine-required
                        data-pristine-required-message="First Name is required"
                        class="input w-full <?= (session('errors.fname')) ? 'border-red-500' : '' ?>"
                        placeholder="First Name" value="<?= old('fname') ?>">
                </fieldset>

                <fieldset class="mb-4">
                    <label for="lname" class="fieldset-label text-black">Last Name</label>
                    <input type="text" name="lname" data-pristine-required
                        data-pristine-required-message="Full Name is required"
                        class="input w-full <?= (session('errors.lname')) ? 'border-red-500' : '' ?>"
                        placeholder="Last Name" value="<?= old('lname') ?>">
                </fieldset>

                <fieldset class="mb-4">
                    <label for="username" class="fieldset-label text-black">Username</label>
                    <input type="text" name="username" data-pristine-required
                        data-pristine-required-message="Username is required" data-pristine-minLength="3"
                        data-pristine-minLength-message="Username must be at least 3 characters"
                        class="input w-full <?= (session('errors.username')) ? 'border-red-500' : '' ?>"
                        placeholder="Username" value="<?= old('username') ?>">
                </fieldset>

                <fieldset class="mb-4">
                    <label for="email" class="fieldset-label text-black">Email</label>
                    <input type="email" name="email" data-pristine-required
                        data-pristine-required-message="Email is required"
                        class="input w-full <?= (session('errors.email')) ? 'border-red-500' : '' ?>"
                        placeholder="Email" value="<?= old('email') ?>">
                </fieldset>

                <fieldset class="mb-4">
                    <label for="phone" class="fieldset-label text-black">Phone</label>
                    <input type="text" name="phone" data-pristine-required
                        data-pristine-required-message="Phone is required"
                        class="input w-full <?= session('errors.phone') ? 'border-red-500' : '' ?>" placeholder="Phone"
                        value="<?= old('phone') ?>">
                </fieldset>

                <fieldset class="mb-4">
                    <label for="sex" class="fieldset-label text-black">Sex</label>
                    <select class="select w-full" name="sex" required data-pristine-required
                        data-pristine-required-message="Sex is required">
                        <option value="" disabled selected>-- Select Sex --</option>
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                    </select>
                </fieldset>

                <fieldset class="mb-4">
                    <label for="dob" class="fieldset-label text-black">Date of Birth</label>
                    <input type="date" name="dob" class="input w-full" required data-pristine-required
                        data-pristine-required-message="Date of Birth is required" value="<?= old('dob') ?>">
                </fieldset>

                <fieldset class="mb-4">
                    <label for="address" class="fieldset-label text-black">Address</label>
                    <textarea name="address" data-pristine-required data-pristine-required-message="Address is required"
                        class="textarea w-full <?= (session('errors.address')) ? 'border-red-500' : '' ?>"
                        placeholder="Address" value="<?= old('address') ?>"></textarea>
                </fieldset>

                <fieldset class="mb-4">
                    <label for="password" class="fieldset-label text-black">Password</label>
                    <input type="password" name="password" data-pristine-required
                        data-pristine-required-message="Password is required" data-pristine-minLength="8"
                        data-pristine-minLength-message="Password must be at least 8 characters"
                        class="input w-full <?= (session('errors.password')) ? 'border-red-500' : '' ?>"
                        placeholder="Password">
                </fieldset>

                <fieldset class="mb-4">
                    <label for="pass_confirm" class="fieldset-label text-black">Confirmation Password</label>
                    <input type="password" name="pass_confirm" data-pristine-required
                        data-pristine-required-message="Password is required" data-pristine-minLength="8"
                        data-pristine-minLength-message="Password must be at least 8 characters"
                        class="input w-full <?= (session('errors.pass_confirm')) ? 'border-red-500' : '' ?>"
                        placeholder="Confirmation Password">
                </fieldset>

                <button type="submit" class="btn btn-primary w-full">
                    Register
                </button>
            </form>

            <div class="mt-4 text-center">
                <p class="text-gray-600">Already have an account? <a href="<?= route_to('login') ?>"
                        class="text-indigo-600 hover:underline">Login</a></p>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('registrationForm');
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