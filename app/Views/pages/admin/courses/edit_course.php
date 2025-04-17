<?= $this->extend('layouts/main_layout') ?>

<?= $this->section('title') ?>Edit Course<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="w-full flex justify-center py-10 px-4">
    <div class="card card-lg border border-gray-300 shadow-lg w-full max-w-xl">
        <div class="card-body">
            <div class="flex justify-center border-b border-gray-300 pb-2 mb-4">
                <h2 class="card-title">Edit Course</h2>
            </div>

            <?php if (session('errors')) : ?>
                <div class="mb-4 p-3 text-red-700 bg-red-100 border border-red-400 rounded">
                    <?php foreach (session('errors') as $error) : ?>
                        <p><?= $error ?></p>
                    <?php endforeach ?>
                </div>
            <?php endif ?>

            <form action="<?= base_url('/courses/edit/' . $course->id) ?>" method="post" id="courseRegistrationForm">
                <?= csrf_field() ?>
                <input type="hidden" name="_method" value="PUT">
                <fieldset class="mb-4">
                    <label for="name" class="fieldset-label text-black">Course Name</label>
                    <input type="text" name="name"
                        data-pristine-required
                        data-pristine-required-message="Course Name is required"
                        class="input w-full <?= (session('errors.name')) ? 'border-red-500' : '' ?>"
                        placeholder="Course Name"
                        value="<?= old('name') ?? esc($course->name ?? '') ?>">
                </fieldset>
                <fieldset class="mb-4">
                    <label class="label" for="code">
                        <span class="label-text">Course Code</span>
                    </label>
                    <input type="text" name="code" id="code" class="input input-bordered w-full <?= (session('errors.code')) ? 'border-red-500' : '' ?>"
                        data-pristine-required
                        data-pristine-required-message="Course Code is required"
                        placeholder="CS101" value="<?= old('code') ?? esc($course->code) ?>">
                </fieldset>

                <fieldset class="mb-4">
                    <label class="label" for="description">
                        <span class="label-text">Description</span>
                    </label>
                    <textarea name="description" id="description" class="textarea textarea-bordered w-full <?= (session('errors.description')) ? 'border-red-500' : '' ?> " rows="3"
                        placeholder="Description of the course..."
                        data-pristine-required
                        data-pristine-required-message="Description is required"><?= old('description') ?? esc($course->description) ?></textarea>
                </fieldset>

                <fieldset class="mb-4">
                    <label class="label" for="enrollment_code">
                        <span class="label-text">Enrollment Code</span>
                    </label>
                    <input type="text" name="enrollment_code" id="enrollment_code" class="input input-bordered w-full <?= (session('errors.enrollment_code')) ? 'border-red-500' : '' ?>" placeholder="ENR-2025-001"
                        data-pristine-required
                        data-pristine-required-message="Enrollment Code is required"
                        value="<?= old('enrollment_code') ?? esc($course->enrollment_code) ?>">
                </fieldset>

                <fieldset class="mb-4">
                    <label class="label" for="expected_duration">
                        <span class="label-text">Expected Duration (months)</span>
                    </label>
                    <input type="number" name="expected_duration" id="expected_duration" class="input input-bordered w-full <?= (session('errors.expected_duration')) ? 'border-red-500' : '' ?>" placeholder="6"
                        data-pristine-required
                        data-pristine-required-message="Expected Duration is required"
                        value="<?= old('expected_duration') ?? esc($course->expected_duration) ?>">
                </fieldset>

                <fieldset class="mb-4">
                    <label class="label" for="level_course_id">
                        <span class="label-text">Level Course</span>
                    </label>
                    <select name="level_course_id" id="level_course_id" class="select select-bordered w-full <?= (session('level_course_id')) ? 'border-red-500' : '' ?>"
                        data-pristine-required
                        data-pristine-required-message="Level Course is required">
                        <option value="">-- Select Level --</option>
                        <?php foreach ($levelCourses as $level): ?>
                            <option value="<?= esc($level->id) ?>"
                                <?= (old('level_course_id') ?? $course->level_course_id ?? '') == $level->id ? 'selected' : '' ?>>
                                <?= esc($level->name) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </fieldset>

                <div class=" flex justify-end">
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