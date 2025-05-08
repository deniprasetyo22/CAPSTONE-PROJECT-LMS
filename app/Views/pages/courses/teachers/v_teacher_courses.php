<?= $this->extend('layouts/admin_layout') ?>

<?= $this->section('title') ?>
<?= $page_title ?>
<?= $this->endSection() ?>

<?= $this->section('admin_content') ?>

<div class="container mx-auto mb-4">

    <div class="bg-gray-200 rounded-md px-4">
        <div class="breadcrumbs">
            <ul>
                <li class="font-semibold">Courses</li>
            </ul>
        </div>
    </div>

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

    <form action="<?= $baseUrl ?>" method="get" class="space-y-4 px-4 md:px-0">

        <div class="w-full">
            <div class="flex rounded-md shadow-sm">
                <input type="text" name="search" value="<?= $params->search ?>"
                    class="input input-bordered w-full rounded-l-md focus:outline-none"
                    placeholder="Search by Code or Name">
                <button type="submit" class="btn btn-primary rounded-r-md">Search</button>
            </div>
        </div>

        <div class="flex flex-wrap gap-5">
            <div class="w-full md:w-2/10">
                <select name="level" class="select select-bordered w-full" onchange="this.form.submit()">
                    <option value="">All Level</option>
                    <?php foreach ($level as $l): ?>
                    <option value="<?= $l->id ?>" <?= ($params->level == $l->id) ? 'selected' : '' ?>>
                        <?= ucfirst($l->name) ?>
                    </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="w-full md:w-2/10">
                <select name="perPage" class="select select-bordered w-full" onchange="this.form.submit()">
                    <?php foreach ([4, 8, 12, 36] as $perPage): ?>
                    <option value="<?= $perPage ?>" <?= ($params->perPage == $perPage) ? 'selected' : '' ?>>
                        <?= $perPage ?> / page
                    </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="w-full md:w-2/10">
                <select name="sort" class="select select-bordered w-full" onchange="this.form.submit()">
                    <option value="id">Sort By</option>
                    <option value="code" <?= ($params->sort == 'code') ? 'selected' : '' ?>>Code</option>
                    <option value="name" <?= ($params->sort == 'name') ? 'selected' : '' ?>>name</option>
                </select>
            </div>

            <div class="w-full md:w-2/10">
                <select name="order" class="select select-bordered w-full" onchange="this.form.submit()">
                    <option value="asc" <?= ($params->order == 'asc') ? 'selected' : '' ?>>Ascending</option>
                    <option value="desc" <?= ($params->order == 'desc') ? 'selected' : '' ?>>Descending</option>
                </select>
            </div>

            <div class="w-full md:flex-1">
                <a href="<?= $params->getResetUrl($baseUrl) ?>" class="btn btn-secondary w-full">
                    Reset
                </a>
            </div>
        </div>
    </form>

    <div class="flex flex-wrap gap-5 px-4 md:px-0 mt-4">
        <a href="<?= url_to('create_course') ?>" class="btn btn-primary w-full lg:w-auto">
            <i class="fa fa-plus mr-2"></i>Add Course
        </a>
    </div>

    <?php if (empty($teacherCourses)) : ?>
    <div class="p-4 md:px-0">
        <div class="divider">
            <div class="divider-title">No Courses</div>
        </div>
    </div>
    <?php endif ?>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 p-4 md:px-0">
        <?php foreach ($teacherCourses as $teacherCourse): ?>
        <div class="card bg-base-100 shadow-md border border-gray-200">
            <figure>
                <div class="relative">
                    <img src="<?= base_url('images/bg_card_courses.png') ?>" alt="Course Image"
                        class="w-full h-auto rounded-t-lg">
                    <span
                        class="absolute inset-0 flex items-center justify-center text-xl font-semibold text-blue-800 bg-white/50">
                        <?= esc($teacherCourse->name) ?>
                    </span>
                </div>
            </figure>

            <div class="card-body flex flex-col justify-between">
                <div class="space-y-2">
                    <h2 class="card-title text-lg font-semibold text-primary">
                        <?= esc($teacherCourse->code) ?> - <?= esc($teacherCourse->name) ?>
                    </h2>

                    <?php 
                        $short = esc(substr($teacherCourse->description, 0, 100));
                        $full = esc($teacherCourse->description);
                        $hasMore = strlen($teacherCourse->description) > 100;
                    ?>
                    <div class="text-sm text-gray-600 min-h-[80px]">
                        <?php if ($hasMore): ?>
                        <span class="short"><?= $short ?>...</span>
                        <span class="full hidden"><?= $full ?></span>
                        <button type="button" class="text-blue-500 text-sm toggle-desc hover:underline">View
                            More...</button>
                        <?php else: ?>
                        <?= $full ?>
                        <?php endif ?>
                    </div>

                    <div class="text-sm text-gray-500">
                        <p><strong>Duration:</strong> <?= esc($teacherCourse->expected_duration) ?> months</p>
                        <p><strong>Level:</strong> <?= esc($teacherCourse->levelName) ?></p>
                    </div>
                </div>

                <div class="flex justify-end gap-2">
                    <a href="<?= url_to('show_course', $teacherCourse->id) ?>" class="btn btn-primary btn-sm">
                        <i class="fa-solid fa-circle-info"></i>
                    </a>
                    <a href="<?= url_to('edit_course', $teacherCourse->id) ?>" class="btn btn-warning btn-sm">
                        <i class="fa fa-edit text-white"></i>
                    </a>
                    <button type="button" class="btn btn-error btn-sm text-white"
                        onclick="document.getElementById('deleteModal<?= $teacherCourse->id ?>').showModal()">
                        <i class="fa fa-trash"></i>
                    </button>
                    <dialog id="deleteModal<?= $teacherCourse->id ?>" class="modal">
                        <div class="modal-box">
                            <h3 class="font-bold text-lg text-red-600">Delete Confirmation</h3>
                            <p class="py-4">Are you sure you want to delete this course?</p>
                            <div class="modal-action">
                                <form method="dialog">
                                    <button class="btn btn-error text-white">Cancel</button>
                                </form>
                                <form action="<?= url_to('delete_course', $teacherCourse->id) ?>" method="post">
                                    <?= csrf_field() ?>
                                    <input type="hidden" name="_method" value="DELETE">
                                    <button type="submit" class="btn btn-success text-white">Yes, Delete</button>
                                </form>
                            </div>
                        </div>
                    </dialog>
                </div>
            </div>

        </div>
        <?php endforeach; ?>
    </div>

    <div class="flex justify-center mt-2">
        <?= $pager->links('teacherCourses', 'custom_pager') ?>
    </div>
    <div class="text-center mt-2">
        <small>Displaying <?= count($teacherCourses) ?> out of <?= $total ?> total courses (Page
            <?= $params->page ?>)</small>
    </div>
</div>

<script>
document.querySelectorAll('.toggle-desc').forEach(button => {
    button.addEventListener('click', function() {
        const short = this.previousElementSibling.previousElementSibling;
        const full = this.previousElementSibling;
        const isHidden = full.classList.contains('hidden');

        short.classList.toggle('hidden', isHidden);
        full.classList.toggle('hidden', !isHidden);
        this.textContent = isHidden ? 'Close' : 'View More...';
    });
});
</script>

<?= $this->endSection() ?>