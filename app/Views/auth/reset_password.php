<?= $this->extend('layouts/main_layout') ?>

<?= $this->section('title') ?>Reset Password<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="container mx-auto flex justify-center py-10">
    <div class="md:w-1/2 w-full bg-white shadow-lg rounded-lg px-6 py-10">
        <h2 class="text-2xl font-semibold text-center mb-4">Reset Password</h2>

        <?= view('Myth\Auth\Views\_message_block') ?>

        <p class="mb-4"><?=lang('Auth.enterCodeEmailPassword')?></p>

        <form action="<?= url_to('reset-password') ?>" method="post">
            <?= csrf_field() ?>

            <fieldset class="mb-4">
                <label for="token" class="label text-black">
                    <span class="label-text"><?=lang('Auth.token')?></span>
                </label>
                <input type="text" name="token"
                    class="input input-bordered w-full <?php if (session('errors.token')) : ?>input-error<?php endif ?>"
                    placeholder="<?=lang('Auth.token')?>" value="<?= old('token', $token ?? '') ?>">
                <?php if (session('errors.token')) : ?>
                <p class="text-error text-sm mt-1"><?= session('errors.token') ?></p>
                <?php endif ?>
            </fieldset>

            <fieldset class="mb-4">
                <label for="email" class="label text-black">
                    <span class="label-text"><?=lang('Auth.email')?></span>
                </label>
                <input type="email" name="email" aria-describedby="emailHelp" placeholder="<?=lang('Auth.email')?>"
                    value="<?= old('email') ?>"
                    class="input input-bordered w-full <?php if (session('errors.email')) : ?>input-error<?php endif ?>">
                <?php if (session('errors.email')) : ?>
                <p class="text-error text-sm mt-1"><?= session('errors.email') ?></p>
                <?php endif ?>
            </fieldset>

            <fieldset class="mb-4">
                <label for="password" class="label text-black">
                    <span class="label-text"><?=lang('Auth.newPassword')?></span>
                </label>
                <input type="password" name="password"
                    class="input input-bordered w-full <?php if (session('errors.password')) : ?>input-error<?php endif ?>">
                <?php if (session('errors.password')) : ?>
                <p class="text-error text-sm mt-1"><?= session('errors.password') ?></p>
                <?php endif ?>
            </fieldset>

            <fieldset class="mb-4">
                <label for="pass_confirm" class="label text-black">
                    <span class="label-text"><?= lang('Auth.newPasswordRepeat') ?></span>
                </label>
                <input type="password" name="pass_confirm"
                    class="input input-bordered w-full <?php if (session('errors.pass_confirm')) : ?>input-error<?php endif ?>">
                <?php if (session('errors.pass_confirm')) : ?>
                <p class="text-error text-sm mt-1"><?= session('errors.pass_confirm') ?></p>
                <?php endif ?>
            </fieldset>

            <button type="submit" class="btn btn-primary btn-block"><?=lang('Auth.resetPassword')?></button>
        </form>
    </div>
</div>

<?= $this->endSection() ?>