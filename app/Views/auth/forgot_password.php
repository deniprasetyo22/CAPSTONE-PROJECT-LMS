<?= $this->extend('layouts/main_layout') ?>

<?= $this->section('title') ?>Forgot Password<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="container mx-auto flex justify-center py-10">
    <div class="md:w-1/2 w-full bg-white shadow-lg rounded-lg px-6 py-10">
        <h2 class="text-2xl font-semibold text-center mb-4">Forgot Password</h2>

        <p class="my-6"><?=lang('Auth.enterEmailForInstructions')?></p>

        <form action="<?= url_to('forgot') ?>" method="post">
            <?= csrf_field() ?>

            <fieldset class="form-group mb-4">
                <label for="email" class="fieldset-label text-black"><?=lang('Auth.emailAddress')?></label>
                <input type="email" class="input w-full <?php if (session('errors.email')) : ?>is-invalid<?php endif ?>"
                    name="email" aria-describedby="emailHelp" placeholder="<?=lang('Auth.email')?>">
                <div class="invalid-feedback">
                    <?= session('errors.email') ?>
                </div>
            </fieldset>

            <button type="submit" class="btn btn-primary btn-block"><?=lang('Auth.sendInstructions')?></button>
        </form>
    </div>

</div>


<?= $this->endSection() ?>