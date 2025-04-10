<?= $this->extend('layouts/main_layout') ?>

<?= $this->section('title') ?>Login<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="container mx-auto flex justify-center py-10">
    <div class="md:w-1/3 w-full bg-white shadow-lg rounded-lg p-6">
        <h2 class="text-2xl font-semibold text-center mb-4">Login</h2>

        <?php if (session('error') !== null) : ?>
        <div class="mb-4 p-3 text-red-700 bg-red-100 border border-red-400 rounded">
            <?= session('error') ?>
        </div>
        <?php endif ?>

        <?php if (session('message') !== null) : ?>
        <div class="mb-4 p-3 text-green-700 bg-green-100 border border-green-400 rounded">
            <?= session('message') ?>
        </div>
        <?php endif ?>

        <form action="<?= route_to('login') ?>" method="post">
            <?= csrf_field() ?>

            <fieldset class="mb-4">
                <label for="login" class="fieldset-label text-black">Email atau Username</label>
                <input type="text" name="login"
                    class="input w-full <?php if (session('errors.login')) echo 'border-red-500'; ?>"
                    placeholder="Email atau Username" value="<?= old('login') ?>">
                <?php if (session('errors.login')) : ?>
                <p class="mt-1 text-sm text-red-600"> <?= session('errors.login') ?> </p>
                <?php endif ?>
            </fieldset>

            <fieldset class="mb-4">
                <label for="password" class="fieldset-label text-black">Password</label>
                <input type="password" name="password"
                    class="input w-full <?php if (session('errors.password')) echo 'border-red-500'; ?>"
                    placeholder="Password">
                <?php if (session('errors.password')) : ?>
                <p class="mt-1 text-sm text-red-600"> <?= session('errors.password') ?> </p>
                <?php endif ?>
            </fieldset>

            <fieldset class="mb-4">
                <label class="fieldset-label text-black text-sm">
                    <input type="checkbox" class="checkbox checkbox-primary checkbox-xs" name="remember"
                        id="remember" />
                    Remember me
                </label>
            </fieldset>

            <button type="submit" class="btn btn-primary w-full">Login</button>
        </form>

        <div class="text-center mt-4">
            <a href="<?= route_to('forgot') ?>" class="text-indigo-600 hover:underline">Forgot Password?</a>
        </div>

        <div class="mt-4 text-center">
            <p class="text-gray-600">Don't have an account? <a href="<?= route_to('register') ?>"
                    class="text-indigo-600 hover:underline">Register</a></p>
        </div>
    </div>

</div>


<?= $this->endSection() ?>