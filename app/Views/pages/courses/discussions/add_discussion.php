<?= $this->extend('layouts/admin_layout') ?>

<?= $this->section('title') ?>
<?= $page_title ?>
<?= $this->endSection() ?>

<?= $this->section('admin_content') ?>

<div>
    <!-- Breadcrumbs -->
    <div class="bg-gray-200 rounded-md px-4">
        <div class="breadcrumbs mb-6">
            <ul>
                <ul>
                    <li><a href="<?= url_to('show_course', $discussion->course_id) ?>">Courses</a></li>
                    <li class="font-semibold">Create Discussions</li>
                </ul>
            </ul>
        </div>
    </div>
    <div class="card card-lg border border-gray-300 shadow-lg w-full">
        <div class="card-body">
            <div class="flex justify-center border-b border-gray-300 pb-2 mb-4">
                <h2 class="card-title">Add Discussion</h2>
            </div>

            <?php if (session('errors')) : ?>
            <div class="mb-4 p-3 text-red-700 bg-red-100 border border-red-400 rounded">
                <?php foreach (session('errors') as $error) : ?>
                <p><?= $error ?></p>
                <?php endforeach ?>
            </div>
            <?php endif ?>

            <form action="<?= url_to('store_discussion', $course_id) ?>" method="post" id="courseRegistrationForm">
                <fieldset class="mb-4">
                    <label for="topic" class="fieldset-label text-black">Topic</label>
                    <input type="text" name="topic" data-pristine-required
                        data-pristine-required-message="Topic is required"
                        class="input w-full <?= (session('errors.topic')) ? 'border-red-500' : '' ?>"
                        placeholder="Topic" value="<?= old('topic') ?>">
                </fieldset>

                <fieldset class="mb-4">
                    <label class="label" for="description">
                        <span class="label-text">Description</span>
                    </label>
                    <textarea name="description" id="description"
                        class="textarea textarea-bordered w-full <?= (session('errors.description')) ? 'border-red-500' : '' ?> "
                        rows="3" placeholder="Description of the course..." data-pristine-required
                        data-pristine-required-message="Description is required"
                        value="<?= old('description') ?>"></textarea>
                </fieldset>

                <div class="flex justify-end">
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>



<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('courseRegistrationForm');
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