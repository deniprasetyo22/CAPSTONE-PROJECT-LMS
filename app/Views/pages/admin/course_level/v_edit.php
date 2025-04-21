<?= $this->extend('layouts/admin_layout') ?>

<?= $this->section('title') ?>
<?= $page_title ?>
<?= $this->endSection() ?>

<?= $this->section('admin_content') ?>

<!-- Breadcrumbs -->
<div class="bg-gray-200 rounded-md px-4">
    <div class="breadcrumbs mb-6">
        <ul>
            <li><a href="<?= url_to('admin_dashboard') ?>">Dashboard</a></li>
            <li><a href="<?= url_to('levels') ?>">Course Level</a></li>
            <li class="font-semibold">Edit Course Level</li>
        </ul>
    </div>
</div>

<div class="mb-4">
    <?php if(session()->has('error')) : ?>
    <div role="alert" class="alert alert-error mb-4">
        <span><i class="fa fa-xmark mr-2"></i><?= session('error') ?></span>
    </div>
    <?php endif ?>
</div>

<div class="card card-lg border border-gray-300 shadow-lg w-full">
    <div class="card-body">
        <div class="flex justify-center border-b border-gray-300 pb-2 mb-4">
            <h2 class="card-title">Edit Course Level</h2>
        </div>

        <?php if (session('errors')) : ?>
        <div role="alert" class="alert alert-error">
            <?php foreach (session('errors') as $error) : ?>
            <span><i class="fa fa-xmark mr-2"></i> <?= $error ?></span>
            <?php endforeach ?>
        </div>
        <?php endif ?>

        <form action="<?= url_to('update_level', $level->id) ?>" method="post" id="levelForm">
            <?= csrf_field() ?>
            <input type="hidden" name="_method" value="PUT">

            <fieldset class="mb-4">
                <label for="name" class="fieldset-label text-black">Name</label>
                <input type="text" name="name" data-pristine-required data-pristine-required-message="Name is required"
                    class="input w-full <?= (session('errors.name')) ? 'border-red-500' : '' ?>" placeholder="Name"
                    value="<?= old('name', $level->name) ?>">
                <?php if (session('errors.name')) : ?>
                <span class="text-red-500 text-sm"><?= session('errors.name') ?></span>
                <?php endif ?>
            </fieldset>

            <fieldset class="mb-4">
                <label for="description" class="fieldset-label text-black">Description</label>
                <textarea name="description" data-pristine-required
                    data-pristine-required-message="Description is required"
                    class="textarea w-full <?= (session('errors.description')) ? 'border-red-500' : '' ?>"
                    placeholder="Description"><?= old('description', $level->description) ?></textarea>
                <?php if (session('errors.description')) : ?>
                <span class="text-red-500 text-sm"><?= session('errors.description') ?></span>
                <?php endif ?>
            </fieldset>

            <button type="submit" class="btn btn-primary w-full mt-4">
                Submit
            </button>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('levelForm');
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