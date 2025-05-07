<?= $this->extend('layouts/admin_layout') ?>

<?= $this->section('title') ?>
<?= $page_title ?>
<?= $this->endSection() ?>

<?= $this->section('admin_content') ?>

<!-- Breadcrumbs -->
<div class="bg-gray-200 rounded-md px-4">
    <div class="breadcrumbs mb-6">
        <ul>
            <li><a href="<?= base_url('admin/dashboard') ?>">Dashboard</a></li>
            <li class="font-semibold">Courses</li>
        </ul>
    </div>
</div>

<div class="mb-4">
    <?php if (session()->has('success')) : ?>
        <div role="alert" class="alert alert-success">
            <span><i class="fa fa-check mr-2"></i> <?= session('success') ?></span>
        </div>
    <?php endif ?>
</div>

<div class="flex flex-col md:flex-row md:justify-between gap-2 mb-2">
    <div class="flex gap-2 md:flex-grow">
        <div class="flex-grow">
            <form method="get" action="<?= url_to('admin_courses') ?>" class="w-full">
                <label class="input flex items-center w-full">
                    <i class="fa fa-search mr-2"></i>
                    <input type="text" placeholder="Search by Code or Name" value="<?= $params->search ?>" name="search"
                        class="w-full focus:outline-none" />
                </label>
            </form>
        </div>
    </div>

    <form method="get" class="flex flex-wrap justify-between gap-2">
        <div>
            <select name="perPage" class="select w-full" onchange="this.form.submit()">
                <?php foreach ([4, 10, 25, 50, 100] as $option): ?>
                    <option value="<?= $option ?>" <?= ($params->perPage == $option) ? 'selected' : '' ?>>
                        Show <?= $option ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div>
            <select name="sort" class="select select-bordered w-full" onchange="this.form.submit()">
                <option value="">Sort By</option>
                <option value="code" <?= ($params->sort == 'code') ? 'selected' : '' ?>>Code</option>
                <option value="enrollment_code" <?= ($params->sort == 'enrollment_code') ? 'selected' : '' ?>>Enrollment
                    Code</option>
                <option value="name" <?= ($params->sort == 'name') ? 'selected' : '' ?>>Name</option>
                <option value="created_at" <?= ($params->sort == 'created_at') ? 'selected' : '' ?>>Created At</option>
                <option value="levelName" <?= ($params->sort == 'levelName') ? 'selected' : '' ?>>Level</option>
            </select>
        </div>
        <div>
            <select name="order" class="select select-bordered w-full" onchange="this.form.submit()">
                <option value="asc" <?= ($params->order == 'asc') ? 'selected' : '' ?>>Ascending</option>
                <option value="desc" <?= ($params->order == 'desc') ? 'selected' : '' ?>>Descending</option>
            </select>
        </div>
        <div>
            <a href="<?= $params->getResetUrl($baseUrl) ?>" class="btn btn-info text-white">
                Reset
            </a>
        </div>
    </form>
</div>



<div class="overflow-x-auto rounded-box border border-gray-300">
    <table class="table">
        <thead>
            <tr>
                <th>#</th>
                <th>ID</th>
                <th>Code</th>
                <th>Enrollment Code</th>
                <th>Name</th>
                <th>Level</th>
                <th>Created At</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($courses)) : ?>
                <tr>
                    <td colspan="7" class="text-center">No courses found</td>
                </tr>
            <?php else : ?>
                <?php $no = ($params->page - 1) * $params->perPage + 1; ?>
                <?php foreach ($courses as $course) : ?>
                    <tr class="hover:bg-gray-200">
                        <td><?= $no++ ?></td>
                        <td><?= $course->id ?></td>
                        <td><?= $course->code ?></td>
                        <td><?= $course->enrollment_code ?></td>
                        <td><?= $course->name ?></td>
                        <td><?= $course->levelName ?></td>
                        <td><?= $course->created_at ?></td>
                        <td class="flex gap-2">
                            <a href="<?= url_to('show_course', $course->id) ?>" class="btn btn-info btn-sm">
                                <i class="fa fa-eye text-white"></i>
                            </a>
                            <a href="<?= url_to('edit_course', $course->id) ?>" class="btn btn-warning btn-sm">
                                <i class="fa fa-edit text-white"></i>
                            </a>
                            <button type="button" class="btn btn-error btn-sm text-white"
                                onclick="document.getElementById('deleteModal<?= $course->id ?>').showModal()">
                                <i class="fa fa-trash"></i>
                            </button>
                            <dialog id="deleteModal<?= $course->id ?>" class="modal">
                                <div class="modal-box">
                                    <h3 class="font-bold text-lg text-red-600">Delete Confirmation</h3>
                                    <p class="py-4">Are you sure you want to delete this course?</p>
                                    <div class="modal-action">
                                        <form method="dialog">
                                            <button class="btn btn-error text-white">Cancel</button>
                                        </form>
                                        <form action="<?= url_to('delete_course', $course->id) ?>" method="post">
                                            <?= csrf_field() ?>
                                            <input type="hidden" name="_method" value="DELETE">
                                            <button type="submit" class="btn btn-success text-white">Yes, Delete</button>
                                        </form>
                                    </div>
                                </div>
                            </dialog>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>

    <div class="flex justify-center mt-2">
        <?= $pager->links('courses', 'custom_pager') ?>
    </div>
    <div class="text-center mt-2">
        <small>Displaying <?= count($courses) ?> out of <?= $total ?> total courses (Page <?= $params->page ?>)</small>
    </div>
</div>

<?= $this->endSection() ?>