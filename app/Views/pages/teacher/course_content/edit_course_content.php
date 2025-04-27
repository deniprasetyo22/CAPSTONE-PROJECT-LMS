<?= $this->extend('layouts/admin_layout') ?>

<?= $this->section('title') ?>Edit Course Content<?= $this->endSection() ?>

<?= $this->section('admin_content') ?>

<div>
    <!-- Breadcrumbs -->
    <div class="bg-gray-200 rounded-md px-4">
        <div class="breadcrumbs mb-6">
            <ul>
                <li><a href="<?= url_to('admin_dashboard') ?>">Dashboard</a></li>
                <li><a href="<?= url_to('list_courses') ?>">Courses</a></li>
                <li class="font-semibold">Edit Course Content</li>
            </ul>
        </div>
    </div>
    <div class="card card-lg border border-gray-300 shadow-lg w-full">
        <div class="card-body">
            <div class="flex justify-center border-b border-gray-300 pb-2 mb-4">
                <h2 class="card-title">Edit Course Content</h2>
            </div>

            <?php if (session('errors')) : ?>
                <div class="mb-4 p-3 text-red-700 bg-red-100 border border-red-400 rounded">
                    <?php foreach (session('errors') as $error) : ?>
                        <p><?= $error ?></p>
                    <?php endforeach ?>
                </div>
            <?php endif ?>

            <form action="<?= base_url('/course-content/edit/' . $content_id) ?>" method="post" id="courseRegistrationForm">
                <?= csrf_field() ?>
                <input type="hidden" name="_method" value="PUT">
                <fieldset class="mb-4">
                    <label for="title" class="text-lg font-semibold text-black mb-2">Title</label>
                    <input type="text" name="title"
                        data-pristine-required
                        data-pristine-required-message="Title is required"
                        class="mt-4 input w-full <?= (session('errors.title')) ? 'border-red-500' : '' ?>"
                        placeholder="Title" value="<?= old('title') ?? esc($course_content->title) ?>">
                </fieldset>
                <div class="flex justify-end">
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?= $this->endSection() ?>