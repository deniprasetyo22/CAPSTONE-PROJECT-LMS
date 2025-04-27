<?= $this->extend('layouts/admin_layout') ?>

<?= $this->section('title') ?>
<?= $page_title ?>
<?= $this->endSection() ?>

<?= $this->section('admin_content') ?>

<div class="mb-4">
    <?php if (session()->has('error')) : ?>
    <div role="alert" class="alert alert-error mb-4">
        <span><i class="fa fa-xmark mr-2"></i><?= session('error') ?></span>
    </div>
    <?php endif ?>
</div>

<?php if (session()->has('success')) : ?>
<div role="alert" class="alert alert-success">
    <span><i class="fa fa-check mr-2"></i> <?= session('success') ?></span>
</div>
<?php endif ?>

<?= $this->endSection() ?>