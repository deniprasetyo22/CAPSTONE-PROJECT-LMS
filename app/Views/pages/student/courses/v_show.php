<?= $this->extend('layouts/main_layout') ?>

<?= $this->section('title') ?>
<?= $page_title ?>
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
    </div>

    <!-- name of each tab group should be unique -->
    <div class="tabs tabs-box">
        <input type="radio" name="my_tabs_6" class="tab" aria-label="Tab 1" checked="checked" />
        <div class="tab-content bg-base-100 border-base-300 p-6">Tab content 1</div>

        <input type="radio" name="my_tabs_6" class="tab" aria-label="Tab 2" />
        <div class="tab-content bg-base-100 border-base-300 p-6">Tab content 2</div>
    </div>
</div>

<?= $this->endSection() ?>