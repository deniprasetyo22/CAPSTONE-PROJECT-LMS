<?= $this->extend('layouts/admin_layout') ?>

<?= $this->section('title') ?>Add Course<?= $this->endSection() ?>

<?= $this->section('admin_content') ?>

<div>
    <!-- Breadcrumbs -->
    <div class="bg-gray-200 rounded-md px-4">
        <div class="breadcrumbs mb-6">
            <ul>
                <li><a href="<?= url_to('teacher_courses') ?>">Courses</a></li>
                <li class="font-semibold">Create Course</li>
            </ul>
        </div>
    </div>
    <div class="card card-lg border border-gray-300 shadow-lg w-full">
        <div class="card-body">
            <div class="flex justify-center border-b border-gray-300 pb-2 mb-4">
                <h2 class="card-title">Add Course</h2>
            </div>

            <?php if (session('errors')) : ?>
            <div class="mb-4 p-3 text-red-700 bg-red-100 border border-red-400 rounded">
                <?php foreach (session('errors') as $error) : ?>
                <p><?= $error ?></p>
                <?php endforeach ?>
            </div>
            <?php endif ?>

            <form action="<?= url_to('store_course') ?>" method="post" id="courseRegistrationForm">
                <fieldset class="mb-4">
                    <label for="name" class="fieldset-label text-black">Course Name</label>
                    <input type="text" name="name" data-pristine-required
                        data-pristine-required-message="Course Name is required"
                        class="input w-full <?= (session('errors.name')) ? 'border-red-500' : '' ?>"
                        placeholder="Course Name" value="<?= old('name') ?>">
                </fieldset>
                <fieldset class="mb-4">
                    <label class="label" for="code">
                        <span class="label-text">Course Code</span>
                    </label>
                    <input type="text" name="code" id="code"
                        class="input input-bordered w-full <?= (session('errors.code')) ? 'border-red-500' : '' ?>"
                        data-pristine-required data-pristine-required-message="Course Code is required"
                        placeholder="CS101" value="<?= old('code') ?>">
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

                <fieldset class="mb-4">
                    <label class="label" for="expected_duration">
                        <span class="label-text">Expected Duration (months)</span>
                    </label>
                    <input type="number" name="expected_duration" id="expected_duration"
                        class="input input-bordered w-full <?= (session('errors.expected_duration')) ? 'border-red-500' : '' ?>"
                        placeholder="6" data-pristine-required
                        data-pristine-required-message="Expected Duration is required"
                        value="<?= old('expected_duration') ?>">
                </fieldset>

                <fieldset class="mb-4">
                    <label class="label" for="level_course_id">
                        <span class="label-text">Level Course</span>
                    </label>
                    <select name="level_course_id" id="level_course_id"
                        class="select select-bordered w-full <?= (session('level_course_id')) ? 'border-red-500' : '' ?>"
                        data-pristine-required data-pristine-required-message="Level Course is required">
                        <option value="">-- Select Level --</option>
                        <?php foreach ($levelCourses as $level): ?>
                        <option value="<?= esc($level->id) ?>"
                            <?= old('level_course_id') == $level->id ? 'selected' : '' ?>>
                            <?= esc($level->name) ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
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