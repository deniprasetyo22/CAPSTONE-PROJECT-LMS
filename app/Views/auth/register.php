<?= $this->extend('layouts/main_layout') ?>

<?= $this->section('title') ?>Registrasi<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="w-full flex justify-center py-10">
    <div class="md:w-1/3 w-full bg-white shadow-lg rounded-lg p-6">
        <h2 class="text-2xl font-semibold text-center mb-4">Registrasi</h2>

        <?php if (session('errors')) : ?>
        <div class="mb-4 p-3 text-red-700 bg-red-100 border border-red-400 rounded">
            <?php foreach (session('errors') as $error) : ?>
            <p><?= $error ?></p>
            <?php endforeach ?>
        </div>
        <?php endif ?>

        <form action="<?= route_to('register') ?>" method="post">
            <?= csrf_field() ?>

            <!-- <div class="mb-4">
                <label for="full_name" class="block text-sm font-medium text-gray-700">Full Name</label>
                <input type="text" name="full_name"
                    class="mt-1 block w-full px-3 py-2 border rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                    placeholder="Full Name" value="<?= old('full_name') ?>">
            </div> -->

            <fieldset class="mb-4">
                <label for="username" class="fieldset-label text-black">Username</label>
                <input type="text" name="username"
                    class="input w-full <?php if (session('errors.username')) echo 'border-red-500'; ?>"
                    placeholder="Username" value="<?= old('username') ?>">
            </fieldset>

            <fieldset class="mb-4">
                <label for="email" class="fieldset-label text-black">Email</label>
                <input type="email" name="email"
                    class="input w-full <?php if (session('errors.email')) echo 'border-red-500'; ?>"
                    placeholder="Email" value="<?= old('email') ?>">
            </fieldset>

            <fieldset class="mb-4">
                <label for="password" class="fieldset-label text-black">Password</label>
                <input type="password" name="password"
                    class="input w-full <?php if (session('errors.password')) echo 'border-red-500'; ?>"
                    placeholder="Password">
            </fieldset>

            <fieldset class="mb-4">
                <label for="pass_confirm" class="fieldset-label text-black">Konfirmasi Password</label>
                <input type="password" name="pass_confirm"
                    class="input w-full <?php if (session('errors.pass_confirm')) echo 'border-red-500'; ?>"
                    placeholder="Konfirmasi Password">
            </fieldset>

            <button type="submit" class="btn btn-primary w-full">
                Daftar
            </button>
        </form>

        <div class="mt-4 text-center">
            <p class="text-gray-600">Sudah punya akun? <a href="<?= route_to('login') ?>"
                    class="text-indigo-600 hover:underline">Login</a></p>
        </div>
    </div>
</div>

<?= $this->endSection() ?>