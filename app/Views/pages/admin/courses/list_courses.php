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

<div class="flex justify-between gap-2 mb-2">
    <a href="<?= site_url('courses/add') ?>" class="btn btn-primary">
        <i class="fa fa-plus mr-2"></i>Add Course
    </a>

    <div class="flex-grow mb-4">
        <form method="get" action="<?= url_to('list_courses') ?>" class="flex gap-2 w-full">
            <input type="text" name="search" placeholder="Search by Course Code, Enrollment code, description, name..."
                value="<?= esc($params->search) ?>" class="input input-bordered flex-grow" />
            <button type="submit" class="btn btn-primary">Search</button>
        </form>
    </div>

    <div>
        <a href="<?= $params->getResetUrl('courses') ?>" class="btn btn-info text-white">Reset</a>
    </div>
</div>

<div class="overflow-x-auto rounded-box border border-gray-300">
    <table class="table">
        <thead>
            <tr>
                <th>#</th>
                <th>Course Code</th>
                <th>Enrollment Code</th>
                <th>Name</th>
                <th>Description</th>
                <th>Expected Duration</th>
                <th>Level Name</th>
                <th>Created At</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($courses)) : ?>
                <tr>
                    <td colspan="9" class="text-center">No courses found</td>
                </tr>
            <?php else : ?>
                <?php $no = 1; ?>
                <?php foreach ($courses as $course) : ?>
                    <tr class="hover:bg-gray-200">
                        <td><?= $no++ ?></td>
                        <td><?= $course->code ?></td>
                        <td><?= $course->enrollment_code ?></td>
                        <td><?= $course->name ?></td>
                        <td><?= $course->description ?></td>
                        <td><?= $course->expected_duration ?> Months</td>
                        <td><?= $course->levelName ?></td>
                        <td><?= $course->created_at ?></td>
                        <td class="flex gap-2">
                            <a href="<?= site_url('courses/edit/' . $course->id) ?>" class="btn btn-warning btn-sm">
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
                                        <form action="<?= site_url('courses/delete/' . $course->id) ?>" method="post">
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
</div>
<div class="mt-4 flex justify-center">
    <?= $pager->links('courses', 'custom_pager') ?>
</div>


<?= $this->endSection() ?>