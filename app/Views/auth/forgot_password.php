<?= $this->extend('layouts/main_layout') ?>

<?= $this->section('title') ?>Forgot Password<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="container mx-auto flex justify-center py-10 px-4">
    <div class="card card-lg w-full max-w-xl border border-gray-300 shadow-lg">
        <div class="card-body">
            <div class="flex justify-center border-b border-gray-300 pb-2 mb-4">
                <h2 class="card-title">Forgot Password</h2>
            </div>

            <p class="mb-4"><?=lang('Auth.enterEmailForInstructions')?></p>

            <form action="<?= url_to('forgot') ?>" method="post">
                <?= csrf_field() ?>

                <fieldset class="form-group mb-4">
                    <label for="email" class="fieldset-label text-black"><?=lang('Auth.emailAddress')?></label>
                    <input type="email"
                        class="input w-full <?php if (session('errors.email')) : ?>is-invalid<?php endif ?>"
                        name="email" aria-describedby="emailHelp" placeholder="<?=lang('Auth.email')?>">
                    <div class="invalid-feedback">
                        <?= session('errors.email') ?>
                    </div>
                </fieldset>

                <button type="submit" class="btn btn-primary btn-block"><?=lang('Auth.sendInstructions')?></button>
            </form>
        </div>
    </div>

</div>


<?= $this->endSection() ?>